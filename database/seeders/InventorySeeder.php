<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryLocation;
use App\Models\InventoryItem;
use App\Models\User;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('Creating inventory locations...');

        // Get first admin user as default manager
        $manager = User::first();

        // Create main warehouse locations
        $mainWarehouse = InventoryLocation::create([
            'code' => 'WH-MAIN',
            'name' => 'Main Warehouse',
            'type' => 'warehouse',
            'description' => 'Primary storage facility for all inventory',
            'address' => '123 Industrial Park',
            'city' => 'Kampala',
            'state' => 'Central',
            'country' => 'Uganda',
            'contact_person' => 'John Doe',
            'contact_phone' => '+256700000001',
            'contact_email' => 'warehouse@company.com',
            'capacity' => 10000,
            'current_utilization' => 0,
            'is_active' => true,
            'allow_negative_stock' => false,
            'manager_id' => $manager?->id,
        ]);

        // Create sub-locations in main warehouse
        $rawMaterials = InventoryLocation::create([
            'code' => 'WH-MAIN-RAW',
            'name' => 'Raw Materials Section',
            'type' => 'warehouse',
            'description' => 'Storage for raw materials and components',
            'capacity' => 3000,
            'is_active' => true,
            'parent_location_id' => $mainWarehouse->id,
            'manager_id' => $manager?->id,
        ]);

        $finishedGoods = InventoryLocation::create([
            'code' => 'WH-MAIN-FIN',
            'name' => 'Finished Goods Section',
            'type' => 'warehouse',
            'description' => 'Storage for finished products ready for delivery',
            'capacity' => 4000,
            'is_active' => true,
            'parent_location_id' => $mainWarehouse->id,
            'manager_id' => $manager?->id,
        ]);

        $equipment = InventoryLocation::create([
            'code' => 'WH-MAIN-EQP',
            'name' => 'Equipment Storage',
            'type' => 'warehouse',
            'description' => 'Storage for equipment and tools',
            'capacity' => 1000,
            'is_active' => true,
            'parent_location_id' => $mainWarehouse->id,
            'manager_id' => $manager?->id,
        ]);

        // Create office location
        $office = InventoryLocation::create([
            'code' => 'OFF-HQ',
            'name' => 'Head Office',
            'type' => 'office',
            'description' => 'Office supplies and consumables',
            'address' => '456 Business Center',
            'city' => 'Kampala',
            'state' => 'Central',
            'country' => 'Uganda',
            'capacity' => 500,
            'is_active' => true,
            'manager_id' => $manager?->id,
        ]);

        // Create site location
        $site = InventoryLocation::create([
            'code' => 'SITE-01',
            'name' => 'Project Site 01',
            'type' => 'site',
            'description' => 'On-site inventory for active project',
            'capacity' => 2000,
            'is_active' => true,
            'allow_negative_stock' => false,
        ]);

        // Create store location
        $store = InventoryLocation::create([
            'code' => 'STORE-RTL',
            'name' => 'Retail Store',
            'type' => 'store',
            'description' => 'Customer-facing retail location',
            'address' => '789 Shopping Mall',
            'city' => 'Kampala',
            'capacity' => 1500,
            'is_active' => true,
        ]);

        $this->command->info('Created ' . InventoryLocation::count() . ' inventory locations.');

        $this->command->info('Creating sample inventory items...');

        // Create sample inventory items

        // Fiber Optic Cables
        InventoryItem::create([
            'sku' => 'FOC-SM-01',
            'barcode' => '8901234567890',
            'name' => 'Single Mode Fiber Optic Cable - 500m',
            'description' => 'High-quality single mode fiber optic cable for long-distance transmission',
            'type' => 'material',
            'category' => 'Cables',
            'subcategory' => 'Fiber Optic',
            'unit_of_measure' => 'meters',
            'quantity_on_hand' => 5000,
            'quantity_reserved' => 500,
            'reorder_level' => 1000,
            'reorder_quantity' => 2000,
            'max_stock_level' => 10000,
            'costing_method' => 'Average',
            'unit_cost' => 2.50,
            'average_cost' => 2.50,
            'track_batches' => true,
            'is_active' => true,
            'is_stockable' => true,
            'is_purchasable' => true,
        ]);

        InventoryItem::create([
            'sku' => 'FOC-MM-01',
            'name' => 'Multi Mode Fiber Optic Cable - 500m',
            'description' => 'Multi mode fiber optic cable for shorter distance applications',
            'type' => 'material',
            'category' => 'Cables',
            'subcategory' => 'Fiber Optic',
            'unit_of_measure' => 'meters',
            'quantity_on_hand' => 3000,
            'reorder_level' => 800,
            'reorder_quantity' => 1500,
            'costing_method' => 'Average',
            'unit_cost' => 1.75,
            'average_cost' => 1.75,
            'track_batches' => true,
            'is_active' => true,
        ]);

        // Network Equipment
        InventoryItem::create([
            'sku' => 'SW-GIG-24',
            'barcode' => '8901234567891',
            'name' => '24-Port Gigabit Switch',
            'description' => 'Managed gigabit ethernet switch with 24 ports',
            'type' => 'equipment',
            'category' => 'Network Equipment',
            'subcategory' => 'Switches',
            'unit_of_measure' => 'units',
            'quantity_on_hand' => 25,
            'quantity_reserved' => 3,
            'reorder_level' => 5,
            'reorder_quantity' => 10,
            'costing_method' => 'FIFO',
            'unit_cost' => 450.00,
            'average_cost' => 450.00,
            'track_serial_numbers' => true,
            'is_active' => true,
            'is_purchasable' => true,
            'is_sellable' => true,
        ]);

        InventoryItem::create([
            'sku' => 'RTR-ENT-01',
            'name' => 'Enterprise Router',
            'description' => 'High-performance enterprise-grade router',
            'type' => 'equipment',
            'category' => 'Network Equipment',
            'subcategory' => 'Routers',
            'unit_of_measure' => 'units',
            'quantity_on_hand' => 15,
            'reorder_level' => 3,
            'reorder_quantity' => 5,
            'costing_method' => 'FIFO',
            'unit_cost' => 850.00,
            'average_cost' => 850.00,
            'track_serial_numbers' => true,
            'is_active' => true,
        ]);

        // Tools and consumables
        InventoryItem::create([
            'sku' => 'TOOL-CRIMP-01',
            'name' => 'RJ45 Crimping Tool',
            'description' => 'Professional crimping tool for RJ45 connectors',
            'type' => 'equipment',
            'category' => 'Tools',
            'unit_of_measure' => 'units',
            'quantity_on_hand' => 12,
            'reorder_level' => 2,
            'reorder_quantity' => 5,
            'costing_method' => 'Average',
            'unit_cost' => 35.00,
            'average_cost' => 35.00,
            'is_active' => true,
        ]);

        InventoryItem::create([
            'sku' => 'CONN-RJ45-100',
            'name' => 'RJ45 Connectors - Pack of 100',
            'description' => 'High-quality RJ45 connectors for ethernet cables',
            'type' => 'consumable',
            'category' => 'Connectors',
            'unit_of_measure' => 'packs',
            'quantity_on_hand' => 150,
            'quantity_reserved' => 10,
            'reorder_level' => 30,
            'reorder_quantity' => 50,
            'costing_method' => 'Average',
            'unit_cost' => 12.50,
            'average_cost' => 12.50,
            'is_active' => true,
        ]);

        InventoryItem::create([
            'sku' => 'TAPE-ELEC-01',
            'name' => 'Electrical Tape - Roll',
            'description' => 'Industrial grade electrical insulation tape',
            'type' => 'consumable',
            'category' => 'Consumables',
            'unit_of_measure' => 'rolls',
            'quantity_on_hand' => 200,
            'reorder_level' => 50,
            'reorder_quantity' => 100,
            'costing_method' => 'Average',
            'unit_cost' => 2.00,
            'average_cost' => 2.00,
            'is_active' => true,
        ]);

        // Spare parts
        InventoryItem::create([
            'sku' => 'SPARE-PWR-01',
            'name' => 'Power Supply Unit - 500W',
            'description' => 'Replacement power supply unit for network equipment',
            'type' => 'spare_part',
            'category' => 'Spare Parts',
            'subcategory' => 'Power',
            'unit_of_measure' => 'units',
            'quantity_on_hand' => 20,
            'reorder_level' => 5,
            'reorder_quantity' => 10,
            'costing_method' => 'FIFO',
            'unit_cost' => 75.00,
            'average_cost' => 75.00,
            'track_serial_numbers' => true,
            'is_active' => true,
        ]);

        InventoryItem::create([
            'sku' => 'SPARE-FAN-01',
            'name' => 'Cooling Fan Module',
            'description' => 'Replacement cooling fan for switches and routers',
            'type' => 'spare_part',
            'category' => 'Spare Parts',
            'unit_of_measure' => 'units',
            'quantity_on_hand' => 30,
            'reorder_level' => 8,
            'reorder_quantity' => 15,
            'costing_method' => 'Average',
            'unit_cost' => 25.00,
            'average_cost' => 25.00,
            'is_active' => true,
        ]);

        // Office supplies
        InventoryItem::create([
            'sku' => 'OFF-PAPER-A4',
            'name' => 'A4 Paper - Ream',
            'description' => 'Premium A4 copier paper, 500 sheets',
            'type' => 'consumable',
            'category' => 'Office Supplies',
            'unit_of_measure' => 'reams',
            'quantity_on_hand' => 100,
            'reorder_level' => 20,
            'reorder_quantity' => 50,
            'costing_method' => 'Average',
            'unit_cost' => 5.00,
            'average_cost' => 5.00,
            'is_active' => true,
        ]);

        $this->command->info('Created ' . InventoryItem::count() . ' inventory items.');
        $this->command->info('Inventory seeding completed successfully!');
    }
}
