<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── payment_clients ───────────────────────────────────────────────────
        // Every app (internal or external) that uses this Payment Hub is a "client".
        // Client #1 = FIINWAY's internal marketplace (seeded automatically).
        Schema::create('payment_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');               // "FIINWAY Internal", "Partner App XYZ"
            $table->string('api_key')->unique();  // Sent in every request header
            $table->string('api_salt');           // Used to verify HMAC signature
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ── payment_requests ──────────────────────────────────────────────────
        // Every payment initiated through the Hub — from any client — lives here.
        // This is the single source of truth the webhook reads from.
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_client_id')->constrained('payment_clients')->cascadeOnDelete();

            // Client-side reference (their own Order ID / reference)
            $table->string('client_order_id');

            // Payment details
            $table->decimal('amount', 12, 2);
            $table->string('currency', 10)->default('INR');
            $table->string('customer_phone', 20)->nullable();
            $table->string('customer_email')->nullable();

            // Where to send the user after Cashfree checkout completes
            $table->string('return_url');

            // Where to POST the server-to-server confirmation (signed with api_salt)
            $table->string('webhook_url');

            // Cashfree-specific data
            // Format: hub_{payment_request_id}_{random5} — never clashes with internal cf_ prefix
            $table->string('cashfree_order_id')->unique()->nullable();
            $table->text('cashfree_session_id')->nullable();

            // Status lifecycle: pending → success | failed | user_dropped
            $table->string('payment_status')->default('pending');
            $table->string('transaction_id')->nullable();   // cf_payment_id from Cashfree

            // Idempotency guard: ensures we never fire the client webhook twice
            $table->boolean('webhook_dispatched')->default(false);

            $table->timestamps();

            // A client can only have ONE payment request per client_order_id
            $table->unique(['payment_client_id', 'client_order_id'], 'uq_client_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
        Schema::dropIfExists('payment_clients');
    }
};
