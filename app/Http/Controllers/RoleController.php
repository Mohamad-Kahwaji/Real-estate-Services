<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')
            ->get();

        return view('super_admin.roles', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::get();

        return view('super_admin.rolecreate', compact('permissions'));
    }

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

        return redirect()->route('roles')->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        $role = Role::with('permissions')
            ->where('guard_name', 'admin')
            ->findOrFail($id);

        $permissions = Permission::where('guard_name', 'admin')->get();

        return view('roles.edit', compact('role', 'permissions'));
    }

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

    public function destroy($id)
    {
        $role = Role::where('guard_name', 'admin')->findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }
}
