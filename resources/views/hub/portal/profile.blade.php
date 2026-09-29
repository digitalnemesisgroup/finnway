@extends('hub.portal.layout')

@section('title', 'Business Profile')
@section('page_title', 'Business Profile & Account Details')

@section('content')
<div class="space-y-6">

    <!-- ── Profile Header Card ────────────────────────────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 sm:p-8 shadow-sm space-y-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between border-b border-slate-200 pb-6 gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-600 text-white font-black text-2xl flex items-center justify-center shadow-lg shadow-blue-500/20 shrink-0">
                    {{ strtoupper(substr($client->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 tracking-tight">{{ $client->name }}</h2>
                    <p class="text-xs text-slate-500">{{ $client->legal_name }} &bull; {{ $client->business_type }}</p>
                </div>
            </div>

            <span class="px-3.5 py-1.5 rounded-full text-xs font-bold {{ $client->isLive() ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-amber-100 text-amber-800' }}">
                <i class="ri-checkbox-circle-fill"></i> ACCOUNT {{ strtoupper($client->approval_status) }}
            </span>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-xs">

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">PAN Number</span>
                <span class="font-mono font-bold text-slate-800 text-sm">{{ $client->pan_number }}</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">GSTIN Identifier</span>
                <span class="font-mono font-bold text-slate-800 text-sm">{{ $client->gstin ?: 'N/A' }}</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Business Category</span>
                <span class="font-bold text-slate-800 text-sm">{{ $client->category }}</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Business Email</span>
                <span class="font-bold text-slate-800 text-sm">{{ $client->business_email }}</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Business Mobile</span>
                <span class="font-bold text-slate-800 text-sm">{{ $client->business_mobile }}</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Website URL</span>
                <a href="{{ $client->website_url }}" target="_blank" class="font-bold text-blue-600 hover:underline text-sm truncate block">
                    {{ $client->website_url }}
                </a>
            </div>

            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/80 md:col-span-2 lg:col-span-3">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Registered Address</span>
                <span class="font-medium text-slate-700 text-xs">{{ $client->registered_address }}</span>
            </div>

        </div>

    </div>

    <!-- ── Financial Terms Box ─────────────────────────────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
        <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
            <i class="ri-percent-line text-blue-600"></i> Account Settlement Terms
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div class="bg-blue-50/60 border border-blue-100 p-3.5 rounded-xl">
                <span class="block text-[10px] font-bold text-slate-500 uppercase">Gateway Charge</span>
                <span class="text-base font-black text-blue-600">{{ number_format($client->gateway_charge_percent ?: 2.0, 2) }}%</span>
            </div>
            <div class="bg-indigo-50/60 border border-indigo-100 p-3.5 rounded-xl">
                <span class="block text-[10px] font-bold text-slate-500 uppercase">GST Rate</span>
                <span class="text-base font-black text-indigo-600">{{ number_format($client->gst_on_charge_percent ?: 18.0, 2) }}%</span>
            </div>
            <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl">
                <span class="block text-[10px] font-bold text-slate-500 uppercase">Starts On</span>
                <span class="text-sm font-bold text-slate-800">{{ $client->starts_at ? $client->starts_at->format('d M Y') : 'N/A' }}</span>
            </div>
            <div class="bg-slate-50 border border-slate-200 p-3.5 rounded-xl">
                <span class="block text-[10px] font-bold text-slate-500 uppercase">Expires On</span>
                <span class="text-sm font-bold text-slate-800">{{ $client->expires_at ? $client->expires_at->format('d M Y') : 'N/A' }}</span>
            </div>
        </div>
    </div>

</div>
@endsection

