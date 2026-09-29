<?php $__env->startSection('title', 'Apply for Payment Gateway'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
            <h1 class="text-xl font-bold text-slate-800">FIINWAY Payment Gateway for Business</h1>
            <p class="text-sm text-slate-500 mt-1">Accept payments seamlessly on your website or app with industry-best success rates.</p>
        </div>

        <div class="p-6">
            <?php if(session('success')): ?>
                <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-lg mb-6 flex items-start gap-3">
                    <i class="ri-checkbox-circle-fill text-xl mt-0.5"></i>
                    <div>
                        <h3 class="font-bold">Success!</h3>
                        <p class="text-sm mt-1"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-lg mb-6">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if($application): ?>
                <?php if($application->approval_status === 'approved'): ?>
                    <div class="bg-emerald-50 border border-emerald-200 p-6 rounded-lg">
                        <div class="flex items-center gap-3 text-emerald-800 mb-4">
                            <i class="ri-verified-badge-fill text-3xl"></i>
                            <h2 class="text-2xl font-bold">Application Approved</h2>
                        </div>
                        <p class="text-emerald-700 mb-6">Your payment gateway account is active. You can now integrate using the credentials below.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-white p-4 rounded border border-emerald-100 shadow-sm">
                                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">API Key</span>
                                <code class="text-sm font-mono text-slate-800 bg-slate-100 px-2 py-1 rounded break-all"><?php echo e($application->api_key); ?></code>
                            </div>
                            <div class="bg-white p-4 rounded border border-emerald-100 shadow-sm">
                                <span class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">API Salt (Secret)</span>
                                <code class="text-sm font-mono text-slate-800 bg-slate-100 px-2 py-1 rounded break-all"><?php echo e($application->api_salt); ?></code>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded border border-emerald-100 shadow-sm mb-6">
                            <h3 class="font-bold text-slate-800 mb-2">Pricing Details</h3>
                            <ul class="text-sm text-slate-600 space-y-1 list-disc list-inside">
                                <li>Gateway Charge: <strong><?php echo e($application->gateway_charge_percent); ?>%</strong> per transaction</li>
                                <li>GST on Charges: <strong><?php echo e($application->gst_on_charge_percent); ?>%</strong></li>
                            </ul>
                        </div>

                        <a href="<?php echo e(url('/payment_hub_architecture.html')); ?>" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition">
                            <i class="ri-book-read-fill"></i> Read Integration Docs
                        </a>
                    </div>
                <?php elseif($application->approval_status === 'pending'): ?>
                    <div class="bg-amber-50 border border-amber-200 p-8 rounded-lg text-center">
                        <i class="ri-time-line text-5xl text-amber-500 mb-4 inline-block"></i>
                        <h2 class="text-2xl font-bold text-amber-800 mb-2">Application Under Review</h2>
                        <p class="text-amber-700 max-w-lg mx-auto">
                            Thank you for applying. Our compliance team is currently reviewing your business details. This usually takes 1-2 business days. We will notify you once approved.
                        </p>
                    </div>
                <?php else: ?>
                    <div class="bg-red-50 border border-red-200 p-8 rounded-lg text-center">
                        <i class="ri-close-circle-fill text-5xl text-red-500 mb-4 inline-block"></i>
                        <h2 class="text-2xl font-bold text-red-800 mb-2">Application Rejected</h2>
                        <p class="text-red-700 max-w-lg mx-auto">
                            Unfortunately, your application for the payment gateway could not be approved at this time. Please contact support for more details.
                        </p>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <form action="<?php echo e(route('business.payment-gateway.store')); ?>" method="POST" class="space-y-8">
                    <?php echo csrf_field(); ?>
                    
                    
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">1. Basic Business Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Brand/Display Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-xs text-slate-500 mt-1">This will be shown to customers on checkout.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Legal Business Name <span class="text-red-500">*</span></label>
                                <input type="text" name="legal_name" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="text-xs text-slate-500 mt-1">As per your PAN/GST registration.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Business Type <span class="text-red-500">*</span></label>
                                <select name="business_type" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Type</option>
                                    <option value="Individual/Sole Proprietorship">Individual / Sole Proprietorship</option>
                                    <option value="Partnership">Partnership</option>
                                    <option value="LLP">LLP</option>
                                    <option value="Private Limited">Private Limited</option>
                                    <option value="Public Limited">Public Limited</option>
                                    <option value="Trust/NGO">Trust / Society / NGO</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Category/Industry <span class="text-red-500">*</span></label>
                                <input type="text" name="category" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. EdTech, E-commerce, SaaS">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Business Description <span class="text-red-500">*</span></label>
                                <textarea name="description" rows="2" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Briefly describe what your business does"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Website/App URL <span class="text-red-500">*</span></label>
                                <input type="url" name="website_url" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="https://example.com">
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">2. Tax & Legal Documents</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Business PAN <span class="text-red-500">*</span></label>
                                <input type="text" name="pan_number" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase" placeholder="ABCDE1234F">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">GSTIN (If applicable)</label>
                                <input type="text" name="gstin" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Business Registration Number (CIN/LLPIN)</label>
                                <input type="text" name="registration_number" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase">
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">3. Contact & Address Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Business Email <span class="text-red-500">*</span></label>
                                <input type="email" name="business_email" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Business Mobile Number <span class="text-red-500">*</span></label>
                                <input type="text" name="business_mobile" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Registered Office Address <span class="text-red-500">*</span></label>
                                <textarea name="registered_address" rows="2" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Operating Address (If different)</label>
                                <textarea name="operating_address" rows="2" class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-4">4. Usage & Volume</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Expected Monthly Volume <span class="text-red-500">*</span></label>
                                <select name="expected_monthly_volume" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Select Volume</option>
                                    <option value="0 - 5L">₹0 - ₹5 Lakhs</option>
                                    <option value="5L - 25L">₹5 Lakhs - ₹25 Lakhs</option>
                                    <option value="25L - 1Cr">₹25 Lakhs - ₹1 Crore</option>
                                    <option value="> 1Cr">More than ₹1 Crore</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Expected Average Transaction Value <span class="text-red-500">*</span></label>
                                <input type="text" name="expected_avg_value" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. ₹5,000">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Purpose/Use case of payments <span class="text-red-500">*</span></label>
                                <textarea name="purpose" rows="2" required class="w-full border-slate-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="What are you collecting payments for?"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                        <p class="text-sm text-blue-800">
                            <i class="ri-information-fill mr-1"></i>
                            By submitting this application, you agree to our Terms of Service. Once submitted, our team will review your application and assign applicable gateway charges. You will receive your API Keys upon approval.
                        </p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold shadow-md hover:bg-blue-700 transition">
                            Submit Application
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/business/payment_gateway/index.blade.php ENDPATH**/ ?>