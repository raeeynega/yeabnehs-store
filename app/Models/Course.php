<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'long_description', 'image',
        'level', 'duration_weeks', 'lessons_count', 'price',
        'compare_at_price', 'is_featured', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(CourseLesson::class)->orderBy('sort_order');
    }

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

    public function levelColor(): string
    {
        return match($this->level) {
            'beginner' => 'bg-green-100 text-green-700',
            'intermediate' => 'bg-yellow-100 text-yellow-700',
            'advanced' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}
