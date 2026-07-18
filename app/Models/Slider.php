<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'subtitle', 'image', 'link', 'button_text', 'is_active', 'sort_order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
