@extends('layouts.admin')

@section('title', 'API Documentation - Payment Hub')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Fira+Code:wght@400;500;600&display=swap');

    .premium-docs { font-family: 'Inter', sans-serif; background: #ffffff; color: #334155; border-radius: 16px; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
    .premium-docs .docs-title { font-size: 2.75rem; font-weight: 800; letter-spacing: -0.04em; background: linear-gradient(135deg, #0f172a 0%, #3b82f6 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1.2; }
    
    .docs-grid { display: grid; grid-template-columns: 1fr; border-bottom: 1px solid #f1f5f9; }
    @media(min-width: 1024px) {
        .docs-grid { grid-template-columns: 1fr 1fr; }
        .docs-left { padding: 4rem 3rem 4rem 4rem; }
        .docs-right { background: #0f172a; padding: 4rem; position: relative; border-left: 1px solid #1e293b; }
        .docs-right::before { content:''; position:absolute; top:-20%; left:-20%; width:140%; height:140%; background: radial-gradient(circle at 50% 50%, rgba(56,189,248,0.08) 0%, transparent 60%); pointer-events: none; }
        .docs-sticky { position: sticky; top: 2rem; }
    }
    
    /* Reveal Animation */
    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.active { opacity: 1; transform: translateY(0); }

    /* Code Window */
    .premium-ide { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255,255,255,0.1); overflow: hidden; font-family: 'Fira Code', monospace; margin-bottom: 2rem; transition: transform 0.3s ease; }
    .premium-ide:hover { transform: translateY(-2px); box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.6), inset 0 1px 0 rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.15); }
    .premium-ide-header { display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.05); background: rgba(255,255,255,0.02); }
    
    .ide-controls { display: flex; gap: 8px; }
    .ide-dot { width: 12px; height: 12px; border-radius: 50%; box-shadow: inset 0 1px 1px rgba(255,255,255,0.2); }
    .dot-red { background: #ff5f56; border: 1px solid #e0443e; } .dot-yellow { background: #ffbd2e; border: 1px solid #dea123; } .dot-green { background: #27c93f; border: 1px solid #1aab29; }
    
    .premium-ide-tabs { display: flex; gap: 0.5rem; background: rgba(0,0,0,0.2); padding: 0.25rem; border-radius: 999px; }
    .premium-ide-tab { font-size: 0.75rem; color: #64748b; padding: 0.25rem 0.75rem; border-radius: 999px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-family: 'Inter', sans-serif; font-weight: 600; cursor: pointer; border: none; outline: none; }
    .premium-ide-tab:hover { color: #f8fafc; }
    .premium-ide-tab.active { color: #fff; background: rgba(255,255,255,0.1); box-shadow: 0 1px 3px rgba(0,0,0,0.3); }
    
    .premium-ide-body { padding: 1.5rem; font-size: 0.85rem; line-height: 1.7; color: #e2e8f0; overflow-x: auto; }
    .premium-ide-body pre { margin: 0; }
    
    /* Syntax */
    .c-kw { color: #ff7b72; font-weight: 500; } .c-str { color: #a5d6ff; } .c-fn { color: #d2a8ff; } .c-cm { color: #8b949e; font-style: italic; } .c-num { color: #79c0ff; } .c-op { color: #ff7b72; } .c-var { color: #c9d1d9; }
    
    .endpoint-badge { display: inline-flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.35rem 1rem; font-family: 'Fira Code', monospace; font-size: 0.85rem; box-shadow: 0 1px 2px rgba(0,0,0,0.05); margin-bottom: 2rem; color: #475569; }
    .badge-post { color: #10b981; font-weight: 700; margin-right: 0.75rem; background: #d1fae5; padding: 0.1rem 0.4rem; border-radius: 4px; font-family: 'Inter', sans-serif; font-size: 0.75rem; }
    .badge-get { color: #3b82f6; font-weight: 700; margin-right: 0.75rem; background: #dbeafe; padding: 0.1rem 0.4rem; border-radius: 4px; font-family: 'Inter', sans-serif; font-size: 0.75rem; }
    .badge-webhook { color: #8b5cf6; font-weight: 700; margin-right: 0.75rem; background: #ede9fe; padding: 0.1rem 0.4rem; border-radius: 4px; font-family: 'Inter', sans-serif; font-size: 0.75rem; }
    
    .docs-h2 { font-size: 1.5rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem; display: flex; align-items: center; gap: 1rem; letter-spacing: -0.02em; }
    .step-number { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 800; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3); }
    
    .docs-p { font-size: 1rem; color: #475569; line-height: 1.7; margin-bottom: 1.5rem; }
    
    .param-table { width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 2rem; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
    .param-table th { text-align: left; padding: 1rem 1.25rem; background: #f8fafc; color: #334155; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #e2e8f0; }
    .param-table td { padding: 1.25rem; border-bottom: 1px solid #e2e8f0; vertical-align: top; background: #ffffff; }
    .param-table tr:last-child td { border-bottom: none; }
    .param-name { font-family: 'Fira Code', monospace; font-weight: 600; color: #0f172a; font-size: 0.85rem; }
    .param-req { color: #ef4444; font-size: 0.65rem; font-weight: 800; margin-left: 0.5rem; background: #fee2e2; padding: 0.15rem 0.4rem; border-radius: 4px; letter-spacing: 0.05em; }
</style>
@endpush

@section('content')
<div class="premium-docs mb-10" x-data="{ 
    observe() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    }
}" x-init="observe()">

    <!-- Header Section -->
    <div class="bg-slate-50 border-b border-slate-200 px-8 py-12 lg:px-16 lg:py-16 relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-96 h-96 bg-blue-500 opacity-5 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="reveal">
                <h1 class="docs-title mb-3">Payment Engine API</h1>
                <p class="text-lg text-slate-500 font-medium max-w-2xl">Integrate secure, high-performance payment processing into your application with our robust developer-first API.</p>
            </div>
            <div class="reveal" style="transition-delay: 100ms;">
                <a href="{{ route('admin.payment-clients.index') }}" class="inline-flex items-center gap-2 bg-slate-900 hover:bg-slate-800 text-white px-6 py-3 rounded-xl font-semibold transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    <i class="ri-key-2-fill text-blue-400"></i> Manage API Keys
                </a>
            </div>
        </div>
    </div>

    <!-- Section 1: Authentication -->
    <div class="docs-grid">
        <div class="docs-left reveal">
            <h2 class="docs-h2"><span class="step-number">1</span> Authentication</h2>
            <p class="docs-p">
                To securely interact with the FIINWAY Payment Hub, requests must be authenticated. Include your <strong>API Key</strong> in the headers and sign your payload with an <strong>HMAC-SHA256 signature</strong> using your private <strong>API Salt</strong>.
            </p>
            
            <div class="bg-blue-50 border border-blue-100 p-5 rounded-xl mb-8 flex gap-4 shadow-sm">
                <div class="bg-blue-100 text-blue-600 w-10 h-10 rounded-full flex items-center justify-center shrink-0">
                    <i class="ri-shield-keyhole-fill text-lg"></i>
                </div>
                <div>
                    <h4 class="font-bold text-blue-900 mb-1">Keep your Salt Secret</h4>
                    <p class="text-sm text-blue-800 leading-relaxed">Never embed your API Salt in a mobile app or frontend code. Signatures must be generated exclusively on your secure backend server.</p>
                </div>
            </div>

            <h3 class="font-bold text-slate-800 mb-3 text-sm uppercase letter-spacing-wide">Signature Payload Format</h3>
            <p class="docs-p text-sm">
                Before generating the hash, construct a raw string using the exact pipe-separated format below:
            </p>
            <div class="bg-slate-100 p-4 rounded-xl border border-slate-200 mb-4">
                <code class="text-pink-600 font-bold font-mono text-sm">{client_order_id}|{amount}|{return_url}</code>
            </div>
        </div>
        
        <div class="docs-right">
            <div class="docs-sticky reveal" style="transition-delay: 200ms;">
                <div class="premium-ide" x-data="{ lang: 'node' }">
                    <div class="premium-ide-header">
                        <div class="ide-controls">
                            <div class="ide-dot dot-red"></div><div class="ide-dot dot-yellow"></div><div class="ide-dot dot-green"></div>
                        </div>
                        <div class="premium-ide-tabs">
                            <button @click="lang = 'node'" class="premium-ide-tab" :class="lang === 'node' ? 'active' : ''">Node.js</button>
                            <button @click="lang = 'php'" class="premium-ide-tab" :class="lang === 'php' ? 'active' : ''">PHP</button>
                            <button @click="lang = 'python'" class="premium-ide-tab" :class="lang === 'python' ? 'active' : ''">Python</button>
                        </div>
                    </div>
                    
                    <div class="premium-ide-body">
                        <!-- Node -->
                        <div x-show="lang === 'node'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
<pre><code><span class="c-kw">const</span> crypto <span class="c-op">=</span> <span class="c-fn">require</span>(<span class="c-str">'crypto'</span>);

<span class="c-kw">const</span> orderId <span class="c-op">=</span> <span class="c-str">'ORD_12345'</span>;
<span class="c-kw">const</span> amount <span class="c-op">=</span> <span class="c-str">'999.00'</span>;
<span class="c-kw">const</span> returnUrl <span class="c-op">=</span> <span class="c-str">'myapp://payment/complete'</span>;
<span class="c-kw">const</span> apiSalt <span class="c-op">=</span> <span class="c-str">'YOUR_API_SALT'</span>;

<span class="c-cm">// 1. Construct raw string</span>
<span class="c-kw">const</span> payload <span class="c-op">=</span> <span class="c-str">`${orderId}|${amount}|${returnUrl}`</span>;

<span class="c-cm">// 2. Generate HMAC-SHA256</span>
<span class="c-kw">const</span> signature <span class="c-op">=</span> crypto.<span class="c-fn">createHmac</span>(<span class="c-str">'sha256'</span>, apiSalt)
                        .<span class="c-fn">update</span>(payload)
                        .<span class="c-fn">digest</span>(<span class="c-str">'hex'</span>);</code></pre>
                        </div>
                        <!-- PHP -->
                        <div x-show="lang === 'php'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
<pre><code><span class="c-kw">&lt;?php</span>

<span class="c-var">$orderId</span> <span class="c-op">=</span> <span class="c-str">'ORD_12345'</span>;
<span class="c-var">$amount</span> <span class="c-op">=</span> <span class="c-str">'999.00'</span>;
<span class="c-var">$returnUrl</span> <span class="c-op">=</span> <span class="c-str">'myapp://payment/complete'</span>;
<span class="c-var">$apiSalt</span> <span class="c-op">=</span> <span class="c-str">'YOUR_API_SALT'</span>;

<span class="c-cm">// 1. Construct raw string</span>
<span class="c-var">$payload</span> <span class="c-op">=</span> <span class="c-str">"</span><span class="c-var">{$orderId}</span><span class="c-str">|</span><span class="c-var">{$amount}</span><span class="c-str">|</span><span class="c-var">{$returnUrl}</span><span class="c-str">"</span>;

<span class="c-cm">// 2. Generate HMAC-SHA256</span>
<span class="c-var">$signature</span> <span class="c-op">=</span> <span class="c-fn">hash_hmac</span>(<span class="c-str">'sha256'</span>, <span class="c-var">$payload</span>, <span class="c-var">$apiSalt</span>);</code></pre>
                        </div>
                        <!-- Python -->
                        <div x-show="lang === 'python'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
<pre><code><span class="c-kw">import</span> hmac
<span class="c-kw">import</span> hashlib

order_id <span class="c-op">=</span> <span class="c-str">'ORD_12345'</span>
amount <span class="c-op">=</span> <span class="c-str">'999.00'</span>
return_url <span class="c-op">=</span> <span class="c-str">'myapp://payment/complete'</span>
api_salt <span class="c-op">=</span> <span class="c-str">'YOUR_API_SALT'</span>

<span class="c-cm"># 1. Construct raw string</span>
payload <span class="c-op">=</span> <span class="c-str">f"{order_id}|{amount}|{return_url}"</span>

<span class="c-cm"># 2. Generate HMAC-SHA256</span>
signature <span class="c-op">=</span> hmac.<span class="c-fn">new</span>(
    api_salt.<span class="c-fn">encode</span>(<span class="c-str">'utf-8'</span>),
    payload.<span class="c-fn">encode</span>(<span class="c-str">'utf-8'</span>),
    hashlib.sha256
).<span class="c-fn">hexdigest</span>()</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section 2: Initiate Payment -->
    <div class="docs-grid">
        <div class="docs-left reveal">
            <h2 class="docs-h2"><span class="step-number">2</span> Create Payment Session</h2>
            <p class="docs-p">
                Send a POST request from your backend to generate a secure checkout URL. Once generated, seamlessly redirect your user to the returned <code>payment_url</code>.
            </p>
            
            <div class="endpoint-badge w-full flex items-center justify-between">
                <div>
                    <span class="badge-post">POST</span>
                    <span>/api/hub/initiate</span>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-2 py-1 rounded">CASHFREE</span>
            </div>
            
            <div class="endpoint-badge w-full flex items-center justify-between" style="margin-top:-1.25rem;">
                <div>
                    <span class="badge-post">POST</span>
                    <span>/api/hub/phonepe/initiate</span>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-2 py-1 rounded">PHONEPE</span>
            </div>

            <table class="param-table">
                <thead>
                    <tr><th>Parameter</th><th>Description</th></tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="param-name">client_order_id<span class="param-req">REQ</span></div>
                            <div class="text-[11px] text-slate-400 font-mono mt-1">string (max 50)</div>
                        </td>
                        <td><div class="text-sm text-slate-600 leading-relaxed">Your unique identifier for this order. Must be unique per transaction.</div></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="param-name">amount<span class="param-req">REQ</span></div>
                            <div class="text-[11px] text-slate-400 font-mono mt-1">decimal</div>
                        </td>
                        <td><div class="text-sm text-slate-600 leading-relaxed">The total amount to charge the user (e.g., <code>999.00</code>).</div></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="param-name">return_url<span class="param-req">REQ</span></div>
                            <div class="text-[11px] text-slate-400 font-mono mt-1">url string</div>
                        </td>
                        <td><div class="text-sm text-slate-600 leading-relaxed">The deep-link or website URL to redirect the user to after payment.</div></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="param-name">webhook_url<span class="param-req">REQ</span></div>
                            <div class="text-[11px] text-slate-400 font-mono mt-1">url string</div>
                        </td>
                        <td><div class="text-sm text-slate-600 leading-relaxed">Your server endpoint where we will send the final payment status asynchronously.</div></td>
                    </tr>
                    <tr>
                        <td>
                            <div class="param-name">signature<span class="param-req">REQ</span></div>
                            <div class="text-[11px] text-slate-400 font-mono mt-1">string (hex)</div>
                        </td>
                        <td><div class="text-sm text-slate-600 leading-relaxed">The HMAC-SHA256 signature generated in Step 1.</div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="docs-right">
            <div class="docs-sticky reveal" style="transition-delay: 200ms;">
                
                <h4 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-3 ml-2 flex items-center gap-2"><i class="ri-code-s-slash-line text-blue-400 text-base"></i> JSON Request</h4>
                <div class="premium-ide mb-8">
                    <div class="premium-ide-body">
<pre><code><span class="c-cm">// Headers</span>
<span class="c-var">Content-Type:</span> application/json
<span class="c-var">X-Api-Key:</span> YOUR_API_KEY

<span class="c-cm">// Body</span>
{
  <span class="c-str">"client_order_id"</span>: <span class="c-str">"ORD_12345"</span>,
  <span class="c-str">"amount"</span>: <span class="c-num">999.00</span>,
  <span class="c-str">"return_url"</span>: <span class="c-str">"myapp://payment/complete"</span>,
  <span class="c-str">"webhook_url"</span>: <span class="c-str">"https://api.myapp.com/payment-webhook"</span>,
  <span class="c-str">"customer_phone"</span>: <span class="c-str">"9876543210"</span>,
  <span class="c-str">"signature"</span>: <span class="c-str">"c5b16954b6..."</span>
}</code></pre>
                    </div>
                </div>
                
                <h4 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-3 ml-2 flex items-center gap-2"><i class="ri-checkbox-circle-fill text-green-400 text-base"></i> Success Response (201)</h4>
                <div class="premium-ide">
                    <div class="premium-ide-body">
<pre><code>{
  <span class="c-str">"payment_request_id"</span>: <span class="c-num">142</span>,
  <span class="c-str">"payment_url"</span>: <span class="c-str">"{{ url('/pay/142') }}"</span>,
  <span class="c-str">"status"</span>: <span class="c-str">"pending"</span>
}</code></pre>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Section 3: Webhooks -->
    <div class="docs-grid">
        <div class="docs-left reveal">
            <h2 class="docs-h2"><span class="step-number">3</span> Verify Webhook</h2>
            <p class="docs-p">
                We will send a POST request to your <code>webhook_url</code> with the final transaction status. <strong>To prevent spoofing, you must verify the signature of this webhook before fulfilling the order.</strong>
            </p>

            <h3 class="font-bold text-slate-800 mb-3 mt-8 text-sm uppercase letter-spacing-wide">Payload Properties</h3>
            <table class="param-table">
                <tbody>
                    <tr>
                        <td class="param-name">status</td>
                        <td><div class="text-sm text-slate-600">Will be <code class="bg-slate-100 text-green-700 px-1.5 py-0.5 rounded border border-slate-200 font-mono text-xs">success</code>, <code class="bg-slate-100 text-red-700 px-1.5 py-0.5 rounded border border-slate-200 font-mono text-xs">failed</code>, or <code class="bg-slate-100 text-orange-700 px-1.5 py-0.5 rounded border border-slate-200 font-mono text-xs">user_dropped</code>.</div></td>
                    </tr>
                    <tr>
                        <td class="param-name">transaction_id</td>
                        <td><div class="text-sm text-slate-600">The official bank/gateway transaction ID.</div></td>
                    </tr>
                    <tr>
                        <td class="param-name">client_order_id</td>
                        <td><div class="text-sm text-slate-600">Your original order ID.</div></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="docs-right">
            <div class="docs-sticky reveal" style="transition-delay: 200ms;">
                
                <h4 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-3 ml-2 flex items-center gap-2"><i class="ri-download-cloud-fill text-purple-400 text-base"></i> Incoming Webhook Payload</h4>
                <div class="premium-ide mb-8">
                    <div class="premium-ide-body">
<pre><code><span class="c-cm">// Header</span>
<span class="c-var">X-Hub-Signature:</span> 8f7c9a2...

<span class="c-cm">// Body JSON</span>
{
  <span class="c-str">"client_order_id"</span>: <span class="c-str">"ORD_12345"</span>,
  <span class="c-str">"status"</span>: <span class="c-str">"success"</span>,
  <span class="c-str">"transaction_id"</span>: <span class="c-str">"CF_887462551"</span>,
  <span class="c-str">"amount"</span>: <span class="c-str">"999.00"</span>,
  <span class="c-str">"currency"</span>: <span class="c-str">"INR"</span>
}</code></pre>
                    </div>
                </div>

                <h4 class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-3 ml-2 flex items-center gap-2"><i class="ri-shield-check-fill text-green-400 text-base"></i> Verification Logic</h4>
                <div class="premium-ide" x-data="{ lang: 'node' }">
                    <div class="premium-ide-header">
                        <div class="ide-controls">
                            <div class="ide-dot dot-red"></div><div class="ide-dot dot-yellow"></div><div class="ide-dot dot-green"></div>
                        </div>
                        <div class="premium-ide-tabs">
                            <button @click="lang = 'node'" class="premium-ide-tab" :class="lang === 'node' ? 'active' : ''">Node.js</button>
                            <button @click="lang = 'php'" class="premium-ide-tab" :class="lang === 'php' ? 'active' : ''">PHP</button>
                        </div>
                    </div>
                    
                    <div class="premium-ide-body">
                        <div x-show="lang === 'node'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
<pre><code>app.<span class="c-fn">post</span>(<span class="c-str">'/webhook'</span>, (req, res) <span class="c-op">=></span> {
    <span class="c-kw">const</span> sigHeader <span class="c-op">=</span> req.headers[<span class="c-str">'x-hub-signature'</span>];
    <span class="c-kw">const</span> rawBody <span class="c-op">=</span> <span class="c-var">JSON</span>.<span class="c-fn">stringify</span>(req.body);
    
    <span class="c-kw">const</span> expectedSig <span class="c-op">=</span> crypto.<span class="c-fn">createHmac</span>(<span class="c-str">'sha256'</span>, apiSalt)
                              .<span class="c-fn">update</span>(rawBody)
                              .<span class="c-fn">digest</span>(<span class="c-str">'hex'</span>);

    <span class="c-kw">if</span> (sigHeader <span class="c-op">===</span> expectedSig) {
        <span class="c-cm">// ✅ Valid! Process order</span>
        res.<span class="c-fn">status</span>(<span class="c-num">200</span>).<span class="c-fn">send</span>(<span class="c-str">'OK'</span>);
    } <span class="c-kw">else</span> {
        res.<span class="c-fn">status</span>(<span class="c-num">400</span>).<span class="c-fn">send</span>(<span class="c-str">'Invalid Signature'</span>);
    }
});</code></pre>
                        </div>
                        <div x-show="lang === 'php'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-cloak>
<pre><code><span class="c-var">$rawBody</span> <span class="c-op">=</span> <span class="c-fn">file_get_contents</span>(<span class="c-str">'php://input'</span>);
<span class="c-var">$sigHeader</span> <span class="c-op">=</span> <span class="c-var">$_SERVER</span>[<span class="c-str">'HTTP_X_HUB_SIGNATURE'</span>];

<span class="c-var">$expectedSig</span> <span class="c-op">=</span> <span class="c-fn">hash_hmac</span>(<span class="c-str">'sha256'</span>, <span class="c-var">$rawBody</span>, <span class="c-var">$apiSalt</span>);

<span class="c-kw">if</span> (<span class="c-fn">hash_equals</span>(<span class="c-var">$expectedSig</span>, <span class="c-var">$sigHeader</span>)) {
    <span class="c-cm">// ✅ Valid! Process order</span>
    <span class="c-fn">http_response_code</span>(<span class="c-num">200</span>);
} <span class="c-kw">else</span> {
    <span class="c-fn">http_response_code</span>(<span class="c-num">400</span>);
}</code></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Details -->
    <div class="px-12 py-10 bg-slate-50 text-center border-t border-slate-200">
        <h3 class="font-bold text-slate-800 mb-2" style="font-family:'Inter',sans-serif;">Need Integration Help?</h3>
        <p class="text-sm text-slate-500 max-w-lg mx-auto">Our development team is available to help you with signature generation, webhook testing, or environment setup.</p>
    </div>

</div>

@endsection
