<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments') && Schema::hasColumn('payments', 'session_token')) {
            try {
                DB::statement('ALTER TABLE payments MODIFY COLUMN session_token TEXT NULL');
            } catch (\Throwable $e) {
                // Ignore if not MySQL or column missing
            }
        }

        if (Schema::hasTable('payment_requests') && Schema::hasColumn('payment_requests', 'session_token')) {
            try {
                DB::statement('ALTER TABLE payment_requests MODIFY COLUMN session_token TEXT NULL');
            } catch (\Throwable $e) {
                // Ignore if not MySQL or column missing
            }
        }
    }

    public function down(): void
    {
    }
};

