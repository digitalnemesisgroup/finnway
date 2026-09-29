<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settlement Statement - <?php echo e($client->name); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; font-size: 11px; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 font-sans p-6 md:p-12">

    <div class="max-w-5xl mx-auto bg-white p-8 rounded-xl shadow-md border border-slate-200">

        <!-- Action bar for print / download -->
        <div class="no-print flex items-center justify-between border-b border-slate-200 pb-4 mb-6">
            <div class="flex items-center gap-2">
                <a href="<?php echo e(route('hub.portal.dashboard')); ?>" class="px-3 py-1.5 rounded-lg bg-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-300">
                    &larr; Back to Dashboard
                </a>
                <span class="text-xs text-slate-500">PDF Settlement Report Preview</span>
            </div>
            <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow">
                🖨️ Print / Save as PDF
            </button>
        </div>

        <!-- Header -->
        <div class="flex justify-between items-start border-b border-slate-300 pb-6 mb-6">
            <div>
                <img src="<?php echo e(asset('logo.png')); ?>" alt="FINNWAY 360" class="h-12 w-auto object-contain mb-2">
                <p class="text-xs font-bold uppercase text-blue-600 tracking-wider">Merchant Settlement Statement</p>
                <div class="mt-4 text-xs space-y-1 text-slate-600">
                    <p><strong class="text-slate-800">Business Name:</strong> <?php echo e($client->name); ?></p>
                    <p><strong class="text-slate-800">Legal Entity:</strong> <?php echo e($client->legal_name); ?></p>
                    <p><strong class="text-slate-800">PAN / GSTIN:</strong> <?php echo e($client->pan_number); ?> / <?php echo e($client->gstin ?: 'N/A'); ?></p>
                    <p><strong class="text-slate-800">Registered Email:</strong> <?php echo e($client->business_email); ?></p>
                </div>
            </div>
            <div class="text-right text-xs space-y-1">
                <p class="font-mono text-slate-400">Statement Date: <?php echo e(now()->format('d M Y, h:i A')); ?></p>
                <div class="mt-3 bg-slate-50 p-3 rounded-lg border border-slate-200 inline-block text-left">
                    <p class="text-[10px] uppercase font-bold text-slate-400">Filter Date Range</p>
                    <p class="font-bold text-slate-800 text-xs">
                        <?php echo e($fromDate ? $fromDate->format('d M Y') : 'Beginning'); ?> — <?php echo e($toDate ? $toDate->format('d M Y') : 'Present'); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Summary Totals Table -->
        <div class="mb-8">
            <h2 class="text-xs font-black uppercase text-slate-700 tracking-wider mb-3">Settlement Summary</h2>
            <div class="grid grid-cols-4 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200 text-center">
                <div>
                    <span class="block text-[10px] font-bold text-slate-500 uppercase">Gross Sales Volume</span>
                    <span class="text-base font-black text-slate-900">₹<?php echo e(number_format($grossSuccessVolume, 2)); ?></span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-500 uppercase">Gateway Charge (<?php echo e($gatewayChargePercent); ?>%)</span>
                    <span class="text-base font-black text-amber-600">- ₹<?php echo e(number_format($totalGatewayDeduction, 2)); ?></span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-500 uppercase">GST (<?php echo e($gstChargePercent); ?>%)</span>
                    <span class="text-base font-black text-indigo-600">- ₹<?php echo e(number_format($totalGstDeduction, 2)); ?></span>
                </div>
                <div class="bg-emerald-100/70 p-2 rounded-lg border border-emerald-300">
                    <span class="block text-[10px] font-black text-emerald-800 uppercase">Net Payout Received</span>
                    <span class="text-lg font-black text-emerald-700">₹<?php echo e(number_format($netSettledAmount, 2)); ?></span>
                </div>
            </div>
        </div>

        <!-- Itemized Transactions Table -->
        <div>
            <h2 class="text-xs font-black uppercase text-slate-700 tracking-wider mb-3">
                Itemized Payment Ledger (<?php echo e(count($transactions)); ?> Records)
            </h2>
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-200 border-b border-slate-300 font-bold uppercase text-[10px] text-slate-700">
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Client Order ID</th>
                        <th class="py-2.5 px-3">Customer</th>
                        <th class="py-2.5 px-3 text-center">Status</th>
                        <th class="py-2.5 px-3 text-right">Gross (₹)</th>
                        <th class="py-2.5 px-3 text-right">Charge (₹)</th>
                        <th class="py-2.5 px-3 text-right">GST (₹)</th>
                        <th class="py-2.5 px-3 text-right">Net Payout (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php $status = strtoupper($item->payment_status); ?>
                        <tr>
                            <td class="py-2 px-3 font-mono text-[10px] text-slate-600"><?php echo e($item->created_at->format('d/m/Y H:i')); ?></td>
                            <td class="py-2 px-3 font-mono font-bold text-slate-800"><?php echo e($item->client_order_id); ?></td>
                            <td class="py-2 px-3 text-slate-700"><?php echo e($item->customer_phone ?? 'N/A'); ?></td>
                            <td class="py-2 px-3 text-center font-bold text-[10px]">
                                <span class="<?php echo e($status === 'SUCCESS' ? 'text-emerald-700' : 'text-rose-600'); ?>"><?php echo e($status); ?></span>
                            </td>
                            <td class="py-2 px-3 text-right font-semibold">₹<?php echo e(number_format($item->amount, 2)); ?></td>
                            <td class="py-2 px-3 text-right text-slate-600 font-mono">
                                <?php echo e($status === 'SUCCESS' ? '- ₹' . number_format($item->fiinway_charge, 2) : '₹0.00'); ?>

                            </td>
                            <td class="py-2 px-3 text-right text-slate-600 font-mono">
                                <?php echo e($status === 'SUCCESS' ? '- ₹' . number_format($item->gst_charge, 2) : '₹0.00'); ?>

                            </td>
                            <td class="py-2 px-3 text-right font-black <?php echo e($status === 'SUCCESS' ? 'text-emerald-700' : 'text-slate-400'); ?>">
                                <?php echo e($status === 'SUCCESS' ? '+ ₹' . number_format($item->net_payout, 2) : '₹0.00'); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="py-6 text-center text-slate-400">No transaction records found for selected period.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-12 border-t border-slate-300 pt-4 flex justify-between items-center text-[10px] text-slate-500">
            <p>Generated by FINNWAY 360° Payment Hub System &bull; Confidential Merchant Statement</p>
            <p>Page 1 of 1</p>
        </div>

    </div>

</body>
</html>

<?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/hub/portal/export_pdf.blade.php ENDPATH**/ ?>