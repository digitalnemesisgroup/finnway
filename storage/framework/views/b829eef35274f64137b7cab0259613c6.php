<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Products Management</h1>
    
    <div class="flex gap-2">
        <?php $__currentLoopData = ['all' => 'All', 'pending' => 'Pending Approval', 'active' => 'Active', 'rejected' => 'Rejected']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.products', ['status' => $val])); ?>" class="btn btn-sm <?php echo e($status === $val ? 'btn-primary' : 'btn-outline bg-white border-slate-200 text-slate-600'); ?>">
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
                    <th>Product</th>
                    <th>Seller</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 shrink-0 rounded-lg overflow-hidden bg-slate-100">
                                <?php if (isset($component)) { $__componentOriginala58dde406db9207f2e2c58e1c4a3d690 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-image','data' => ['product' => $product,'aspect' => 'square','class' => 'w-full h-full object-cover']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product),'aspect' => 'square','class' => 'w-full h-full object-cover']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $attributes = $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $component = $__componentOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800 line-clamp-1 max-w-[200px]"><?php echo e($product->name); ?></p>
                                <p class="text-xs text-slate-500 uppercase"><?php echo e($product->condition_type); ?></p>
                            </div>
                        </div>
                    </td>
                    <td><?php echo e($product->seller->name); ?></td>
                    <td><?php echo e($product->category->name); ?></td>
                    <td class="font-bold">₹<?php echo e(number_format($product->selling_price)); ?></td>
                    <td>
                        <span class="badge <?php echo e($product->status === 'active' ? 'badge-success' : ($product->status === 'pending' ? 'badge-warning' : 'badge-danger')); ?>">
                            <?php echo e(ucfirst($product->status)); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($product->status === 'pending'): ?>
                        <div class="flex gap-2" x-data="{ rejectOpen: false }">
                            <form action="<?php echo e(route('admin.products.approve', $product->id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="fk-btn-primary btn-sm"><i class="ri-check-line"></i></button>
                            </form>
                            <button @click="rejectOpen = true" class="btn btn-danger btn-sm"><i class="ri-close-line"></i></button>
                            
                            <!-- Reject Modal -->
                            <div x-show="rejectOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40" x-cloak>
                                <div class="bg-white rounded-xl p-6 w-96 shadow-xl" @click.away="rejectOpen = false">
                                    <h3 class="font-bold text-lg mb-4">Reject Product</h3>
                                    <form action="<?php echo e(route('admin.products.reject', $product->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <textarea name="reason" class="input w-full mb-4" placeholder="Reason for rejection..." required></textarea>
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="rejectOpen = false" class="fk-btn-outline btn-sm">Cancel</button>
                                            <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php else: ?>
                            <div class="flex items-center gap-2">
                                <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>" class="text-[#006837] hover:underline text-sm font-semibold flex items-center gap-1">
                                    <i class="ri-edit-box-line"></i> Modify
                                </a>
                                <span class="text-slate-300">|</span>
                                <form action="<?php echo e(route('admin.products.toggle', $product->id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="<?php echo e($product->status === 'active' ? 'text-orange-600' : 'text-green-600'); ?> hover:underline text-sm font-semibold flex items-center gap-1">
                                        <i class="ri-power-line"></i> <?php echo e($product->status === 'active' ? 'Turn Off' : 'Turn On'); ?>

                                    </button>
                                </form>
                                <span class="text-slate-300">|</span>
                                <a href="<?php echo e(route('products.show', $product->slug)); ?>" target="_blank" class="text-slate-500 hover:text-slate-700 text-sm font-semibold">
                                    <i class="ri-external-link-line"></i> View
                                </a>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="6" class="text-center py-8 text-slate-500">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if($products->hasPages()): ?>
    <div class="p-4 border-t border-slate-100">
        <?php echo e($products->links('pagination::tailwind')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/products.blade.php ENDPATH**/ ?>