<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto py-12 px-4">
    <h1 class="text-3xl font-black text-slate-900 mb-8">My Wallets</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Buyer Wallet -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white">Buyer Wallet</h2>
                <p class="text-indigo-100 text-sm mt-1">Use for purchasing products</p>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Available Balance</p>
                    <p class="text-4xl font-black text-slate-900 mt-1">₹<?php echo e(number_format(Auth::user()->wallet_balance, 2)); ?></p>
                </div>
                
                <form action="<?php echo e(route('wallet.topup')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Top-up Amount (₹)</label>
                        <input type="number" name="amount" class="w-full p-3 border rounded-md" placeholder="Enter amount" min="1" required>
                    </div>
                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-md">Top Up Wallet</button>
                </form>
            </div>
        </div>

        <!-- Seller Wallet -->
        <?php if(Auth::user()->is_seller): ?>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-[#006837] px-6 py-4">
                <h2 class="text-xl font-bold text-white">Seller Wallet</h2>
                <p class="text-green-100 text-sm mt-1">Earnings from sales</p>
            </div>
            <div class="p-6">
                <div class="mb-6">
                    <p class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Available Earnings</p>
                    <p class="text-4xl font-black text-slate-900 mt-1">₹<?php echo e(number_format(Auth::user()->seller_wallet_balance, 2)); ?></p>
                </div>
                
                <form action="<?php echo e(route('wallet.withdraw')); ?>" method="POST" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Withdrawal Amount (₹)</label>
                        <input type="number" name="amount" class="w-full p-3 border rounded-md" placeholder="Enter amount" min="1" max="<?php echo e(Auth::user()->seller_wallet_balance); ?>" required>
                    </div>
                    <button type="submit" class="w-full py-3 bg-[#006837] hover:bg-[#004e29] text-white font-bold rounded-md" <?php echo e(Auth::user()->seller_wallet_balance <= 0 ? 'disabled' : ''); ?>>Request Withdrawal</button>
                </form>
            </div>
        </div>
        <?php else: ?>
        <div class="bg-slate-50 rounded-xl border border-dashed border-slate-300 flex flex-col items-center justify-center p-8 text-center">
            <i class="ri-store-2-line text-4xl text-slate-400 mb-3"></i>
            <h3 class="text-lg font-bold text-slate-700 mb-2">Not a Seller Yet?</h3>
            <p class="text-sm text-slate-500 mb-4">Create a seller account to start earning from your products.</p>
            <a href="<?php echo e(route('seller.onboarding')); ?>" class="px-5 py-2.5 bg-[#e94f1c] hover:bg-[#cc4214] text-white font-bold rounded-md text-sm">Become a Seller</a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/wallet/index.blade.php ENDPATH**/ ?>