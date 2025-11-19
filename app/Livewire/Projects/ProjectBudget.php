<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\ProjectBudgetItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProjectBudget extends Component
{
    public Project $project;
    public $item_name = '';
    public $description = '';
    public $category = '';
    public $quantity = 1;
    public $unit = '';
    public $unit_cost = 0;
    public $justification = '';

    public $editingItemId = null;
    public $showAddForm = false;

    protected $rules = [
        'item_name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'category' => 'nullable|string|max:255',
        'quantity' => 'required|integer|min:1',
        'unit' => 'nullable|string|max:50',
        'unit_cost' => 'required|numeric|min:0',
        'justification' => 'nullable|string',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function toggleAddForm()
    {
        $this->showAddForm = !$this->showAddForm;
        $this->resetForm();
    }

    public function addBudgetItem()
    {
        $this->validate();

        ProjectBudgetItem::create([
            'project_id' => $this->project->id,
            'item_name' => $this->item_name,
            'description' => $this->description,
            'category' => $this->category,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'unit_cost' => $this->unit_cost,
            'justification' => $this->justification,
            'added_by' => Auth::id(),
            'status' => 'pending',
        ]);

        $this->resetForm();
        $this->showAddForm = false;
        session()->flash('message', 'Budget item added successfully!');
    }

    public function editItem($itemId)
    {
        $item = ProjectBudgetItem::findOrFail($itemId);

        $this->editingItemId = $itemId;
        $this->item_name = $item->item_name;
        $this->description = $item->description;
        $this->category = $item->category;
        $this->quantity = $item->quantity;
        $this->unit = $item->unit;
        $this->unit_cost = $item->unit_cost;
        $this->justification = $item->justification;
        $this->showAddForm = true;
    }

    public function updateBudgetItem()
    {
        $this->validate();

        $item = ProjectBudgetItem::findOrFail($this->editingItemId);
        $item->update([
            'item_name' => $this->item_name,
            'description' => $this->description,
            'category' => $this->category,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'unit_cost' => $this->unit_cost,
            'justification' => $this->justification,
        ]);

        $this->resetForm();
        $this->showAddForm = false;
        $this->editingItemId = null;
        session()->flash('message', 'Budget item updated successfully!');
    }

    public function deleteItem($itemId)
    {
        ProjectBudgetItem::findOrFail($itemId)->delete();
        session()->flash('message', 'Budget item deleted successfully!');
    }

    public function submitForApproval()
    {
        if ($this->project->budgetItems()->count() === 0) {
            session()->flash('error', 'Please add at least one budget item before submitting for approval.');
            return;
        }

        $this->project->update(['status' => 'pending_approval']);

        session()->flash('message', 'Project budget submitted for approval!');
        return redirect()->route('projects.list');
    }

    public function resetForm()
    {
        $this->item_name = '';
        $this->description = '';
        $this->category = '';
        $this->quantity = 1;
        $this->unit = '';
        $this->unit_cost = 0;
        $this->justification = '';
        $this->editingItemId = null;
    }

    public function render()
    {
        $this->project->load(['budgetItems.addedBy']);

        return view('livewire.projects.project-budget', [
            'budgetItems' => $this->project->budgetItems,
            'totalBudget' => $this->project->budgetItems->sum('total_cost'),
        ]);
    }
}
