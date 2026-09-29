<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'FIINWAY — India ka Bazaar'); ?></title>
    <link rel="icon" href="<?php echo e(asset('logo.png')); ?>" type="image/png">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'FIINWAY: India\'s leading marketplace for new and pre-owned electronics, mobiles, laptops and gadgets with secure payments.'); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #f1f3f6; }
        .fk-btn-primary { background: #e94f1c; color: #fff; font-weight: 700; padding: 0.7rem 2rem; border-radius: 2px; transition: background 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,.2); letter-spacing: .04em; text-transform: uppercase; }
        .fk-btn-primary:hover { background: #cc4214; }
        .fk-btn-outline { background: #fff; color: #e94f1c; border: 1px solid #e94f1c; font-weight: 700; padding: 0.7rem 2rem; border-radius: 2px; letter-spacing: .04em; text-transform: uppercase; }
        .fk-card { background: #fff; border-radius: 2px; }
        .fk-price { color: #212121; font-weight: 700; font-size: 1.1rem; }
        .fk-mrp { color: #878787; text-decoration: line-through; font-size: .85rem; }
        .fk-discount { color: #388e3c; font-weight: 600; font-size: .85rem; }
        .fk-tag-new { background: #ff6161; color: #fff; font-size: 10px; padding: 2px 6px; font-weight: 700; border-radius: 2px; }
        .fk-tag-used { background: #ff9f00; color: #fff; font-size: 10px; padding: 2px 6px; font-weight: 700; border-radius: 2px; }
        .fk-star { background: #388e3c; color: #fff; font-size: 11px; padding: 2px 6px; border-radius: 2px; font-weight: 700; }
        [x-cloak] { display: none !important; }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="antialiased min-h-screen flex flex-col" style="background:#f1f3f6;">
    <?php
        $cartCount = 0;
        if (Auth::check()) {
            $cart = Auth::user()->cart;
            $cartCount = $cart ? $cart->items()->count() : 0;
        }
    ?>

    
    <?php if(!isset($hideHeader)): ?>
        <?php if (isset($component)) { $__componentOriginalfd1f218809a441e923395fcbf03e4272 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfd1f218809a441e923395fcbf03e4272 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $attributes = $__attributesOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__attributesOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfd1f218809a441e923395fcbf03e4272)): ?>
<?php $component = $__componentOriginalfd1f218809a441e923395fcbf03e4272; ?>
<?php unset($__componentOriginalfd1f218809a441e923395fcbf03e4272); ?>
<?php endif; ?>
    <?php endif; ?>

    
    <?php if(session('success')): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-16 left-4 right-4 z-[100] max-w-sm mx-auto p-4 rounded bg-green-700 text-white shadow-xl flex items-center gap-3">
        <i class="ri-checkbox-circle-fill text-xl shrink-0"></i>
        <span class="flex-1 text-sm font-semibold"><?php echo e(session('success')); ?></span>
        <button @click="show = false" class="text-white/80 hover:text-white"><i class="ri-close-line text-lg"></i></button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
         class="fixed top-16 left-4 right-4 z-[100] max-w-sm mx-auto p-4 rounded bg-red-600 text-white shadow-xl flex items-center gap-3">
        <i class="ri-error-warning-fill text-xl shrink-0"></i>
        <span class="flex-1 text-sm font-semibold"><?php echo e(session('error')); ?></span>
        <button @click="show = false" class="text-white/80 hover:text-white"><i class="ri-close-line text-lg"></i></button>
    </div>
    <?php endif; ?>

    <main class="flex-1 pb-16 md:pb-0">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php if(!isset($hideFooter)): ?>
    <footer style="background:#172337;" class="text-slate-300 hidden md:block mt-4">
        
        <div class="max-w-7xl mx-auto px-4 py-10 flex flex-wrap md:flex-nowrap justify-between gap-4 text-[11px] border-b border-slate-700">
            <div>
                <h4 class="text-white font-bold uppercase tracking-wider mb-3 text-[11px]">About</h4>
                <ul class="space-y-2 text-slate-400">
                    <li><a href="<?php echo e(route('page.contact')); ?>" class="hover:text-white">Contact Us</a></li>
                    <li><a href="<?php echo e(route('page.about')); ?>" class="hover:text-white">About FIINWAY</a></li>
                    <li><a href="<?php echo e(route('page.careers')); ?>" class="hover:text-white"></a></li>
                    <li><a href="<?php echo e(route('page.press')); ?>" class="hover:text-white"></a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold uppercase tracking-wider mb-3 text-[11px]">Help</h4>
                <ul class="space-y-2 text-slate-400">
                    <li><a href="<?php echo e(route('returns.index')); ?>" class="hover:text-white">Manage Returns</a></li>
                    <li><a href="<?php echo e(route('orders')); ?>" class="hover:text-white">Track Order</a></li>
                    <li><a href="<?php echo e(route('page.support')); ?>" class="hover:text-white">Customer Support</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-bold uppercase tracking-wider mb-3 text-[11px]">Policy</h4>
                <ul class="space-y-2 text-slate-400">
                    <li><a href="<?php echo e(route('page.terms')); ?>" class="hover:text-white">Terms & Conditions</a></li>
                    <li><a href="<?php echo e(route('page.privacy')); ?>" class="hover:text-white">Privacy Policy</a></li>
                    <li><a href="<?php echo e(route('page.return-policy')); ?>" class="hover:text-white">Return & Replacement</a></li>
                    <li><a href="<?php echo e(route('page.refund')); ?>" class="hover:text-white">Refund & Cancellation</a></li>
                    <li><a href="<?php echo e(route('page.shipping')); ?>" class="hover:text-white">Shipping & Delivery</a></li>
                </ul>
            </div>
            <!--<div>-->
            <!--    <h4 class="text-white font-bold uppercase tracking-wider mb-3 text-[11px]">Legal & Security</h4>-->
            <!--    <ul class="space-y-2 text-slate-400">-->
            <!--        <li><a href="<?php echo e(route('page.payments')); ?>" class="hover:text-white">Payment Policy</a></li>-->
            <!--        <li><a href="<?php echo e(route('page.grievance')); ?>" class="hover:text-white">Grievance Redressal</a></li>-->
            <!--        <li><a href="<?php echo e(route('page.seller-policy')); ?>" class="hover:text-white">Seller Policy</a></li>-->
            <!--        <li><a href="<?php echo e(route('page.disclaimer')); ?>" class="hover:text-white">Disclaimer</a></li>-->
            <!--        <li><a href="<?php echo e(route('page.security')); ?>" class="hover:text-white">Security</a></li>-->
            <!--    </ul>-->
            <!--</div>-->
            <div>
                <h4 class="text-white font-bold uppercase tracking-wider mb-3 text-[11px]">Social</h4>
                <ul class="space-y-2 text-slate-400">
                    <li><a href="https://facebook.com" target="_blank" rel="noopener" class="hover:text-white flex items-center gap-1.5"><i class="ri-facebook-fill text-[#1877f2]"></i> Facebook</a></li>
                    <li><a href="https://twitter.com" target="_blank" rel="noopener" class="hover:text-white flex items-center gap-1.5"><i class="ri-twitter-x-fill"></i> Twitter</a></li>
                    <li><a href="https://instagram.com" target="_blank" rel="noopener" class="hover:text-white flex items-center gap-1.5"><i class="ri-instagram-fill text-pink-400"></i> Instagram</a></li>
                    <li><a href="https://youtube.com" target="_blank" rel="noopener" class="hover:text-white flex items-center gap-1.5"><i class="ri-youtube-fill text-red-500"></i> YouTube</a></li>
                </ul>
            </div>
            <!--<div>-->
            <!--    <h4 class="text-white font-bold uppercase tracking-wider mb-3 text-[11px]">Sell on FIINWAY</h4>-->
            <!--    <ul class="space-y-2 text-slate-400">-->
            <!--        <li><a href="<?php echo e(route('page.sell-online')); ?>" class="hover:text-white">Sell Products Online</a></li>-->
            <!--        <li><a href="<?php echo e(route('business.payment-gateway.index')); ?>" class="hover:text-white font-bold text-[#e94f1c]">Payment Gateway <i class="ri-arrow-right-up-line"></i></a></li>-->
            <!--        <li><a href="<?php echo e(route('seller.dashboard')); ?>" class="hover:text-white">Seller Dashboard</a></li>-->
            <!--        <li><a href="<?php echo e(route('seller.earnings')); ?>" class="hover:text-white">View Earnings</a></li>-->
            <!--    </ul>-->
            <!--</div>-->
        </div>
        
        <div class="max-w-7xl mx-auto px-4 py-4 flex flex-col md:flex-row items-center justify-between gap-4 text-[11px] text-slate-500">
            <div class="flex items-center gap-2">
                <img src="<?php echo e(asset('logo.png')); ?>" alt="FIINWAY Logo" class="h-6 object-contain grayscale brightness-200">
                <span>© <?php echo e(date('Y')); ?> FIINWAY 360 COMMUNICATION</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1 text-slate-400"><i class="ri-shield-check-fill text-green-400 text-sm"></i> Verified & Secure Payments</span>
                <span class="flex items-center gap-1 text-slate-400"><i class="ri-truck-line text-green-500 text-sm"></i> Pan India Delivery</span>
            </div>
        </div>
    </footer>
    <?php endif; ?>

    
    <?php if(!isset($hideNav)): ?>
    <?php $notifCount = Auth::check() ? Auth::user()->notifications()->where('is_read', false)->count() : 0; ?>
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 flex justify-around items-center py-1.5 border-t border-slate-200" style="background:#fff;">
        <a href="<?php echo e(route('home')); ?>" class="flex flex-col items-center gap-0.5 text-[10px] font-bold px-3 <?php echo e(request()->routeIs('home') ? 'text-green-700' : 'text-slate-500'); ?>">
            <i class="ri-home-5-<?php echo e(request()->routeIs('home') ? 'fill' : 'line'); ?> text-2xl"></i>
            Home
        </a>
        <a href="<?php echo e(route('products')); ?>" class="flex flex-col items-center gap-0.5 text-[10px] font-bold px-3 <?php echo e(request()->routeIs('products*') ? 'text-green-700' : 'text-slate-500'); ?>">
            <i class="ri-search-<?php echo e(request()->routeIs('products*') ? 'fill' : 'line'); ?> text-2xl"></i>
            Search
        </a>
        <a href="<?php echo e(Auth::check() ? route('cart') : route('mobile')); ?>" class="flex flex-col items-center gap-0.5 text-[10px] font-bold px-3 relative <?php echo e(request()->routeIs('cart') ? 'text-green-700' : 'text-slate-500'); ?>">
            <i class="ri-shopping-cart-2-<?php echo e(request()->routeIs('cart') ? 'fill' : 'line'); ?> text-2xl"></i>
            <?php if($cartCount > 0): ?>
                <span class="absolute top-0 right-1 text-[8px] font-black text-white rounded-full w-4 h-4 flex items-center justify-center" style="background:#ff6161;"><?php echo e($cartCount); ?></span>
            <?php endif; ?>
            Cart
        </a>
        <a href="<?php echo e(Auth::check() ? route('wishlist') : route('mobile')); ?>" class="flex flex-col items-center gap-0.5 text-[10px] font-bold px-3 <?php echo e(request()->routeIs('wishlist') ? 'text-green-700' : 'text-slate-500'); ?>">
            <i class="ri-heart-3-<?php echo e(request()->routeIs('wishlist') ? 'fill' : 'line'); ?> text-2xl"></i>
            Wishlist
        </a>
        <a href="<?php echo e(Auth::check() ? route('orders') : route('mobile')); ?>" class="flex flex-col items-center gap-0.5 text-[10px] font-bold px-3 <?php echo e(request()->routeIs('orders*') ? 'text-green-700' : 'text-slate-500'); ?>">
            <i class="ri-file-list-3-<?php echo e(request()->routeIs('orders*') ? 'fill' : 'line'); ?> text-2xl"></i>
            Orders
        </a>
    </nav>
    <?php endif; ?>

    <?php if(auth()->guard()->check()): ?>
    <?php if(config('broadcasting.default') === 'reverb' && env('REVERB_APP_KEY') && env('REVERB_HOST') && env('REVERB_HOST') !== 'localhost'): ?>
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.3.0/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@2.1.0/dist/echo.iife.js"></script>
    <script>
        if (typeof Echo !== 'undefined') {
            const EchoClass = Echo.default || Echo;
            window.EchoInstance = new EchoClass({
                broadcaster: 'reverb',
                key: '<?php echo e(env("REVERB_APP_KEY")); ?>',
                wsHost: '<?php echo e(env("REVERB_HOST")); ?>',
                wsPort: <?php echo e(env("REVERB_PORT", 8080)); ?>,
                wssPort: <?php echo e(env("REVERB_PORT", 443)); ?>,
                forceTLS: <?php echo e(env("REVERB_SCHEME", "https") === "https" ? "true" : "false"); ?>,
                enabledTransports: ['ws', 'wss'],
                authEndpoint: '/broadcasting/auth',
            });
            window.Echo = window.EchoInstance;
        }
    </script>
    <?php endif; ?>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/layouts/app.blade.php ENDPATH**/ ?>