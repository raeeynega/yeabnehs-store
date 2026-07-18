<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedIp extends Model
{
    use HasFactory;

    protected $fillable = ['ip_address', 'reason', 'blocked_by', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function blocker()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}
