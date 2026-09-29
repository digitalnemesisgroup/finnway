<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment — <?php echo e($paymentRequest->client->name); ?></title>
    <meta name="description" content="Complete your secure payment powered by FIINWAY Payment Hub">

    
    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>

    
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">

    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --indigo-50:  #eef2ff;
            --indigo-100: #e0e7ff;
            --indigo-500: #6366f1;
            --indigo-600: #4f46e5;
            --indigo-700: #4338ca;
            --slate-50:   #f8fafc;
            --slate-100:  #f1f5f9;
            --slate-200:  #e2e8f0;
            --slate-300:  #cbd5e1;
            --slate-400:  #94a3b8;
            --slate-500:  #64748b;
            --slate-700:  #334155;
            --slate-900:  #0f172a;
            --emerald-50: #ecfdf5;
            --emerald-600:#059669;
            --emerald-700:#047857;
            --amber-50:   #fffbeb;
            --amber-200:  #fde68a;
            --amber-700:  #b45309;
            --amber-800:  #92400e;
            --amber-900:  #78350f;
            --red-50:     #fef2f2;
            --red-600:    #dc2626;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--slate-50);
            min-height: 100vh;
            color: var(--slate-900);
        }

        /* ── Top nav bar ─────────────────────────────────────────────────────── */
        .top-bar {
            background: #fff;
            border-bottom: 1px solid var(--slate-100);
            padding: 0 1.5rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .top-bar .brand {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            text-decoration: none;
        }
        .top-bar .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--indigo-600), #7c3aed);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem;
        }
        .top-bar .brand-name { font-weight: 800; font-size: 1rem; color: var(--slate-900); }
        .top-bar .brand-sub  { font-size: 0.7rem; font-weight: 600; color: var(--slate-400); }
        .ssl-badge {
            display: flex; align-items: center; gap: 0.375rem;
            background: var(--emerald-50); color: var(--emerald-600);
            padding: 0.35rem 0.75rem; border-radius: 999px;
            font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;
        }

        /* ── Page shell ──────────────────────────────────────────────────────── */
        .page { max-width: 860px; margin: 0 auto; padding: 2rem 1rem 4rem; }

        /* ── Amount banner ───────────────────────────────────────────────────── */
        .amount-banner {
            background: linear-gradient(135deg, var(--slate-900) 0%, #1e1b4b 50%, var(--slate-900) 100%);
            color: #fff;
            border-radius: 1.5rem;
            padding: 2rem;
            display: flex; align-items: center; justify-content: space-between; gap: 1.5rem;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
            box-shadow: 0 20px 40px rgba(79,70,229,.25);
        }
        .amount-label { font-size: 0.7rem; font-weight: 700; color: #a5b4fc; text-transform: uppercase; letter-spacing: 0.08em; }
        .amount-value { font-size: 2.5rem; font-weight: 900; margin-top: 0.25rem; }
        .amount-ref   { font-size: 0.7rem; color: #94a3b8; margin-top: 0.375rem; }
        .amount-meta {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 1rem; padding: 1rem 1.25rem;
            font-size: 0.75rem; text-align: right;
        }
        .amount-meta p { color: #cbd5e1; margin-bottom: 0.25rem; }
        .amount-meta strong { color: #fff; font-weight: 700; }

        /* ── Card ────────────────────────────────────────────────────────────── */
        .card {
            background: #fff;
            border: 1px solid var(--slate-100);
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
            margin-bottom: 1rem;
        }

        /* ── Payment gateway section ─────────────────────────────────────────── */
        .gateway-illustration {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .gateway-icon {
            width: 64px; height: 64px;
            background: #fff;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            box-shadow: 0 4px 12px rgba(79,70,229,.15);
            font-size: 1.75rem; color: var(--indigo-600);
        }
        .gateway-illustration h3 { font-size: 0.9rem; font-weight: 800; color: var(--slate-900); }
        .gateway-illustration p  { font-size: 0.75rem; color: var(--slate-400); margin-top: 0.375rem; }

        /* Payment method badges */
        .method-badges {
            display: flex; flex-wrap: wrap; gap: 0.5rem; justify-content: center; margin-top: 1rem;
        }
        .method-badge {
            background: #fff; border: 1px solid var(--slate-200);
            border-radius: 0.5rem; padding: 0.35rem 0.75rem;
            font-size: 0.65rem; font-weight: 700; color: var(--slate-500);
            display: flex; align-items: center; gap: 0.3rem;
        }

        /* ── Buttons ─────────────────────────────────────────────────────────── */
        .btn-pay {
            width: 100%; padding: 1rem;
            background: var(--indigo-600);
            color: #fff; font-weight: 800; font-size: 0.95rem;
            border: none; border-radius: 1rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            box-shadow: 0 8px 24px rgba(79,70,229,.3);
            transition: background .2s, transform .1s;
        }
        .btn-pay:hover   { background: var(--indigo-700); }
        .btn-pay:active  { transform: scale(.98); }
        .btn-pay:disabled { opacity: .6; cursor: not-allowed; }

        /* ── Processing overlay ───────────────────────────────────────────────── */
        #processingOverlay {
            display: none; position: fixed; inset: 0; z-index: 50;
            background: rgba(15,23,42,.8); backdrop-filter: blur(8px);
            align-items: center; justify-content: center; padding: 1rem;
        }
        #processingOverlay.show { display: flex; }
        .overlay-card {
            background: #fff; border-radius: 1.5rem; padding: 2.5rem 2rem;
            max-width: 360px; width: 100%; text-align: center;
        }
        .spin-icon {
            width: 64px; height: 64px;
            background: var(--indigo-50); color: var(--indigo-600);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem; font-size: 1.75rem;
            animation: pulse 1.4s ease-in-out infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.7;transform:scale(.95)} }
        .progress-bar { background: var(--slate-100); border-radius: 999px; height: 6px; overflow: hidden; margin-top: 1.25rem; }
        .progress-fill { background: var(--indigo-600); height: 100%; width: 100%; animation: progress 2s ease-in-out infinite; }
        @keyframes progress { 0%{transform:translateX(-100%)} 100%{transform:translateX(100%)} }

        /* ── Stuck banner ────────────────────────────────────────────────────── */
        #stuckBanner {
            display: none; position: fixed; top: 0; left: 0; right: 0; z-index: 60;
            background: #f59e0b; color: #fff;
            font-size: 0.75rem; font-weight: 700;
            padding: 0.75rem 1rem;
            align-items: center; justify-content: space-between; gap: 1rem;
        }
        #stuckBanner.show { display: flex; }
        .btn-check-status {
            background: #fff; color: #b45309;
            border: none; border-radius: 999px;
            padding: 0.35rem 1rem; font-size: 0.75rem; font-weight: 800; cursor: pointer;
            white-space: nowrap;
        }

        /* ── Trust footer ─────────────────────────────────────────────────────── */
        .trust-row {
            display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;
            margin-top: 1.5rem;
        }
        .trust-item {
            display: flex; align-items: center; gap: 0.4rem;
            font-size: 0.7rem; font-weight: 600; color: var(--slate-400);
        }

        @media(max-width:600px) {
            .amount-banner { padding: 1.5rem; }
            .amount-value  { font-size: 2rem; }
            .card { padding: 1.25rem; }
        }
    </style>
</head>
<body>


<header class="top-bar">
    <div class="brand">
        <div class="brand-icon"><i class="ri-secure-payment-line"></i></div>
        <div>
            <div class="brand-name">Payment Hub</div>
            <div class="brand-sub">Powered by FIINWAY</div>
        </div>
    </div>
    <div class="ssl-badge">
        <i class="ri-shield-check-fill"></i> 256-bit SSL
    </div>
</header>


<div id="stuckBanner">
    <span><i class="ri-wifi-off-line"></i>&nbsp; Payment may still be processing — don't close this tab!</span>
    <button class="btn-check-status" onclick="checkStatus()">Check Status</button>
</div>


<div id="processingOverlay">
    <div class="overlay-card">
        <div class="spin-icon"><i class="ri-shield-keyhole-line"></i></div>
        <h3 style="font-size:1.1rem;font-weight:900;color:var(--slate-900)">Processing Payment…</h3>
        <p style="font-size:0.78rem;color:var(--slate-400);margin-top:.5rem">
            Verifying with Cashfree. Please do not close this window.
        </p>
        <div class="progress-bar"><div class="progress-fill"></div></div>
    </div>
</div>


<main class="page">

    
    <div class="amount-banner">
        <div>
            <div class="amount-label">Total Amount Payable</div>
            <div class="amount-value">₹<?php echo e(number_format($paymentRequest->amount, 2)); ?></div>
            <div class="amount-ref">Order Ref: <?php echo e($paymentRequest->client_order_id); ?></div>
        </div>
        <div class="amount-meta">
            <p>Initiated by</p>
            <strong><?php echo e($paymentRequest->client->name); ?></strong>
            <p style="margin-top:.5rem">Payment ID</p>
            <strong>#<?php echo e($paymentRequest->id); ?></strong>
        </div>
    </div>

    
    <div class="card">
        <h2 style="font-size:1rem;font-weight:800;color:var(--slate-900);margin-bottom:1.25rem">
            <i class="ri-bank-card-line" style="color:var(--indigo-600)"></i>&ensp;Pay Online Securely
        </h2>

        
        <div class="gateway-illustration">
            <div class="gateway-icon"><i class="ri-shield-check-fill"></i></div>
            <h3>Cashfree Payment Gateway</h3>
            <p>You will be redirected to Cashfree's secure checkout popup to complete your payment.</p>
            <div class="method-badges">
                <span class="method-badge"><i class="ri-smartphone-line"></i> UPI</span>
                <span class="method-badge"><i class="ri-bank-card-line"></i> Cards</span>
                <span class="method-badge"><i class="ri-global-line"></i> NetBanking</span>
                <span class="method-badge"><i class="ri-wallet-3-line"></i> Wallets</span>
                <span class="method-badge"><i class="ri-scan-2-line"></i> QR Code</span>
            </div>
        </div>

        
        <button id="payBtn" class="btn-pay" onclick="startPayment()">
            <i class="ri-lock-line"></i>
            Pay ₹<?php echo e(number_format($paymentRequest->amount, 2)); ?> Now
        </button>
    </div>

    
    <div class="trust-row">
        <div class="trust-item"><i class="ri-shield-check-line"></i> PCI-DSS Compliant</div>
        <div class="trust-item"><i class="ri-lock-2-line"></i> 256-bit Encryption</div>
        <div class="trust-item"><i class="ri-time-line"></i> Instant Confirmation</div>
        <div class="trust-item"><i class="ri-customer-service-2-line"></i> 24/7 Support</div>
    </div>

</main>

<script>
    // ── Config ────────────────────────────────────────────────────────────────
    const MODE       = "<?php echo e(config('services.cashfree.mode', 'sandbox')); ?>";
    const SESSION_ID = "<?php echo e($paymentRequest->cashfree_session_id); ?>";
    const PR_ID      = "<?php echo e($paymentRequest->id); ?>";
    const VERIFY_URL = "<?php echo e(route('hub.verify', $paymentRequest->id)); ?>?order_id=<?php echo e($paymentRequest->cashfree_order_id); ?>";
    const LS_KEY     = 'hub_pending_payment_' + PR_ID;

    // Crash recovery: show stuck banner if we have a localStorage record from before
    window.addEventListener('DOMContentLoaded', () => {
        const saved = JSON.parse(localStorage.getItem(LS_KEY) || 'null');
        if (saved && saved.prId === PR_ID) {
            document.getElementById('stuckBanner').classList.add('show');
        }
    });

    const cashfree = Cashfree({ mode: MODE });

    function setProcessing(on) {
        const overlay = document.getElementById('processingOverlay');
        const btn     = document.getElementById('payBtn');
        if (on) { overlay.classList.add('show'); btn.disabled = true; }
        else    { overlay.classList.remove('show'); btn.disabled = false; }
    }

    function startPayment() {
        // Save to localStorage BEFORE opening popup (crash guard)
        localStorage.setItem(LS_KEY, JSON.stringify({ prId: PR_ID, verifyUrl: VERIFY_URL, savedAt: Date.now() }));
        setProcessing(true);

        cashfree.checkout({
            paymentSessionId: SESSION_ID,
            redirectTarget: "_modal",
        }).then((result) => {
            setProcessing(false);

            if (result.error) {
                // User closed / cancelled the Cashfree popup
                const confirmed = confirm(
                    'Are you sure you want to cancel this payment?\n\nYou will be redirected back to the requesting application.'
                );
                if (confirmed) {
                    localStorage.removeItem(LS_KEY);
                    // Redirect to client return_url with status=user_dropped
                    window.location.href = "<?php echo e($paymentRequest->return_url); ?>" +
                        "?client_order_id=<?php echo e(urlencode($paymentRequest->client_order_id)); ?>&status=user_dropped";
                }
                return;
            }

            if (result.redirect) {
                // Cashfree is doing a full-page redirect — keep localStorage alive
                return;
            }

            if (result.paymentDetails) {
                // Payment flow completed — verify with backend
                verifyWithRetry(VERIFY_URL);
            }

        }).catch((err) => {
            setProcessing(false);
            console.error('Cashfree SDK error:', err);
            document.getElementById('stuckBanner').classList.add('show');
        });
    }

    // Retry verification with exponential backoff (2s → 4s → 8s)
    async function verifyWithRetry(url, attempt = 1) {
        setProcessing(true);

        const bannerTimer = setTimeout(() => {
            document.getElementById('stuckBanner').classList.add('show');
        }, 12000);

        try {
            const res = await fetch(url, { redirect: 'follow' });
            clearTimeout(bannerTimer);
            localStorage.removeItem(LS_KEY);

            if (res.ok || res.redirected) {
                window.location.href = res.url || "<?php echo e($paymentRequest->return_url); ?>";
            } else {
                throw new Error('HTTP ' + res.status);
            }
        } catch (e) {
            clearTimeout(bannerTimer);
            if (attempt <= 3) {
                const delay = Math.pow(2, attempt) * 1000;
                console.warn(`Verify attempt ${attempt} failed. Retrying in ${delay}ms...`);
                setTimeout(() => verifyWithRetry(url, attempt + 1), delay);
            } else {
                setProcessing(false);
                document.getElementById('stuckBanner').classList.add('show');
            }
        }
    }

    // Manual status check via stuck banner button
    async function checkStatus() {
        try {
            // Hit our public status endpoint (no auth needed — it's by ID)
            const res  = await fetch('/api/hub/status/' + PR_ID);
            const data = await res.json();
            if (data.status === 'success') {
                localStorage.removeItem(LS_KEY);
                window.location.href = "<?php echo e($paymentRequest->return_url); ?>" +
                    "?client_order_id=<?php echo e(urlencode($paymentRequest->client_order_id)); ?>&status=success";
            } else if (data.status === 'pending') {
                alert('Payment is still being processed. Please wait a moment and try again.');
            } else {
                alert('Payment status: ' + data.status + '. Please contact support if you believe this is an error.');
            }
        } catch (e) {
            alert('Could not reach server. Please check your internet connection.');
        }
    }
</script>

</body>
</html>
<?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/hub/payment.blade.php ENDPATH**/ ?>