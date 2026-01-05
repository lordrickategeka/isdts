<?php

namespace App\Livewire\ServiceTypes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceType;
use App\Models\ServiceSubcategory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ServiceTypesComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 6;
    public $activeTab = 'service-types'; // Tab state

    // Form fields
    public $serviceTypeId;
    public $name = '';
    public $description = '';
    public $status = 'active';
    public $hasSubcategories = false;

    // Subcategories array
    public $subcategories = [];

    // Products array
    public $products = [];

    public $isEditing = false;
    public $showModal = false;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'hasSubcategories' => 'boolean',
            'subcategories.*.name' => 'required|string|max:255',
            'subcategories.*.description' => 'nullable|string',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.subcategory_index' => 'nullable|integer',
            'products.*.price' => 'nullable|numeric|min:0',
            'products.*.capacity' => 'nullable|string',
            'products.*.installation_charge' => 'nullable|numeric|min:0',
            'products.*.monthly_charge' => 'nullable|numeric|min:0',
            'products.*.billing_cycle' => 'nullable|string',
        ];
    }

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
        $this->status = 'active';
        $this->hasSubcategories = false;
        $this->subcategories = [];
        $this->products = [];
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function addSubcategory()
    {
        $this->subcategories[] = [
            'name' => '',
            'description' => '',
            'sort_order' => count($this->subcategories) + 1,
        ];
    }

    public function removeSubcategory($index)
    {
        unset($this->subcategories[$index]);
        $this->subcategories = array_values($this->subcategories);
    }

    public function addProduct()
    {
        $this->products[] = [
            'name' => '',
            'description' => '',
            'subcategory_index' => $this->hasSubcategories ? null : -1,
            'price' => null,
            'capacity' => '',
            'installation_charge' => null,
            'monthly_charge' => null,
            'billing_cycle' => 'monthly',
            'sort_order' => count($this->products) + 1,
        ];
    }

    public function removeProduct($index)
    {
        unset($this->products[$index]);
        $this->products = array_values($this->products);
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->isEditing) {
                $serviceType = ServiceType::findOrFail($this->serviceTypeId);
                $serviceType->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'status' => $this->status,
                ]);
            } else {
                $serviceType = ServiceType::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'status' => $this->status,
                ]);

                // Create subcategories
                $subcategoryModels = [];
                if ($this->hasSubcategories && count($this->subcategories) > 0) {
                    foreach ($this->subcategories as $index => $subcategory) {
                        $subcategoryModels[$index] = ServiceSubcategory::create([
                            'service_type_id' => $serviceType->id,
                            'name' => $subcategory['name'],
                            'description' => $subcategory['description'] ?? null,
                            'status' => 'active',
                            'sort_order' => $subcategory['sort_order'],
                        ]);
                    }
                }

                // Create products - DISABLED: Products are now managed through Vendors → Vendor Services → Products
                // Use the Products management page to create products
                if (count($this->products) > 0) {
                    session()->flash('warning', 'Product creation is now managed through Vendors. Please use the Products page to create products under Vendor Services.');
                }
            }

            session()->flash('success', $this->isEditing ? 'Service type updated successfully!' : 'Service type created successfully!');
        });

        $this->closeModal();
    }

    public function edit($id)
    {
        $serviceType = ServiceType::findOrFail($id);
        $this->serviceTypeId = $serviceType->id;
        $this->name = $serviceType->name;
        $this->description = $serviceType->description;
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
        $serviceTypes = [];
        $subcategories = [];

        if ($this->activeTab === 'service-types') {
            $serviceTypes = ServiceType::query()
                ->with(['subcategories.products', 'products'])
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        } elseif ($this->activeTab === 'subcategories') {
            $subcategories = ServiceSubcategory::query()
                ->with(['serviceType', 'products'])
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        }

        return view('livewire.service-types.service-types-component', [
            'serviceTypes' => $serviceTypes,
            'subcategories' => $subcategories,
        ]);
    }
}
