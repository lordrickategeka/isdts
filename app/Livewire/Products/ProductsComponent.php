<?php

namespace App\Livewire\Products;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Vendor;
use App\Models\VendorService;

class ProductsComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 6;

    // Form fields
    public $productId;
    public $vendor_id = '';
    public $vendor_service_id = '';
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

    // For dynamic vendor services
    public $vendorServices = [];

    public $isEditing = false;
    public $showModal = false;

    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'vendor_id' => 'required|exists:vendors,id',
        'vendor_service_id' => 'required|exists:vendor_services,id',
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

    public function updatedVendorId($value)
    {
        // Load vendor services when vendor changes
        if ($value) {
            $this->vendorServices = VendorService::where('vendor_id', $value)
                ->orderBy('service_name')
                ->get();
        } else {
            $this->vendorServices = [];
        }
        $this->vendor_service_id = '';
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
        $this->vendor_id = '';
        $this->vendor_service_id = '';
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
        $this->vendorServices = [];
        $this->isEditing = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'vendor_id' => $this->vendor_id,
            'vendor_service_id' => $this->vendor_service_id,
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
        $this->vendor_id = $product->vendor_id;
        $this->vendor_service_id = $product->vendor_service_id;
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

        // Load vendor services for the selected vendor
        $this->vendorServices = VendorService::where('vendor_id', $this->vendor_id)
            ->orderBy('service_name')
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
        $products = Product::with(['vendor', 'vendorService'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%')
                    ->orWhereHas('vendor', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('vendorService', function ($q) {
                        $q->where('service_name', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        $vendors = Vendor::where('status', 'active')->orderBy('name')->get();

        return view('livewire.products.products-component', [
            'products' => $products,
            'vendors' => $vendors,
        ]);
    }
}
