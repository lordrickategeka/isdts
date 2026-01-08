<?php

namespace App\Livewire\Projects\AdvancedDetails;

use Livewire\Component;
use App\Models\ProjectPerson;
use App\Models\Project;

class OwnershipGovernanceComponent extends Component
{
    public $projectId;
    public $projectPersons = [];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->loadProjectPersons();
    }

    public function loadProjectPersons()
    {
        $this->projectPersons = ProjectPerson::where('project_id', $this->projectId)
            ->with(['user'])
            ->orderBy('role')
            ->get();
    }

    public function render()
    {
        return view('livewire.projects.advanced-details.ownership-governance-component');
    }
}
