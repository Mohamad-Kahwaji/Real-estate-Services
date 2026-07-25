<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\UserDatabaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Refund as StripeRefund;

class ServiceRequestController extends Controller
{
    // Returns all approved services not belonging to the user's own businesses, with favorites pre-loaded.
    public function allservices()
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $services = Service::with(['business.user', 'business.activeType', 'business.city', 'category'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('status', 'approved')
            ->whereNotIn('business_id', $businessIds)
            ->latest()
            ->get();

        $favorites = $user->favorites()->pluck('service_id')->toArray();

        return view('users.allservice', compact('services', 'favorites'));
    }

    // Validates and creates a service request for an approved service, then redirects to payment checkout.
    public function requestservice(Request $request, $id)
    {
        $user = auth('users')->user();
        $request->validate([
            'quantity'  => 'required|integer|min:1',
            'needed_at' => 'required|date|after:today',
            'details'   => 'nullable|string',
        ]);

        $myBusiness = $user->businesses()->where('status', 'approved')->first();
        if (!$myBusiness) {
            return back()->with('error', 'You need an approved business account to request services.');
        }

        $service = Service::findOrFail($id);

        if ($service->business_id === $myBusiness->id) {
            return back()->with('error', 'You cannot request your own business service.');
        }

        $serviceRequest = ServiceRequest::create([
            'user_id'        => $user->id,
            'service_id'     => $id,
            'business_id'    => Service::findOrFail($id)->business_id,
            'quantity'       => $request->quantity,
            'needed_at'      => $request->needed_at,
            'details'        => $request->details,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('payment.checkout', $serviceRequest->id);
    }

    // Lists all service requests sent by the authenticated user along with payment status.
    public function sentservice()
    {
        /** @var User $user */
        $user     = auth('users')->user();
        $requests = ServiceRequest::with([
                'service' => fn($q) => $q->withTrashed(),
                'service.business' => fn($q) => $q->withTrashed(),
                'service.category',
                'payment',
            ])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('users.sentservice', compact('requests'));
    }

    // Lists paid, pending service requests directed at the authenticated user's businesses.
    public function incoming()
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $requests = ServiceRequest::with(['service.business', 'service.category', 'payment'])
            ->where('status', 'pending')
            ->where('payment_status', 'paid')
            ->whereHas('service', fn ($q) => $q->whereIn('business_id', $businessIds))
            ->latest()
            ->get();

        return view('users.servicerequest', compact('requests'));
    }

    // Approves a paid service request belonging to the user's business and notifies the requester.
    public function approve($id)
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $serviceRequest = ServiceRequest::with(['service.category', 'service.business'])
            ->where('id', $id)
            ->where('payment_status', 'paid')
            ->whereHas('service', fn ($q) => $q->whereIn('business_id', $businessIds))
            ->firstOrFail();

        $serviceRequest->update(['status' => 'approved']);

        $serviceRequest->user->notify(new UserDatabaseNotification(
            'Service Approved',
            'Your service request has been approved.',
            ['type' => 'service', 'service_id' => $serviceRequest->service_id]
        ));

        return redirect()->route('incoming.user')
            ->with('success', 'Request approved successfully.');
    }

    // Rejects a pending service request and automatically issues a refund if the payment was already captured.
    public function reject($id)
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $serviceRequest = ServiceRequest::with('payment')
            ->where('id', $id)
            ->where('status', 'pending')
            ->whereHas('service', fn ($q) => $q->whereIn('business_id', $businessIds))
            ->firstOrFail();

        $serviceRequest->update(['status' => 'rejected']);

        $payment = $serviceRequest->payment;
        if ($payment && $payment->status === 'paid') {
            $refunded = $this->issueRefund($payment);

            $payment->update(['status' => $refunded ? 'refunded' : 'refund_pending']);
            $serviceRequest->update(['payment_status' => 'refunded']);
        }

        $serviceRequest->user->notify(new UserDatabaseNotification(
            'Service Rejected',
            $payment && $payment->status !== 'paid'
                ? 'Your service request has been rejected. Your payment will be refunded.'
                : 'Your service request has been rejected.',
            ['type' => 'service', 'service_id' => $serviceRequest->service_id]
        ));

        return redirect()->route('incoming.user')
            ->with('success', 'Request rejected.' . ($payment && $payment->status !== 'pending' ? ' Refund initiated.' : ''));
    }

    // Attempts a gateway refund (Stripe or PayPal) for the given payment, returning true on success.
    private function issueRefund(Payment $payment): bool
    {
        try {
            if ($payment->payment_method === 'stripe' && $payment->transaction_id) {
                Stripe::setApiKey(config('services.stripe.secret'));
                StripeRefund::create(['payment_intent' => $payment->transaction_id]);
                return true;
            }

            if ($payment->payment_method === 'paypal' && $payment->transaction_id) {
                $accessToken = $this->getPaypalAccessToken();
                $response = Http::withToken($accessToken)
                    ->post($this->paypalBaseUrl() . "/v2/payments/captures/{$payment->transaction_id}/refund", [
                        'amount' => [
                            'value'         => number_format($payment->amount, 2, '.', ''),
                            'currency_code' => $payment->currency,
                        ],
                    ]);
                return $response->successful();
            }

            // Bank transfer & test: mark as refunded (manual process for bank)
            return true;

        } catch (\Throwable $e) {
            Log::error('Refund failed for payment #' . $payment->id . ': ' . $e->getMessage());
            return false;
        }
    }

    // Returns the PayPal API base URL for either live or sandbox mode.
    private function paypalBaseUrl(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    // Fetches a short-lived PayPal OAuth access token using client credentials.
    private function getPaypalAccessToken(): string
    {
        $response = Http::withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.client_secret')
        )->asForm()->post($this->paypalBaseUrl() . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials',
        ]);

        return $response->json('access_token');
    }

    // Lists approved service requests received by the user's business (completed transactions).
    public function received()
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $requests = ServiceRequest::with(['service.category', 'service.business', 'user', 'payment'])
            ->where('status', 'approved')
            ->whereHas('service', fn ($q) => $q->whereIn('business_id', $businessIds))
            ->latest()
            ->get();

        return view('users.servicereceived', compact('requests'));
    }

    // Lists the current user's own service requests that have been approved.
    public function approverequest()
    {
        /** @var User $user */
        $user     = auth('users')->user();
        $requests = ServiceRequest::with([
                'service' => fn($q) => $q->withTrashed(),
                'service.business' => fn($q) => $q->withTrashed(),
                'service.category',
                'payment',
            ])
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('users.servicereceived', compact('requests'));
    }

    // Approves a paid service request by ID (admin/superadmin action) and notifies the requesting user.
    public function approverec($id)
    {
        $serviceRequest = ServiceRequest::where('id', $id)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $serviceRequest->update(['status' => 'approved']);

        $serviceRequest->user->notify(new UserDatabaseNotification(
            'Service Approved',
            'Your service request has been approved.',
            ['type' => 'service', 'service_id' => $serviceRequest->service_id]
        ));

        return back()->with('success', 'Request approved.');
    }
}
