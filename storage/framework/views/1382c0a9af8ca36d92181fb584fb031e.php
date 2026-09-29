<?php $__env->startSection('title', 'Track Order #' . $order->order_number . ' — FIINWAY'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#f1f3f6] min-h-screen pb-16">
    <div class="max-w-4xl mx-auto px-2 sm:px-4 py-4 sm:py-6 space-y-4">

        
        <div class="bg-white p-4 sm:p-6 rounded-sm shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-lg font-medium text-[#212121]">Order Tracking</h1>
                <p class="text-xs text-[#878787] mt-1">Order #<?php echo e($order->order_number); ?> • <?php echo e($order->created_at->format('d M Y')); ?></p>
            </div>
            <a href="<?php echo e(route('orders.show', $order->id)); ?>" class="px-5 py-2 rounded-sm border border-slate-300 hover:bg-slate-50 text-[#212121] text-sm font-medium transition-colors text-center">
                View Details
            </a>
        </div>

        <?php
            // Resolve which items and status to display
            // Use shipments if they exist, otherwise synthesize one block from the order itself
            $trackingBlocks = $order->shipments->isNotEmpty()
                ? $order->shipments->map(fn($s) => [
                    'shipment'   => $s,
                    'status'     => $s->status,
                    'items'      => ($s->items && $s->items->isNotEmpty()) ? $s->items : $order->items,
                    'courier'    => $s->courier_name,
                    'trackingId' => $s->tracking_id,
                    'seller'     => $s->seller->name ?? 'Verified Seller',
                  ])
                : collect([[
                    'shipment'   => null,
                    'status'     => $order->status,
                    'items'      => $order->items,
                    'courier'    => null,
                    'trackingId' => null,
                    'seller'     => null,
                  ]]);

            $statusOrder = ['confirmed' => 1, 'packed' => 2, 'shipped' => 3, 'out_for_delivery' => 4, 'delivered' => 5];
        ?>

        <?php $__currentLoopData = $trackingBlocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $block): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $status      = $block['status'];
            $items       = $block['items'];
            $currentStep = $statusOrder[$status] ?? 1;
        ?>

        <div class="bg-white rounded-sm shadow-sm overflow-hidden">

            
            <div class="p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-green-50 text-[#006837] flex items-center justify-center">
                        <i class="ri-truck-line text-lg"></i>
                    </div>
                    <div>
                        <?php if($block['shipment']): ?>
                            <h3 class="font-medium text-[#212121] text-sm">Shipment #<?php echo e($block['shipment']->id); ?></h3>
                            <p class="text-xs text-[#878787]">Seller: <?php echo e($block['seller']); ?></p>
                        <?php else: ?>
                            <h3 class="font-medium text-[#212121] text-sm">Order #<?php echo e($order->order_number); ?></h3>
                            <p class="text-xs text-[#878787]">All items</p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if($block['trackingId']): ?>
                <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                    <?php if($block['seller_id'] ?? false): ?>
                        <?php $sellerModel = \App\Models\User::find($block['seller_id']); ?>
                        <?php if($sellerModel): ?>
                            <div class="flex gap-2">
                                <?php if($sellerModel->phone): ?>
                                    <a href="tel:<?php echo e($sellerModel->phone); ?>" class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-200 rounded-sm text-xs font-bold hover:bg-green-100 flex items-center gap-1">
                                        <i class="ri-phone-line"></i> Call Seller
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('chat.show', ['order' => $order->id, 'seller' => $sellerModel->id])); ?>" class="px-3 py-1.5 bg-green-50 text-green-800 border border-green-200 rounded-sm text-xs font-bold hover:bg-green-100 flex items-center gap-1">
                                    <i class="ri-chat-3-line"></i> Chat with Seller
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="px-4 py-2 bg-white border border-slate-200 rounded-sm text-right sm:text-left">
                        <span class="text-[10px] font-bold text-[#878787] uppercase tracking-wider block"><?php echo e($block['courier'] ?? 'Courier Partner'); ?></span>
                        <span class="text-sm font-bold text-[#212121]"><?php echo e($block['trackingId']); ?></span>
                    </div>
                </div>
                <?php else: ?>
                    <?php if($block['seller_id'] ?? false): ?>
                        <?php $sellerModel = \App\Models\User::find($block['seller_id']); ?>
                        <?php if($sellerModel): ?>
                            <div class="flex gap-2">
                                <?php if($sellerModel->phone): ?>
                                    <a href="tel:<?php echo e($sellerModel->phone); ?>" class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-200 rounded-sm text-xs font-bold hover:bg-green-100 flex items-center gap-1">
                                        <i class="ri-phone-line"></i> Call Seller
                                    </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('chat.show', ['order' => $order->id, 'seller' => $sellerModel->id])); ?>" class="px-3 py-1.5 bg-green-50 text-green-800 border border-green-200 rounded-sm text-xs font-bold hover:bg-green-100 flex items-center gap-1">
                                    <i class="ri-chat-3-line"></i> Chat with Seller
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="p-4 sm:p-6 md:p-8">

                
                <div class="mb-8 space-y-3">
                    <h4 class="text-sm font-medium text-[#212121] mb-3">Items in this shipment</h4>
                    <?php $__empty_1 = true; $__currentLoopData = $items ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center gap-4 py-2 border-b border-slate-50 last:border-0">
                            <div class="w-14 h-14 shrink-0 border border-slate-100 flex items-center justify-center p-1 rounded-sm">
                                <?php if (isset($component)) { $__componentOriginala58dde406db9207f2e2c58e1c4a3d690 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.product-image','data' => ['product' => $item->product,'aspect' => 'square','class' => 'w-full h-full object-contain']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('product-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['product' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($item->product),'aspect' => 'square','class' => 'w-full h-full object-contain']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $attributes = $__attributesOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__attributesOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690)): ?>
