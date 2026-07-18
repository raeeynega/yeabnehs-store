<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'subject_type', 'subject_id',
        'description', 'ip_address', 'user_agent', 'url',
        'method', 'properties', 'risk_level',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    public function riskColor(): string
    {
        return match($this->risk_level) {
            'low' => 'bg-green-100 text-green-700',
            'medium' => 'bg-yellow-100 text-yellow-700',
            'high' => 'bg-orange-100 text-orange-700',
            'critical' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }
}
