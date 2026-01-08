<?php

namespace App\Livewire\ServiceTypes;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VendorService;
use App\Models\Vendor;
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
                $vendorService = VendorService::findOrFail($this->serviceTypeId);
                $vendorService->update([
                    'service_name' => $this->name,
                    'description' => $this->description,
                ]);
            } else {
                $vendorService = VendorService::create([
                    'vendor_id' => $this->vendor_id ?? 1, // You'll need to add vendor_id property
                    'service_name' => $this->name,
                    'description' => $this->description,
                ]);

                // Create products through vendor services
                if (count($this->products) > 0) {
                    foreach ($this->products as $product) {
                        Product::create([
                            'vendor_id' => $vendorService->vendor_id,
                            'vendor_service_id' => $vendorService->id,
                            'name' => $product['name'],
                            'description' => $product['description'] ?? null,
                            'price' => $product['price'] ?? null,
                            'status' => 'active',
                        ]);
                    }
                }
            }

            session()->flash('success', $this->isEditing ? 'Service updated successfully!' : 'Service created successfully!');
        });

        $this->closeModal();
    }

    public function edit($id)
    {
        $vendorService = VendorService::findOrFail($id);
        $this->serviceTypeId = $vendorService->id;
        $this->name = $vendorService->service_name;
        $this->description = $vendorService->description;
        $this->isEditing = true;
        $this->showModal = true;
    }

    public function delete($id)
    {
        VendorService::findOrFail($id)->delete();
        session()->flash('success', 'Service deleted successfully!');
    }

    public function render()
    {
        $serviceTypes = [];

        if ($this->activeTab === 'service-types') {
            $serviceTypes = VendorService::query()
                ->with(['vendor', 'products'])
                ->when($this->search, function ($query) {
                    $query->where('service_name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                })
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        }

        return view('livewire.service-types.service-types-component', [
            'serviceTypes' => $serviceTypes,
            'subcategories' => [],
        ]);
    }
}
