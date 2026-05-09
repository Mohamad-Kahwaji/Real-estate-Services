<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles')->get();
        $users = User::with('roles')->get();
        return view('super_admin.permissioncreate', compact('permissions', 'users'));
    }

    public function create()
    {
        $permissions = Role::where('guard_name', 'admin')->get();

        return view('super_admin.permissioncreate', compact('permissions'));
    }

    public function store(Request $request)
    {
        $val = $request->validate([
            'name' => 'required|string|max:255',
            'guard_name' => 'required|in:admin,web',
            'roles' => 'nullable|array',
        ]);

        $permission = Permission::create([
            'name' => $val['name'],
            'guard_name' => $val['guard_name'],
        ]);
        if (!empty($val['roles'])) {
            $roles = Role::whereIn('name', $val['roles'])
                ->where('guard_name', $val['guard_name'])
                ->get();

            foreach ($roles as $role) {
                $role->givePermissionTo($permission);
            }
        }

        return redirect()->route('roleindex')->with('success', 'Permission created successfully');
    }
    public function destroy($id){
      Role::findOrFail($id)->delete();
      return redirect()->route('roleindex');
    }
}
