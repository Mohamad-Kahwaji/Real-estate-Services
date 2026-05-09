<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    /** @use HasFactory<\Database\Factories\ReviewFactory> */
    use HasFactory;

    public function order(){
        return $this->hasOne(Order::class);
    }
    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function user(){
      return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'service_id',
        'rating',
        'comment',
        'user_id',
        'order_id',
    ];
}
