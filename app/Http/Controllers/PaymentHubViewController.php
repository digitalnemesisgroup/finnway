<?php

namespace App\Http\Controllers;

use App\Models\PaymentRequest;
use App\Services\PaymentSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 * PaymentHubViewController
 *
 * Handles web-facing encrypted session payment routes:
 *  - hub.checkout : Verified checkout view using encrypted session token
 *  - hub.abandon  : Handles browser tab close / back button abandonment (Beacon POST)
 *  - hub.verify   : Return URL from Cashfree/PhonePe
 */
class PaymentHubViewController extends Controller
{
    protected PaymentSessionService $sessionService;

    public function __construct(PaymentSessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    /**
     * Show the unified payment checkout page using encrypted session token.
     */
    public function checkout(Request $request, string $token)
    {
        // Smooth backward compatibility: If legacy numeric ID is passed, upgrade to encrypted session token
        if (is_numeric($token)) {
            $paymentRequest = PaymentRequest::find((int) $token);
            if ($paymentRequest) {
                $sessionToken = $paymentRequest->session_token ?: $this->sessionService->createSession($paymentRequest, 'hub');
                return redirect()->route('hub.checkout', ['token' => $sessionToken]);
            }
        }

        $verification = $this->sessionService->verifyAndIncrementSession($token, 'hub');

        if (!$verification['valid']) {
            if (isset($verification['reason']) && $verification['reason'] === 'terminal_state' && isset($verification['model'])) {
                $paymentRequest = $verification['model'];
                return redirect($paymentRequest->return_url . '?' . http_build_query([
                    'client_order_id' => $paymentRequest->client_order_id,
                    'status'          => $paymentRequest->payment_status,
                ]));
            }

            return response()->view('hub.session_error', [
                'message' => $verification['message'] ?? 'Payment session is invalid, expired, or has exceeded access limit.',
                'reason'  => $verification['reason'] ?? 'invalid_session',
            ], 403);
        }

        $paymentRequest = $verification['model'];

        if (!$paymentRequest->cashfree_session_id) {
            abort(404, 'Payment session configuration missing.');
        }

        $sessionToken = $token;
        $openCount    = $verification['open_count'];
        $maxOpens     = $verification['max_opens'];
        $expiresAt    = $paymentRequest->expires_at;
        $isPhonePe    = str_starts_with($paymentRequest->cashfree_session_id ?? '', 'http://')
                     || str_starts_with($paymentRequest->cashfree_session_id ?? '', 'https://')
                     || str_contains(strtolower($paymentRequest->cashfree_session_id ?? ''), 'phonepe');

        return view('hub.payment', compact('paymentRequest', 'sessionToken', 'openCount', 'maxOpens', 'expiresAt', 'isPhonePe'));
    }

    /**
     * Endpoint hit by navigator.sendBeacon when user closes or leaves the checkout page.
     */
    public function abandon(Request $request, string $token)
    {
        try {
            $json = Crypt::decryptString($token);
            $payload = json_decode($json, true);
            if (isset($payload['id'])) {
                $this->sessionService->rejectSession((int) $payload['id'], 'hub', 'user_dropped');
                Log::info("PaymentHub: Session #{$payload['id']} marked user_dropped via abandonment beacon.");
            }
        } catch (\Throwable $e) {
            Log::warning("PaymentHub: Abandon beacon error: " . $e->getMessage());
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Gateway return URL — user redirected here after payment attempt.
     */
    public function verify(Request $request, $id)
    {
        $paymentRequest = PaymentRequest::with('client')->findOrFail($id);

        Log::info("PaymentHub: User returned to verify for PaymentRequest #{$id}", [
            'query' => $request->query(),
        ]);

        // If explicit status passed from frontend checkout (e.g. user_dropped, failed) and payment is still pending, update it
        if ($paymentRequest->payment_status === 'pending' && $request->filled('status')) {
            $passedStatus = strtolower($request->input('status'));
            if (in_array($passedStatus, ['user_dropped', 'failed', 'rejected', 'cancelled'])) {
                $paymentRequest->update([
                    'payment_status' => $passedStatus === 'rejected' ? 'failed' : $passedStatus,
                    'closed_at'      => now(),
                ]);
            }
        }

        // If still pending, perform instant status check with PhonePe & Cashfree APIs
        if ($paymentRequest->payment_status === 'pending' && $paymentRequest->cashfree_order_id) {
            // 1. PhonePe Check
            try {
                $phonePeService = app(\App\Services\PhonePeService::class);
                $verification = $phonePeService->verifyPayment($paymentRequest->cashfree_order_id);
                if (($verification['status'] ?? null) === 'Success') {
                    $paymentRequest->update([
                        'payment_status' => 'success',
                        'transaction_id' => $verification['payment_id'] ?? $paymentRequest->cashfree_order_id,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning("PaymentHub: Immediate PhonePe status check note: " . $e->getMessage());
            }

            // 2. Cashfree Check (if still pending)
            if ($paymentRequest->fresh()->payment_status === 'pending') {
                try {
                    \Cashfree\Cashfree::$XClientId = config('services.cashfree.key_id', 'demo');
                    \Cashfree\Cashfree::$XClientSecret = config('services.cashfree.key_secret', 'demo');
                    \Cashfree\Cashfree::$XEnvironment = config('services.cashfree.mode', 'sandbox') === 'production' 
                                                ? \Cashfree\Cashfree::$PRODUCTION 
                                                : \Cashfree\Cashfree::$SANDBOX;
                    $cf = new \Cashfree\Cashfree();
                    $cfRes = $cf->PGOrderFetchPayments("2023-08-01", $paymentRequest->cashfree_order_id);
                    if (!empty($cfRes[0])) {
                        foreach ($cfRes[0] as $p) {
                            if (isset($p['payment_status']) && $p['payment_status'] === 'SUCCESS') {
                                $paymentRequest->update([
                                    'payment_status' => 'success',
                                    'transaction_id' => $p['cf_payment_id'] ?? $paymentRequest->cashfree_order_id,
                                ]);
                                break;
                            } elseif (isset($p['payment_status']) && in_array($p['payment_status'], ['FAILED', 'CANCELLED', 'USER_DROPPED'])) {
                                $paymentRequest->update([
                                    'payment_status' => strtolower($p['payment_status']) === 'user_dropped' ? 'user_dropped' : 'failed',
                                ]);
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    Log::warning("PaymentHub: Immediate Cashfree status check note: " . $e->getMessage());
                }
            }
        }

        // Fire outgoing client callback webhook if status is final and callback hasn't been dispatched yet
        $freshPR = $paymentRequest->fresh();
        if (in_array($freshPR->payment_status, ['success', 'failed', 'user_dropped']) && !$freshPR->webhook_dispatched) {
            $freshPR->fireClientCallback();
        }

        // Build redirect back to client return_url with reference params
        $returnParams = [
            'order_id'           => $freshPR->cashfree_order_id,
            'client_order_id'    => $freshPR->client_order_id,
            'payment_request_id' => $freshPR->id,
            'status'             => $freshPR->payment_status,
        ];

        return redirect($freshPR->return_url . '?' . http_build_query($returnParams));
    }
}
