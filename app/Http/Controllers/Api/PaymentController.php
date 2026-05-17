<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    // ─── GET payment details for a request ───────────────────────────────────
    public function show($requestId)
    {
        $user           = request()->user();
        $serviceRequest = ServiceRequest::with(['service.business', 'service.category', 'payment'])
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $service = $serviceRequest->service;
        $qty     = $serviceRequest->quantity ?? 1;

        return response()->json([
            'status' => true,
            'data'   => [
                'request_id'     => $serviceRequest->id,
                'payment_status' => $serviceRequest->payment_status,
                'service'        => [
                    'id'        => $service->id,
                    'title'     => $service->title,
                    'price_usd' => $service->price_usd,
                    'price_syp' => $service->price_syp,
                ],
                'quantity'   => $qty,
                'total_usd'  => $service->price_usd * $qty,
                'total_syp'  => $service->price_syp * $qty,
                'needed_at'  => $serviceRequest->needed_at,
                'payment'    => $serviceRequest->payment,
                // available_methods tells the client what to show
                'available_methods' => [
                    ['method' => 'stripe',        'currencies' => ['USD'], 'label' => 'Credit / Debit Card (Stripe)'],
                    ['method' => 'paypal',         'currencies' => ['USD'], 'label' => 'PayPal'],
                    ['method' => 'bank_transfer',  'currencies' => ['USD', 'SYP'], 'label' => 'Bank Transfer'],
                ],
                'bank_info'  => [
                    'bank_name'      => config('payment.bank_name'),
                    'account_name'   => config('payment.bank_account_name'),
                    'account_number' => config('payment.bank_account_number'),
                ],
            ],
        ]);
    }

    // ─── Stripe: create PaymentIntent (returns client_secret for Flutter SDK) ─
    public function createStripeIntent($requestId)
    {
        $user           = request()->user();
        $serviceRequest = ServiceRequest::with('service')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->firstOrFail();

        $service      = $serviceRequest->service;
        $qty          = $serviceRequest->quantity ?? 1;
        $amountCents  = (int) round($service->price_usd * $qty * 100);

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount'   => $amountCents,
            'currency' => 'usd',
            'metadata' => [
                'service_request_id' => $requestId,
                'user_id'            => $user->id,
            ],
        ]);

        Payment::updateOrCreate(
            ['service_request_id' => $requestId],
            [
                'user_id'          => $user->id,
                'amount'           => $service->price_usd * $qty,
                'currency'         => 'USD',
                'payment_method'   => 'stripe',
                'status'           => 'pending',
                'transaction_id'   => $intent->id,
            ]
        );

        return response()->json([
            'status'        => true,
            'client_secret' => $intent->client_secret,
            'payment_intent_id' => $intent->id,
            'amount'        => $amountCents,
            'currency'      => 'usd',
        ]);
    }

    // ─── Stripe: confirm payment (called by Flutter after SDK confirms) ────────
    public function confirmStripe(Request $request, $requestId)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::retrieve($request->payment_intent_id);

        if ($intent->status === 'succeeded') {
            Payment::where('service_request_id', $requestId)->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);
            ServiceRequest::where('id', $requestId)->update(['payment_status' => 'paid']);

            return response()->json([
                'status'  => true,
                'message' => 'Payment confirmed successfully.',
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Payment not yet succeeded. Status: ' . $intent->status,
        ], 422);
    }

    // ─── PayPal: create order ─────────────────────────────────────────────────
    public function createPaypalOrder($requestId)
    {
        $user           = request()->user();
        $serviceRequest = ServiceRequest::with('service')
            ->where('id', $requestId)
            ->where('user_id', $user->id)
            ->where('payment_status', 'unpaid')
            ->firstOrFail();

        $service = $serviceRequest->service;
        $qty     = $serviceRequest->quantity ?? 1;
        $total   = number_format($service->price_usd * $qty, 2, '.', '');

        $accessToken = $this->paypalAccessToken();

        $response = Http::withToken($accessToken)
            ->post($this->paypalBase() . '/v2/checkout/orders', [
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
                'amount'          => $service->price_usd * $qty,
                'currency'        => 'USD',
                'payment_method'  => 'paypal',
                'status'          => 'pending',
                'paypal_order_id' => $orderId,
            ]
        );

        return response()->json([
            'status'   => true,
            'order_id' => $orderId,
        ]);
    }

    // ─── PayPal: capture order ────────────────────────────────────────────────
    public function capturePaypalOrder(Request $request, $requestId)
    {
        $request->validate(['order_id' => 'required|string']);

        $accessToken = $this->paypalAccessToken();
        $response    = Http::withToken($accessToken)
            ->post($this->paypalBase() . "/v2/checkout/orders/{$request->order_id}/capture");

        if ($response->json('status') === 'COMPLETED') {
            Payment::where('service_request_id', $requestId)->update([
                'status'         => 'paid',
                'transaction_id' => $request->order_id,
                'paid_at'        => now(),
            ]);
            ServiceRequest::where('id', $requestId)->update(['payment_status' => 'paid']);

            return response()->json([
                'status'  => true,
                'message' => 'PayPal payment captured successfully.',
            ]);
        }

        return response()->json(['status' => false, 'message' => 'Capture failed.'], 422);
    }

    // ─── Bank Transfer: upload receipt ───────────────────────────────────────
    // Accepts currency: USD or SYP (Stripe/PayPal only support USD)
    public function bankTransfer(Request $request, $requestId)
    {
        $user           = $request->user();
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
                'user_id'        => $user->id,
                'amount'         => $amount,
                'currency'       => $currency,
                'payment_method' => 'bank_transfer',
                'status'         => 'paid',
                'proof_image'    => $proofPath,
                'paid_at'        => now(),
            ]
        );

        $serviceRequest->update(['payment_status' => 'paid']);

        return response()->json([
            'status'  => true,
            'message' => 'Receipt uploaded. Your request is now pending approval.',
        ]);
    }

    // ─── Test payment (only when test mode is on) ─────────────────────────────
    public function testPay(Request $request, $requestId)
    {
        abort_unless(config('payment.test_mode'), 403, 'Test mode is disabled.');

        $request->validate(['currency' => 'nullable|in:USD,SYP']);

        $user           = $request->user();
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
                'user_id'        => $user->id,
                'amount'         => $amount,
                'currency'       => $currency,
                'payment_method' => 'test',
                'status'         => 'paid',
                'transaction_id' => 'TEST-' . strtoupper(uniqid()),
                'paid_at'        => now(),
            ]
        );

        $serviceRequest->update(['payment_status' => 'paid']);

        return response()->json([
            'status'  => true,
            'message' => 'Test payment successful. Request is now pending approval.',
        ]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────
    private function paypalBase(): string
    {
        return config('services.paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    private function paypalAccessToken(): string
    {
        $res = Http::withBasicAuth(
            config('services.paypal.client_id'),
            config('services.paypal.client_secret')
        )->asForm()->post($this->paypalBase() . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials',
        ]);

        return $res->json('access_token');
    }
}