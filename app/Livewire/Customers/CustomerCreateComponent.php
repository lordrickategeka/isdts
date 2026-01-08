<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\VendorService;
use App\Models\Product;
use App\Models\Region;
use App\Models\District;
use Illuminate\Support\Facades\DB;

class CustomerCreateComponent extends Component
{
    use WithFileUploads;
    public $project_id;
    public $customer_name;
    public $mode = 'project';
    public $phone;
    public $email;
    public $coordinates_auto = false;
    public $latitude;
    public $longitude;
    public $region_id;
    public $district_id;
    public $region;
    public $district;
    public $vendor_id;
    public $transmission;
    public $nrc;
    public $mrc;
    public $vlan;
    public $capacity;
    public $installation_date;
    public $installation_engineer;
    public $status = 'pending';

    public $projects = [];
    public $vendors = [];
    public $vendor_services = [];
    public $regions = [];
    public $districts = [];
    public $showImportModal = false;
    public $importMode = 'project';
    public $importProjectId = null;
    public $importFile;

    public function updatedMode($value)
    {
        // when switching away from project mode, clear project-specific fields
        if ($value !== 'project') {
            $this->project_id = null;
            $this->nrc = null;
            $this->mrc = null;
        }
    }

    public function updatedVendorId($value)
    {
        // Load products for the selected vendor
        if ($value) {
            $this->vendor_services = Product::whereHas('vendorService', function($q) use ($value) {
                $q->where('vendor_id', $value);
            })->orderBy('name')->get();
        } else {
            $this->vendor_services = [];
        }
        // Reset transmission when vendor changes
        $this->transmission = null;
    }

    public function updatedRegionId($value)
    {
        // Load districts for the selected region
        if ($value) {
            $this->districts = District::where('region_id', $value)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
            // Set region name for backward compatibility
            $region = Region::find($value);
            $this->region = $region ? $region->name : null;
        } else {
            $this->districts = [];
            $this->region = null;
        }
        // Reset district when region changes
        $this->district_id = null;
        $this->district = null;
    }

    public function updatedDistrictId($value)
    {
        // Set district name for backward compatibility
        if ($value) {
            $district = District::find($value);
            $this->district = $district ? $district->name : null;
        } else {
            $this->district = null;
        }
    }

    // Called from the Blade to switch tabs (avoids inline $set usage)
    public function setMode($mode)
    {
        $this->mode = $mode;
        $this->updatedMode($mode);
    }

    // Called from the Blade when Import button clicked; emits an event for JS/modal
    public function openImportModal()
    {
        $this->showImportModal = true;
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->importFile = null;
        $this->importProjectId = null;
        $this->importMode = 'project';
    }


    public function importConfirm()
    {
        // minimal validation for modal inputs
        $rules = [
            'importMode' => 'required|in:project,customer',
        ];

        if ($this->importMode === 'project') {
            $rules['importProjectId'] = 'required|exists:projects,id';
        }
        // require a CSV file
        $rules['importFile'] = 'required|file|mimes:csv,txt';

        $this->validate($rules);

        try {
            // store uploaded file in storage/app/imports
            $path = $this->importFile->store('imports');

            // In a real implementation you'd dispatch a job to parse/process the file.
            $msg = 'Import queued for ' . ($this->importMode === 'project' ? 'project' : 'customers');
            if ($this->importMode === 'project' && $this->importProjectId) {
                $proj = collect($this->projects)->firstWhere('id', $this->importProjectId);
                if ($proj) $msg .= ' (' . $proj->name . ')';
            }
            $msg .= ": file saved to {$path}";

            session()->flash('success', $msg);
        } catch (\Exception $e) {
            $this->addError('import', 'Failed to store uploaded file: ' . $e->getMessage());
            return;
        }

        $this->closeImportModal();
    }

    protected $rules = [
        'project_id' => 'nullable|exists:projects,id',
        'customer_name' => 'required|string|max:191',
        'phone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:191',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'region' => 'nullable|string|max:191',
        'district' => 'nullable|string|max:191',
        'vendor_id' => 'nullable|exists:vendors,id',
        'transmission' => 'nullable|exists:products,id',
        'nrc' => 'nullable|string|max:191',
        'mrc' => 'nullable|numeric',
        'vlan' => 'nullable|string|max:191',
        'capacity' => 'nullable|string|max:191',
        'installation_date' => 'nullable|date',
        'installation_engineer' => 'nullable|string|max:191',
        'status' => 'nullable|string|max:50',
    ];

    public function mount()
    {
        $this->projects = Project::orderBy('name')->get();
        $this->vendors = Vendor::orderBy('name')->get();
        $this->regions = Region::where('is_active', true)->orderBy('name')->get();
        // vendor_services and districts will be loaded dynamically when vendor/region is selected
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $client = Client::create([
                'customer_name' => $this->customer_name,
                'contact_person' => $this->installation_engineer ?: null,
                'phone' => $this->phone,
                'email' => $this->email,
                'city' => $this->district,
                'state' => $this->region,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'status' => $this->status,
            ]);

            // If service details present, create a ClientService
            if ($this->capacity || $this->mrc || $this->nrc) {
                ClientService::create([
                    'client_id' => $client->id,
                    'project_id' => $this->project_id ?: null,
                    'vendor_id' => $this->vendor_id ?: null,
                    'product_id' => $this->transmission ?: null,
                    'capacity' => $this->capacity,
                    'installation_charge' => $this->nrc ?: null,
                    'monthly_charge' => $this->mrc ?: null,
                    'status' => $this->status,
                ]);
            }

            DB::commit();

            session()->flash('success', 'Customer created (demo/save) successfully.');
            return redirect()->route('customers.index');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('save', 'Failed to create customer: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.customers.customer-create-component');
    }
}
