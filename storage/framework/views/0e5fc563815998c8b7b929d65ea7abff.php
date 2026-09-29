<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Admin Dashboard</h1>
    <div class="text-sm text-slate-500"><?php echo e(now()->format('l, d M Y')); ?></div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="stat-icon bg-indigo-100 text-[#006837]"><i class="ri-group-fill"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['users']); ?></div>
            <div class="stat-label">Total Users (<?php echo e($stats['sellers']); ?> Sellers)</div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon bg-emerald-100 text-emerald-600"><i class="ri-shopping-bag-3-fill"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['products']); ?></div>
            <div class="stat-label">Products (<?php echo e($stats['pending_products']); ?> Pending)</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-orange-100 text-orange-700"><i class="ri-shopping-cart-fill"></i></div>
        <div>
            <div class="stat-value"><?php echo e($stats['orders']); ?></div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon bg-green-100 text-green-700"><i class="ri-wallet-3-fill"></i></div>
        <div>
            <div class="stat-value text-xl">₹<?php echo e(number_format($stats['commission'])); ?></div>
            <div class="stat-label">Platform Commission</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-2 gap-8">
    <!-- Pending Products -->
    <div class="fk-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-slate-800">Products Pending Approval</h2>
        <a href="<?php echo e(route('admin.products', ['status' => 'pending'])); ?>" class="text-sm font-semibold" style="color:#006837;">View All</a>
        </div>
        
        <?php if($pendingProducts->isEmpty()): ?>
            <p class="text-slate-500 text-sm text-center py-4">No products pending approval.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $pendingProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="<?php echo e($product->primary_image_url); ?>" class="w-12 h-12 rounded-lg object-cover">
                        <div>
                            <p class="text-sm font-semibold text-slate-800"><?php echo e($product->name); ?></p>
                            <p class="text-xs text-slate-500">By <?php echo e($product->seller->name); ?> • ₹<?php echo e(number_format($product->selling_price)); ?></p>
                        </div>
                    </div>
                    <form action="<?php echo e(route('admin.products.approve', $product->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="fk-btn-primary text-xs py-2 px-4 shadow-none"><i class="ri-check-line"></i> Approve</button>
                    </form>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Orders -->
    <div class="fk-card p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-slate-800">Recent Orders</h2>
            <a href="<?php echo e(route('admin.orders')); ?>" class="text-sm font-semibold" style="color:#006837;">View All</a>
        </div>
        
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Buyer</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="font-semibold text-slate-700"><?php echo e($order->order_number); ?></td>
                        <td><?php echo e($order->buyer->name); ?></td>
                        <td class="font-bold">₹<?php echo e(number_format($order->total)); ?></td>
                        <td><span class="badge <?php echo e($order->status === 'delivered' ? 'badge-success' : 'badge-warning'); ?>"><?php echo e(ucfirst($order->status)); ?></span></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" class="text-center py-4 text-slate-500">No recent orders.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>