@extends('layouts.app')

@section('title', 'Complete Payment — FIINWAY')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="paymentHub()">
    <!-- PhonePe SDK not required, using redirect -->

    <!-- Header -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xs flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Payment Gateway</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Order #{{ $order->order_number }} • Subtotal ₹{{ number_format($order->total, 2) }}</p>
        </div>
        <div class="flex items-center gap-2">
            @if(isset($openCount) && isset($maxOpens))
                <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 text-xs font-black uppercase">
                    <i class="ri-eye-line"></i> Session View {{ $openCount }}/{{ $maxOpens }}
                </span>
            @endif
            <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-black uppercase">
                <i class="ri-shield-check-fill"></i> 256-bit Encrypted Session
            </span>
        </div>
    </div>

    <!-- Amount Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <span class="text-xs text-indigo-300 font-bold uppercase tracking-wider block">Total Amount Payable</span>
            <h2 class="text-3xl sm:text-4xl font-black text-white mt-1">₹{{ number_format($order->total, 2) }}</h2>
            <p class="text-xs text-slate-400 mt-1">Delivery Address: {{ $order->address->city ?? 'Registered Location' }}</p>
        </div>

        <div class="p-4 rounded-2xl bg-white/10 backdrop-blur-md border border-white/10 text-xs space-y-1 sm:text-right">
            <p class="font-bold text-white">Order Reference: {{ $order->order_number }}</p>
            <p class="text-slate-300">Transaction ID: {{ $payment->gateway_order_id ?? 'PENDING' }}</p>
        </div>
    </div>

    <!-- Payment Modes Selection Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left 4 Cols: Payment Options Sidebar -->
        <div class="lg:col-span-4 space-y-2">
            <button type="button" @click="tab = 'online'" :class="tab === 'online' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-white text-slate-700 hover:bg-slate-100'" class="w-full p-4 rounded-2xl border border-slate-100 font-extrabold text-xs text-left transition-all flex items-center justify-between">
                <span class="flex items-center gap-3">
                    <i class="ri-bank-card-line text-lg"></i> Pay Online (UPI, Cards, NetBanking)
                </span>
                <i class="ri-arrow-right-s-line" :class="tab === 'online' ? 'text-white' : 'text-slate-400'"></i>
            </button>

            <button type="button" @click="tab = 'cod'" :class="tab === 'cod' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/20' : 'bg-white text-slate-700 hover:bg-slate-100'" class="w-full p-4 rounded-2xl border border-slate-100 font-extrabold text-xs text-left transition-all flex items-center justify-between">
                <span class="flex items-center gap-3">
                    <i class="ri-hand-coin-line text-lg"></i> Cash on Delivery (COD)
                </span>
                <i class="ri-arrow-right-s-line" :class="tab === 'cod' ? 'text-white' : 'text-slate-400'"></i>
            </button>
        </div>

        <!-- Right 8 Cols: Active Mode Form Content -->
        <div class="lg:col-span-8">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-xs space-y-6">

                <!-- 1. PAY ONLINE TAB -->
                <div x-show="tab === 'online'" class="space-y-6" x-transition.opacity>
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-black text-slate-900">Pay Online Securely</h3>
                            <p class="text-xs text-slate-400 font-semibold">Pay via UPI, Credit/Debit Cards, NetBanking, or Wallets.</p>
                        </div>
                        <div class="flex gap-2 text-xl text-slate-400">
                            <i class="ri-secure-payment-line"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" @click="selectedGateway = 'phonepe'" :class="selectedGateway === 'phonepe' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600 ring-offset-2' : 'border-slate-200 hover:border-indigo-300'" class="p-4 rounded-3xl border text-center space-y-2 transition-all">
                            <div class="w-12 h-12 mx-auto bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100 overflow-hidden p-2">
                                <img src="https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=https://www.phonepe.com&size=128" alt="PhonePe" class="w-full h-full object-contain">
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm">PhonePe</h4>
                        </button>
                        
                        <button type="button" @click="selectedGateway = 'cashfree'" :class="selectedGateway === 'cashfree' ? 'border-indigo-600 bg-indigo-50 ring-2 ring-indigo-600 ring-offset-2' : 'border-slate-200 hover:border-indigo-300'" class="p-4 rounded-3xl border text-center space-y-2 transition-all">
                            <div class="w-12 h-12 mx-auto bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100 overflow-hidden p-2">
                                <img src="https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL&url=https://www.cashfree.com&size=128" alt="Cashfree" class="w-full h-full object-contain">
                            </div>
                            <h4 class="font-bold text-slate-900 text-sm">Cashfree</h4>
                        </button>
                    </div>

                    <button type="button" @click="submitPayment('online')" class="w-full py-4 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-sm shadow-xl shadow-indigo-600/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-lock-line"></i> Pay ₹{{ number_format($order->total, 2) }} Now
                    </button>
                </div>

                <!-- 2. CASH ON DELIVERY TAB -->
                <div x-show="tab === 'cod'" class="space-y-6" x-transition.opacity>
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Cash on Delivery (COD)</h3>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">Pay in cash when your package is delivered</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-amber-50 border border-amber-200 space-y-2 text-xs font-semibold text-amber-900">
                        <div class="flex items-center gap-2 text-amber-800 font-bold">
                            <i class="ri-information-fill text-lg"></i> Notice for Cash on Delivery Orders:
                        </div>
                        <ul class="space-y-1 text-amber-700 font-medium pl-6 list-disc">
                            <li>Please keep exact change of ₹{{ number_format($order->total, 2) }} ready at delivery.</li>
                            <li>OTP verification will be requested by delivery partner upon package arrival.</li>
                        </ul>
                    </div>

                    <button type="button" @click="submitPayment('cod')" class="w-full py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-sm shadow-xl shadow-emerald-600/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-checkbox-circle-line text-lg"></i> Confirm Order with COD
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Hidden Form for Local Payment Process (COD/Voucher) -->
    <form id="paymentProcessForm" action="{{ route('payment.process', $order->id) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="payment_method" :value="paymentMethod">
    </form>

    <!-- Processing Overlay Modal -->
    <div x-show="processing" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-md" x-cloak>
        <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center space-y-4 shadow-2xl">
            <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto text-3xl animate-bounce">
                <i class="ri-shield-keyhole-line"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900">Processing Payment...</h3>
            <p class="text-xs text-slate-500 font-medium">Verifying security signatures with payment gateway...</p>
            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                <div class="bg-indigo-600 h-full animate-pulse w-full"></div>
            </div>
        </div>
    </div>

    <!-- Cancel Payment Form (hidden, submitted by JS on confirmed cancel) -->
    <form id="cancelPaymentForm" action="{{ route('payment.cancel', $order->id) }}" method="POST" class="hidden">
        @csrf
    </form>

    <!-- Network Error / Stuck Payment Recovery Banner -->
    <div id="stuckBanner" class="hidden fixed top-0 left-0 right-0 z-50 bg-amber-500 text-white text-xs font-bold px-4 py-3 flex items-center justify-between shadow-lg">
        <span><i class="ri-wifi-off-line mr-2"></i>Payment may still be processing. Don't close this tab!</span>
        <button onclick="checkStatus()" class="ml-4 px-3 py-1 bg-white text-amber-700 rounded-full text-xs font-black">Check Status</button>
    </div>

