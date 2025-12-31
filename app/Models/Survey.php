<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $guarded = [];

    protected $casts = [
        'engineer_user_ids' => 'array',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    // Multiple engineers
    public function engineers()
    {
        return $this->belongsToMany(User::class, 'survey_engineer', 'survey_id', 'user_id');
    }

    public function surveyEngineers()
    {
        return $this->hasMany(SurveyEngineer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }


    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
