<?php $__env->startSection('content'); ?>
<div class="bg-white min-h-screen pb-32">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white border-b border-slate-100 p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('seller.products')); ?>" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-700">
                <i class="ri-arrow-left-line text-lg"></i>
            </a>
            <h1 class="text-lg font-bold text-slate-900 line-clamp-1">Edit <?php echo e($product->name); ?></h1>
        </div>
    </div>

    <form action="<?php echo e(route('seller.products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data" class="p-4 space-y-6">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Existing Photos</label>
            <div class="flex overflow-x-auto gap-3 pb-2 hide-scrollbar">
                <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="w-24 h-24 shrink-0 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden relative">
                    <img src="<?php echo e($img->url); ?>" class="w-full h-full object-cover">
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="divider border-dashed border-slate-200"></div>

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Product Name</label>
                <input type="text" name="name" class="input" value="<?php echo e($product->name); ?>" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Selling Price (₹)</label>
                    <input type="number" name="selling_price" class="input" value="<?php echo e((int)$product->selling_price); ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Original Price (₹)</label>
                    <input type="number" name="original_price" class="input" value="<?php echo e((int)$product->original_price); ?>">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Stock Quantity</label>
                <input type="number" name="stock" class="input" value="<?php echo e($product->stock); ?>" min="0" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Description</label>
                <textarea name="description" class="input" rows="4" required><?php echo e($product->description); ?></textarea>
            </div>

            <div class="bg-indigo-50 rounded-xl p-4 border border-indigo-100">
                <h4 class="text-sm font-bold text-indigo-900 mb-1">Editing Limitations</h4>
                <p class="text-xs text-indigo-700 leading-relaxed">For major changes like category or condition, please deactivate this product and create a new one to maintain integrity.</p>
            </div>
        </div>

        <!-- Bottom Actions -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-100 p-4 z-50 shadow-[0_-10px_20px_rgba(0,0,0,0.05)]">
            <div class="container-app">
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-lg shadow-indigo-200">Update Product <i class="ri-save-3-line ml-1"></i></button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['hideNav' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/seller/products/edit.blade.php ENDPATH**/ ?>