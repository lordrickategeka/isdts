<?php

namespace App\Livewire\Projects\AdvancedDetails;

use Livewire\Component;
use App\Models\ProjectMilestone;
use App\Models\Project;
use App\Models\User;

class MilestonesComponent extends Component
{
    public $projectId;
    public $milestones = [];
    
    // Milestone modal properties
    public $showMilestoneModal = false;
    public $editingMilestoneId = null;
    public $milestone_name = '';
    public $milestone_description = '';
    public $milestone_start_date = '';
    public $milestone_due_date = '';
    public $milestone_amount = '';
    public $milestone_percentage = '';
    public $milestone_is_billable = false;
    public $milestone_status = 'pending';
    public $milestone_priority = 'medium';
    public $milestone_progress = 0;
    public $milestone_deliverables = '';
    public $milestone_notes = '';
    public $milestone_assigned_to = null;
    public $milestone_depends_on = null;

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->loadMilestones();
    }

    public function loadMilestones()
    {
        $this->milestones = ProjectMilestone::where('project_id', $this->projectId)
            ->with(['assignedTo', 'approvedBy', 'createdBy', 'dependsOnMilestone'])
            ->orderBy('due_date')
            ->get();
    }

    public function openAddMilestoneModal()
    {
        $this->resetMilestoneForm();
        $this->showMilestoneModal = true;
    }

    public function closeMilestoneModal()
    {
        $this->showMilestoneModal = false;
        $this->resetMilestoneForm();
        $this->resetValidation();
    }

    public function resetMilestoneForm()
    {
        $this->editingMilestoneId = null;
        $this->milestone_name = '';
        $this->milestone_description = '';
        $this->milestone_start_date = '';
        $this->milestone_due_date = '';
        $this->milestone_amount = '';
        $this->milestone_percentage = '';
        $this->milestone_is_billable = false;
        $this->milestone_status = 'pending';
        $this->milestone_priority = 'medium';
        $this->milestone_progress = 0;
        $this->milestone_deliverables = '';
        $this->milestone_notes = '';
        $this->milestone_assigned_to = null;
        $this->milestone_depends_on = null;
    }

    public function saveMilestone()
    {
        \Log::info('saveMilestone called', [
            'milestone_name' => $this->milestone_name,
            'editingMilestoneId' => $this->editingMilestoneId
        ]);

        // Convert empty strings to null for numeric fields
        $this->milestone_amount = $this->milestone_amount === '' ? null : $this->milestone_amount;
        $this->milestone_percentage = $this->milestone_percentage === '' ? null : $this->milestone_percentage;
        $this->milestone_assigned_to = $this->milestone_assigned_to === '' ? null : $this->milestone_assigned_to;
        $this->milestone_depends_on = $this->milestone_depends_on === '' ? null : $this->milestone_depends_on;

        $validated = $this->validate([
            'milestone_name' => 'required|string|max:255',
            'milestone_description' => 'nullable|string',
            'milestone_start_date' => 'nullable|date',
            'milestone_due_date' => 'nullable|date',
            'milestone_amount' => 'nullable|numeric|min:0',
            'milestone_percentage' => 'nullable|numeric|min:0|max:100',
            'milestone_is_billable' => 'boolean',
            'milestone_status' => 'required|in:pending,in_progress,completed,delayed,cancelled,invoiced',
            'milestone_priority' => 'required|in:low,medium,high,critical',
            'milestone_progress' => 'required|integer|min:0|max:100',
            'milestone_deliverables' => 'nullable|string',
            'milestone_notes' => 'nullable|string',
            'milestone_assigned_to' => 'nullable|exists:users,id',
            'milestone_depends_on' => 'nullable|exists:project_milestones,id',
        ]);

        \Log::info('Validation passed', ['validated' => $validated]);

        try {
            if ($this->editingMilestoneId) {
                $milestone = ProjectMilestone::findOrFail($this->editingMilestoneId);
                $milestone->update([
                    'name' => $validated['milestone_name'],
                    'description' => $validated['milestone_description'],
                    'start_date' => $validated['milestone_start_date'],
                    'due_date' => $validated['milestone_due_date'],
                    'amount' => $validated['milestone_amount'],
                    'percentage' => $validated['milestone_percentage'],
                    'is_billable' => $validated['milestone_is_billable'],
                    'status' => $validated['milestone_status'],
                    'priority' => $validated['milestone_priority'],
                    'progress_percentage' => $validated['milestone_progress'],
                    'deliverables' => $validated['milestone_deliverables'],
                    'notes' => $validated['milestone_notes'],
                    'assigned_to' => $validated['milestone_assigned_to'],
                    'depends_on_milestone_id' => $validated['milestone_depends_on'],
                ]);
                \Log::info('Milestone updated', ['id' => $milestone->id]);
                session()->flash('success', 'Milestone updated successfully.');
            } else {
                $milestone = ProjectMilestone::create([
                    'project_id' => $this->projectId,
                    'name' => $validated['milestone_name'],
                    'description' => $validated['milestone_description'],
                    'start_date' => $validated['milestone_start_date'],
                    'due_date' => $validated['milestone_due_date'],
                    'amount' => $validated['milestone_amount'],
                    'percentage' => $validated['milestone_percentage'],
                    'is_billable' => $validated['milestone_is_billable'],
                    'status' => $validated['milestone_status'],
                    'priority' => $validated['milestone_priority'],
                    'progress_percentage' => $validated['milestone_progress'],
                    'deliverables' => $validated['milestone_deliverables'],
                    'notes' => $validated['milestone_notes'],
                    'assigned_to' => $validated['milestone_assigned_to'],
                    'depends_on_milestone_id' => $validated['milestone_depends_on'],
                    'created_by' => auth()->id(),
                ]);
                \Log::info('Milestone created', ['id' => $milestone->id]);
                session()->flash('success', 'Milestone created successfully.');
            }

            $this->loadMilestones();
            $this->closeMilestoneModal();
        } catch (\Exception $e) {
            \Log::error('Failed to save milestone', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Failed to save milestone: ' . $e->getMessage());
        }
    }

    public function editMilestone($milestoneId)
    {
        $milestone = ProjectMilestone::findOrFail($milestoneId);
        
        $this->editingMilestoneId = $milestone->id;
        $this->milestone_name = $milestone->name;
        $this->milestone_description = $milestone->description;
        $this->milestone_start_date = $milestone->start_date?->format('Y-m-d');
        $this->milestone_due_date = $milestone->due_date?->format('Y-m-d');
        $this->milestone_amount = $milestone->amount;
        $this->milestone_percentage = $milestone->percentage;
        $this->milestone_is_billable = $milestone->is_billable;
        $this->milestone_status = $milestone->status;
        $this->milestone_priority = $milestone->priority;
        $this->milestone_progress = $milestone->progress_percentage;
        $this->milestone_deliverables = $milestone->deliverables;
        $this->milestone_notes = $milestone->notes;
        $this->milestone_assigned_to = $milestone->assigned_to;
        $this->milestone_depends_on = $milestone->depends_on_milestone_id;
        
        $this->showMilestoneModal = true;
    }

    public function deleteMilestone($milestoneId)
    {
        try {
            $milestone = ProjectMilestone::findOrFail($milestoneId);
            $milestone->delete();
            
            $this->loadMilestones();
            session()->flash('success', 'Milestone deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete milestone: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $users = User::orderBy('name')->get();
        $availableMilestones = ProjectMilestone::where('project_id', $this->projectId)
            ->where('id', '!=', $this->editingMilestoneId)
            ->get();

        return view('livewire.projects.advanced-details.milestones-component', [
            'users' => $users,
            'availableUsers' => $users,
            'availableMilestones' => $availableMilestones,
        ]);
    }
}
