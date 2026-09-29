@extends('layouts.app')
@section('title', 'Shipping & Delivery Policy — FIINWAY')
@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Shipping &amp; Delivery Policy</h1>
        <p class="text-xs text-slate-400 mb-6">Last Updated: 20 August 2026</p>

        @php
        $sections = [
            ['title' => 'Shipping Policy', 'body' => 'All products will be shipped and delivered within 6 to 8 days.'],
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
            <p>© 2026 FIINWAY 360 COMMUNICATION. All Rights Reserved. | Website: fiinway.in</p>
        </div>
    </div>
</div>
@endsection
