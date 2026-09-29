<?php $__env->startSection('title', 'API Integration Docs'); ?>
<?php $__env->startSection('page_title', 'Payment Gateway Integration Docs'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="{ gateway: 'cashfree', activeTab: 'php' }">

    <!-- ── Gateway Switcher Header ─────────────────────────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-black text-slate-800 tracking-tight flex items-center gap-2">
                <i class="ri-code-box-line text-blue-600"></i> Gateway Integration Docs
            </h3>
            <p class="text-xs text-slate-500">Select a payment provider below to view dedicated API endpoints, HMAC signature rules, and code snippets for FINNWAY 360°.</p>
        </div>

        <!-- Provider Switcher Buttons -->
        <div class="flex items-center gap-2 bg-slate-100 p-1.5 rounded-xl border border-slate-200 shrink-0">
            <button @click="gateway = 'cashfree'; activeTab = 'php'" :class="gateway === 'cashfree' ? 'bg-blue-600 text-white font-black shadow-md' : 'text-slate-600 hover:text-slate-900 font-bold'"
                class="px-4 py-2 text-xs rounded-lg transition flex items-center gap-2">
                <span>💳 Cashfree Gateway</span>
            </button>
            <button @click="gateway = 'phonepe'; activeTab = 'php'" :class="gateway === 'phonepe' ? 'bg-indigo-600 text-white font-black shadow-md' : 'text-slate-600 hover:text-slate-900 font-bold'"
                class="px-4 py-2 text-xs rounded-lg transition flex items-center gap-2">
                <span>🪪 PhonePe Gateway</span>
            </button>
        </div>
    </div>

    <!-- ───────────────────────────────────────────────────────────────────────── -->
    <!-- 1. CASHFREE GATEWAY INTEGRATION DOCS                                     -->
    <!-- ───────────────────────────────────────────────────────────────────────── -->
    <div x-show="gateway === 'cashfree'" x-cloak class="space-y-6">

        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-black text-lg">CF</span>
                    <div>
                        <h4 class="font-black text-slate-800 text-base">Cashfree Gateway Integration</h4>
                        <p class="text-xs text-slate-500">Accept Credit/Debit Cards, NetBanking, UPI, and Wallets via Cashfree checkout</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200">API v1.0</span>
            </div>

            <!-- Steps -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Endpoint -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600">Endpoint URL</label>
                    <div class="flex items-center gap-2 bg-slate-900 text-white px-4 py-3 rounded-xl font-mono text-xs overflow-x-auto">
                        <span class="px-2 py-0.5 rounded bg-emerald-500 text-slate-950 font-bold">POST</span>
                        <span><?php echo e(url('/api/hub/initiate')); ?></span>
                    </div>
                </div>

                <!-- Headers -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600">Required Headers</label>
                    <div class="bg-slate-100 border border-slate-300 rounded-xl px-4 py-3 font-mono text-xs space-y-1">
                        <p><strong class="text-slate-800">Content-Type:</strong> application/json</p>
                        <p><strong class="text-slate-800">X-Api-Key:</strong> <?php echo e($client->api_key); ?></p>
                    </div>
                </div>

            </div>

            <!-- Signature Rule -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-xs space-y-1.5">
                <h5 class="font-bold text-blue-900 text-xs">HMAC-SHA256 Signature Rule</h5>
                <p class="text-blue-800 leading-relaxed">
                    Canonical String: <code class="bg-white px-2 py-0.5 rounded font-mono font-bold text-slate-800 border border-blue-200">client_order_id|amount|return_url</code><br>
                    Compute SHA-256 HMAC of this string using your <code class="bg-white px-1 py-0.5 rounded font-mono font-bold text-slate-800 border border-blue-200">API Salt</code>.
                </p>
            </div>

            <!-- Code Snippets Tabs for Cashfree -->
            <div class="space-y-4 pt-2 border-t border-slate-200">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h5 class="font-bold text-slate-800 text-xs uppercase tracking-wider">Cashfree Integration Code Snippets</h5>

                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                        <button @click="activeTab = 'php'" :class="activeTab === 'php' ? 'bg-white font-bold text-blue-600 shadow-sm' : 'text-slate-600'" class="px-3 py-1 text-xs rounded-lg transition">PHP</button>
                        <button @click="activeTab = 'node'" :class="activeTab === 'node' ? 'bg-white font-bold text-blue-600 shadow-sm' : 'text-slate-600'" class="px-3 py-1 text-xs rounded-lg transition">Node.js</button>
                        <button @click="activeTab = 'python'" :class="activeTab === 'python' ? 'bg-white font-bold text-blue-600 shadow-sm' : 'text-slate-600'" class="px-3 py-1 text-xs rounded-lg transition">Python</button>
                        <button @click="activeTab = 'curl'" :class="activeTab === 'curl' ? 'bg-white font-bold text-blue-600 shadow-sm' : 'text-slate-600'" class="px-3 py-1 text-xs rounded-lg transition">cURL</button>
                    </div>
                </div>

                <!-- PHP Snippet -->
                <div x-show="activeTab === 'php'" class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code>&lt;?php
// Cashfree Payment Hub Integration (PHP)
$apiKey  = "<?php echo e($client->api_key); ?>";
$apiSalt = "<?php echo e($client->api_salt); ?>";

$clientOrderId = "CF_ORD_" . time();
$amount        = "1500.00";
$returnUrl     = "https://yourbusiness.com/payment/callback";
$webhookUrl    = "https://yourbusiness.com/api/webhooks/fiinway";

// 1. Generate HMAC-SHA256 signature
$canonicalString = $clientOrderId . '|' . $amount . '|' . $returnUrl;
$signature = hash_hmac('sha256', $canonicalString, $apiSalt);

// 2. Post payload to FIINWAY Cashfree Endpoint
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
<pre><code>// Cashfree Payment Hub Integration (Node.js)
const crypto = require('crypto');
const axios = require('axios');

const apiKey  = "<?php echo e($client->api_key); ?>";
const apiSalt = "<?php echo e($client->api_salt); ?>";

const clientOrderId = `CF_ORD_${Date.now()}`;
const amount = "1500.00";
const returnUrl = "https://yourbusiness.com/payment/callback";

const canonicalString = `${clientOrderId}|${amount}|${returnUrl}`;
const signature = crypto.createHmac('sha256', apiSalt).update(canonicalString).digest('hex');

async function initiateCashfreePayment() {
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

    console.log("Cashfree Checkout URL:", response.data.payment_url);
}
initiateCashfreePayment();
</code></pre>
                </div>

                <!-- Python Snippet -->
                <div x-show="activeTab === 'python'" x-cloak class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code># Cashfree Payment Hub Integration (Python)
import hmac
import hashlib
import time
import requests

api_key  = "<?php echo e($client->api_key); ?>"
api_salt = "<?php echo e($client->api_salt); ?>"

client_order_id = f"CF_ORD_{int(time.time())}"
amount = "1500.00"
return_url = "https://yourbusiness.com/payment/callback"

canonical_string = f"{client_order_id}|{amount}|{return_url}"
signature = hmac.new(api_salt.encode('utf-8'), canonical_string.encode('utf-8'), hashlib.sha256).hexdigest()

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
print("Redirect URL:", response.json().get("payment_url"))
</code></pre>
                </div>

                <!-- cURL Snippet -->
                <div x-show="activeTab === 'curl'" x-cloak class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code>curl -X POST "<?php echo e(url('/api/hub/initiate')); ?>" \
  -H "Content-Type: application/json" \
  -H "X-Api-Key: <?php echo e($client->api_key); ?>" \
  -d '{
    "client_order_id": "CF_ORD_12345",
    "amount": "1500.00",
    "customer_phone": "9876543210",
    "customer_email": "customer@example.com",
    "return_url": "https://yourbusiness.com/payment/callback",
    "webhook_url": "https://yourbusiness.com/api/webhooks/fiinway",
    "signature": "&lt;HMAC_SHA256_SIGNATURE&gt;"
  }'
