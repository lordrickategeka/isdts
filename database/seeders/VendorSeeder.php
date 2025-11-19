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
                'name' => 'MTN Uganda',
                'email' => 'business@mtn.co.ug',
                'phone' => '+256312120000',
                'address' => 'MTN Tower, Jinja Road, Kampala',
                'status' => 'active',
                'notes' => 'Primary telecom provider for fiber and connectivity services',
                'services' => [
                    ['service_name' => 'Fiber Internet - 10Mbps', 'description' => 'Business fiber internet connection 10Mbps dedicated'],
                    ['service_name' => 'Fiber Internet - 20Mbps', 'description' => 'Business fiber internet connection 20Mbps dedicated'],
                    ['service_name' => 'Fiber Internet - 50Mbps', 'description' => 'Business fiber internet connection 50Mbps dedicated'],
                    ['service_name' => 'Fiber Internet - 100Mbps', 'description' => 'Business fiber internet connection 100Mbps dedicated'],
                    ['service_name' => 'Last Mile Connectivity', 'description' => 'Last mile fiber installation and connectivity'],
                ]
            ],
            [
                'name' => 'Airtel Uganda',
                'email' => 'enterprise@airtel.co.ug',
                'phone' => '+256200200100',
                'address' => 'Airtel House, Plot 16 Kampala Road, Kampala',
                'status' => 'active',
                'notes' => 'Alternative telecom provider for redundancy',
                'services' => [
                    ['service_name' => 'Business Fiber - 10Mbps', 'description' => 'Dedicated fiber connection 10Mbps'],
                    ['service_name' => 'Business Fiber - 25Mbps', 'description' => 'Dedicated fiber connection 25Mbps'],
                    ['service_name' => 'Business Fiber - 50Mbps', 'description' => 'Dedicated fiber connection 50Mbps'],
                    ['service_name' => '4G LTE Backup', 'description' => '4G LTE backup connectivity solution'],
                ]
            ],
            [
                'name' => 'Liquid Telecom Uganda',
                'email' => 'sales@liquid.ug',
                'phone' => '+256414348400',
                'address' => 'Liquid Telecom House, Plot 37A, Nakasero Road, Kampala',
                'status' => 'active',
                'notes' => 'Specialized in fiber infrastructure and data services',
                'services' => [
                    ['service_name' => 'Metro Ethernet - 10Mbps', 'description' => 'Metro ethernet connectivity 10Mbps'],
                    ['service_name' => 'Metro Ethernet - 50Mbps', 'description' => 'Metro ethernet connectivity 50Mbps'],
                    ['service_name' => 'Metro Ethernet - 100Mbps', 'description' => 'Metro ethernet connectivity 100Mbps'],
                    ['service_name' => 'Dark Fiber', 'description' => 'Dark fiber infrastructure leasing'],
                    ['service_name' => 'MPLS VPN', 'description' => 'MPLS VPN connectivity solution'],
                ]
            ],
            [
                'name' => 'Smile Telecom Uganda',
                'email' => 'business@smile.co.ug',
                'phone' => '+256800100100',
                'address' => 'Smile Centre, Plot 27 Kampala Road, Kampala',
                'status' => 'active',
                'notes' => '4G LTE specialist for backup and mobile connectivity',
                'services' => [
                    ['service_name' => '4G LTE Business - 20Mbps', 'description' => '4G LTE business package 20Mbps'],
                    ['service_name' => '4G LTE Business - 50Mbps', 'description' => '4G LTE business package 50Mbps'],
                    ['service_name' => 'Wireless Backup Solution', 'description' => 'Wireless backup connectivity solution'],
                ]
            ],
            [
                'name' => 'Africell Uganda',
                'email' => 'corporate@africell.co.ug',
                'phone' => '+256750999999',
                'address' => 'Africell Plaza, Lugogo Bypass, Kampala',
                'status' => 'active',
                'notes' => 'Cost-effective solutions for SME segment',
                'services' => [
                    ['service_name' => 'Business Internet - 10Mbps', 'description' => 'Business internet package 10Mbps'],
                    ['service_name' => 'Business Internet - 20Mbps', 'description' => 'Business internet package 20Mbps'],
                    ['service_name' => 'Wireless Solution', 'description' => 'Wireless connectivity solution'],
                ]
            ],
            [
                'name' => 'Roke Telkom',
                'email' => 'info@roketelkom.com',
                'phone' => '+256393256000',
                'address' => 'Plot 2, Acacia Avenue, Kololo, Kampala',
                'status' => 'active',
                'notes' => 'Local ISP with good coverage in business areas',
                'services' => [
                    ['service_name' => 'Fiber Business - 10Mbps', 'description' => 'Fiber business connection 10Mbps'],
                    ['service_name' => 'Fiber Business - 30Mbps', 'description' => 'Fiber business connection 30Mbps'],
                    ['service_name' => 'Fiber Business - 50Mbps', 'description' => 'Fiber business connection 50Mbps'],
                    ['service_name' => 'Managed Network Services', 'description' => 'Managed network and connectivity services'],
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
