<?php $__env->startSection('title', 'Seller Policy — FIINWAY'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Seller Policy</h1>
        <p class="text-xs text-slate-400 mb-6">Last Updated: 18 August 2026 | FIINWAY 360 COMMUNICATION</p>
        <p class="text-sm text-slate-600 mb-6 leading-relaxed">This Seller Policy applies to all sellers, vendors, merchants, suppliers, brands, businesses, and other entities ("Seller", "you") who list, sell, supply, or fulfil products through the <strong>fiinway.in</strong> platform.</p>

        <?php $sections = [
            ['title' => '1. Seller Eligibility & KYC', 'body' => "To become a Seller, you must provide accurate legal/business information, including PAN, GSTIN (where applicable), bank details, and business registration. FIINWAY may conduct verification checks for business, payment, fraud-prevention, and legal purposes."],
            ['title' => '2. Product Listing Requirements', 'body' => "Sellers must provide accurate and complete product information (images, specifications, price, taxes, stock, SKU, etc.). Information must not be false, misleading, or deceptive."],
            ['title' => '3. Product Authenticity', 'body' => "Sellers must ensure products are genuine and legally saleable. Selling counterfeit, fake, stolen, or unauthorized replica products is strictly prohibited. FIINWAY may request invoices or authorization letters for verification."],
            ['title' => '4. Prohibited Products', 'body' => "Sellers must not list products prohibited by Indian laws, government regulations, payment gateways, or logistics partners. This includes restricted, illegal, counterfeit, or unsafe goods."],
            ['title' => '5. Pricing & Taxes', 'body' => "The Seller determines the selling price, which must not exceed the MRP. The Seller is responsible for ensuring correct taxes (GST) are included in the price where applicable."],
            ['title' => '6. Order Fulfilment', 'body' => "Sellers are responsible for processing, packing, and dispatching orders within the agreed timeframe. Products must be packed securely to prevent transit damage. Sellers must include the correct tax invoice with the shipment."],
            ['title' => '7. Returns & Replacements', 'body' => "Sellers must comply with the FIINWAY Return & Replacement Policy. If a customer raises a valid return request for a defective, damaged, or incorrect item, the Seller must accept the return or replacement as per platform rules."],
            ['title' => '8. Payments & Settlements', 'body' => "FIINWAY will collect payments from customers and remit the settled amount to the Seller's registered bank account after deducting applicable platform fees, commissions, shipping charges, and taxes, as agreed in the Seller Agreement."],
            ['title' => '9. Contact FIINWAY', 'body' => "FIINWAY 360 COMMUNICATION\nSeller Support: info@fiinway.in\nGrievance: grievance@fiinway.in\nPhone: +91 94296 93669\nAddress: 5, Balaganj, Near Siwi Factory, Mehta Vihar, Lucknow, Uttar Pradesh, India – 226003"],
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
            <p>© 2026 FIINWAY 360 COMMUNICATION. All Rights Reserved.</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/seller-policy.blade.php ENDPATH**/ ?>