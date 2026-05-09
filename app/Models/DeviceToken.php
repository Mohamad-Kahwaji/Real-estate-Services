<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceToken extends Model
{
  protected $table = 'device_tokens';
    protected $fillable = [
        'user_id',
        'device_token',
        'platform',
        'admin_id',
        'super_admin_id',
        'token',
    ];


}
