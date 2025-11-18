<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\ServiceType;
use App\Models\ServiceSubcategory;

class ProductsComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 6;

    // Form fields
    public $productId;
    public $service_type_id = '';
    public $service_subcategory_id = '';
    public $name = '';
    public $description = '';
    public $price = '';
    public $capacity = '';
    public $installation_charge = '';
    public $monthly_charge = '';
    public $billing_cycle = 'monthly';
    public $specifications = [];
    public $status = 'active';
    public $sort_order = 0;

    // For dynamic subcategories
    public $subcategories = [];

    public $isEditing = false;
    public $showModal = false;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'service_type_id' => 'required|exists:service_types,id',
        'service_subcategory_id' => 'nullable|exists:service_subcategories,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'nullable|numeric|min:0',
        'capacity' => 'nullable|string|max:255',
        'installation_charge' => 'nullable|numeric|min:0',
        'monthly_charge' => 'nullable|numeric|min:0',
        'billing_cycle' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'sort_order' => 'nullable|integer',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatedServiceTypeId($value)
    {
        // Load subcategories when service type changes
        if ($value) {
            $this->subcategories = ServiceSubcategory::where('service_type_id', $value)
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->subcategories = [];
        }
        $this->service_subcategory_id = '';
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
        $this->productId = null;
        $this->service_type_id = '';
        $this->service_subcategory_id = '';
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->capacity = '';
        $this->installation_charge = '';
        $this->monthly_charge = '';
        $this->billing_cycle = 'monthly';
        $this->specifications = [];
        $this->status = 'active';
        $this->sort_order = 0;
        $this->subcategories = [];
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'service_type_id' => $this->service_type_id,
            'service_subcategory_id' => $this->service_subcategory_id ?: null,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'capacity' => $this->capacity,
            'installation_charge' => $this->installation_charge,
            'monthly_charge' => $this->monthly_charge,
            'billing_cycle' => $this->billing_cycle,
            'specifications' => $this->specifications,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
        ];

        if ($this->isEditing) {
            $product = Product::findOrFail($this->productId);
            $product->update($data);
            session()->flash('success', 'Product updated successfully!');
        } else {
            Product::create($data);
            session()->flash('success', 'Product created successfully!');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $product->id;
        $this->service_type_id = $product->service_type_id;
        $this->service_subcategory_id = $product->service_subcategory_id;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->capacity = $product->capacity;
        $this->installation_charge = $product->installation_charge;
        $this->monthly_charge = $product->monthly_charge;
        $this->billing_cycle = $product->billing_cycle;
        $this->specifications = $product->specifications ?? [];
        $this->status = $product->status;
        $this->sort_order = $product->sort_order;
        $this->isEditing = true;

        // Load subcategories for the selected service type
        $this->subcategories = ServiceSubcategory::where('service_type_id', $this->service_type_id)
            ->orderBy('sort_order')
            ->get();

        $this->showModal = true;
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('success', 'Product deleted successfully!');
    }

    public function render()
    {
        $products = Product::with(['serviceType', 'subcategory'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('serviceType', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $serviceTypes = ServiceType::where('status', 'active')->orderBy('name')->get();

        return view('livewire.products.products-component', [
            'products' => $products,
            'serviceTypes' => $serviceTypes,
        ])->layout('layouts.app');
    }
}
