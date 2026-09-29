<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            // SQLite doesn't support modifyColumn with foreign keys directly; rebuild approach
            DB::statement('PRAGMA foreign_keys=OFF');
            DB::statement('CREATE TABLE reviews_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                product_id INTEGER NOT NULL,
                user_id INTEGER NOT NULL,
                order_id INTEGER NULL,
                rating INTEGER NOT NULL,
                comment TEXT,
                is_approved INTEGER NOT NULL DEFAULT 1,
                title VARCHAR NULL,
                created_at DATETIME,
                updated_at DATETIME
            )');
            DB::statement('INSERT INTO reviews_new (id, product_id, user_id, order_id, rating, comment, is_approved, created_at, updated_at)
                SELECT id, product_id, user_id, order_id, rating, comment, is_approved, created_at, updated_at FROM reviews');
            DB::statement('DROP TABLE reviews');
            DB::statement('ALTER TABLE reviews_new RENAME TO reviews');
            DB::statement('PRAGMA foreign_keys=ON');
        } else {
            // For MySQL (Hostinger) we can just do a standard ALTER
            DB::statement('ALTER TABLE reviews MODIFY order_id BIGINT UNSIGNED NULL');
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE reviews MODIFY order_id BIGINT UNSIGNED NOT NULL');
        }
    }
};
