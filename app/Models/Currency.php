<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'code', // e.g., 'UGX', 'USD'
        'name', // e.g., 'Ugandan Shilling', 'US Dollar'
        'symbol', // e.g., 'USh', '$'
    ];

    // Optionally, you can define constants for the supported currencies
    public const UGX = 'UGX';
    public const USD = 'USD';

    // Static method to get supported currencies
    public static function supportedCurrencies()
    {
        return [
            [
                'code' => self::UGX,
                'name' => 'Ugandan Shilling',
                'symbol' => 'USh',
            ],
            [
                'code' => self::USD,
                'name' => 'US Dollar',
                'symbol' => '$',
            ],
        ];
    }
}
