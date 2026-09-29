<?php $__env->startSection('title', 'Checkout — FIINWAY'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ showAddressModal: <?php echo e($addresses->isEmpty() ? 'true' : 'false'); ?>, deliveryOption: 'standard', expressFee: 99, baseTotal: <?php echo e($total); ?>, useWallet: false, walletBalance: <?php echo e(Auth::user()->wallet_balance); ?> }">

    <!-- Header -->
    <div class="bg-white p-6 rounded-sm border border-slate-100 shadow-xs flex items-center justify-between">
        <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Checkout</h1>
            <p class="text-xs font-semibold text-slate-400 mt-1">Confirm delivery details & payment</p>
        </div>
        <a href="<?php echo e(route('cart')); ?>" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs transition-colors flex items-center gap-1">
            <i class="ri-arrow-left-line"></i> Back to Cart
        </a>
    </div>

    <form action="<?php echo e(route('order.place')); ?>" method="POST" id="checkoutForm">
        <?php echo csrf_field(); ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left 8 Cols: Delivery Address & Shipping Options -->
            <div class="lg:col-span-8 space-y-6">

                <!-- Delivery Address Section -->
                <div class="bg-white p-6 rounded-sm border border-slate-100 shadow-xs space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                                <i class="ri-map-pin-user-line text-[#006837]"></i> Delivery Address
                            </h3>
                            <p class="text-xs text-slate-400 font-semibold">Select where you want your order delivered</p>
                        </div>
                        <button type="button" @click="showAddressModal = true" class="px-4 py-2 rounded-sm bg-[#e94f1c] hover:bg-[#cc4214] text-white font-bold text-xs transition-colors flex items-center gap-1">
                            <i class="ri-add-line"></i> Add New Address
                        </button>
                    </div>

                    <?php if($addresses->isEmpty()): ?>
                        <div class="p-6 rounded-sm bg-amber-50/50 border border-amber-200/80 text-center space-y-3">
                            <div class="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl mx-auto">
                                <i class="ri-map-pin-warning-line"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-amber-900 text-sm">No Saved Address Found</h4>
                                <p class="text-xs text-amber-700 font-medium">Please add a delivery address below to complete your checkout.</p>
                            </div>
                            <button type="button" @click="showAddressModal = true" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs shadow-md shadow-amber-600/20">
                                + Create New Address
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <label class="relative p-5 rounded-sm border-2 cursor-pointer transition-all flex flex-col justify-between space-y-3 bg-white"
                                    :class="selectedAddress == '<?php echo e($address->id); ?>' ? 'border-[#006837] bg-[#f0f9f4] ring-2 ring-[#006837]/10' : 'border-slate-100 hover:border-slate-200'"
                                    x-data="{ selectedAddress: '<?php echo e($defaultAddress->id ?? $addresses->first()->id); ?>' }">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" name="address_id" value="<?php echo e($address->id); ?>" 
                                                class="w-4 h-4 text-[#006837] focus:ring-[#006837]" 
                                                <?php echo e(($defaultAddress->id ?? null) === $address->id ? 'checked' : ''); ?>>
                                            <span class="font-extrabold text-slate-900 text-sm"><?php echo e($address->full_name); ?></span>
                                        </div>
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700">
                                            <?php echo e($address->label); ?>

                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                        <?php echo e($address->fullText()); ?>

                                    </p>
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-xs font-semibold text-slate-500">
                                        <span><i class="ri-phone-line"></i> +91 <?php echo e($address->phone); ?></span>
                                        <?php if($address->is_default): ?>
                                            <span class="text-[#006837] font-extrabold text-[10px] uppercase">Default</span>
                                        <?php endif; ?>
                                    </div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Delivery Speed Options -->
                <div class="bg-white p-6 rounded-sm border border-slate-100 shadow-xs space-y-4">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <i class="ri-truck-line text-[#006837]"></i> Delivery Shipping Speed
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="p-5 rounded-sm border-2 cursor-pointer transition-all flex items-center justify-between"
                            :class="deliveryOption === 'standard' ? 'border-[#006837] bg-[#f0f9f4]' : 'border-slate-100 bg-white'"
                            @click="deliveryOption = 'standard'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="delivery_option" value="standard" class="w-4 h-4 text-[#006837]" checked>
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm">Standard Delivery</h4>
                                    <p class="text-xs text-slate-400 font-medium">Delivered in 3-5 business days</p>
                                </div>
                            </div>
                            <span class="font-black text-emerald-600 text-xs">FREE</span>
                        </label>

                        <label class="p-5 rounded-sm border-2 cursor-pointer transition-all flex items-center justify-between"
                            :class="deliveryOption === 'express' ? 'border-[#006837] bg-[#f0f9f4]' : 'border-slate-100 bg-white'"
                            @click="deliveryOption = 'express'">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="delivery_option" value="express" class="w-4 h-4 text-[#006837]">
                                <div>
                                    <h4 class="font-extrabold text-slate-900 text-sm">Express Speed</h4>
                                    <p class="text-xs text-slate-400 font-medium">Delivered in 1-2 business days</p>
                                </div>
                            </div>
                            <span class="font-black text-slate-900 text-xs">+₹99</span>
                        </label>
                    </div>
                </div>

                <!-- Order Items Review -->
                <div class="bg-white p-6 rounded-sm border border-slate-100 shadow-xs space-y-4">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <i class="ri-shopping-bag-3-line text-[#006837]"></i> Items In Order
                    </h3>

                    <div class="divide-y divide-slate-100">
                        <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if(!$item->product): ?> <?php continue; ?> <?php endif; ?>
                            <div class="py-3 first:pt-0 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 shrink-0">
                                        <?php if (isset($component)) { $__componentOriginala58dde406db9207f2e2c58e1c4a3d690 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-image','data' => ['product' => $item->product,'aspect' => 'square','class' => 'w-full h-full !rounded-xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->product),'aspect' => 'square','class' => 'w-full h-full !rounded-xl']); ?>
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
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm line-clamp-1"><?php echo e($item->product->name); ?></h4>
                                        <p class="text-xs text-slate-400 font-medium">Seller: <?php echo e($item->product->seller?->name ?? 'Verified'); ?> • Qty: <?php echo e($item->quantity); ?></p>
                                    </div>
                                </div>
                                <span class="font-black text-slate-900 text-sm">₹<?php echo e(number_format($item->subtotal, fmod($item->subtotal, 1) !== 0.0 ? 2 : 0)); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

            </div>

            <!-- Right 4 Cols: Payment Summary & Proceed Button -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-slate-900 text-white p-6 rounded-sm shadow-xl space-y-6 sticky top-24">
                    <h3 class="text-lg font-black text-white">Payment Breakdown</h3>

                    <div class="space-y-3 text-xs font-semibold text-slate-300">
                        <div class="flex justify-between">
                            <span>Subtotal (<?php echo e($cart->items->count()); ?> items)</span>
                            <span class="text-white font-bold">₹<?php echo e(number_format($subtotal, fmod($subtotal, 1) !== 0.0 ? 2 : 0)); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Delivery Shipping</span>
                            <span class="text-emerald-400 font-bold" x-text="deliveryOption === 'express' ? '₹99' : 'FREE'"></span>
                        </div>
                        <?php if($discount > 0): ?>
                        <div class="flex justify-between text-emerald-400 font-bold">
                            <span>Coupon Discount</span>
                            <span>-₹<?php echo e(number_format($discount, fmod($discount, 1) !== 0.0 ? 2 : 0)); ?></span>
                        </div>
                        <?php endif; ?>

                        <?php if(Auth::user()->wallet_balance > 0): ?>
                        <label class="flex justify-between items-center mt-2 cursor-pointer text-white font-medium text-xs">
                            <span class="flex items-center gap-2">
                                <input type="checkbox" name="use_wallet" value="1" x-model="useWallet" class="rounded bg-slate-800 border-slate-700 text-[#e94f1c] focus:ring-[#e94f1c]">
                                Use Wallet Balance (₹<?php echo e(number_format(Auth::user()->wallet_balance)); ?>)
                            </span>
                            <span x-show="useWallet" class="text-emerald-400 font-bold">-₹<span x-text="Math.min(walletBalance, (deliveryOption === 'express' ? baseTotal + expressFee : baseTotal))"></span></span>
                        </label>
                        <?php endif; ?>
                        
                        <div class="pt-4 border-t border-slate-800 flex justify-between items-center text-sm">
                            <span class="font-extrabold text-white">Total Amount</span>
                            <span class="font-black text-emerald-400 text-2xl"
                                x-text="'₹' + Math.max(0, (deliveryOption === 'express' ? baseTotal + expressFee : baseTotal) - (useWallet ? walletBalance : 0)).toLocaleString('en-IN')">
                            </span>
                        </div>
                    </div>

                    <div class="p-4 rounded-sm bg-white/10 backdrop-blur-md border border-white/10 text-xs text-slate-300 flex items-center gap-2">
                        <i class="ri-shield-check-fill text-emerald-400 text-xl shrink-0"></i>
                        <span>Secure FINNWAY 360 Gateway with 256-bit encryption & HMAC signature verification.</span>
                    </div>

                    <button type="submit" <?php if($addresses->isEmpty()): ?> disabled <?php endif; ?> 
                        class="w-full py-4 rounded-sm bg-indigo-600 hover:bg-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-extrabold text-sm shadow-xl shadow-[#e94f1c]/30 transition-all active:scale-95 flex items-center justify-center gap-2">
                        Proceed to Pay <i class="ri-arrow-right-line"></i>
                    </button>
                    
                    <input type="hidden" name="buyer_latitude" id="buyer_latitude">
                    <input type="hidden" name="buyer_longitude" id="buyer_longitude">
                </div>
            </div>

        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    document.getElementById('buyer_latitude').value = lat;
                    document.getElementById('buyer_longitude').value = lng;
                }, function(error) {
                    console.warn('Geolocation failed: ', error.message);
                });
            }
        });
    </script>

    <!-- Slide-over / Modal: Add New Address -->
    <div x-show="showAddressModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs" @click="showAddressModal = false"></div>

        <div class="relative bg-white w-full max-w-lg rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6 z-10 overflow-y-auto max-h-[90vh]"
             x-show="showAddressModal" 
             x-transition:enter="transition ease-out duration-300 transform" 
             x-transition:enter-start="opacity-0 scale-95" 
             x-transition:enter-end="opacity-100 scale-100" 
             x-transition:leave="transition ease-in duration-200 transform" 
             x-transition:leave-start="opacity-100 scale-100" 
             x-transition:leave-end="opacity-0 scale-95">

            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <h3 class="text-xl font-black text-slate-900">Add New Delivery Address</h3>
                <button type="button" @click="showAddressModal = false" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:text-slate-800 flex items-center justify-center">
                    <i class="ri-close-line text-lg"></i>
                </button>
            </div>

            <form action="<?php echo e(route('addresses.store')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Name</label>
                        <input type="text" name="full_name" required value="<?php echo e(Auth::user()->name); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:ring-2 focus:ring-[#006837]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Phone Number</label>
                        <input type="text" name="phone" required maxlength="10" placeholder="10 digit mobile" value="<?php echo e(Auth::user()->phone); ?>" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:ring-2 focus:ring-[#006837]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Address Line 1</label>
                    <input type="text" name="address_line1" required placeholder="House/Flat No., Building Name, Street" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:ring-2 focus:ring-[#006837]">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Address Line 2 (Optional)</label>
                    <input type="text" name="address_line2" placeholder="Landmark, Area" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:ring-2 focus:ring-[#006837]">
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">City</label>
                        <input type="text" name="city" required placeholder="City" value="<?php echo e(Auth::user()->city); ?>" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:ring-2 focus:ring-[#006837]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">State</label>
                        <input type="text" name="state" required placeholder="State" value="<?php echo e(Auth::user()->state); ?>" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:ring-2 focus:ring-[#006837]">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pincode</label>
                        <input type="text" name="pincode" required maxlength="6" placeholder="6 digits" value="<?php echo e(Auth::user()->pincode); ?>" class="w-full px-3 py-2.5 rounded-xl border border-slate-200 text-xs font-medium outline-none focus:ring-2 focus:ring-[#006837]">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="label" value="Home" checked class="text-[#006837]">
                            <span class="text-xs font-bold text-slate-700">Home</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="label" value="Work" class="text-[#006837]">
                            <span class="text-xs font-bold text-slate-700">Work</span>
                        </label>
                        <label class="inline-flex items-center gap-1.5 cursor-pointer">
                            <input type="radio" name="label" value="Other" class="text-[#006837]">
                            <span class="text-xs font-bold text-slate-700">Other</span>
                        </label>
                    </div>

                    <label class="inline-flex items-center gap-1.5 cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" checked class="rounded text-[#006837]">
                        <span class="text-xs font-bold text-slate-700">Set Default</span>
                    </label>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showAddressModal = false" class="w-1/2 py-3 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50">
                        Cancel
                    </button>
                    <button type="submit" class="w-1/2 py-3 rounded-xl bg-[#006837] hover:bg-[#004e29] text-white font-extrabold text-xs shadow-md shadow-[#006837]/20">
                        Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/buyer/checkout.blade.php ENDPATH**/ ?>