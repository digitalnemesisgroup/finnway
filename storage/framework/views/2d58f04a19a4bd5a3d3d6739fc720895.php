<?php $__env->startSection('title', 'Refund Policy — FIINWAY'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Refund Policy</h1>
        <p class="text-xs text-slate-400 mb-6">Last Updated: 20 August 2026 | FIINWAY 360 COMMUNICATION</p>

        <?php $sections = [
            ['title' => 'Refund Inspection', 'body' => 'We will notify you once we receive and inspect your returned item. After inspection, we will inform you whether your refund is approved.'],
            ['title' => 'Refund Timeline', 'body' => "If approved, your refund will be processed and credited to your original payment method within 7 business days.\n\nPlease note that your bank or card issuer may take additional time to reflect the refund."],
            ['title' => 'Delayed Refunds', 'body' => "If more than 15 business days have passed since your refund was approved, please contact us at:\nEmail: grievance@finway.in"],
        ]; ?>

        <div class="space-y-6">
            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <h2 class="text-sm font-bold text-[#212121] mb-1"><?php echo e($s['title']); ?></h2>
                <p class="text-sm text-slate-600 whitespace-pre-line"><?php echo e($s['body']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-xs text-slate-400">
            <p>© 2026 FIINWAY 360 COMMUNICATION. All Rights Reserved.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/refund.blade.php ENDPATH**/ ?>