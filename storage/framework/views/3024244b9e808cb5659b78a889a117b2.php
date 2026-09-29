<?php $__env->startSection('title', 'Disclaimer — FIINWAY'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Disclaimer</h1>
        <p class="text-xs text-slate-400 mb-6">Last Updated: 18 August 2026 | FIINWAY 360 COMMUNICATION</p>
        <p class="text-sm text-slate-600 mb-6 leading-relaxed">This Disclaimer applies to <strong>fiinway.in</strong>, operated by FIINWAY 360 COMMUNICATION, 5, Balaganj, Near Siwi Factory, Mehta Vihar, Lucknow, Uttar Pradesh, India – 226003.</p>

        <?php $sections = [
            ['title' => '1. General Information', 'body' => "Information on fiinway.in is intended for general informational and e-commerce purposes. We make reasonable efforts to ensure accuracy but do not guarantee that all content will always be complete, accurate, current, error-free, or uninterrupted."],
            ['title' => '2. Product Information', 'body' => "We make reasonable efforts to display products accurately. However, actual products may differ slightly from website images due to screen/device settings, photography, lighting, manufacturing changes, or product variants. Such minor differences do not constitute a product defect."],
            ['title' => '3. Pricing Information', 'body' => "Technical errors, typographical errors, or system errors may occasionally result in incorrect pricing. We reserve the right to correct the information, contact the customer, or cancel the affected order and process an applicable refund."],
            ['title' => '4. Third-Party Sellers', 'body' => "Where products are listed by third-party sellers, the Seller may be responsible for product authenticity, quality, description, inventory, packaging, fulfilment, and warranty. Seller responsibilities are governed by the applicable Seller Policy."],
            ['title' => '5. Payment Disclaimer', 'body' => "If a payment is deducted but an order is not confirmed, contact us with the transaction details rather than making repeated payments. Refunds are subject to our Payment Policy and Refund & Cancellation Policy.\n\nFIINWAY will NEVER ask for your UPI PIN, ATM PIN, CVV, OTP, or internet banking passwords."],
            ['title' => '6. Shipping & Delivery', 'body' => "Delivery dates are estimates unless expressly stated otherwise. Timelines may be affected by courier delays, weather, public holidays, natural disasters, government restrictions, incorrect customer information, or other circumstances beyond our reasonable control."],
            ['title' => '7. Website Availability', 'body' => "We do not guarantee that the website will always operate without interruption. The website may be temporarily unavailable due to maintenance, updates, technical issues, server problems, or other circumstances beyond our reasonable control."],
            ['title' => '8. Limitation of Liability', 'body' => "To the maximum extent permitted by applicable law, FIINWAY 360 COMMUNICATION shall not be responsible for indirect, incidental, special, or consequential losses arising from use of the website, delivery delays, or technical issues. Nothing in this Disclaimer limits any liability that cannot legally be excluded."],
            ['title' => '9. Governing Law', 'body' => "This Disclaimer is governed by the laws of India. Disputes are subject to the jurisdiction of courts in Lucknow, Uttar Pradesh."],
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
            <p>Contact: <a href="mailto:info@fiinway.in" class="text-[#e94f1c]">info@fiinway.in</a> | Grievance: <a href="mailto:grievance@fiinway.in" class="text-[#e94f1c]">grievance@fiinway.in</a> | +91 94296 93669</p>
            <p class="mt-1">© 2026 FIINWAY 360 COMMUNICATION. All Rights Reserved.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/disclaimer.blade.php ENDPATH**/ ?>