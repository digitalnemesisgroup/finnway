<?php $__env->startSection('content'); ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Category Management</h1>
</div>

<?php if(session('success')): ?>
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-medium">
        ✅ <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="grid grid-cols-3 gap-8">
    
    <div class="col-span-1">
        <div class="fk-card p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Add New Category</h2>
            <form action="<?php echo e(route('admin.categories.store')); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Name</label>
                    <input type="text" name="name" class="input w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Icon (Emoji)</label>
                    <input type="text" name="icon" class="input w-full" placeholder="e.g. 📱">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" class="input w-full" value="0">
                </div>
                <button type="submit" class="fk-btn-primary btn-block">Add Category</button>
            </form>
        </div>
    </div>

    
    <div class="col-span-2 space-y-4">
        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="fk-card p-0 overflow-hidden" x-data="{ open: false }">
            
            <div class="flex items-center justify-between p-4 cursor-pointer hover:bg-slate-50 transition-colors" @click="open = !open">
                <div class="flex items-center gap-3">
                    <span class="text-2xl"><?php echo e($cat->icon); ?></span>
                    <div>
                        <p class="font-bold text-slate-800"><?php echo e($cat->name); ?></p>
                        <p class="text-xs text-slate-500"><?php echo e($cat->products_count); ?> products</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <div class="text-right text-xs">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-white text-[10px] font-bold
                            <?php echo e($cat->commission_type === 'percent' ? 'bg-indigo-500' : 'bg-orange-500'); ?>">
                            <?php echo e($cat->commission_type === 'percent' ? $cat->commission_value . '%' : '₹'.$cat->commission_value); ?>

                        </span>
                        <span class="ml-1 text-slate-500">commission</span>
                    </div>
                    <i class="ri-settings-3-line text-slate-400 text-lg transition-transform" :class="open ? 'rotate-90' : ''"></i>
                </div>
            </div>

            
            <div x-show="open" x-cloak class="border-t border-slate-100 bg-slate-50 p-5">
                <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="ri-money-dollar-circle-line text-indigo-500"></i>
                    Commission & Delivery Settings for "<?php echo e($cat->name); ?>"
                </h3>

                <form action="<?php echo e(route('admin.categories.commission', $cat->id)); ?>" method="POST" class="grid grid-cols-2 gap-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Commission Type</label>
                        <div class="flex gap-3">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="commission_type" value="percent" <?php echo e($cat->commission_type === 'percent' ? 'checked' : ''); ?> class="text-indigo-600">
                                <span class="text-sm font-medium">Percentage (%)</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="commission_type" value="flat" <?php echo e($cat->commission_type === 'flat' ? 'checked' : ''); ?> class="text-indigo-600">
                                <span class="text-sm font-medium">Flat (₹)</span>
                            </label>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Commission Value (₹ / %)</label>
                        <input type="number" name="commission_value" step="0.01" min="0"
                               value="<?php echo e($cat->commission_value ?? 5); ?>"
                               class="input w-full" required>
                    </div>

                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Other Applicable Fee (₹)</label>
                        <input type="number" name="other_fee" step="0.01" min="0"
                               value="<?php echo e($cat->other_fee ?? 0); ?>"
                               class="input w-full" placeholder="0">
                    </div>

                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Delivery Responsibility</label>
                        <select name="delivery_responsibility" class="input w-full">
                            <option value="seller" <?php echo e(($cat->delivery_responsibility ?? 'seller') === 'seller' ? 'selected' : ''); ?>>Seller</option>
                            <option value="fiinway" <?php echo e(($cat->delivery_responsibility ?? '') === 'fiinway' ? 'selected' : ''); ?>>FIINWAY</option>
                            <option value="third_party" <?php echo e(($cat->delivery_responsibility ?? '') === 'third_party' ? 'selected' : ''); ?>>Third Party</option>
                        </select>
                    </div>

                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Settlement</label>
                        <div class="flex gap-3 mt-1">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="settlement_type" value="automatic" <?php echo e(($cat->settlement_type ?? 'automatic') === 'automatic' ? 'checked' : ''); ?> class="text-indigo-600">
                                <span class="text-sm font-medium">Automatic</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="settlement_type" value="manual" <?php echo e(($cat->settlement_type ?? '') === 'manual' ? 'checked' : ''); ?> class="text-indigo-600">
                                <span class="text-sm font-medium">Admin Approval</span>
                            </label>
                        </div>
                    </div>

                    
                    <div class="col-span-2">
                        <div class="bg-blue-50 rounded-lg p-3 border border-blue-100 text-xs text-blue-800">
                            <strong>Current Settings:</strong>
                            Commission: <?php echo e($cat->commission_type === 'percent' ? $cat->commission_value.'%' : '₹'.$cat->commission_value); ?> |
                            Other Fee: ₹<?php echo e($cat->other_fee ?? 0); ?> |
                            Delivery: <?php echo e(ucfirst($cat->delivery_responsibility ?? 'seller')); ?> |
                            Settlement: <?php echo e(ucfirst($cat->settlement_type ?? 'automatic')); ?>

                        </div>
                    </div>

                    <div class="col-span-2 flex justify-between mt-4">
                        <a href="<?php echo e(route('admin.categories.fields', $cat->id)); ?>" class="fk-btn-outline px-4 text-xs">
                            <i class="ri-list-settings-line mr-1"></i> Manage Dynamic Fields
                        </a>
                        <button type="submit" class="fk-btn-primary px-6">
                            <i class="ri-save-line mr-1"></i> Save Commission Settings
                        </button>
                    </div>
                            <i class="ri-save-line mr-1"></i> Save Commission Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php if($categories->isEmpty()): ?>
            <div class="fk-card p-8 text-center text-slate-500">No categories yet. Add one on the left.</div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/categories.blade.php ENDPATH**/ ?>