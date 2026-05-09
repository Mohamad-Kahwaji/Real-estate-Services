<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    /** @use HasFactory<\Database\Factories\ReportFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'service_id',
        'reason',
        'message',
        'status',
    ];
    public function service(){
        return $this->belongsTo(Service::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
