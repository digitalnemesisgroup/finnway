<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->string('seller_type')->default('Retailer')->after('user_id');
            $table->string('model')->nullable()->after('brand');
        });
    }
    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['seller_type', 'model']);
        });
    }
};
