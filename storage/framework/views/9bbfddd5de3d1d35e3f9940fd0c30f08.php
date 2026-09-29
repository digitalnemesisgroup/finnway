<?php $__env->startSection('content'); ?>
<div class="max-w-xl mx-auto py-12 px-4">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="bg-[#006837] px-6 py-4">
            <h1 class="text-xl font-bold text-white">Become a Seller</h1>
            <p class="text-white/80 text-sm mt-1">Start selling on FIINWAY marketplace</p>
        </div>

        <form action="<?php echo e(route('seller.onboarding.store')); ?>" method="POST" class="p-6 space-y-6" x-data="{ sellerType: '' }">
            <?php echo csrf_field(); ?>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Select Seller Type <span class="text-red-500">*</span></label>
                <select name="seller_type" class="input bg-white w-full p-3 border rounded-md" required x-model="sellerType">
                    <option value="">Select Seller Type</option>
                    <option value="Manufacturer">Manufacturer</option>
                    <option value="Distributor">Distributor</option>
                    <option value="Wholesaler">Wholesaler</option>
                    <option value="Retailer">Retailer</option>
                    <option value="Individual Seller">Individual Seller</option>
                </select>
                <p class="text-xs text-slate-500 mt-2">Individual Seller option is useful when selling old/used products.</p>
            </div>

            <div x-show="sellerType && sellerType !== 'Individual Seller'" x-transition>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Business Name</label>
                <input type="text" name="business_name" class="input w-full p-3 border rounded-md" placeholder="e.g. Acme Corp">
            </div>

            <div x-show="sellerType && sellerType !== 'Individual Seller'" x-transition>
                <label class="block text-sm font-semibold text-slate-700 mb-1">GST Number</label>
                <input type="text" name="gst_number" class="input w-full p-3 border rounded-md" placeholder="Enter GST Number (Optional)">
            </div>

            <button type="submit" class="w-full py-3 bg-[#e94f1c] hover:bg-[#cc4214] text-white font-bold text-base rounded-md shadow">
                Create Seller Account
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/seller/onboarding.blade.php ENDPATH**/ ?>