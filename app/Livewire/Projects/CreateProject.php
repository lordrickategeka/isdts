<?php

namespace App\Livewire\Projects;

use App\Models\Client;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateProject extends Component
{
    public $name = '';
    public $description = '';
    public $start_date = '';
    public $end_date = '';
    public $estimated_budget = '';
    public $priority = 'medium';
    public $client_id = '';
    public $objectives = '';
    public $deliverables = '';

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

    public function mount()
    {
        $this->start_date = now()->format('Y-m-d');
    }

    public function createProject()
    {
        $this->validate();

        // Ensure empty estimated budget is stored as null (not empty string)
        $estimatedBudget = $this->estimated_budget;
        if ($estimatedBudget === '' || $estimatedBudget === null) {
            $estimatedBudget = null;
        } else {
            // normalize number strings (remove commas) and cast to float
            $estimatedBudget = (float) str_replace(',', '', $estimatedBudget);
        }

        $project = Project::create([
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'estimated_budget' => $estimatedBudget,
            'priority' => $this->priority,
            'client_id' => $this->client_id,
            'objectives' => $this->objectives,
            'deliverables' => $this->deliverables,
            'created_by' => Auth::id(),
            'status' => 'draft',
        ]);

        session()->flash('message', 'Project created successfully!');

        return redirect()->route('projects.budget', $project->id);
    }

    public function render()
    {
        // Order by company first, then contact_person, then email
        $clients = Client::orderByRaw('COALESCE(company, contact_person, email)')->get();

        return view('livewire.projects.create-project', [
            'clients' => $clients,
        ]);
    }
}
