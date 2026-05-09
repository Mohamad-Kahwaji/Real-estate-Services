<?php

namespace App\Services;

use App\Models\User;

/**
 * Class UserService.
 */
class UserService
{
  public function index(){
    $users = User::get();
    return $users; 
  }

}
