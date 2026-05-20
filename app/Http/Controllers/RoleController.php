<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // List all roles with their assigned permissions for the admin panel.
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('super_admin.roles', compact('roles', 'permissions'));
    }

    // Show the form to create a new role.
    public function create()
    {
        $permissions = Permission::get();

        return view('super_admin.rolecreate', compact('permissions'));
    }

    // Validate and persist a new role with its selected permissions.
    public function store(Request $request)
    {
        $val = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $val['name'],
            'guard_name' => 'admin',
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roleindex')->with('success', 'Role created successfully.');
    }

    // Show the edit form for a specific admin-guard role.
    public function edit($id)
    {
        $role = Role::with('permissions')
            ->where('guard_name', 'admin')
            ->findOrFail($id);

        $permissions = Permission::where('guard_name', 'admin')->get();

        return view('roles.edit', compact('role', 'permissions'));
    }

    // Validate and apply updates to a role's name and permissions.
    public function update(Request $request, $id)
    {
        $role = Role::where('guard_name', 'admin')->findOrFail($id);

        $val = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update([
            'name' => $val['name'],
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    // Permanently delete an admin-guard role.
    public function destroy($id)
    {
        $role = Role::where('guard_name', 'admin')->findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
