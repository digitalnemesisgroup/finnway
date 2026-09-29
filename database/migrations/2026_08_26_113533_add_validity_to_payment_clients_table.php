<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payment_clients', function (Blueprint $table) {
            // API validity window - set upon approval
            $table->timestamp('starts_at')->nullable()->after('approval_status');
            $table->timestamp('expires_at')->nullable()->after('starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('payment_clients', function (Blueprint $table) {
            $table->dropColumn(['starts_at', 'expires_at']);
        });
    }
};
