<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
use App\Models\Region;
use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (sales manager or admin)
        $user = User::first();

        // Get vendors
        $bcc = Vendor::where('name', 'BCC')->first();
        $mtn = Vendor::where('name', 'MTN Uganda')->first();

        if (!$bcc || !$mtn) {
            throw new \Exception('Vendors not found. Please run VendorSeeder first.');
        }

        // Get products
        $fiberProducts = Product::where('name', 'like', 'Fiber%')->get();
        $lteProducts = Product::where('name', 'like', 'LTE%')->get();
        $microwaveProducts = Product::where('name', 'like', 'Microwave%')->get();

        // Get regions
        $central = Region::where('name', 'Central')->first();
        $western = Region::where('name', 'Western')->first();

        // Get districts
        $kampala = District::where('name', 'Kampala')->first();
        $wakiso = District::where('name', 'Wakiso')->first();
        $mbarara = District::where('name', 'Mbarara')->first();

        // Get projects (ProjectSeeder should be run first)
        $ecProject = Project::where('project_code', 'PROJ-EC-' . now()->format('Ymd'))->first();
        $bccProject = Project::where('project_code', 'PROJ-BCC-' . now()->format('Ymd'))->first();
        $unebProject = Project::where('project_code', 'PROJ-UNEB-' . now()->format('Ymd'))->first();

        if (!$ecProject || !$bccProject || !$unebProject) {
            throw new \Exception('Projects not found. Please run ProjectSeeder first.');
        }

        // Create Corporate Client with Fiber
        $client1 = Client::create([
            'client_code' => 'BCC-' . str_pad(1, 6, '0', STR_PAD_LEFT),
            'category' => 'Corporate',
            'customer_name' => 'Tech Solutions Ltd',
            'contact_person' => 'John Doe',
            'nature_of_business' => 'IT Services',
            'tin_no' => '1234567890',
            'phone' => '0700123456',
            'email' => 'john@techsolutions.com',
            'business_phone' => '0700123456',
            'business_email' => 'info@techsolutions.com',
            'alternative_contact' => '0700654321',
            'address' => 'Plot 10, Industrial Area, Kampala',
            'latitude' => '0.3476',
            'longitude' => '32.5825',
            'region' => $central ? $central->name : 'Central',
            'district' => $kampala ? $kampala->name : 'Kampala',

            'created_by' => $user ? $user->id : 1,
        ]);

        $fiber50 = $fiberProducts->where('name', 'Fiber 50 Mbps')->where('vendor_id', $bcc->id)->first();
        if ($fiber50) {
            ClientService::create([
                'client_id' => $client1->id,
                'vendor_id' => $bcc->id,
                'project_id' => $ecProject->id,
                'product_id' => $fiber50->id,
                'service_type' => 'Fiber',
                'capacity' => '50 Mbps',
                'vlan' => '100',
                'nrc' => $fiber50->installation_charge,
                'mrc' => $fiber50->monthly_charge,
                'contract_start_date' => now(),
                'installation_date' => now(),


            ]);
        }

        // Create Home Client with LTE
        $client2 = Client::create([
            'created_by' => $user ? $user->id : 1,
            'client_code' => 'BCC-' . str_pad(2, 6, '0', STR_PAD_LEFT),
            'category' => 'Home',
            'customer_name' => 'Jane Smith',
            'contact_person' => 'Jane Smith',
            'phone' => '0750987654',
            'email' => 'jane.smith@email.com',
            'alternative_contact' => '0750123789',
            'address' => 'Block 5, Ntinda',
            'latitude' => '0.3565',
            'longitude' => '32.6149',
            'region' => $central ? $central->name : 'Central',
            'district' => $kampala ? $kampala->name : 'Kampala',

        ]);

        $lte20 = $lteProducts->where('name', 'LTE 20 Mbps')->where('vendor_id', $mtn->id)->first();
        if ($lte20) {
            ClientService::create([
                'client_id' => $client2->id,
                'vendor_id' => $mtn->id,
                'project_id' => $ecProject->id,
                'product_id' => $lte20->id,
                'service_type' => 'LTE',
                'capacity' => '20 Mbps',
                'vlan' => '101',
                'nrc' => $lte20->installation_charge,
                'mrc' => $lte20->monthly_charge,
                'contract_start_date' => now(),
                'installation_date' => now(),


            ]);
        }

        // Create SME Client with Microwave
        $client3 = Client::create([
            'created_by' => $user ? $user->id : 1,
            'client_code' => 'BCC-' . str_pad(3, 6, '0', STR_PAD_LEFT),
            'category' => 'Corporate',
            'customer_name' => 'Swift Traders Uganda',
            'contact_person' => 'David Okello',
            'nature_of_business' => 'Trading',
            'tin_no' => '9876543210',
            'phone' => '0780555444',
            'email' => 'david@swifttraders.ug',
            'business_phone' => '0780555444',
            'business_email' => 'sales@swifttraders.ug',
            'alternative_contact' => '0780333222',
            'address' => 'Shop 45, Owino Market',
            'latitude' => '0.3136',
            'longitude' => '32.5811',
            'region' => $central ? $central->name : 'Central',
            'district' => $kampala ? $kampala->name : 'Kampala',

        ]);

        $microwave50 = $microwaveProducts->where('name', 'Microwave 50 Mbps')->where('vendor_id', $bcc->id)->first();
        if ($microwave50) {
            ClientService::create([
                'client_id' => $client3->id,
                'vendor_id' => $bcc->id,
                'project_id' => $ecProject->id,
                'product_id' => $microwave50->id,
                'service_type' => 'Microwave',
                'capacity' => '50 Mbps',
                'vlan' => '102',
                'nrc' => $microwave50->installation_charge,
                'mrc' => $microwave50->monthly_charge,
                'contract_start_date' => now()->addDays(7),
                'installation_date' => now()->addDays(7),


            ]);
        }

        // Create Government Client with high-speed Fiber
        $client4 = Client::create([
            'created_by' => $user ? $user->id : 1,
            'client_code' => 'BCC-' . str_pad(4, 6, '0', STR_PAD_LEFT),
            'category' => 'Corporate',
            'customer_name' => 'Ministry of Health',
            'contact_person' => 'Dr. Sarah Namukasa',
            'nature_of_business' => 'Government',
            'tin_no' => '1111222233',
            'phone' => '0772111222',
            'email' => 'it@health.go.ug',
            'business_phone' => '0414123456',
            'business_email' => 'procurement@health.go.ug',
            'alternative_contact' => '0772333444',
            'address' => 'Plot 6, Lourdel Road, Nakasero',
            'latitude' => '0.3186',
            'longitude' => '32.5811',
            'region' => $central ? $central->name : 'Central',
            'district' => $kampala ? $kampala->name : 'Kampala',

        ]);

        $fiber100 = $fiberProducts->where('name', 'Fiber 100 Mbps')->where('vendor_id', $mtn->id)->first();
        if ($fiber100) {
            ClientService::create([
                'client_id' => $client4->id,
                'vendor_id' => $mtn->id,
                'project_id' => $ecProject->id,
                'product_id' => $fiber100->id,
                'service_type' => 'Fiber',
                'capacity' => '100 Mbps',
                'vlan' => '103',
                'nrc' => $fiber100->installation_charge,
                'mrc' => $fiber100->monthly_charge,
                'contract_start_date' => now(),
                'installation_date' => now(),


            ]);
        }

        // Create NGO Client in Western Region
        $client5 = Client::create([
            'created_by' => $user ? $user->id : 1,
            'client_code' => 'BCC-' . str_pad(5, 6, '0', STR_PAD_LEFT),
            'category' => 'Corporate',
            'customer_name' => 'Hope Foundation Uganda',
            'contact_person' => 'Michael Ouma',
            'nature_of_business' => 'Non-Profit',
            'tin_no' => '5555666677',
            'phone' => '0785222333',
            'email' => 'michael@hopefoundation.org',
            'business_phone' => '0785222333',
            'business_email' => 'info@hopefoundation.org',
            'alternative_contact' => '0785444555',
            'address' => 'Plot 12, Mbarara Town',
            'latitude' => '-0.6074',
            'longitude' => '30.6591',
            'region' => $western ? $western->name : 'Western',
            'district' => $mbarara ? $mbarara->name : 'Mbarara',

        ]);

        $lte10 = $lteProducts->where('name', 'LTE 10 Mbps')->where('vendor_id', $bcc->id)->first();
        if ($lte10) {
            ClientService::create([
                'client_id' => $client5->id,
                'vendor_id' => $bcc->id,
                'project_id' => $ecProject->id,
                'product_id' => $lte10->id,
                'service_type' => 'LTE',
                'capacity' => '10 Mbps',
                'vlan' => '104',
                'nrc' => $lte10->installation_charge,
                'mrc' => $lte10->monthly_charge,
                'contract_start_date' => now(),
                'installation_date' => now(),


            ]);
        }
    }
}
