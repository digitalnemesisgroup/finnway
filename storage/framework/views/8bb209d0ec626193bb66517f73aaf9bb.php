<?php $__env->startSection('header_title', 'Edit Product'); ?>

<?php $__env->startSection('content'); ?>
<div class="fk-card p-6 max-w-3xl">
    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-100">
        <img src="<?php echo e($product->primary_image_url); ?>" class="w-16 h-16 rounded object-cover border border-slate-200">
        <div>
            <h2 class="text-xl font-bold text-slate-800"><?php echo e($product->name); ?></h2>
            <p class="text-sm text-slate-500">Seller: <span class="font-medium text-slate-700"><?php echo e($product->seller->name); ?></span></p>
        </div>
    </div>

    <form action="<?php echo e(route('admin.products.update', $product->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Product Name</label>
                <input type="text" name="name" value="<?php echo e(old('name', $product->name)); ?>" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-[#006837] focus:outline-none" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                <select name="category_id" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-[#006837] focus:outline-none" required>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e($product->category_id == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Selling Price (₹)</label>
                <input type="number" step="0.01" name="selling_price" value="<?php echo e(old('selling_price', $product->selling_price)); ?>" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-[#006837] focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Original Price (₹)</label>
                <input type="number" step="0.01" name="original_price" value="<?php echo e(old('original_price', $product->original_price)); ?>" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-[#006837] focus:outline-none">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Stock</label>
                <input type="number" name="stock" value="<?php echo e(old('stock', $product->stock)); ?>" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-[#006837] focus:outline-none" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                <select name="status" class="w-full border border-slate-300 rounded px-3 py-2 text-sm focus:border-[#006837] focus:outline-none" required>
                    <option value="active" <?php echo e($product->status == 'active' ? 'selected' : ''); ?>>Active</option>
                    <option value="pending" <?php echo e($product->status == 'pending' ? 'selected' : ''); ?>>Pending</option>
                    <option value="rejected" <?php echo e($product->status == 'rejected' ? 'selected' : ''); ?>>Rejected</option>
                    <option value="inactive" <?php echo e($product->status == 'inactive' ? 'selected' : ''); ?>>Inactive / Off</option>
                    <option value="sold" <?php echo e($product->status == 'sold' ? 'selected' : ''); ?>>Sold</option>
                </select>
            </div>
        </div>
        
        <div class="flex items-center gap-3 pt-4 border-t border-slate-100">
            <button type="submit" class="fk-btn-primary">Save Changes</button>
            <a href="<?php echo e(route('admin.products')); ?>" class="fk-btn-outline">Cancel</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/products-edit.blade.php ENDPATH**/ ?>