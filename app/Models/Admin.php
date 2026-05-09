<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use App\Models\DeviceToken;

class Admin extends Model
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory,HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];
    public function devicetokens(){
        return $this->hasMany(DeviceToken::class,'admin_id');
    }
    protected string $guard_name = 'admins';
    protected $casts = ['is_active'=>'boolean',];
}
