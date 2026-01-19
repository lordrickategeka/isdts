<?php

namespace App\Livewire\Projects\AdvancedDetails;

use Livewire\Component;
use App\Models\ProjectPerson;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;

class OwnershipGovernanceComponent extends Component
{
    public $projectId;
    public $projectPersons = [];
    public $projectHierarchy = [];
    public $clients = [];
    public $users = [];
    public $newChildProject = [
        'name' => ''
    ];
    public $newPerson = [
        'name' => '',
        'role' => '',
        'responsibilities' => ''
    ];

    protected $rules = [
        'newChildProject.name' => 'required|string|max:255',
        'newPerson.name' => 'required|string|max:255',
        'newPerson.role' => 'required|string|max:255',
        'newPerson.responsibilities' => 'nullable|string',
    ];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->loadProjectPersons();
        $this->loadProjectHierarchy();
        $this->loadClients();
        $this->loadUsers();
    }

    public function loadProjectPersons()
    {
        $this->projectPersons = ProjectPerson::where('project_id', $this->projectId)
            ->with(['user'])
            ->orderBy('role')
            ->get();
    }

    public function loadProjectHierarchy()
    {
        $this->projectHierarchy = Project::where('parent_id', $this->projectId)
            ->with('children')
            ->get();
    }

    public function loadClients()
    {
        $this->clients = Client::all();
    }

    public function loadUsers()
    {
        $this->users = User::all();
    }

    public function addHierarchy()
    {
        $this->validate();

        $childProject = new Project();
        $childProject->name = $this->newChildProject['name'];
        $childProject->parent_id = $this->projectId;
        $childProject->save();

        $this->loadProjectHierarchy();

        $this->newChildProject = ['name' => ''];

        $this->dispatchBrowserEvent('close-modal');
    }

    public function addPerson()
    {
        $this->validate();

        $person = new ProjectPerson();
        $person->project_id = $this->projectId;
        $person->name = $this->newPerson['name'];
        $person->role = $this->newPerson['role'];
        $person->responsibility = $this->newPerson['responsibilities'];
        $person->save();

        $this->loadProjectPersons();

        $this->newPerson = [
            'name' => '',
            'role' => '',
            'responsibilities' => ''
        ];

        $this->dispatchBrowserEvent('close-modal');
    }

    public function render()
    {
        return view('livewire.projects.advanced-details.ownership-governance-component');
    }
}
