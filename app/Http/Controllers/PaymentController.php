<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Webhook as StripeWebhook;

class PaymentController extends Controller
{
    // Loads the payment checkout page for an unpaid service request belonging to the authenticated user.
    public function checkout($requestId)
    {
        $user           = auth('users')->user();
        $serviceRequest = ServiceRequest::with(['service.business', 'service.category'])
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->firstOrFail();

        $payment = $serviceRequest->payment;

        return view('payment.checkout', compact('serviceRequest', 'payment'));
    }

    // Creates a Stripe hosted-checkout session for the service request and redirects the user to it.
    public function createStripeSession($requestId)
    {
        $user           = auth('users')->user();
        $serviceRequest = ServiceRequest::with('service')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->firstOrFail();

        $service = $serviceRequest->service;
        $total   = $service->price_usd * $serviceRequest->quantity;

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'usd',
                    'product_data' => ['name' => $service->title],
                    'unit_amount'  => (int) round($service->price_usd * 100),
                ],
                'quantity' => $serviceRequest->quantity,
            ]],
            'mode'        => 'payment',
            'success_url' => route('payment.stripe.success', [
                'requestId'  => $requestId,
                'session_id' => '{CHECKOUT_SESSION_ID}',
            ]),
            'cancel_url'  => route('payment.checkout', $requestId),
            'metadata'    => [
                'service_request_id' => $requestId,
                'user_id'            => $user->id,
            ],
        ]);

        Payment::updateOrCreate(
            ['service_request_id' => $requestId],
            [
                'user_id'                    => $user->id,
                'amount'                     => $total,
                'currency'                   => 'USD',
                'payment_method'             => 'stripe',
                'status'                     => 'pending',
                'stripe_checkout_session_id' => $session->id,
            ]
        );

        return redirect($session->url);
    }

    // Retrieves the Stripe session after redirect, marks payment as paid if successful, and routes accordingly.
    public function stripeSuccess(Request $request, $requestId)
    {
        $sessionId = $request->get('session_id');

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = StripeSession::retrieve($sessionId);

        if ($session->payment_status === 'paid') {
            Payment::where('service_request_id', $requestId)->update([
                'status'         => 'paid',
                'transaction_id' => $session->payment_intent,
                'paid_at'        => now(),
            ]);
            ServiceRequest::where('id', $requestId)->update(['payment_status' => 'paid']);

            return redirect()->route('payment.success', $requestId);
        }

        return redirect()->route('payment.failed', $requestId);
    }

    // Creates a PayPal order for the service request and returns the PayPal order ID as JSON.
    public function createPaypalOrder($requestId)
    {
        $user           = auth('users')->user();
        $serviceRequest = ServiceRequest::with('service')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->firstOrFail();

        $service = $serviceRequest->service;
        $total   = number_format($service->price_usd * $serviceRequest->quantity, 2, '.', '');

        $accessToken = $this->getPaypalAccessToken();

        $response = Http::withToken($accessToken)
            ->post($this->paypalBaseUrl() . '/v2/checkout/orders', [
                'intent'         => 'CAPTURE',
                'purchase_units' => [[
                    'amount'      => ['currency_code' => 'USD', 'value' => $total],
                    'description' => $service->title,
                ]],
            ]);

        $orderId = $response->json('id');

        Payment::updateOrCreate(
            ['service_request_id' => $requestId],
            [
                'user_id'         => $user->id,
                'amount'          => $service->price_usd * $serviceRequest->quantity,
                'currency'        => 'USD',
                'payment_method'  => 'paypal',
                'status'          => 'pending',
                'paypal_order_id' => $orderId,
            ]
        );

        return response()->json(['id' => $orderId]);
    }

    // Captures an approved PayPal order and marks the payment and service request as paid.
    public function capturePaypalOrder(Request $request, $requestId)
    {
        $orderId     = $request->input('orderID');
        $accessToken = $this->getPaypalAccessToken();

        $response = Http::withToken($accessToken)
            ->post($this->paypalBaseUrl() . "/v2/checkout/orders/{$orderId}/capture");

        if ($response->json('status') === 'COMPLETED') {
            Payment::where('service_request_id', $requestId)->update([
                'status'         => 'paid',
                'transaction_id' => $orderId,
                'paid_at'        => now(),
            ]);
            ServiceRequest::where('id', $requestId)->update(['payment_status' => 'paid']);

            return response()->json([
                'success'  => true,
                'redirect' => route('payment.success', $requestId),
            ]);
        }

        return response()->json(['success' => false], 422);
    }

    // Accepts a bank transfer proof image, records the payment as paid, and redirects to the success page.
    public function processBankTransfer(Request $request, $requestId)
    {
        $user           = auth('users')->user();
        $serviceRequest = ServiceRequest::with('service')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->firstOrFail();

        $request->validate([
            'proof_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'currency'    => 'required|in:USD,SYP',
        ]);

        $proofPath = $request->file('proof_image')->store('payment_proofs', 'public');
        $service   = $serviceRequest->service;
        $currency  = strtoupper($request->currency);
        $qty       = $serviceRequest->quantity ?? 1;
        $amount    = $currency === 'SYP'
            ? $service->price_syp * $qty
            : $service->price_usd * $qty;

        Payment::updateOrCreate(
            ['service_request_id' => $requestId],
            [
                'user_id'         => $user->id,
                'amount'          => $amount,
                'currency'        => $currency,
                'payment_method'  => 'bank_transfer',
                'status'          => 'paid',
                'proof_image'     => $proofPath,
                'paid_at'         => now(),
            ]
        );

        $serviceRequest->update(['payment_status' => 'paid']);

        return redirect()->route('payment.success', $requestId);
    }

    // Simulates a completed payment in test mode, bypassing real gateways, and marks the request as paid.
    public function processTest(Request $request, $requestId)
    {
        abort_unless(config('payment.test_mode'), 403, 'Test mode is disabled.');

        $request->validate(['currency' => 'nullable|in:USD,SYP']);

        $user           = auth('users')->user();
        $serviceRequest = ServiceRequest::with('service')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->firstOrFail();

        $service  = $serviceRequest->service;
        $currency = strtoupper($request->currency ?? 'USD');
        $qty      = $serviceRequest->quantity ?? 1;
        $amount   = $currency === 'SYP'
            ? $service->price_syp * $qty
            : $service->price_usd * $qty;

        Payment::updateOrCreate(
            ['service_request_id' => $requestId],
            [
                'user_id'         => $user->id,
                'amount'          => $amount,
                'currency'        => $currency,
                'payment_method'  => 'test',
                'status'          => 'paid',
                'transaction_id'  => 'TEST-' . strtoupper(uniqid()),
                'paid_at'         => now(),
            ]
        );

        $serviceRequest->update(['payment_status' => 'paid']);

        return redirect()->route('payment.success', $requestId);
    }

    // Handles Stripe webhook events, marking the associated payment and service request as paid on completion.
    public function stripeWebhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secret    = config('services.stripe.webhook_secret');

        try {
            $event = StripeWebhook::constructEvent($payload, $sigHeader, $secret);
        } catch (\Exception $e) {
            return response('Webhook error: ' . $e->getMessage(), 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session   = $event->data->object;
            $requestId = $session->metadata->service_request_id;

            Payment::where('service_request_id', $requestId)->update([
                'status'         => 'paid',
                'transaction_id' => $session->payment_intent,
                'paid_at'        => now(),
            ]);
            ServiceRequest::where('id', $requestId)->update(['payment_status' => 'paid']);
        }

        return response('OK', 200);
    }

    // Loads the payment success page for a completed service request.
    public function success($requestId)
    {
        $user           = auth('users')->user();
        $serviceRequest = ServiceRequest::with(['service.business', 'payment'])
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('payment.success', compact('serviceRequest'));
    }

    // Loads the payment failure page for a service request whose payment did not complete.
    public function failed($requestId)
    {
        $user           = auth('users')->user();
        $serviceRequest = ServiceRequest::with('service')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('payment.failed', compact('serviceRequest'));
    }

    // Returns the correct PayPal API base URL depending on live or sandbox mode.
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
}
