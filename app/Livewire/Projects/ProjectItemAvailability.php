<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\ProjectBudgetItem;
use App\Models\ProjectItemAvailability as ProjectItemAvailabilityModel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProjectItemAvailability extends Component
{
    public Project $project;
    public $selectedItemId = null;
    public $available_quantity = 0;
    public $required_quantity = 0;
    public $availability_status = 'unavailable';
    public $notes = '';
    public $expected_availability_date = '';

    protected $rules = [
        'available_quantity' => 'required|integer|min:0',
        'required_quantity' => 'required|integer|min:1',
        'availability_status' => 'required|in:available,partial,unavailable,ordered',
        'notes' => 'nullable|string',
        'expected_availability_date' => 'nullable|date',
    ];

    public function mount(Project $project)
    {
        $this->project = $project->load(['budgetItems.availability.checker']);
    }

    public function checkAvailability($itemId)
    {
        $item = ProjectBudgetItem::with('availability')->findOrFail($itemId);

        $this->selectedItemId = $itemId;
        $this->required_quantity = $item->quantity;

        if ($item->availability) {
            $this->available_quantity = $item->availability->available_quantity;
            $this->availability_status = $item->availability->availability_status;
            $this->notes = $item->availability->notes;
            $this->expected_availability_date = $item->availability->expected_availability_date?->format('Y-m-d');
        } else {
            $this->available_quantity = 0;
            $this->availability_status = 'unavailable';
            $this->notes = '';
            $this->expected_availability_date = '';
        }
    }

    public function saveAvailability()
    {
        $this->validate();

        // Automatically determine status if not manually set
        if ($this->available_quantity >= $this->required_quantity) {
            $this->availability_status = 'available';
        } elseif ($this->available_quantity > 0) {
            $this->availability_status = 'partial';
        }

        ProjectItemAvailabilityModel::updateOrCreate(
            [
                'project_budget_item_id' => $this->selectedItemId,
                'project_id' => $this->project->id,
            ],
            [
                'available_quantity' => $this->available_quantity,
                'required_quantity' => $this->required_quantity,
                'availability_status' => $this->availability_status,
                'notes' => $this->notes,
                'expected_availability_date' => $this->expected_availability_date ?: null,
                'checked_by' => Auth::id(),
                'checked_at' => now(),
            ]
        );

        // Update project status based on all items availability
        $this->updateProjectStatus();

        $this->selectedItemId = null;
        $this->resetForm();

        session()->flash('message', 'Item availability updated successfully!');
    }

    protected function updateProjectStatus()
    {
        $totalItems = $this->project->budgetItems()->count();
        $checkedItems = ProjectItemAvailabilityModel::where('project_id', $this->project->id)->count();

        if ($totalItems === $checkedItems) {
            $allAvailable = ProjectItemAvailabilityModel::where('project_id', $this->project->id)
                ->whereIn('availability_status', ['available'])
                ->count() === $totalItems;

            if ($allAvailable) {
                $this->project->update(['status' => 'in_progress']);
            } else {
                $this->project->update(['status' => 'checking_availability']);
            }
        }
    }

    public function resetForm()
    {
        $this->available_quantity = 0;
        $this->required_quantity = 0;
        $this->availability_status = 'unavailable';
        $this->notes = '';
        $this->expected_availability_date = '';
    }

    public function render()
    {
        $this->project->load(['budgetItems.availability.checker']);

        return view('livewire.projects.project-item-availability', [
            'budgetItems' => $this->project->budgetItems,
        ]);
    }
}
