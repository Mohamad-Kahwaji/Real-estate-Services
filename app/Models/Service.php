<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    /** @use HasFactory<\Database\Factories\ServiceFactory> */
    use HasFactory, SoftDeletes;

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
        return $this->hasOneThrough(
            User::class,
            Business::class,
            'id',        // businesses.id (referenced by service.business_id)
            'id',        // users.id (referenced by business.user_id)
            'business_id', // service.business_id
            'user_id'    // business.user_id
        );
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

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return "https://picsum.photos/seed/svc{$this->id}/600/400";
        }
        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }

    public function getLocationNameAttribute(): string
    {
        $city = $this->business?->city;
        if (!$city) return __('app.unknown_location');
        return app()->getLocale() === 'ar' ? ($city->name_ar ?? $city->name_en) : $city->name_en;
    }



}
