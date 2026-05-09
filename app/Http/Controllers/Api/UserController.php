<?php

namespace App\Http\Controllers;

use App\Models\Activetype;
use App\Models\Business;
use App\Models\City;
use App\Models\Service;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index(){
        //$users = User::get();
        //$businesses = Business::with('user')->findOrFail($id);
        //$cities = City::get();
        //$activetypes = Activetype::get();
        $user = auth('users')->user();
        //$user = $this->user_service->index();
        return view('pages.pages-account-settings-account',compact('user'));
    }
    public function edit(){
        //$users = User::get();
        //$businesses = Business::with('user')->findOrFail($id);
        //$cities = City::get();
        //$activetypes = Activetype::get();
        $user = auth()->user();
        return view('pages.pages-account-settings-account',compact('user'));
    }

    /*public function update(Request $request,$id){
        $val = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            //'user_id'=>'required|exists:users,id',
            'activetype' => 'required|boolean',
            'license_number' => 'required|integer',
            'job_name_ar' => 'required|string|max:255',
            'job_name_en' => 'required|string|max:255',
            'activites' => 'required|string|max:255',
            'details' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            //'role_id'=>'required|exists:roles,id',
        ]);
        $business = Business::findOrFail($id);
        $user = User::findOrFail($business->user_id);
        $business->update([
        'active' => $val['active'],
        'license_number' => $val['license_number'],
        'job_name_ar' => $val['job_name_ar'],
        'job_name_en' => $val['job_name_en'],
        'activites' => $val['activites'],
        'details' => $val['details'],
        'city_id' => $val['city_id'],
        'image' => $val['image'] ?? $business->image,
        ]);

        $userData = [
        'name' => $val['name'],
        'phone' => $val['phone'],
        'email' => $val['email'],
        ];
        return redirect()->route('allindex.edit',$business->id);
    }*/

    public function dash(){
      //$user = User::where('id',auth()->id())->get();
      $user = auth('users')->user();
      $services = Service::all();
      return view('users.index',compact('user','services'));
    }

    public function update(Request $request){
      $user = auth('users')->user();
      $val = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',]);
         // $user = User::findOrFail($id);
          $user->update( [
        'name' => $val['name'],
        'phone' => $val['phone'],
        'email' => $val['email'],
        ]);

        return redirect()->route('dash');
    }



  public function __construct(protected UserService $user_service)
  {
   // throw new \Exception('Not implemented');
  }
}
