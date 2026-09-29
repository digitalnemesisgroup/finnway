<?php

namespace Database\Seeders;

use App\Models\PaymentClient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * PaymentClientSeeder
 *
 * Seeds the FIINWAY internal marketplace as Payment Hub Client #1.
 * External apps should be added via the admin panel or manually in the DB.
 *
 * The api_key and api_salt are generated once and stable (uses firstOrCreate
 * so re-running the seeder never overwrites existing credentials).
 */
class PaymentClientSeeder extends Seeder
{
    public function run(): void
    {
        // ── Client #1: FIINWAY Internal Marketplace ───────────────────────────
        PaymentClient::firstOrCreate(
            ['name' => 'FIINWAY Internal'],
            [
                'api_key'   => 'fiinway_internal_' . Str::random(16),
                'api_salt'  => Str::random(64),
                'is_active' => true,
            ]
        );

        $this->command->info('✅ Payment Hub: FIINWAY Internal client seeded.');
        $this->command->line('');
        $this->command->line('Add external app clients manually:');
        $this->command->line('  INSERT INTO payment_clients (name, api_key, api_salt, is_active) VALUES (...)');
    }
}
