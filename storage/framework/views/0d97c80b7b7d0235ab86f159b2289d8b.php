<?php $__env->startSection('title', 'Contact Us — FIINWAY'); ?>
<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 py-10">
    <div class="bg-white rounded shadow-sm p-8">
        <h1 class="text-2xl font-bold text-[#212121] mb-1">Contact Us</h1>
        <p class="text-xs text-slate-400 mb-8">We'd love to hear from you — fill out the form and we'll get back shortly</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            
            <form class="space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-[#212121] mb-1">Your Name</label>
                        <input type="text" placeholder="John Doe"
                            class="w-full px-4 py-3 border border-slate-200 rounded-md text-sm outline-none transition focus:border-[#006837] focus:ring-2 focus:ring-[#006837]/15" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#212121] mb-1">Email</label>
                        <input type="email" placeholder="you@example.com"
                            class="w-full px-4 py-3 border border-slate-200 rounded-md text-sm outline-none transition focus:border-[#006837] focus:ring-2 focus:ring-[#006837]/15" />
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#212121] mb-1">Subject</label>
                    <input type="text" placeholder="How can we help?"
                        class="w-full px-4 py-3 border border-slate-200 rounded-md text-sm outline-none transition focus:border-[#006837] focus:ring-2 focus:ring-[#006837]/15" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#212121] mb-1">Message</label>
                    <textarea rows="6" placeholder="Describe your query or feedback in detail..."
                        class="w-full px-4 py-3 border border-slate-200 rounded-md text-sm outline-none transition focus:border-[#006837] focus:ring-2 focus:ring-[#006837]/15 resize-none"></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">For order-related issues, please visit our <a href="<?php echo e(route('page.support')); ?>" class="text-[#e94f1c] hover:underline">Customer Support</a> page.</p>
                </div>

                <button type="submit"
                    class="group inline-flex items-center justify-center gap-2 w-full sm:w-auto px-8 py-3 bg-[#006837] text-white text-sm font-semibold rounded-md uppercase tracking-wide hover:bg-[#00552c] hover:shadow-lg hover:shadow-[#006837]/25 transition-all duration-200 active:scale-[0.98]">
                    Submit
                    <i class="ri-send-plane-2-line group-hover:translate-x-0.5 transition-transform"></i>
                </button>
            </form>

            
            <div>
                <h2 class="text-xl font-bold text-[#212121] mb-5">Other ways to reach us</h2>
                <div class="space-y-5">
                    <div class="flex items-start gap-4 p-4 bg-[#f1f3f6] rounded-sm">
                        <div class="text-2xl text-[#006837]"><i class="ri-mail-line"></i></div>
                        <div>
                            <h3 class="font-bold text-[#212121] text-sm">General Enquiries</h3>
                            <p class="text-[#878787] text-sm mt-1">info@fiinway.in</p>
                            <p class="text-[#878787] text-xs mt-0.5">We usually reply within 24 hours</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 bg-[#f1f3f6] rounded-sm">
                        <div class="text-2xl text-[#006837]"><i class="ri-shield-user-line"></i></div>
                        <div>
                            <h3 class="font-bold text-[#212121] text-sm">Grievance / Escalation</h3>
                            <p class="text-[#878787] text-sm mt-1">grievance@fiinway.in</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 bg-[#f1f3f6] rounded-sm">
                        <div class="text-2xl text-[#006837]"><i class="ri-lock-line"></i></div>
                        <div>
                            <h3 class="font-bold text-[#212121] text-sm">Privacy Concerns</h3>
                            <p class="text-[#878787] text-sm mt-1">privacy@fiinway.in</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4 p-4 bg-[#f1f3f6] rounded-sm">
                        <div class="text-2xl text-[#006837]"><i class="ri-map-pin-line"></i></div>
                        <div>
                            <h3 class="font-bold text-[#212121] text-sm">Head Office</h3>
                            <p class="text-[#878787] text-sm mt-1">FIINWAY 360 COMMUNICATION<br>5, Balaganj, Near Siwi Factory, Mehta Vihar, Lucknow, Uttar Pradesh, India – 226003</p>
                        </div>
                    </div>
                    <div class="flex gap-3 mt-4">
                        <a href="https://facebook.com" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-[#1877f2] text-white text-sm rounded-sm hover:opacity-90 transition">
                            <i class="ri-facebook-fill"></i> Facebook
                        </a>
                        <a href="https://twitter.com" target="_blank" class="flex items-center gap-2 px-4 py-2 bg-[#000] text-white text-sm rounded-sm hover:opacity-90 transition">
                            <i class="ri-twitter-x-fill"></i> Twitter
                        </a>
                        <a href="https://instagram.com" target="_blank" class="flex items-center gap-2 px-4 py-2 text-white text-sm rounded-sm hover:opacity-90 transition" style="background: linear-gradient(135deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888);">
                            <i class="ri-instagram-fill"></i> Instagram
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/pages/contact.blade.php ENDPATH**/ ?>