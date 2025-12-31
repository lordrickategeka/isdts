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

    // Modal and form properties
    public $showCreateModal = false;
    public $name = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $estimated_budget = '';
    public $priority = 'medium';
    public $client_id = '';
    public $objectives = '';
    public $deliverables = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'start_date' => 'required|date',
        'end_date' => 'nullable|date|after:start_date',
        'estimated_budget' => 'nullable|numeric|min:0',
        'priority' => 'required|in:low,medium,high,critical',
        'client_id' => 'nullable|exists:clients,id',
        'objectives' => 'nullable|string',
        'deliverables' => 'nullable|string',
    ];

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->start_date = now()->format('Y-m-d');
        $this->resetForm();
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
        $this->resetValidation();
    }

    public function createProject()
    {
        $this->validate();

        // Normalize estimated budget: empty string => null, otherwise cast numeric
        $estimatedBudget = $this->estimated_budget;
        if ($estimatedBudget === '' || $estimatedBudget === null) {
            $estimatedBudget = null;
        } else {
            $estimatedBudget = (float) str_replace(',', '', $estimatedBudget);
        }

        $project = Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'estimated_budget' => $estimatedBudget,
            'priority' => $this->priority,
            'client_id' => $this->client_id ?: null,
            'objectives' => $this->objectives,
            'deliverables' => $this->deliverables,
            'created_by' => Auth::id(),
            'status' => 'draft',
        ]);

        $this->closeCreateModal();
        session()->flash('message', 'Project created successfully!');

        return redirect()->route('projects.budget', $project->id);
    }

    public function resetForm()
    {
        $this->name = '';
        $this->description = '';
        $this->start_date = now()->format('Y-m-d');
        $this->end_date = '';
        $this->estimated_budget = '';
        $this->priority = 'medium';
        $this->client_id = '';
        $this->objectives = '';
        $this->deliverables = '';
    }

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

        $clients = Client::orderByRaw('COALESCE(company, contact_person, email)')->get();

        return view('livewire.projects.project-list', [
            'projects' => $projects,
            'clients' => $clients,
        ]);
    }
}
