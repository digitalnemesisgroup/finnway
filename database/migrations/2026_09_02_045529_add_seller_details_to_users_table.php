<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('seller_type')->nullable()->after('is_seller');
            $table->decimal('seller_wallet_balance', 12, 2)->default(0)->after('wallet_balance');
            $table->string('business_name')->nullable()->after('seller_type');
            $table->string('gst_number')->nullable()->after('business_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['seller_type', 'seller_wallet_balance', 'business_name', 'gst_number']);
        });
    }
};
