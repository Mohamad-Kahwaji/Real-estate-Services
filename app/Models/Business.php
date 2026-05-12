<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    /** @use HasFactory<\Database\Factories\BusinessFactory> */
    use HasFactory;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function service(){
        return $this->hasMany(Service::class);
    }
    public function activeType(){
        return $this->belongsTo(Activetype::class,'activetype_id');
    }

    protected $fillable = [
      'job_name_ar',
      'job_name_en',
      'license_number',
      'activites',
      'details',
      'city_id',
      'image',
      'status',
      'activetype_id',
      'user_id',
      'latitude',
      'longitude',
    ];
}
