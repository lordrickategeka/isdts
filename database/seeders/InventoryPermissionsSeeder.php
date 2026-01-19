<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InventoryPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            // Inventory Items
            'view_inventory',
            'create_inventory',
            'edit_inventory',
            'delete_inventory',
            'manage_inventory',

            // Inventory Locations
            'view_inventory_locations',
            'create_inventory_locations',
            'edit_inventory_locations',
            'delete_inventory_locations',
            'manage_inventory_locations',

            // Inventory Transactions
            'view_inventory_transactions',
            'create_inventory_transactions',
            'approve_inventory_transactions',

            // Stock Adjustments
            'view_stock_adjustments',
            'create_stock_adjustments',
            'approve_stock_adjustments',

            // Inventory Reports
            'view_inventory_reports',
            'export_inventory_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles

        // Admin gets all permissions
        $adminRole = Role::where('name', 'super_admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }

        // Manager gets most permissions except delete
        $managerRole = Role::where('name', 'Manager')->first();
        if ($managerRole) {
            $managerRole->givePermissionTo([
                'view_inventory',
                'create_inventory',
                'edit_inventory',
                'manage_inventory',
                'view_inventory_locations',
                'create_inventory_locations',
                'edit_inventory_locations',
                'view_inventory_transactions',
                'create_inventory_transactions',
                'approve_inventory_transactions',
                'view_stock_adjustments',
                'create_stock_adjustments',
                'approve_stock_adjustments',
                'view_inventory_reports',
                'export_inventory_reports',
            ]);
        }

        // Inventory Manager - specialized role for inventory management
        $inventoryRole = Role::firstOrCreate(['name' => 'Inventory Manager']);
        $inventoryRole->givePermissionTo([
            'view_inventory',
            'create_inventory',
            'edit_inventory',
            'manage_inventory',
            'view_inventory_locations',
            'create_inventory_locations',
            'edit_inventory_locations',
            'manage_inventory_locations',
            'view_inventory_transactions',
            'create_inventory_transactions',
            'view_stock_adjustments',
            'create_stock_adjustments',
            'view_inventory_reports',
            'export_inventory_reports',
        ]);

        // Warehouse Staff - basic inventory operations
        $warehouseRole = Role::firstOrCreate(['name' => 'Warehouse Staff']);
        $warehouseRole->givePermissionTo([
            'view_inventory',
            'view_inventory_locations',
            'view_inventory_transactions',
            'create_inventory_transactions',
            'view_stock_adjustments',
            'create_stock_adjustments',
        ]);

        // User gets view permissions only
        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $userRole->givePermissionTo([
                'view_inventory',
                'view_inventory_locations',
                'view_inventory_transactions',
            ]);
        }

        $this->command->info('Inventory permissions created and assigned successfully!');
    }
}
