@extends('layouts.app')
@section('title', 'Grievance Redressal Policy — FIINWAY')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Grievance Redressal Policy</h1>
        <p class="text-xs text-slate-400 mb-6">Last Updated: 18 August 2026 | FIINWAY 360 COMMUNICATION</p>
        <p class="text-sm text-slate-600 mb-6 leading-relaxed">FIINWAY 360 COMMUNICATION is committed to resolving customer grievances promptly and fairly. This policy outlines how you can raise a complaint and how we will address it.</p>

        {{-- Grievance Contact Card --}}
        <div class="bg-[#0f172a] rounded-lg p-6 mb-8 border border-[#f59e0b]/30">
            <h2 class="text-base font-bold text-[#f59e0b] mb-3 flex items-center gap-2">
                <i class="ri-shield-user-line"></i> Grievance Officer Contact
            </h2>
            <div class="text-sm text-white/80 space-y-1">
                <p><strong class="text-white">Company:</strong> FIINWAY 360 COMMUNICATION</p>
                <p><strong class="text-white">Grievance Email:</strong> <a href="mailto:grievance@fiinway.in" class="text-[#f59e0b] hover:underline">grievance@fiinway.in</a></p>
                <p><strong class="text-white">Phone:</strong> <a href="tel:+919429693669" class="text-[#f59e0b] hover:underline">+91 94296 93669</a></p>
                <p><strong class="text-white">Address:</strong> 5, Balaganj, Near Siwi Factory, Mehta Vihar, Lucknow, Uttar Pradesh, India – 226003</p>
                <p><strong class="text-white">Website:</strong> <a href="https://fiinway.in" class="text-[#f59e0b] hover:underline">fiinway.in</a></p>
            </div>
        </div>

        @php $sections = [
            ['title' => '1. Scope', 'body' => "This policy applies to grievances related to:\n• Products purchased on fiinway.in\n• Orders, delivery, or returns\n• Payments and refunds\n• Account or login issues\n• Privacy or data concerns\n• Seller conduct\n• Any other matter related to the use of fiinway.in"],
            ['title' => '2. How to Submit a Grievance', 'body' => "Step 1: Contact Customer Support first\nEmail: info@fiinway.in | Phone: +91 94296 93669\n\nStep 2: If unresolved, escalate to our Grievance Officer\nEmail: grievance@fiinway.in\n\nWhen submitting, please include:\n• Your name and registered mobile number\n• Order ID (if applicable)\n• Description of the issue\n• Supporting evidence (photos, screenshots, etc.)"],
            ['title' => '3. Acknowledgement', 'body' => "We will acknowledge grievances submitted to our Grievance Officer within a reasonable period, subject to the nature of the complaint and applicable law."],
            ['title' => '4. Resolution Timeline', 'body' => "We aim to resolve grievances within the timeframe required under applicable law. Complex matters may require additional time for investigation, verification, or coordination with third parties."],
            ['title' => '5. Process', 'body' => "Upon receiving a grievance, we may:\n• Acknowledge receipt\n• Request additional information or evidence\n• Investigate the matter\n• Coordinate with the relevant seller, logistics partner, or payment provider\n• Communicate the outcome and applicable resolution"],
            ['title' => '6. Applicable Law', 'body' => "This policy is governed by the laws of India, including applicable consumer protection laws and Information Technology rules. We process grievances in good faith and in compliance with applicable legal requirements."],
        ]; @endphp

        <div class="space-y-6">
            @foreach($sections as $s)
            <div>
                <h2 class="text-sm font-bold text-[#212121] mb-1">{{ $s['title'] }}</h2>
                <p class="text-sm text-slate-600 whitespace-pre-line">{{ $s['body'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-xs text-slate-400">
            <p>© 2026 FIINWAY 360 COMMUNICATION. All Rights Reserved.</p>
        </div>
    </div>
</div>
@endsection
