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
        Schema::table('category_fields', function (Blueprint $table) {
            $table->string('input_type')->default('text')->after('is_required'); // text, number, select
            $table->integer('min_val')->nullable()->after('input_type');
            $table->integer('max_val')->nullable()->after('min_val');
            $table->text('options')->nullable()->after('max_val'); // JSON array or comma separated
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_fields', function (Blueprint $table) {
            $table->dropColumn(['input_type', 'min_val', 'max_val', 'options']);
        });
    }
};
