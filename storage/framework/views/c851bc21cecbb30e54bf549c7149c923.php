<?php $__env->startSection('title', 'Browse Products — FIINWAY Marketplace'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#f1f3f6] min-h-screen pb-16 md:pb-0">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 py-4 sm:py-6 flex flex-col md:flex-row gap-4 items-start">

        
        <div class="hidden md:block w-60 shrink-0">
            <div class="bg-white rounded-sm shadow-sm overflow-hidden sticky top-20">
                <div class="px-4 py-3 border-b border-slate-200 flex items-center justify-between">
                    <h2 class="font-bold text-[#212121] text-base">Filters</h2>
                    <a href="<?php echo e(route('products')); ?>" class="text-[#006837] text-xs font-bold hover:underline">Clear All</a>
                </div>
                <form action="<?php echo e(route('products')); ?>" method="GET" id="filterForm" class="divide-y divide-slate-100">
                    <input type="hidden" name="q" value="<?php echo e(request('q')); ?>">
                    <input type="hidden" name="sort" value="<?php echo e(request('sort')); ?>">

                    
                    <div class="p-4">
                        <h4 class="font-bold text-[#212121] text-xs uppercase tracking-wide mb-3">Condition</h4>
                        <?php $__currentLoopData = ['' => 'All', 'new' => 'Brand New', 'old' => 'Pre-owned']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer text-sm text-[#212121]">
                                <input type="radio" name="condition" value="<?php echo e($val); ?>" <?php echo e(request('condition', '') === $val ? 'checked' : ''); ?> class="accent-[#006837]" onchange="document.getElementById('filterForm').submit()">
                                <?php echo e($label); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="p-4">
                        <h4 class="font-bold text-[#212121] text-xs uppercase tracking-wide mb-3">Category</h4>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center gap-2 mb-2 cursor-pointer text-sm text-[#212121]">
                                <input type="checkbox" name="category" value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'checked' : ''); ?> class="accent-[#006837]" onchange="document.getElementById('filterForm').submit()">
                                <?php echo e($cat->name); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="p-4">
                        <h4 class="font-bold text-[#212121] text-xs uppercase tracking-wide mb-3">Price (₹)</h4>
                        <div class="flex gap-2">
                            <input type="number" name="min_price" value="<?php echo e(request('min_price')); ?>" placeholder="Min" class="w-full px-2 py-1.5 border border-slate-200 text-sm outline-none focus:border-[#006837]">
                            <span class="text-slate-400">–</span>
                            <input type="number" name="max_price" value="<?php echo e(request('max_price')); ?>" placeholder="Max" class="w-full px-2 py-1.5 border border-slate-200 text-sm outline-none focus:border-[#006837]">
                        </div>
                    </div>

                    <div class="p-4">
                        <button type="submit" class="w-full py-2.5 bg-[#006837] text-white font-bold text-sm rounded-sm hover:bg-green-800">Apply</button>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="flex-1 w-full space-y-3">
            
            <div class="bg-white rounded-sm shadow-sm px-4 py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-base font-medium text-[#212121]">
                        <?php echo e(request('q') ? '"' . request('q') . '"' : (request('category') ? ($categories->find(request('category'))?->name ?? 'All Products') : 'All Products')); ?>

                    </h1>
                    <p class="text-xs text-slate-500 mt-0.5" id="result-count"><?php echo e($products->total()); ?> results found</p>
                </div>
                <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide text-sm">
                    <span class="text-slate-400 text-xs font-medium shrink-0">Sort by</span>
                    <?php $__currentLoopData = ['popular'=>'Popularity','newest'=>'Newest','price_asc'=>'Price: Low to High','price_desc'=>'Price: High to Low']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val=>$label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort'=>$val])); ?>"
                           class="px-3 py-1.5 border whitespace-nowrap text-xs transition-colors <?php echo e(request('sort','popular')===$val ? 'border-[#006837] text-[#006837] font-bold' : 'border-slate-200 text-[#212121] hover:border-[#006837]'); ?>">
                            <?php echo e($label); ?>

                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    
                    <button @click="$dispatch('open-filters')" class="md:hidden px-3 py-1.5 border border-slate-200 text-xs flex items-center gap-1">
                        <i class="ri-equalizer-line text-[#006837]"></i> Filter
                    </button>
                </div>
            </div>

            
            <?php if($products->isNotEmpty()): ?>
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3" id="products-grid">
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-card','data' => ['product' => $product]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $attributes = $__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__attributesOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a)): ?>
<?php $component = $__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a; ?>
<?php unset($__componentOriginal3fd2897c1d6a149cdb97b41db9ff827a); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div id="scroll-sentinel" class="flex justify-center py-6" data-page="<?php echo e($products->currentPage()); ?>" data-has-more="<?php echo e($products->hasMorePages() ? 'true' : 'false'); ?>">
                    
                    <div id="loading-indicator" class="hidden w-full">
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <?php for($i = 0; $i < 4; $i++): ?>
                                <div class="bg-white rounded-sm shadow-sm animate-pulse">
                                    <div class="bg-slate-200 h-48 w-full rounded-t-sm"></div>
                                    <div class="p-3 space-y-2">
                                        <div class="h-3 bg-slate-200 rounded w-3/4"></div>
                                        <div class="h-3 bg-slate-200 rounded w-1/2"></div>
                                        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>

                    
                    <div id="end-message" class="hidden text-center py-4">
                        <div class="inline-flex items-center gap-2 text-slate-400 text-sm">
                            <div class="h-px w-16 bg-slate-200"></div>
                            <span>All products loaded</span>
                            <div class="h-px w-16 bg-slate-200"></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-sm shadow-sm p-16 text-center">
                    <i class="ri-search-2-line text-5xl text-slate-200 block mb-4"></i>
                    <h3 class="text-lg font-medium text-[#212121] mb-2">No products found</h3>
                    <p class="text-sm text-slate-500 mb-6">Try different keywords or remove filters.</p>
                    <a href="<?php echo e(route('products')); ?>" class="px-8 py-3 bg-[#006837] text-white font-bold text-sm rounded-sm">Clear Filters</a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>


<div x-data="{ open: false }" @open-filters.window="open = true" x-show="open" class="fixed inset-0 z-50 flex items-end" x-cloak>
    <div class="absolute inset-0 bg-black/40" @click="open = false"></div>
    <div class="w-full bg-white rounded-t-xl max-h-[80vh] overflow-y-auto relative z-10" x-transition:enter="transition transform duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0">
        <div class="flex items-center justify-between p-4 border-b border-slate-200 sticky top-0 bg-white">
            <h3 class="font-bold text-[#212121]">Filters</h3>
            <button @click="open = false"><i class="ri-close-line text-xl text-slate-500"></i></button>
        </div>
        <form action="<?php echo e(route('products')); ?>" method="GET" class="p-4 space-y-5">
            <input type="hidden" name="q" value="<?php echo e(request('q')); ?>">
            <div>
                <h4 class="font-bold text-xs uppercase text-slate-500 mb-2">Condition</h4>
                <?php $__currentLoopData = ['' => 'All', 'new' => 'Brand New', 'old' => 'Pre-owned']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center gap-2 mb-2 text-sm text-[#212121]">
                        <input type="radio" name="condition" value="<?php echo e($val); ?>" <?php echo e(request('condition','') === $val ? 'checked' : ''); ?> class="accent-[#006837]"><?php echo e($label); ?>

                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div>
                <h4 class="font-bold text-xs uppercase text-slate-500 mb-2">Category</h4>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="flex items-center gap-2 mb-2 text-sm text-[#212121]">
                        <input type="checkbox" name="category" value="<?php echo e($cat->id); ?>" <?php echo e(request('category')==$cat->id ? 'checked' : ''); ?> class="accent-[#006837]"><?php echo e($cat->name); ?>

                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="flex gap-3 pt-2">
                <a href="<?php echo e(route('products')); ?>" class="flex-1 py-3 border border-slate-300 text-center text-sm font-bold text-[#212121] rounded-sm">Clear</a>
                <button type="submit" class="flex-1 py-3 bg-[#006837] text-white font-bold text-sm rounded-sm">Apply</button>
            </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const sentinel  = document.getElementById('scroll-sentinel');
    const grid      = document.getElementById('products-grid');
    const loader    = document.getElementById('loading-indicator');
    const endMsg    = document.getElementById('end-message');

    if (!sentinel || !grid) return;

    let currentPage = parseInt(sentinel.dataset.page);
    let hasMore     = sentinel.dataset.hasMore === 'true';
    let loading     = false;

    // Build base URL (preserves all current filters/sort/q but strips page)
    function buildUrl(page) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', page);
        return url.toString();
    }

    function loadMore() {
        if (loading || !hasMore) return;
        loading = true;

        loader.classList.remove('hidden');
        endMsg.classList.add('hidden');

        const nextPage = currentPage + 1;

        fetch(buildUrl(nextPage), {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            loader.classList.add('hidden');

            if (data.html) {
                const tmp = document.createElement('div');
                tmp.innerHTML = data.html;

                // Fade-in each card as it appears
                Array.from(tmp.children).forEach((card, i) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(16px)';
                    card.style.transition = `opacity 0.35s ease ${i * 60}ms, transform 0.35s ease ${i * 60}ms`;
                    grid.appendChild(card);
                    // Force reflow then animate in
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        });
                    });
                });
            }

            currentPage = nextPage;
            hasMore     = data.hasMore;
            loading     = false;

            if (!hasMore) {
                endMsg.classList.remove('hidden');
            }
        })
        .catch(() => {
            loader.classList.add('hidden');
            loading = false;
        });
    }

    // IntersectionObserver — fires when sentinel enters viewport
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) loadMore();
        });
    }, { rootMargin: '300px' });   // start loading 300px before bottom

    observer.observe(sentinel);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/buyer/products/index.blade.php ENDPATH**/ ?>