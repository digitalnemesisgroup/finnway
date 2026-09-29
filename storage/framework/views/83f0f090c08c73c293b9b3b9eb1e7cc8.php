<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['product']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['product']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $liked = auth()->check() && auth()->user()->hasWishlisted($product->id);
?>

<div class="fk-card group relative overflow-hidden hover:shadow-xl transition-shadow duration-200 flex flex-col"
     x-data="wishlistCard(<?php echo e($product->id); ?>, <?php echo e($liked ? 'true' : 'false'); ?>)">

    
    <div class="relative overflow-hidden bg-white flex items-center justify-center p-2" style="aspect-ratio:1/1;">
        <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="block w-full h-full">
            <?php if (isset($component)) { $__componentOriginala58dde406db9207f2e2c58e1c4a3d690 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-image','data' => ['product' => $product,'aspect' => 'square','class' => 'w-full h-full object-contain transition-transform duration-300 group-hover:scale-105']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($product),'aspect' => 'square','class' => 'w-full h-full object-contain transition-transform duration-300 group-hover:scale-105']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $attributes = $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $component = $__componentOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
        </a>

        
        <div class="absolute top-2 left-2 z-10">
            <?php if($product->condition_type === 'new'): ?>
                <span class="fk-tag-new">NEW</span>
            <?php else: ?>
                <span class="fk-tag-used"><?php echo e(strtoupper($product->condition_label ?? 'PRE-OWNED')); ?></span>
            <?php endif; ?>
        </div>

        
        <?php if(auth()->guard()->check()): ?>
            <button type="button" @click.prevent="toggleWishlist()"
                class="absolute top-2 right-2 z-10 w-8 h-8 rounded-full bg-white shadow flex items-center justify-center transition-all hover:scale-110 active:scale-95"
                :title="liked ? 'Remove from Wishlist' : 'Save'">
                <i :class="liked ? 'ri-heart-fill text-red-500' : 'ri-heart-3-line text-slate-400 hover:text-red-400'" class="text-base transition-all"></i>
            </button>
        <?php else: ?>
            <a href="<?php echo e(route('mobile')); ?>" class="absolute top-2 right-2 z-10 w-8 h-8 rounded-full bg-white shadow flex items-center justify-center" title="Login to Save">
                <i class="ri-heart-3-line text-slate-400 text-base"></i>
            </a>
        <?php endif; ?>

        
        <?php if($product->discount_percent > 0): ?>
            <div class="absolute bottom-2 left-2 z-10">
                <span class="text-[10px] font-bold bg-orange-500 text-slate-900 px-1.5 py-0.5 rounded-sm"><?php echo e(round($product->discount_percent)); ?>% off</span>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="p-3 flex flex-col flex-1 justify-between border-t border-slate-100">
        <div>
            
            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">
                <?php echo e($product->brand ?: ($product->category->name ?? 'General')); ?>

            </div>

            
            <h3 class="text-sm font-medium text-slate-800 leading-snug line-clamp-2 mb-1">
                <a href="<?php echo e(route('products.show', $product->slug)); ?>" class="hover:text-green-700">
                    <?php echo e($product->name); ?>

                </a>
            </h3>

            
            <?php if($product->rating): ?>
            <div class="flex items-center gap-1.5 mb-2">
                <span class="fk-star flex items-center gap-0.5">
                    <?php echo e(number_format($product->rating, 1)); ?>

                    <i class="ri-star-fill text-[9px]"></i>
                </span>
                <?php if($product->rating_count): ?>
                <span class="text-[10px] text-slate-400">(<?php echo e($product->rating_count); ?>)</span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="flex items-center justify-between">
            <div>
                <span class="fk-price">₹<?php echo e(number_format($product->selling_price)); ?></span>
                <?php if($product->original_price && $product->original_price > $product->selling_price): ?>
                    <span class="fk-mrp ml-1">₹<?php echo e(number_format($product->original_price)); ?></span>
                    <span class="fk-discount ml-1"><?php echo e(round($product->discount_percent)); ?>% off</span>
                <?php endif; ?>
            </div>

            
            <?php if(auth()->guard()->check()): ?>
                <form action="<?php echo e(route('cart.add')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit"
                            class="w-8 h-8 rounded-full flex items-center justify-center text-white transition-all active:scale-95 hover:brightness-90"
                            style="background:#006837;" title="Add to Cart">
                        <i class="ri-shopping-cart-2-line text-base"></i>
                    </button>
                </form>
            <?php else: ?>
                <a href="<?php echo e(route('mobile')); ?>"
                   class="w-8 h-8 rounded-full flex items-center justify-center text-white"
                   style="background:#006837;" title="Login to Add to Cart">
                    <i class="ri-shopping-cart-2-line text-base"></i>
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function wishlistCard(productId, initialLiked) {
    return {
        liked: initialLiked,
        loading: false,
        async toggleWishlist() {
            if (this.loading) return;
            this.loading = true;
            this.liked = !this.liked;
            try {
                const resp = await fetch(`/wishlist/${productId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                if (!resp.ok) throw new Error('Server error');
                const data = await resp.json();
                this.liked = data.liked;
            } catch (e) {
                this.liked = !this.liked;
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
<?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/components/product-card.blade.php ENDPATH**/ ?>