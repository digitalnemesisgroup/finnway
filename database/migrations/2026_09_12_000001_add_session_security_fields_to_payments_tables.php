<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (!Schema::hasColumn('payments', 'session_token')) {
                    $table->text('session_token')->nullable();
                }
                if (!Schema::hasColumn('payments', 'open_count')) {
                    $table->integer('open_count')->default(0);
                }
                if (!Schema::hasColumn('payments', 'max_opens')) {
                    $table->integer('max_opens')->default(2);
                }
                if (!Schema::hasColumn('payments', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable();
                }
                if (!Schema::hasColumn('payments', 'closed_at')) {
                    $table->timestamp('closed_at')->nullable();
                }
            });
        }

        if (Schema::hasTable('payment_requests')) {
            Schema::table('payment_requests', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_requests', 'session_token')) {
                    $table->text('session_token')->nullable();
                }
                if (!Schema::hasColumn('payment_requests', 'open_count')) {
                    $table->integer('open_count')->default(0);
                }
                if (!Schema::hasColumn('payment_requests', 'max_opens')) {
                    $table->integer('max_opens')->default(2);
                }
                if (!Schema::hasColumn('payment_requests', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable();
                }
                if (!Schema::hasColumn('payment_requests', 'closed_at')) {
                    $table->timestamp('closed_at')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn(['session_token', 'open_count', 'max_opens', 'expires_at', 'closed_at']);
            });
        }

        if (Schema::hasTable('payment_requests')) {
            Schema::table('payment_requests', function (Blueprint $table) {
                $table->dropColumn(['session_token', 'open_count', 'max_opens', 'expires_at', 'closed_at']);
            });
        }
    }
};

