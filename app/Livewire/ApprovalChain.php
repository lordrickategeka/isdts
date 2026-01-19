<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ApprovalChain as ApprovalChainModel;
use Illuminate\Support\Facades\Auth;

class ApprovalChain extends Component
{
    public $name;
    public $scope;
    public $description;
    public $is_active = true;
    public $approvalChains;
    public $editingId = null;

    public function mount()
    {
        $this->loadApprovalChains();
    }

    public function loadApprovalChains()
    {
        $this->approvalChains = ApprovalChainModel::all();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'scope' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($this->editingId) {
            $approvalChain = ApprovalChainModel::findOrFail($this->editingId);
            $approvalChain->update([
                'name' => $this->name,
                'scope' => $this->scope,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'created_by' => Auth::id(),
            ]);
        } else {
            ApprovalChainModel::create([
                'name' => $this->name,
                'scope' => $this->scope,
                'description' => $this->description,
                'is_active' => $this->is_active,
                'created_by' => Auth::id(),
            ]);
        }

        $this->resetForm();
        $this->loadApprovalChains();
    }

    public function edit($id)
    {
        $approvalChain = ApprovalChainModel::findOrFail($id);
        $this->editingId = $approvalChain->id;
        $this->name = $approvalChain->name;
        $this->scope = $approvalChain->scope;
        $this->description = $approvalChain->description;
        $this->is_active = $approvalChain->is_active;
    }

    public function delete($id)
    {
        $approvalChain = ApprovalChainModel::findOrFail($id);
        $approvalChain->delete();
        $this->loadApprovalChains();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->scope = '';
        $this->description = '';
        $this->is_active = true;
    }

    public function render()
    {
        return view('livewire.approval-chain', [
            'approvalChains' => $this->approvalChains,
        ]);
    }
}
