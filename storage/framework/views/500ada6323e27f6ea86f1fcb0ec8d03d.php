<?php $__env->startSection('title', 'My Wishlist — FIINWAY'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#f1f3f6] min-h-screen pb-16 md:pb-0">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 py-4 sm:py-6 space-y-3">

        
        <div class="bg-white rounded-sm shadow-sm p-4 flex items-center justify-between">
            <h1 class="text-xl font-medium text-[#212121] flex items-center gap-2">
                <i class="ri-heart-3-fill text-red-500"></i> My Wishlist
                <span class="text-sm text-slate-400 font-normal">(<?php echo e($items->count()); ?> items)</span>
            </h1>
            <a href="<?php echo e(route('products')); ?>" class="text-sm text-[#006837] font-medium hover:underline">Continue Shopping</a>
        </div>

        <?php if($items->isEmpty()): ?>
            <div class="bg-white rounded-sm shadow-sm p-16 text-center">
                <i class="ri-heart-3-line text-6xl text-slate-200 block mb-4"></i>
                <h3 class="text-lg font-medium text-[#212121] mb-2">Your Wishlist is empty!</h3>
                <p class="text-sm text-slate-500 mb-6">Add items that you like to your wishlist. Review them anytime and easily move them to the bag.</p>
                <a href="<?php echo e(route('products')); ?>" class="px-8 py-3 bg-[#e94f1c] text-white font-bold text-sm rounded-sm hover:bg-[#cc4214] inline-block">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($item->product): ?>
                        <?php if (isset($component)) { $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-card','data' => ['product' => $item->product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->product)]); ?>
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
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/buyer/wishlist.blade.php ENDPATH**/ ?>