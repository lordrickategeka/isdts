<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'tenant_id',
        'name',
        'color',
    ];

    public function assignments()
    {
        return $this->hasMany(TagAssignment::class);
    }
}
