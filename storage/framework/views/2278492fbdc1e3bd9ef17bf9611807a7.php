<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'icon' => 'ri-inbox-line',
    'title' => 'No items found',
    'message' => 'There is nothing to display here at the moment.',
    'actionUrl' => null,
    'actionLabel' => null
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
    'icon' => 'ri-inbox-line',
    'title' => 'No items found',
    'message' => 'There is nothing to display here at the moment.',
    'actionUrl' => null,
    'actionLabel' => null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="p-12 text-center bg-white rounded-3xl border border-slate-100 shadow-sm max-w-md mx-auto my-8">
    <div class="w-20 h-20 mx-auto rounded-3xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl mb-4 shadow-sm">
        <i class="<?php echo e($icon); ?>"></i>
    </div>
    <h3 class="text-xl font-bold text-slate-900 mb-2"><?php echo e($title); ?></h3>
    <p class="text-sm text-slate-500 mb-6 leading-relaxed"><?php echo e($message); ?></p>

    <?php if($actionUrl && $actionLabel): ?>
        <a href="<?php echo e($actionUrl); ?>" class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm transition-all shadow-lg shadow-indigo-600/20 active:scale-95">
            <?php echo e($actionLabel); ?> <i class="ri-arrow-right-line ml-2"></i>
        </a>
    <?php endif; ?>
</div>
<?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/components/empty-state.blade.php ENDPATH**/ ?>