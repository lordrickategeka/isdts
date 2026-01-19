<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DepartmentPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions for department management
        $permissions = [
            'view_departments',
            'create_departments',
            'edit_departments',
            'delete_departments',
            'manage_department_hierarchy',
            'assign_department_managers',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        // Admin role gets all permissions
        $adminRole = Role::where('name', 'super_admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // Manager role gets view, create, and edit permissions
        $managerRole = Role::where('name', 'Manager')->first();
        if ($managerRole) {
            $managerRole->givePermissionTo([
                'view_departments',
                'create_departments',
                'edit_departments',
            ]);
        }

        // HR or similar roles could get all department permissions
        $hrRole = Role::where('name', 'HR')->first();
        if ($hrRole) {
            $hrRole->givePermissionTo($permissions);
        }

        $this->command->info('Department permissions created and assigned successfully!');
    }
}
