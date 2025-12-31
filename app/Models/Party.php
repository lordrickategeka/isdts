<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'display_name',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    public function partyType()
    {
        return $this->belongsTo(PartyCategory::class, 'party_type_id');
    }

    public function customFieldValues()
    {
        return $this->morphMany(CustomFieldValue::class, 'model');
    }

    public function getFullNameAttribute()
    {
        if ($this->party_type === 'company') {
            return $this->name;
        }

        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function associations()
    {
        return $this->hasMany(PartyAssociation::class);
    }

    public function contacts()
    {
        return $this->hasMany(PartyContact::class);
    }

    public function profile()
    {
        return match ($this->party_type) {
            'person'   => $this->hasOne(PersonProfile::class),
            'company'  => $this->hasOne(CompanyProfile::class),
            'asset'    => $this->hasOne(AssetProfile::class),
            'location' => $this->hasOne(LocationProfile::class),
            default    => null,
        };
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = $model->created_by ?? Auth::id();
                $model->updated_by = $model->updated_by ?? Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
