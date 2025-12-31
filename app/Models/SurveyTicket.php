<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyTicket extends Model
{
    protected $fillable = [
        'survey_name',
        'assigned_user_id',
        'project_id',
        'client_id',
        'contact_person',
        'start_date',
        'description',
        'location',
        'priority',
        'status',
    ];

    // Relationships
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function surveyEngineers()
    {
        return $this->hasMany(SurveyEngineer::class, 'survey_id');
    }
}
