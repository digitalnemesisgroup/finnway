<?php $__env->startSection('title', 'Terms & Conditions — FIINWAY'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Terms &amp; Conditions</h1>
        <p class="text-xs text-slate-400 mb-6">Last Updated: 18 August 2026</p>

        <p class="text-sm text-slate-600 mb-6">These Terms &amp; Conditions govern your use of <strong>fiinway.in</strong>, operated by <strong>FIINWAY 360 COMMUNICATION</strong>, 5, Balaganj, Near Siwi Factory, Mehta Vihar, Lucknow, Uttar Pradesh, India – 226003.</p>

        <?php
        $sections = [
            ['title' => '1. Acceptance of Terms', 'body' => 'By accessing or using fiinway.in, you agree to be bound by these Terms & Conditions and all applicable laws. If you do not agree, please do not use the website.'],
            ['title' => '2. Eligibility', 'body' => 'You must be at least 18 years of age (or the age of majority in your jurisdiction) to use this website and place orders.'],
            ['title' => '3. Account Registration', 'body' => 'You may be required to register an account to access certain features. You are responsible for maintaining the confidentiality of your account credentials and for all activities under your account. Please notify us immediately of any unauthorized use.'],
            ['title' => '4. Products & Listings', 'body' => 'FIINWAY 360 COMMUNICATION operates a marketplace where sellers list products. Product descriptions, images, and prices are provided by sellers. We strive for accuracy but do not guarantee that all information is complete or error-free.'],
            ['title' => '5. Pricing & Availability', 'body' => 'Prices are displayed in Indian Rupees (INR). We reserve the right to modify prices at any time. Product availability may change without notice.'],
            ['title' => '6. Orders', 'body' => 'Placing an order constitutes an offer to purchase. We reserve the right to accept or decline any order. Order confirmation will be sent after successful payment.'],
            ['title' => '7. Payment', 'body' => 'Payments may be made using available payment methods. We do not ask for UPI PIN, ATM PIN, CVV, OTP, or internet banking passwords. Never share such information with anyone claiming to represent FIINWAY.'],
            ['title' => '8. Payment Failure', 'body' => 'If a payment fails or is debited but the order is not confirmed, please contact us with transaction details. We will coordinate with the payment gateway for resolution.'],
            ['title' => '9. Shipping & Delivery', 'body' => 'Delivery timelines depend on product availability, location, courier partner, and other factors. Estimated dates are indicative. Please refer to our Shipping & Delivery Policy for full details.'],
            ['title' => '10. Return, Replacement & Refund', 'body' => 'Returns and replacements are subject to our Return & Replacement Policy. Eligibility depends on product category, condition, time period, and other applicable conditions.'],
            ['title' => '11. Order Cancellation by Us', 'body' => 'We may cancel orders (fully or partially) for reasons including product unavailability, pricing errors, suspected fraud, payment failure, delivery restrictions, or other legitimate business reasons. Refunds will be processed for cancelled paid orders.'],
            ['title' => '12. Intellectual Property', 'body' => 'All content on fiinway.in including the logo, brand name, text, graphics, images, and software is owned by or licensed to FIINWAY 360 COMMUNICATION. You may not reproduce or exploit such content without prior written permission.'],
            ['title' => '13. Customer Responsibilities', 'body' => 'You agree not to use the website for unlawful purposes, provide false information, create fraudulent accounts, misuse promotional offers, or attempt unauthorized access to our systems.'],
            ['title' => '14. Limitation of Liability', 'body' => 'To the maximum extent permitted by law, FIINWAY 360 COMMUNICATION shall not be liable for indirect, incidental, or consequential losses arising from use of the website.'],
            ['title' => '15. Governing Law', 'body' => 'These Terms are governed by the laws of India. Disputes are subject to the jurisdiction of competent courts in Lucknow, Uttar Pradesh, India.'],
            ['title' => '16. Grievance Redressal', 'body' => "Grievance Email: grievance@fiinway.in\nPhone: +91 94296 93669\nAddress: 5, Balaganj, Near Siwi Factory, Mehta Vihar, Lucknow, Uttar Pradesh, India – 226003"],
            ['title' => '17. Changes to These Terms', 'body' => 'We may update these Terms at any time. Continued use of the website after updates constitutes acceptance of the revised Terms.'],
        ];
        ?>

        <div class="space-y-6">
            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <h2 class="text-sm font-bold text-[#212121] mb-1"><?php echo e($s['title']); ?></h2>
                <p class="text-sm text-slate-600 whitespace-pre-line"><?php echo e($s['body']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-xs text-slate-400">
            <p><strong class="text-slate-600">FIINWAY 360 COMMUNICATION</strong></p>
            <p>5, Balaganj, Near Siwi Factory, Mehta Vihar, Lucknow, Uttar Pradesh, India – 226003</p>
            <p>Support: <a href="mailto:info@fiinway.in" class="text-[#e94f1c]">info@fiinway.in</a> | Grievance: <a href="mailto:grievance@fiinway.in" class="text-[#e94f1c]">grievance@fiinway.in</a> | Phone: +91 94296 93669</p>
            <p class="mt-2">© 2026 FIINWAY 360 COMMUNICATION. All Rights Reserved.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/terms.blade.php ENDPATH**/ ?>