<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    protected $fillable = [
        'user_id',
        'service_id',
        'business_id',
        'status',
        'image',
        'quantity',
        'needed_at',
        'details',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
