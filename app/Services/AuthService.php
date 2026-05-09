<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService.
 */
class AuthService
{
    public function register(array $data ){
      $user = User::query()->create($data);

    $token = $user->CreateToken('auth-token')->plainTextToken;
    return [
      'user'=>$user,
      'token'=>$token,
    ];
    }



    public function login(array $data){
      $user = User::query()->where('phone',$data['phone'])->first();
      if($user && Hash::check($data['password'], $user->password)){
        $token = $user->createToken('auth-token')->plainTextToken;
        return [
          'user'=>$user,
          'token'=>$token,
        ];
      }
      throw new AuthenticationException();
    }

    public function logout(array $data){
        auth()->user()->tokens()->delete();
        return $this->successResponse(); 
    }
}
