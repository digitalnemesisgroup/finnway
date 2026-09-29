@extends('layouts.app')
@section('title', 'Return & Replacement Policy — FIINWAY')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Return &amp; Exchange Policy</h1>
        <p class="text-xs text-slate-400 mb-6">Last Updated: 20 August 2026</p>

        @php
        $sections = [
            ['title' => 'Return Policy', 'body' => 'We offer a 3-day return window. You may request a return within 3 days of receiving your order.'],
            ['title' => 'Exchange Policy', 'body' => "If you receive a damaged product or the wrong item, please record a clear unboxing video as proof.\n\nOnce our team verifies the issue, we will process an exchange and deliver the replacement product within 7-10 business days."],
        ];
        @endphp

        <div class="space-y-6">
            @foreach($sections as $s)
            <div>
                <h2 class="text-sm font-bold text-[#212121] mb-1">{{ $s['title'] }}</h2>
                <p class="text-sm text-slate-600 whitespace-pre-line">{{ $s['body'] }}</p>
            </div>
            @endforeach
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-xs text-slate-400">
            <p><strong class="text-slate-600">Contact Us</strong></p>
            <p>Email: <a href="mailto:grievance@finway.in" class="text-[#e94f1c]">grievance@finway.in</a></p>
        </div>
    </div>
</div>
@endsection