<?php $component = $__componentOriginala58dde406db9207f2e2c58e1c4a3d690; ?>
<?php unset($__componentOriginala58dde406db9207f2e2c58e1c4a3d690); ?>
<?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="<?php echo e(route('products.show', $item->product->slug)); ?>" class="font-medium text-[#212121] text-sm hover:text-[#006837] line-clamp-1">
                                    <?php echo e($item->product_name ?? $item->product->name); ?>

                                </a>
                                <p class="text-xs text-[#878787] mt-0.5">Qty: <?php echo e($item->quantity); ?> • ₹<?php echo e(number_format($item->price)); ?></p>
                            </div>
                            <?php if(in_array($status, ['delivered']) && $order->status === 'delivered'): ?>
                                <a href="<?php echo e(route('reviews.create')); ?>?product_id=<?php echo e($item->product_id); ?>&order_id=<?php echo e($order->id); ?>"
                                   class="shrink-0 px-3 py-1.5 text-[#006837] text-xs font-medium hover:bg-green-50 transition-colors border border-green-100 rounded-sm flex items-center gap-1">
                                    <i class="ri-star-line"></i> Rate &amp; Review
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-sm text-[#878787]">No item details available.</p>
                    <?php endif; ?>
                </div>

                
                <div class="relative pl-8 space-y-8 before:absolute before:left-3.5 before:top-3 before:bottom-3 before:w-0.5 before:bg-slate-200">

                    <?php $__currentLoopData = [
                        [1, 'Order Confirmed',   'ri-checkbox-circle-line', 'Payment verified and order submitted to seller.'],
                        [2, 'Packed & Handled',  'ri-box-3-line',           'Seller has packed your item securely.'],
                        [3, 'Shipped',           'ri-truck-line',           'In transit with courier partner.'],
                        [4, 'Out for Delivery',  'ri-map-pin-time-line',    'Delivery agent is on the way.'],
                        [5, 'Delivered',         'ri-home-smile-2-line',    'Package delivered to your address.'],
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$step, $label, $icon, $desc]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isLast    = $step === 5;
                        $done      = $currentStep >= $step;
                        $active    = $currentStep === $step;
                        $dotColor  = $done ? ($isLast ? 'bg-[#388e3c] border-[#388e3c]' : 'bg-[#006837] border-[#006837]') : 'bg-white border-slate-200';
                        $textColor = $done ? ($isLast ? 'text-[#388e3c]' : 'text-[#212121]') : 'text-[#878787]';
                    ?>
                    <div class="relative flex items-start gap-4">
                        <div class="absolute -left-8 top-0 w-7 h-7 rounded-full flex items-center justify-center text-xs border-2 transition-all <?php echo e($dotColor); ?>">
                            <?php if($done): ?>
                                <i class="ri-check-line text-white text-xs font-bold"></i>
                            <?php endif; ?>
                        </div>
                        <div class="pt-0.5 flex-1">
                            <h4 class="font-medium text-sm flex items-center gap-2 <?php echo e($textColor); ?>">
                                <i class="<?php echo e($icon); ?>"></i> <?php echo e($label); ?>

                                <?php if($active): ?>
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-1.5 py-0.5 rounded-sm"
                                          style="background:#fff3cd;color:#856404;">Current</span>
                                <?php endif; ?>
                            </h4>
                            <p class="text-xs text-[#878787] mt-0.5"><?php echo e($desc); ?></p>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>

                <?php if($block['shipment'] && $block['shipment']->delivery_partner_type === 'fiinway'): ?>
                    <div class="mt-8 p-4 bg-indigo-50 border border-indigo-200 rounded-sm">
                        <h4 class="font-bold text-indigo-800 text-sm mb-1"><i class="ri-shield-keyhole-line mr-1"></i> Delivery Verification OTP</h4>
                        <p class="text-xs text-indigo-700">Please share this OTP with the FIINWAY delivery executive to receive your package.</p>
                        <div class="mt-3 text-3xl font-black tracking-widest text-indigo-900 text-center"><?php echo e($block['shipment']->delivery_otp ?? 'N/A'); ?></div>
                    </div>
                <?php endif; ?>

                <?php if($block['shipment'] && $block['shipment']->delivery_partner_type === 'seller' && $order->address->latitude && $block['shipment']->seller->latitude): ?>
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <h4 class="font-bold text-slate-800 text-sm mb-3">Live Tracking / Distance</h4>
                        <div id="map-<?php echo e($block['shipment']->id); ?>" class="w-full h-64 rounded bg-slate-100 border border-slate-200 z-10 relative"></div>
                        <p class="text-xs text-slate-500 mt-2"><i class="ri-map-pin-line text-[#e94f1c]"></i> Seller Location to <i class="ri-map-pin-line text-[#388e3c]"></i> Delivery Address</p>
                    </div>

                    <?php $__env->startPush('scripts'); ?>
                    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
                    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const buyerLat = <?php echo e($order->address->latitude); ?>;
                            const buyerLng = <?php echo e($order->address->longitude); ?>;
                            const sellerLat = <?php echo e($block['shipment']->seller->latitude); ?>;
                            const sellerLng = <?php echo e($block['shipment']->seller->longitude); ?>;

                            const map = L.map('map-<?php echo e($block['shipment']->id); ?>').setView([(buyerLat + sellerLat) / 2, (buyerLng + sellerLng) / 2], 12);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                maxZoom: 19,
                                attribution: '© OpenStreetMap'
                            }).addTo(map);

                            const buyerIcon = L.divIcon({ html: '<i class="ri-map-pin-fill text-[#388e3c] text-3xl" style="filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));"></i>', className: '', iconSize: [24, 24], iconAnchor: [12, 24] });
                            const sellerIcon = L.divIcon({ html: '<i class="ri-map-pin-fill text-[#e94f1c] text-3xl" style="filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));"></i>', className: '', iconSize: [24, 24], iconAnchor: [12, 24] });

                            L.marker([buyerLat, buyerLng], {icon: buyerIcon}).addTo(map).bindPopup('Delivery Address');
                            L.marker([sellerLat, sellerLng], {icon: sellerIcon}).addTo(map).bindPopup('Seller Location');

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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/buyer/order-tracking.blade.php ENDPATH**/ ?>