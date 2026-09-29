<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white p-6 flex flex-col relative">
    
    <div class="flex-1 max-w-sm mx-auto w-full pt-8">
        <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
            <i class="ri-user-smile-fill text-3xl text-indigo-600"></i>
        </div>
        
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Complete your profile</h1>
        <p class="text-slate-500 text-sm mb-8">Just a few details to get you started.</p>

        <form action="<?php echo e(route('profile.save')); ?>" method="POST" class="space-y-5">
            <?php echo csrf_field(); ?>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Full Name</label>
                <input type="text" name="name" required class="input" placeholder="e.g. Rahul Sharma" value="<?php echo e(old('name')); ?>">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">City</label>
                    <input type="text" name="city" required class="input" placeholder="e.g. Mumbai" value="<?php echo e(old('city')); ?>">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">State</label>
                    <input type="text" name="state" required class="input" placeholder="e.g. Maharashtra" value="<?php echo e(old('state')); ?>">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Pincode</label>
                <input type="text" name="pincode" required pattern="[0-9]{6}" class="input" placeholder="e.g. 400001" value="<?php echo e(old('pincode')); ?>">
            </div>

            <div class="pt-4 border-t border-slate-100">
                <label class="block text-sm font-semibold text-slate-700 mb-1">Referral Code (Optional)</label>
                <input type="text" name="referred_by" class="input uppercase" placeholder="Enter friend's code">
                <p class="text-xs text-slate-500 mt-1">Earn ₹<?php echo e(\App\Models\AppSetting::get('referral_reward', 50)); ?> on your first order if referred!</p>
            </div>

            <div class="pt-6">
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Complete Setup
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['hideNav' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/auth/profile-setup.blade.php ENDPATH**/ ?>