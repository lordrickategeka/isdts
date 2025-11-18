<?php

namespace App\Livewire\ServiceTypes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceType;

class ServiceTypesComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 6;

    // Form fields
    public $serviceTypeId;
    public $name = '';
    public $description = '';
    public $base_price = '';
    public $billing_cycle = 'monthly';
    public $status = 'active';

    public $isEditing = false;
    public $showModal = false;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'base_price' => 'nullable|numeric|min:0',
        'billing_cycle' => 'nullable|string',
        'status' => 'required|in:active,inactive',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->serviceTypeId = null;
        $this->name = '';
        $this->description = '';
        $this->base_price = '';
        $this->billing_cycle = 'monthly';
        $this->status = 'active';
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $serviceType = ServiceType::findOrFail($this->serviceTypeId);
            $serviceType->update([
                'name' => $this->name,
                'description' => $this->description,
                'base_price' => $this->base_price,
                'billing_cycle' => $this->billing_cycle,
                'status' => $this->status,
            ]);
            session()->flash('success', 'Service type updated successfully!');
        } else {
            ServiceType::create([
                'name' => $this->name,
                'description' => $this->description,
                'base_price' => $this->base_price,
                'billing_cycle' => $this->billing_cycle,
                'status' => $this->status,
            ]);
            session()->flash('success', 'Service type created successfully!');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $serviceType = ServiceType::findOrFail($id);
        $this->serviceTypeId = $serviceType->id;
        $this->name = $serviceType->name;
        $this->description = $serviceType->description;
        $this->base_price = $serviceType->base_price;
        $this->billing_cycle = $serviceType->billing_cycle;
        $this->status = $serviceType->status;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        ServiceType::findOrFail($id)->delete();
        session()->flash('success', 'Service type deleted successfully!');
    }

    public function render()
    {
        $serviceTypes = ServiceType::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.service-types.service-types-component', [
            'serviceTypes' => $serviceTypes
        ])->layout('layouts.app');
    }
}
