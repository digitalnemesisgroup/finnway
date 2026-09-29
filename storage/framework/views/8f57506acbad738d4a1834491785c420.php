<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Seller Payouts</h1>
        <p class="text-slate-500 text-sm mt-1">Manage pending payouts to sellers.</p>
    </div>
</div>

<div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 mb-6 flex gap-3">
    <i class="ri-information-fill text-indigo-500 text-xl"></i>
    <div>
        <h4 class="font-bold text-indigo-900 text-sm mb-1">How Payouts Work</h4>
        <p class="text-xs text-indigo-800">When an order is delivered and confirmed by the customer, the seller's earnings enter a <strong><?php echo e(\App\Models\AppSetting::get('payment_hold_days', 2)); ?>-day hold</strong> period. After this hold, earnings are automatically marked as "Released". If you need to manually release early, you can do so here.</p>
    </div>
</div>

<div class="fk-card p-0">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Seller</th>
                    <th>Order</th>
                    <th>Amount</th>
                    <th>Hold Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $earnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $earning): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <p class="font-bold text-slate-800"><?php echo e($earning->seller->name); ?></p>
                        <p class="text-xs text-slate-500">+91 <?php echo e($earning->seller->phone); ?></p>
                    </td>
                    <td>
                        <p class="font-semibold text-slate-700"><?php echo e($earning->order->order_number); ?></p>
                        <p class="text-xs text-slate-500">Ord Total: ₹<?php echo e(number_format($earning->order_amount)); ?></p>
                    </td>
                    <td>
                        <p class="font-black text-emerald-600">₹<?php echo e(number_format($earning->seller_amount)); ?></p>
                        <p class="text-[0.65rem] text-slate-400">Fee: ₹<?php echo e(number_format($earning->commission_amount)); ?></p>
                    </td>
                    <td>
                        <?php if($earning->status === 'on_hold'): ?>
                            <span class="badge badge-warning">On Hold</span>
                            <p class="text-[0.65rem] text-slate-500 mt-1">Till: <?php echo e($earning->hold_until->format('d M, h:i A')); ?></p>
                        <?php elseif($earning->status === 'customer_ok'): ?>
                            <span class="badge badge-info">Customer OK</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form action="<?php echo e(route('admin.payouts.release', $earning->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="fk-btn-primary btn-sm" onclick="return confirm('Release this payout to seller early?')">Force Release</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center py-8 text-slate-500">No pending payouts found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($earnings->hasPages()): ?>
    <div class="p-4 border-t border-slate-100">
        <?php echo e($earnings->links('pagination::tailwind')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/payouts.blade.php ENDPATH**/ ?>