</code></pre>
                </div>

            </div>

        </div>

    </div>

    <!-- ───────────────────────────────────────────────────────────────────────── -->
    <!-- 2. PHONEPE GATEWAY INTEGRATION DOCS                                       -->
    <!-- ───────────────────────────────────────────────────────────────────────── -->
    <div x-show="gateway === 'phonepe'" x-cloak class="space-y-6">

        <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-200 pb-4">
                <div class="flex items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-lg">PE</span>
                    <div>
                        <h4 class="font-black text-slate-800 text-base">PhonePe Gateway Integration</h4>
                        <p class="text-xs text-slate-500">Accept PhonePe UPI, QR, Intent, and Card checkout seamlessly</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">PhonePe v2 SDK</span>
            </div>

            <!-- Steps -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Endpoint -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600">Endpoint URL</label>
                    <div class="flex items-center gap-2 bg-slate-900 text-white px-4 py-3 rounded-xl font-mono text-xs overflow-x-auto">
                        <span class="px-2 py-0.5 rounded bg-emerald-500 text-slate-950 font-bold">POST</span>
                        <span><?php echo e(url('/api/hub/phonepe/initiate')); ?></span>
                    </div>
                </div>

                <!-- Headers -->
                <div class="space-y-2">
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600">Required Headers</label>
                    <div class="bg-slate-100 border border-slate-300 rounded-xl px-4 py-3 font-mono text-xs space-y-1">
                        <p><strong class="text-slate-800">Content-Type:</strong> application/json</p>
                        <p><strong class="text-slate-800">X-Api-Key:</strong> <?php echo e($client->api_key); ?></p>
                    </div>
                </div>

            </div>

            <!-- Signature Rule -->
            <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-xs space-y-1.5">
                <h5 class="font-bold text-indigo-900 text-xs">HMAC-SHA256 Signature Rule</h5>
                <p class="text-indigo-800 leading-relaxed">
                    Canonical String: <code class="bg-white px-2 py-0.5 rounded font-mono font-bold text-slate-800 border border-indigo-200">client_order_id|amount|return_url</code><br>
                    Compute SHA-256 HMAC of this string using your <code class="bg-white px-1 py-0.5 rounded font-mono font-bold text-slate-800 border border-indigo-200">API Salt</code>.
                </p>
            </div>

            <!-- Code Snippets Tabs for PhonePe -->
            <div class="space-y-4 pt-2 border-t border-slate-200">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <h5 class="font-bold text-slate-800 text-xs uppercase tracking-wider">PhonePe Integration Code Snippets</h5>

                    <div class="flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
                        <button @click="activeTab = 'php'" :class="activeTab === 'php' ? 'bg-white font-bold text-indigo-600 shadow-sm' : 'text-slate-600'" class="px-3 py-1 text-xs rounded-lg transition">PHP</button>
                        <button @click="activeTab = 'node'" :class="activeTab === 'node' ? 'bg-white font-bold text-indigo-600 shadow-sm' : 'text-slate-600'" class="px-3 py-1 text-xs rounded-lg transition">Node.js</button>
                        <button @click="activeTab = 'python'" :class="activeTab === 'python' ? 'bg-white font-bold text-indigo-600 shadow-sm' : 'text-slate-600'" class="px-3 py-1 text-xs rounded-lg transition">Python</button>
                        <button @click="activeTab = 'curl'" :class="activeTab === 'curl' ? 'bg-white font-bold text-indigo-600 shadow-sm' : 'text-slate-600'" class="px-3 py-1 text-xs rounded-lg transition">cURL</button>
                    </div>
                </div>

                <!-- PHP Snippet -->
                <div x-show="activeTab === 'php'" class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code>&lt;?php
