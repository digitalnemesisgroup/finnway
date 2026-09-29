<?php $__env->startSection('title', 'Orders & Settlements'); ?>
<?php $__env->startSection('page_title', 'Orders & Settlement Ledger'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6" x-data="{ loading: false }">

    <!-- ── Search, Filters, Debouncing, Per-Page & Export Toolbar ─────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-4">
        <form id="filter-form" action="<?php echo e(route('hub.portal.orders')); ?>" method="GET" @submit="loading = true" class="space-y-4">

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-6 gap-3">

                <!-- Debounced Search Field -->
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 flex items-center justify-between">
                        <span>Search Order / Customer</span>
                        <span class="text-[9px] text-blue-500 font-normal">Debounced 400ms</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                            placeholder="Search client_order_id or phone..."
                            @input.debounce.400ms="$dispatch('submit')"
                            class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-9 pr-3 py-2 text-xs focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                        <i class="ri-search-line absolute left-3 top-2.5 text-slate-400 text-sm"></i>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Status</label>
                    <select name="status" @change="$dispatch('submit')" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs focus:bg-white focus:border-blue-500 transition">
                        <option value="ALL" <?php echo e(request('status') == 'ALL' ? 'selected' : ''); ?>>All Statuses</option>
                        <option value="SUCCESS" <?php echo e(request('status') == 'SUCCESS' ? 'selected' : ''); ?>>✅ SUCCESS</option>
                        <option value="PENDING" <?php echo e(request('status') == 'PENDING' ? 'selected' : ''); ?>>⏳ PENDING</option>
                        <option value="CANCELLED" <?php echo e(request('status') == 'CANCELLED' ? 'selected' : ''); ?>>❌ CANCELLED</option>
                        <option value="FAILED" <?php echo e(request('status') == 'FAILED' ? 'selected' : ''); ?>>⚠️ FAILED</option>
                    </select>
                </div>

                <!-- From Date -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">From Date</label>
                    <input type="date" name="from_date" value="<?php echo e(request('from_date')); ?>" @change="$dispatch('submit')"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs focus:bg-white focus:border-blue-500 transition">
                </div>

                <!-- To Date -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">To Date</label>
                    <input type="date" name="to_date" value="<?php echo e(request('to_date')); ?>" @change="$dispatch('submit')"
                        class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs focus:bg-white focus:border-blue-500 transition">
                </div>

                <!-- Per Page Rows selector -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Per Page</label>
                    <select name="per_page" @change="$dispatch('submit')" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-3 py-2 text-xs focus:bg-white focus:border-blue-500 transition font-mono">
                        <option value="10" <?php echo e($perPage == 10 ? 'selected' : ''); ?>>10 rows</option>
                        <option value="15" <?php echo e($perPage == 15 ? 'selected' : ''); ?>>15 rows</option>
                        <option value="25" <?php echo e($perPage == 25 ? 'selected' : ''); ?>>25 rows</option>
                        <option value="50" <?php echo e($perPage == 50 ? 'selected' : ''); ?>>50 rows</option>
                        <option value="100" <?php echo e($perPage == 100 ? 'selected' : ''); ?>>100 rows</option>
                    </select>
                </div>

            </div>

            <!-- Filter Actions & Export Toolbar -->
            <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <button type="submit" :disabled="loading" class="bg-slate-900 text-white font-bold py-2 px-4 rounded-xl text-xs hover:bg-slate-800 transition flex items-center justify-center gap-1.5 shadow-sm disabled:opacity-50">
                        <i :class="loading ? 'ri-loader-4-line animate-spin' : 'ri-filter-3-line'"></i>
                        <span x-text="loading ? 'Filtering...' : 'Apply Filters'"></span>
                    </button>

                    <?php if(request()->anyFilled(['search', 'status', 'from_date', 'to_date', 'per_page'])): ?>
                        <a href="<?php echo e(route('hub.portal.orders')); ?>" class="px-3 py-2 rounded-xl border border-slate-300 text-slate-600 text-xs hover:bg-slate-100 transition shrink-0" title="Reset Filters">
                            <i class="ri-refresh-line"></i> Reset
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Export Reports Action Group -->
                <?php
                    $exportParams = request()->only(['search', 'status', 'from_date', 'to_date']);
                ?>
                <div class="flex items-center gap-2 w-full sm:w-auto shrink-0 justify-end">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider hidden md:inline">Export:</span>

                    <!-- Export to Excel (CSV) -->
                    <a href="<?php echo e(route('hub.portal.export.excel', $exportParams)); ?>"
                       class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <i class="ri-file-excel-2-line text-sm"></i> Excel (.csv)
                    </a>

                    <!-- Export to PDF / Printable Report -->
                    <a href="<?php echo e(route('hub.portal.export.pdf', $exportParams)); ?>" target="_blank"
                       class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                        <i class="ri-file-pdf-line text-sm"></i> PDF Report
                    </a>
                </div>
            </div>

        </form>
    </div>

    <!-- ── Detailed Per-Order Transactions Ledger Table ────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800 text-base">Payment Ledger & Settlement Breakdown</h3>
                <p class="text-xs text-slate-500">Showing per-order deductions (Gateway <?php echo e($gatewayChargePercent); ?>% + GST <?php echo e($gstChargePercent); ?>%) and net payout</p>
            </div>
            <span class="text-xs font-semibold px-3 py-1 rounded-lg bg-slate-200 text-slate-700">
                Showing <?php echo e($transactions->firstItem() ?? 0); ?>-<?php echo e($transactions->lastItem() ?? 0); ?> of <?php echo e($transactions->total()); ?> records
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-600">
                        <th class="py-3.5 px-4">Date & Time</th>
                        <th class="py-3.5 px-4">Client Order ID</th>
                        <th class="py-3.5 px-4">Customer</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Gross Amount</th>
                        <th class="py-3.5 px-4 text-right">Gateway Charge (<?php echo e($gatewayChargePercent); ?>%)</th>
                        <th class="py-3.5 px-4 text-right">GST (<?php echo e($gstChargePercent); ?>%)</th>
                        <th class="py-3.5 px-4 text-right bg-emerald-50/80 text-emerald-900 font-black">Net Payout Received</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-xs">
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $status = strtoupper($item->payment_status);
                        ?>
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap text-slate-500 font-mono text-[11px]">
                                <?php echo e($item->created_at->format('d M Y, h:i A')); ?>

                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold font-mono text-slate-800 block text-xs"><?php echo e($item->client_order_id); ?></span>
                                <?php if($item->cashfree_order_id || $item->transaction_id): ?>
                                    <span class="text-[10px] text-slate-400 font-mono block">Ref: <?php echo e($item->transaction_id ?: $item->cashfree_order_id); ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="block font-medium text-slate-700"><?php echo e($item->customer_phone ?? 'N/A'); ?></span>
                                <span class="block text-[11px] text-slate-400"><?php echo e($item->customer_email ?: 'No email'); ?></span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <?php if($status === 'SUCCESS'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                                        <i class="ri-checkbox-circle-fill"></i> SUCCESS
                                    </span>
                                <?php elseif($status === 'PENDING'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 uppercase tracking-wide">
                                        <i class="ri-time-line"></i> PENDING
                                    </span>
                                <?php elseif(in_array($status, ['CANCELLED', 'FAILED', 'UNDONE'])): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 uppercase tracking-wide" title="Payment cancelled or undone by user">
                                        <i class="ri-close-circle-fill"></i> <?php echo e($status); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-slate-100 text-slate-700 uppercase">
                                        <?php echo e($status); ?>

                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-slate-800 whitespace-nowrap">
                                ₹<?php echo e(number_format($item->amount, 2)); ?>

                            </td>
                            <td class="py-3.5 px-4 text-right text-amber-700 whitespace-nowrap font-mono">
                                <?php if($status === 'SUCCESS'): ?>
                                    - ₹<?php echo e(number_format($item->fiinway_charge, 2)); ?>

                                <?php else: ?>
                                    <span class="text-slate-400">₹0.00</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-right text-indigo-700 whitespace-nowrap font-mono">
                                <?php if($status === 'SUCCESS'): ?>
                                    - ₹<?php echo e(number_format($item->gst_charge, 2)); ?>

                                <?php else: ?>
                                    <span class="text-slate-400">₹0.00</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap bg-emerald-50/50">
                                <?php if($status === 'SUCCESS'): ?>
                                    <span class="font-black text-emerald-700 text-sm font-mono">+ ₹<?php echo e(number_format($item->net_payout, 2)); ?></span>
                                <?php else: ?>
                                    <span class="text-slate-400 text-xs font-mono">₹0.00 <span class="text-[9px] block text-rose-500 font-semibold">(No Payout)</span></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400 bg-slate-50/50">
                                <i class="ri-inbox-archive-line text-4xl block text-slate-300 mb-2"></i>
                                <span class="font-bold text-slate-600 block">No payment records found</span>
                                <span class="text-xs">Adjust your search or filter parameters to view transactions</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <span class="text-xs text-slate-500">
                Page <?php echo e($transactions->currentPage()); ?> of <?php echo e($transactions->lastPage()); ?>

            </span>
            <div>
                <?php echo e($transactions->links()); ?>

            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('hub.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/hub/portal/orders.blade.php ENDPATH**/ ?>