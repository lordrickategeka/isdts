<?php

namespace App\Livewire\Vendors;

use Livewire\Component;
use App\Models\Vendor;
use App\Models\VendorService;
use App\Models\Product;

class VendorCreateComponent extends Component
{
    // Form fields
    public $name = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $status = 'active';
    public $notes = '';

    // Services
    public $services = [];
    public $newServiceName = '';
    public $newServiceDescription = '';

    // Products
    public $newProductName = '';
    public $newProductDescription = '';
    public $newProductCapacity = '';
    public $newProductInstallationCharge = '';
    public $newProductMonthlyCharge = '';
    public $newProductBillingCycle = 'monthly';
    public $newProductStatus = 'active';
    public $selectedServiceIndex = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'notes' => 'nullable|string',
    ];

    protected $messages = [
        'name.required' => 'Vendor name is required.',
        'email.email' => 'Please enter a valid email address.',
    ];

    public function addService()
    {
        $this->validate([
            'newServiceName' => 'required|string|max:255',
        ], [
            'newServiceName.required' => 'Service name is required.',
        ]);

        $this->services[] = [
            'service_name' => $this->newServiceName,
            'description' => $this->newServiceDescription,
            'products' => [],
        ];

        $this->newServiceName = '';
        $this->newServiceDescription = '';
    }

    public function removeService($index)
    {
        unset($this->services[$index]);
        $this->services = array_values($this->services);
    }

    public function addProduct($serviceIndex)
    {
        $this->validate([
            'newProductName' => 'required|string|max:255',
        ], [
            'newProductName.required' => 'Product name is required.',
        ]);

        $this->services[$serviceIndex]['products'][] = [
            'name' => $this->newProductName,
            'description' => $this->newProductDescription,
            'capacity' => $this->newProductCapacity,
            'installation_charge' => $this->newProductInstallationCharge,
            'monthly_charge' => $this->newProductMonthlyCharge,
            'billing_cycle' => $this->newProductBillingCycle,
            'status' => $this->newProductStatus,
        ];

        $this->resetProductForm();
    }

    public function removeProduct($serviceIndex, $productIndex)
    {
        unset($this->services[$serviceIndex]['products'][$productIndex]);
        $this->services[$serviceIndex]['products'] = array_values($this->services[$serviceIndex]['products']);
    }

    public function resetProductForm()
    {
        $this->newProductName = '';
        $this->newProductDescription = '';
        $this->newProductCapacity = '';
        $this->newProductInstallationCharge = '';
        $this->newProductMonthlyCharge = '';
        $this->newProductBillingCycle = 'monthly';
        $this->newProductStatus = 'active';
        $this->selectedServiceIndex = null;
    }

    public function save()
    {
        $this->validate();

        $vendor = Vendor::create([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);

        // Create services and products
        foreach ($this->services as $serviceData) {
            $service = $vendor->services()->create([
                'service_name' => $serviceData['service_name'],
                'description' => $serviceData['description'],
            ]);

            // Create products for this service
            if (isset($serviceData['products']) && count($serviceData['products']) > 0) {
                foreach ($serviceData['products'] as $productData) {
                    Product::create([
                        'vendor_id' => $vendor->id,
                        'vendor_service_id' => $service->id,
                        'name' => $productData['name'],
                        'description' => $productData['description'] ?: null,
                        'capacity' => $productData['capacity'] ?: null,
                        'installation_charge' => $productData['installation_charge'] ?: null,
                        'monthly_charge' => $productData['monthly_charge'] ?: null,
                        'billing_cycle' => $productData['billing_cycle'],
                        'status' => $productData['status'],
                    ]);
                }
            }
        }

        session()->flash('success', 'Vendor created successfully!');

        return redirect()->route('vendors.index');
    }

    public function cancel()
    {
        return redirect()->route('vendors.index');
    }

    public function render()
    {
        return view('livewire.vendors.vendor-create-component');
    }
}
