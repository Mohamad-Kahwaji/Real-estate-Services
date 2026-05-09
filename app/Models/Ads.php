<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    /** @use HasFactory<\Database\Factories\AdsFactory> */
    use HasFactory;

    protected $fillable = [
        'description',
        'title',
        'image',
        'is_active',
    ];
}
