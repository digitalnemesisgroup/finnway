<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">All Orders</h1>
    
    <div class="flex gap-2">
        <?php $__currentLoopData = ['all' => 'All', 'pending' => 'Pending', 'confirmed' => 'Processing', 'shipped' => 'Shipped', 'delivered' => 'Delivered']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.orders', ['status' => $val])); ?>" class="btn btn-sm <?php echo e($status === $val ? 'btn-primary' : 'btn-outline bg-white border-slate-200 text-slate-600'); ?>">
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
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Buyer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="font-bold text-slate-700"><?php echo e($order->order_number); ?></td>
                    <td class="text-sm"><?php echo e($order->created_at->format('d M, Y')); ?></td>
                    <td>
                        <p class="font-semibold"><?php echo e($order->buyer->name); ?></p>
                        <p class="text-xs text-slate-500">+91 <?php echo e($order->buyer->phone); ?></p>
                    </td>
                    <td class="font-black text-[#006837]">₹<?php echo e(number_format($order->total)); ?></td>
                    <td>
                        <span class="badge <?php echo e($order->status === 'delivered' ? 'badge-success' : 'badge-primary'); ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($order->payment_status === 'paid'): ?>
                            <div class="flex items-center gap-1 text-green-600 text-sm font-bold">
                                <i class="ri-check-double-line"></i> <?php echo e(strtoupper($order->payment_method)); ?>

                            </div>
                        <?php else: ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-8 text-slate-500">No orders found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($orders->hasPages()): ?>
    <div class="p-4 border-t border-slate-100">
        <?php echo e($orders->links('pagination::tailwind')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/orders.blade.php ENDPATH**/ ?>