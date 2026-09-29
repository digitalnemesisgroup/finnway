<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Users</h1>
    
    <div class="flex gap-2">
        <?php $__currentLoopData = ['all' => 'All Users', 'sellers' => 'Sellers', 'buyers' => 'Buyers Only', 'blocked' => 'Blocked']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.users', ['filter' => $val])); ?>" class="btn btn-sm <?php echo e($filter === $val ? 'btn-primary' : 'btn-outline bg-white border-slate-200 text-slate-600'); ?>">
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
                    <th>User Info</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Role</th>
                    <th>Wallet / Referral</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-500">
                                <?php echo e(substr($user->name, 0, 1)); ?>

                            </div>
                            <div>
                                <p class="font-bold text-slate-800"><?php echo e($user->name); ?></p>
                                <p class="text-[0.65rem] text-slate-400">Joined: <?php echo e($user->created_at->format('d M, Y')); ?></p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="font-semibold text-slate-700">+91 <?php echo e($user->phone); ?></p>
                        <p class="text-xs text-slate-500"><?php echo e($user->email ?? 'No email'); ?></p>
                    </td>
                    <td>
                        <p class="text-sm"><?php echo e($user->city ?? 'N/A'); ?></p>
                        <p class="text-xs text-slate-500"><?php echo e($user->state); ?></p>
                    </td>
                    <td>
                        <?php if($user->isAdmin()): ?>
                            <span class="badge badge-purple">Admin</span>
                        <?php elseif($user->is_seller): ?>
                            <span class="badge badge-success">Seller</span>
                        <?php else: ?>
                            <span class="badge badge-primary">Buyer</span>
                        <?php endif; ?>
                        
                        <?php if($user->is_blocked): ?>
                            <span class="badge badge-danger ml-1">Blocked</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <p class="font-bold text-[#006837]">₹<?php echo e(number_format($user->wallet_balance)); ?></p>
                        <p class="text-xs text-slate-500">Ref: <?php echo e($user->referral_code); ?></p>
                    </td>
                    <td>
                        <?php if(!$user->isAdmin()): ?>
                            <form action="<?php echo e(route('admin.users.toggle-block', $user->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm <?php echo e($user->is_blocked ? 'btn-success' : 'btn-danger'); ?>">
                                    <?php echo e($user->is_blocked ? 'Unblock' : 'Block'); ?>

                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-8 text-slate-500">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($users->hasPages()): ?>
    <div class="p-4 border-t border-slate-100">
        <?php echo e($users->links('pagination::tailwind')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/users.blade.php ENDPATH**/ ?>