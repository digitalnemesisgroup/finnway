<?php $__env->startSection('title', 'My Returns — FIINWAY'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-slate-50 min-h-screen pb-24">
    <div class="bg-white border-b border-slate-100 p-4 sticky top-0 z-40 shadow-sm">
        <h1 class="text-xl font-bold text-slate-900">My Returns</h1>
    </div>

    <div class="p-4 space-y-4">
        <?php $__empty_1 = true; $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <span class="text-xs font-bold text-slate-500">Requested: <?php echo e($req->created_at->format('d M Y')); ?></span>
                <span class="badge badge-<?php echo e($req->statusColor()); ?>"><?php echo e($req->statusLabel()); ?></span>
            </div>
            <div class="p-4">
                <div class="flex items-start gap-4 mb-3">
                    <div class="w-16 h-16 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                        <i class="ri-arrow-go-back-line text-2xl text-slate-400"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">Order <?php echo e($req->order->order_number); ?></p>
                        <p class="text-sm font-semibold text-slate-700 mt-1">Reason: <?php echo e($req->reason); ?></p>
                        <p class="text-xs text-slate-500 line-clamp-2 mt-1"><?php echo e($req->description); ?></p>
                    </div>
                </div>

                <?php if($req->admin_note): ?>
                <div class="mt-3 p-3 bg-slate-50 rounded-lg border border-slate-100 text-sm">
                    <span class="font-bold text-slate-700">Update:</span> <?php echo e($req->admin_note); ?>

                </div>
                <?php endif; ?>
                
                <?php if($req->refund && $req->refund->status === 'processed'): ?>
                <div class="mt-3 p-3 bg-emerald-50 rounded-lg border border-emerald-100 text-sm flex items-center gap-2">
                    <i class="ri-checkbox-circle-fill text-emerald-500 text-lg"></i>
                    <div>
                        <span class="font-bold text-emerald-700">Refund Processed</span>
                        <p class="text-xs text-emerald-600">Ref: <?php echo e($req->refund->transaction_ref); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center text-slate-300 mb-4">
                <i class="ri-arrow-go-back-line text-4xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1">No returns</h3>
            <p class="text-sm text-slate-500">You haven't requested any returns.</p>
        </div>
        <?php endif; ?>

        <div class="mt-4"><?php echo e($returns->links('pagination::tailwind')); ?></div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/buyer/returns/index.blade.php ENDPATH**/ ?>