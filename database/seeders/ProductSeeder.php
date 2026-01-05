<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\VendorService;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Products are now linked to vendors through vendor_services
     */
    public function run(): void
    {
        // Get all vendors
        $vendors = Vendor::all();

        foreach ($vendors as $vendor) {
            // Get vendor services (Internet service)
            $vendorServices = VendorService::where('vendor_id', $vendor->id)->get();

            foreach ($vendorServices as $vendorService) {
                // Create Fiber product
                Product::create([
                    'vendor_id' => $vendor->id,
                    'vendor_service_id' => $vendorService->id,
                    'name' => 'Fiber',
                    'description' => null,
                    'price' => null,
                    'capacity' => null,
                    'installation_charge' => null,
                    'monthly_charge' => null,
                    'billing_cycle' => null,
                    'specifications' => null,
                    'status' => 'active',
                    'sort_order' => 1,
                ]);

                // Create LTE product
                Product::create([
                    'vendor_id' => $vendor->id,
                    'vendor_service_id' => $vendorService->id,
                    'name' => 'LTE',
                    'description' => null,
                    'price' => null,
                    'capacity' => null,
                    'installation_charge' => null,
                    'monthly_charge' => null,
                    'billing_cycle' => null,
                    'specifications' => null,
                    'status' => 'active',
                    'sort_order' => 2,
                ]);

                // Create Microwave product
                Product::create([
                    'vendor_id' => $vendor->id,
                    'vendor_service_id' => $vendorService->id,
                    'name' => 'Microwave',
                    'description' => null,
                    'price' => null,
                    'capacity' => null,
                    'installation_charge' => null,
                    'monthly_charge' => null,
                    'billing_cycle' => null,
                    'specifications' => null,
                    'status' => 'active',
                    'sort_order' => 3,
                ]);
            }
        }
    }
}
