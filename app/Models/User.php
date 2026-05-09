<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Service;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',

        'phone',
        'password',
        'fcm_token'
    ];
    protected string $guard_namre = 'users';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'number_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function order(){
        return $this->hasMany(Order::class);
    }
    public function review(){
        return $this->hasMany(Review::class);
    }
    public function favorites(){
      return $this->belongsToMany(Service::class, 'favorites', 'user_id', 'service_id');
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function service(){
        return $this->hasMany(Service::class);
    }
    public function report(){
        return $this->hasMany(Report::class);
    }
    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
}

}
