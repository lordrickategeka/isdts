<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\ServiceType;
use App\Models\Product;
use App\Models\User;
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

        // Get or create a service type and product used for seeding
        $internetService = ServiceType::firstOrCreate(['name' => 'Internet'], ['description' => 'Internet service']);
        $product = Product::firstOrCreate(
            ['name' => 'Default Internet Product'],
            ['price' => 0, 'service_type_id' => $internetService->id]
        );

        // Create Corporate Client
        $client1 = Client::create([
            'client_code' => 'BCC-' . str_pad(1, 6, '0', STR_PAD_LEFT),
            'category' => 'company',
            'category_type' => 'Corporate',
            'company' => 'Tech Solutions Ltd',
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
            'status' => 'active',
            'created_by' => $user ? $user->id : null,
            'city' => null,
            'state' => null,
            'country' => null,
        ]);

        // Add service to client 1
        ClientService::create([
            'client_id' => $client1->id,
            'service_type_id' => $internetService->id,
            'product_id' => $product->id,
            'capacity' => '50 Mbps',
            'installation_charge' => 500000,
            'monthly_charge' => 300000,
            'contract_start_date' => now(),
        ]);

        // Create Individual Client
        $client2 = Client::create([
            'created_by' => $user ? $user->id : null,
            'client_code' => 'BCC-' . str_pad(2, 6, '0', STR_PAD_LEFT),
            'category' => 'individual',
            'category_type' => 'Individual',
            'contact_person' => 'Jane Smith',
            'phone' => '0750987654',
            'email' => 'jane.smith@email.com',
            'alternative_contact' => '0750123789',
            'address' => 'Block 5, Ntinda, Kampala',
            'latitude' => '0.3565',
            'longitude' => '32.6149',
            'status' => 'active',
            'city' => null,
            'state' => null,
            'country' => null,
        ]);

        // Add service to client 2
        ClientService::create([
            'client_id' => $client2->id,
            'service_type_id' => $internetService->id,
            'product_id' => $product->id,
            'capacity' => '20 Mbps',
            'installation_charge' => 200000,
            'monthly_charge' => 150000,
            'contract_start_date' => now(),
        ]);

        // Create SME Client
        $client3 = Client::create([
            'created_by' => $user ? $user->id : null,
            'client_code' => 'BCC-' . str_pad(3, 6, '0', STR_PAD_LEFT),
            'category' => 'company',
            'category_type' => 'SME',
            'company' => 'Swift Traders Uganda',
            'contact_person' => 'David Okello',
            'nature_of_business' => 'Trading',
            'tin_no' => '9876543210',
            'phone' => '0780555444',
            'email' => 'david@swifttraders.ug',
            'business_phone' => '0780555444',
            'business_email' => 'sales@swifttraders.ug',
            'alternative_contact' => '0780333222',
            'address' => 'Shop 45, Owino Market, Kampala',
            'latitude' => '0.3136',
            'longitude' => '32.5811',
            'status' => 'active',
            'notes' => null,
            'city' => null,
            'state' => null,
            'country' => null,
        ]);

        // Add service to client 3
        ClientService::create([
            'client_id' => $client3->id,
            'service_type_id' => $internetService->id,
            'product_id' => $product->id,
            'capacity' => '30 Mbps',
            'installation_charge' => 300000,
            'monthly_charge' => 200000,
            'contract_start_date' => now()->addDays(7),
        ]);

        // Create Government Client
        $client4 = Client::create([
            'created_by' => $user ? $user->id : null,
            'client_code' => 'BCC-' . str_pad(4, 6, '0', STR_PAD_LEFT),
            'category' => 'government',
            'category_type' => 'Government',
            'company' => 'Ministry of Health',
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
            'status' => 'active',
            'notes' => null,
            'city' => null,
            'state' => null,
            'country' => null,
        ]);

        // Add service to client 4
        ClientService::create([
            'client_id' => $client4->id,
            'service_type_id' => $internetService->id,
            'product_id' => $product->id,
            'capacity' => '100 Mbps',
            'installation_charge' => 800000,
            'monthly_charge' => 600000,
            'contract_start_date' => now(),
        ]);

        // Create NGO Client
        $client5 = Client::create([
            'created_by' => $user ? $user->id : null,
            'client_code' => 'BCC-' . str_pad(5, 6, '0', STR_PAD_LEFT),
            'category' => 'company',
            'category_type' => 'NGO',
            'company' => 'Hope Foundation Uganda',
            'contact_person' => 'Michael Ouma',
            'nature_of_business' => 'Non-Profit',
            'tin_no' => '5555666677',
            'phone' => '0785222333',
            'email' => 'michael@hopefoundation.org',
            'business_phone' => '0785222333',
            'business_email' => 'info@hopefoundation.org',
            'alternative_contact' => '0785444555',
            'address' => 'Plot 12, Kololo, Kampala',
            'latitude' => '0.3310',
            'longitude' => '32.5996',
            'status' => 'active',
            'notes' => null,
            'city' => null,
            'state' => null,
            'country' => null,
        ]);

        // Add service to client 5
        ClientService::create([
            'client_id' => $client5->id,
            'service_type_id' => $internetService->id,
            'product_id' => $product->id,
            'capacity' => '40 Mbps',
            'installation_charge' => 350000,
            'monthly_charge' => 250000,
            'contract_start_date' => now(),
        ]);
    }
}

