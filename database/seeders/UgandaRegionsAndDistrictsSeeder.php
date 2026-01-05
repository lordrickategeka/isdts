<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UgandaRegionsAndDistrictsSeeder extends Seeder
{
    public function run()
    {
        // Define regions and their districts
        $regions = [
            'Central' => [
                'Kampala', 'Mukono', 'Wakiso', 'Mpigi', 'Buikwe', 'Kayunga', 'Luwero', 'Masaka', 'Mubende', 'Kalangala', 'Rakai', 'Sembabule', 'Lyantonde', 'Kyotera'
            ],
            'Eastern' => [
                'Jinja', 'Iganga', 'Mayuge', 'Kamuli', 'Bugiri', 'Busia', 'Tororo', 'Pallisa', 'Kumi', 'Bukedea', 'Sironko', 'Mbale', 'Manafwa', 'Bududa', 'Butaleja', 'Kaberamaido', 'Namutumba', 'Soroti', 'Katakwi', 'Amuria', 'Ngora'
            ],
            'Northern' => [
                'Gulu', 'Kitgum', 'Pader', 'Amuru', 'Nwoya', 'Lamwo', 'Omoro', 'Agago', 'Lira', 'Dokolo', 'Apac', 'Oyam', 'Amolatar', 'Kwania', 'Alebtong', 'Otuke', 'Abim', 'Kotido', 'Kaabong', 'Karenga', 'Nakapiripirit', 'Amudat', 'Napak', 'Moroto'
            ],
            'Western' => [
                'Mbarara', 'Bushenyi', 'Ibanda', 'Isingiro', 'Kiruhura', 'Kamwenge', 'Kabarole', 'Kyegegwa', 'Kyenjojo', 'Kasese', 'Bundibugyo', 'Ntoroko', 'Hoima', 'Masindi', 'Buliisa', 'Kikuube', 'Kagadi', 'Kibaale', 'Kyankwanzi', 'Rubirizi', 'Rukungiri', 'Kanungu', 'Bunyangabu'
            ],
        ];

        foreach ($regions as $regionName => $districts) {
            // Insert or update region
            DB::table('regions')->updateOrInsert(
                ['name' => $regionName],
                ['name' => $regionName, 'is_active' => true, 'updated_at' => now()]
            );

            // Get the region
            $region = DB::table('regions')->where('name', $regionName)->first();

            // Insert districts if districts table exists
            if (Schema::hasTable('districts')) {
                foreach ($districts as $districtName) {
                    DB::table('districts')->updateOrInsert(
                        ['region_id' => $region->id, 'name' => $districtName],
                        ['region_id' => $region->id, 'name' => $districtName, 'is_active' => true, 'updated_at' => now()]
                    );
                }
            }
        }

        $this->command->info("Uganda regions" . (Schema::hasTable('districts') ? " and districts" : "") . " seeded successfully.");
    }
}
