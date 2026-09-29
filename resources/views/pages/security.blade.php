@extends('layouts.app')
@section('title', 'Security — FIINWAY')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Security at FIINWAY</h1>
        <p class="text-xs text-slate-400 mb-6">Your safety is our priority</p>

        <div class="bg-red-50 border border-red-200 rounded p-4 mb-8 text-sm text-red-800">
            <p class="font-bold mb-1"><i class="ri-error-warning-line mr-1"></i>Important Security Notice</p>
            <p>FIINWAY or its representatives will <strong>NEVER</strong> ask you to share your:</p>
            <ul class="list-disc list-inside mt-2 space-y-1">
                <li>UPI PIN or UPI ID password</li>
                <li>ATM / Debit / Credit Card PIN</li>
                <li>CVV number</li>
                <li>OTP (One Time Password)</li>
                <li>Internet Banking Password</li>
            </ul>
            <p class="mt-2">If anyone asks for these, <strong>do not share</strong> and report it immediately to <a href="mailto:grievance@fiinway.in" class="underline">grievance@fiinway.in</a>.</p>
        </div>

        @php $sections = [
            ['icon' => 'ri-lock-password-line', 'title' => 'Secure Payments', 'body' => 'All transactions on FIINWAY are processed through PCI-DSS compliant payment gateways (Razorpay). Your card and payment data is encrypted and never stored on our servers.'],
            ['icon' => 'ri-shield-check-line', 'title' => 'OTP-Based Login', 'body' => 'We use mobile OTP-based authentication — no passwords to remember or steal. Your account is protected by your registered mobile number.'],
            ['icon' => 'ri-eye-off-line', 'title' => 'Privacy by Design', 'body' => 'We collect only the information necessary to process your orders and improve your experience. We do not sell your personal data to third parties. See our Privacy Policy for full details.'],
            ['icon' => 'ri-spam-2-line', 'title' => 'Fraud Prevention', 'body' => 'Our systems actively monitor for suspicious activity, including fake accounts, fraudulent orders, and payment manipulation. Accounts found engaging in fraud will be permanently suspended.'],
            ['icon' => 'ri-customer-service-2-line', 'title' => 'Report Suspicious Activity', 'body' => "If you notice any suspicious activity on your account or receive suspicious calls/messages claiming to be from FIINWAY:\n• Do not share any credentials\n• Email us immediately: grievance@fiinway.in\n• Call us: +91 94296 93669"],
        ]; @endphp

        <div class="space-y-6">
            @foreach($sections as $s)
            <div class="flex gap-4">
                <div class="shrink-0 w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center">
                    <i class="{{ $s['icon'] }} text-[#e94f1c] text-xl"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-[#212121] mb-1">{{ $s['title'] }}</h2>
                    <p class="text-sm text-slate-600 whitespace-pre-line">{{ $s['body'] }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-xs text-slate-400">
            <p>© 2026 FIINWAY 360 COMMUNICATION. All Rights Reserved.</p>
        </div>
    </div>
</div>
@endsection
