<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\ProjectBudgetItem;
use App\Models\Currency;
use App\Helpers\DataHelper;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProjectBudget extends Component
{
    public Project $project;
    public $pendingItems = [];
    public $item_name = '';
    public $description = '';
    public $category = '';
    public $quantity = 1;
    public $unit = '';
    public $unit_cost = 0;
    public $justification = '';

    public $editingItemId = null;
    public $editingPendingIndex = null;
    public $showAddForm = false;

    public $currency = 'USD';
    public $currencies = [];

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
        // Check permission to view project budget
        if (!Auth::user()->can('view_project_budget')) {
            abort(403, 'Unauthorized action.');
        }
        
        $this->project = $project;
        
        // Get default currency using DataHelper
        $defaultCurrency = DataHelper::getDefaultCurrency();
        $this->currency = $defaultCurrency ? $defaultCurrency->code : 'USD';
        
        // Get all active currencies for dropdown
        $this->currencies = DataHelper::getCurrencies()->map(function ($currency) {
            return [
                'code' => $currency->code,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
            ];
        })->toArray();
    }

    public function toggleAddForm()
    {
        // Check permission to create budget items
        if (!auth()->user()->can('create_budget_items')) {
            session()->flash('error', 'You do not have permission to add budget items.');
            return;
        }
        $this->showAddForm = !$this->showAddForm;
        $this->resetForm();
    }

    public function addItemToList()
    {
        $this->validate();

        if ($this->editingPendingIndex !== null) {
            // Update existing pending item
            $this->pendingItems[$this->editingPendingIndex] = [
                'item_name' => $this->item_name,
                'description' => $this->description,
                'category' => $this->category,
                'quantity' => $this->quantity,
                'unit' => $this->unit,
                'unit_cost' => $this->unit_cost,
                'total_cost' => $this->quantity * $this->unit_cost,
                'justification' => $this->justification,
            ];
            $this->editingPendingIndex = null;
        } else {
            // Add new item to pending list
            $this->pendingItems[] = [
                'item_name' => $this->item_name,
                'description' => $this->description,
                'category' => $this->category,
                'quantity' => $this->quantity,
                'unit' => $this->unit,
                'unit_cost' => $this->unit_cost,
                'total_cost' => $this->quantity * $this->unit_cost,
                'justification' => $this->justification,
            ];
        }

        $this->resetForm();
    }

    public function editPendingItem($index)
    {
        $item = $this->pendingItems[$index];
        $this->editingPendingIndex = $index;
        $this->item_name = $item['item_name'];
        $this->description = $item['description'];
        $this->category = $item['category'];
        $this->quantity = $item['quantity'];
        $this->unit = $item['unit'];
        $this->unit_cost = $item['unit_cost'];
        $this->justification = $item['justification'];
    }

    public function removePendingItem($index)
    {
        unset($this->pendingItems[$index]);
        $this->pendingItems = array_values($this->pendingItems); // Re-index array
    }

    public function saveAllItems()
    {
        // Check permission to create budget items
        if (!auth()->user()->can('create_budget_items')) {
            session()->flash('error', 'You do not have permission to save budget items.');
            return;
        }
        
        if (empty($this->pendingItems)) {
            session()->flash('error', 'Please add at least one item to the list before saving.');
            return;
        }

        foreach ($this->pendingItems as $item) {
            ProjectBudgetItem::create([
                'project_id' => $this->project->id,
                'item_name' => $item['item_name'],
                'description' => $item['description'],
                'category' => $item['category'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'unit_cost' => $item['unit_cost'],
                'justification' => $item['justification'],
                'added_by' => Auth::id(),
                'status' => 'pending',
            ]);
        }

        $this->pendingItems = [];
        $this->showAddForm = false;
        session()->flash('message', count($this->pendingItems) . ' budget items added successfully!');
    }

    public function cancelAddItems()
    {
        $this->pendingItems = [];
        $this->showAddForm = false;
        $this->resetForm();
    }

    public function editItem($itemId)
    {
        // Check permission to edit budget items
        if (!auth()->user()->can('edit_budget_items')) {
            session()->flash('error', 'You do not have permission to edit budget items.');
            return;
        }
        
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
        // Check permission to edit budget items
        if (!auth()->user()->can('edit_budget_items')) {
            session()->flash('error', 'You do not have permission to update budget items.');
            return;
        }
        
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
        // Check permission to delete budget items
        if (!auth()->user()->can('delete_budget_items')) {
            session()->flash('error', 'You do not have permission to delete budget items.');
            return;
        }
        
        ProjectBudgetItem::findOrFail($itemId)->delete();
        session()->flash('message', 'Budget item deleted successfully!');
    }

    public function submitForApproval()
    {
        // Check permission to submit budget for approval
        if (!auth()->user()->can('submit_budget_for_approval')) {
            session()->flash('error', 'You do not have permission to submit budget for approval.');
            return;
        }
        
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
        
        // Get default currency symbol
        $defaultCurrency = DataHelper::getDefaultCurrency();
        $currencySymbol = $defaultCurrency ? $defaultCurrency->symbol : '$';

        return view('livewire.projects.project-budget', [
            'budgetItems' => $this->project->budgetItems,
            'totalBudget' => $this->project->budgetItems->sum('total_cost'),
            'pendingTotal' => collect($this->pendingItems)->sum('total_cost'),
            'currencies' => $this->currencies,
            'currency' => $this->currency,
            'currencySymbol' => $currencySymbol,
        ]);
    }
}