// PhonePe Payment Hub Integration (PHP)
$apiKey  = "<?php echo e($client->api_key); ?>";
$apiSalt = "<?php echo e($client->api_salt); ?>";

$clientOrderId = "PPE_ORD_" . time();
$amount        = "2000.00";
$returnUrl     = "https://yourbusiness.com/payment/callback";
$webhookUrl    = "https://yourbusiness.com/api/webhooks/fiinway";

// 1. Generate HMAC-SHA256 signature
$canonicalString = $clientOrderId . '|' . $amount . '|' . $returnUrl;
$signature = hash_hmac('sha256', $canonicalString, $apiSalt);

// 2. Post payload to FIINWAY PhonePe Endpoint
$payload = json_encode([
    'client_order_id' =&gt; $clientOrderId,
    'amount'          =&gt; $amount,
    'customer_phone'  =&gt; '9876543210',
    'customer_email'  =&gt; 'customer@example.com',
    'return_url'      =&gt; $returnUrl,
    'webhook_url'     =&gt; $webhookUrl,
    'signature'       =&gt; $signature,
]);

$ch = curl_init("<?php echo e(url('/api/hub/phonepe/initiate')); ?>");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-Api-Key: ' . $apiKey
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = json_decode(curl_exec($ch), true);
curl_close($ch);

