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
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    // Render the admin login page.
    public function showLoginForm()
    {
        return view('login-admin');
    }

    // Authenticate an admin and redirect to the dashboard on success.
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required','email'],
            'password' => ['required']
        ]);

        if (Auth::guard('admins')->attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    // Display the admin dashboard with business, category, and service statistics.
    public function index(){
        $admin = Auth::guard('admins')->user();

        if (!$admin) {
            if (Auth::guard('superadmins')->check()) {
                return redirect()->route('indexsuperadmin');
            }
            return redirect()->route('logina.create');
        }

        $pendingBusinesses  = Business::where('status', 'pending')->count();
        $totalBusinesses    = Business::count();
        $approvedBusinesses = Business::where('status', 'approved')->count();

        $totalCategories    = Category::count();
        $totalSubcategories = Subcategory::count();
        $totalCities        = City::count();

        $pendingServices    = Service::where('status', 'pending')->count();
        $totalServices      = Service::count();

        $notifications = $admin->notifications()->latest()->take(15)->get();
        $unreadCount   = $admin->unreadNotifications()->count();

        return view('admin.index', compact(
            'admin',
            'pendingBusinesses','totalBusinesses','approvedBusinesses',
            'totalCategories','totalSubcategories','totalCities',
            'pendingServices','totalServices',
            'notifications', 'unreadCount'
        ));
    }

    // Load the create-admin form with available permissions and roles.
    public function create(){
        $permissions = Permission::where('guard_name', 'admins')->get();
        $roles       = Role::where('guard_name', 'admins')->with('permissions')->get();
        return view('super_admin.createadmin', compact('permissions', 'roles'));
    }

    // Validate and persist a new admin along with their assigned roles and permissions.
    public function store(Request $request){
        $val = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'password'       => 'required|string|min:8|confirmed',
            'roles'          => 'nullable|array',
            'roles.*'        => 'exists:roles,name',
            'permissions'    => 'nullable|array',
            'permissions.*'  => 'exists:permissions,name',
        ]);

        $admin = Admin::create([
            'name'     => $val['name'],
            'email'    => $val['email'],
            'password' => Hash::make($val['password']),
        ]);

        $admin->syncRoles($val['roles'] ?? []);

        if (!empty($val['permissions'])) {
            $admin->givePermissionTo($val['permissions']);
        }

        return redirect()->route('adminsindex')->with('success', 'Admin created successfully.');
    }

    // Load the edit-permissions form for a specific admin.
    public function editpermission($id){
        $admin       = Admin::with('roles', 'permissions')->findOrFail($id);
        $permissions = Permission::where('guard_name', 'admins')->get();
        $roles       = Role::where('guard_name', 'admins')->with('permissions')->get();

        return view('super_admin.editadmin', compact('admin', 'permissions', 'roles'));
    }

    // Update an admin's profile, password, roles, and direct permissions.
    public function update(Request $request, $admin)
    {
        $admin = Admin::findOrFail($admin);

        $val = $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'password'       => 'nullable|string|min:8|confirmed',
            'roles'          => 'nullable|array',
            'roles.*'        => 'exists:roles,name',
            'permissions'    => 'nullable|array',
            'permissions.*'  => 'exists:permissions,name',
        ]);

        $data = ['name' => $val['name'], 'email' => $val['email']];
        if (!empty($val['password'])) {
            $data['password'] = Hash::make($val['password']);
        }
        $admin->update($data);

        $admin->syncRoles($val['roles'] ?? []);
        $admin->syncPermissions($val['permissions'] ?? []);

        return redirect()->route('adminsindex')->with('success', 'Admin updated successfully.');
    }

    // Proxy to update() to support route-based admin editing by ID.
    public function updateadmin(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    // Toggle the active/inactive status of an admin account.
    public function status($id)
    {
        $admin = Admin::findOrFail($id);
        $admin->is_active = !$admin->is_active;
        $admin->save();

        return redirect()->route('adminsindex');
    }

    // Mark all unread notifications as read for the authenticated admin.
    public function markAllNotificationsRead()
    {
        Auth::guard('admins')->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    protected $guard_name = 'admin';

    // Update the currently authenticated admin's name, email, and optionally their password.
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admins')->user();

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ];

        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required|string';
            $rules['new_password']     = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
        }

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('new_password')) {
            $data['password'] = Hash::make($validated['new_password']);
        }

        $admin->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Profile updated successfully.');
    }

}
