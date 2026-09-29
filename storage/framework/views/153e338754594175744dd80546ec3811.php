<?php $__env->startSection('title', 'Return & Replacement Policy — FIINWAY'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Return &amp; Exchange Policy</h1>
        <p class="text-xs text-slate-400 mb-6">Last Updated: 20 August 2026</p>

        <?php
        $sections = [
            ['title' => 'Return Policy', 'body' => 'We offer a 3-day return window. You may request a return within 3 days of receiving your order.'],
            ['title' => 'Exchange Policy', 'body' => "If you receive a damaged product or the wrong item, please record a clear unboxing video as proof.\n\nOnce our team verifies the issue, we will process an exchange and deliver the replacement product within 7-10 business days."],
        ];
        ?>

        <div class="space-y-6">
            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <h2 class="text-sm font-bold text-[#212121] mb-1"><?php echo e($s['title']); ?></h2>
                <p class="text-sm text-slate-600 whitespace-pre-line"><?php echo e($s['body']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-xs text-slate-400">
            <p><strong class="text-slate-600">Contact Us</strong></p>
            <p>Email: <a href="mailto:grievance@finway.in" class="text-[#e94f1c]">grievance@finway.in</a></p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/return-policy.blade.php ENDPATH**/ ?>