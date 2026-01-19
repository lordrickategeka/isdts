<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    use Auditable;

    protected $fillable = [
        'form_id',
        'field_type',
        'name',
        'label',
        'placeholder',
        'is_required',
        'help_text',
        'options',
        'validation_rules',
        'settings',
        'order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'options' => 'array',
        'validation_rules' => 'array',
        'settings' => 'array',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
