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
            UsersSeeder::class,
            VendorSeeder::class,
            ProductSeeder::class,
            UgandaRegionsAndDistrictsSeeder::class,
            ProjectSeeder::class,
            ClientSeeder::class,
            CurrencySeeder::class,
        ]);
    }
}
