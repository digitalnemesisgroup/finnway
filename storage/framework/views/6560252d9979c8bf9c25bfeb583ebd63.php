<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Refunds</h1>
    
    <div class="flex gap-2">
        <?php $__currentLoopData = ['all' => 'All', 'pending' => 'Pending', 'processed' => 'Processed']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.refunds', ['status' => $val])); ?>" class="btn btn-sm <?php echo e($status === $val ? 'btn-primary' : 'btn-outline bg-white border-slate-200 text-slate-600'); ?>">
                <?php echo e($label); ?>

            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>


<div class="fk-card p-0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Refund Date</th>
                    <th>Order & Buyer</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-sm text-slate-500"><?php echo e($refund->created_at->format('d M, Y')); ?></td>
                    <td>
                        <p class="font-bold text-slate-800"><?php echo e($refund->order->order_number); ?></p>
                        <p class="text-xs text-slate-500"><?php echo e($refund->order->buyer->name); ?></p>
                    </td>
                    <td>
                        <p class="font-semibold text-slate-700 text-sm uppercase"><?php echo e($refund->payment->method); ?></p>
                        <p class="text-[0.65rem] text-slate-400">Orig Ref: <?php echo e($refund->payment->transaction_id); ?></p>
                    </td>
                    <td class="font-bold text-danger">₹<?php echo e(number_format($refund->amount)); ?></td>
                    <td>
                        <span class="badge <?php echo e($refund->status === 'processed' ? 'badge-success' : 'badge-warning'); ?>"><?php echo e($refund->statusLabel()); ?></span>
                        <?php if($refund->transaction_ref): ?>
                            <p class="text-[0.65rem] text-slate-500 mt-1">Ref: <?php echo e($refund->transaction_ref); ?></p>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($refund->status === 'pending'): ?>
                            <form action="<?php echo e(route('admin.refunds.process', $refund->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="fk-btn-primary btn-sm" onclick="return confirm('Process this refund via Razorpay? This cannot be undone.')">Process Refund</button>
                            </form>
                        <?php else: ?>
                            <span class="text-xs text-slate-400 font-semibold">Processed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-8 text-slate-500">No refunds found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($refunds->hasPages()): ?>
    <div class="p-4 border-t border-slate-100">
        <?php echo e($refunds->links('pagination::tailwind')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/refunds.blade.php ENDPATH**/ ?>