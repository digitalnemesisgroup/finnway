<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Session Error — FIINWAY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-800 rounded-3xl p-8 border border-slate-700 shadow-2xl text-center space-y-6">
        <div class="w-20 h-20 bg-rose-500/10 text-rose-500 rounded-full flex items-center justify-center mx-auto text-4xl border border-rose-500/20">
            <i class="ri-error-warning-line"></i>
        </div>
        
        <div>
            <h1 class="text-2xl font-black text-white tracking-tight">Payment Session Invalid</h1>
            <p class="text-sm font-medium text-slate-400 mt-2">{{ $message ?? 'This payment session link has expired or has exceeded the maximum allowed access attempts.' }}</p>
        </div>

        <div class="bg-slate-900/60 rounded-2xl p-4 border border-slate-700 text-xs font-mono text-slate-400 space-y-1 text-left">
            <div class="flex justify-between">
                <span class="text-slate-500">Error Code:</span>
                <span class="text-rose-400 uppercase font-bold">{{ $reason ?? 'SESSION_INVALID' }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Status:</span>
                <span class="text-rose-400 font-bold">REJECTED / TERMINATED</span>
            </div>
        </div>

        <div class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-xs text-amber-300 font-medium flex items-start gap-3 text-left">
            <i class="ri-information-line text-lg shrink-0 mt-0.5 text-amber-400"></i>
            <span>For security reasons, payment URLs expire after a set duration (TTL) and have a strict open limit. Please return to the merchant site and initiate a new payment link.</span>
        </div>
    </div>
</body>
</html>

