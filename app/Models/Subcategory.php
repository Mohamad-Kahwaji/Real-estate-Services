<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    /** @use HasFactory<\Database\Factories\SubcategoryFactory> */
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function dynamicFields()
    {
        return $this->hasMany(DynamicField::class);
    }

    protected $fillable = [
      'name_ar',
      'name_en',
      'category_id',
      'fields',
      ''

    ];
}