// 3. Redirect customer to PhonePe payment_url
if (isset($response['payment_url'])) {
    header("Location: " . $response['payment_url']);
    exit;
}
</code></pre>
                </div>

                <!-- Node.js Snippet -->
                <div x-show="activeTab === 'node'" x-cloak class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code>// PhonePe Payment Hub Integration (Node.js)
const crypto = require('crypto');
const axios = require('axios');

const apiKey  = "<?php echo e($client->api_key); ?>";
const apiSalt = "<?php echo e($client->api_salt); ?>";

const clientOrderId = `PPE_ORD_${Date.now()}`;
const amount = "2000.00";
const returnUrl = "https://yourbusiness.com/payment/callback";

const canonicalString = `${clientOrderId}|${amount}|${returnUrl}`;
const signature = crypto.createHmac('sha256', apiSalt).update(canonicalString).digest('hex');

async function initiatePhonePePayment() {
    const response = await axios.post("<?php echo e(url('/api/hub/phonepe/initiate')); ?>", {
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

    console.log("PhonePe Redirect URL:", response.data.payment_url);
}
initiatePhonePePayment();
</code></pre>
                </div>

                <!-- Python Snippet -->
                <div x-show="activeTab === 'python'" x-cloak class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code># PhonePe Payment Hub Integration (Python)
import hmac
import hashlib
import time
import requests

api_key  = "<?php echo e($client->api_key); ?>"
api_salt = "<?php echo e($client->api_salt); ?>"

client_order_id = f"PPE_ORD_{int(time.time())}"
amount = "2000.00"
return_url = "https://yourbusiness.com/payment/callback"

canonical_string = f"{client_order_id}|{amount}|{return_url}"
signature = hmac.new(api_salt.encode('utf-8'), canonical_string.encode('utf-8'), hashlib.sha256).hexdigest()

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

response = requests.post("<?php echo e(url('/api/hub/phonepe/initiate')); ?>", json=payload, headers=headers)
print("PhonePe Payment URL:", response.json().get("payment_url"))
</code></pre>
                </div>

                <!-- cURL Snippet -->
                <div x-show="activeTab === 'curl'" x-cloak class="bg-slate-900 rounded-xl p-4 text-xs font-mono text-slate-200 overflow-x-auto">
<pre><code>curl -X POST "<?php echo e(url('/api/hub/phonepe/initiate')); ?>" \
  -H "Content-Type: application/json" \
  -H "X-Api-Key: <?php echo e($client->api_key); ?>" \
  -d '{
    "client_order_id": "PPE_ORD_12345",
    "amount": "2000.00",
    "customer_phone": "9876543210",
    "customer_email": "customer@example.com",
    "return_url": "https://yourbusiness.com/payment/callback",
    "webhook_url": "https://yourbusiness.com/api/webhooks/fiinway",
    "signature": "&lt;HMAC_SHA256_SIGNATURE&gt;"
  }'
</code></pre>
                </div>

            </div>

        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hub.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/hub/portal/docs.blade.php ENDPATH**/ ?>