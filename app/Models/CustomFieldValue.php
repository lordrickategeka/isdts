<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'custom_field_id',
        'model_id',
        'value',
    ];

    public function field()
    {
        return $this->belongsTo(CustomField::class, 'custom_field_id');
    }

    public function model()
    {
        return $this->morphTo();
    }
}
