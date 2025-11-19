<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProjectPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create permissions for projects
        $permissions = [
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'manage_project_budget',
            'approve_projects',
            'check_item_availability',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles

        // CTO Role
        $ctoRole = Role::firstOrCreate(['name' => 'CTO']);
        $ctoRole->givePermissionTo([
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'manage_project_budget',
            'approve_projects',
        ]);

        // Director Role
        $directorRole = Role::firstOrCreate(['name' => 'Director']);
        $directorRole->givePermissionTo([
            'view_projects',
            'create_projects',
            'edit_projects',
            'delete_projects',
            'manage_project_budget',
            'approve_projects',
        ]);

        // Planning Team Role
        $planningRole = Role::firstOrCreate(['name' => 'Planning Team']);
        $planningRole->givePermissionTo([
            'view_projects',
            'create_projects',
            'edit_projects',
            'manage_project_budget',
        ]);

        // Implementation Team Role
        $implementationRole = Role::firstOrCreate(['name' => 'Implementation Team']);
        $implementationRole->givePermissionTo([
            'view_projects',
            'check_item_availability',
        ]);

        // Stores Team Role
        $storesRole = Role::firstOrCreate(['name' => 'Stores Team']);
        $storesRole->givePermissionTo([
            'view_projects',
            'check_item_availability',
        ]);

        $this->command->info('Project permissions and roles created successfully!');
    }
}
