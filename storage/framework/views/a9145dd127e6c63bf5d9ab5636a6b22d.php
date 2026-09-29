<header class="sticky top-0 z-50 bg-white border-b border-slate-200 shadow-sm">
    
    <div class="max-w-7xl mx-auto px-2 sm:px-4">
        <div class="flex items-center h-14 gap-2 sm:gap-4">

            
            <a href="<?php echo e(route('home')); ?>" class="flex flex-col items-start shrink-0">
                <img src="<?php echo e(asset('logo.png')); ?>" alt="FIINWAY Logo" class="h-8 sm:h-10 object-contain">
            </a>

            
            <form action="<?php echo e(route('products')); ?>" method="GET" class="hidden md:block flex-1 max-w-2xl">
                <div class="flex items-center bg-slate-100 rounded-sm overflow-hidden border border-slate-200">
                    <input
                        type="text"
                        name="q"
                        value="<?php echo e(request('q')); ?>"
                        placeholder="Search for products, brands and more"
                        class="flex-1 px-3 py-2.5 text-sm text-slate-800 placeholder-slate-500 outline-none bg-transparent"
                    />
                    <button type="submit" class="px-4 py-2.5 flex items-center justify-center" style="background:#ff6161;">
                        <i class="ri-search-line text-white text-lg"></i>
                    </button>
                </div>
            </form>

            
            <div class="hidden md:flex items-center gap-1">

                
                <?php if(auth()->guard()->check()): ?>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded hover:bg-slate-100 transition-colors">
                            <i class="ri-user-line text-slate-800 text-base"></i>
                            <div class="text-left">
                                <span class="text-slate-800 font-bold text-xs block leading-tight"><?php echo e(Str::limit(Auth::user()->name, 10)); ?></span>
                                <span class="text-slate-500 text-[10px]">Account</span>
                            </div>
                            <i class="ri-arrow-down-s-line text-slate-800 text-sm"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" x-cloak
                             class="absolute right-0 mt-1 w-52 bg-white shadow-xl rounded border border-slate-100 z-50 py-1 text-sm text-slate-700">
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="font-bold text-slate-900 text-xs"><?php echo e(Auth::user()->name); ?></p>
                                <p class="text-[10px] text-slate-400"><?php echo e(Auth::user()->phone); ?></p>
                            </div>
                            <?php if(Auth::user()->isAdmin()): ?>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 font-bold text-blue-700 text-xs">
                                    <i class="ri-shield-star-line"></i> Admin Panel
                                </a>
                            <?php endif; ?>
                            <?php if(Auth::user()->isPaymentAdmin()): ?>
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 font-bold text-[#006837] text-xs">
                                    <i class="ri-secure-payment-line"></i> Payment Admin Portal
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo e(route('profile')); ?>" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-xs">
                                <i class="ri-user-line text-slate-400"></i> My Profile
                            </a>
                            <a href="<?php echo e(route('wallet.index')); ?>" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-xs">
                                <i class="ri-wallet-3-line text-slate-400"></i> My Wallet
                            </a>
                            <a href="<?php echo e(route('seller.dashboard')); ?>" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-xs">
                                <i class="ri-store-2-line text-slate-400"></i> Seller Dashboard
                            </a>
                            <a href="<?php echo e(route('orders')); ?>" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-xs">
                                <i class="ri-file-list-3-line text-slate-400"></i> My Orders
                            </a>
                            <a href="<?php echo e(route('wishlist')); ?>" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-xs">
                                <i class="ri-heart-3-line text-slate-400"></i> Wishlist
                            </a>
                            <a href="<?php echo e(route('notifications')); ?>" class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-xs">
                                <i class="ri-notification-3-line text-slate-400"></i> Notifications
                            </a>
                            <div class="border-t border-slate-100 my-1"></div>
                            <form action="<?php echo e(route('logout')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2 text-rose-600 font-bold hover:bg-rose-50 text-xs">
                                    <i class="ri-logout-box-r-line"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo e(route('mobile')); ?>" class="flex items-center gap-1.5 px-4 py-1.5 rounded hover:bg-slate-100 transition-colors border border-slate-200">
                        <i class="ri-user-line text-slate-800 text-base"></i>
                        <div class="text-left">
                            <span class="text-slate-800 font-bold text-xs block leading-tight">Login</span>
                            <span class="text-slate-500 text-[10px]">or Register</span>
                        </div>
                    </a>
                <?php endif; ?>

                
                <!--<a href="<?php echo e(route('seller.products.create')); ?>"-->
                <!--   class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded hover:bg-slate-100 transition-colors text-slate-800 font-bold text-xs">-->
                <!--    <i class="ri-store-2-line text-base text-slate-800"></i>-->
                <!--    I want to sell-->
                <!--</a>-->

                
                <?php
                    $cartCount = 0;
                    if (Auth::check()) {
                        $cart = Auth::user()->cart;
                        $cartCount = $cart ? $cart->items()->count() : 0;
                    }
                ?>
                <a href="<?php echo e(route('cart')); ?>"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded hover:bg-slate-100 transition-colors relative">
                    <i class="ri-shopping-cart-2-line text-slate-800 text-xl"></i>
                    <span class="text-slate-800 font-bold text-xs hidden lg:inline">Cart</span>
                    <?php if($cartCount > 0): ?>
                        <span class="absolute -top-0.5 -right-0.5 w-5 h-5 rounded-full text-[10px] font-black flex items-center justify-center border-2 border-white" style="background:#ff6161; color:white;">
                            <?php echo e($cartCount); ?>

                        </span>
                    <?php endif; ?>
                </a>

            </div>

            
            <div class="flex md:hidden items-center gap-2 ml-auto">
                <a href="<?php echo e(route('cart')); ?>" class="relative p-1.5">
                    <i class="ri-shopping-cart-2-line text-slate-800 text-xl"></i>
                    <?php if($cartCount > 0): ?>
                        <span class="absolute top-0 right-0 w-4 h-4 rounded-full text-[9px] font-black flex items-center justify-center border border-white" style="background:#ff6161; color:white;"><?php echo e($cartCount); ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </div>

    
    <div class="md:hidden px-2 pb-2">
        <form action="<?php echo e(route('products')); ?>" method="GET">
            <div class="flex items-center bg-white rounded-sm overflow-hidden">
                <i class="ri-search-line text-slate-400 ml-3 text-sm"></i>
                <input type="text" name="q" value="<?php echo e(request('q')); ?>"
                       placeholder="Search for products, brands and more"
                       class="flex-1 px-2 py-2 text-xs text-slate-800 outline-none bg-white placeholder-slate-400"/>
                <button type="submit" class="px-3 py-2" style="background:#ff6161;">
                    <i class="ri-search-line text-white text-sm"></i>
                </button>
            </div>
        </form>
    </div>
</header>
<?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/components/header.blade.php ENDPATH**/ ?>