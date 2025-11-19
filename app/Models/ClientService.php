<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClientService extends Model
{
    protected $fillable = [
        'client_id',
        'service_code',
        'service_type_id',
        'product_id',
        'capacity',
        'installation_charge',
        'monthly_charge',
        'contract_start_date',
        'status',
    ];

    protected $casts = [
        'installation_charge' => 'decimal:2',
        'monthly_charge' => 'decimal:2',
        'contract_start_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($service) {
            if (empty($service->service_code)) {
                $service->service_code = self::generateServiceCode($service->client_id);
            }
        });
    }

    /**
     * Generate unique service code in format: SRV-{CLIENT_CODE}-XXXX
     */
    private static function generateServiceCode($clientId)
    {
        $client = \App\Models\Client::find($clientId);
        $clientCode = $client ? $client->client_code : 'CLI-UNKNOWN';

        $lastService = self::where('client_id', $clientId)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastService && preg_match('/-(\d{4})$/', $lastService->service_code, $matches)) {
            $sequence = intval($matches[1]) + 1;
        } else {
            $sequence = 1;
        }

        return 'SRV-' . $clientCode . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function feasibility(): HasOne
    {
        return $this->hasOne(ServiceFeasibility::class);
    }
}
