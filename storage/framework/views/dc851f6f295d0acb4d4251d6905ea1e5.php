<?php $__env->startSection('title', 'Merchant Secret Login — FINNWAY 360'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen w-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-900 text-slate-100 relative overflow-hidden">

    <!-- Background glowing decoration -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo & Header -->
        <div class="text-center">
            <img src="<?php echo e(asset('logo.png')); ?>" alt="FINNWAY 360 Logo" class="h-20 w-auto mx-auto object-contain mb-4 drop-shadow-xl">
            <h2 class="text-2xl font-black tracking-tight text-white">Payment Hub Portal</h2>
            <p class="mt-1.5 text-xs text-slate-400">Sign in to view merchant payments, settlements & developer keys</p>
        </div>

        <!-- Login Form Box -->
        <div class="bg-slate-800/80 border border-slate-700/80 backdrop-blur-md rounded-2xl p-8 shadow-2xl space-y-6">
            <?php if($errors->any()): ?>
                <div class="p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm flex items-start gap-3">
                    <i class="ri-error-warning-fill text-lg shrink-0 mt-0.5"></i>
                    <div>
                        <span class="font-bold block">Authentication Failed</span>
                        <span><?php echo e($errors->first()); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('hub.portal.login.post')); ?>" method="POST" class="space-y-5">
                <?php echo csrf_field(); ?>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-300 mb-2">Business Email / Login ID</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="ri-mail-line text-lg"></i>
                        </span>
                        <input type="text" name="login_id" value="<?php echo e(old('login_id')); ?>" required placeholder="e.g. merchant@demobusiness.com or API Key"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-10 pr-4 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div x-data="{ showPass: false }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-300">Password</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="ri-lock-2-line text-lg"></i>
                        </span>
                        <input :type="showPass ? 'text' : 'password'" name="password" required placeholder="Enter password"
                            class="w-full bg-slate-900 border border-slate-700 rounded-xl pl-10 pr-10 py-3 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                        <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-200">
                            <i :class="showPass ? 'ri-eye-off-line' : 'ri-eye-line'" class="text-lg"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm hover:from-blue-500 hover:to-indigo-500 shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2">
                    <i class="ri-login-circle-line text-lg"></i> Secure Merchant Sign In
                </button>
            </form>

            <div class="pt-4 border-t border-slate-700/60 text-center">
                <p class="text-xs text-slate-400">Need a Payment Gateway Account?</p>
                <a href="<?php echo e(route('business.payment-gateway.index')); ?>" class="text-xs font-semibold text-blue-400 hover:underline mt-1 inline-block">Apply for FINNWAY 360 Gateway Access &rarr;</a>
            </div>
        </div>

        <div class="text-center text-xs text-slate-500">
            🔒 Protected by FINNWAY 360° 256-bit SHA-256 HMAC Engine
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hub.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/hub/portal/login.blade.php ENDPATH**/ ?>