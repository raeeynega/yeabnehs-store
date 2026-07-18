<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'event_type', 'severity', 'description',
        'ip_address', 'user_agent', 'metadata',
        'resolved', 'resolved_by', 'resolved_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function severityColor(): string
    {
        return match($this->severity) {
            'low' => 'bg-blue-100 text-blue-700',
            'medium' => 'bg-yellow-100 text-yellow-700',
            'high' => 'bg-orange-100 text-orange-700',
            'critical' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function scopeUnresolved($query)
    {
        return $query->where('resolved', false);
    }
}
