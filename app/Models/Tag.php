<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

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
