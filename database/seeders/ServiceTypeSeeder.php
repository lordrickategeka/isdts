<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceType;
use App\Models\ServiceSubcategory;
use App\Models\Product;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Internet Service with Dedicated/Shared subcategories
        $internet = ServiceType::create([
            'name' => 'Internet',
            'description' => 'Internet connectivity services',
            'base_price' => 0.00,
            'billing_cycle' => 'monthly',
            'status' => 'active',
        ]);

        // Internet Subcategories
        $dedicated = ServiceSubcategory::create([
            'service_type_id' => $internet->id,
            'name' => 'Dedicated',
            'description' => 'Dedicated internet connection with guaranteed bandwidth',
            'price_modifier' => 200000.00,
            'status' => 'active',
            'sort_order' => 1,
        ]);

        $shared = ServiceSubcategory::create([
            'service_type_id' => $internet->id,
            'name' => 'Shared',
            'description' => 'Shared internet connection',
            'price_modifier' => 100000.00,
            'status' => 'active',
            'sort_order' => 2,
        ]);

        // Products for Internet Dedicated
        Product::create([
            'service_type_id' => $internet->id,
            'service_subcategory_id' => $dedicated->id,
            'name' => 'Fiber',
            'description' => 'High-speed fiber optic connection',
            'price' => 500000.00,
            'billing_cycle' => 'monthly',
            'specifications' => [
                'speed' => '100 Mbps',
                'connection_type' => 'Fiber Optic',
                'sla' => '99.9%',
            ],
            'status' => 'active',
            'sort_order' => 1,
        ]);

        Product::create([
            'service_type_id' => $internet->id,
            'service_subcategory_id' => $dedicated->id,
            'name' => 'LTE',
            'description' => '4G LTE wireless internet connection',
            'price' => 400000.00,
            'billing_cycle' => 'monthly',
            'specifications' => [
                'speed' => '50 Mbps',
                'connection_type' => '4G LTE',
                'sla' => '99.5%',
            ],
            'status' => 'active',
            'sort_order' => 2,
        ]);

        // Products for Internet Shared
        Product::create([
            'service_type_id' => $internet->id,
            'service_subcategory_id' => $shared->id,
            'name' => 'Fiber',
            'description' => 'Shared fiber optic connection',
            'price' => 250000.00,
            'billing_cycle' => 'monthly',
            'specifications' => [
                'speed' => 'Up to 50 Mbps',
                'connection_type' => 'Fiber Optic',
                'contention_ratio' => '1:10',
            ],
            'status' => 'active',
            'sort_order' => 1,
        ]);

        Product::create([
            'service_type_id' => $internet->id,
            'service_subcategory_id' => $shared->id,
            'name' => 'LTE',
            'description' => 'Shared 4G LTE wireless internet',
            'price' => 150000.00,
            'billing_cycle' => 'monthly',
            'specifications' => [
                'speed' => 'Up to 20 Mbps',
                'connection_type' => '4G LTE',
                'contention_ratio' => '1:20',
            ],
            'status' => 'active',
            'sort_order' => 2,
        ]);

        // Hosting Service with Monthly/Quarterly subcategories
        $hosting = ServiceType::create([
            'name' => 'Hosting',
            'description' => 'Web hosting and server solutions',
            'base_price' => 0.00,
            'billing_cycle' => 'monthly',
            'status' => 'active',
        ]);

        // Hosting Subcategories (billing periods)
        $monthly = ServiceSubcategory::create([
            'service_type_id' => $hosting->id,
            'name' => 'Monthly',
            'description' => 'Monthly billing cycle',
            'price_modifier' => 0.00,
            'status' => 'active',
            'sort_order' => 1,
        ]);

        $quarterly = ServiceSubcategory::create([
            'service_type_id' => $hosting->id,
            'name' => 'Quarterly',
            'description' => 'Quarterly billing cycle with discount',
            'price_modifier' => -10.00, // 10% discount
            'status' => 'active',
            'sort_order' => 2,
        ]);

        // Products for Hosting
        Product::create([
            'service_type_id' => $hosting->id,
            'service_subcategory_id' => $monthly->id,
            'name' => 'Shared Hosting',
            'description' => 'Basic shared web hosting',
            'price' => 50000.00,
            'billing_cycle' => 'monthly',
            'specifications' => [
                'storage' => '10 GB',
                'bandwidth' => 'Unlimited',
                'email_accounts' => '10',
                'databases' => '5',
            ],
            'status' => 'active',
            'sort_order' => 1,
        ]);

        Product::create([
            'service_type_id' => $hosting->id,
            'service_subcategory_id' => $monthly->id,
            'name' => 'VPS Hosting',
            'description' => 'Virtual Private Server hosting',
            'price' => 150000.00,
            'billing_cycle' => 'monthly',
            'specifications' => [
                'storage' => '50 GB SSD',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'bandwidth' => '1 TB',
            ],
            'status' => 'active',
            'sort_order' => 2,
        ]);

        Product::create([
            'service_type_id' => $hosting->id,
            'service_subcategory_id' => $quarterly->id,
            'name' => 'Shared Hosting',
            'description' => 'Basic shared web hosting (Quarterly)',
            'price' => 135000.00, // 3 months with 10% discount
            'billing_cycle' => 'quarterly',
            'specifications' => [
                'storage' => '10 GB',
                'bandwidth' => 'Unlimited',
                'email_accounts' => '10',
                'databases' => '5',
            ],
            'status' => 'active',
            'sort_order' => 1,
        ]);

        Product::create([
            'service_type_id' => $hosting->id,
            'service_subcategory_id' => $quarterly->id,
            'name' => 'VPS Hosting',
            'description' => 'Virtual Private Server hosting (Quarterly)',
            'price' => 405000.00, // 3 months with 10% discount
            'billing_cycle' => 'quarterly',
            'specifications' => [
                'storage' => '50 GB SSD',
                'ram' => '4 GB',
                'cpu' => '2 Cores',
                'bandwidth' => '1 TB',
            ],
            'status' => 'active',
            'sort_order' => 2,
        ]);
    }
}
