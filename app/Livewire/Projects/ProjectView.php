<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Livewire\Component;

class ProjectView extends Component
{
    public $projectId;
    public $project;

    // Project details
    public $project_code;
    public $name;
    public $description;
    public $start_date;
    public $end_date;
    public $estimated_budget;
    public $actual_budget;
    public $status;
    public $priority;
    public $objectives;
    public $deliverables;

    // Related data
    public $client;
    public $creator;
    public $budgetItems = [];
    public $approvals = [];
    public $itemAvailability = [];

    // UI state
    public $activeTab = 'project-sites';

    // Statistics
    public $totalBudget = 0;
    public $totalItems = 0;
    public $approvalStatus = '';
    public $showPrintButton = true;

    public $demoClients;

    public function mount($project)
    {
        $this->projectId = $project;
        $this->loadProjectData();

        $this->demoClients = collect([
            [
                'id' => 1,
                'company' => 'Acme Corp',
                'contact_person' => 'Jane Doe',
                'category' => 'Corporate',
                'category_type' => 'Enterprise',
                'email' => 'jane.doe@acme.example',
                'phone' => '+256 700 000001',
                'services' => [
                    ['service' => 'Internet', 'product' => 'Fiber 100Mbps', 'capacity' => '100Mbps', 'monthly_charge' => 120000],
                ],
                'payment_type' => 'postpaid',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'company' => 'Beta Solutions',
                'contact_person' => 'John Smith',
                'category' => 'Home',
                'category_type' => null,
                'email' => 'john@beta.example',
                'phone' => '+256 700 000002',
                'services' => [],
                'payment_type' => 'prepaid',
                'status' => 'pending_approval',
            ],
            [
                'id' => 3,
                'company' => 'Gamma Traders',
                'contact_person' => 'Alice N',
                'category' => 'SME',
                'category_type' => 'Retail',
                'email' => 'alice@gamma.example',
                'phone' => '+256 700 000003',
                'services' => [
                    ['service' => 'Hosting', 'product' => 'Business Host', 'capacity' => null, 'monthly_charge' => 30000],
                ],
                'payment_type' => null,
                'status' => 'approved',
            ],
        ]);
    }


    public function loadProjectData()
    {
        $this->project = Project::with([
            'client',
            'creator',
            'budgetItems.addedBy',
            'budgetItems.availability.vendor',
            'approvals.approver',
            'itemAvailability.budgetItem',
            'itemAvailability.vendor'
        ])->findOrFail($this->projectId);

        // Project basic info
        $this->project_code = $this->project->project_code;
        $this->name = $this->project->name;
        $this->description = $this->project->description;
        $this->start_date = $this->project->start_date;
        $this->end_date = $this->project->end_date;
        $this->estimated_budget = $this->project->estimated_budget;
        $this->status = $this->project->status;
        $this->priority = $this->project->priority;
        $this->objectives = $this->project->objectives;
        $this->deliverables = $this->project->deliverables;

        // Related data
        $this->client = $this->project->client;
        $this->creator = $this->project->creator;
        $this->budgetItems = $this->project->budgetItems;
        $this->approvals = $this->project->approvals;
        $this->itemAvailability = $this->project->itemAvailability;

        // Calculate statistics
        $this->totalBudget = $this->project->getTotalBudgetAttribute();
        $this->actual_budget = $this->totalBudget;
        $this->totalItems = $this->budgetItems->count();
        $this->approvalStatus = $this->project->getApprovalStatus();
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'draft' => 'bg-gray-100 text-gray-700',
            'budget_planning' => 'bg-blue-100 text-blue-700',
            'pending_approval' => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700',
            'in_progress' => 'bg-indigo-100 text-indigo-700',
            'checking_availability' => 'bg-purple-100 text-purple-700',
            'on_hold' => 'bg-orange-100 text-orange-700',
            'completed' => 'bg-teal-100 text-teal-700',
            'cancelled' => 'bg-gray-100 text-gray-500',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function getPriorityBadgeClass($priority)
    {
        return match($priority) {
            'low' => 'bg-gray-100 text-gray-700',
            'medium' => 'bg-blue-100 text-blue-700',
            'high' => 'bg-orange-100 text-orange-700',
            'critical' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function render()
    {
        return view('livewire.projects.project-view')->layout('layouts.app');
    }
}
