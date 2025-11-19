<?php

namespace App\Livewire\Projects;

use App\Models\Client;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProjectList extends Component
{
    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
    ];

    public function deleteProject($projectId)
    {
        $project = Project::findOrFail($projectId);

        // Check permissions (you may want to add role-based authorization)
        $project->delete();

        session()->flash('message', 'Project deleted successfully.');
    }

    public function render()
    {
        $projects = Project::with(['creator', 'client', 'budgetItems', 'approvals'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('project_code', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($query) {
                $query->where('priority', $this->priorityFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.projects.project-list', [
            'projects' => $projects,
        ])->layout('layouts.app');
    }
}
