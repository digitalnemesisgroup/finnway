<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Return Requests</h1>
    
    <div class="flex gap-2">
        <?php $__currentLoopData = ['all' => 'All', 'requested' => 'New Requests', 'approved' => 'Approved', 'rejected' => 'Rejected']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.returns', ['status' => $val])); ?>" class="btn btn-sm <?php echo e($status === $val ? 'btn-primary' : 'btn-outline bg-white border-slate-200 text-slate-600'); ?>">
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
                    <th>Date</th>
                    <th>Order & Buyer</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $req): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="text-sm text-slate-500"><?php echo e($req->created_at->format('d M, Y')); ?></td>
                    <td>
                        <p class="font-bold text-slate-800"><?php echo e($req->order->order_number); ?></p>
                        <p class="text-xs text-slate-500"><?php echo e($req->buyer->name); ?> • <?php echo e($req->buyer->phone); ?></p>
                    </td>
                    <td>
                        <p class="font-semibold text-slate-700 text-sm"><?php echo e($req->reason); ?></p>
                        <p class="text-xs text-slate-500 line-clamp-1 max-w-[250px]"><?php echo e($req->description); ?></p>
                    </td>
                    <td>
                        <span class="badge badge-<?php echo e($req->statusColor()); ?>"><?php echo e($req->statusLabel()); ?></span>
                    </td>
                    <td>
                        <?php if($req->status === 'requested'): ?>
                        <div class="flex gap-2" x-data="{ open: false }">
                            <button @click="open = true" class="fk-btn-primary btn-sm">Process</button>
                            
                            <!-- Process Modal -->
                            <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40" x-cloak>
                                <div class="bg-white rounded-xl p-6 w-[450px] shadow-xl" @click.away="open = false">
                                    <h3 class="font-bold text-lg mb-4">Process Return Request</h3>
                                    
                                    <div class="mb-4 bg-slate-50 p-3 rounded-lg border border-slate-100 text-sm">
                                        <p><strong>Reason:</strong> <?php echo e($req->reason); ?></p>
                                        <p class="mt-1"><strong>Details:</strong> <?php echo e($req->description); ?></p>
                                    </div>

                                    <form action="<?php echo e(route('admin.returns.process', $req->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-4">
                                            <label class="block text-sm font-bold text-slate-700 mb-2">Admin Note (Sent to buyer)</label>
                                            <textarea name="admin_note" class="input w-full" rows="3" placeholder="Provide instructions or reason..."></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2">
                                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject Request</button>
                                            <button type="submit" name="action" value="approve" class="fk-btn-primary btn-sm">Approve & Queue Refund</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                            <?php if($req->admin_note): ?>
                                <button class="fk-btn-outline btn-sm" onclick="alert('Admin Note: <?php echo e(addslashes($req->admin_note)); ?>')">View Note</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5" class="text-center py-8 text-slate-500">No return requests found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($returns->hasPages()): ?>
    <div class="p-4 border-t border-slate-100">
        <?php echo e($returns->links('pagination::tailwind')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/returns.blade.php ENDPATH**/ ?>