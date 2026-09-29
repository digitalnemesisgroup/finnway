<?php $__env->startSection('title', 'API Credentials & Developer Integration Docs'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ activeTab: 'php', copiedKey: false, copiedSalt: false }">

    <!-- ── Header Banner ───────────────────────────────────────────────────────── -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-blue-950 rounded-2xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border border-slate-700/60">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-blue-500/20 text-blue-300 border border-blue-500/30">
                    Developer & API Suite
                </span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">API Credentials & Integration Docs</h1>
            <p class="text-sm text-slate-300 mt-1">
                Manage your API credentials, generate HMAC signatures, and integrate FIINWAY Payment Hub into your business app.
            </p>
        </div>
        <a href="<?php echo e(route('hub.portal.dashboard')); ?>" class="px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 text-xs font-bold text-white transition flex items-center gap-2">
            <i class="ri-arrow-left-line"></i> Back to Dashboard
        </a>
    </div>

    <!-- ── API Credentials Card ────────────────────────────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
        <div class="flex items-center justify-between border-b border-slate-200 pb-4">
            <div>
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="ri-key-2-fill text-blue-600"></i> Your Production API Credentials
                </h3>
                <p class="text-xs text-slate-500">Never share your API Salt publicly. Store it securely in your server environment variables.</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo e($client->isLive() ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800'); ?>">
                <i class="ri-checkbox-circle-fill"></i> <?php echo e($client->isLive() ? 'LIVE & ACTIVE' : 'APPROVAL PENDING'); ?>

            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- API Key -->
            <div class="space-y-2">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600">X-Api-Key (Public Identifier)</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="<?php echo e($client->api_key ?: 'Key pending approval'); ?>" id="api-key-input"
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold text-slate-800 focus:outline-none select-all">
                    <button type="button" @click="navigator.clipboard.writeText('<?php echo e($client->api_key); ?>'); copiedKey = true; setTimeout(() => copiedKey = false, 2000)"
                        class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-1.5 shadow-sm">
                        <i :class="copiedKey ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                        <span x-text="copiedKey ? 'Copied!' : 'Copy Key'"></span>
                    </button>
                </div>
            </div>

            <!-- API Salt -->
            <div class="space-y-2" x-data="{ revealSalt: false }">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600">API Salt (HMAC Secret)</label>
                <div class="flex items-center gap-2">
                    <input :type="revealSalt ? 'text' : 'password'" readonly value="<?php echo e($client->api_salt ?: 'Salt pending approval'); ?>" id="api-salt-input"
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold text-slate-800 focus:outline-none select-all">
                    <button type="button" @click="revealSalt = !revealSalt" class="px-3 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold transition shrink-0" title="Toggle Reveal">
                        <i :class="revealSalt ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                    </button>
                    <button type="button" @click="navigator.clipboard.writeText('<?php echo e($client->api_salt); ?>'); copiedSalt = true; setTimeout(() => copiedSalt = false, 2000)"
                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-1.5 shadow-sm">
                        <i :class="copiedSalt ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                        <span x-text="copiedSalt ? 'Copied!' : 'Copy Salt'"></span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Account Validity Details -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Starts On</span>
                <span class="font-bold text-slate-800"><?php echo e($client->starts_at ? $client->starts_at->format('d M Y') : 'N/A'); ?></span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Expires On</span>
                <span class="font-bold text-slate-800"><?php echo e($client->expires_at ? $client->expires_at->format('d M Y') : 'N/A'); ?></span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Gateway Fee</span>
                <span class="font-bold text-blue-600"><?php echo e(number_format($client->gateway_charge_percent, 2)); ?>% + <?php echo e(number_format($client->gst_on_charge_percent, 2)); ?>% GST</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Registered Email</span>
                <span class="font-bold text-slate-800"><?php echo e($client->business_email); ?></span>
            </div>
        </div>
    </div>

    <!-- ── API Integration Guide ───────────────────────────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
        <div>
            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                <i class="ri-code-box-line text-blue-600"></i> Integration Walkthrough
            </h3>
            <p class="text-xs text-slate-500">Integrate FIINWAY Payment Hub in 3 simple steps</p>
        </div>

        <!-- Step 1: Signature Generation -->
        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-black flex items-center justify-center">1</span>
                <h4 class="font-bold text-slate-800 text-sm">Compute HMAC-SHA256 Signature</h4>
            </div>
            <p class="text-xs text-slate-600 pl-8">
                Create a canonical payload string separated by pipe (`|`):
                <code class="bg-slate-100 px-2 py-1 rounded text-slate-800 font-mono text-[11px]">client_order_id|amount|return_url</code>
                then generate SHA-256 HMAC using your <code class="bg-slate-100 px-1 py-0.5 rounded font-mono text-[11px]">API Salt</code>.
            </p>
        </div>

        <!-- Step 2: Initiate Payment API Endpoint -->
        <div class="space-y-3">
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-black flex items-center justify-center">2</span>
                <h4 class="font-bold text-slate-800 text-sm">Initiate Payment Endpoint</h4>
            </div>
            <div class="pl-8 space-y-2">
                <div class="flex items-center gap-2 bg-slate-900 text-white px-4 py-2.5 rounded-xl font-mono text-xs w-fit">
                    <span class="px-2 py-0.5 rounded bg-emerald-500 text-slate-950 font-bold">POST</span>
                    <span><?php echo e(url('/api/hub/initiate')); ?></span>
                </div>
                <div class="text-xs text-slate-600">
                    Header: <code class="bg-slate-100 px-2 py-0.5 rounded font-mono">X-Api-Key: <?php echo e($client->api_key); ?></code>
                </div>
            </div>
        </div>

        <!-- Step 3: Interactive Code Snippets Tabs -->
        <div class="space-y-4 pt-2 border-t border-slate-200">
            <div class="flex items-center justify-between">
                <h4 class="font-bold text-slate-800 text-sm">Code Integration Examples</h4>

                <!-- Language Tabs -->
                <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                    <button @click="activeTab = 'php'" :class="activeTab === 'php' ? 'bg-white font-bold text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3 py-1.5 text-xs rounded-lg transition">PHP (cURL)</button>
                    <button @click="activeTab = 'node'" :class="activeTab === 'node' ? 'bg-white font-bold text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3 py-1.5 text-xs rounded-lg transition">Node.js</button>
                    <button @click="activeTab = 'python'" :class="activeTab === 'python' ? 'bg-white font-bold text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3 py-1.5 text-xs rounded-lg transition">Python</button>
                    <button @click="activeTab = 'curl'" :class="activeTab === 'curl' ? 'bg-white font-bold text-blue-600 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                        class="px-3 py-1.5 text-xs rounded-lg transition">cURL</button>
                </div>
            </div>

            <!-- PHP Snippet -->
            <div x-show="activeTab === 'php'" x-cloak class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code>&lt;?php
$apiKey = "<?php echo e($client->api_key); ?>";
$apiSalt = "<?php echo e($client->api_salt); ?>";

$clientOrderId = "ORD_" . time();
$amount = "500.00";
$returnUrl = "https://yourbusiness.com/checkout/callback";
$webhookUrl = "https://yourbusiness.com/api/webhooks/fiinway";

// 1. Build canonical string and generate HMAC signature
$canonicalString = $clientOrderId . '|' . $amount . '|' . $returnUrl;
$signature = hash_hmac('sha256', $canonicalString, $apiSalt);

// 2. Send POST request
$payload = json_encode([
    'client_order_id' =&gt; $clientOrderId,
    'amount'          =&gt; $amount,
    'customer_phone'  =&gt; '9876543210',
    'customer_email'  =&gt; 'customer@example.com',
    'return_url'      =&gt; $returnUrl,
    'webhook_url'     =&gt; $webhookUrl,
    'signature'       =&gt; $signature,
]);

$ch = curl_init("<?php echo e(url('/api/hub/initiate')); ?>");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Api-Key: ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

// 3. Redirect customer to payment_url
if (isset($response['payment_url'])) {
    header("Location: " . $response['payment_url']);
    exit;
}
</code></pre>
            </div>

            <!-- Node.js Snippet -->
            <div x-show="activeTab === 'node'" x-cloak class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code>const crypto = require('crypto');
const axios = require('axios');

const apiKey = "<?php echo e($client->api_key); ?>";
const apiSalt = "<?php echo e($client->api_salt); ?>";

const clientOrderId = `ORD_${Date.now()}`;
const amount = "500.00";
const returnUrl = "https://yourbusiness.com/checkout/callback";

// 1. Create HMAC signature
const canonicalString = `${clientOrderId}|${amount}|${returnUrl}`;
const signature = crypto.createHmac('sha256', apiSalt).update(canonicalString).digest('hex');

// 2. Initiate payment request
async function initiatePayment() {
    const response = await axios.post("<?php echo e(url('/api/hub/initiate')); ?>", {
        client_order_id: clientOrderId,
        amount: amount,
        customer_phone: "9876543210",
        customer_email: "customer@example.com",
        return_url: returnUrl,
        webhook_url: "https://yourbusiness.com/api/webhooks/fiinway",
        signature: signature
    }, {
        headers: {
            'Content-Type': 'application/json',
            'X-Api-Key': apiKey
        }
    });

    console.log("Redirect URL:", response.data.payment_url);
}
initiatePayment();
</code></pre>
            </div>

            <!-- Python Snippet -->
            <div x-show="activeTab === 'python'" x-cloak class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code>import hmac
import hashlib
import time
import requests

api_key = "<?php echo e($client->api_key); ?>"
api_salt = "<?php echo e($client->api_salt); ?>"

client_order_id = f"ORD_{int(time.time())}"
amount = "500.00"
return_url = "https://yourbusiness.com/checkout/callback"

# 1. Compute HMAC signature
canonical_string = f"{client_order_id}|{amount}|{return_url}"
signature = hmac.new(api_salt.encode('utf-8'), canonical_string.encode('utf-8'), hashlib.sha256).hexdigest()

# 2. Send API request
payload = {
    "client_order_id": client_order_id,
    "amount": amount,
    "customer_phone": "9876543210",
    "customer_email": "customer@example.com",
    "return_url": return_url,
    "webhook_url": "https://yourbusiness.com/api/webhooks/fiinway",
    "signature": signature
}

headers = {
    "Content-Type": "application/json",
    "X-Api-Key": api_key
}

response = requests.post("<?php echo e(url('/api/hub/initiate')); ?>", json=payload, headers=headers)
print("Payment URL:", response.json().get("payment_url"))
</code></pre>
            </div>

            <!-- cURL Snippet -->
            <div x-show="activeTab === 'curl'" x-cloak class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code>curl -X POST "<?php echo e(url('/api/hub/initiate')); ?>" \
  -H "Content-Type: application/json" \
  -H "X-Api-Key: <?php echo e($client->api_key); ?>" \
  -d '{
    "client_order_id": "ORD_12345",
    "amount": "500.00",
    "customer_phone": "9876543210",
    "customer_email": "customer@example.com",
    "return_url": "https://yourbusiness.com/checkout/callback",
    "webhook_url": "https://yourbusiness.com/api/webhooks/fiinway",
    "signature": "&lt;COMPUTED_HMAC_SHA256_SIGNATURE&gt;"
  }'
</code></pre>
            </div>

        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('hub.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/hub/portal/developer.blade.php ENDPATH**/ ?>