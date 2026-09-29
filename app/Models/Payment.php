<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'gateway',
        'gateway_order_id',
        'gateway_payment_id',
        'amount',
        'currency',
        'method',
        'transaction_id',
        'status',
        'signature',
        'gateway_response',
        'metadata',
        'paid_at',
        'refunded_at',
        'session_token',
        'open_count',
        'max_opens',
        'expires_at',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'gateway_response' => 'array',
            'metadata' => 'array',
            'paid_at' => 'datetime',
            'refunded_at' => 'datetime',
            'expires_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function isSessionExpired(): bool
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }

    public function hasExceededMaxOpens(): bool
    {
        return $this->open_count > ($this->max_opens ?? 2);
    }

    public function isSessionClosed(): bool
    {
        return !is_null($this->closed_at) || in_array($this->status, ['user_dropped', 'failed', 'rejected', 'cancelled']);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
