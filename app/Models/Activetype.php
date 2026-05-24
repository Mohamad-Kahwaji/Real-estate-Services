<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activetype extends Model
{
    /** @use HasFactory<\Database\Factories\ActiveTypeFactory> */
    use HasFactory;

    protected $fillable = ['name', 'name_ar'];

    public function getLocalizedNameAttribute(): string
    {
        return app()->getLocale() === 'ar' && $this->name_ar
            ? $this->name_ar
            : $this->name;
    }
}
