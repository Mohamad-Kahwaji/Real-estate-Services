<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceFieldValue extends Model
{
    protected $fillable = [
        'service_id',
        'dynamic_field_id',
        'value',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function dynamicField()
    {
        return $this->belongsTo(DynamicField::class);
    }
}
