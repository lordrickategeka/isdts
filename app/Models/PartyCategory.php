<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyCategory extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
    ];

    public function parties()
    {
        return $this->hasMany(Party::class, 'category_id');
    }
}
