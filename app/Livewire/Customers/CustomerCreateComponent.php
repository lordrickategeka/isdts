<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Project;
use App\Models\Vendor;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\ServiceType;
use Illuminate\Support\Facades\DB;

class CustomerCreateComponent extends Component
{
    use WithFileUploads;
    public $project_id;
    public $client_name;
    public $mode = 'project';
    public $phone;
    public $email;
    public $coordinates_auto = false;
    public $latitude;
    public $longitude;
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
    public $service_types = [];
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

    // public function closeCreateModal()
    // {
    //     $this->showCreateModal = false;
    //     $this->resetForm();
    //     $this->resetValidation();
    // }

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
        'client_name' => 'required|string|max:191',
        'phone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:191',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'region' => 'nullable|string|max:191',
        'district' => 'nullable|string|max:191',
        'vendor_id' => 'nullable|exists:vendors,id',
        'transmission' => 'nullable|exists:service_types,id',
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
        $this->service_types = ServiceType::orderBy('name')->get();
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $client = Client::create([
                'company' => $this->client_name,
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
                    'service_type_id' => $this->transmission ?: null,
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
