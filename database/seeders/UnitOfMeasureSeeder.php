<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UnitofMeasure;

class UnitOfMeasureSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('Creating units of measure...');

        // Length Units
        $meter = UnitofMeasure::create([
            'code' => 'm',
            'name' => 'Meter',
            'symbol' => 'm',
            'description' => 'Base unit for length',
            'category' => 'length',
            'conversion_factor' => 1,
            'is_base_unit' => true,
            'is_default' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        UnitofMeasure::create([
            'code' => 'km',
            'name' => 'Kilometer',
            'symbol' => 'km',
            'description' => '1000 meters',
            'category' => 'length',
            'base_unit_id' => $meter->id,
            'conversion_factor' => 1000,
            'is_active' => true,
            'sort_order' => 2,
        ]);

        UnitofMeasure::create([
            'code' => 'cm',
            'name' => 'Centimeter',
            'symbol' => 'cm',
            'description' => '0.01 meters',
            'category' => 'length',
            'base_unit_id' => $meter->id,
            'conversion_factor' => 0.01,
            'is_active' => true,
            'sort_order' => 3,
        ]);

        UnitofMeasure::create([
            'code' => 'mm',
            'name' => 'Millimeter',
            'symbol' => 'mm',
            'description' => '0.001 meters',
            'category' => 'length',
            'base_unit_id' => $meter->id,
            'conversion_factor' => 0.001,
            'is_active' => true,
            'sort_order' => 4,
        ]);

        UnitofMeasure::create([
            'code' => 'ft',
            'name' => 'Foot',
            'symbol' => 'ft',
            'description' => '0.3048 meters',
            'category' => 'length',
            'base_unit_id' => $meter->id,
            'conversion_factor' => 0.3048,
            'is_active' => true,
            'sort_order' => 5,
        ]);

        UnitofMeasure::create([
            'code' => 'in',
            'name' => 'Inch',
            'symbol' => 'in',
            'description' => '0.0254 meters',
            'category' => 'length',
            'base_unit_id' => $meter->id,
            'conversion_factor' => 0.0254,
            'is_active' => true,
            'sort_order' => 6,
        ]);

        // Weight Units
        $kilogram = UnitofMeasure::create([
            'code' => 'kg',
            'name' => 'Kilogram',
            'symbol' => 'kg',
            'description' => 'Base unit for weight',
            'category' => 'weight',
            'conversion_factor' => 1,
            'is_base_unit' => true,
            'is_default' => true,
            'is_active' => true,
            'sort_order' => 10,
        ]);

        UnitofMeasure::create([
            'code' => 'g',
            'name' => 'Gram',
            'symbol' => 'g',
            'description' => '0.001 kilograms',
            'category' => 'weight',
            'base_unit_id' => $kilogram->id,
            'conversion_factor' => 0.001,
            'is_active' => true,
            'sort_order' => 11,
        ]);

        UnitofMeasure::create([
            'code' => 'mg',
            'name' => 'Milligram',
            'symbol' => 'mg',
            'description' => '0.000001 kilograms',
            'category' => 'weight',
            'base_unit_id' => $kilogram->id,
            'conversion_factor' => 0.000001,
            'is_active' => true,
            'sort_order' => 12,
        ]);

        UnitofMeasure::create([
            'code' => 'ton',
            'name' => 'Metric Ton',
            'symbol' => 't',
            'description' => '1000 kilograms',
            'category' => 'weight',
            'base_unit_id' => $kilogram->id,
            'conversion_factor' => 1000,
            'is_active' => true,
            'sort_order' => 13,
        ]);

        UnitofMeasure::create([
            'code' => 'lb',
            'name' => 'Pound',
            'symbol' => 'lb',
            'description' => '0.453592 kilograms',
            'category' => 'weight',
            'base_unit_id' => $kilogram->id,
            'conversion_factor' => 0.453592,
            'is_active' => true,
            'sort_order' => 14,
        ]);

        UnitofMeasure::create([
            'code' => 'oz',
            'name' => 'Ounce',
            'symbol' => 'oz',
            'description' => '0.0283495 kilograms',
            'category' => 'weight',
            'base_unit_id' => $kilogram->id,
            'conversion_factor' => 0.0283495,
            'is_active' => true,
            'sort_order' => 15,
        ]);

        // Volume Units
        $liter = UnitofMeasure::create([
            'code' => 'l',
            'name' => 'Liter',
            'symbol' => 'L',
            'description' => 'Base unit for volume',
            'category' => 'volume',
            'conversion_factor' => 1,
            'is_base_unit' => true,
            'is_default' => true,
            'is_active' => true,
            'sort_order' => 20,
        ]);

        UnitofMeasure::create([
            'code' => 'ml',
            'name' => 'Milliliter',
            'symbol' => 'mL',
            'description' => '0.001 liters',
            'category' => 'volume',
            'base_unit_id' => $liter->id,
            'conversion_factor' => 0.001,
            'is_active' => true,
            'sort_order' => 21,
        ]);

        UnitofMeasure::create([
            'code' => 'm3',
            'name' => 'Cubic Meter',
            'symbol' => 'm³',
            'description' => '1000 liters',
            'category' => 'volume',
            'base_unit_id' => $liter->id,
            'conversion_factor' => 1000,
            'is_active' => true,
            'sort_order' => 22,
        ]);

        UnitofMeasure::create([
            'code' => 'gal',
            'name' => 'Gallon',
            'symbol' => 'gal',
            'description' => '3.78541 liters',
            'category' => 'volume',
            'base_unit_id' => $liter->id,
            'conversion_factor' => 3.78541,
            'is_active' => true,
            'sort_order' => 23,
        ]);

        // Area Units
        $sqMeter = UnitofMeasure::create([
            'code' => 'sqm',
            'name' => 'Square Meter',
            'symbol' => 'm²',
            'description' => 'Base unit for area',
            'category' => 'area',
            'conversion_factor' => 1,
            'is_base_unit' => true,
            'is_default' => true,
            'is_active' => true,
            'sort_order' => 30,
        ]);

        UnitofMeasure::create([
            'code' => 'sqft',
            'name' => 'Square Foot',
            'symbol' => 'ft²',
            'description' => '0.092903 square meters',
            'category' => 'area',
            'base_unit_id' => $sqMeter->id,
            'conversion_factor' => 0.092903,
            'is_active' => true,
            'sort_order' => 31,
        ]);

        UnitofMeasure::create([
            'code' => 'acre',
            'name' => 'Acre',
            'symbol' => 'ac',
            'description' => '4046.86 square meters',
            'category' => 'area',
            'base_unit_id' => $sqMeter->id,
            'conversion_factor' => 4046.86,
            'is_active' => true,
            'sort_order' => 32,
        ]);

        UnitofMeasure::create([
            'code' => 'hectare',
            'name' => 'Hectare',
            'symbol' => 'ha',
            'description' => '10000 square meters',
            'category' => 'area',
            'base_unit_id' => $sqMeter->id,
            'conversion_factor' => 10000,
            'is_active' => true,
            'sort_order' => 33,
        ]);

        // Quantity Units
        $unit = UnitofMeasure::create([
            'code' => 'units',
            'name' => 'Units',
            'symbol' => 'pcs',
            'description' => 'Base unit for quantity/pieces',
            'category' => 'quantity',
            'conversion_factor' => 1,
            'is_base_unit' => true,
            'is_default' => true,
            'is_active' => true,
            'allow_fractional' => false,
            'decimal_places' => 0,
            'sort_order' => 40,
        ]);

        UnitofMeasure::create([
            'code' => 'pack',
            'name' => 'Pack',
            'symbol' => 'pk',
            'description' => 'Pack of items',
            'category' => 'quantity',
            'base_unit_id' => $unit->id,
            'conversion_factor' => 1,
            'is_active' => true,
            'allow_fractional' => false,
            'decimal_places' => 0,
            'sort_order' => 41,
        ]);

        UnitofMeasure::create([
            'code' => 'box',
            'name' => 'Box',
            'symbol' => 'bx',
            'description' => 'Box of items',
            'category' => 'quantity',
            'base_unit_id' => $unit->id,
            'conversion_factor' => 1,
            'is_active' => true,
            'allow_fractional' => false,
            'decimal_places' => 0,
            'sort_order' => 42,
        ]);

        UnitofMeasure::create([
            'code' => 'dozen',
            'name' => 'Dozen',
            'symbol' => 'dz',
            'description' => '12 units',
            'category' => 'quantity',
            'base_unit_id' => $unit->id,
            'conversion_factor' => 12,
            'is_active' => true,
            'allow_fractional' => false,
            'decimal_places' => 0,
            'sort_order' => 43,
        ]);

        UnitofMeasure::create([
            'code' => 'roll',
            'name' => 'Roll',
            'symbol' => 'rl',
            'description' => 'Roll (for cables, tapes, etc.)',
            'category' => 'quantity',
            'base_unit_id' => $unit->id,
            'conversion_factor' => 1,
            'is_active' => true,
            'allow_fractional' => false,
            'decimal_places' => 0,
            'sort_order' => 44,
        ]);

        UnitofMeasure::create([
            'code' => 'ream',
            'name' => 'Ream',
            'symbol' => 'rm',
            'description' => 'Ream (500 sheets of paper)',
            'category' => 'quantity',
            'base_unit_id' => $unit->id,
            'conversion_factor' => 500,
            'is_active' => true,
            'allow_fractional' => false,
            'decimal_places' => 0,
            'sort_order' => 45,
        ]);

        // Time Units
        $hour = UnitofMeasure::create([
            'code' => 'hour',
            'name' => 'Hour',
            'symbol' => 'hr',
            'description' => 'Base unit for time',
            'category' => 'time',
            'conversion_factor' => 1,
            'is_base_unit' => true,
            'is_default' => true,
            'is_active' => true,
            'sort_order' => 50,
        ]);

        UnitofMeasure::create([
            'code' => 'day',
            'name' => 'Day',
            'symbol' => 'day',
            'description' => '24 hours',
            'category' => 'time',
            'base_unit_id' => $hour->id,
            'conversion_factor' => 24,
            'is_active' => true,
            'sort_order' => 51,
        ]);

        UnitofMeasure::create([
            'code' => 'week',
            'name' => 'Week',
            'symbol' => 'wk',
            'description' => '168 hours (7 days)',
            'category' => 'time',
            'base_unit_id' => $hour->id,
            'conversion_factor' => 168,
            'is_active' => true,
            'sort_order' => 52,
        ]);

        UnitofMeasure::create([
            'code' => 'month',
            'name' => 'Month',
            'symbol' => 'mo',
            'description' => '730 hours (30.42 days average)',
            'category' => 'time',
            'base_unit_id' => $hour->id,
            'conversion_factor' => 730,
            'is_active' => true,
            'sort_order' => 53,
        ]);

        $this->command->info('Created ' . UnitofMeasure::count() . ' units of measure.');
        $this->command->info('Unit of measure seeding completed successfully!');
    }
}
