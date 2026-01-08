<?php

namespace App\Helpers;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Support\Facades\Cache;

class DataHelper
{
    // Get list of countries from database
    public static function getCountries(bool $activeOnly = true)
    {
        return Cache::remember('countries_' . ($activeOnly ? 'active' : 'all'), 3600, function () use ($activeOnly) {
            $query = Country::query();
            
            if ($activeOnly) {
                $query->where('is_active', true);
            }
            
            return $query->orderBy('name')->get();
        });
    }

    // Get countries as array (code => name) for dropdowns
    public static function getCountriesArray(bool $activeOnly = true): array
    {
        return self::getCountries($activeOnly)->pluck('name', 'alpha2')->toArray();
    }

     // Get list of currencies from database
    public static function getCurrencies(bool $activeOnly = true)
    {
        return Cache::remember('currencies_' . ($activeOnly ? 'active' : 'all'), 3600, function () use ($activeOnly) {
            $query = Currency::query();
            
            if ($activeOnly) {
                $query->where('is_active', true);
            }
            
            return $query->orderBy('code')->get();
        });
    }

    // Get currencies as array (code => details) for dropdowns
    public static function getCurrenciesArray(bool $activeOnly = true): array
    {
        return self::getCurrencies($activeOnly)->mapWithKeys(function ($currency) {
            return [$currency->code => [
                'name' => $currency->name,
                'symbol' => $currency->symbol,
            ]];
        })->toArray();
    }

    //Get currency symbol by code
    public static function getCurrencySymbol(string $code): string
    {
        $currency = Currency::where('code', $code)->first();
        return $currency ? $currency->symbol : $code;
    }

    // Get currency name by code
    public static function getCurrencyName(string $code): string
    {
        $currency = Currency::where('code', $code)->first();
        return $currency ? $currency->name : $code;
    }

    // Get currency by code
    public static function getCurrency(string $code): ?Currency
    {
        return Currency::where('code', $code)->first();
    }

    // Get country name by code
    public static function getCountryName(string $code): string
    {
        $country = Country::where('alpha2', $code)->first();
        return $country ? $country->name : $code;
    }

    
    // Get country by code
    public static function getCountry(string $code): ?Country
    {
        return Country::where('alpha2', $code)->first();
    }

    // Get default currency
    public static function getDefaultCurrency(): ?Currency
    {
        return Cache::remember('default_currency', 3600, function () {
            return Currency::where('is_default', true)->first();
        });
    }

    // Get default country
    public static function getDefaultCountry(): ?Country
    {
        return Cache::remember('default_country', 3600, function () {
            return Country::where('is_default', true)->first();
        });
    }

    // Get currencies for dropdown (code => name with symbol)
    public static function getCurrenciesForDropdown(bool $activeOnly = true): array
    {
        return self::getCurrencies($activeOnly)->mapWithKeys(function ($currency) {
            return [$currency->code => "{$currency->name} ({$currency->symbol})"];
        })->toArray();
    }

    // Clear cached data
    public static function clearCache(): void
    {
        Cache::forget('countries_active');
        Cache::forget('countries_all');
        Cache::forget('currencies_active');
        Cache::forget('currencies_all');
        Cache::forget('default_currency');
        Cache::forget('default_country');
    }
}
