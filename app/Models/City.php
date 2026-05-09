<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;

    public function business(){
        return $this->hasMany(Business::class);
    }

    protected $fillable = [
      'name_ar',
      'name_en'
    ];
}
