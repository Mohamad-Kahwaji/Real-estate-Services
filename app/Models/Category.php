<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory;

    public function subcategory()
    {
        return $this->hasMany(Subcategory::class);
    }
    public function dynamicFields()
    {
        return $this->hasMany(DynamicField::class);
    }

    protected $fillable = [
      'name_ar',
      'name_en'
    ];
}
