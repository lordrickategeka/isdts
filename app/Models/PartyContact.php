<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyContact extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'tenant_id',
        'party_id',
        'type',
        'value',
        'primary',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }
}
