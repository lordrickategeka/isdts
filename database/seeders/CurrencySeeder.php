<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [
            [
                'code' => 'UGX',
                'name' => 'Ugandan Shilling',
                'symbol' => 'USh',
                'numeric_code' => 800,
                'decimal_places' => 0,
                'decimal_separator' => '.',
                'thousand_separator' => ',',
                'is_active' => true,
                'is_default' => true,
            ],
            [
                'code' => 'USD',
                'name' => 'US Dollar',
                'symbol' => '$',
                'numeric_code' => 840,
                'decimal_places' => 2,
                'decimal_separator' => '.',
                'thousand_separator' => ',',
                'is_active' => true,
                'is_default' => false,
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                $currency
            );
        }
    }
}
