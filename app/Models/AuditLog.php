<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    protected $fillable = [
        'chain_index',
        'timestamp',
        'user_id',
        'action',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'request_url',
        'request_method',
        'risk_level',
        'previous_hash',
        'chain_hash',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'timestamp' => 'datetime',
    ];

    /**
     * Compute SHA-256 hash for this entry.
     * Hash chain: SHA256(previous_hash + timestamp + user_id + action + entity_type + entity_id + old_values + new_values)
     * This ensures immutability — any tampering breaks the chain.
     */
    public function computeHash(): string
    {
        $payload = json_encode([
            'previous_hash' => $this->previous_hash,
            'timestamp'     => $this->timestamp->toIso8601String(),
            'user_id'       => $this->user_id,
            'action'        => $this->action,
            'entity_type'   => $this->entity_type,
            'entity_id'     => $this->entity_id,
            'old_values'    => $this->old_values,
            'new_values'    => $this->new_values,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        return hash('sha256', $payload);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFromIndex($query, int $index)
    {
        return $query->where('chain_index', '>=', $index);
    }

    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('timestamp', '>=', now()->subHours($hours));
    }

    public function riskColor(): string
    {
        return match ($this->risk_level) {
            'low'      => 'bg-blue-100 text-blue-700',
            'medium'   => 'bg-yellow-100 text-yellow-700',
            'high'     => 'bg-orange-100 text-orange-700',
            'critical' => 'bg-red-100 text-red-700',
            default    => 'bg-gray-100 text-gray-700',
        };
    }
}
