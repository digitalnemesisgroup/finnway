<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentClient;
use App\Models\PaymentRequest;
use App\Services\PhonePeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * PaymentHubController
 *
 * Single API endpoint that any authorised client (internal or external) calls
 * to initiate a payment. Returns a payment_url the client redirects the user to.
 *
 * Authentication: api_key header + HMAC-SHA256 signature of the payload.
 * Idempotency:    Duplicate client_order_id returns the existing payment_url.
 */
class PaymentHubController extends Controller
{
    public function initiate(Request $request)
    {
        // ── 1. Validate required fields ───────────────────────────────────────
        $validated = $request->validate([
            'client_order_id'  => 'required|string|max:100',
            'amount'           => 'required|numeric|min:1',
            'customer_phone'   => 'nullable|string|max:20',
            'customer_email'   => 'nullable|email|max:255',
            'return_url'       => 'required|url',
            'webhook_url'      => 'required|url',
            'signature'        => 'required|string',
        ]);

        // ── 2. Resolve client from api_key header ─────────────────────────────
        $apiKey = $request->header('X-Api-Key');
        if (!$apiKey) {
            return response()->json(['error' => 'Missing X-Api-Key header'], 401);
        }

        $client = PaymentClient::where('api_key', $apiKey)->first();
        if (!$client) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }
        if (!$client->isLive()) {
            $reason = $client->isExpired() ? 'API key has expired. Please renew your subscription.' : 'API key is inactive or not approved.';
            return response()->json(['error' => $reason], 403);
        }

        // ── 3. Verify HMAC signature ──────────────────────────────────────────
        // Canonical string: client_order_id|amount|return_url
        // Client must hash this with their api_salt using SHA-256 HMAC.
        $canonicalString     = $validated['client_order_id'] . '|' . $validated['amount'] . '|' . $validated['return_url'];
        $expectedSignature   = hash_hmac('sha256', $canonicalString, $client->api_salt);

