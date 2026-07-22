<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class AdminRolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage dashboard',
            'manage site info',
            'manage about',
            'manage sliders',
            'manage subscriptions',
            'manage contacts',
            'manage users',
            'manage agencies',
            'manage agents',
            'manage properties',
            'manage property types',
            'manage amenities',
            'manage locations',
            'manage divisions',
            'manage districts',
            'manage areas',
            'manage blog',
            'manage blog categories',
            'manage blog posts',
            'manage blog comments',
            'manage blog page settings',
            'manage roles',
            'manage permissions',
            'assign admin roles',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'admin');
        }

        $role = Role::findOrCreate('Super Admin', 'admin');
        $role->syncPermissions(Permission::where('guard_name', 'admin')->pluck('name')->all());

        Admin::query()->each(function (Admin $admin) use ($role) {
            if (! $admin->hasRole($role)) {
                $admin->assignRole($role);
            }
        });
    }
}
