<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DynamicField extends Model
{
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'label',
        'label_ar',
        'label_en',
        'type',
        'is_required',
        'options'
    ];

    protected $casts = [
        'options' => 'array',
        'is_required' => 'boolean',
    ];

    public function values(){
        return $this->hasMany(ServiceFieldValue::class);
    }
}
