<?php $__env->startSection('title', $product->name . ' — Buy Online at Best Price in India'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $imagesCount = $product->images->count();
?>

<div class="bg-white min-h-screen pb-20" x-data="{ ...productGallery(<?php echo e($imagesCount > 0 ? $imagesCount : 1); ?>), showReviewForm: false, rating: 0 }">

    
    <div class="max-w-7xl mx-auto px-4 py-3 text-xs font-medium text-slate-500 flex items-center gap-2 overflow-x-auto border-b border-slate-100">
        <a href="<?php echo e(route('home')); ?>" class="hover:text-[#e94f1c] shrink-0">Home</a>
        <i class="ri-arrow-right-s-line shrink-0 text-slate-400"></i>
        <a href="<?php echo e(route('products')); ?>" class="hover:text-[#e94f1c] shrink-0">All Products</a>
        <?php if($product->category): ?>
            <i class="ri-arrow-right-s-line shrink-0 text-slate-400"></i>
            <a href="<?php echo e(route('products', ['category' => $product->category_id])); ?>" class="hover:text-[#e94f1c] shrink-0"><?php echo e($product->category->name); ?></a>
        <?php endif; ?>
        <i class="ri-arrow-right-s-line shrink-0 text-slate-400"></i>
        <span class="text-slate-800 shrink-0"><?php echo e(Str::limit($product->name, 40)); ?></span>
    </div>

    <div class="max-w-7xl mx-auto flex flex-col md:flex-row">

        
        <div class="w-full md:w-[40%] lg:w-[35%] p-4 md:border-r border-slate-200 relative">
            <div class="sticky top-20">

                
                <div class="relative w-full aspect-square flex items-center justify-center mb-4 border border-slate-100 p-4 rounded bg-slate-50">
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div x-show="activeImage === <?php echo e($idx); ?>" class="w-full h-full flex items-center justify-center">
                            <?php if (isset($component)) { $__componentOriginala58dde406db9207f2e2c58e1c4a3d690 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-image','data' => ['path' => $img->image_path,'alt' => $product->name,'aspect' => 'square','class' => 'max-w-full max-h-full object-contain']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['path' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($img->image_path),'alt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product->name),'aspect' => 'square','class' => 'max-w-full max-h-full object-contain']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $attributes = $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $component = $__componentOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($product->images->isEmpty()): ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <?php if (isset($component)) { $__componentOriginala58dde406db9207f2e2c58e1c4a3d690 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-image','data' => ['product' => $product,'aspect' => 'square','class' => 'max-w-full max-h-full object-contain']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product),'aspect' => 'square','class' => 'max-w-full max-h-full object-contain']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $attributes = $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $component = $__componentOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
                        </div>
                    <?php endif; ?>

                    
                    <?php if(auth()->guard()->check()): ?>
                    <form action="<?php echo e(route('wishlist.toggle', $product)); ?>" method="POST" class="absolute top-4 right-4">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="w-10 h-10 rounded-full bg-white shadow flex items-center justify-center border border-slate-100 text-slate-400 hover:text-red-500 transition-colors">
                            <i class="ri-heart-3-fill text-xl"></i>
                        </button>
                    </form>
                    <?php endif; ?>
                </div>

                
                <?php if($imagesCount > 1): ?>
                <div class="flex items-center gap-2 overflow-x-auto pb-2 justify-center">
                    <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button @click="activeImage = <?php echo e($idx); ?>"
                                class="w-16 h-16 border-2 flex items-center justify-center shrink-0 p-1 rounded transition-colors"
                                :class="activeImage === <?php echo e($idx); ?> ? 'border-[#e94f1c]' : 'border-slate-200'">
                            <?php if (isset($component)) { $__componentOriginala58dde406db9207f2e2c58e1c4a3d690 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-image','data' => ['path' => $img->image_path,'alt' => $product->name,'aspect' => 'square','class' => 'max-w-full max-h-full object-contain']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['path' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($img->image_path),'alt' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product->name),'aspect' => 'square','class' => 'max-w-full max-h-full object-contain']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $attributes = $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $component = $__componentOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>

                
                <div class="hidden md:flex flex-col gap-2 mt-4">
                    <?php if(strtolower($product->condition_type) === 'old'): ?>
                        <div class="bg-orange-50 border border-orange-200 text-orange-800 text-xs font-bold p-3 rounded flex items-start gap-2 mb-2">
                            <i class="ri-error-warning-fill text-orange-500 text-lg"></i>
                            <p>⚠️ <strong>Inspect the product before purchase.</strong> Arrange a face-to-face meeting with the seller for physical inspection before paying.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="tel:<?php echo e($product->seller->phone ?? '#'); ?>" class="flex-1 py-4 bg-white text-[#212121] border border-slate-300 font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow-sm hover:bg-slate-50 transition-colors">
                                <i class="ri-phone-fill text-xl"></i> CONTACT SELLER
                            </a>
                            <?php if(auth()->guard()->check()): ?>
                            <a href="<?php echo e(route('chat.direct', $product->seller->id)); ?>" class="flex-1 py-4 bg-[#388e3c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-green-700 transition-colors">
                                <i class="ri-chat-3-fill text-xl"></i> MESSAGE SELLER
                            </a>
                            <?php else: ?>
                            <a href="<?php echo e(route('mobile')); ?>?redirect=<?php echo e(urlencode(url()->current())); ?>" class="flex-1 py-4 bg-[#388e3c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-green-700 transition-colors">
                                <i class="ri-chat-3-fill text-xl"></i> MESSAGE SELLER
                            </a>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-2">
                            <?php if(auth()->guard()->check()): ?>
                                <?php if($inCart): ?>
                                    <a href="<?php echo e(route('checkout')); ?>" class="flex-1 py-4 bg-[#388e3c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#2d7230] transition-colors">
                                        <i class="ri-checkbox-circle-fill text-xl"></i> GO TO CHECKOUT
                                    </a>
                                <?php else: ?>
                                    <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="flex-1">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                        <input type="hidden" name="from_url" value="<?php echo e(url()->current()); ?>">
                                        <button type="submit" class="w-full py-4 bg-[#ff9f00] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#f39800] transition-colors">
                                            <i class="ri-shopping-cart-2-fill text-xl"></i> ADD TO CART
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="flex-1">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                    <input type="hidden" name="action" value="buy_now">
                                    <button type="submit" class="w-full py-4 bg-[#e94f1c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#cc4214] transition-colors">
                                        <i class="ri-flashlight-fill text-xl"></i> BUY NOW
                                    </button>
                                </form>
                            <?php else: ?>
                                <button onclick="guestCartAction('<?php echo e(url()->current()); ?>')" class="flex-1 py-4 bg-[#ff9f00] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#f39800] transition-colors">
                                    <i class="ri-shopping-cart-2-fill text-xl"></i> ADD TO CART
                                </button>
                                <button onclick="guestCartAction('<?php echo e(url()->current()); ?>')" class="flex-1 py-4 bg-[#e94f1c] text-white font-bold text-base flex items-center justify-center gap-2 rounded-sm shadow hover:bg-[#cc4214] transition-colors">
                                    <i class="ri-flashlight-fill text-xl"></i> BUY NOW
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="w-full md:w-[60%] lg:w-[65%] p-4 sm:p-6">

            <div class="border-b border-slate-200 pb-4 mb-4 space-y-2">
                <h1 class="text-[18px] sm:text-[22px] text-[#212121] leading-snug"><?php echo e($product->name); ?></h1>

                <div class="flex flex-wrap items-center gap-3">
                    <?php if($product->rating): ?>
                    <span class="fk-star flex items-center gap-1 px-1.5 py-0.5 text-[13px]">
                        <?php echo e(number_format($product->rating, 1)); ?> <i class="ri-star-fill text-[10px]"></i>
                    </span>
                    <span class="text-slate-500 font-medium text-sm"><?php echo e($product->rating_count ?? 0); ?> Ratings &amp; <?php echo e($product->reviews->count()); ?> Reviews</span>
                    <?php else: ?>
                    <span class="text-slate-500 font-medium text-sm"><?php echo e($product->reviews->count()); ?> Reviews</span>
                    <?php endif; ?>

                    
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
                    <span class="text-3xl text-[#212121] font-medium">₹<?php echo e(number_format($product->selling_price)); ?></span>
                    <?php if($product->original_price > $product->selling_price): ?>
                        <span class="text-base text-[#878787] line-through mb-1">₹<?php echo e(number_format($product->original_price)); ?></span>
                        <span class="text-base text-[#388e3c] font-medium mb-1"><?php echo e(round($product->discount_percent)); ?>% off</span>
                    <?php endif; ?>
                </div>
                
                <div class="grid grid-cols-2 gap-4 mt-4 text-sm">
                    <div><span class="text-slate-500">Brand:</span> <span class="font-medium text-slate-800"><?php echo e($product->brand ?: 'N/A'); ?></span></div>
                    <div><span class="text-slate-500">Model:</span> <span class="font-medium text-slate-800"><?php echo e($product->model ?: 'N/A'); ?></span></div>
                    <div><span class="text-slate-500">Condition:</span> <span class="font-medium text-slate-800 capitalize"><?php echo e($product->condition_type); ?></span></div>
                    <div><span class="text-slate-500">Availability:</span> <span class="font-medium text-slate-800"><?php echo e($product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock'); ?></span></div>
                </div>
            </div>

            
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
                        <span><strong class="font-medium">EMI:</strong> No cost EMI ₹<?php echo e(number_format($product->selling_price / 6)); ?>/month. Standard EMI also available</span>
                    </li>
                </ul>
            </div>

            
            <div class="flex flex-wrap items-center gap-6 py-4 border-y border-slate-200 mb-6 text-sm font-medium text-[#212121]">
                <div class="flex items-center gap-2">
                    <i class="ri-truck-fill text-[#e94f1c] text-xl"></i> 
                    Delivery By: 
                    <strong class="text-[#e94f1c] ml-1">
                        <?php if($product->delivery_partner_type === 'fiinway'): ?>
                            FIINWAY Delivery
                        <?php elseif($product->delivery_partner_type === 'third_party'): ?>
                            Third-Party Courier
                        <?php elseif($product->delivery_partner_type === 'buyer_pickup' || $product->pickup_available || $product->delivery_type === 'self'): ?>
                            Self Pickup (By Buyer)
                        <?php else: ?>
                            Seller Delivery
                        <?php endif; ?>
                    </strong>
                </div>
                <div class="flex items-center gap-2">
                    <i class="ri-refresh-line text-[#e94f1c] text-xl"></i> 7 Days Replacement Policy
                </div>
                <div class="flex items-center gap-2">
                    <i class="ri-money-rupee-circle-line text-[#e94f1c] text-xl"></i> Cash on Delivery available
                </div>
            </div>

            
            <?php if(strtolower($product->condition_type) === 'old'): ?>
                <div class="border border-slate-200 rounded-sm mb-6">
                    <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121]">Product Condition &amp; History</div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                            <div class="flex flex-col">
                                <span class="text-[#878787] mb-1">Age</span>
                                <span class="text-[#212121]"><?php echo e($product->product_age_months ? $product->product_age_months . ' months' : 'Not provided'); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[#878787] mb-1">Condition</span>
                                <span class="text-[#212121] capitalize"><?php echo e($product->condition_label ?: 'Not provided'); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[#878787] mb-1">Bill Available</span>
                                <span class="text-[#212121]"><?php echo e($product->bill_available ? 'Yes' : 'No'); ?></span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[#878787] mb-1">Warranty Available</span>
                                <span class="text-[#212121]"><?php echo e($product->warranty_available ? 'Yes' : 'No'); ?></span>
                            </div>
                        </div>
                        <?php if($product->warranty_available && $product->warranty_info): ?>
                            <div class="mt-4 pt-4 border-t border-slate-100 text-sm">
                                <span class="text-[#878787] block mb-1">Warranty Info</span>
                                <span class="text-[#212121] block whitespace-pre-line"><?php echo e($product->warranty_info); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($product->damage_details): ?>
                            <div class="mt-4 pt-4 border-t border-slate-100 text-sm">
                                <span class="text-[#878787] block mb-1">Damage / Repair Details</span>
                                <span class="text-[#212121] block whitespace-pre-line"><?php echo e($product->damage_details); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if($product->metas->isNotEmpty()): ?>
            <div class="border border-slate-200 rounded-sm mb-6">
                <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121]">Product Specifications</div>
                <div class="p-6">
                    <table class="w-full text-sm text-left">
                        <tbody>
                            <?php $__currentLoopData = $product->metas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b border-slate-100 last:border-0">
                                <td class="py-3 text-slate-500 w-1/3"><?php echo e($meta->key); ?></td>
                                <td class="py-3 text-slate-800 font-medium"><?php echo e(is_array(json_decode($meta->value, true)) ? implode(', ', json_decode($meta->value, true)) : $meta->value); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            
            <div class="border border-slate-200 rounded-sm mb-6">
                <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121]">Seller Information</div>
                <div class="p-6">
                    <div class="flex items-start gap-12 text-sm">
                        <div class="text-[#878787] font-medium w-24 shrink-0">Sold By</div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[#e94f1c] font-bold text-base"><?php echo e($product->seller->name); ?></span>
                                <span class="bg-blue-100 text-blue-800 text-[10px] font-bold px-2 py-0.5 rounded-full"><i class="ri-verified-badge-fill"></i> Verified Seller</span>
                            </div>
                            <div class="text-slate-600 mb-3 text-xs"><?php echo e($product->seller_type ?: 'Retailer'); ?></div>
                            
                            <div class="grid grid-cols-2 gap-y-2 mb-4 text-xs">
                                <div><span class="text-slate-500">Rating:</span> <span class="font-medium bg-green-100 text-green-800 px-1.5 py-0.5 rounded"><?php echo e(number_format($product->seller->rating ?? 4.5, 1)); ?> <i class="ri-star-fill text-[9px]"></i></span></div>
                                <div><span class="text-slate-500">Location:</span> <span class="font-medium text-slate-800"><?php echo e($product->city ?: 'N/A'); ?></span></div>
                            </div>
                            
                            <ul class="list-disc list-inside text-slate-600 space-y-1 text-xs">
                                <li>7 Days Replacement Policy</li>
                                <li>100% Secure Payments</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="border border-slate-200 rounded-sm mb-6">
                <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121]">Product Description</div>
                <div class="p-6 text-sm text-[#212121] leading-relaxed whitespace-pre-line">
                    <?php echo e($product->description); ?>

                </div>
            </div>

            
            <div class="border border-slate-200 rounded-sm">
                <div class="px-6 py-4 text-lg font-medium border-b border-slate-200 text-[#212121] flex items-center justify-between">
                    <span>Ratings &amp; Reviews</span>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(!$userHasReviewed): ?>
                            <button @click="showReviewForm = !showReviewForm" class="px-4 py-2 bg-white text-[#212121] font-medium text-sm border border-slate-300 rounded shadow-sm hover:bg-slate-50 transition-colors">
                                <i class="ri-star-line mr-1"></i> Rate Product
                            </button>
                        <?php else: ?>
                            <span class="text-xs text-slate-400 flex items-center gap-1"><i class="ri-checkbox-circle-fill text-green-500"></i> You've reviewed this</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?php echo e(route('mobile')); ?>" class="px-4 py-2 bg-white text-[#e94f1c] font-medium text-sm border border-[#e94f1c] rounded shadow-sm hover:bg-orange-50 transition-colors text-xs">
                            Login to Review
                        </a>
                    <?php endif; ?>
                </div>

                <?php if(auth()->guard()->check()): ?>
                    <?php if(!$userHasReviewed): ?>
                        <div x-show="showReviewForm" style="display: none;" class="p-6 border-b border-slate-200 bg-slate-50">
                            <form action="<?php echo e(route('reviews.store')); ?>" method="POST" class="space-y-4">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
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
                    <?php endif; ?>
                <?php endif; ?>

                <div class="p-6 space-y-6">
                    <?php $__empty_1 = true; $__currentLoopData = $product->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="fk-star flex items-center gap-1 px-1.5 py-0.5 text-[11px] <?php echo e($review->rating < 3 ? 'bg-red-500' : ($review->rating == 3 ? 'bg-orange-700' : 'bg-[#388e3c]')); ?>">
                                    <?php echo e($review->rating); ?> <i class="ri-star-fill text-[9px]"></i>
                                </span>
                                <span class="font-medium text-sm text-[#212121]"><?php echo e($review->title ?: 'Verified Review'); ?></span>
                            </div>
                            <p class="text-sm text-[#212121] mb-3"><?php echo e($review->comment); ?></p>
                            <div class="flex items-center gap-4 text-xs font-medium text-[#878787]">
                                <span><?php echo e($review->user->name ?: 'User'); ?></span>
                                <span class="flex items-center gap-1"><i class="ri-checkbox-circle-fill text-green-400"></i> Verified</span>
                                <span><?php echo e($review->created_at->diffForHumans()); ?></span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-sm text-[#878787] text-center py-4">No reviews yet. Be the first to review!</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    
    <?php if($related->isNotEmpty()): ?>
    <div class="max-w-7xl mx-auto px-4 py-8 border-t border-slate-100 mt-4">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-[#212121]">Similar Products</h2>
            <?php if($product->category): ?>
            <a href="<?php echo e(route('products', ['category' => $product->category_id])); ?>" class="text-sm text-[#e94f1c] font-medium hover:underline flex items-center gap-1">
                View All <i class="ri-arrow-right-s-line"></i>
            </a>
            <?php endif; ?>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            <?php $__currentLoopData = $related->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if (isset($component)) { $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-card','data' => ['product' => $rel]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($rel)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $attributes = $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $component = $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>
</div>


<div class="fixed bottom-0 left-0 right-0 md:hidden bg-white border-t border-slate-200 flex flex-col z-50 shadow-lg">
    <?php if(strtolower($product->condition_type) === 'old'): ?>
        <div class="bg-orange-50 text-orange-800 text-[10px] font-bold py-1.5 px-3 text-center border-b border-orange-100">
            ⚠️ Inspect the product before purchase. Arrange a physical meeting first.
        </div>
        <div class="flex items-center w-full">
            <a href="tel:<?php echo e($product->seller->phone ?? '#'); ?>" class="w-1/2 py-3.5 bg-white text-[#212121] font-bold text-sm flex items-center justify-center uppercase border-r border-slate-200">
                <i class="ri-phone-fill mr-1 text-lg"></i> CALL
            </a>
            <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('chat.direct', $product->seller->id)); ?>" class="w-1/2 py-3.5 bg-[#388e3c] text-white font-bold text-sm flex items-center justify-center uppercase">
                <i class="ri-chat-3-fill mr-1 text-lg"></i> MESSAGE
            </a>
            <?php else: ?>
            <a href="<?php echo e(route('mobile')); ?>" class="w-1/2 py-3.5 bg-[#388e3c] text-white font-bold text-sm flex items-center justify-center uppercase">
                <i class="ri-chat-3-fill mr-1 text-lg"></i> MESSAGE
            </a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="flex items-center w-full">
            <?php if(auth()->guard()->check()): ?>
                <?php if($inCart): ?>
                    <a href="<?php echo e(route('checkout')); ?>" class="w-full py-3.5 bg-[#388e3c] text-white font-bold text-sm flex items-center justify-center gap-2 uppercase">
                        <i class="ri-checkbox-circle-fill text-lg"></i> GO TO CHECKOUT
                    </a>
                <?php else: ?>
                    <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="w-1/2">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                        <input type="hidden" name="from_url" value="<?php echo e(url()->current()); ?>">
                        <button type="submit" class="w-full py-3.5 bg-white text-[#212121] font-bold text-sm flex items-center justify-center uppercase border-r border-slate-200">
                            ADD TO CART
                        </button>
                    </form>
                    <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="w-1/2">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                        <input type="hidden" name="action" value="buy_now">
                        <button type="submit" class="w-full py-3.5 bg-[#e94f1c] text-white font-bold text-sm flex items-center justify-center uppercase">
                            <i class="ri-flashlight-fill mr-1 text-lg"></i> BUY NOW
                        </button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <button onclick="guestCartAction('<?php echo e(url()->current()); ?>')" class="w-1/2 py-3.5 bg-white text-[#212121] font-bold text-sm flex items-center justify-center uppercase border-r border-slate-200">
                    ADD TO CART
                </button>
                <button onclick="guestCartAction('<?php echo e(url()->current()); ?>')" class="w-1/2 py-3.5 bg-[#e94f1c] text-white font-bold text-sm flex items-center justify-center uppercase">
                    <i class="ri-flashlight-fill mr-1 text-lg"></i> BUY NOW
                </button>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function productGallery(total) {
    return {
        activeImage: 0,
        totalImages: total,
    }
}

// Guest: store intended URL in session, then redirect to login
function guestCartAction(intendedUrl) {
    fetch('<?php echo e(route('guest.action')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ intended_url: intendedUrl })
    }).then(() => {
        window.location.href = '<?php echo e(route('mobile')); ?>';
    }).catch(() => {
        window.location.href = '<?php echo e(route('mobile')); ?>';
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Hide the global mobile bottom nav on the product page so the cart actions are visible */
    nav.fixed.bottom-0.z-50 { display: none !important; }
</style>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/buyer/products/show.blade.php ENDPATH**/ ?>