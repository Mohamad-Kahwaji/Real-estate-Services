<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $auth_service)
    {

    }
    public function register(RegisterRequest $request){
      $response = $this->auth_service->register($request->validated());
      return $this->successResponse(data::$data);
    }

    public function login(LoginRequest $request){
      $response = $this->auth_service->login($request->validated());
      if($response == "failed"){
        return response()->json([
          'message'=>"unauth",
          'data'=>[],
        ]);
      }
      return $this->successResponse(data:$response);
    }
}
