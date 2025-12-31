<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagAssignment extends Model
{
    use HasFactory;

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
