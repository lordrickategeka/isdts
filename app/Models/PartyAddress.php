<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'party_id',
        'type',
        'address_line1',
        'address_line2',
        'city',
        'district',
        'state',
        'country',
        'postal_code',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
