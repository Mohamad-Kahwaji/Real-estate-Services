<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // List all permissions, roles, and users for the permissions management page.
    public function index()
    {
        $permissions = Permission::with('roles')->get();
        $roles       = Role::where('guard_name', 'admins')->with('permissions')->get();
        $users       = User::with('roles')->get();
        return view('super_admin.permissioncreate', compact('permissions', 'roles', 'users'));
    }

    // Show the form for creating a new permission.
    public function create()
    {
        $permissions = Permission::with('roles')->get();
        $roles       = Role::where('guard_name', 'admins')->with('permissions')->get();

        return view('super_admin.permissioncreate', compact('permissions', 'roles'));
    }

    // Validate, create a permission, and assign it to the specified roles.
    public function store(Request $request)
    {
        $val = $request->validate([
            'name'       => 'required|string|max:255',
            'guard_name' => 'required|in:admins,users',
            'roles'      => 'nullable|array',
        ]);

        $permission = Permission::create([
            'name' => $val['name'],
            'guard_name' => $val['guard_name'],
        ]);
        if (!empty($val['roles'])) {
            /** @var Role $role */
            foreach (Role::whereIn('name', $val['roles'])->where('guard_name', $val['guard_name'])->get() as $role) {
                $role->givePermissionTo($permission);
            }
        }

        return redirect()->route('roleindex')->with('success', 'Permission created successfully.');
    }

    // Permanently delete a permission by ID.
    public function destroy(int $id)
    {
        Permission::findOrFail($id)->delete();
        return redirect()->route('roleindex')->with('success', 'Permission deleted.');
    }
}
