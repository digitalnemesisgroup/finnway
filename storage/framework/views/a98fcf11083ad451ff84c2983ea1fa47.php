<?php $__env->startSection('title', $pageTitle . ' — FIINWAY'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#f1f3f6] min-h-screen pb-8">

    
    <div style="background: linear-gradient(135deg, #172337 0%, #006837 100%);" class="text-white py-10 px-4">
        <div class="max-w-5xl mx-auto">
            <?php if(isset($breadcrumb)): ?>
            <p class="text-green-200 text-xs mb-2 uppercase tracking-widest"><?php echo e($breadcrumb); ?></p>
            <?php endif; ?>
            <h1 class="text-2xl md:text-3xl font-bold"><?php echo e($pageTitle); ?></h1>
            <?php if(isset($pageSubtitle)): ?>
            <p class="text-green-100 mt-2 text-sm md:text-base"><?php echo e($pageSubtitle); ?></p>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="bg-white rounded-sm shadow-sm p-6 md:p-10">
            <?php echo $__env->yieldContent('page-content'); ?>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/layout.blade.php ENDPATH**/ ?>