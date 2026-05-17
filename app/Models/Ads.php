<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    /** @use HasFactory<\Database\Factories\AdsFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'link',
        'is_active',
    ];

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) return asset('assets/img/elements/5.png');
        if (str_starts_with($this->image, 'http')) return $this->image;
        return asset('storage/' . $this->image);
    }
}
