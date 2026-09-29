<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page_title', 'Dashboard Overview'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8">

    <!-- ── Header Banner ───────────────────────────────────────────────────────── -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-2xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border border-slate-700/60">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold tracking-wider uppercase bg-blue-500/20 text-blue-300 border border-blue-500/30">
                    Live Client Portal
                </span>
                <span class="text-xs text-slate-400">Merchant ID: #<?php echo e($client->id); ?></span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight"><?php echo e($client->name); ?></h1>
            <p class="text-xs md:text-sm text-slate-300 mt-1 max-w-2xl">
                <?php echo e($client->legal_name); ?> &bull; Category: <span class="font-medium text-slate-200"><?php echo e($client->category); ?></span>
            </p>
        </div>

        <!-- Commission Rates Pill -->
        <div class="bg-slate-800/80 border border-slate-700 rounded-xl px-5 py-3.5 flex items-center gap-6 shrink-0 shadow-inner">
            <div class="text-center">
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Gateway Charge</span>
                <span class="text-lg font-black text-blue-400"><?php echo e(number_format($gatewayChargePercent, 2)); ?>%</span>
            </div>
            <div class="w-px h-8 bg-slate-700"></div>
            <div class="text-center">
                <span class="block text-[10px] font-bold text-slate-400 uppercase">GST on Charge</span>
                <span class="text-lg font-black text-indigo-400"><?php echo e(number_format($gstChargePercent, 2)); ?>%</span>
            </div>
            <div class="w-px h-8 bg-slate-700"></div>
            <div class="text-center">
                <span class="block text-[10px] font-bold text-slate-400 uppercase">Account Status</span>
                <span class="inline-flex items-center gap-1 text-xs font-bold <?php echo e($client->isLive() ? 'text-emerald-400' : 'text-amber-400'); ?>">
                    <i class="ri-checkbox-circle-fill"></i> <?php echo e(strtoupper($client->approval_status)); ?>

                </span>
            </div>
        </div>
    </div>

    <!-- ── KPI Financial Overview Cards ────────────────────────────────────────── -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        <!-- 1. Total Successful Settlements -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Net Amount Received</span>
                <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-lg">₹</span>
            </div>
            <p class="text-2xl font-black text-emerald-600 mt-3">₹<?php echo e(number_format($netSettledAmount, 2)); ?></p>
            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                <i class="ri-check-double-line text-emerald-500"></i> From <?php echo e($successCount); ?> successful payments
            </p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-emerald-500"></div>
        </div>

        <!-- 2. Gross Successful Sales Volume -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Gross Sales Volume</span>
                <span class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg"><i class="ri-money-rupee-circle-line"></i></span>
            </div>
            <p class="text-2xl font-black text-slate-800 mt-3">₹<?php echo e(number_format($grossSuccessVolume, 2)); ?></p>
            <p class="text-xs text-slate-500 mt-1">Total customer transactions paid</p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-blue-500"></div>
        </div>

        <!-- 3. Total FIINWAY & Gateway Deductions -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Deductions</span>
                <span class="w-9 h-9 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-lg"><i class="ri-percent-line"></i></span>
            </div>
            <p class="text-2xl font-black text-amber-600 mt-3">₹<?php echo e(number_format($totalDeductions, 2)); ?></p>
            <p class="text-xs text-slate-500 mt-1">
                Charges (₹<?php echo e(number_format($totalGatewayDeduction, 2)); ?>) + GST (₹<?php echo e(number_format($totalGstDeduction, 2)); ?>)
            </p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-amber-500"></div>
        </div>

        <!-- 4. Cancelled / Undone Payments -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition relative overflow-hidden">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Cancelled / Failed</span>
                <span class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-lg"><i class="ri-close-circle-line"></i></span>
            </div>
            <p class="text-2xl font-black text-rose-600 mt-3"><?php echo e($cancelledCount); ?> <span class="text-xs font-semibold text-slate-400">orders</span></p>
            <p class="text-xs text-slate-500 mt-1">
                Volume undone: <span class="font-semibold text-slate-700">₹<?php echo e(number_format($cancelledVolume, 2)); ?></span>
            </p>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-rose-500"></div>
        </div>

    </div>

    <!-- ── Financial Settlement Formula Explainer Box ──────────────────────────── -->
    <div class="bg-blue-50/80 border border-blue-200 rounded-2xl p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center text-xl shrink-0 mt-0.5 shadow-md shadow-blue-500/20">
                <i class="ri-calculator-line"></i>
            </div>
            <div>
                <h4 class="font-bold text-slate-800 text-sm">Settlement Formula & Deductions Rules</h4>
                <p class="text-xs text-slate-600 mt-0.5">
                    For every successful payment, <span class="font-semibold text-slate-800">FINNWAY 360 Charge (<?php echo e($gatewayChargePercent); ?>%)</span> + <span class="font-semibold text-slate-800">GST on Charge (<?php echo e($gstChargePercent); ?>%)</span> are deducted. The remaining net amount is transferred directly back to you.
                </p>
            </div>
        </div>
        <div class="bg-white border border-blue-200 px-4 py-2 rounded-xl text-xs font-mono text-slate-700 shrink-0 shadow-sm">
            <span class="text-blue-600 font-bold">Net Payout</span> = Order Amount - (Gateway Charge + GST)
        </div>
    </div>

    <!-- ── Recent Orders Quick Ledger ─────────────────────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Recent Transactions</h3>
                <p class="text-xs text-slate-500">Latest 5 payment requests</p>
            </div>
            <a href="<?php echo e(route('hub.portal.orders')); ?>" class="text-xs font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1">
                View All Orders &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-600">
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Client Order ID</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Gross Amount</th>
                        <th class="py-3 px-4 text-right bg-emerald-50/80 text-emerald-900 font-black">Net Payout</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $status = strtoupper($item->payment_status); ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3 px-4 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                <?php echo e($item->created_at->format('d M Y, h:i A')); ?>

                            </td>
                            <td class="py-3 px-4 font-mono font-bold text-slate-800">
                                <?php echo e($item->client_order_id); ?>

                            </td>
                            <td class="py-3 px-4 text-slate-700">
                                <?php echo e($item->customer_phone ?? 'N/A'); ?>

                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold <?php echo e($status === 'SUCCESS' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'); ?>">
                                    <?php echo e($status); ?>

                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-slate-800">₹<?php echo e(number_format($item->amount, 2)); ?></td>
                            <td class="py-3 px-4 text-right font-black bg-emerald-50/50 <?php echo e($status === 'SUCCESS' ? 'text-emerald-700' : 'text-slate-400'); ?>">
                                <?php echo e($status === 'SUCCESS' ? '+ ₹' . number_format($item->net_payout, 2) : '₹0.00'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">No transactions recorded yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hub.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/hub/portal/dashboard.blade.php ENDPATH**/ ?>