</div>

<script>
    // PhonePe uses redirect flow

    // ── Persistence keys ───────────────────────────────────────────────────────
    const LS_KEY     = 'fiinway_pending_payment';
    const orderId    = '{{ $order->id }}';
    const verifyUrl  = "{{ route('payment.verify', ['order' => $order->id]) }}?order_id={{ $payment->gateway_order_id }}";
    const statusUrl  = "{{ route('payment.status', $order->id) }}";
    const successUrl = "{{ route('payment.success', $order->id) }}";

    // On page load: show stuck banner if we have a saved pending payment (crash recovery)
    window.addEventListener('DOMContentLoaded', () => {
        const saved = JSON.parse(localStorage.getItem(LS_KEY) || 'null');
        if (saved && saved.orderId === orderId) {
            document.getElementById('stuckBanner').classList.remove('hidden');
        }
    });

    // Poll server to see if payment already went through (used by stuck banner button)
    async function checkStatus() {
        try {
            const res  = await fetch(statusUrl);
            const data = await res.json();
            if (data.status === 'paid' && data.success_url) {
                localStorage.removeItem(LS_KEY);
                window.location.href = data.success_url;
            } else {
                alert('Payment is still pending. If you completed it, please wait a moment and click "Check Status" again.');
            }
        } catch (e) {
            alert('Could not reach the server. Please check your internet connection and try again.');
        }
    }

    let isSubmittingPayment = false;
    const sessionToken = "{{ $sessionToken ?? '' }}";
    const abandonUrl = sessionToken ? "{{ route('payment.abandon', ['token' => ':token']) }}".replace(':token', sessionToken) : '';

    function triggerAbandonmentBeacon() {
        if (!isSubmittingPayment && abandonUrl) {
            navigator.sendBeacon(abandonUrl);
        }
    }

    window.addEventListener('pagehide', triggerAbandonmentBeacon);
    window.addEventListener('beforeunload', triggerAbandonmentBeacon);

