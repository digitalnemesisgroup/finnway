@extends('layouts.app')

@section('title', $product->name . ' — Buy Online at Best Price in India')

@section('content')
@php
    $imagesCount = $product->images->count();
@endphp

<div class="bg-white min-h-screen pb-20" x-data="{ ...productGallery({{ $imagesCount > 0 ? $imagesCount : 1 }}), showReviewForm: false, rating: 0 }">

    {{-- Breadcrumb --}}
    <div class="max-w-7xl mx-auto px-4 py-3 text-xs font-medium text-slate-500 flex items-center gap-2 overflow-x-auto border-b border-slate-100">
        <a href="{{ route('home') }}" class="hover:text-[#e94f1c] shrink-0">Home</a>
        <i class="ri-arrow-right-s-line shrink-0 text-slate-400"></i>
        <a href="{{ route('products') }}" class="hover:text-[#e94f1c] shrink-0">All Products</a>
        @if($product->category)
            <i class="ri-arrow-right-s-line shrink-0 text-slate-400"></i>
            <a href="{{ route('products', ['category' => $product->category_id]) }}" class="hover:text-[#e94f1c] shrink-0">{{ $product->category->name }}</a>
        @endif
        <i class="ri-arrow-right-s-line shrink-0 text-slate-400"></i>
        <span class="text-slate-800 shrink-0">{{ Str::limit($product->name, 40) }}</span>
    </div>

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row">

        {{-- Left: Sticky Image Gallery --}}
        <div class="w-full md:w-[40%] lg:w-[35%] p-4 md:border-r border-slate-200 relative">
            <div class="sticky top-20">

                {{-- Main Image --}}
                <div class="relative w-full aspect-square flex items-center justify-center mb-4 border border-slate-100 p-4 rounded bg-slate-50">
                    @foreach($product->images as $idx => $img)
                        <div x-show="activeImage === {{ $idx }}" class="w-full h-full flex items-center justify-center">
                            <x-product-image :path="$img->image_path" :alt="$product->name" aspect="square" class="max-w-full max-h-full object-contain" />
                        </div>
                    @endforeach
                    @if($product->images->isEmpty())
                        <div class="w-full h-full flex items-center justify-center">
                            <x-product-image :product="$product" aspect="square" class="max-w-full max-h-full object-contain" />
                        </div>
                    @endif

                    {{-- Wishlist --}}
                    @auth
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-4 right-4">
                        @csrf
                        <button type="submit" class="w-10 h-10 rounded-full bg-white shadow flex items-center justify-center border border-slate-100 text-slate-400 hover:text-red-500 transition-colors">
                            <i class="ri-heart-3-fill text-xl"></i>
                        </button>
                    </form>
                    @endauth
                </div>

                {{-- Thumbnails --}}
                @if($imagesCount > 1)
                <div class="flex items-center gap-2 overflow-x-auto pb-2 justify-center">
                    @foreach($product->images as $idx => $img)
                        <button @click="activeImage = {{ $idx }}"
                                class="w-16 h-16 border-2 flex items-center justify-center shrink-0 p-1 rounded transition-colors"
                                :class="activeImage === {{ $idx }} ? 'border-[#e94f1c]' : 'border-slate-200'">
                            <x-product-image :path="$img->image_path" :alt="$product->name" aspect="square" class="max-w-full max-h-full object-contain" />
                        </button>
                    @endforeach
                </div>
                @endif

                {{-- Desktop Action Buttons --}}
                <div class="hidden md:flex flex-col gap-2 mt-4">
                    @if(strtolower($product->condition_type) === 'old')
                        <div class="bg-orange-50 border border-orange-200 text-orange-800 text-xs font-bold p-3 rounded flex items-start gap-2 mb-2">
                            <i class="ri-error-warning-fill text-orange-500 text-lg"></i>
                            <p>⚠️ <strong>Inspect the product before purchase.</strong> Arrange a face-to-face meeting with the seller for physical inspection before paying.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="tel:{{ $product->seller->phone ?? '#' }}" class="flex-1 py-4 bg-white text-[#212121] border border-slate-300 font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow-sm hover:bg-slate-50 transition-colors">
                                <i class="ri-phone-fill text-xl"></i> CONTACT SELLER
                            </a>
                            @auth
                            <a href="{{ route('chat.direct', $product->seller->id) }}" class="flex-1 py-4 bg-[#388e3c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-green-700 transition-colors">
                                <i class="ri-chat-3-fill text-xl"></i> MESSAGE SELLER
                            </a>
                            @else
                            <a href="{{ route('mobile') }}?redirect={{ urlencode(url()->current()) }}" class="flex-1 py-4 bg-[#388e3c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-green-700 transition-colors">
                                <i class="ri-chat-3-fill text-xl"></i> MESSAGE SELLER
                            </a>
                            @endauth
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            @auth
                                @if($inCart)
                                    <a href="{{ route('checkout') }}" class="flex-1 py-4 bg-[#388e3c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#2d7230] transition-colors">
                                        <i class="ri-checkbox-circle-fill text-xl"></i> GO TO CHECKOUT
                                    </a>
                                @else
                                    <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="from_url" value="{{ url()->current() }}">
                                        <button type="submit" class="w-full py-4 bg-[#ff9f00] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#f39800] transition-colors">
                                            <i class="ri-shopping-cart-2-fill text-xl"></i> ADD TO CART
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="action" value="buy_now">
                                    <button type="submit" class="w-full py-4 bg-[#e94f1c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#cc4214] transition-colors">
                                        <i class="ri-flashlight-fill text-xl"></i> BUY NOW
                                    </button>
                                </form>
                            @else
                                <button onclick="guestCartAction('{{ url()->current() }}')" class="flex-1 py-4 bg-[#ff9f00] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#f39800] transition-colors">
                                    <i class="ri-shopping-cart-2-fill text-xl"></i> ADD TO CART
                                </button>
                                <button onclick="guestCartAction('{{ url()->current() }}')" class="flex-1 py-4 bg-[#e94f1c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#cc4214] transition-colors">
                                    <i class="ri-flashlight-fill text-xl"></i> BUY NOW
                                </button>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Right: Product Details --}}
        <div class="w-full md:w-[60%] lg:w-[65%] p-4 sm:p-6">

            <div class="border-b border-slate-200 pb-4 mb-4 space-y-2">
                <h1 class="text-[18px] sm:text-[22px] text-[#212121] leading-snug">{{ $product->name }}</h1>

                <div class="flex flex-wrap items-center gap-3">
                    @if($product->rating)
                    <span class="fk-star flex items-center gap-1 px-1.5 py-0.5 text-[13px]">
                        {{ number_format($product->rating, 1) }} <i class="ri-star-fill text-[10px]"></i>
                    </span>
                    <span class="text-slate-500 font-medium text-sm">{{ $product->rating_count ?? 0 }} Ratings &amp; {{ $product->reviews->count() }} Reviews</span>
                    @else
                    <span class="text-slate-500 font-medium text-sm">{{ $product->reviews->count() }} Reviews</span>
                    @endif

                    {{-- ✅ FIINWAY Assured Badge — Unique Style --}}
                    <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-[11px] font-black tracking-wide shadow-sm border"
                          style="background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); border-color: #f59e0b; color: #f59e0b;">
                        <i class="ri-shield-check-fill text-[13px]" style="color:#f59e0b;"></i>
                        FIINWAY
                        <span class="font-light italic tracking-widest text-white/80 text-[10px]">assured</span>
                        <i class="ri-checkbox-circle-fill text-[13px] text-emerald-400"></i>
                    </span>
                </div>

                <div class="text-[#388e3c] font-medium text-sm">Special price</div>
                <div class="flex items-end gap-3 mt-1">
                    <span class="text-3xl text-[#212121] font-medium">₹{{ number_format($product->selling_price) }}</span>
                    @if($product->original_price > $product->selling_price)
                        <span class="text-base text-[#878787] line-through mb-1">₹{{ number_format($product->original_price) }}</span>
                        <span class="text-base text-[#388e3c] font-medium mb-1">{{ round($product->discount_percent) }}% off</span>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                    <div><span class="text-slate-500">Brand:</span> <span class="font-medium text-slate-800">{{ $product->brand ?: 'N/A' }}</span></div>
                    <div><span class="text-slate-500">Model:</span> <span class="font-medium text-slate-800">{{ $product->model ?: 'N/A' }}</span></div>
                    <div><span class="text-slate-500">Condition:</span> <span class="font-medium text-slate-800 capitalize">{{ $product->condition_type }}</span></div>
                    <div><span class="text-slate-500">Availability:</span> <span class="font-medium text-slate-800">{{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}</span></div>
                </div>
            </div>

            {{-- Offers --}}
            <div class="mb-6 space-y-3">
                <h3 class="text-base font-medium text-[#212121]">Available Soon</h3>
                <ul class="space-y-2 text-sm text-[#212121]">
                    <li class="flex items-start gap-2">
                        <i class="ri-price-tag-3-fill text-[#18ab56] mt-0.5"></i>
                        <span><strong class="font-medium">Bank Offer:</strong> 5% Unlimited Cashback on FIINWAY Axis Bank Credit Card</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="ri-price-tag-3-fill text-[#18ab56] mt-0.5"></i>
                        <span><strong class="font-medium">Special Price:</strong> Get extra 10% off (price inclusive of cashback/coupon)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="ri-calendar-check-fill text-[#18ab56] mt-0.5"></i>
                        <span><strong class="font-medium">EMI:</strong> No cost EMI ₹{{ number_format($product->selling_price / 6) }}/month. Standard EMI also available</span>
                    </li>
                </ul>
            </div>

            {{-- Service Info --}}
            <div class="flex flex-wrap items-center gap-6 py-4 border-y border-slate-200 mb-6 text-sm font-medium text-[#212121]">
                <div class="flex items-center gap-2">
                    <i class="ri-truck-fill text-[#e94f1c] text-xl"></i> 
                    Delivery By: 
                    <strong class="text-[#e94f1c] ml-1">
                        @if($product->delivery_partner_type === 'fiinway')
                            FIINWAY Delivery
                        @elseif($product->delivery_partner_type === 'third_party')
                            Third-Party Courier
                        @elseif($product->delivery_partner_type === 'buyer_pickup' || $product->pickup_available || $product->delivery_type === 'self')
                            Self Pickup (By Buyer)
                        @else
                            Seller Delivery
                        @endif
                    </strong>
                </div>
                <div class="flex items-center gap-2">
                    <i class="ri-refresh-line text-[#e94f1c] text-xl"></i> 7 Days Replacement Policy
                </div>
                <div class="flex items-center gap-2">
                    <i class="ri-money-rupee-circle-line text-[#e94f1c] text-xl"></i> Cash on Delivery available
                </div>
            </div>

            {{-- Product Condition (Old Products) --}}
            @if(strtolower($product->condition_type) === 'old')
                <div class="border border-slate-200 rounded-sm mb-6">
                    <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121]">Product Condition &amp; History</div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                            <div class="flex flex-col">
                                <span class="text-[#878787] mb-1">Age</span>
                                <span class="text-[#212121]">{{ $product->product_age_months ? $product->product_age_months . ' months' : 'Not provided' }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[#878787] mb-1">Condition</span>
                                <span class="text-[#212121] capitalize">{{ $product->condition_label ?: 'Not provided' }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[#878787] mb-1">Bill Available</span>
                                <span class="text-[#212121]">{{ $product->bill_available ? 'Yes' : 'No' }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[#878787] mb-1">Warranty Available</span>
                                <span class="text-[#212121]">{{ $product->warranty_available ? 'Yes' : 'No' }}</span>
                            </div>
                        </div>
                        @if($product->warranty_available && $product->warranty_info)
                            <div class="mt-4 pt-4 border-t border-slate-100 text-sm">
                                <span class="text-[#878787] block mb-1">Warranty Info</span>
                                <span class="text-[#212121] block whitespace-pre-line">{{ $product->warranty_info }}</span>
                            </div>
                        @endif
                        @if($product->damage_details)
                            <div class="mt-4 pt-4 border-t border-slate-100 text-sm">
                                <span class="text-[#878787] block mb-1">Damage / Repair Details</span>
                                <span class="text-[#212121] block whitespace-pre-line">{{ $product->damage_details }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Product Specifications --}}
            @if($product->metas->isNotEmpty())
            <div class="border border-slate-200 rounded-sm mb-6">
                <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121]">Product Specifications</div>
                <div class="p-6">
                    <table class="w-full text-sm text-left">
                        <tbody>
                            @foreach($product->metas as $meta)
                            <tr class="border-b border-slate-100 last:border-0">
                                <td class="py-3 text-slate-500 w-1/3">{{ $meta->key }}</td>
                                <td class="py-3 text-slate-800 font-medium">{{ is_array(json_decode($meta->value, true)) ? implode(', ', json_decode($meta->value, true)) : $meta->value }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Seller Info --}}
            <div class="border border-slate-200 rounded-sm mb-6">
                <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121]">Seller Information</div>
                <div class="p-6">
                    <div class="flex items-start gap-12 text-sm">
                        <div class="text-[#878787] font-medium w-24 shrink-0">Sold By</div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[#e94f1c] font-bold text-base">{{ $product->seller->name }}</span>
                                <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded-full"><i class="ri-verified-badge-fill"></i> Verified Seller</span>
                            </div>
                            <div class="text-slate-600 mb-3 text-xs">{{ $product->seller_type ?: 'Retailer' }}</div>
                            
                            <div class="grid grid-cols-2 gap-y-2 mb-4 text-xs">
                                <div><span class="text-slate-500">Rating:</span> <span class="font-medium bg-green-100 text-green-800 px-1.5 py-0.5 rounded">{{ number_format($product->seller->rating ?? 4.5, 1) }} <i class="ri-star-fill text-[9px]"></i></span></div>
                                <div><span class="text-slate-500">Location:</span> <span class="font-medium text-slate-800">{{ $product->city ?: 'N/A' }}</span></div>
                            </div>
                            
                            <ul class="list-disc list-inside text-slate-600 space-y-1 text-xs">
                                <li>7 Days Replacement Policy</li>
                                <li>100% Secure Payments</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            <div class="border border-slate-200 rounded-sm mb-6">
                <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121]">Product Description</div>
                <div class="p-6 text-sm text-[#212121] leading-relaxed whitespace-pre-line">
                    {{ $product->description }}
                </div>
            </div>

            {{-- Ratings & Reviews --}}
            <div class="border border-slate-200 rounded-sm">
                <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121] flex items-center justify-between">
                    <span>Ratings &amp; Reviews</span>
                    @auth
                        @if(!$userHasReviewed)
                            <button @click="showReviewForm = !showReviewForm" class="px-4 py-2 bg-white text-[#212121] font-medium text-sm border border-slate-300 rounded shadow-sm hover:bg-slate-50 transition-colors">
                                <i class="ri-star-line mr-1"></i> Rate Product
                            </button>
                        @else
                            <span class="text-xs text-slate-400 flex items-center gap-1"><i class="ri-checkbox-circle-fill text-green-500"></i> You've reviewed this</span>
                        @endif
                    @else
                        <a href="{{ route('mobile') }}" class="px-4 py-2 bg-white text-[#e94f1c] font-medium text-sm border border-[#e94f1c] rounded shadow-sm hover:bg-orange-50 transition-colors text-xs">
                            Login to Review
                        </a>
                    @endauth
                </div>

                @auth
                    @if(!$userHasReviewed)
                        <div x-show="showReviewForm" style="display: none;" class="p-6 border-b border-slate-200 bg-slate-50">
                            <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="order_id" value="0">
                                <div>
                                    <label class="block text-sm font-medium text-[#212121] mb-2">Rate this product</label>
                                    <div class="flex items-center gap-2">
                                        <template x-for="i in 5">
                                            <button type="button" @click="rating = i" class="text-2xl transition-colors" :class="rating >= i ? 'text-[#ff9f00]' : 'text-slate-300'">
                                                <i class="ri-star-fill"></i>
                                            </button>
                                        </template>
                                    </div>
                                    <input type="hidden" name="rating" x-model="rating" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#212121] mb-2">Your Review</label>
                                    <textarea name="comment" rows="3" class="w-full p-3 border border-slate-300 rounded outline-none focus:border-[#e94f1c] text-sm" placeholder="Share your experience with this product..."></textarea>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="showReviewForm = false" class="px-6 py-2 text-[#212121] font-medium">Cancel</button>
                                    <button type="submit" class="px-6 py-2 bg-[#e94f1c] text-white font-medium rounded shadow-sm hover:bg-[#cc4214]">Submit Review</button>
                                </div>
                            </form>
                        </div>
                    @endif
                @endauth

                <div class="p-6 space-y-6">
                    @forelse($product->reviews as $review)
                        <div class="border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="fk-star flex items-center gap-1 px-1.5 py-0.5 text-[11px] {{ $review->rating < 3 ? 'bg-red-500' : ($review->rating == 3 ? 'bg-orange-700' : 'bg-[#388e3c]') }}">
                                    {{ $review->rating }} <i class="ri-star-fill text-[9px]"></i>
                                </span>
                                <span class="font-medium text-sm text-[#212121]">{{ $review->title ?: 'Verified Review' }}</span>
                            </div>
                            <p class="text-sm text-[#212121] mb-3">{{ $review->comment }}</p>
                            <div class="flex items-center gap-4 text-xs font-medium text-[#878787]">
                                <span>{{ $review->user->name ?: 'User' }}</span>
                                <span class="flex items-center gap-1"><i class="ri-checkbox-circle-fill text-green-400"></i> Verified</span>
                                <span>{{ $review->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-[#878787] text-center py-4">No reviews yet. Be the first to review!</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    {{-- ─── Similar Products (same category) ─── --}}
    @if($related->isNotEmpty())
    <div class="max-w-7xl mx-auto px-4 py-8 border-t border-slate-100 mt-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-[#212121]">Similar Products</h2>
            @if($product->category)
            <a href="{{ route('products', ['category' => $product->category_id]) }}" class="text-sm text-[#e94f1c] font-medium hover:underline flex items-center gap-1">
                View All <i class="ri-arrow-right-s-line"></i>
            </a>
            @endif
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            @foreach($related->take(5) as $rel)
                <x-product-card :product="$rel" />
            @endforeach
        </div>
    </div>
    @endif
</div>

{{-- Mobile Sticky Footer Actions --}}
<div class="fixed bottom-0 left-0 right-0 md:hidden bg-white border-t border-slate-200 flex flex-col z-50 shadow-lg">
    @if(strtolower($product->condition_type) === 'old')
        <div class="bg-orange-50 text-orange-800 text-[10px] font-bold py-1.5 px-3 text-center border-b border-orange-100">
            ⚠️ Inspect the product before purchase. Arrange a physical meeting first.
        </div>
        <div class="flex items-center w-full">
            <a href="tel:{{ $product->seller->phone ?? '#' }}" class="w-1/2 py-3.5 bg-white text-[#212121] font-bold text-sm flex items-center justify-center uppercase border-r border-slate-200">
                <i class="ri-phone-fill mr-1 text-lg"></i> CALL
            </a>
            @auth
            <a href="{{ route('chat.direct', $product->seller->id) }}" class="w-1/2 py-3.5 bg-[#388e3c] text-white font-bold text-sm flex items-center justify-center uppercase">
                <i class="ri-chat-3-fill mr-1 text-lg"></i> MESSAGE
            </a>
            @else
            <a href="{{ route('mobile') }}" class="w-1/2 py-3.5 bg-[#388e3c] text-white font-bold text-sm flex items-center justify-center uppercase">
                <i class="ri-chat-3-fill mr-1 text-lg"></i> MESSAGE
            </a>
            @endauth
        </div>
    @else
        <div class="flex items-center w-full">
            @auth
                @if($inCart)
                    <a href="{{ route('checkout') }}" class="w-full py-3.5 bg-[#388e3c] text-white font-bold text-sm flex items-center justify-center gap-2 uppercase">
                        <i class="ri-checkbox-circle-fill text-lg"></i> GO TO CHECKOUT
                    </a>
                @else
                    <form action="{{ route('cart.add') }}" method="POST" class="w-1/2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="from_url" value="{{ url()->current() }}">
                        <button type="submit" class="w-full py-3.5 bg-white text-[#212121] font-bold text-sm flex items-center justify-center uppercase border-r border-slate-200">
                            ADD TO CART
                        </button>
                    </form>
                    <form action="{{ route('cart.add') }}" method="POST" class="w-1/2">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="action" value="buy_now">
                        <button type="submit" class="w-full py-3.5 bg-[#e94f1c] text-white font-bold text-sm flex items-center justify-center uppercase">
                            <i class="ri-flashlight-fill mr-1 text-lg"></i> BUY NOW
                        </button>
                    </form>
                @endif
            @else
                <button onclick="guestCartAction('{{ url()->current() }}')" class="w-1/2 py-3.5 bg-white text-[#212121] font-bold text-sm flex items-center justify-center uppercase border-r border-slate-200">
                    ADD TO CART
                </button>
                <button onclick="guestCartAction('{{ url()->current() }}')" class="w-1/2 py-3.5 bg-[#e94f1c] text-white font-bold text-sm flex items-center justify-center uppercase">
                    <i class="ri-flashlight-fill mr-1 text-lg"></i> BUY NOW
                </button>
            @endauth
        </div>
    @endif
</div>

@push('scripts')
<script>
function productGallery(total) {
    return {
        activeImage: 0,
        totalImages: total,
    }
}

// Guest: store intended URL in session, then redirect to login
function guestCartAction(intendedUrl) {
    fetch('{{ route('guest.action') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ intended_url: intendedUrl })
    }).then(() => {
        window.location.href = '{{ route('mobile') }}';
    }).catch(() => {
        window.location.href = '{{ route('mobile') }}';
    });
}
</script>
@endpush

@push('styles')
<style>
    /* Hide the global mobile bottom nav on the product page so the cart actions are visible */
    nav.fixed.bottom-0.z-50 { display: none !important; }
</style>
@endpush
@endsection
