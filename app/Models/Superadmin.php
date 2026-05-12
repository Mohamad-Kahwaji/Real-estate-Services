<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\DeviceToken;
class Superadmin extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\SuperadminFactory> */
    use HasFactory, HasRoles, Notifiable;
    protected $table = 'superadmins';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'fcm_token'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function devicetokens(){
        return $this->hasMany(DeviceToken::class,'super_admin_id');
    }
    protected string $guard_name = 'superadmins';
}
