<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ServiceType;
use App\Models\ServiceSubcategory;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing service types
        $internet = ServiceType::where('name', 'Internet')->first();
        $hosting = ServiceType::where('name', 'Hosting')->first();

        if ($internet) {
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
                'capacity' => '100 Mbps',
                'installation_charge' => 300000.00,
                'monthly_charge' => 500000.00,
                'billing_cycle' => 'monthly',
                'specifications' => [
                    'speed' => '100 Mbps',
                    'connection_type' => 'Fiber Optic',
                    'sla' => '99.9%',
                    'installation_time' => '3-5 business days',
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
                'capacity' => '50 Mbps',
                'installation_charge' => 200000.00,
                'monthly_charge' => 400000.00,
                'billing_cycle' => 'monthly',
                'specifications' => [
                    'speed' => '50 Mbps',
                    'connection_type' => '4G LTE',
                    'sla' => '99.5%',
                    'installation_time' => '1-2 business days',
                ],
                'status' => 'active',
                'sort_order' => 2,
            ]);

            Product::create([
                'service_type_id' => $internet->id,
                'service_subcategory_id' => $dedicated->id,
                'name' => 'Microwave',
                'description' => 'Point-to-point microwave connection',
                'price' => 600000.00,
                'capacity' => '150 Mbps',
                'installation_charge' => 400000.00,
                'monthly_charge' => 600000.00,
                'billing_cycle' => 'monthly',
                'specifications' => [
                    'speed' => '150 Mbps',
                    'connection_type' => 'Microwave',
                    'sla' => '99.8%',
                    'installation_time' => '5-7 business days',
                ],
                'status' => 'active',
                'sort_order' => 3,
            ]);

            // Products for Internet Shared
            Product::create([
                'service_type_id' => $internet->id,
                'service_subcategory_id' => $shared->id,
                'name' => 'Fiber',
                'description' => 'Shared fiber optic connection',
                'price' => 250000.00,
                'capacity' => 'Up to 50 Mbps',
                'installation_charge' => 150000.00,
                'monthly_charge' => 250000.00,
                'billing_cycle' => 'monthly',
                'specifications' => [
                    'speed' => 'Up to 50 Mbps',
                    'connection_type' => 'Fiber Optic',
                    'contention_ratio' => '1:10',
                    'installation_time' => '3-5 business days',
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
                'capacity' => 'Up to 20 Mbps',
                'installation_charge' => 100000.00,
                'monthly_charge' => 150000.00,
                'billing_cycle' => 'monthly',
                'specifications' => [
                    'speed' => 'Up to 20 Mbps',
                    'connection_type' => '4G LTE',
                    'contention_ratio' => '1:20',
                    'installation_time' => '1-2 business days',
                ],
                'status' => 'active',
                'sort_order' => 2,
            ]);
        }

        if ($hosting) {
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
                'description' => 'Quarterly billing cycle with 10% discount',
                'price_modifier' => -10.00,
                'status' => 'active',
                'sort_order' => 2,
            ]);

            $annually = ServiceSubcategory::create([
                'service_type_id' => $hosting->id,
                'name' => 'Annually',
                'description' => 'Annual billing cycle with 20% discount',
                'price_modifier' => -20.00,
                'status' => 'active',
                'sort_order' => 3,
            ]);

            // Products for Hosting Monthly
            Product::create([
                'service_type_id' => $hosting->id,
                'service_subcategory_id' => $monthly->id,
                'name' => 'Shared Hosting',
                'description' => 'Basic shared web hosting',
                'price' => 50000.00,
                'capacity' => '10 GB SSD',
                'installation_charge' => 50000.00,
                'monthly_charge' => 50000.00,
                'billing_cycle' => 'monthly',
                'specifications' => [
                    'storage' => '10 GB SSD',
                    'bandwidth' => 'Unlimited',
                    'email_accounts' => '10',
                    'databases' => '5',
                    'ssl_certificate' => 'Free',
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
                'capacity' => '50 GB SSD / 4 GB RAM',
                'installation_charge' => 100000.00,
                'monthly_charge' => 150000.00,
                'billing_cycle' => 'monthly',
                'specifications' => [
                    'storage' => '50 GB SSD',
                    'ram' => '4 GB',
                    'cpu' => '2 Cores',
                    'bandwidth' => '1 TB',
                    'root_access' => 'Yes',
                ],
                'status' => 'active',
                'sort_order' => 2,
            ]);

            Product::create([
                'service_type_id' => $hosting->id,
                'service_subcategory_id' => $monthly->id,
                'name' => 'Dedicated Server',
                'description' => 'Fully dedicated server hosting',
                'price' => 500000.00,
                'capacity' => '1 TB SSD / 32 GB RAM',
                'installation_charge' => 300000.00,
                'monthly_charge' => 500000.00,
                'billing_cycle' => 'monthly',
                'specifications' => [
                    'storage' => '1 TB SSD',
                    'ram' => '32 GB',
                    'cpu' => '8 Cores',
                    'bandwidth' => 'Unlimited',
                    'dedicated_ip' => '5',
                ],
                'status' => 'active',
                'sort_order' => 3,
            ]);

            // Products for Hosting Quarterly
            Product::create([
                'service_type_id' => $hosting->id,
                'service_subcategory_id' => $quarterly->id,
                'name' => 'Shared Hosting',
                'description' => 'Basic shared web hosting (Quarterly)',
                'price' => 135000.00,
                'capacity' => '10 GB SSD',
                'installation_charge' => 50000.00,
                'monthly_charge' => 45000.00,
                'billing_cycle' => 'quarterly',
                'specifications' => [
                    'storage' => '10 GB SSD',
                    'bandwidth' => 'Unlimited',
                    'email_accounts' => '10',
                    'databases' => '5',
                    'ssl_certificate' => 'Free',
                    'discount' => '10%',
                ],
                'status' => 'active',
                'sort_order' => 1,
            ]);

            Product::create([
                'service_type_id' => $hosting->id,
                'service_subcategory_id' => $quarterly->id,
                'name' => 'VPS Hosting',
                'description' => 'Virtual Private Server hosting (Quarterly)',
                'price' => 405000.00,
                'capacity' => '50 GB SSD / 4 GB RAM',
                'installation_charge' => 100000.00,
                'monthly_charge' => 135000.00,
                'billing_cycle' => 'quarterly',
                'specifications' => [
                    'storage' => '50 GB SSD',
                    'ram' => '4 GB',
                    'cpu' => '2 Cores',
                    'bandwidth' => '1 TB',
                    'root_access' => 'Yes',
                    'discount' => '10%',
                ],
                'status' => 'active',
                'sort_order' => 2,
            ]);

            // Products for Hosting Annually
            Product::create([
                'service_type_id' => $hosting->id,
                'service_subcategory_id' => $annually->id,
                'name' => 'Shared Hosting',
                'description' => 'Basic shared web hosting (Annual)',
                'price' => 480000.00,
                'capacity' => '10 GB SSD',
                'installation_charge' => 50000.00,
                'monthly_charge' => 40000.00,
                'billing_cycle' => 'annually',
                'specifications' => [
                    'storage' => '10 GB SSD',
                    'bandwidth' => 'Unlimited',
                    'email_accounts' => '10',
                    'databases' => '5',
                    'ssl_certificate' => 'Free',
                    'discount' => '20%',
                ],
                'status' => 'active',
                'sort_order' => 1,
            ]);

            Product::create([
                'service_type_id' => $hosting->id,
                'service_subcategory_id' => $annually->id,
                'name' => 'VPS Hosting',
                'description' => 'Virtual Private Server hosting (Annual)',
                'price' => 1440000.00,
                'capacity' => '50 GB SSD / 4 GB RAM',
                'installation_charge' => 100000.00,
                'monthly_charge' => 120000.00,
                'billing_cycle' => 'annually',
                'specifications' => [
                    'storage' => '50 GB SSD',
                    'ram' => '4 GB',
                    'cpu' => '2 Cores',
                    'bandwidth' => '1 TB',
                    'root_access' => 'Yes',
                    'discount' => '20%',
                ],
                'status' => 'active',
                'sort_order' => 2,
            ]);
        }
    }
}
