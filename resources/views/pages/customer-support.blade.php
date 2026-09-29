@extends('layouts.app')
@section('title', 'Customer Support — FIINWAY')
@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Customer Support</h1>
        <p class="text-xs text-slate-400 mb-8">Order issues, returns, or shipping questions? We're here to help you out</p>

        {{-- Support Channels --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
            <div class="p-5 bg-[#f1f3f6] rounded-sm text-center">
                <div class="text-3xl text-[#006837] mb-2"><i class="ri-headphone-line"></i></div>
                <h3 class="font-bold text-[#212121] text-sm">Call Us</h3>
                <p class="text-[#006837] font-semibold text-sm mt-1">1800-202-9898</p>
                <p class="text-[#878787] text-xs mt-0.5">Toll Free · Mon – Sat, 9 AM – 8 PM</p>
            </div>
            <div class="p-5 bg-[#f1f3f6] rounded-sm text-center">
                <div class="text-3xl text-[#006837] mb-2"><i class="ri-mail-line"></i></div>
                <h3 class="font-bold text-[#212121] text-sm">Email Support</h3>
                <p class="text-[#006837] font-semibold text-sm mt-1">support@bazaarhub.in</p>
                <p class="text-[#878787] text-xs mt-0.5">Response within 24 hours</p>
            </div>
            <div class="p-5 bg-[#f1f3f6] rounded-sm text-center">
                <div class="text-3xl text-[#006837] mb-2"><i class="ri-live-line"></i></div>
                <h3 class="font-bold text-[#212121] text-sm">Live Chat</h3>
                <p class="text-[#006837] font-semibold text-sm mt-1">Available 24/7</p>
                <p class="text-[#878787] text-xs mt-0.5">Tap the chat icon on any page</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            {{-- Support Form --}}
            <form class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-[#212121] mb-1">Issue Type</label>
                    <select class="w-full px-4 py-3 border border-slate-200 rounded-md text-sm outline-none transition focus:border-[#006837] focus:ring-2 focus:ring-[#006837]/15 bg-white">
                        <option>Order & Delivery</option>
                        <option>Returns & Refunds</option>
                        <option>Payments</option>
                        <option>Product Query</option>
                        <option>Other</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#212121] mb-1">Order ID <span class="text-slate-400 font-normal">(optional)</span></label>
                        <input type="text" placeholder="e.g. FW100245"
                            class="w-full px-4 py-3 border border-slate-200 rounded-md text-sm outline-none transition focus:border-[#006837] focus:ring-2 focus:ring-[#006837]/15" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#212121] mb-1">Email</label>
                        <input type="email" placeholder="you@example.com"
                            class="w-full px-4 py-3 border border-slate-200 rounded-md text-sm outline-none transition focus:border-[#006837] focus:ring-2 focus:ring-[#006837]/15" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#212121] mb-1">Describe your issue</label>
                    <textarea rows="6" placeholder="Tell us what went wrong and we'll sort it out..."
                        class="w-full px-4 py-3 border border-slate-200 rounded-md text-sm outline-none transition focus:border-[#006837] focus:ring-2 focus:ring-[#006837]/15 resize-none"></textarea>
                </div>

                <button type="submit"
                    class="group inline-flex items-center justify-center gap-2 w-full sm:w-auto px-8 py-3 bg-[#e94f1c] text-white text-sm font-semibold rounded-md uppercase tracking-wide hover:bg-[#cf4317] hover:shadow-lg hover:shadow-[#e94f1c]/25 transition-all duration-200 active:scale-[0.98]">
                    Raise a Ticket
                    <i class="ri-customer-service-2-line group-hover:translate-x-0.5 transition-transform"></i>
                </button>
            </form>

            {{-- Quick Help --}}
            <div>
                <h2 class="text-xl font-bold text-[#212121] mb-5">Quick help</h2>
                <div class="space-y-3">
                    <a href="{{ route('orders') }}" class="flex items-center gap-3 p-4 bg-[#f1f3f6] rounded-sm hover:bg-[#e9edf2] transition">
                        <i class="ri-truck-line text-[#006837] text-xl"></i>
                        <div>
                            <p class="font-medium text-[#212121] text-sm">Track your order</p>
                            <p class="text-[#878787] text-xs">Check the live status of your shipment</p>
                        </div>
                    </a>
                    <a href="{{ route('returns.index') }}" class="flex items-center gap-3 p-4 bg-[#f1f3f6] rounded-sm hover:bg-[#e9edf2] transition">
                        <i class="ri-arrow-go-back-line text-[#006837] text-xl"></i>
                        <div>
                            <p class="font-medium text-[#212121] text-sm">Manage returns</p>
                            <p class="text-[#878787] text-xs">Start a return or replacement request</p>
                        </div>
                    </a>
                    <a href="{{ route('page.shipping') }}" class="flex items-center gap-3 p-4 bg-[#f1f3f6] rounded-sm hover:bg-[#e9edf2] transition">
                        <i class="ri-map-2-line text-[#006837] text-xl"></i>
                        <div>
                            <p class="font-medium text-[#212121] text-sm">Shipping & delivery</p>
                            <p class="text-[#878787] text-xs">Timelines and charges explained</p>
                        </div>
                    </a>
                    <a href="{{ route('page.contact') }}" class="flex items-center gap-3 p-4 bg-[#f1f3f6] rounded-sm hover:bg-[#e9edf2] transition">
                        <i class="ri-mail-open-line text-[#006837] text-xl"></i>
                        <div>
                            <p class="font-medium text-[#212121] text-sm">General enquiries</p>
                            <p class="text-[#878787] text-xs">Reach our team for anything else</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
