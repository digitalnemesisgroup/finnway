<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Hub Report - FIINWAY</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    <style>
        body { background-color: #f1f5f9; color: #334155; font-family: 'Inter', sans-serif; }
        .print-container { max-w-5xl mx-auto p-8 bg-white my-8 shadow-sm print:shadow-none print:my-0 print:p-0 }
        
        @media print {
            body { background-color: white; }
            .print-container { max-width: 100%; box-shadow: none; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .page-break { page-break-after: always; }
            .page-break:last-child { page-break-after: auto; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            @page { margin: 15mm; }
        }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 6px 10px; text-align: left; font-size: 10px; }
        thead th { border-top: 1px solid #cbd5e1; border-bottom: 1px solid #cbd5e1; background-color: #f8fafc; font-weight: bold; color: #475569; text-transform: uppercase; }
        tfoot td { border-top: 1px solid #cbd5e1; border-bottom: 1px solid #cbd5e1; font-weight: bold; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="fixed top-4 right-4 no-print flex gap-2 items-start">
        
        <div class="relative" id="excelDropdownWrap">
            <button onclick="document.getElementById('excelMenu').classList.toggle('hidden')" class="px-4 py-2 rounded text-sm font-bold shadow flex items-center gap-1" style="background-color: #10b981; color: white;">
                Export Excel <span style="font-size:10px;">&#9660;</span>
            </button>
            <div id="excelMenu" class="hidden absolute right-0 mt-1 w-52 bg-white border border-slate-200 rounded shadow-lg z-50 text-sm overflow-hidden">
                <button onclick="exportMultiSheet()" class="w-full text-left px-4 py-2.5 hover:bg-slate-50 flex items-center gap-2 font-medium text-slate-700">
                    &#128196; Multiple Sheets (Per Company)
                </button>
                <button onclick="exportSingleSheet()" class="w-full text-left px-4 py-2.5 hover:bg-slate-50 flex items-center gap-2 font-medium text-slate-700 border-t border-slate-100">
                    &#128203; Single Sheet (With Gap)
                </button>
            </div>
        </div>
        <button onclick="window.print()" class="px-4 py-2 rounded text-sm font-bold shadow" style="background-color: #ff6161; color: white;">Print / PDF</button>
        <button onclick="window.close()" class="px-4 py-2 rounded text-sm font-bold shadow" style="background-color: #e2e8f0; color: #1e293b;">Close</button>
    </div>
    <script>
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const wrap = document.getElementById('excelDropdownWrap');
        if (wrap && !wrap.contains(e.target)) {
            document.getElementById('excelMenu').classList.add('hidden');
        }
    });
    </script>

    <?php if($reportType === 'summary'): ?>
    
    <div class="print-container">
        <div class="text-center mb-6 border-b pb-4">
            <img src="<?php echo e(asset('logo.png')); ?>" alt="FIINWAY Logo" class="h-10 mx-auto mb-2 object-contain" style="filter: brightness(0)">
            <h1 class="text-lg font-black text-slate-800 mb-1">FIINWAY 360 COMMUNICATION</h1>
            <h2 class="text-xs font-bold text-slate-600 uppercase tracking-widest">Payment Analytics Summary Report</h2>
            <p class="text-[10px] text-slate-500 mt-2">Period: <?php echo e($fromDate->format('d M Y')); ?> to <?php echo e($toDate->format('d M Y')); ?></p>
            <p class="text-[9px] text-slate-400 mt-1">Generated on: <?php echo e(now()->format('d M Y H:i:s')); ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Business Code (ID)</th>
                    <th>Company / Business Name</th>
                    <th class="text-right">Transactions</th>
                    <th class="text-right">Sales Volume (₹)</th>
                    <?php if($includeCharge): ?> <th class="text-right">Charge Collected (₹)</th> <?php endif; ?>
                    <?php if($includeGst): ?> <th class="text-right">GST Collected (₹)</th> <?php endif; ?>
                    <th class="text-right">Total Net (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $gtVolume = 0; $gtCharge = 0; $gtGst = 0; $gtTotal = 0; $gtTrans = 0;
                ?>
                <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        // Filter transactions for this client
                        $clientTxns = $transactions->where('payment_client_id', $client->id);
                        $vol = $clientTxns->sum('amount');
                        $count = $clientTxns->count();
                        
                        // Calculate metrics if they are successful payments
                        $charge = 0; $gst = 0;
                        if (in_array('success', $paymentStatus)) {
                            $successVol = $clientTxns->where('payment_status', 'success')->sum('amount');
                            $charge = ($successVol * $client->gateway_charge_percent) / 100;
                            $gst = ($charge * $client->gst_on_charge_percent) / 100;
                        }

                        $net = $vol - ($charge + $gst);

                        $gtVolume += $vol;
                        $gtCharge += $charge;
                        $gtGst += $gst;
                        $gtTotal += $net;
                        $gtTrans += $count;
                    ?>
                    <tr>
                        <td class="font-mono text-xs">FW-CLI-<?php echo e(str_pad($client->id, 4, '0', STR_PAD_LEFT)); ?></td>
                        <td class="font-bold"><?php echo e($client->name); ?> <br><span class="font-normal text-[10px] text-slate-500"><?php echo e($client->legal_name); ?></span></td>
                        <td class="text-right"><?php echo e(number_format($count)); ?></td>
                        <td class="text-right font-medium"><?php echo e(number_format($vol, 2)); ?></td>
                        <?php if($includeCharge): ?> <td class="text-right text-rose-600"><?php echo e(number_format($charge, 2)); ?></td> <?php endif; ?>
                        <?php if($includeGst): ?> <td class="text-right text-rose-600"><?php echo e(number_format($gst, 2)); ?></td> <?php endif; ?>
                        <td class="text-right font-bold text-green-700"><?php echo e(number_format($net, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr class="bg-slate-50 font-black">
                    <td colspan="2" class="text-right uppercase">Grand Total:</td>
                    <td class="text-right"><?php echo e(number_format($gtTrans)); ?></td>
                    <td class="text-right"><?php echo e(number_format($gtVolume, 2)); ?></td>
                    <?php if($includeCharge): ?> <td class="text-right text-rose-600"><?php echo e(number_format($gtCharge, 2)); ?></td> <?php endif; ?>
                    <?php if($includeGst): ?> <td class="text-right text-rose-600"><?php echo e(number_format($gtGst, 2)); ?></td> <?php endif; ?>
                    <td class="text-right text-green-700"><?php echo e(number_format($gtTotal, 2)); ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php endif; ?>

    <?php if($reportType === 'detailed'): ?>
    
        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $clientTxns = $transactions->where('payment_client_id', $client->id);
                $pageNo = $index + 1;
                $totalPages = $clients->count();
                $tVol = 0; $tCharge = 0; $tGst = 0; $tNet = 0;
            ?>
            <div class="print-container page-break">
                <div class="flex justify-between items-start mb-4 border-b pb-4">
                    <div class="flex items-start gap-4">
                        <img src="<?php echo e(asset('logo.png')); ?>" alt="FIINWAY Logo" class="h-10 object-contain" style="filter: brightness(0)">
                        <div>
                            <h1 class="text-base font-black text-slate-800 mb-1">FIINWAY 360 COMMUNICATION</h1>
                            <h2 class="text-xs font-bold text-slate-600 uppercase tracking-widest">Detailed Payment Breakup</h2>
                            <p class="text-[10px] text-slate-500 mt-1">Period: <?php echo e($fromDate->format('d M Y')); ?> to <?php echo e($toDate->format('d M Y')); ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-800"><?php echo e($client->name); ?></p>
                        <p class="text-[10px] text-slate-500">ID: FW-CLI-<?php echo e(str_pad($client->id, 4, '0', STR_PAD_LEFT)); ?></p>
                        <p class="text-[9px] text-slate-400 mt-1">Page <?php echo e($pageNo); ?> of <?php echo e($totalPages); ?></p>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Order ID</th>
                            <th>Client Order ID</th>
                            <?php if(count($paymentStatus) > 1): ?> <th>Status</th> <?php endif; ?>
                            <th class="text-right">Amount (₹)</th>
                            <?php if($includeCharge): ?> <th class="text-right">Charge (₹)</th> <?php endif; ?>
                            <?php if($includeGst): ?> <th class="text-right">GST (₹)</th> <?php endif; ?>
                            <th class="text-right">Net (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $clientTxns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $amt = $txn->amount;
                                $chg = 0; $gst = 0;
                                if ($txn->payment_status === 'success') {
                                    $chg = ($amt * $client->gateway_charge_percent) / 100;
                                    $gst = ($chg * $client->gst_on_charge_percent) / 100;
                                }
                                $net = $amt - ($chg + $gst);
                                $tVol += $amt; $tCharge += $chg; $tGst += $gst; $tNet += $net;
                            ?>
                            <tr>
                                <td class="text-[10px]"><?php echo e($txn->created_at->format('d M Y H:i')); ?></td>
                                <td class="font-mono text-[10px] text-slate-500"><?php echo e($txn->cashfree_order_id ?? 'N/A'); ?></td>
                                <td class="font-mono text-[10px]"><?php echo e($txn->client_order_id); ?></td>
                                <?php if(count($paymentStatus) > 1): ?>
                                    <td>
                                        <span class="px-1.5 py-0.5 rounded text-[9px] uppercase font-bold
                                            <?php echo e($txn->payment_status === 'success' ? 'bg-green-100 text-green-700' :
                                              ($txn->payment_status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-slate-100 text-slate-700')); ?>">
                                            <?php echo e($txn->payment_status); ?>

                                        </span>
                                    </td>
                                <?php endif; ?>
                                <td class="text-right"><?php echo e(number_format($amt, 2)); ?></td>
                                <?php if($includeCharge): ?> <td class="text-right text-slate-500"><?php echo e(number_format($chg, 2)); ?></td> <?php endif; ?>
                                <?php if($includeGst): ?> <td class="text-right text-slate-500"><?php echo e(number_format($gst, 2)); ?></td> <?php endif; ?>
                                <td class="text-right font-medium"><?php echo e(number_format($net, 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr style="background:#f8fafc; font-weight:bold;">
                            <td colspan="<?php echo e(count($paymentStatus) > 1 ? '4' : '3'); ?>" class="text-right uppercase">Company Total:</td>
                            <td class="text-right"><?php echo e(number_format($tVol, 2)); ?></td>
                            <?php if($includeCharge): ?> <td class="text-right" style="color:#e11d48;"><?php echo e(number_format($tCharge, 2)); ?></td> <?php endif; ?>
                            <?php if($includeGst): ?> <td class="text-right" style="color:#e11d48;"><?php echo e(number_format($tGst, 2)); ?></td> <?php endif; ?>
                            <td class="text-right" style="color:#15803d;"><?php echo e(number_format($tNet, 2)); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

</body>
<script>
// ── Helper: extract rows from a table element → array of arrays ──────────────
function tableToAoA(tableEl) {
    const rows = [];
    tableEl.querySelectorAll('tr').forEach(tr => {
        const row = [];
        tr.querySelectorAll('th, td').forEach(cell => {
            const val = cell.innerText.trim();
            // Try to parse numbers (remove commas)
            const num = parseFloat(val.replace(/,/g, ''));
            row.push(isNaN(num) || val === '' ? val : num);
        });
        rows.push(row);
    });
    return rows;
}

// ── Option 1: Multiple sheets — one sheet per company ─────────────────────────
function exportMultiSheet() {
    document.getElementById('excelMenu').classList.add('hidden');
    const wb = XLSX.utils.book_new();
    const containers = document.querySelectorAll('.print-container');

    containers.forEach((container, idx) => {
        // Get company name from the header for sheet name
        const nameEl = container.querySelector('p.font-bold, h2');
        let sheetName = 'Company_' + (idx + 1);
        if (nameEl) sheetName = nameEl.innerText.trim().substring(0, 30).replace(/[:\\\/\?\*\[\]]/g, '_');

        const tableEl = container.querySelector('table');
        if (!tableEl) return;

        // Add report header rows
        const header = [
            ['FIINWAY 360 COMMUNICATION'],
            ['Detailed Payment Report'],
            [], // blank row
        ];

        const tableData = tableToAoA(tableEl);
        const ws = XLSX.utils.aoa_to_sheet([...header, ...tableData]);

        // Column widths
        ws['!cols'] = [18, 22, 22, 10, 12, 12, 12].map(w => ({ wch: w }));

        XLSX.utils.book_append_sheet(wb, ws, sheetName);
    });

    XLSX.writeFile(wb, 'FIINWAY_Payment_Report_MultiSheet.xlsx');
}

// ── Option 2: Single sheet — all companies with one blank row gap ──────────────
function exportSingleSheet() {
    document.getElementById('excelMenu').classList.add('hidden');
    const wb = XLSX.utils.book_new();
    const allRows = [
        ['FIINWAY 360 COMMUNICATION'],
        ['Payment Report — All Companies'],
        [],
    ];

    const containers = document.querySelectorAll('.print-container');
    containers.forEach((container, idx) => {
        // Company name separator
        const nameEl = container.querySelector('p.font-bold, h2');
        const companyLabel = nameEl ? nameEl.innerText.trim() : ('Company ' + (idx + 1));
        allRows.push(['Company: ' + companyLabel]);

        const tableEl = container.querySelector('table');
        if (tableEl) {
            const tableData = tableToAoA(tableEl);
            tableData.forEach(row => allRows.push(row));
        }

        // One blank row gap between companies
        allRows.push([]);
    });

    const ws = XLSX.utils.aoa_to_sheet(allRows);
    ws['!cols'] = [18, 22, 22, 10, 12, 12, 12].map(w => ({ wch: w }));

    XLSX.utils.book_append_sheet(wb, ws, 'All Companies');
    XLSX.writeFile(wb, 'FIINWAY_Payment_Report_SingleSheet.xlsx');
}
</script>
</html>
<?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/payment_clients/report_output.blade.php ENDPATH**/ ?>