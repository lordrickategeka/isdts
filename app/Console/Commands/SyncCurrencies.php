<?php

namespace App\Console\Commands;

use App\Models\Currency;
use App\Models\Country;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;

class SyncCurrencies extends Command
{
    protected $signature = 'currencies:sync';
    protected $description = 'Sync currencies and countries from REST Countries API';

    public function handle()
    {
        $this->info('Starting currency and country sync...');

        try {
            $response = Http::timeout(20)
                ->withOptions(['verify' => false])
                ->get('https://restcountries.com/v3.1/all', [
                    'fields' => 'name,cca2,cca3,ccn3,idd,region,subregion,flags,currencies'
                ]);

            if (!$response->successful()) {
                $this->error('Sync failed: API returned status ' . $response->status());
                return Command::FAILURE;
            }

            $payload = $response->json();
            $now = Carbon::now();

            DB::transaction(function () use ($payload, $now) {

                $seenCurrencyCodes = [];

                foreach ($payload as $countryData) {
                    
                    // Skip if no alpha2 code
                    if (empty($countryData['cca2'])) {
                        continue;
                    }

                    // Sync Country
                    $country = Country::updateOrCreate(
                        ['alpha2' => $countryData['cca2']],
                        [
                            'name'          => $countryData['name']['common'] ?? 'Unknown',
                            'official_name' => $countryData['name']['official'] ?? null,
                            'alpha3'        => $countryData['cca3'] ?? null,
                            'numeric_code'  => $countryData['ccn3'] ?? null,
                            'idd_root'      => $countryData['idd']['root'] ?? null,
                            'idd_suffixes'  => $countryData['idd']['suffixes'] ?? null,
                            'region'        => $countryData['region'] ?? null,
                            'subregion'     => $countryData['subregion'] ?? null,
                            'flag_svg'      => $countryData['flags']['svg'] ?? null,
                            'flag_png'      => $countryData['flags']['png'] ?? null,
                            'is_active'     => true,
                        ]
                    );

                    // Sync Currencies for this country
                    if (!empty($countryData['currencies'])) {
                        $currencyIds = [];
                        $isPrimary = true; // First currency is primary

                        foreach ($countryData['currencies'] as $code => $currencyInfo) {
                            $code = strtoupper(trim($code));

                            // Create or update currency (avoid duplicates)
                            if (!isset($seenCurrencyCodes[$code])) {
                                Currency::updateOrCreate(
                                    ['code' => $code],
                                    [
                                        'name'           => $currencyInfo['name'] ?? $code,
                                        'symbol'         => $currencyInfo['symbol'] ?? null,
                                        'is_active'      => true,
                                        'last_synced_at' => $now,
                                    ]
                                );
                                $seenCurrencyCodes[$code] = true;
                            }

                            $currency = Currency::where('code', $code)->first();
                            if ($currency) {
                                $currencyIds[$currency->id] = ['is_primary' => $isPrimary];
                                $isPrimary = false; // Only first is primary
                            }
                        }

                        // Sync pivot table
                        $country->currencies()->sync($currencyIds);
                    }
                }
            });

            // Set default country based on default currency
            $defaultCurrency = Currency::where('is_default', true)->first();
            if ($defaultCurrency) {
                // Find country that uses this currency as primary
                $defaultCountry = Country::whereHas('currencies', function ($query) use ($defaultCurrency) {
                    $query->where('currencies.id', $defaultCurrency->id)
                          ->where('country_currency.is_primary', true);
                })->first();

                if ($defaultCountry) {
                    Country::where('is_default', true)->update(['is_default' => false]);
                    $defaultCountry->update(['is_default' => true]);
                    $this->info("Set {$defaultCountry->name} as default country (uses {$defaultCurrency->code})");
                }
            }

            $this->info('Countries and currencies synced successfully.');
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Sync failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
