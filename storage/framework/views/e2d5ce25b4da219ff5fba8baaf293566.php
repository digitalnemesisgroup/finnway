<?php $__env->startSection('title', 'Business Applications & API Clients'); ?>
<?php $__env->startSection('header_title', 'Business Applications & API Clients'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-8" x-data="{ addModalOpen: false }">

    <!-- ── Header Bar ──────────────────────────────────────────────────────────── -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <div>
            <h1 class="text-xl font-black text-slate-800 tracking-tight">Business Applications & API Clients</h1>
            <p class="text-xs text-slate-500 mt-1">Review merchant applications, configure platform charges %, and issue API keys.</p>
        </div>
        <div class="flex items-center gap-2">
            <button @click="addModalOpen = true" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                <i class="ri-add-line text-sm"></i> Add Business Manually
            </button>
            <a href="<?php echo e(route('hub.portal.docs')); ?>" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="ri-book-open-line text-sm"></i> View Docs
            </a>
        </div>
    </div>

    <!-- ── Pending Applications ────────────────────────────────────────────────── -->
    <?php if($pendingClients->isNotEmpty()): ?>
    <div class="space-y-4">
        <h2 class="text-sm font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-amber-500 text-white text-[11px] font-black"><?php echo e($pendingClients->count()); ?></span>
            Pending Applications Review
        </h2>

        <div class="space-y-4">
            <?php $__currentLoopData = $pendingClients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pending): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white border-2 border-amber-300 rounded-2xl shadow-sm overflow-hidden" x-data="{ showDetails: false }">

                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 px-6 py-4 bg-amber-50/80 border-b border-amber-200">
                    <div>
                        <span class="inline-block text-[10px] font-extrabold uppercase tracking-widest text-amber-800 bg-amber-200/80 px-2 py-0.5 rounded mb-1">Pending Review</span>
                        <h3 class="text-lg font-black text-slate-800"><?php echo e($pending->name); ?></h3>
                        <p class="text-xs text-slate-500"><?php echo e($pending->legal_name); ?> &bull; <?php echo e($pending->business_type); ?></p>
                    </div>
                    <button @click="showDetails = !showDetails"
                        class="shrink-0 text-xs font-bold px-4 py-2 rounded-xl bg-white border border-amber-300 text-amber-800 hover:bg-amber-100 transition flex items-center gap-1">
                        <span x-show="!showDetails"><i class="ri-eye-line mr-1"></i>View Full Application</span>
                        <span x-show="showDetails" x-cloak><i class="ri-eye-off-line mr-1"></i>Collapse Details</span>
                    </button>
                </div>

                <!-- Full Details -->
                <div x-show="showDetails" x-cloak class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 border-b border-slate-100 text-xs bg-slate-50/50">
                    <?php $__currentLoopData = [
                        ['PAN Number', $pending->pan_number],
                        ['GSTIN', $pending->gstin ?: 'N/A'],
                        ['Reg. Number', $pending->registration_number ?: 'N/A'],
                        ['Category', $pending->category],
                        ['Business Email', $pending->business_email],
                        ['Business Mobile', $pending->business_mobile],
                        ['Monthly Volume', $pending->expected_monthly_volume],
                        ['Avg Ticket Value', $pending->expected_avg_value],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $value]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white p-3 rounded-xl border border-slate-200">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5"><?php echo e($label); ?></span>
                        <p class="text-xs font-bold text-slate-800"><?php echo e($value); ?></p>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <div class="md:col-span-2 bg-white p-3 rounded-xl border border-slate-200">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Website</span>
                        <a href="<?php echo e($pending->website_url); ?>" target="_blank" class="text-xs text-blue-600 hover:underline font-bold"><?php echo e($pending->website_url); ?></a>
                    </div>
                    <div class="md:col-span-2 bg-white p-3 rounded-xl border border-slate-200">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Purpose</span>
                        <p class="text-xs text-slate-700"><?php echo e($pending->purpose); ?></p>
                    </div>
                    <div class="md:col-span-4 bg-white p-3 rounded-xl border border-slate-200">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Registered Address</span>
                        <p class="text-xs text-slate-700"><?php echo e($pending->registered_address); ?></p>
                    </div>
                </div>

                <!-- Approve / Reject Form Panel -->
                <div class="px-6 py-5 bg-slate-50 border-t border-slate-100">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Set Platform Pricing, Subscription Dates & Approve</p>
                    <form action="<?php echo e(route('admin.payment-clients.approve', $pending)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Gateway Charge (%)</label>
                                <input type="number" step="0.01" name="gateway_charge_percent" value="2.00" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs font-mono font-bold focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">GST on Charge (%)</label>
                                <input type="number" step="0.01" name="gst_on_charge_percent" value="18.00" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs font-mono font-bold focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Starts On <span class="text-rose-500">*</span></label>
                                <input type="date" name="starts_at" value="<?php echo e(now()->toDateString()); ?>" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Expires On <span class="text-rose-500">*</span></label>
                                <input type="date" name="expires_at" value="<?php echo e(now()->addYear()->toDateString()); ?>" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2 text-xs focus:border-blue-500">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-sm">
                                <i class="ri-checkbox-circle-line"></i> Approve & Generate API Keys
                            </button>
                    </form>
                    <form action="<?php echo e(route('admin.payment-clients.reject', $pending)); ?>" method="POST" onsubmit="return confirm('Reject this application?')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 hover:bg-rose-100 font-bold text-xs transition flex items-center gap-1">
                            <i class="ri-close-circle-line"></i> Reject Application
                        </button>
                    </form>
                        </div>
                </div>

            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ── Approved & Registered Clients ──────────────────────────────────────── -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Approved & Active API Clients</h3>
                <p class="text-xs text-slate-500">Active accounts and integration keys</p>
            </div>
            <span class="text-xs font-bold px-3 py-1 rounded-lg bg-slate-200 text-slate-700">
                Total Clients: <?php echo e($clients->count()); ?>

            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-100/70 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-600">
                        <th class="py-3.5 px-4">Client Name</th>
                        <th class="py-3.5 px-4">Pricing Tier</th>
                        <th class="py-3.5 px-4">Validity Period</th>
                        <th class="py-3.5 px-4">API Credentials</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200" x-data="{ revealId: null }">
                    <?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-slate-50/80 transition">
                        <!-- Name & Category -->
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-800 text-sm block"><?php echo e($c->name); ?></span>
                                <?php if($c->id === 1): ?>
                                    <span class="bg-blue-100 text-blue-800 text-[10px] font-extrabold px-2 py-0.5 rounded">INTERNAL</span>
                                <?php endif; ?>
                            </div>
                            <span class="text-[11px] text-slate-500 block"><?php echo e($c->legal_name); ?> &bull; <?php echo e($c->category); ?></span>
                            <span class="text-[10px] text-slate-400 block font-mono"><?php echo e($c->business_email); ?></span>
                        </td>

                        <!-- Pricing Tier -->
                        <td class="py-4 px-4 whitespace-nowrap">
                            <span class="font-bold text-blue-600 font-mono"><?php echo e(number_format($c->gateway_charge_percent, 2)); ?>%</span>
                            <span class="text-[10px] text-slate-400 block">+ <?php echo e(number_format($c->gst_on_charge_percent, 2)); ?>% GST</span>
                        </td>

                        <!-- Validity -->
                        <td class="py-4 px-4 whitespace-nowrap text-slate-600">
                            <?php if($c->starts_at && $c->expires_at): ?>
                                <span class="block font-medium"><?php echo e($c->starts_at->format('d M Y')); ?> &rarr; <?php echo e($c->expires_at->format('d M Y')); ?></span>
                                <?php if($c->isExpired()): ?>
                                    <span class="text-[10px] font-bold text-rose-600 block">EXPIRED</span>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-400 block"><?php echo e($c->expires_at->diffForHumans()); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-slate-400">No Expiry Set</span>
                            <?php endif; ?>
                        </td>

                        <!-- API Credentials -->
                        <td class="py-4 px-4">
                            <?php if($c->api_key): ?>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1 font-mono text-[11px] bg-slate-100 px-2 py-1 rounded w-fit text-slate-800">
                                        <span class="font-bold">Key:</span> <?php echo e($c->api_key); ?>

                                    </div>
                                    <div class="flex items-center gap-1 font-mono text-[10px] text-slate-500">
                                        <span class="font-bold">Salt:</span>
                                        <span x-show="revealId !== <?php echo e($c->id); ?>">••••••••••••••••</span>
                                        <span x-show="revealId === <?php echo e($c->id); ?>" x-cloak class="text-slate-800 font-bold select-all"><?php echo e($c->api_salt); ?></span>
                                        <button @click="revealId = revealId === <?php echo e($c->id); ?> ? null : <?php echo e($c->id); ?>" class="text-blue-600 hover:underline text-[10px] ml-1 font-sans">
                                            <span x-text="revealId === <?php echo e($c->id); ?> ? 'Hide' : 'Reveal'"></span>
                                        </button>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="text-slate-400 italic">No Keys Issued</span>
                            <?php endif; ?>
                        </td>

                        <!-- Status Badge -->
                        <td class="py-4 px-4 text-center">
                            <?php if($c->approval_status === 'approved'): ?>
                                <?php if($c->isLive()): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 uppercase">
                                        <i class="ri-checkbox-circle-fill"></i> LIVE
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 uppercase">
                                        INACTIVE
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-rose-100 text-rose-800 uppercase">
                                    <?php echo e(strtoupper($c->approval_status)); ?>

                                </span>
                            <?php endif; ?>
                        </td>

                        <!-- Actions -->
                        <td class="py-4 px-4 text-right whitespace-nowrap">
                            <?php if($c->id !== 1): ?>
                            <div class="flex items-center justify-end gap-2">
                                <form action="<?php echo e(route('admin.payment-clients.toggle', $c)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-xs font-bold <?php echo e($c->is_active ? 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100'); ?>">
                                        <?php echo e($c->is_active ? 'Disable' : 'Activate'); ?>

                                    </button>
                                </form>
                                <form action="<?php echo e(route('admin.payment-clients.destroy', $c)); ?>" method="POST" onsubmit="return confirm('Delete this client?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="px-2 py-1 rounded-lg text-xs text-rose-600 hover:bg-rose-50 border border-rose-200 font-bold">
                                        Delete
                                    </button>
                                </form>
                            </div>
                            <?php else: ?>
                                <span class="text-[10px] font-bold text-slate-400 italic">Protected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400">No registered clients found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ── Manual Add Modal ────────────────────────────────────────────────────── -->
    <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
        <div class="bg-white border border-slate-200 rounded-2xl max-w-md w-full p-6 space-y-4 shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <h3 class="font-bold text-slate-800 text-base">Add Business Application</h3>
                <button @click="addModalOpen = false" class="text-slate-400 hover:text-slate-600"><i class="ri-close-line text-xl"></i></button>
            </div>

            <form action="<?php echo e(route('admin.payment-clients.store')); ?>" method="POST" class="space-y-4 text-xs">
                <?php echo csrf_field(); ?>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Business Name</label>
                    <input type="text" name="name" required class="w-full border border-slate-300 rounded-xl px-3 py-2 focus:border-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Legal Entity Name</label>
                    <input type="text" name="legal_name" required class="w-full border border-slate-300 rounded-xl px-3 py-2 focus:border-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Business Type</label>
                    <input type="text" name="business_type" placeholder="e.g. Private Limited, Proprietorship" required class="w-full border border-slate-300 rounded-xl px-3 py-2 focus:border-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">PAN Number</label>
                    <input type="text" name="pan_number" required class="w-full border border-slate-300 rounded-xl px-3 py-2 focus:border-blue-500">
                </div>
                <div>
                    <label class="block font-bold text-slate-700 mb-1">Business Email</label>
                    <input type="email" name="business_email" required class="w-full border border-slate-300 rounded-xl px-3 py-2 focus:border-blue-500">
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" @click="addModalOpen = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-sm">Save Application</button>
                </div>
            </form>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('hub.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/admin/payment_clients/index.blade.php ENDPATH**/ ?>