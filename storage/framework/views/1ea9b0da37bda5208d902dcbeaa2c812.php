<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo e(\App\Models\AppSetting::get('app_name', 'FIINWAY')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #f1f3f6; }
        .fk-btn-primary { background: #e94f1c; color: #fff; font-weight: 700; padding: 0.7rem 2rem; border-radius: 2px; transition: background 0.15s; box-shadow: 0 1px 2px rgba(0,0,0,.2); letter-spacing: .04em; text-transform: uppercase; }
        .fk-btn-primary:hover { background: #cc4214; }
        .fk-btn-outline { background: #fff; color: #e94f1c; border: 1px solid #e94f1c; font-weight: 700; padding: 0.7rem 2rem; border-radius: 2px; letter-spacing: .04em; text-transform: uppercase; }
        .fk-card { background: #fff; border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1); }
        .sidebar-link { display: flex; items-center: center; gap: 0.75rem; padding: 0.75rem 1.25rem; color: #fff; text-decoration: none; transition: all 0.2s; border-radius: 4px; margin-bottom: 0.25rem; font-size: 0.9rem; font-weight: 500; }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); }
        .sidebar-link.active { background: #e94f1c; color: #fff; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
        
        .table-wrap { overflow-x: auto; background: #fff; border-radius: 2px; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #f8fafc; padding: 0.875rem 1rem; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; text-align: left; border-bottom: 1px solid #e2e8f0; }
        tbody td { padding: 0.875rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; vertical-align: middle; }
        tbody tr:hover { background: #f8fafc; }
        
        .stat-card { background: #fff; border-radius: 2px; padding: 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 1rem; transition: all 0.3s; }
        .stat-icon { width: 3rem; height: 3rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        .stat-value { font-size: 1.75rem; font-weight: 800; line-height: 1; color: #212121; }
        .stat-label { font-size: 0.85rem; color: #878787; font-weight: 500; margin-top: 0.25rem; }
    </style>
</head>
<body class="antialiased flex min-h-screen bg-[#f1f3f6]">

    
    <?php echo $__env->make('admin.partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main class="flex-1 flex flex-col min-w-0">
        
        
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 shrink-0">
            <h2 class="text-xl font-bold text-[#212121]"><?php echo $__env->yieldContent('header_title', 'Overview'); ?></h2>
            <div class="flex items-center gap-4 text-sm font-medium text-[#878787]">
                <span><i class="ri-calendar-line"></i> <?php echo e(now()->format('d M Y')); ?></span>
                <div class="w-8 h-8 rounded-full bg-green-100 text-[#006837] flex items-center justify-center font-bold border border-green-200">
                    A
                </div>
            </div>
        </header>

        
        <div class="p-8 flex-1">
            
            <?php if(session('success')): ?>
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-6 flex items-center gap-3 p-4 rounded-sm bg-[#e8f5e9] border border-[#a5d6a7] text-[#2e7d32] shadow-sm">
                <i class="ri-checkbox-circle-fill text-xl"></i>
                <span class="flex-1 font-medium"><?php echo e(session('success')); ?></span>
                <button @click="show = false"><i class="ri-close-line text-xl opacity-70 hover:opacity-100"></i></button>
            </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
            <div x-data="{ show: true }" x-show="show" class="mb-6 flex items-start gap-3 p-4 rounded-sm bg-[#ffebee] border border-[#ef9a9a] text-[#c62828] shadow-sm">
                <i class="ri-error-warning-fill text-xl mt-0.5"></i>
                <div class="flex-1 font-medium space-y-1">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <button @click="show = false"><i class="ri-close-line text-xl opacity-70 hover:opacity-100"></i></button>
            </div>
            <?php endif; ?>

            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

</body>
</html>
<?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/layouts/admin.blade.php ENDPATH**/ ?>