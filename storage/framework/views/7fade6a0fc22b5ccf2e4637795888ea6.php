<?php $__env->startSection('content'); ?>
<div class="bg-slate-50 min-h-screen pb-32">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white border-b border-slate-100 p-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('seller.orders')); ?>" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-700">
                <i class="ri-arrow-left-line text-lg"></i>
            </a>
            <h1 class="text-lg font-bold text-slate-900">Manage Order</h1>
        </div>
    </div>

    <div class="p-4 space-y-4">
        <!-- Action Card -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 text-center">
            <div class="inline-flex items-center justify-center px-3 py-1 bg-slate-100 rounded-full text-xs font-bold text-slate-600 mb-3 uppercase tracking-wider">
                Current Status
            </div>
            <h2 class="text-2xl font-black text-slate-900 mb-2"><?php echo e(ucfirst(str_replace('_', ' ', $item->status))); ?></h2>
            
            <div class="mt-6 pt-6 border-t border-slate-100">
                <?php if($item->status === 'confirmed'): ?>
                    <form action="<?php echo e(route('seller.orders.pack', $item->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="ri-box-3-line mr-1"></i> Mark as Packed</button>
                    </form>
                <?php elseif($item->status === 'packed'): ?>
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="btn btn-primary btn-block btn-lg"><i class="ri-truck-line mr-1"></i> Ship Order</button>
                        
                        <!-- Ship Modal -->
                        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm" x-cloak>
                            <div class="bg-white rounded-3xl w-full max-w-sm p-6 shadow-2xl relative text-left" @click.away="open = false">
                                <h3 class="text-lg font-bold text-slate-900 mb-4">Shipping Details</h3>
                                <form action="<?php echo e(route('seller.orders.ship', $item->id)); ?>" method="POST" class="space-y-4">
                                    <?php echo csrf_field(); ?>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Courier Partner (Optional)</label>
                                        <input type="text" name="courier_name" class="input" placeholder="e.g. Delhivery, DTDC">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tracking ID (Optional)</label>
                                        <input type="text" name="tracking_id" class="input" placeholder="e.g. AWB123456789">
                                    </div>
                                    <div class="pt-4 flex gap-3">
                                        <button type="button" @click="open = false" class="btn btn-outline flex-1">Cancel</button>
                                        <button type="submit" class="btn btn-primary flex-1">Confirm Ship</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php elseif($item->status === 'shipped'): ?>
                    <form action="<?php echo e(route('seller.orders.out-for-delivery', $item->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary btn-block btn-lg"><i class="ri-ebike-2-line mr-1"></i> Out for Delivery</button>
                    </form>
                <?php elseif($item->status === 'out_for_delivery'): ?>
                    <form action="<?php echo e(route('seller.orders.deliver', $item->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-block btn-lg shadow-lg shadow-green-200"><i class="ri-checkbox-circle-fill mr-1"></i> Mark Delivered</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Summary -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <h3 class="text-sm font-bold text-slate-800 mb-4">Product Details</h3>
            <div class="flex items-start gap-3">
                <img src="<?php echo e($item->product->primary_image_url); ?>" class="w-16 h-16 rounded-xl object-cover shrink-0 border border-slate-100">
                <div>
                    <h4 class="font-semibold text-slate-900 text-sm mb-1"><?php echo e($item->product_name); ?></h4>
                    <p class="text-xs text-slate-500 mb-2">Price: ₹<?php echo e(number_format($item->price)); ?> | Qty: <?php echo e($item->quantity); ?></p>
                    <p class="font-bold text-indigo-600">Total: ₹<?php echo e(number_format($item->subtotal)); ?></p>
                </div>
            </div>
        </div>

        <!-- Earning Summary -->
        <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100">
            <h3 class="text-sm font-bold text-emerald-900 mb-3 flex items-center gap-2"><i class="ri-wallet-3-line"></i> Earning Breakdown</h3>
            <?php if($item->earning): ?>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between text-emerald-700">
                    <span>Sale Amount</span>
                    <span class="font-medium">₹<?php echo e(number_format($item->earning->order_amount)); ?></span>
                </div>
                <div class="flex justify-between text-emerald-700">
                    <span>FIINWAY Fee <?php echo e($item->earning->commission_percent > 0 ? '('.$item->earning->commission_percent.'%)' : ''); ?></span>
                    <span class="font-medium text-red-500">-₹<?php echo e(number_format($item->earning->commission_amount)); ?></span>
                </div>
                <div class="divider border-emerald-200 border-dashed my-2"></div>
                <div class="flex justify-between items-center">
                    <span class="font-bold text-emerald-900">You Receive</span>
                    <span class="font-black text-emerald-600 text-lg">₹<?php echo e(number_format($item->earning->seller_amount)); ?></span>
                </div>
                <div class="mt-3 pt-3 border-t border-emerald-200">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-emerald-900">Settlement Status</span>
                        <span class="font-bold <?php echo e($item->earning->status === 'released' ? 'text-green-600' : 'text-orange-500'); ?>">
                            <?php echo e($item->earning->status === 'released' ? 'Paid ✓' : ucfirst(str_replace('_', ' ', $item->earning->status))); ?>

                        </span>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="text-sm text-emerald-700">Earnings calculation pending.</div>
            <?php endif; ?>
        </div>

        <!-- Buyer & Shipping Info -->
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <h3 class="text-sm font-bold text-slate-800 mb-4">Buyer Details & Address</h3>
            
            <div class="mb-4">
                <p class="text-sm font-bold text-slate-900"><?php echo e($item->order->buyer->name); ?></p>
                <p class="text-xs text-slate-500"><i class="ri-phone-line"></i> +91 <?php echo e($item->order->buyer->phone); ?></p>
            </div>

            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                <p class="font-bold text-slate-800 text-sm mb-1"><?php echo e($item->order->address->full_name); ?></p>
                <p class="text-xs text-slate-600 leading-relaxed"><?php echo e($item->order->address->fullText()); ?></p>
            </div>
        </div>
        
        <?php if($shipment && $shipment->delivery_partner_type === 'fiinway'): ?>
            <div class="bg-indigo-50 rounded-2xl p-4 shadow-sm border border-indigo-200 text-center">
                <h3 class="text-sm font-bold text-indigo-900 mb-2"><i class="ri-shield-keyhole-line"></i> FIINWAY Pickup OTP</h3>
                <p class="text-xs text-indigo-700 mb-2">Share this OTP with the FIINWAY delivery agent when they come for pickup.</p>
                <div class="text-3xl font-black tracking-widest text-indigo-800"><?php echo e($shipment->pickup_otp ?? 'N/A'); ?></div>
            </div>
        <?php endif; ?>

        <?php if($shipment && $shipment->delivery_partner_type === 'seller' && $item->order->address->latitude && auth()->user()->latitude): ?>
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 mb-3">Delivery Route (Live Map)</h3>
                <div id="seller-map-<?php echo e($shipment->id); ?>" class="w-full h-64 rounded bg-slate-100 border border-slate-200 z-10 relative"></div>
                <p class="text-xs text-slate-500 mt-2"><i class="ri-map-pin-line text-[#e94f1c]"></i> Your Location to <i class="ri-map-pin-line text-[#388e3c]"></i> Buyer's Address</p>
            </div>

            <?php $__env->startPush('scripts'); ?>
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const buyerLat = <?php echo e($item->order->address->latitude); ?>;
                    const buyerLng = <?php echo e($item->order->address->longitude); ?>;
                    const sellerLat = <?php echo e(auth()->user()->latitude); ?>;
                    const sellerLng = <?php echo e(auth()->user()->longitude); ?>;

                    const map = L.map('seller-map-<?php echo e($shipment->id); ?>').setView([(buyerLat + sellerLat) / 2, (buyerLng + sellerLng) / 2], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    const buyerIcon = L.divIcon({ html: '<i class="ri-map-pin-fill text-[#388e3c] text-3xl" style="filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));"></i>', className: '', iconSize: [24, 24], iconAnchor: [12, 24] });
                    const sellerIcon = L.divIcon({ html: '<i class="ri-map-pin-fill text-[#e94f1c] text-3xl" style="filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));"></i>', className: '', iconSize: [24, 24], iconAnchor: [12, 24] });

                    L.marker([buyerLat, buyerLng], {icon: buyerIcon}).addTo(map).bindPopup('Delivery Address');
                    L.marker([sellerLat, sellerLng], {icon: sellerIcon}).addTo(map).bindPopup('Your Location');

                    const bounds = new L.LatLngBounds([[buyerLat, buyerLng], [sellerLat, sellerLng]]);
                    map.fitBounds(bounds, { padding: [30, 30] });
                    
                    // Simple polyline path
                    L.polyline([[sellerLat, sellerLng], [buyerLat, buyerLng]], {color: '#e94f1c', weight: 3, dashArray: '5, 5'}).addTo(map);
                });
            </script>
            <?php $__env->stopPush(); ?>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', ['hideNav' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/seller/orders/show.blade.php ENDPATH**/ ?>