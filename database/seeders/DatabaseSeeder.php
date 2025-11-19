<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // Create admin user
        $admin = User::create([
            'name' => 'Lordrick Ategeka',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('super_admin');

        // Create Sales Manager
        $salesManager = User::create([
            'name' => 'John Sales',
            'email' => 'sales@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $salesManager->assignRole('sales_manager');

        // Create CCO (Chief Commercial Officer)
        $cco = User::create([
            'name' => 'Mary Commercial',
            'email' => 'cco@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $cco->assignRole('chief_commercial');

        // Create Credit Control Manager
        $creditControl = User::create([
            'name' => 'David Credit',
            'email' => 'credit@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $creditControl->assignRole('credit_control');

        // Create CFO (Chief Financial Officer)
        $cfo = User::create([
            'name' => 'Sarah Finance',
            'email' => 'cfo@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $cfo->assignRole('chief_financial');

        // Create Business Analyst
        $businessAnalyst = User::create([
            'name' => 'Michael Analysis',
            'email' => 'analyst@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $businessAnalyst->assignRole('business_analyst');

        // Create Network Planning
        $networkPlanning = User::create([
            'name' => 'James Network',
            'email' => 'network@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $networkPlanning->assignRole('network_planning');

        // Create Implementation Manager
        $implementation = User::create([
            'name' => 'Linda Implementation',
            'email' => 'implementation@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $implementation->assignRole('implementation');

        // Create Director
        $director = User::create([
            'name' => 'Robert Director',
            'email' => 'director@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $director->assignRole('director');

        // Seed service types, subcategories, and products
        $this->call([
            ServiceTypeSeeder::class,
            ProductSeeder::class,
            VendorSeeder::class,
            ClientSeeder::class,
        ]);
    }
}
