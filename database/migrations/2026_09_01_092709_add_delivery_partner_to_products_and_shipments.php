<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('delivery_partner_type')->default('seller')->after('delivery_type'); 
            // values: 'seller', 'fiinway', 'third_party'
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->string('delivery_partner_type')->default('seller')->after('seller_id');
            $table->string('delivery_request_id')->nullable()->after('delivery_partner_type');
            $table->string('driver_name')->nullable()->after('delivery_request_id');
            $table->string('driver_phone')->nullable()->after('driver_name');
            $table->timestamp('pickup_time')->nullable()->after('expected_delivery');
            $table->timestamp('delivery_time')->nullable()->after('pickup_time');
            $table->string('delivery_proof_image')->nullable()->after('delivery_time');
            $table->string('delivery_otp')->nullable()->after('delivery_proof_image');
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_partner_type', 'delivery_request_id', 'driver_name', 
                'driver_phone', 'pickup_time', 'delivery_time', 'delivery_proof_image', 'delivery_otp'
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('delivery_partner_type');
        });
    }
};
