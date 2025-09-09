<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Create or update the admin user
        $user = User::firstOrCreate(
            ['email' => 'admin@hris.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Ensure all permissions are assigned to admin role
        $allPermissions = Permission::all();
        if ($allPermissions->isNotEmpty()) {
            $adminRole->syncPermissions($allPermissions);
            // Also sync directly on user to be safe
            $user->syncPermissions($allPermissions);
        }

        // Assign role to user (idempotent)
        if (!$user->hasRole($adminRole->name)) {
            $user->assignRole($adminRole);
        }
    }
}



