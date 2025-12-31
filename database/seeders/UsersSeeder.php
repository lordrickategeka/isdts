<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Lordrick Ategeka',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('super_admin');

        // Create Salesperson
        $salesperson = User::create([
            'name' => 'Jane Salesperson',
            'email' => 'salesperson@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $salesperson->assignRole('salesperson');

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

        // Create Engineer 1
        $engineer1 = User::create([
            'name' => 'Alice Engineer',
            'email' => 'alice.engineer@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $engineer1->assignRole('engineer');

        // Create Engineer 2
        $engineer2 = User::create([
            'name' => 'Bob Engineer',
            'email' => 'bob.engineer@gmail.com',
            'password' => Hash::make('qwertyui'),
            'email_verified_at' => now(),
        ]);
        $engineer2->assignRole('engineer');
    }
}
