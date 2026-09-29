<?php $__env->startSection('title', 'My Orders — FIINWAY'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#f1f3f6] min-h-screen pb-16 md:pb-0">
    <div class="max-w-5xl mx-auto px-2 sm:px-4 py-4 sm:py-6 space-y-3">

        
        <div class="bg-white rounded-sm shadow-sm p-4">
            <h1 class="text-xl font-medium text-[#212121] mb-3">My Orders</h1>
            <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-hide">
                <?php $tabs = ['all'=>'All','pending'=>'Pending','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled']; ?>
                <?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('orders', ['status'=>$val])); ?>"
                       class="px-4 py-1.5 rounded text-sm font-medium border whitespace-nowrap transition-colors
                       <?php echo e($status === $val ? 'border-[#006837] text-[#006837] bg-green-50' : 'border-slate-200 text-[#212121] hover:border-[#006837]'); ?>">
                        <?php echo e($label); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        
        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-sm shadow-sm overflow-hidden">
                
                <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-4 text-xs text-slate-500 font-medium flex-wrap">
                        <span>ORDER #<?php echo e($order->order_number); ?></span>
                        <span><?php echo e($order->created_at->format('d M Y, h:i A')); ?></span>
                        <?php if($order->payment_status === 'paid'): ?>
                            <span class="text-[#388e3c] font-bold uppercase">● PAID</span>
                        <?php else: ?>
                            <span class="text-[#ff9f00] font-bold uppercase">● PAYMENT PENDING</span>
                        <?php endif; ?>
                    </div>
                    <span class="text-xs font-bold px-2 py-1 rounded uppercase
                        <?php echo e($order->status === 'delivered' ? 'bg-[#388e3c] text-white' : ($order->status === 'cancelled' ? 'bg-red-600 text-white' : 'bg-[#006837] text-white')); ?>">
                        <?php echo e(strtoupper($order->status)); ?>

                    </span>
                </div>

                
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="px-4 py-4 flex items-center gap-4 border-b border-slate-100 last:border-0">
                        <div class="w-16 h-16 shrink-0 border border-slate-100 bg-white flex items-center justify-center p-1">
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
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-[#212121] text-sm truncate"><?php echo e($item->product_name); ?></h4>
                            <p class="text-xs text-slate-500 mt-0.5">Seller: <?php echo e($item->seller->name ?? 'Verified Seller'); ?> • Qty: <?php echo e($item->quantity); ?></p>
                        </div>
                        <span class="font-bold text-[#212121] text-sm shrink-0">₹<?php echo e(number_format($item->subtotal)); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <div class="px-4 py-3 bg-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                    <div>
                        <span class="text-xs text-slate-500">Total Amount</span>
                        <p class="text-lg font-bold text-[#212121]">₹<?php echo e(number_format($order->total)); ?></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if($order->payment_status !== 'paid'): ?>
                            <a href="<?php echo e(route('payment', $order->id)); ?>" class="px-6 py-2 bg-[#e94f1c] text-white font-bold text-sm rounded-sm hover:bg-[#cc4214] transition-colors flex items-center gap-2">
                                <i class="ri-bank-card-line"></i> Complete Payment
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(route('orders.track', $order->id)); ?>" class="px-5 py-2 border border-slate-300 text-[#212121] font-medium text-sm rounded-sm hover:bg-slate-50">
                                Track
                            </a>
                            <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="px-5 py-2 border border-[#006837] text-[#006837] font-medium text-sm rounded-sm hover:bg-green-50">
                                View Details
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white rounded-sm shadow-sm p-16 text-center">
                <i class="ri-file-list-3-line text-5xl text-slate-200 block mb-4"></i>
                <h3 class="text-lg font-medium text-[#212121] mb-2">No orders found</h3>
                <p class="text-sm text-slate-500 mb-6">You haven't placed any orders yet.</p>
                <a href="<?php echo e(route('products')); ?>" class="px-8 py-3 bg-[#e94f1c] text-white font-bold text-sm rounded-sm hover:bg-[#cc4214]">Start Shopping</a>
            </div>
        <?php endif; ?>

        <div class="pb-4"><?php echo e($orders->links('pagination::tailwind')); ?></div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/buyer/orders.blade.php ENDPATH**/ ?>