<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // =========================
        // Superadmin Permissions
        // =========================
        $superadminPermissions = [
            'view analytics',
            'add role',
            'edit role',
            'delete role',
            'set role',
            'add new admin',
            'edit permission admin',
        ];

        foreach ($superadminPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'superadmins',
            ]);
        }

        $superadminRole = Role::firstOrCreate([
            'name' => 'superadmins',
            'guard_name' => 'superadmins',
        ]);

        $superadminRole->syncPermissions($superadminPermissions);


        // =========================
        // Admin Permissions
        // =========================
        $adminPermissions = [
            'accept business account',
            'reject business account',
            'pending service',
            'accept service',
            'reject service',
            'add category',
            'edit category',
            'delete category',
            'add subcategory',
            'edit subcategory',
            'delete subcategory',
            'add dynamic field',
            'edit dynamic field',
            'delete dynamic field',
            'manage slider ads',
            'add city',
            'manage report',
        ];

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'admins',
            ]);
        }

        $adminRole = Role::firstOrCreate([
            'name' => 'admins',
            'guard_name' => 'admins',
        ]);

        $adminRole->syncPermissions($adminPermissions);


        // =========================
        // User Permissions
        // =========================
        $userPermissions = [
            'create user account',
            'login',
            'logout',
            'edit profile',
            'add business account',
            'edit business account',
            'view business accounts',
            'add service',
            'edit service',
            'delete service',
            'view services',
            'add order service',
            'view orders received',
            'view orders sent',
            'accept order service',
            'reject order service',
            'delete order service',
            'add rate',
            'add service to favorite',
            'delete service from favorite',
            'report service',
        ];

        foreach ($userPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'users',
            ]);
        }

        $userRole = Role::firstOrCreate([
            'name' => 'users',
            'guard_name' => 'users',
        ]);

        $userRole->syncPermissions($userPermissions);
    }
}

