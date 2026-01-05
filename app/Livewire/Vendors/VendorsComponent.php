<?php

namespace App\Livewire\Vendors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vendor;
use App\Models\VendorService;

class VendorsComponent extends Component
{
    use WithPagination;

    // Modal state
    public $showModal = false;
    public $showDetailsModal = false;
    public $isEdit = false;
    public $vendorId;
    public $viewVendor;

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

    // Search and filter
    public $search = '';
    public $statusFilter = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'notes' => 'nullable|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function openEditModal($vendorId)
    {
        $this->resetForm();
        $vendor = Vendor::with('services')->findOrFail($vendorId);

        $this->vendorId = $vendor->id;
        $this->name = $vendor->name;
        $this->email = $vendor->email;
        $this->phone = $vendor->phone;
        $this->address = $vendor->address;
        $this->status = $vendor->status;
        $this->notes = $vendor->notes;

        $this->services = $vendor->services->map(function ($service) {
            return [
                'id' => $service->id,
                'service_name' => $service->service_name,
                'description' => $service->description,
            ];
        })->toArray();

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function viewDetails($vendorId)
    {
        $this->viewVendor = Vendor::with(['services.products'])->findOrFail($vendorId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->viewVendor = null;
    }

    public function addService()
    {
        $this->validate([
            'newServiceName' => 'required|string|max:255',
        ]);

        $this->services[] = [
            'id' => null,
            'service_name' => $this->newServiceName,
            'description' => $this->newServiceDescription,
        ];

        $this->newServiceName = '';
        $this->newServiceDescription = '';
    }

    public function removeService($index)
    {
        unset($this->services[$index]);
        $this->services = array_values($this->services);
    }

    public function save()
    {
        $this->validate();

        if ($this->isEdit) {
            $vendor = Vendor::findOrFail($this->vendorId);
            $vendor->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            // Delete removed services
            $keepIds = collect($this->services)->pluck('id')->filter();
            $vendor->services()->whereNotIn('id', $keepIds)->delete();

            // Update or create services
            foreach ($this->services as $service) {
                if (isset($service['id']) && $service['id']) {
                    VendorService::where('id', $service['id'])->update([
                        'service_name' => $service['service_name'],
                        'description' => $service['description'],
                    ]);
                } else {
                    $vendor->services()->create([
                        'service_name' => $service['service_name'],
                        'description' => $service['description'],
                    ]);
                }
            }

            session()->flash('success', 'Vendor updated successfully!');
        } else {
            $vendor = Vendor::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'status' => $this->status,
                'notes' => $this->notes,
            ]);

            // Create services
            foreach ($this->services as $service) {
                $vendor->services()->create([
                    'service_name' => $service['service_name'],
                    'description' => $service['description'],
                ]);
            }

            session()->flash('success', 'Vendor created successfully!');
        }

        $this->closeModal();
    }

    public function delete($vendorId)
    {
        $vendor = Vendor::findOrFail($vendorId);
        $vendor->delete();

        session()->flash('success', 'Vendor deleted successfully!');
    }

    private function resetForm()
    {
        $this->vendorId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->status = 'active';
        $this->notes = '';
        $this->services = [];
        $this->newServiceName = '';
        $this->newServiceDescription = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        $vendors = Vendor::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('vendor_code', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->with('services')
            ->withCount('services')
            ->latest()
            ->paginate(10);

        return view('livewire.vendors.vendors-component', [
            'vendors' => $vendors,
        ]);
    }
}
