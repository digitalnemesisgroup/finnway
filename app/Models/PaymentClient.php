<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentClient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'api_key',
        'api_salt',
        'is_active',
        'user_id',
        'legal_name',
        'business_type',
        'pan_number',
        'gstin',
        'registration_number',
        'category',
        'description',
        'website_url',
        'registered_address',
        'operating_address',
        'business_email',
        'business_mobile',
        'expected_monthly_volume',
        'expected_avg_value',
        'purpose',
        'gateway_charge_percent',
        'gst_on_charge_percent',
        'approval_status',
        'starts_at',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** True when the API key has passed its expiry date */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /** True when the key is usable right now (active + approved + not expired) */
    public function isLive(): bool
    {
        return $this->is_active
            && $this->approval_status === 'approved'
            && !$this->isExpired();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function paymentRequests()
    {
        return $this->hasMany(PaymentRequest::class);
    }
}
