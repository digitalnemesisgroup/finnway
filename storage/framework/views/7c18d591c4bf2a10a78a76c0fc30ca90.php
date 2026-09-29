<?php $pageTitle = 'Sell on FIINWAY'; $pageSubtitle = "Reach crores of customers across India — start selling today"; $breadcrumb = 'Sell'; ?>

<?php $__env->startSection('page-content'); ?>
<div class="space-y-8 text-[#212121]">

    
    <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-4 text-center">
        <?php $__currentLoopData = [
            ['icon'=>'👥','title'=>'50M+ Buyers','desc'=>'Instantly access a massive customer base'],
            ['icon'=>'🆓','title'=>'Zero Listing Fee','desc'=>'List unlimited products for free'],
            ['icon'=>'💸','title'=>'Fast Payouts','desc'=>'Get paid within 7–15 business days'],
            ['icon'=>'📊','title'=>'Powerful Analytics','desc'=>'Track sales, views and earnings in real time'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="p-5 bg-[#f1f3f6] rounded-sm">
            <div class="text-3xl mb-2"><?php echo e($item['icon']); ?></div>
            <h3 class="font-bold text-sm"><?php echo e($item['title']); ?></h3>
            <p class="text-xs text-[#878787] mt-1"><?php echo e($item['desc']); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    
    <div>
        <h2 class="text-xl font-bold mb-5">How It Works</h2>
        <div class="space-y-3">
            <?php $__currentLoopData = [
                ['n'=>'1','t'=>'Register as a Seller','d'=>'Create your seller account in minutes — all you need is your phone number, GSTIN and bank account details.'],
                ['n'=>'2','t'=>'List Your Products','d'=>'Upload products with photos, description and price. Our smart catalog makes listings quick and easy.'],
                ['n'=>'3','t'=>'Start Receiving Orders','d'=>'Buyers discover your products and place orders. You get notified instantly via SMS and email.'],
                ['n'=>'4','t'=>'Pack & Ship','d'=>'Our logistics network picks up from your location and delivers to the buyer within the promised timeline.'],
                ['n'=>'5','t'=>'Get Paid','d'=>'Earnings are transferred directly to your bank account after the delivery is confirmed.'],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="flex gap-4 items-start p-4 border border-slate-100 rounded-sm">
                <div class="w-9 h-9 rounded-full text-white font-bold flex items-center justify-center shrink-0 text-sm" style="background:#006837;"><?php echo e($s['n']); ?></div>
                <div>
                    <h3 class="font-bold"><?php echo e($s['t']); ?></h3>
                    <p class="text-sm text-[#878787] mt-0.5"><?php echo e($s['d']); ?></p>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="p-8 rounded-sm text-center text-white" style="background: linear-gradient(135deg, #172337, #006837);">
        <h2 class="text-2xl font-bold mb-2">Ready to start selling?</h2>
        <p class="text-green-100 mb-6 text-sm">Join 2 million+ sellers already growing their business on FIINWAY</p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <?php if(auth()->guard()->check()): ?>
                <?php if(Auth::user()->is_seller): ?>
                    <a href="<?php echo e(route('seller.dashboard')); ?>" class="px-8 py-3 bg-[#e94f1c] text-white font-bold rounded-sm hover:bg-[#cc4214] transition uppercase">Go to Seller Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('seller.products.create')); ?>" class="px-8 py-3 bg-[#e94f1c] text-white font-bold rounded-sm hover:bg-[#cc4214] transition uppercase">Start Selling Now</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="<?php echo e(route('mobile')); ?>" class="px-8 py-3 bg-[#e94f1c] text-white font-bold rounded-sm hover:bg-[#cc4214] transition uppercase">Register as Seller</a>
            <?php endif; ?>
            <a href="<?php echo e(route('page.contact')); ?>" class="px-8 py-3 bg-white text-[#006837] font-bold rounded-sm hover:bg-green-50 transition uppercase">Talk to Sales</a>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('pages.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/sell-online.blade.php ENDPATH**/ ?>