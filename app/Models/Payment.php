<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_number', 'order_id', 'method', 'status', 'amount',
        'account_number', 'transaction_ref', 'sender_name', 'sender_phone',
        'notes', 'verified_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function methodName(): string
    {
        return match($this->method) {
            'cbe' => 'Commercial Bank of Ethiopia (CBE)',
            'telebirr' => 'Telebirr',
            default => $this->method,
        };
    }

    public function statusColor(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-700',
            'verified' => 'bg-green-100 text-green-700',
            'failed' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
