<?php

namespace App\Livewire\Vendors;

use Livewire\Component;
use App\Models\Vendor;

class VendorViewComponent extends Component
{
    public $vendorId;
    public $vendor;

    public function mount($vendorId)
    {
        $this->vendorId = $vendorId;
        $this->loadVendor();
    }

    public function loadVendor()
    {
        $this->vendor = Vendor::with(['services.products'])
            ->findOrFail($this->vendorId);
    }

    public function render()
    {
        return view('livewire.vendors.vendor-view-component');
    }
}
