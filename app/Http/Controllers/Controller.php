<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function successResponse($message,$data,$statuscode){
      return response()->json([
        'message'=>$message,
        'data'=>$data,
      ],$statuscode);
    }
}
