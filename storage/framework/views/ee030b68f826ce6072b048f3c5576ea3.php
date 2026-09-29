<?php $__env->startSection('content'); ?>
<div class="flex items-center gap-4 mb-6">
    <a href="<?php echo e(route('admin.categories')); ?>" class="text-slate-500 hover:text-indigo-600">
        <i class="ri-arrow-left-line text-xl"></i>
    </a>
    <h1 class="text-2xl font-bold text-slate-800">Dynamic Fields for "<?php echo e($category->name); ?>"</h1>
</div>

<?php if(session('success')): ?>
    <div class="mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm font-medium">
        ✅ <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    
    <div class="col-span-1">
        <div class="fk-card p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4">Add New Field</h2>
            <form action="<?php echo e(route('admin.categories.fields.store', $category->id)); ?>" method="POST" class="space-y-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Field Name (e.g. RAM, Screen Size)</label>
                    <input type="text" name="name" class="input w-full" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Input Type</label>
                    <select name="input_type" class="input w-full">
                        <option value="text">Text (Short Answer)</option>
                        <option value="number">Number</option>
                        <option value="select">Select (Dropdown)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Condition</label>
                    <select name="condition_type" class="input w-full">
                        <option value="both">Both (Old & New)</option>
                        <option value="old">Only Old Products</option>
                        <option value="new">Only New Products</option>
                    </select>
                </div>
                <div class="flex gap-4">
                    <div class="w-1/2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Min Length/Value</label>
                        <input type="number" name="min_val" class="input w-full" placeholder="Optional">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Max Length/Value</label>
                        <input type="number" name="max_val" class="input w-full" placeholder="Optional">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Dropdown Options</label>
                    <textarea name="options" class="input w-full" rows="2" placeholder="Comma separated, e.g. Red, Blue, Green"></textarea>
                    <p class="text-[10px] text-slate-500 mt-1">Only applicable if Input Type is Select.</p>
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_required" value="1" class="w-4 h-4 text-indigo-600 rounded border-slate-300">
                        <span class="text-sm font-semibold text-slate-700">Is Required?</span>
                    </label>
                </div>
                <button type="submit" class="fk-btn-primary btn-block">Save Field</button>
            </form>
        </div>
    </div>

    
    <div class="col-span-2">
        <div class="fk-card p-0 overflow-hidden">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 border-b border-slate-100 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-6 py-3 font-semibold">Field Name</th>
                        <th class="px-6 py-3 font-semibold">Type</th>
                        <th class="px-6 py-3 font-semibold">Condition</th>
                        <th class="px-6 py-3 font-semibold">Required</th>
                        <th class="px-6 py-3 font-semibold">Validation/Options</th>
                        <th class="px-6 py-3 font-semibold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__empty_1 = true; $__currentLoopData = $fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-3 font-bold text-slate-800"><?php echo e($field->name); ?></td>
                        <td class="px-6 py-3 uppercase text-[10px] tracking-wider"><?php echo e($field->input_type); ?></td>
                        <td class="px-6 py-3">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold 
                                <?php echo e($field->condition_type == 'both' ? 'bg-blue-100 text-blue-700' : ($field->condition_type == 'old' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700')); ?>">
                                <?php echo e(strtoupper($field->condition_type)); ?>

                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <?php if($field->is_required): ?>
                                <span class="text-red-500 font-bold">Yes</span>
                            <?php else: ?>
                                <span class="text-slate-400">No</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3 text-xs">
                            <?php if($field->min_val || $field->max_val): ?>
                                [<?php echo e($field->min_val ?? '0'); ?> - <?php echo e($field->max_val ?? '∞'); ?>]
                            <?php endif; ?>
                            <?php if($field->options): ?>
                                <div class="text-[10px] text-slate-500 truncate max-w-[150px]"><?php echo e($field->options); ?></div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-3 text-right">
                            <form action="<?php echo e(route('admin.categories.fields.destroy', $field->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Delete this field?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="text-red-500 hover:bg-red-50 p-1.5 rounded"><i class="ri-delete-bin-line"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">No fields configured for this category.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/category-fields.blade.php ENDPATH**/ ?>