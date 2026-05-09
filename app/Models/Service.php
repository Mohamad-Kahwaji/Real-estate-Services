<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory;

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function order(){
        return $this->hasMany(Order::class);
    }
    public function review(){
        return $this->hasMany(Review::class);
    }
    public function user(){
        return $this->hasMany(User::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    public function request(){
      return $this->hasMany(ServiceRequest::class);
    }
    public function favorite(){
return $this->belongsToMany(User::class, 'favorites', 'service_id', 'user_id');    }

public function report(){
    return $this->hasMany(Report::class);
}
public function fieldValues()
{
    return $this->hasMany(ServiceFieldValue::class);
}

    protected $fillable = [
        'business_id',
        'category_id',
        'subcategory_id',
        'title',
        'description',
        'quantity',
        'services_type',
        'image',
        'currency',
        'status',
        'latitude',
        'longitude',
        'price_usd',
        'price_syp',


    ];



}
