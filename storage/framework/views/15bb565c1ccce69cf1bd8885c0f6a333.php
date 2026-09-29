<?php $__env->startSection('title', 'Payments — FIINWAY'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Payments at FIINWAY</h1>
        <p class="text-xs text-slate-400 mb-6">Safe, secure & multiple payment options</p>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
            <?php $__currentLoopData = ['UPI', 'Debit Card', 'Credit Card', 'Net Banking', 'EMI', 'Wallets', 'Cash on Delivery', 'Razorpay']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="border border-slate-100 rounded p-3 text-center">
                <i class="ri-bank-card-line text-2xl text-[#e94f1c] mb-1 block"></i>
                <p class="text-xs font-medium text-[#212121]"><?php echo e($method); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <?php $sections = [
            ['title' => 'Accepted Payment Methods', 'body' => "You can pay on FIINWAY using:\n• UPI (PhonePe, GPay, Paytm, BHIM, etc.)\n• Debit Cards (Visa, Mastercard, RuPay)\n• Credit Cards (Visa, Mastercard, Amex)\n• Net Banking\n• EMI (No-cost and standard EMI available)\n• Digital Wallets\n• Cash on Delivery (where available)"],
            ['title' => 'Payment Security', 'body' => 'All payments are processed through Razorpay, a PCI-DSS Level 1 certified payment gateway. Your card details are encrypted using SSL and are never stored on our servers.'],
            ['title' => 'Payment Failure', 'body' => "If your payment fails but money was deducted from your account:\n• Do not retry immediately\n• Contact us with your transaction/reference ID\n• Email: info@fiinway.in | Phone: +91 94296 93669\n\nWe will coordinate with the payment gateway and process a refund within the applicable timeline."],
            ['title' => 'EMI Options', 'body' => 'No-cost and standard EMI options are available on select products for credit card holders. EMI eligibility depends on your card issuer and the product. EMI details are shown on the product page and at checkout.'],
            ['title' => 'Refunds', 'body' => "Approved refunds are credited to the original payment method:\n• UPI / Net Banking / Wallets: 3–5 business days\n• Debit / Credit Cards: 5–7 business days\n\nTimelines may vary depending on your bank."],
            ['title' => 'Important — Never Share Credentials', 'body' => "FIINWAY will NEVER ask for your:\n• UPI PIN\n• ATM PIN\n• CVV\n• OTP\n• Internet banking password\n\nReport any such request immediately to grievance@fiinway.in"],
        ]; ?>

        <div class="space-y-6">
            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <h2 class="text-sm font-bold text-[#212121] mb-1"><?php echo e($s['title']); ?></h2>
                <p class="text-sm text-slate-600 whitespace-pre-line"><?php echo e($s['body']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-xs text-slate-400">
            <p>For payment support: <a href="mailto:info@fiinway.in" class="text-[#e94f1c]">info@fiinway.in</a> | +91 94296 93669</p>
            <p class="mt-1">© 2026 FIINWAY 360 COMMUNICATION. All Rights Reserved.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/payments.blade.php ENDPATH**/ ?>