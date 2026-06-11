<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'manage users',
            'manage roles',
            'manage content',
            'manage schedule',
            'manage gallery',
            'view admin panel',
            'view own profile',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            UserRole::Developer->value => $permissions,
            UserRole::Admin->value => [
                'manage users',
                'manage roles',
                'manage content',
                'manage schedule',
                'manage gallery',
                'view admin panel',
                'view own profile',
            ],
            UserRole::Teacher->value => [
                'manage content',
                'manage schedule',
                'view admin panel',
                'view own profile',
            ],
            UserRole::Student->value => [
                'view own profile',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        $developer = User::firstOrCreate(
            ['phone' => env('DEV_ADMIN_PHONE', '998901234567')],
            [
                'name' => 'Developer',
                'email' => env('DEV_ADMIN_EMAIL', 'developer@maktab.uz'),
                'password' => Hash::make(env('DEV_ADMIN_PASSWORD', 'password')),
                'phone_verified_at' => now(),
                'auth_provider' => 'phone',
                'is_active' => true,
            ]
        );

        if (! $developer->hasRole(UserRole::Developer->value)) {
            $developer->assignRole(UserRole::Developer->value);
        }
    }
}
