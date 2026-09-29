<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->decimal('other_fee', 10, 2)->default(0.00)->after('commission_value');
            $table->string('delivery_responsibility')->default('seller')->after('other_fee');
            $table->string('settlement_type')->default('automatic')->after('delivery_responsibility');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['other_fee', 'delivery_responsibility', 'settlement_type']);
        });
    }
};
