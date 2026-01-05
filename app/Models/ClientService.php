<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClientService extends Model
{
    protected $fillable = [
        'client_id',
        'vendor_id',
        'vendor_name',
        'project_id',
        'product_id',
        'product_name',
        'service_feasibility_id',
        'service_type',
        'service_code',
        'username',
        'serial_number',
        'capacity',
        'capacity_type',
        'vlan',
        'nrc',
        'mrc',
        'contract_start_date',
        'installation_date',
        'status',
    ];

    protected $casts = [
        'nrc' => 'decimal:2',
        'mrc' => 'decimal:2',
        'contract_start_date' => 'date',
        'installation_date' => 'date',
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

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function serviceFeasibility(): BelongsTo
    {
        return $this->belongsTo(ServiceFeasibility::class);
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
