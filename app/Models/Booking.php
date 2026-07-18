<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_number', 'trainable_type', 'trainable_id',
        'customer_name', 'customer_email', 'customer_phone',
        'message', 'preferred_date', 'preferred_time',
        'status', 'total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function trainable(): MorphTo
    {
        return $this->morphTo();
    }
}
