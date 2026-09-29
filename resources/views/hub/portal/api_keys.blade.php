@extends('hub.portal.layout')

@section('title', 'API Keys')
@section('page_title', 'API Credentials')

@section('content')
<div class="space-y-6" x-data="{ copiedKey: false, copiedSalt: false, revealSalt: false }">

    <!-- ── API Credentials Box ────────────────────────────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-200 pb-5 gap-3">
            <div>
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="ri-key-2-fill text-blue-600"></i> Production API Credentials
                </h3>
                <p class="text-xs text-slate-500">Your production credentials for machine-to-machine payment initiation</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $client->isLive() ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800' }}">
                <i class="ri-checkbox-circle-fill"></i> {{ $client->isLive() ? 'ACCOUNT LIVE & ACTIVE' : 'APPROVAL PENDING' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- 1. API Key -->
            <div class="space-y-2">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600">X-Api-Key (Public Client Key)</label>
                <div class="flex items-center gap-2">
                    <input type="text" readonly value="{{ $client->api_key ?: 'Key pending approval' }}" id="api-key-input"
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl px-3.5 py-3 text-xs font-mono font-bold text-slate-800 focus:outline-none select-all">
                    <button type="button" @click="navigator.clipboard.writeText('{{ $client->api_key }}'); copiedKey = true; setTimeout(() => copiedKey = false, 2000)"
                        class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-1.5 shadow-sm">
                        <i :class="copiedKey ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                        <span x-text="copiedKey ? 'Copied!' : 'Copy Key'"></span>
                    </button>
                </div>
            </div>

            <!-- 2. API Salt -->
            <div class="space-y-2">
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600">API Salt (HMAC Signing Secret)</label>
                <div class="flex items-center gap-2">
                    <input :type="revealSalt ? 'text' : 'password'" readonly value="{{ $client->api_salt ?: 'Salt pending approval' }}" id="api-salt-input"
                        class="w-full bg-slate-100 border border-slate-300 rounded-xl px-3.5 py-3 text-xs font-mono font-bold text-slate-800 focus:outline-none select-all">
                    <button type="button" @click="revealSalt = !revealSalt" class="px-3.5 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-bold transition shrink-0" title="Toggle Reveal">
                        <i :class="revealSalt ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                    </button>
                    <button type="button" @click="navigator.clipboard.writeText('{{ $client->api_salt }}'); copiedSalt = true; setTimeout(() => copiedSalt = false, 2000)"
                        class="px-4 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shrink-0 flex items-center gap-1.5 shadow-sm">
                        <i :class="copiedSalt ? 'ri-check-line' : 'ri-file-copy-line'"></i>
                        <span x-text="copiedSalt ? 'Copied!' : 'Copy Salt'"></span>
                    </button>
                </div>
            </div>

        </div>

        <!-- Account Validity Details -->
        <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Subscription Starts</span>
                <span class="font-bold text-slate-800 text-sm">{{ $client->starts_at ? $client->starts_at->format('d M Y') : 'N/A' }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Subscription Expires</span>
                <span class="font-bold text-slate-800 text-sm">{{ $client->expires_at ? $client->expires_at->format('d M Y') : 'N/A' }}</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Platform Charge Rate</span>
                <span class="font-bold text-blue-600 text-sm">{{ number_format($client->gateway_charge_percent ?: 2.0, 2) }}%</span>
            </div>
            <div>
                <span class="block text-[10px] font-bold text-slate-400 uppercase">GST Rate</span>
                <span class="font-bold text-indigo-600 text-sm">{{ number_format($client->gst_on_charge_percent ?: 18.0, 2) }}%</span>
            </div>
        </div>
    </div>

    <!-- ── Security Warning Box ────────────────────────────────────────────────── -->
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-xl shrink-0 mt-0.5 shadow-md shadow-amber-500/20">
            <i class="ri-shield-keyhole-line"></i>
        </div>
        <div>
            <h4 class="font-bold text-amber-900 text-sm">Security Best Practices</h4>
            <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                Do not commit your <code class="bg-amber-100 font-mono text-[11px] px-1 rounded">API Salt</code> to public repositories or client-side code (iOS, Android, React/Vue frontend). Always compute HMAC-SHA256 signatures on your backend server.
            </p>
        </div>
    </div>

</div>
@endsection

