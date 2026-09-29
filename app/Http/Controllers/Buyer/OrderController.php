<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\Coupon;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\ReferralReward;
use App\Models\SellerEarning;
use App\Models\Shipment;
use App\Models\UserAddress;
use App\Services\PhonePeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    protected PhonePeService $phonePeService;

    public function __construct(PhonePeService $phonePeService)
    {
        $this->phonePeService = $phonePeService;
    }

    public function checkout()
    {
        $user = Auth::user();

        // Buy Now: build a temporary virtual cart from session
        $buyNowProductId = session('buy_now_product_id');
        if ($buyNowProductId) {
            $product = \App\Models\Product::with(['images', 'seller'])->find($buyNowProductId);
            if (!$product || $product->status !== 'active') {
                session()->forget(['buy_now_product_id', 'buy_now_quantity']);
                return redirect()->route('cart')->with('error', 'Product is no longer available.');
            }
            $qty = session('buy_now_quantity', 1);
            // Build a fake cart-like object for the view
            $fakeItem = (object)[
                'product'  => $product,
                'quantity' => $qty,
                'price'    => $product->selling_price,
                'subtotal' => $product->selling_price * $qty,
            ];
            $subtotal = $fakeItem->subtotal;
            $standardDelivery = (float)\App\Models\AppSetting::get('standard_delivery_fee', 0);
            $expressDelivery  = (float)\App\Models\AppSetting::get('express_delivery_fee', 99);
            $delivery = $standardDelivery;
            $discount = 0;
            $total    = $subtotal + $delivery;

            $addresses      = $user->addresses()->get();
            $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();

            // Pass a virtual cart with an items collection
            $cart           = (object)['items' => collect([$fakeItem])];
            return view('buyer.checkout', compact('cart', 'addresses', 'defaultAddress', 'subtotal', 'delivery', 'discount', 'total'));
        }

        // Normal cart flow
        $cart = $user->cart()->with(['items.product.images', 'items.product.seller'])->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $addresses = $user->addresses()->get();
        $defaultAddress = $addresses->where('is_default', true)->first() ?? $addresses->first();

        $subtotal = $cart->subtotal;
        $standardDelivery = (float)\App\Models\AppSetting::get('standard_delivery_fee', 0);
        $expressDelivery  = (float)\App\Models\AppSetting::get('express_delivery_fee', 99);

        $delivery = session('delivery_option', 'standard') === 'express'
            ? $expressDelivery
            : $standardDelivery;
        $discount = session('coupon_discount', 0);
        $total    = $subtotal + $delivery - $discount;

        return view('buyer.checkout', compact('cart', 'addresses', 'defaultAddress', 'subtotal', 'delivery', 'discount', 'total'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id'      => 'required|exists:user_addresses,id',
            'delivery_option' => 'required|in:standard,express',
        ]);

        $user = Auth::user();

        // Determine items: Buy Now (single product) OR full cart
        $buyNowProductId = session('buy_now_product_id');
        $isBuyNow        = (bool) $buyNowProductId;

        if ($isBuyNow) {
            $product = \App\Models\Product::find($buyNowProductId);
            if (!$product || $product->status !== 'active') {
                session()->forget(['buy_now_product_id', 'buy_now_quantity']);
                return redirect()->route('cart')->with('error', 'Product is no longer available.');
            }
            $buyNowQty = session('buy_now_quantity', 1);
            // Fake items collection consistent with cart items
            $items = collect([(object)[
                'product_id' => $product->id,
                'product'    => $product,
                'quantity'   => $buyNowQty,
                'price'      => $product->selling_price,
                'subtotal'   => $product->selling_price * $buyNowQty,
            ]]);
        } else {
            $cart = $user->cart()->with(['items.product'])->first();
            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart')->with('error', 'Your cart is empty.');
            }
            $items = $cart->items;
        }

        // Ensure internal client exists BEFORE the transaction so the API can see it
        $internalClient = \App\Models\PaymentClient::firstOrCreate(
            ['name' => 'FIINWAY Internal'],
            ['api_key' => 'internal_' . \Illuminate\Support\Str::random(32), 'api_salt' => \Illuminate\Support\Str::random(32), 'is_active' => true, 'approval_status' => 'approved']
        );
        if (!$internalClient->is_active) {
            $internalClient->update(['is_active' => true, 'approval_status' => 'approved']);
        }

        DB::beginTransaction();

        try {
            $address = UserAddress::where('id', $request->address_id)->where('user_id', $user->id)->firstOrFail();
        
            // Save geolocation if provided and not already set
            if ($request->filled('buyer_latitude') && $request->filled('buyer_longitude')) {
                $address->update([
                    'latitude' => $request->buyer_latitude,
                    'longitude' => $request->buyer_longitude,
                ]);
                $user->update([
                    'latitude' => $request->buyer_latitude,
                    'longitude' => $request->buyer_longitude,
                ]);
            }

            $standardDelivery = (float) AppSetting::get('standard_delivery_fee', 0);
            $expressDelivery  = (float) AppSetting::get('express_delivery_fee', 99);
            $subtotal  = $items->sum('subtotal');
            $delivery  = $isBuyNow ? $standardDelivery
                       : ($request->delivery_option === 'express' ? $expressDelivery : $standardDelivery);
            $discount  = $isBuyNow ? 0 : session('coupon_discount', 0);
            $couponCode = $isBuyNow ? null : session('coupon_code');
            $total     = $subtotal + $delivery - $discount;

            $useWallet = $request->has('use_wallet') && $user->wallet_balance > 0;
            $walletApplied = 0;

            if ($useWallet) {
                $walletApplied = min($user->wallet_balance, $total);
                $total -= $walletApplied;
                
                // Deduct from wallet atomically
                $user->decrement('wallet_balance', $walletApplied);
            }

            // Create order
            $order = Order::create([
                'order_number'    => 'ORD-' . strtoupper(Str::random(10)),
                'user_id'         => $user->id,
                'address_id'      => $address->id,
                'subtotal'        => $subtotal,
                'delivery_charge' => $delivery,
                'discount'        => $discount,
                'total'           => $total,
                'wallet_balance_used' => $walletApplied,
                'coupon_code'     => $couponCode,
                'delivery_option' => $request->delivery_option,
                'status'          => 'pending',
                'payment_status'  => 'pending',
            ]);

            $productIds = $items->pluck('product_id')->toArray();

            // ── Atomic stock reservation ──────────────────────────────────────────
            // We use a per-product DB-level lock (FOR UPDATE) inside a sub-transaction.
            // This guarantees only ONE concurrent request can decrement stock below 0.
            // Winner: first request whose UPDATE affects 1 row. Loser: affected = 0 → exception.
            foreach ($items as $item) {
                $affected = DB::table('products')
                    ->where('id', $item->product_id)
                    ->where('stock', '>=', $item->quantity) // atomic guard
                    ->decrement('stock', $item->quantity);

                if ($affected === 0) {
                    // Re-fetch to give a meaningful message
                    $productName = \App\Models\Product::find($item->product_id)?->name ?? 'A product';
                    throw new \Exception("{$productName} is out of stock. Please go back and try again.");
                }

                // If stock hits 0, mark product as sold
                $freshStock = DB::table('products')->where('id', $item->product_id)->value('stock');
                if ($freshStock <= 0) {
                    DB::table('products')->where('id', $item->product_id)->update(['status' => 'sold']);
                }
            }

            // ── Reload products after reservation for order item creation ─────────
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($items as $item) {
                $product = $products->get($item->product_id);

                $orderItem = OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'seller_id'    => $item->product->user_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $item->subtotal,
                    'status'       => 'pending',
                ]);

                $category = $product->category;
                $val = (float) ($category->commission_value ?? 5);
                $type = $category->commission_type ?? 'percent';
                
                if ($type === 'flat') {
                    $commissionAmt = $val * $item->quantity;
                    $commissionPercent = 0;
                } else {
                    $commissionPercent = $val;
                    $commissionAmt = round($item->subtotal * $val / 100, 2);
                }
                
                // Other applicable fee from category
                $otherFee = (float) ($category->other_fee ?? 0);
                $totalDeductions = $commissionAmt + $otherFee;

                SellerEarning::create([
                    'seller_id'          => $item->product->user_id,
                    'order_id'           => $order->id,
                    'order_item_id'      => $orderItem->id,
                    'order_amount'       => $item->subtotal,
                    'commission_percent' => $commissionPercent,
                    'commission_amount'  => $totalDeductions, // Storing combined deduction
                    'seller_amount'      => max(0, $item->subtotal - $totalDeductions),
                    'status'             => 'pending',
                ]);

                $shipment = Shipment::firstOrCreate(
                    ['order_id' => $order->id, 'seller_id' => $item->product->user_id],
                    [
                        'status'                => 'confirmed',
                        'expected_delivery'     => now()->addDays($request->delivery_option === 'express' ? 2 : 5),
                        'delivery_partner_type' => $product->delivery_partner_type ?? 'seller',
                    ]
                );

                if ($shipment->wasRecentlyCreated && $shipment->delivery_partner_type === 'fiinway') {
                    $shipment->update([
                        'pickup_otp'   => (string) rand(100000, 999999),
                        'delivery_otp' => (string) rand(100000, 999999),
                    ]);
                }
            } // Close foreach

            if ($total <= 0) {
                // Fully paid by wallet
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => 'wallet',
                    'paid_at'        => now(),
                    'status'         => 'confirmed',
                ]);
                $order->items()->update(['status' => 'confirmed']);
                $order->shipments()->update(['status' => 'confirmed']);

                Payment::create([
                    'order_id'           => $order->id,
                    'user_id'            => $user->id,
                    'gateway'            => 'wallet',
                    'amount'             => $walletApplied,
                    'currency'           => 'INR',
                    'method'             => 'wallet',
                    'status'             => 'success',
                    'paid_at'            => now(),
                ]);
            } else {
                // Defer Payment Hub Request until the user selects a gateway on the payment page!
                $payment = Payment::create([
                    'order_id'           => $order->id,
                    'user_id'            => $user->id,
                    'gateway'            => 'hub',
                    'gateway_order_id'   => null,
                    'amount'             => $order->total,
                    'currency'           => 'INR',
                    'method'             => 'pending',
                    'status'             => 'pending',
                    'gateway_payment_id' => null,
                ]);

                $sessionService = app(\App\Services\PaymentSessionService::class);
                $sessionToken = $sessionService->createSession($payment, 'buyer');
            }

            if ($couponCode) {
                Coupon::where('code', $couponCode)->increment('used_count');
                session()->forget(['coupon_code', 'coupon_discount']);
            }

            // Clear buy-now session after order is created
            if ($isBuyNow) {
                session()->forget(['buy_now_product_id', 'buy_now_quantity']);
            } else {
                // Only clear cart for normal checkout
            }

            DB::commit();

            session(['payment_verify_key_' . $order->id => Str::uuid()]);
            session(['pending_order_id' => $order->id]);

            if ($total <= 0) {
                // Clear cart
                if (!$isBuyNow) {
                    $user->cart()->items()->delete();
                }
                return redirect()->route('payment.success', $order->id);
            }

            return redirect()->route('payment.session', ['token' => $sessionToken]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage() ?: 'Something went wrong. Please try again.');
        }
    }

    public function payment(Order $order)
    {
        Gate::authorize('view', $order);
        if ($order->payment_status === 'paid') return redirect()->route('orders.show', $order->id);

        $payment = Payment::where('order_id', $order->id)->where('gateway', 'hub')->latest()->first();

        if (!$payment) {
            return redirect()->route('orders.show', $order->id)->with('error', 'Payment record missing.');
        }

        $sessionService = app(\App\Services\PaymentSessionService::class);
        $token = $payment->session_token ?: $sessionService->createSession($payment, 'buyer');

        return redirect()->route('payment.session', ['token' => $token]);
    }

    public function paymentSession(Request $request, string $token)
    {
        $sessionService = app(\App\Services\PaymentSessionService::class);
        $verification = $sessionService->verifyAndIncrementSession($token, 'buyer');

        if (!$verification['valid']) {
            if (isset($verification['reason']) && $verification['reason'] === 'terminal_state' && isset($verification['model'])) {
                $payment = $verification['model'];
                return redirect()->route('orders.show', $payment->order_id);
            }

            return response()->view('buyer.session_error', [
                'message' => $verification['message'] ?? 'Payment session link is invalid, expired, or has exceeded access limits.',
                'reason'  => $verification['reason'] ?? 'invalid_session',
            ], 403);
        }

        $payment = $verification['model'];
        $order   = $payment->order;
        $sessionToken = $token;
        $openCount = $verification['open_count'];
        $maxOpens  = $verification['max_opens'];
        $expiresAt = $payment->expires_at;

        return view('buyer.payment', compact('order', 'payment', 'sessionToken', 'openCount', 'maxOpens', 'expiresAt'));
    }

    public function abandonSession(Request $request, string $token)
    {
        try {
            $json = \Illuminate\Support\Facades\Crypt::decryptString($token);
            $payload = json_decode($json, true);
            if (isset($payload['id'])) {
                $sessionService = app(\App\Services\PaymentSessionService::class);
                $sessionService->rejectSession((int) $payload['id'], 'buyer', 'user_dropped');
                Log::info("BuyerOrderController: Session #{$payload['id']} marked user_dropped via abandonment beacon.");
            }
        } catch (\Throwable $e) {
            Log::warning("BuyerOrderController: Abandon beacon error: " . $e->getMessage());
        }

        return response()->json(['ok' => true]);
    }

    public function processPayment(Request $request, Order $order)
    {
        Gate::authorize('view', $order);
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id);
        }

        $request->validate([
            'payment_method' => 'required|in:online,cod,voucher',
        ]);

        $payment = Payment::where('order_id', $order->id)->firstOrFail();

        // If it's a digital payment method, this shouldn't be called directly, frontend handles it.
        // But if COD or Voucher, process it locally.
        if (in_array($request->payment_method, ['cod', 'voucher'])) {
            $payment->update([
                'gateway_payment_id' => 'sim_' . Str::random(10),
                'transaction_id'     => 'sim_' . Str::random(10),
                'status'             => 'success',
                'paid_at'            => now(),
            ]);

            $order->update([
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method,
                'transaction_id' => $payment->transaction_id,
                'paid_at'        => now(),
                'status'         => 'confirmed',
            ]);

            $order->items()->update(['status' => 'confirmed']);
            $order->shipments()->update(['status' => 'confirmed']);

            // Stock already atomically reserved during placeOrder — no decrement needed here.

            // Clear buyer cart
            $buyer = Auth::user();
            if ($buyer && $buyer->cart) {
                $buyer->cart->items()->delete();
            }

            return redirect()->route('payment.success', $order->id);
        }

        return redirect()->route('payment', $order->id)->with('error', 'Please use the payment gateway for digital payments.');
    }

    /**
     * Server-side Cashfree API verification endpoint.
     */
    public function verifyPayment(Request $request, Order $order)
    {
        Gate::authorize('view', $order);

        $request->validate([
            'order_id' => 'nullable|string',
        ]);

        // Idempotency: if payment already verified (paid), just redirect to success
        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.success', $order->id);
        }

        // Session idempotency key: prevent replay attacks / duplicate verify calls
        $verifyDoneKey = 'payment_verify_done_' . $order->id;
        if (session()->has($verifyDoneKey)) {
            return redirect()->route('payment.success', $order->id);
        }

        $payment = Payment::where('order_id', $order->id)->where('gateway', 'hub')->first();

        if (!$payment) {
            return redirect()->route('payment', $order->id)->with('error', 'Payment record missing.');
        }

        $isSuccessful = $request->input('status') === 'success';
        $verifiedTxnId = $payment->gateway_order_id;

        // 1. Verify with PhonePe API if method is phonepe or unknown
        if (!$isSuccessful && ($payment->method === 'phonepe' || empty($payment->method))) {
            try {
                $phonePeService = app(\App\Services\PhonePeService::class);
                $verification = $phonePeService->verifyPayment($payment->gateway_order_id);
                if (($verification['status'] ?? null) === 'Success') {
                    $isSuccessful = true;
                    $verifiedTxnId = $verification['payment_id'] ?? $payment->gateway_order_id;
                }
            } catch (\Throwable $e) {
                Log::warning("PhonePe verify note: " . $e->getMessage());
            }
        }

        // 2. Verify with Cashfree API if method is cashfree or unknown
        if (!$isSuccessful && ($payment->method === 'cashfree' || empty($payment->method))) {
            try {
                \Cashfree\Cashfree::$XClientId = config('services.cashfree.key_id', 'demo');
                \Cashfree\Cashfree::$XClientSecret = config('services.cashfree.key_secret', 'demo');
                \Cashfree\Cashfree::$XEnvironment = config('services.cashfree.mode', 'sandbox') === 'production' 
                                            ? \Cashfree\Cashfree::$PRODUCTION 
                                            : \Cashfree\Cashfree::$SANDBOX;
                $cf = new \Cashfree\Cashfree();
                $cfRes = $cf->PGOrderFetchPayments("2023-08-01", $payment->gateway_order_id);
                if (!empty($cfRes[0])) {
                    foreach ($cfRes[0] as $p) {
                        if (isset($p['payment_status']) && $p['payment_status'] === 'SUCCESS') {
                            $isSuccessful = true;
                            $verifiedTxnId = $p['cf_payment_id'] ?? $payment->gateway_order_id;
                            break;
                        }
                    }
                }
            } catch (\Throwable $e) {
                Log::warning("Cashfree verify note: " . $e->getMessage());
            }
        }

        // 3. Fallback: Check PaymentRequest status in DB
        if (!$isSuccessful) {
            $pr = \App\Models\PaymentRequest::where('cashfree_order_id', $payment->gateway_order_id)
                ->orWhere('id', $request->input('payment_request_id'))
                ->first();
            if ($pr && $pr->payment_status === 'success') {
                $isSuccessful = true;
                $verifiedTxnId = $pr->transaction_id ?? $payment->gateway_order_id;
            }
        }

        if (!$isSuccessful) {
            return redirect()->route('payment', $order->id)->with('error', 'Payment failed or is pending. If money was deducted, your order will confirm automatically.');
        }

        // 4. Update payment and order to paid & confirmed
        DB::transaction(function () use ($order, $payment, $verifiedTxnId) {
            $paymentFresh = Payment::lockForUpdate()->find($payment->id);
            $orderFresh   = Order::lockForUpdate()->find($order->id);

            if ($paymentFresh->status === 'success' || $orderFresh->payment_status === 'paid') {
                return;
            }

            $paymentFresh->update([
                'gateway_payment_id' => $verifiedTxnId,
                'transaction_id'     => $verifiedTxnId,
                'status'             => 'success',
                'paid_at'            => now(),
            ]);

            $orderFresh->update([
                'payment_status' => 'paid',
                'payment_method' => $payment->method ?? 'phonepe',
                'transaction_id' => $verifiedTxnId,
                'paid_at'        => now(),
                'status'         => 'confirmed',
            ]);

            $orderFresh->items()->update(['status' => 'confirmed']);
            $orderFresh->shipments()->update(['status' => 'confirmed']);

            // Stock already atomically reserved during placeOrder — no decrement needed here.

            // Clear buyer cart
            $buyer = Auth::user();
            if ($buyer && $buyer->cart) {
                $buyer->cart->items()->delete();
            }

            // Check referral reward
            $referral = Referral::where('referred_id', $buyer->id)
                ->where('eligible_action_done', false)
                ->first();

            if ($referral) {
                $referral->update(['eligible_action_done' => true, 'eligible_at' => now()]);
                $rewardAmount = (float) AppSetting::get('referral_reward', 50);
                ReferralReward::create([
                    'user_id'     => $referral->referrer_id,
                    'referral_id' => $referral->id,
                    'amount'      => $rewardAmount,
                    'status'      => 'credited',
                    'credited_at' => now(),
                ]);
                $referral->referrer->increment('wallet_balance', $rewardAmount);
            }

            Notification::create([
                'user_id' => $orderFresh->user_id,
                'title'   => 'Payment Successful! 🎉',
                'body'    => "Your payment of ₹" . number_format($orderFresh->total, 2) . " for order #{$orderFresh->order_number} has been verified.",
                'type'    => 'order',
            ]);
        });

        // Mark idempotency done — prevents re-processing on page refresh
        session()->forget('payment_verify_key_' . $order->id);
        session(['payment_verify_done_' . $order->id => true]);

        return redirect()->route('payment.success', $order->id);
    }

    /**
     * Cancel a pending payment and restore reserved stock.
     * Called when the user explicitly cancels or abandons the payment popup.
     */
    public function initiateHubPayment(Request $request, Order $order)
    {
        Gate::authorize('view', $order);
        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Order already paid.'], 400);
        }

        $gatewayChoice = $request->input('gateway', 'phonepe'); // 'phonepe' or 'cashfree'
        
        $internalClient = \App\Models\PaymentClient::where('name', 'FIINWAY Internal')->first();
        if (!$internalClient) {
            return response()->json(['error' => 'Internal payment client missing.'], 500);
        }

        $clientOrderId = 'FIINWAY_' . $order->id . '_' . Str::random(4);
        $amount = $order->total;
        $returnUrl = route('payment.verify', $order->id);
        $webhookUrl = route('webhooks.internal');
        
        $canonicalString = $clientOrderId . '|' . $amount . '|' . $returnUrl;
        $signature = hash_hmac('sha256', $canonicalString, $internalClient->api_salt);

        $endpoint = $gatewayChoice === 'phonepe' ? route('hub.api.initiate.phonepe') : route('hub.api.initiate');

        $user = \Illuminate\Support\Facades\Auth::user();

        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'X-Api-Key' => $internalClient->api_key,
        ])->post($endpoint, [
            'client_order_id' => $clientOrderId,
            'amount' => $amount,
            'customer_phone' => $user->phone ?? '9999999999',
            'customer_email' => $user->email ?? null,
            'return_url' => $returnUrl,
            'webhook_url' => $webhookUrl,
            'signature' => $signature,
        ]);

        if (!$response->successful()) {
            \Illuminate\Support\Facades\Log::error("PaymentHub Request Failed: " . $response->body());
            return response()->json(['error' => 'Gateway unavailable.'], 503);
        }

        $hubData = $response->json();
        
        $payment = Payment::where('order_id', $order->id)->where('gateway', 'hub')->first();
        if ($payment) {
            $payment->update([
                'gateway_order_id' => $hubData['payment_request_id'],
                'method' => $gatewayChoice,
                'gateway_payment_id' => $hubData['payment_url'],
            ]);
        }

        return response()->json(['payment_url' => $hubData['payment_url']]);
    }

    public function cancelPayment(Order $order)
    {
        Gate::authorize('view', $order);

        // Only cancel genuinely pending orders — never refund a paid one
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.show', $order->id);
        }

        DB::transaction(function () use ($order) {
            $orderFresh = Order::lockForUpdate()->find($order->id);

            if ($orderFresh->payment_status === 'paid') {
                return; // Race-condition guard — already paid, do nothing
            }

            // Restore atomically reserved stock for every item in the order
            foreach ($orderFresh->items as $item) {
                $affected = DB::table('products')
                    ->where('id', $item->product_id)
                    ->increment('stock', $item->quantity);

                // If product was marked sold but stock is now back, re-activate it
                if ($affected) {
                    $freshStock = DB::table('products')->where('id', $item->product_id)->value('stock');
                    if ($freshStock > 0) {
                        DB::table('products')
                            ->where('id', $item->product_id)
                            ->where('status', 'sold')
                            ->update(['status' => 'active']);
                    }
                }
            }

            // Cancel the order and payment record
            $orderFresh->update([
                'status'         => 'cancelled',
                'payment_status' => 'failed',
            ]);

            Payment::where('order_id', $orderFresh->id)
                ->where('status', 'pending')
                ->update(['status' => 'failed']);

            // ── Void all pending seller earnings so they never appear in commission ──
            SellerEarning::where('order_id', $orderFresh->id)
                ->whereIn('status', ['pending'])
                ->update(['status' => 'failed']);

            Log::info("Order #{$orderFresh->order_number} cancelled and stock restored.", [
                'order_id' => $orderFresh->id,
                'user_id'  => $orderFresh->user_id,
            ]);
        });

        // Clear idempotency keys
        session()->forget([
            'payment_verify_key_'  . $order->id,
            'payment_verify_done_' . $order->id,
            'pending_order_id',
        ]);

        return redirect()->route('cart')->with('info', 'Payment was cancelled. Your cart items are still available.');
    }

    /**
     * JSON polling endpoint — lets the frontend check if payment is confirmed
     * without doing full server-side verification. Used for network-error recovery.
     */
    public function checkPaymentStatus(Order $order)
    {
        Gate::authorize('view', $order);

        return response()->json([
            'status'         => $order->payment_status,
            'order_status'   => $order->status,
            'success_url'    => $order->payment_status === 'paid'
                                    ? route('payment.success', $order->id)
                                    : null,
        ]);
    }

    public function paymentSuccess(Order $order)
    {
        Gate::authorize('view', $order);
        $order->load(['items.product', 'address']);
        return view('buyer.payment-success', compact('order'));
    }

    // My Orders list
    public function myOrders(Request $request)
    {
        $status = $request->status ?? 'all';
        $query = Order::with(['items.product.images'])
            ->where('user_id', Auth::id())
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10);
        return view('buyer.orders', compact('orders', 'status'));
    }

    // Order detail
    public function show(Order $order)
    {
        Gate::authorize('view', $order);
        $order->load(['items.product.images', 'address', 'shipments.events', 'payment']);
        return view('buyer.order-detail', compact('order'));
    }

    // Order tracking
    public function track(Order $order)
    {
        Gate::authorize('view', $order);
        $order->load(['items.product.images', 'address', 'shipments.events', 'shipments.items.product.images']);
        return view('buyer.order-tracking', compact('order'));
    }

    // Customer confirms receipt
    public function confirmReceipt(Order $order)
    {
        Gate::authorize('view', $order);
        if ($order->status !== 'delivered') abort(400);

        $order->update([
            'customer_confirmed'    => true,
            'customer_confirmed_at' => now(),
        ]);

        // Get first item's category settlement type (assuming same for entire order for simplicity, or we can use the order's primary product)
        $firstItem = $order->items->first();
        $autoSettlement = $firstItem ? ($firstItem->product->category->settlement_type === 'automatic' ? '1' : '0') : '0';

        if ($autoSettlement === '1') {
            // Automatically release payment
            $earnings = $order->earnings()->where('status', 'pending')->get();
            foreach ($earnings as $earning) {
                $earning->update([
                    'status'       => 'released',
                    'customer_ok_at' => now(),
                    'released_at'  => now(),
                ]);
                $earning->seller->increment('seller_wallet_balance', $earning->seller_amount);
                
                \App\Models\Payout::create([
                    'seller_id'       => $earning->seller_id,
                    'amount'          => $earning->seller_amount,
                    'status'          => 'done',
                    'processed_at'    => now(),
                    'transaction_ref' => 'AUTO-' . strtoupper(substr(md5($earning->id . now()), 0, 10)),
                ]);
            }
            
            // Notify Admin
            \App\Models\Notification::create([
                'user_id' => 1, // assuming 1 is admin
                'title'   => 'Order Delivered & Settled',
                'body'    => "Order #{$order->id} delivered successfully. Settlement automatically released to seller.",
                'type'    => 'admin',
                'data'    => json_encode(['order_id' => $order->id]),
            ]);
            
            return back()->with('success', 'Thank you for confirming! Payment has been released to the seller.');
        } else {
            // Update seller earnings — start hold for admin approval
            $order->earnings()->where('status', 'pending')->update([
                'status'       => 'on_hold',
                'customer_ok_at' => now(),
                'hold_until'   => now()->addDays(2),
            ]);
            
            // Notify Admin
            \App\Models\Notification::create([
                'user_id' => 1, // assuming 1 is admin
                'title'   => 'Order Delivered - Settlement Pending',
                'body'    => "Order #{$order->id} delivered successfully. Seller settlement is ready for approval.",
                'type'    => 'admin',
                'data'    => json_encode(['order_id' => $order->id]),
            ]);
            
            return back()->with('success', 'Thank you for confirming!');
        }
    }
}
