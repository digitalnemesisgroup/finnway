<?php $__env->startSection('title', 'FIINWAY - Online Shopping Site for Mobiles, Electronics, Furniture, Grocery, Lifestyle, Books & More. Best Offers!'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-4 pt-2">
    <!-- Category Strip -->
    <div class="bg-white shadow-sm overflow-hidden mb-2">
        <div class="max-w-7xl mx-auto px-2 sm:px-4">
            <div class="flex items-center justify-between py-4 overflow-x-auto gap-4 scrollbar-hide">
                <?php $__currentLoopData = $categories->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('products', ['category' => $cat->id])); ?>" class="flex flex-col items-center gap-2 group min-w-[64px] shrink-0">
                        <div class="w-16 h-16 rounded bg-slate-50 flex items-center justify-center text-3xl group-hover:bg-green-50 transition-colors">
                            <?php echo e($cat->icon ?? '🛒'); ?>

                        </div>
                        <span class="text-[13px] font-medium text-slate-800 text-center"><?php echo e($cat->name); ?></span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Hero Banner -->
    <div class="max-w-7xl mx-auto px-2 sm:px-4">
        <div class="rounded overflow-hidden flex flex-col md:flex-row items-center justify-between p-6 md:p-10 text-white relative" style="background: linear-gradient(135deg, #003d1f 0%, #006837 50%, #e94f1c 100%);">
            <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, #fff 0, #fff 1px, transparent 0, transparent 50%); background-size: 12px 12px;"></div>
            <div class="absolute top-0 right-0 w-64 h-full opacity-20" style="background: linear-gradient(135deg, transparent 0%, #e94f1c 100%);"></div>
            <div class="z-10 text-center md:text-left space-y-3">
                <h2 class="text-3xl md:text-5xl font-black italic tracking-tight" style="color: #ffd700;">Big Saving Days</h2>
                <p class="text-lg md:text-xl font-medium text-white/90">Sale is Live. Lowest Prices of the Year!</p>
                <div class="pt-2">
                    <a href="<?php echo e(route('products')); ?>" class="inline-block font-bold px-6 py-2 rounded text-sm transition-colors shadow" style="background: #ffd700; color: #003d1f;">
                        Shop Now
                    </a>
                </div>
            </div>
            <div class="z-10 mt-6 md:mt-0 flex gap-4">
                <i class="ri-smartphone-line text-6xl opacity-90"></i>
                <i class="ri-macbook-line text-6xl opacity-90 hidden sm:block"></i>
                <i class="ri-headphone-line text-6xl opacity-90"></i>
            </div>
        </div>
    </div>

    <?php $__currentLoopData = $categoryGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="max-w-7xl mx-auto px-2 sm:px-4 <?php echo e($index > 0 ? 'mt-4' : ''); ?>">
        <div class="bg-white rounded shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-medium text-slate-800">Best of <?php echo e($category->name); ?></h2>
                    <p class="text-slate-400 text-sm mt-0.5">Top deals on brand new <?php echo e(strtolower($category->name)); ?></p>
                </div>
                <a href="<?php echo e(route('products', ['category' => $category->id])); ?>" class="w-8 h-8 rounded-full bg-green-700 text-white flex items-center justify-center hover:bg-green-800 transition-colors shadow-sm">
                    <i class="ri-arrow-right-s-line text-xl"></i>
                </a>
            </div>
            <div class="p-4 overflow-x-auto">
                <div class="flex gap-4 min-w-max pb-2">
                    <?php $__currentLoopData = $category->top_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="w-48 shrink-0">
                            <?php if (isset($component)) { $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-card','data' => ['product' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
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
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>

    <?php if($index == 0): ?>
    <!-- Referral / Ad Banner after first category -->
    <!--<div class="max-w-7xl mx-auto px-2 sm:px-4 mt-4 mb-4">-->
    <!--    <div class="flex flex-col sm:flex-row gap-4">-->
    <!--        <div class="flex-1 rounded overflow-hidden flex items-center p-6 shadow-sm justify-between relative cursor-pointer"-->
    <!--             style="background: linear-gradient(135deg, #f7a200 0%, #e94f1c 100%);"-->
    <!--             onclick="window.location.href='<?php echo e(route('mobile')); ?>'">-->
    <!--            <div class="z-10">-->
    <!--                <h3 class="font-bold text-xl text-white mb-1">Refer &amp; Earn ₹<?php echo e(\App\Models\AppSetting::get('referral_reward', 50)); ?></h3>-->
    <!--                <p class="text-white/80 text-sm font-medium">Invite friends to FIINWAY &amp; earn rewards</p>-->
    <!--            </div>-->
    <!--            <i class="ri-gift-2-line text-6xl text-white/30 z-10"></i>-->
    <!--        </div>-->
    <!--        <div class="flex-1 rounded overflow-hidden flex items-center p-6 shadow-sm justify-between relative cursor-pointer"-->
    <!--             style="background: linear-gradient(135deg, #003d1f 0%, #006837 100%);"-->
    <!--             onclick="window.location.href='<?php echo e(route('seller.products.create')); ?>'">-->
    <!--            <div class="z-10">-->
    <!--                <h3 class="font-bold text-xl text-white mb-1">I want to sell</h3>-->
    <!--                <p class="text-white/70 text-sm font-medium">Sell to crores of customers on FIINWAY</p>-->
    <!--            </div>-->
    <!--            <i class="ri-store-2-line text-6xl text-white/30 z-10"></i>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/home.blade.php ENDPATH**/ ?>