<?php $__env->startSection('title', 'Privacy Policy — FIINWAY'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Privacy Policy</h1>
        <p class="text-xs text-slate-400 mb-6">Last Updated: 18 August 2026</p>

        <p class="text-sm text-slate-600 mb-6">FIINWAY 360 COMMUNICATION ("we", "us", "our") respects your privacy. This Privacy Policy explains how we collect, use, store, and protect your personal information when you use <strong>fiinway.in</strong>.</p>

        <?php
        $sections = [
            ['title' => 'Information We Collect', 'body' => "We may collect:\n• Name, mobile number, email address\n• Delivery address, city, state, PIN code\n• Order history and transaction data\n• Device and browser information\n• Usage data and cookies"],
            ['title' => 'How We Use Your Information', 'body' => "We use your information to:\n• Process and fulfil orders\n• Send order confirmations and updates\n• Provide customer support\n• Improve our platform and services\n• Comply with legal obligations"],
            ['title' => 'Data Sharing', 'body' => "We may share your information with:\n• Sellers (for order fulfilment)\n• Logistics/courier partners (for delivery)\n• Payment gateways (for payment processing)\n• Legal authorities (when required by law)\n\nWe do not sell your personal data to third parties."],
            ['title' => 'Data Security', 'body' => 'We implement industry-standard security measures to protect your personal data. However, no method of transmission over the internet is 100% secure.'],
            ['title' => 'Cookies', 'body' => 'We use cookies and similar technologies to improve your browsing experience, analyse site traffic, and personalise content. You can disable cookies in your browser settings, though some features may not function properly.'],
            ['title' => 'Your Rights', 'body' => "You have the right to:\n• Access your personal information\n• Request correction of inaccurate data\n• Request deletion of your account data\n• Opt out of marketing communications\n\nContact us at privacy@fiinway.in to exercise your rights."],
            ['title' => 'Data Retention', 'body' => 'We retain your personal data for as long as necessary to fulfil the purposes outlined in this policy, unless a longer retention period is required or permitted by law.'],
            ['title' => 'Contact — Privacy Concerns', 'body' => "Privacy: privacy@fiinway.in\nGrievance: grievance@fiinway.in\nPhone: +91 94296 93669\nAddress: 5, Balaganj, Near Siwi Factory, Mehta Vihar, Lucknow, Uttar Pradesh, India – 226003"],
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
            <p>© 2026 FIINWAY 360 COMMUNICATION. All Rights Reserved.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/privacy.blade.php ENDPATH**/ ?>