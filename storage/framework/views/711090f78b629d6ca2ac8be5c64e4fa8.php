<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'product' => null,
    'path' => null,
    'alt' => null,
    'aspect' => 'square', // square, video, 4/3
    'class' => '',
    'lazy' => true
]));

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

foreach (array_filter(([
    'product' => null,
    'path' => null,
    'alt' => null,
    'aspect' => 'square', // square, video, 4/3
    'class' => '',
    'lazy' => true
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $imagePath = null;
    if ($path) {
        $imagePath = $path;
    } elseif ($product) {
        $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
        $imagePath = $primary ? $primary->image_path : null;
    }

    $url = null;
    if ($imagePath) {
        // If it's already a full URL (CDN), use directly; otherwise resolve from storage
        $url = (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://'))
            ? $imagePath
            : asset('storage/' . ltrim($imagePath, '/'));
    } else {
        $url = asset('storage/products/default.webp');
    }
    $altText = $alt ?? ($product ? $product->name : 'Product Image');

    $aspectClass = match($aspect) {
        'video' => 'aspect-video',
        '4/3'   => 'aspect-[4/3]',
        default => 'aspect-square',
    };
?>

<div class="relative overflow-hidden bg-slate-100 dark:bg-slate-800 rounded-xl <?php echo e($aspectClass); ?> <?php echo e($class); ?>">
    <img 
        src="<?php echo e($url); ?>" 
        alt="<?php echo e($altText); ?>" 
        <?php if($lazy): ?> loading="lazy" <?php endif; ?>
        class="w-full h-full object-cover object-center transition-transform duration-500 hover:scale-105"
        onerror="this.onerror=null; this.src='<?php echo e(asset('storage/products/default.webp')); ?>';"
    />
</div>
<?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/components/product-image.blade.php ENDPATH**/ ?>