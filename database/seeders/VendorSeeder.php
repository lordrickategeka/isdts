<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\VendorService;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vendors = [
            [
                'name' => 'BCC',
                'email' => 'info@bcc.co.ug',
                'phone' => '+256700000000',
                'address' => 'BCC House, Kampala Road, Kampala',
                'status' => 'active',
                'notes' => 'BCC Internet Service Provider',
                'services' => [
                    ['service_name' => 'Internet', 'description' => 'Internet connectivity services'],
                ]
            ],
            [
                'name' => 'MTN Uganda',
                'email' => 'business@mtn.co.ug',
                'phone' => '+256312120000',
                'address' => 'MTN Tower, Jinja Road, Kampala',
                'status' => 'active',
                'notes' => 'Primary telecom provider for fiber and connectivity services',
                'services' => [
                    ['service_name' => 'Internet', 'description' => 'Internet connectivity services'],
                ]
            ],
        ];

        foreach ($vendors as $vendorData) {
            $services = $vendorData['services'];
            unset($vendorData['services']);

            $vendor = Vendor::create($vendorData);

            foreach ($services as $service) {
                VendorService::create([
                    'vendor_id' => $vendor->id,
                    'service_name' => $service['service_name'],
                    'description' => $service['description'],
                ]);
            }
        }
    }
}
