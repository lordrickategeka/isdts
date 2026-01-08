<?php

namespace App\Livewire\Projects\AdvancedDetails;

use Livewire\Component;
use App\Models\ProjectTask;
use App\Models\Project;

class TasksComponent extends Component
{
    public $projectId;
    public $tasks = [];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $this->tasks = ProjectTask::where('project_id', $this->projectId)
            ->with(['assignedTo', 'milestone'])
            ->orderBy('due_date')
            ->get();
    }

    public function render()
    {
        return view('livewire.projects.advanced-details.tasks-component');
    }
}
