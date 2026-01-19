<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Model;

class PartyAssociation extends Model
{
    use Auditable;

    protected $fillable = [
        'party_id',
        'related_party_id',
        'association_type',
        'status',
        'start_date',
        'end_date',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'association_type' => 'array',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function relatedParty()
    {
        return $this->belongsTo(Party::class, 'related_party_id');
    }
}
