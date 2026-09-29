<?php $__env->startSection('title', 'Shopping Cart — FIINWAY'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#f1f3f6] min-h-screen pb-20">

    <?php if(!$cart || $cart->items->isEmpty()): ?>

        <div class="max-w-6xl mx-auto px-4 py-8 md:py-12">
            <div class="bg-white rounded-sm shadow-sm p-8 md:p-16 text-center">

                <img
                    src="https://rukminim2.flixcart.com/www/800/800/promos/16/05/2019/d438a32e-765a-4d8b-b4a6-520b560971e8.png"
                    alt="Empty Cart"
                    class="w-32 md:w-40 mx-auto mb-6 opacity-80 object-contain"
                >

                <h3 class="text-xl font-medium text-[#212121] mb-2">
                    Your cart is empty!
                </h3>

                <p class="text-sm text-slate-500 mb-6">
                    Add items to it now.
                </p>

                <a
                    href="<?php echo e(route('products')); ?>"
                    class="inline-flex items-center justify-center px-8 md:px-10 py-3 bg-[#006837] text-white font-bold text-sm rounded-sm hover:bg-green-800 uppercase tracking-wide transition"
                >
                    Shop Now
                </a>
            </div>
        </div>

    <?php else: ?>

        <?php
            $groupedItems = $cart->items->groupBy(
                fn($item) => $item->product->seller->id ?? 0
            );

            $couponDiscount = session('coupon_discount', 0);
            $finalTotal = $total - $couponDiscount;
            $totalSavings = $subtotal - $finalTotal;
        ?>

        <div class="max-w-[1400px] mx-auto px-3 sm:px-4 lg:px-6 py-4 md:py-6">

            
            <div class="bg-white rounded-sm shadow-sm px-4 md:px-5 py-3 mb-4">

                <div class="flex items-center justify-between gap-4">

                    <div class="min-w-0">
                        <h1 class="text-lg md:text-xl font-medium text-[#212121]">
                            My Cart

                            <span class="text-slate-400 font-normal text-sm md:text-base">
                                (<?php echo e($cart->items->count()); ?> items)
                            </span>
                        </h1>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-[#212121] shrink-0">
                        <i class="ri-map-pin-line text-[#006837] text-lg"></i>

                        <span class="hidden sm:block">
                            Deliver to:
                            <span class="font-bold">Home</span>
                        </span>
                    </div>

                </div>
            </div>


            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 lg:gap-5 items-start">

                
                <div class="md:col-span-7 xl:col-span-8 min-w-0">

                    <div class="space-y-3">

                        <?php $__currentLoopData = $groupedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sellerId => $items): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                                $sellerName = $items->first()->product->seller->name ?? 'Verified Seller';
                            ?>

                            <div class="bg-white rounded-sm shadow-sm overflow-hidden">

                                
                                <div class="px-4 md:px-5 py-3 bg-slate-50 border-b border-slate-100">

                                    <div class="flex items-center gap-2 text-[#212121]">
                                        <i class="ri-store-2-line text-[#006837] text-base"></i>

                                        <span class="text-xs md:text-sm font-bold">
                                            Seller:
                                        </span>

                                        <span class="text-xs md:text-sm text-slate-700 truncate">
                                            <?php echo e($sellerName); ?>

                                        </span>
                                    </div>

                                </div>


                                
                                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if(!$item->product): ?>
                                        <div class="px-4 md:px-5 py-5 border-b border-slate-100 last:border-0 flex justify-between items-center bg-red-50/50">
                                            <div class="text-red-500 text-sm font-medium">This item is no longer available.</div>
                                            <form action="<?php echo e(route('cart.remove', $item->id)); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-xs md:text-sm font-bold text-[#212121] hover:text-red-600 uppercase transition">
                                                    REMOVE
                                                </button>
                                            </form>
                                        </div>
                                        <?php continue; ?>
                                    <?php endif; ?>

                                    <div class="px-4 md:px-5 py-5 border-b border-slate-100 last:border-0">

                                        <div class="flex gap-4 md:gap-5 lg:gap-6">

                                            
                                            <div class="w-24 md:w-28 lg:w-32 shrink-0">

                                                <a
                                                    href="<?php echo e(route('products.show', $item->product->slug)); ?>"
                                                    class="w-full h-24 md:h-28 lg:h-32 flex items-center justify-center p-2"
                                                >
                                                    <?php if (isset($component)) { $__componentOriginala58dde406db9207f2e2c58e1c4a3d690 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-image','data' => ['product' => $item->product,'aspect' => 'square','class' => 'w-full h-full object-contain']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->product),'aspect' => 'square','class' => 'w-full h-full object-contain']); ?>
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
                                                </a>

                                                
                                                <div class="flex justify-center mt-3">

                                                    <div class="flex items-center border border-slate-300 rounded-sm">

                                                        <form
                                                            action="<?php echo e(route('cart.update', $item->id)); ?>"
                                                            method="POST"
                                                        >
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PATCH'); ?>

                                                            <input
                                                                type="hidden"
                                                                name="quantity"
                                                                value="<?php echo e(max(1, $item->quantity - 1)); ?>"
                                                            >

                                                            <button
                                                                type="submit"
                                                                class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center text-[#212121] bg-white border-r border-slate-300 hover:bg-slate-50 transition disabled:opacity-30"
                                                                <?php echo e($item->quantity <= 1 ? 'disabled' : ''); ?>

                                                            >
                                                                <i class="ri-subtract-line text-xs font-bold"></i>
                                                            </button>
                                                        </form>


                                                        <span class="w-10 md:w-11 h-8 md:h-9 flex items-center justify-center text-sm font-medium text-[#212121]">
                                                            <?php echo e($item->quantity); ?>

                                                        </span>


                                                        <form
                                                            action="<?php echo e(route('cart.update', $item->id)); ?>"
                                                            method="POST"
                                                        >
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PATCH'); ?>

                                                            <input
                                                                type="hidden"
                                                                name="quantity"
                                                                value="<?php echo e($item->quantity + 1); ?>"
                                                            >

                                                            <button
                                                                type="submit"
                                                                class="w-8 h-8 md:w-9 md:h-9 flex items-center justify-center text-[#212121] bg-white border-l border-slate-300 hover:bg-slate-50 transition"
                                                            >
                                                                <i class="ri-add-line text-xs font-bold"></i>
                                                            </button>
                                                        </form>

                                                    </div>

                                                </div>

                                            </div>


                                            
                                            <div class="flex-1 min-w-0">

                                                <a
                                                    href="<?php echo e(route('products.show', $item->product->slug)); ?>"
                                                    class="block font-medium text-[#212121] text-sm md:text-base lg:text-[17px] leading-6 hover:text-[#006837] transition line-clamp-2"
                                                >
                                                    <?php echo e($item->product->name); ?>

                                                </a>


                                                
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-2">

                                                    <?php if(($item->product->original_price ?? 0) > $item->price): ?>

                                                        <span class="text-xs md:text-sm text-[#878787] line-through">
                                                            ₹<?php echo e(number_format($item->product->original_price)); ?>

                                                        </span>

                                                    <?php endif; ?>

                                                    <span class="text-lg md:text-xl font-medium text-[#212121]">
                                                        ₹<?php echo e(number_format($item->price, fmod($item->price, 1) !== 0.0 ? 2 : 0)); ?>

                                                    </span>


                                                    <?php if(($item->product->original_price ?? 0) > $item->price): ?>

                                                        <span class="text-xs md:text-sm font-medium text-[#388e3c]">
                                                            <?php echo e(round((($item->product->original_price - $item->price) / $item->product->original_price) * 100)); ?>% Off
                                                        </span>

                                                    <?php endif; ?>

                                                </div>


                                                
                                                <div class="hidden md:flex items-center gap-3 mt-3 text-xs text-slate-500">

                                                    <span class="flex items-center gap-1">
                                                        <i class="ri-shield-check-line text-[#388e3c]"></i>
                                                        Genuine Product
                                                    </span>

                                                    <span class="flex items-center gap-1">
                                                        <i class="ri-truck-line text-[#006837]"></i>
                                                        Free Delivery
                                                    </span>

                                                </div>


                                                
                                                <div class="mt-5 pt-4 border-t border-slate-100">

                                                    <form
                                                        action="<?php echo e(route('cart.remove', $item->id)); ?>"
                                                        method="POST"
                                                    >
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>

                                                        <button
                                                            type="submit"
                                                            class="text-xs md:text-sm font-medium text-[#212121] hover:text-[#006837] uppercase tracking-wide transition"
                                                        >
                                                            REMOVE
                                                        </button>

                                                    </form>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                        
                        <div class="bg-white rounded-sm shadow-sm p-4 md:p-5">

                            <div class="flex items-center justify-between gap-4">

                                <div class="hidden sm:block">

                                    <p class="text-xs text-slate-500">
                                        Safe and secure checkout
                                    </p>

                                    <p class="text-sm font-medium text-[#212121] mt-1">
                                        <?php echo e($cart->items->count()); ?> items
                                    </p>

                                </div>


                                <a
                                    href="<?php echo e(route('checkout')); ?>"
                                    class="ml-auto w-full sm:w-auto min-w-[220px] md:min-w-[240px] px-8 md:px-12 py-3.5 md:py-4 bg-[#e94f1c] text-white font-medium text-sm md:text-base text-center uppercase tracking-wide rounded-sm hover:bg-[#cc4214] transition shadow"
                                >
                                    PLACE ORDER
                                </a>

                            </div>

                        </div>

                    </div>

                </div>


                
                <div class="md:col-span-5 xl:col-span-4 min-w-0">

                    <div class="md:sticky md:top-20 space-y-4">

                        
                        <div class="bg-white rounded-sm shadow-sm p-4 md:p-5">

                            <div class="flex items-center justify-between mb-4">

                                <h3 class="text-sm md:text-base font-medium text-[#212121] flex items-center gap-2">
                                    <i class="ri-coupon-3-line text-[#006837] text-lg"></i>
                                    Apply Coupon
                                </h3>

                            </div>


                            <?php if(session('coupon_code')): ?>

                                <div class="flex items-center justify-between gap-3 p-3 bg-[#f2fdf5] border border-[#388e3c]/20 rounded-sm">

                                    <div class="flex items-center gap-2 min-w-0">

                                        <i class="ri-checkbox-circle-fill text-[#388e3c]"></i>

                                        <span class="font-bold text-[#388e3c] text-xs uppercase truncate">
                                            <?php echo e(session('coupon_code')); ?> Applied
                                        </span>

                                    </div>

                                    <form
                                        action="<?php echo e(route('cart.coupon.remove')); ?>"
                                        method="POST"
                                        class="shrink-0"
                                    >
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

                                        <button
                                            type="submit"
                                            class="text-red-500 font-bold text-xs hover:text-red-700"
                                        >
                                            Remove
                                        </button>
                                    </form>

                                </div>

                            <?php else: ?>

                                <form
                                    action="<?php echo e(route('cart.coupon')); ?>"
                                    method="POST"
                                    class="flex gap-2"
                                >
                                    <?php echo csrf_field(); ?>

                                    <input
                                        type="text"
                                        name="code"
                                        placeholder="Enter coupon code"
                                        class="flex-1 min-w-0 px-3 py-2.5 border border-slate-200 rounded-sm text-sm outline-none focus:border-[#006837] uppercase font-medium"
                                    >

                                    <button
                                        type="submit"
                                        class="px-4 md:px-5 py-2.5 text-white bg-[#006837] font-medium text-sm rounded-sm shadow-sm hover:bg-green-800 transition"
                                    >
                                        Apply
                                    </button>

                                </form>

                            <?php endif; ?>

                        </div>


                        
                        <div class="bg-white rounded-sm shadow-sm p-4 md:p-5 lg:p-6">

                            <h3 class="text-sm md:text-base font-medium text-[#878787] uppercase tracking-wide mb-5 border-b border-slate-200 pb-4">
                                PRICE DETAILS
                            </h3>


                            <div class="space-y-4 text-sm md:text-base text-[#212121]">

                                <div class="flex justify-between gap-4">
                                    <span>
                                        Price (<?php echo e($cart->items->count()); ?> items)
                                    </span>

                                    <span class="font-medium">
                                        ₹<?php echo e(number_format($subtotal, fmod($subtotal, 1) !== 0.0 ? 2 : 0)); ?>

                                    </span>
                                </div>


                                <div class="flex justify-between gap-4">

                                    <span>
                                        Delivery Charges
                                    </span>

                                    <span class="font-medium text-[#388e3c]">
                                        <?php if($delivery > 0): ?>
                                            ₹<?php echo e(number_format($delivery, fmod($delivery, 1) !== 0.0 ? 2 : 0)); ?>

                                        <?php else: ?>
                                            <span class="font-semibold text-[#388e3c]">FREE</span>
                                        <?php endif; ?>
                                    </span>

                                </div>


                                <?php if(session('coupon_discount')): ?>

                                    <div class="flex justify-between gap-4 text-[#388e3c]">

                                        <span>
                                            Coupon Discount
                                        </span>

                                        <span class="font-medium">
                                            -₹<?php echo e(number_format($couponDiscount)); ?>

                                        </span>

                                    </div>

                                <?php endif; ?>


                                <div class="border-t border-dashed border-slate-300 pt-4 mt-2 flex justify-between gap-4 font-medium text-lg md:text-xl">

                                    <span class="text-[#212121]">
                                        Total Amount
                                    </span>

                                    <span class="text-[#212121]">
                                        ₹<?php echo e(number_format($finalTotal, fmod($finalTotal, 1) !== 0.0 ? 2 : 0)); ?>

                                    </span>

                                </div>


                                <?php if($totalSavings > 0): ?>

                                    <div class="border-t border-slate-200 pt-4 mt-4">

                                        <p class="text-[#388e3c] font-medium text-sm">
                                            You will save ₹<?php echo e(number_format($totalSavings)); ?> on this order
                                        </p>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>


                        
                        <div class="bg-white rounded-sm shadow-sm p-4">

                            <div class="flex items-center justify-center gap-2 text-xs md:text-sm font-medium text-[#878787]">

                                <i class="ri-shield-check-fill text-[#878787] text-lg"></i>

                                <span>
                                    Safe and Secure Payments
                                </span>

                            </div>

                            <div class="text-center text-[11px] text-slate-400 mt-1">
                                Easy returns and secure checkout
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/buyer/cart.blade.php ENDPATH**/ ?>