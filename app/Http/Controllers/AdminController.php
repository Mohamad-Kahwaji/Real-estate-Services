<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Service;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{
     public function showLoginForm()
    {
        return view('login-admin');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }



    public function index(){
        $admin = Auth::guard('admins')->user();

        $pendingBusinesses  = Business::where('status', 'pending')->count();
        $totalBusinesses    = Business::count();
        $approvedBusinesses = Business::where('status', 'approved')->count();

        $totalCategories    = Category::count();
        $totalSubcategories = Subcategory::count();
        $totalCities        = City::count();

        $pendingServices    = Service::where('status', 'pending')->count();
        $totalServices      = Service::count();

        $recentPending = Business::with('user','city')
            ->where('status','pending')
            ->latest()->take(8)->get();

        return view('admin.index', compact(
            'admin',
            'pendingBusinesses','totalBusinesses','approvedBusinesses',
            'totalCategories','totalSubcategories','totalCities',
            'pendingServices','totalServices',
            'recentPending'
        ));
    }

    public function create(){
      $permissions = Permission::where('guard_name','admins')->get();
      return view('super_admin.createadmin',compact('permissions'));
    }


    public function store(Request $request){
      $val = $request->validate([
        'name'=> 'required|string|max:255',
        'email'=>'required|string|max:20',
        'password'=>'required|string|min:8|confirmed',
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,name',
        ]);
        $admin = Admin::create([
        'name' => $val['name'],
        'email' => $val['email'],
        'password' => Hash::make($val['password']),
    ]);
    if (!empty($val['permissions'])) {
        $admin->syncPermissions($val['permissions']);
    }
    return redirect()->route('adminsindex');
    }

    public function editpermission($id){
   $admin = Admin::findOrFail($id);
    $permissions = Permission::where('guard_name', 'admins')->get();


    return view('super_admin.editadmin', compact('admin', 'permissions'));
    }


    public function update(Request $request, $admin)
{
    $admin = Admin::findOrFail($admin);

    $val = $request->validate([
        'name'        => 'required|string|max:255',
        'email'       => 'required|email|max:255',
        'password'    => 'nullable|string|min:8|confirmed',
        'permissions' => 'nullable|array',
        'permissions.*' => 'exists:permissions,name',
    ]);

    $data = [
        'name'  => $val['name'],
        'email' => $val['email'],
    ];

    if (!empty($val['password'])) {
        $data['password'] = Hash::make($val['password']);
    }

    $admin->update($data);
    $admin->syncPermissions($val['permissions'] ?? []);

    return redirect()->route('adminsindex')->with('success', 'Admin updated successfully');
}

    public function updateadmin(Request $request, $id)
{
    return $this->update($request, $id);
}

  public function status($id)
{
    $admin = Admin::findOrFail($id);
    $admin->is_active = !$admin->is_active;
    $admin->save();

    return redirect()->route('adminsindex');
}


    protected $guard_name = 'admin';





}
