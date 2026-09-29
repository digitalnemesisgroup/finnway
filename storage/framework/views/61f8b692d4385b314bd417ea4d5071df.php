<?php $__env->startSection('title', 'Platform Analytics'); ?>
<?php $__env->startSection('header_title', 'Payment Gateway Platform Analytics'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">

    <!-- ── Header ──────────────────────────────────────────────────────────────── -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-2xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border border-slate-700/60">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-blue-500/20 text-blue-300 border border-blue-500/30">
                    System Control Panel
                </span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Platform Financial Analytics</h1>
            <p class="text-xs md:text-sm text-slate-300 mt-1">Real-time revenue, client sales volume, and GST collection statistics.</p>
        </div>
        <a href="<?php echo e(route('admin.payment-analytics.report')); ?>" class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center gap-2 shadow-md">
            <i class="ri-file-chart-line"></i> Generate Custom Report
        </a>
    </div>

    <!-- ── Overview KPI Cards ─────────────────────────────────────────────────── -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Active Business Clients</span>
            <p class="text-3xl font-black text-slate-900 mt-2"><?php echo e($totalClients); ?></p>
            <p class="text-xs text-slate-400 mt-1">Approved & live API clients</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Sales Volume</span>
            <p class="text-3xl font-black text-blue-600 mt-2">₹<?php echo e(number_format($totalVolume, 2)); ?></p>
            <p class="text-xs text-slate-400 mt-1">Across <?php echo e(number_format($totalTransactions)); ?> successful payments</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Successful Transactions</span>
            <p class="text-3xl font-black text-emerald-600 mt-2"><?php echo e(number_format($totalTransactions)); ?></p>
            <p class="text-xs text-slate-400 mt-1">Completed orders</p>
        </div>

    </div>

    <!-- ── Per-Client Financial Performance Table ─────────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Revenue & Performance per Client</h3>
                <p class="text-xs text-slate-500">Calculated platform charges and GST collection per business</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-600">
                        <th class="py-3.5 px-4">Client Name</th>
                        <th class="py-3.5 px-4 text-center">Transactions</th>
                        <th class="py-3.5 px-4 text-right">Sales Volume</th>
                        <th class="py-3.5 px-4 text-right">Gateway Charge (₹)</th>
                        <th class="py-3.5 px-4 text-right">GST Collected (₹)</th>
                        <th class="py-3.5 px-4 text-right bg-blue-50/80 text-blue-900 font-black">Total Platform Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php $__empty_1 = true; $__currentLoopData = $clientStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3.5 px-4">
                            <span class="font-bold text-slate-800 block text-xs"><?php echo e($c['name']); ?></span>
                            <span class="text-[10px] text-slate-400 block"><?php echo e($c['legal_name']); ?></span>
                        </td>
                        <td class="py-3.5 px-4 text-center font-bold text-slate-700"><?php echo e(number_format($c['transaction_count'])); ?></td>
                        <td class="py-3.5 px-4 text-right font-bold text-slate-800">₹<?php echo e(number_format($c['sales_volume'], 2)); ?></td>
                        <td class="py-3.5 px-4 text-right text-amber-700 font-mono">₹<?php echo e(number_format($c['gateway_charge'], 2)); ?></td>
                        <td class="py-3.5 px-4 text-right text-indigo-700 font-mono">₹<?php echo e(number_format($c['gst_collected'], 2)); ?></td>
                        <td class="py-3.5 px-4 text-right font-black text-blue-700 bg-blue-50/50 font-mono">
                            ₹<?php echo e(number_format($c['total_revenue'], 2)); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">No active client data available.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hub.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/payment_clients/analytics.blade.php ENDPATH**/ ?>