function paymentHub() {
    return {
        tab: 'online',
        selectedGateway: 'phonepe',
        paymentMethod: 'online',
        processing: false,
        networkError: false,

        async submitPayment(method) {
            isSubmittingPayment = true;
            this.paymentMethod = method;
            this.networkError  = false;

            if (method === 'cod') {
                this.processing = true;
                setTimeout(() => {
                    document.getElementById('paymentProcessForm').submit();
                }, 800);
                return;
            }

            this.processing = true;

            try {
                // Call server to generate Payment Hub Request
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]').value;
                const response = await fetch("{{ route('payment.initiate', $order->id) }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ gateway: this.selectedGateway })
                });

                if (!response.ok) {
                    throw new Error('Failed to initiate payment.');
                }

                const data = await response.json();

                // Persist to localStorage BEFORE redirecting (crash guard)
                localStorage.setItem(LS_KEY, JSON.stringify({ orderId, verifyUrl, savedAt: Date.now() }));

                // Redirect securely to Gateway Pay Page
                window.location.href = data.payment_url;
                
            } catch (error) {
                alert("Error connecting to payment gateway. Please try again.");
                this.processing = false;
            }
        },

        async verifyWithRetry(url, attempt = 1) {
            this.processing   = true;
            this.networkError = false;

            // Show stuck banner if verification takes more than 10 seconds
            const bannerTimer = setTimeout(() => {
                document.getElementById('stuckBanner').classList.remove('hidden');
            }, 10000);

            try {
                const res = await fetch(url, { redirect: 'follow' });
                clearTimeout(bannerTimer);
                localStorage.removeItem(LS_KEY);

                if (res.ok || res.redirected) {
                    window.location.href = res.url || successUrl;
                } else {
                    throw new Error('Server returned ' + res.status);
                }
            } catch (e) {
                clearTimeout(bannerTimer);
                if (attempt <= 3) {
                    // Exponential backoff: 2s → 4s → 8s
                    const delay = Math.pow(2, attempt) * 1000;
                    console.warn(`Verify attempt ${attempt} failed. Retrying in ${delay}ms...`);
                    setTimeout(() => this.verifyWithRetry(url, attempt + 1), delay);
                } else {
                    // 3 retries exhausted — show recovery UI
                    this.processing   = false;
                    this.networkError = true;
                    document.getElementById('stuckBanner').classList.remove('hidden');
                }
            }
        }
    }
}
</script>
@endsection
