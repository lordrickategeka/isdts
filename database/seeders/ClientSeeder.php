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

        // Get service types and products
        $internetService = ServiceType::where('name', 'Internet')->first();
        $product = Product::first();

        // Create Corporate Client
        $client1 = Client::create([
            'user_id' => $user->id,
            'client_code' => 'BCC-' . str_pad(1, 6, '0', STR_PAD_LEFT),
            'category' => 'Business',
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
            'designation' => 'CEO',
            'address' => 'Plot 10, Industrial Area, Kampala',
            'latitude' => '0.3476',
            'longitude' => '32.5825',
            'agreement_number' => 'AGR-' . date('Y') . '-' . str_pad(1, 4, '0', STR_PAD_LEFT),
            'payment_type' => 'prepaid',
            'status' => 'active',
            'notes' => 'High priority client',
            'first_name' => null,
            'last_name' => null,
            'city' => null,
            'state' => null,
            'zip_code' => null,
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
            'service_code' => 'SVC-' . str_pad(1, 6, '0', STR_PAD_LEFT),
        ]);

        // Create Individual Client
        $client2 = Client::create([
            'user_id' => $user->id,
            'client_code' => 'BCC-' . str_pad(2, 6, '0', STR_PAD_LEFT),
            'category' => 'Home',
            'category_type' => 'Individual',
            'contact_person' => 'Jane Smith',
            'phone' => '0750987654',
            'email' => 'jane.smith@email.com',
            'alternative_contact' => '0750123789',
            'designation' => 'Home User',
            'address' => 'Block 5, Ntinda, Kampala',
            'latitude' => '0.3565',
            'longitude' => '32.6149',
            'agreement_number' => 'AGR-' . date('Y') . '-' . str_pad(2, 4, '0', STR_PAD_LEFT),
            'payment_type' => 'postpaid',
            'status' => 'active',
            'first_name' => null,
            'last_name' => null,
            'city' => null,
            'state' => null,
            'zip_code' => null,
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
            'service_code' => 'SVC-' . str_pad(2, 6, '0', STR_PAD_LEFT),
        ]);

        // Create SME Client
        $client3 = Client::create([
            'user_id' => $user->id,
            'client_code' => 'BCC-' . str_pad(3, 6, '0', STR_PAD_LEFT),
            'category' => 'SME',
            'category_type' => 'Corporate',
            'company' => 'Swift Traders Uganda',
            'contact_person' => 'David Okello',
            'nature_of_business' => 'Trading',
            'tin_no' => '9876543210',
            'phone' => '0780555444',
            'email' => 'david@swifttraders.ug',
            'business_phone' => '0780555444',
            'business_email' => 'sales@swifttraders.ug',
            'alternative_contact' => '0780333222',
            'designation' => 'Managing Director',
            'address' => 'Shop 45, Owino Market, Kampala',
            'latitude' => '0.3136',
            'longitude' => '32.5811',
            'agreement_number' => 'AGR-' . date('Y') . '-' . str_pad(3, 4, '0', STR_PAD_LEFT),
            'payment_type' => 'prepaid',
            'status' => 'pending_approval',
            'notes' => 'Requires credit check',
            'first_name' => null,
            'last_name' => null,
            'city' => null,
            'state' => null,
            'zip_code' => null,
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
            'service_code' => 'SVC-' . str_pad(3, 6, '0', STR_PAD_LEFT),
        ]);

        // Create Government Client
        $client4 = Client::create([
            'user_id' => $user->id,
            'client_code' => 'BCC-' . str_pad(4, 6, '0', STR_PAD_LEFT),
            'category' => 'Government',
            'category_type' => 'Corporate',
            'company' => 'Ministry of Health',
            'contact_person' => 'Dr. Sarah Namukasa',
            'nature_of_business' => 'Government',
            'tin_no' => '1111222233',
            'phone' => '0772111222',
            'email' => 'it@health.go.ug',
            'business_phone' => '0414123456',
            'business_email' => 'procurement@health.go.ug',
            'alternative_contact' => '0772333444',
            'designation' => 'Director IT',
            'address' => 'Plot 6, Lourdel Road, Nakasero',
            'latitude' => '0.3186',
            'longitude' => '32.5811',
            'agreement_number' => 'AGR-' . date('Y') . '-' . str_pad(4, 4, '0', STR_PAD_LEFT),
            'payment_type' => 'postpaid',
            'status' => 'active',
            'notes' => 'Government contract - Net 60 payment terms',
            'first_name' => null,
            'last_name' => null,
            'city' => null,
            'state' => null,
            'zip_code' => null,
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
            'service_code' => 'SVC-' . str_pad(4, 6, '0', STR_PAD_LEFT),
        ]);

        // Create NGO Client
        $client5 = Client::create([
            'user_id' => $user->id,
            'client_code' => 'BCC-' . str_pad(5, 6, '0', STR_PAD_LEFT),
            'category' => 'NGO',
            'category_type' => 'Corporate',
            'company' => 'Hope Foundation Uganda',
            'contact_person' => 'Michael Ouma',
            'nature_of_business' => 'Non-Profit',
            'tin_no' => '5555666677',
            'phone' => '0785222333',
            'email' => 'michael@hopefoundation.org',
            'business_phone' => '0785222333',
            'business_email' => 'info@hopefoundation.org',
            'alternative_contact' => '0785444555',
            'designation' => 'Program Manager',
            'address' => 'Plot 12, Kololo, Kampala',
            'latitude' => '0.3310',
            'longitude' => '32.5996',
            'agreement_number' => 'AGR-' . date('Y') . '-' . str_pad(5, 4, '0', STR_PAD_LEFT),
            'payment_type' => 'prepaid',
            'status' => 'active',
            'notes' => 'NGO discount applied',
            'first_name' => null,
            'last_name' => null,
            'city' => null,
            'state' => null,
            'zip_code' => null,
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
            'service_code' => 'SVC-' . str_pad(5, 6, '0', STR_PAD_LEFT),
        ]);
    }
}

