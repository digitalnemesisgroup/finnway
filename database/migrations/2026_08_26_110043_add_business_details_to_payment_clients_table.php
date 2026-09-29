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
        Schema::table('payment_clients', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('id');
            $table->string('legal_name')->nullable()->after('name');
            $table->string('business_type')->nullable(); // Individual, Partnership, LLP, etc.
            $table->string('pan_number')->nullable();
            $table->string('gstin')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->string('website_url')->nullable();
            $table->text('registered_address')->nullable();
            $table->text('operating_address')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_mobile')->nullable();
            $table->string('expected_monthly_volume')->nullable();
            $table->string('expected_avg_value')->nullable();
            $table->text('purpose')->nullable();
            $table->decimal('gateway_charge_percent', 5, 2)->default(0.00);
            $table->decimal('gst_on_charge_percent', 5, 2)->default(18.00);
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('approved');
            
            // Make api_key and api_salt nullable because they will only be generated upon approval
            $table->string('api_key')->nullable()->change();
            $table->string('api_salt')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_clients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id', 'legal_name', 'business_type', 'pan_number', 'gstin', 
                'registration_number', 'category', 'description', 'website_url', 
                'registered_address', 'operating_address', 'business_email', 
                'business_mobile', 'expected_monthly_volume', 'expected_avg_value', 
                'purpose', 'gateway_charge_percent', 'gst_on_charge_percent', 'approval_status'
            ]);
            
            // Revert api_key and api_salt to non-nullable (may fail if there are null records)
            $table->string('api_key')->nullable(false)->change();
            $table->string('api_salt')->nullable(false)->change();
        });
    }
};
