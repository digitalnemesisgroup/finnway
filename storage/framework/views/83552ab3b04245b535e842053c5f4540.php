<aside class="w-[260px] flex-shrink-0 flex flex-col shadow-lg z-50 sticky top-0 h-screen self-start" style="background: #006837;">
    <div class="p-6 mb-2 border-b border-white/10 text-center shrink-0">
        <img src="<?php echo e(asset('logo.png')); ?>" alt="FIINWAY Logo" class="h-10 object-contain mx-auto mb-3 brightness-200 contrast-200 drop-shadow-md">
        <p class="text-[10px] text-green-200 uppercase tracking-[0.2em] font-bold">Admin Portal</p>
    </div>

    <div class="px-4 py-4 flex-1 overflow-y-auto">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
            <i class="ri-dashboard-fill"></i> Dashboard
        </a>

        <?php if(auth()->user()->isPaymentAdmin()): ?>
            <div class="px-4 py-2 mt-2 mb-1 text-[10px] text-green-200 uppercase tracking-[0.1em] font-bold">Payment Engine</div>
            <a href="<?php echo e(route('admin.payment-analytics.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.payment-analytics.index') ? 'active' : ''); ?>">
                <i class="ri-bar-chart-box-fill"></i> Analytics
            </a>
            <a href="<?php echo e(route('admin.payment-clients.index')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.payment-clients.index') ? 'active' : ''); ?>">
                <i class="ri-key-2-fill"></i> API Keys
            </a>
            <a href="<?php echo e(route('admin.payment-clients.docs')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.payment-clients.docs') ? 'active' : ''); ?>">
                <i class="ri-book-read-fill"></i> API Docs
            </a>
        <?php endif; ?>

        <?php if(auth()->user()->isAdmin()): ?>
            <a href="<?php echo e(route('admin.products')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.products') ? 'active' : ''); ?>">
                <i class="ri-shopping-bag-3-fill"></i> Products
            </a>
            <a href="<?php echo e(route('admin.orders')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.orders') ? 'active' : ''); ?>">
                <i class="ri-shopping-cart-fill"></i> Orders
            </a>
            <a href="<?php echo e(route('admin.users')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.users') ? 'active' : ''); ?>">
                <i class="ri-group-fill"></i> Users
            </a>
            <a href="<?php echo e(route('admin.payouts')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.payouts') ? 'active' : ''); ?>">
                <i class="ri-wallet-3-fill"></i> Payouts
            </a>
            <a href="<?php echo e(route('admin.returns')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.returns') ? 'active' : ''); ?>">
                <i class="ri-arrow-go-back-fill"></i> Returns
            </a>
            <a href="<?php echo e(route('admin.refunds')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.refunds') ? 'active' : ''); ?>">
                <i class="ri-refund-2-fill"></i> Refunds
            </a>
        <?php endif; ?>

        <?php if(auth()->user()->isAdmin()): ?>

        <div class="px-4 py-2 mt-2 mb-1 text-[10px] text-green-200 uppercase tracking-[0.1em] font-bold">Store</div>
        <a href="<?php echo e(route('admin.categories')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.categories') ? 'active' : ''); ?>">
            <i class="ri-list-check"></i> Categories
        </a>
        <a href="<?php echo e(route('admin.referrals')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.referrals') ? 'active' : ''); ?>">
            <i class="ri-user-add-fill"></i> Referrals
        </a>
        <a href="<?php echo e(route('admin.settings')); ?>" class="sidebar-link <?php echo e(request()->routeIs('admin.settings') ? 'active' : ''); ?>">
            <i class="ri-settings-3-fill"></i> Settings
        </a>
        <?php endif; ?>
    </div>

    <div class="p-4 border-t border-white/10 bg-black/10 shrink-0">
        <a href="<?php echo e(route('home')); ?>" class="flex items-center gap-2 px-3 py-2 text-green-200 hover:text-white text-sm font-medium transition-colors mb-2">
            <i class="ri-external-link-line"></i> Go to Website
        </a>
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded bg-white/10 hover:bg-[#e94f1c] text-white text-sm font-bold transition-colors">
                <i class="ri-logout-box-r-line"></i> Logout
            </button>
        </form>
    </div>
</aside>
<?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/partials/sidebar.blade.php ENDPATH**/ ?>