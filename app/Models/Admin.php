<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\DeviceToken;

class Admin extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\AdminFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'last_seen_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password'    => 'hashed',
            'is_active'   => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }
    public function devicetokens(){
        return $this->hasMany(DeviceToken::class,'admin_id');
    }
    protected string $guard_name = 'admins';
}
