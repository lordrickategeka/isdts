<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\ProjectApproval;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProjectApprovals extends Component
{
    public Project $project;
    public $userRole = null;
    public $existingApproval = null;
    public $comments = '';

    protected $rules = [
        'comments' => 'nullable|string|max:1000',
    ];

    public function mount(Project $project)
    {
        $this->project = $project->load(['approvals.approver', 'budgetItems']);
        $this->determineUserRole();
        $this->loadExistingApproval();
    }

    protected function determineUserRole()
    {
        $user = Auth::user();

        // Check if user has CTO or Director role
        // Adjust this based on your role/permission system
        if ($user->hasRole('CTO')) {
            $this->userRole = 'cto';
        } elseif ($user->hasRole('Director')) {
            $this->userRole = 'director';
        }
    }

    protected function loadExistingApproval()
    {
        if ($this->userRole) {
            $this->existingApproval = ProjectApproval::where('project_id', $this->project->id)
                ->where('approver_role', $this->userRole)
                ->first();

            if ($this->existingApproval) {
                $this->comments = $this->existingApproval->comments;
            }
        }
    }

    public function approve()
    {
        if (!$this->userRole) {
            session()->flash('error', 'You do not have permission to approve projects.');
            return;
        }

        $this->validate();

        ProjectApproval::updateOrCreate(
            [
                'project_id' => $this->project->id,
                'approver_role' => $this->userRole,
            ],
            [
                'approver_id' => Auth::id(),
                'status' => 'approved',
                'comments' => $this->comments,
                'reviewed_at' => now(),
            ]
        );

        // Check if project is fully approved
        if ($this->project->fresh()->isFullyApproved()) {
            $this->project->update(['status' => 'approved']);
            session()->flash('message', 'Project fully approved! Implementation team can now proceed.');
        } else {
            session()->flash('message', 'Your approval has been recorded. Waiting for other approvals.');
        }

        $this->loadExistingApproval();
    }

    public function reject()
    {
        if (!$this->userRole) {
            session()->flash('error', 'You do not have permission to reject projects.');
            return;
        }

        $this->validate();

        ProjectApproval::updateOrCreate(
            [
                'project_id' => $this->project->id,
                'approver_role' => $this->userRole,
            ],
            [
                'approver_id' => Auth::id(),
                'status' => 'rejected',
                'comments' => $this->comments,
                'reviewed_at' => now(),
            ]
        );

        $this->project->update(['status' => 'rejected']);

        session()->flash('message', 'Project has been rejected.');
        $this->loadExistingApproval();
    }

    public function render()
    {
        return view('livewire.projects.project-approvals', [
            'approvals' => $this->project->approvals,
            'budgetItems' => $this->project->budgetItems,
            'totalBudget' => $this->project->budgetItems->sum('total_cost'),
        ])->layout('layouts.app');
    }
}
