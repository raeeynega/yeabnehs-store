<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'long_description', 'image',
        'type', 'duration', 'max_participants', 'price',
        'compare_at_price', 'features', 'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function hasDiscount(): bool
    {
        return $this->compare_at_price && $this->compare_at_price > $this->price;
    }

    public function discountPercent(): int
    {
        if (!$this->hasDiscount()) return 0;
        return (int) round((1 - $this->price / $this->compare_at_price) * 100);
    }

    public function coverImage(): string
    {
        return $this->image ?: 'https://placehold.co/800x500/0a0a0a/c8ff00?text=' . urlencode($this->title);
    }

    public function getFeaturesArray(): array
    {
        if (!$this->features) return [];
        $decoded = json_decode($this->features, true);
        return is_array($decoded) ? $decoded : [$this->features];
    }

    public function typeIcon(): string
    {
        return match($this->type) {
            '1-on-1' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>',
            'group' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
            'online' => '<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>',
            default => '',
        };
    }

    public function typeLabel(): string
    {
        return match($this->type) {
            '1-on-1' => '1-on-1 Training',
            'group' => 'Group Training',
            'online' => 'Online Training',
            default => $this->type,
        };
    }
}
