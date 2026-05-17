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
        // Superadmin — ثابت، لا تغيير
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
                'name'       => $permission,
                'guard_name' => 'superadmins',
            ]);
        }

        $superadminRole = Role::firstOrCreate([
            'name'       => 'superadmins',
            'guard_name' => 'superadmins',
        ]);

        $superadminRole->syncPermissions($superadminPermissions);


        // =========================
        // Admin Permissions — كل الصلاحيات المتاحة للـ admins
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
                'name'       => $permission,
                'guard_name' => 'admins',
            ]);
        }

        // =========================
        // Admin Roles — كل role مجموعة مهام محددة
        // =========================
        $adminRoles = [

            'service-manager' => [
                'pending service',
                'accept service',
                'reject service',
            ],

            'business-manager' => [
                'accept business account',
                'reject business account',
            ],

            'category-manager' => [
                'add category',
                'edit category',
                'delete category',
                'add subcategory',
                'edit subcategory',
                'delete subcategory',
                'add dynamic field',
                'edit dynamic field',
                'delete dynamic field',
            ],

            'city-manager' => [
                'add city',
            ],

            'report-manager' => [
                'manage report',
            ],

            'ads-manager' => [
                'manage slider ads',
            ],

        ];

        foreach ($adminRoles as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name'       => $roleName,
                'guard_name' => 'admins',
            ]);
            $role->syncPermissions($permissions);
        }


        // =========================
        // User Permissions — ثابت، role واحد لكل المستخدمين
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
                'name'       => $permission,
                'guard_name' => 'users',
            ]);
        }

        $userRole = Role::firstOrCreate([
            'name'       => 'users',
            'guard_name' => 'users',
        ]);

        $userRole->syncPermissions($userPermissions);
    }
}
