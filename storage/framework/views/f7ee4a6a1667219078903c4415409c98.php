<?php $__env->startSection('title', 'Chat with ' . $seller->name . ' — FIINWAY'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-[#f1f3f6] min-h-screen flex flex-col">
    <div class="max-w-2xl mx-auto w-full flex-1 flex flex-col px-2 py-4">

        
        <div class="bg-white rounded-t-xl border border-slate-200 shadow-sm px-4 py-3 flex items-center gap-3">
            <a href="<?php echo e(url()->previous()); ?>" class="w-9 h-9 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-600 hover:bg-slate-100">
                <i class="ri-arrow-left-line text-lg"></i>
            </a>
            <div class="flex items-center gap-3 flex-1 min-w-0">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-[#388e3c] flex items-center justify-center text-white font-bold text-sm shrink-0">
                    <?php echo e(strtoupper(substr($seller->name, 0, 1))); ?>

                </div>
                <div class="min-w-0">
                    <h2 class="font-bold text-[#212121] text-sm truncate"><?php echo e($seller->name); ?></h2>
                    <p class="text-[10px] text-slate-500">
                        <?php if(isset($order) && $order): ?>
                            Order #<?php echo e($order->order_number); ?>

                        <?php else: ?>
                            <?php echo e($seller->seller_type ?? 'Seller'); ?> • Direct Message
                        <?php endif; ?>
                        &nbsp;•&nbsp;<span id="ws-status" class="text-orange-400 font-medium">Connecting...</span>
                    </p>
                </div>
            </div>
            <?php if($seller->phone): ?>
            <a href="tel:<?php echo e($seller->phone); ?>" class="w-9 h-9 rounded-full bg-green-50 border border-green-200 flex items-center justify-center text-green-700 hover:bg-green-100">
                <i class="ri-phone-line"></i>
            </a>
            <?php endif; ?>
        </div>

        
        <?php if(!isset($order) || !$order): ?>
        <div class="bg-orange-50 border-x border-orange-200 px-4 py-2.5 flex items-center gap-2">
            <i class="ri-error-warning-fill text-orange-500 text-lg shrink-0"></i>
            <p class="text-xs text-orange-800 font-medium leading-snug">
                Inspect the product in person before purchasing. Arrange a safe meeting place for physical inspection.
            </p>
        </div>
        <?php endif; ?>

        
        <div id="messages-container" 
             class="flex-1 bg-white border-x border-slate-200 overflow-y-auto p-4 space-y-4 min-h-[400px] max-h-[60vh]"
             data-auth-id="<?php echo e(Auth::id()); ?>"
             data-conversation-id="<?php echo e($conversation->id); ?>">

            <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex flex-col <?php echo e($msg->sender_id === Auth::id() ? 'items-end' : 'items-start'); ?>" data-msg-id="<?php echo e($msg->id); ?>">
                <div class="max-w-[80%] px-3.5 py-2.5 rounded-2xl text-sm leading-relaxed
                    <?php echo e($msg->sender_id === Auth::id() 
                        ? 'bg-[#006837] text-white rounded-br-sm' 
                        : 'bg-slate-100 text-[#212121] rounded-bl-sm'); ?>">
                    <?php echo e($msg->body); ?>

                </div>
                <span class="text-[10px] text-slate-400 mt-1 px-1"><?php echo e($msg->created_at->format('h:i A')); ?></span>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div id="empty-state" class="flex flex-col items-center justify-center py-12 text-slate-400">
                <i class="ri-chat-3-line text-4xl mb-2"></i>
                <p class="text-sm font-medium">No messages yet</p>
                <p class="text-xs">Start the conversation below!</p>
            </div>
            <?php endif; ?>
        </div>

        
        <div class="bg-white border border-slate-200 rounded-b-xl px-3 py-3 flex items-center gap-2 shadow-sm">
            <form id="chat-form" 
                  action="<?php echo e(route('chat.store', $conversation)); ?>" 
                  method="POST" 
                  class="flex items-center gap-2 flex-1">
                <?php echo csrf_field(); ?>
                <input type="text" 
                       id="message-input"
                       name="body" 
                       class="flex-1 bg-slate-100 rounded-full px-4 py-2.5 text-sm outline-none focus:ring-2 focus:ring-[#006837]/30 placeholder:text-slate-400"
                       placeholder="Type a message..." 
                       autocomplete="off"
                       required>
                <button type="submit" 
                        id="send-btn"
                        class="w-10 h-10 bg-[#006837] hover:bg-[#004d27] text-white rounded-full flex items-center justify-center transition-all active:scale-95 shadow-md shadow-green-900/20">
                    <i class="ri-send-plane-fill text-base"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function() {
    const container      = document.getElementById('messages-container');
    const form           = document.getElementById('chat-form');
    const input          = document.getElementById('message-input');
    const sendBtn        = document.getElementById('send-btn');
    const statusEl       = document.getElementById('ws-status');
    const authId         = parseInt(container.dataset.authId);
    const conversationId = container.dataset.conversationId;
    const csrfToken      = document.querySelector('meta[name="csrf-token"]').content;
    const storeUrl       = '<?php echo e(route("chat.store", $conversation)); ?>';

    // Scroll to bottom of messages
    function scrollToBottom() {
        container.scrollTop = container.scrollHeight;
    }
    scrollToBottom();

    // Render a new message bubble
    function renderMessage(msg) {
        const empty = document.getElementById('empty-state');
        if (empty) empty.remove();

        const isMine = parseInt(msg.sender_id) === authId;
        const wrapper = document.createElement('div');
        wrapper.className = `flex flex-col ${isMine ? 'items-end' : 'items-start'}`;
        wrapper.setAttribute('data-msg-id', msg.id);
        wrapper.innerHTML = `
            <div class="max-w-[80%] px-3.5 py-2.5 rounded-2xl text-sm leading-relaxed
                ${isMine ? 'bg-[#006837] text-white rounded-br-sm' : 'bg-slate-100 text-[#212121] rounded-bl-sm'}">
                ${escapeHtml(msg.body)}
            </div>
            <span class="text-[10px] text-slate-400 mt-1 px-1">${msg.created_at}</span>
        `;
        container.appendChild(wrapper);
        scrollToBottom();
    }

    function escapeHtml(text) {
        const el = document.createElement('div');
        el.textContent = text;
        return el.innerHTML;
    }

    // ── WebSocket via Laravel Reverb ──────────────────────────────────────────
    let wsConnected = false;

    <?php if(auth()->guard()->check()): ?>
    try {
        // Laravel Echo + Reverb
        const Echo = window.Echo;
        if (Echo) {
            Echo.private(`conversation.${conversationId}`)
                .listen('MessageSent', (e) => {
                    // Only render if not already present (avoid duplicates from sender)
                    if (!document.querySelector(`[data-msg-id="${e.id}"]`)) {
                        renderMessage(e);
                    }
                })
                .subscribed(() => {
                    wsConnected = true;
                    statusEl.textContent = 'Online';
                    statusEl.className = 'text-emerald-500 font-medium';
                })
                .error(() => {
                    statusEl.textContent = 'Offline (Polling)';
                    statusEl.className = 'text-orange-400 font-medium';
                    startPolling();
                });
        } else {
            startPolling();
        }
    } catch (e) {
        startPolling();
    }
    <?php endif; ?>

    // ── Polling Fallback (if Echo not available) ──────────────────────────────
    let lastMsgId = <?php echo e($messages->last()->id ?? 0); ?>;
    let pollInterval = null;

    function startPolling() {
        if (pollInterval) return;
        statusEl.textContent = 'Polling';
        statusEl.className = 'text-slate-400 font-medium';
        pollInterval = setInterval(async () => {
            try {
                const res  = await fetch('<?php echo e(route("chat.messages", $conversation)); ?>');
                const data = await res.json();
                const newMsgs = data.messages.filter(m => m.id > lastMsgId);
                newMsgs.forEach(m => {
                    if (!document.querySelector(`[data-msg-id="${m.id}"]`)) {
                        renderMessage(m);
                        lastMsgId = m.id;
                    }
                });
            } catch (_) {}
        }, 3000);
    }

    // ── AJAX Form Submit ──────────────────────────────────────────────────────
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const body = input.value.trim();
        if (!body) return;

        // Optimistic render (show immediately)
        const optimistic = {
            id: 'opt-' + Date.now(),
            body,
            sender_id: authId,
            created_at: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
        };
        renderMessage(optimistic);
        input.value = '';
        sendBtn.disabled = true;

        try {
            const res = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ body })
            });

            if (!res.ok) throw new Error('Send failed');

            const data = await res.json();
            // Update optimistic message id
            const optEl = document.querySelector(`[data-msg-id="opt-${optimistic.id.split('-')[1]}"]`) 
                        || container.lastElementChild;
            if (optEl && data.id) optEl.setAttribute('data-msg-id', data.id);

        } catch (err) {
            // Fallback to standard form submission
            form.submit();
        } finally {
            sendBtn.disabled = false;
            input.focus();
        }
    });

    // Enter to send
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            form.dispatchEvent(new Event('submit'));
        }
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/ubuntu/Desktop/finnwayy/resources/views/chat/show.blade.php ENDPATH**/ ?>