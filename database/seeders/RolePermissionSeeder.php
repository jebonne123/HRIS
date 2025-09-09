<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view_dashboard',
            'manage_employees',
            'view_employees',
            'create_employees',
            'edit_employees',
            'delete_employees',
            'manage_departments',
            'view_departments',
            'create_departments',
            'edit_departments',
            'delete_departments',
            'manage_shifts',
            'view_shifts',
            'create_shifts',
            'edit_shifts',
            'delete_shifts',
            'manage_attendance',
            'view_attendance',
            'create_attendance',
            'edit_attendance',
            'delete_attendance',
            'manage_payroll',
            'view_payroll',
            'create_payroll',
            'edit_payroll',
            'delete_payroll',
            'manage_reports',
            'view_reports',
            'manage_settings',
            'view_settings',
            'edit_settings',
            'manage_roles',
            'view_roles',
            'create_roles',
            'edit_roles',
            'delete_roles',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $hrRole = Role::create(['name' => 'hr']);
        $payrollRole = Role::create(['name' => 'payroll']);
        $employeeRole = Role::create(['name' => 'employee']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(Permission::all());

        $hrRole->givePermissionTo([
            'view_dashboard',
            'manage_employees',
            'view_employees',
            'create_employees',
            'edit_employees',
            'view_departments',
            'view_shifts',
            'manage_attendance',
            'view_attendance',
            'create_attendance',
            'edit_attendance',
            'view_payroll',
            'view_reports',
            'view_settings',
        ]);

        $payrollRole->givePermissionTo([
            'view_dashboard',
            'view_employees',
            'view_departments',
            'view_shifts',
            'view_attendance',
            'manage_payroll',
            'view_payroll',
            'create_payroll',
            'edit_payroll',
            'view_reports',
        ]);

        $employeeRole->givePermissionTo([
            'view_dashboard',
            'view_attendance',
        ]);
    }
}