        if (!hash_equals($expectedSignature, $validated['signature'])) {
            Log::warning("PaymentHub: Invalid signature from client [{$client->name}] for order [{$validated['client_order_id']}]");
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // ── 4. Idempotency: return existing request if it already exists ───────
        $existing = PaymentRequest::where('payment_client_id', $client->id)
            ->where('client_order_id', $validated['client_order_id'])
            ->first();

        if ($existing) {
            Log::info("PaymentHub: Idempotent return for existing PaymentRequest #{$existing->id}");
            $sessionService = app(\App\Services\PaymentSessionService::class);
            $token = $existing->session_token ?: $sessionService->createSession($existing, 'hub');
            return response()->json([
                'payment_request_id' => $existing->id,
                'payment_url'        => route('hub.checkout', ['token' => $token]),
                'status'             => $existing->payment_status,
            ]);
        }

        // ── 5. Create PaymentRequest record ───────────────────────────────────
        $paymentRequest = PaymentRequest::create([
            'payment_client_id' => $client->id,
            'client_order_id'   => $validated['client_order_id'],
            'amount'            => $validated['amount'],
            'currency'          => 'INR',
            'customer_phone'    => $validated['customer_phone'] ?? '9999999999',
            'customer_email'    => $validated['customer_email'] ?? null,
            'return_url'        => $validated['return_url'],
            'webhook_url'       => $validated['webhook_url'],
            'payment_status'    => 'pending',
        ]);

        // Create encrypted session token
        $sessionService = app(\App\Services\PaymentSessionService::class);
        $sessionToken = $sessionService->createSession($paymentRequest, 'hub');

        // ── 6. Create Provider order ──────────────────────────────────────────
        $providerOrderId = 'hub_' . $paymentRequest->id . '_' . Str::random(5);
        $isPhonepe = $request->is('*phonepe*');

        try {
            if ($isPhonepe) {
                $phonePeService = app(PhonePeService::class);
                
                $phonePeOrderData = [
                    'transaction_id' => $providerOrderId,
                    'user_id' => 'hub_usr_' . $paymentRequest->id,
                    'amount' => $paymentRequest->amount,
                    'redirect_url' => route('hub.verify', $paymentRequest->id),
                    'callback_url' => route('webhook.phonepe'),
                    'phone' => $paymentRequest->customer_phone ?? '9999999999',
                ];

                $result = $phonePeService->createOrder($phonePeOrderData);
                $cashfreeSessionId = $result['redirect_url']; // Provider redirect link
                $redirectUrl = route('hub.checkout', ['token' => $sessionToken]);
            } else {
                \Cashfree\Cashfree::$XClientId = config('services.cashfree.key_id', 'demo');
                \Cashfree\Cashfree::$XClientSecret = config('services.cashfree.key_secret', 'demo');
                \Cashfree\Cashfree::$XEnvironment = config('services.cashfree.mode', 'sandbox') === 'production' 
                                            ? \Cashfree\Cashfree::$PRODUCTION 
                                            : \Cashfree\Cashfree::$SANDBOX;

                $cfReq = new \Cashfree\Model\CreateOrderRequest();
                $cfReq->setOrderAmount(round((float)$paymentRequest->amount, 2));
                $cfReq->setOrderCurrency("INR");
                $cfReq->setOrderId($providerOrderId);

                $cfCust = new \Cashfree\Model\CustomerDetails();
                $cfCust->setCustomerId('hub_usr_' . $paymentRequest->id);
                $cfCust->setCustomerPhone($paymentRequest->customer_phone ?? '9999999999');
                $cfReq->setCustomerDetails($cfCust);

                $cfMeta = new \Cashfree\Model\OrderMeta();
                $cfMeta->setReturnUrl(route('hub.verify', $paymentRequest->id) . '?order_id={order_id}');
                $cfMeta->setNotifyUrl(route('webhooks.cashfree')); // Send webhook to Cashfree handler
                $cfReq->setOrderMeta($cfMeta);

                $cf = new \Cashfree\Cashfree();
                $result = $cf->PGCreateOrder("2023-08-01", $cfReq);
                
                $cashfreeSessionId = $result[0]->getPaymentSessionId();
                $redirectUrl = route('hub.checkout', ['token' => $sessionToken]);
            }

        } catch (\Exception $e) {
            $paymentRequest->delete(); // Clean up on Gateway failure
            Log::error("PaymentHub: Gateway order creation failed for PaymentRequest #{$paymentRequest->id}: " . $e->getMessage());
            return response()->json(['error' => 'Payment gateway unavailable. Please try again.'], 503);
        }

        // ── 7. Persist Provider session details ───────────────────────────────
        $paymentRequest->update([
            'cashfree_order_id'  => $providerOrderId,
            'cashfree_session_id' => $cashfreeSessionId,
        ]);

        Log::info("PaymentHub: PaymentRequest #{$paymentRequest->id} created for client [{$client->name}]", [
            'provider_order_id' => $providerOrderId,
            'gateway' => $isPhonepe ? 'phonepe' : 'cashfree'
        ]);

        return response()->json([
            'payment_request_id' => $paymentRequest->id,
            'payment_url'        => $redirectUrl,
            'status'             => 'pending',
        ], 201);
    }

    /**
     * Status check endpoint — clients can poll this to see current state.
     */
    public function status(Request $request, $id)
    {
        $apiKey = $request->header('X-Api-Key');
        $client = PaymentClient::where('api_key', $apiKey)->first();
        if (!$client || !$client->isLive()) {
            return response()->json(['error' => 'Unauthorised'], 401);
        }

        $paymentRequest = PaymentRequest::where('id', $id)
            ->where('payment_client_id', $client->id)
            ->firstOrFail();

        return response()->json([
            'payment_request_id' => $paymentRequest->id,
            'client_order_id'    => $paymentRequest->client_order_id,
            'status'             => $paymentRequest->payment_status,
            'transaction_id'     => $paymentRequest->transaction_id,
            'amount'             => $paymentRequest->amount,
        ]);
    }
}
