@extends('layouts.app')

@section('title', 'Payment Session Expired — FIINWAY')

@section('content')
<div class="max-w-md mx-auto my-12 px-4">
    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl text-center space-y-6">
        <div class="w-20 h-20 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center mx-auto text-4xl border border-rose-100">
            <i class="ri-shield-keyhole-line"></i>
        </div>

        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Payment Link Expired</h1>
            <p class="text-xs font-semibold text-slate-500 mt-2">{{ $message ?? 'This payment session link has expired or exceeded maximum access attempts.' }}</p>
        </div>

        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-xs font-mono text-slate-600 space-y-1.5 text-left">
            <div class="flex justify-between">
                <span class="text-slate-400 font-semibold">Security Code:</span>
                <span class="text-rose-600 font-bold uppercase">{{ $reason ?? 'SESSION_TERMINATED' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-400 font-semibold">Session Status:</span>
                <span class="text-rose-600 font-bold">REJECTED</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100 text-xs text-indigo-900 font-medium flex items-start gap-3 text-left">
            <i class="ri-information-fill text-lg shrink-0 text-indigo-600"></i>
            <span>Your order is safe. You can re-initiate payment from your Order History page.</span>
        </div>

        <a href="{{ route('orders') }}" class="block w-full py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs shadow-lg shadow-indigo-600/20 transition-all">
            <i class="ri-arrow-left-line mr-1"></i> Go to My Orders
        </a>
    </div>
</div>
@endsection

