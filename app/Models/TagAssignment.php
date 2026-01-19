<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagAssignment extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'tenant_id',
        'tag_id',
        'taggable_id',
        'taggable_type',
    ];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function taggable()
    {
        return $this->morphTo();
    }
}
