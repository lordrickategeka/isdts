<?php

namespace App\Livewire\ServiceFeasibility;

use App\Models\ClientService;
use App\Models\ServiceFeasibility;
use App\Models\ServiceFeasibilityVendor;
use App\Models\ServiceFeasibilityMaterial;
use App\Models\Vendor;
use App\Models\VendorService;
use Livewire\Component;
use App\Services\ServiceFeasibilityVendorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ManageFeasibility extends Component
{
    public $clientService;
    public $feasibility;

    // Feasibility main details
    public $is_feasible = false;
    public $notes = '';
    public $status = 'pending';

    // Vendors
    public $vendors = [];
    public $selectedVendor;
    public $selectedVendorService;
    public $nrc_cost = 0;
    public $mrc_cost = 0;
    public $vendor_notes = '';
    public $vendorServices = [];
    public $loadingVendorServices = false;
    public $vendorServicesFallbackForVendorId = null;

    // Materials
    public $materials = [];
    public $material_name = '';
    public $material_quantity = 1;
    public $material_unit_cost = 0;
    public $material_description = '';

    // Modal states
    public $showVendorModal = false;
    public $showMaterialModal = false;
    public $editingVendorId = null;
    public $editingMaterialId = null;

    protected $rules = [
        'is_feasible' => 'required|boolean',
        'notes' => 'nullable|string',
        'selectedVendor' => 'nullable|exists:vendors,id',
        'selectedVendorService' => 'nullable|exists:vendor_services,id',
        'nrc_cost' => 'required|numeric|min:0',
        'mrc_cost' => 'required|numeric|min:0',
        'vendor_notes' => 'nullable|string',
        'material_name' => 'required|string|max:255',
        'material_quantity' => 'required|integer|min:1',
        'material_unit_cost' => 'required|numeric|min:0',
        'material_description' => 'nullable|string',
    ];

    public function mount($clientServiceId)
    {
        $this->clientService = ClientService::with(['serviceType', 'product', 'client'])->findOrFail($clientServiceId);

        // Load existing feasibility if it exists
        $this->feasibility = ServiceFeasibility::with(['vendors.vendor', 'vendors.vendorService', 'materials'])
            ->where('client_service_id', $this->clientService->id)
            ->first();

        if ($this->feasibility) {
            $this->is_feasible = $this->feasibility->is_feasible;
            $this->notes = $this->feasibility->notes;
            $this->status = $this->feasibility->status;
            $this->vendors = $this->feasibility->vendors->toArray();
            $this->materials = $this->feasibility->materials->toArray();
        }

        // If a vendor is pre-selected (rare), load its services
        if ($this->selectedVendor) {
            $this->loadVendorServices();
        }
    }

    public function saveFeasibility()
    {
        $this->validate([
            'is_feasible' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        if (!$this->feasibility) {
            $this->feasibility = ServiceFeasibility::create([
                'client_service_id' => $this->clientService->id,
                'is_feasible' => $this->is_feasible,
                'notes' => $this->notes,
                'status' => $this->status,
                'created_by' => Auth::user()->id,
            ]);
        } else {
            $this->feasibility->update([
                'is_feasible' => $this->is_feasible,
                'notes' => $this->notes,
                'status' => $this->status,
            ]);
        }

        session()->flash('success', 'Feasibility details saved successfully.');
    }

    public function openVendorModal()
    {
        $this->resetVendorForm();
        // Clear any previously loaded services so the list only shows services
        // relevant to the currently selected vendor (or when editing).
        $this->vendorServices = collect();
        $this->vendorServicesFallbackForVendorId = null;
        $this->showVendorModal = true;
    }

    public function closeVendorModal()
    {
        $this->showVendorModal = false;
        $this->resetVendorForm();
    }

    public function resetVendorForm()
    {
        $this->selectedVendor = null;
        $this->selectedVendorService = null;
        $this->nrc_cost = 0;
        $this->mrc_cost = 0;
        $this->vendor_notes = '';
        $this->editingVendorId = null;
    }

    public function addVendor()
    {
        $this->validate([
            'selectedVendor' => 'required|exists:vendors,id',
            'nrc_cost' => 'required|numeric|min:0',
            'mrc_cost' => 'required|numeric|min:0',
        ]);

        if (!$this->feasibility) {
            $this->saveFeasibility();
        }

        $svc = new ServiceFeasibilityVendorService();
        try {
            $svc->ensureServiceBelongsToVendor($this->selectedVendorService, $this->selectedVendor);
            $svc->ensureUniqueVendorForFeasibility($this->feasibility->id, $this->selectedVendor);

            $svc->createVendorEntry([
                'service_feasibility_id' => $this->feasibility->id,
                'vendor_id' => $this->selectedVendor,
                'vendor_service_id' => $this->selectedVendorService,
                'nrc_cost' => $this->nrc_cost,
                'mrc_cost' => $this->mrc_cost,
                'notes' => $this->vendor_notes,
            ]);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
            return;
        }

        $this->loadVendors();
        $this->closeVendorModal();
        session()->flash('success', 'Vendor added successfully.');
    }

    public function editVendor($vendorId)
    {
        $vendor = ServiceFeasibilityVendor::findOrFail($vendorId);
        $this->editingVendorId = $vendorId;
        $this->selectedVendor = $vendor->vendor_id;
        $this->selectedVendorService = $vendor->vendor_service_id;
        $this->nrc_cost = $vendor->nrc_cost;
        $this->mrc_cost = $vendor->mrc_cost;
        $this->vendor_notes = $vendor->notes;
        // Ensure vendor services are loaded for the selected vendor before showing modal
        $this->loadVendorServices();
        $this->showVendorModal = true;
    }

    /**
     * Livewire hook: called when $selectedVendor changes.
     * Loads services for the newly selected vendor and toggles loading flag.
     *
     * @param mixed $value
     * @return void
     */
    public function updatedSelectedVendor($value)
    {
        $this->loadVendorServices();
    }

    /**
     * Load vendor services into the public property and set loading flag.
     *
     * @return void
     */
    protected function loadVendorServices()
    {
        $this->loadingVendorServices = true;

        Log::info('loadVendorServices called', ['selectedVendor' => $this->selectedVendor]);

        $this->vendorServices = $this->selectedVendor
            ? VendorService::where('vendor_id', $this->selectedVendor)->get()
            : collect();

        Log::info('vendorServices loaded', ['count' => is_countable($this->vendorServices) ? count($this->vendorServices) : ($this->vendorServices->count() ?? 0)]);

        $this->loadingVendorServices = false;
    }

    public function updateVendor()
    {
        $this->validate([
            'selectedVendor' => 'required|exists:vendors,id',
            'nrc_cost' => 'required|numeric|min:0',
            'mrc_cost' => 'required|numeric|min:0',
        ]);

        $svc = new ServiceFeasibilityVendorService();
        try {
            $svc->ensureServiceBelongsToVendor($this->selectedVendorService, $this->selectedVendor);
            $svc->ensureUniqueVendorForFeasibility($this->feasibility->id, $this->selectedVendor, $this->editingVendorId);

            $svc->updateVendorEntry($this->editingVendorId, [
                'vendor_id' => $this->selectedVendor,
                'vendor_service_id' => $this->selectedVendorService,
                'nrc_cost' => $this->nrc_cost,
                'mrc_cost' => $this->mrc_cost,
                'notes' => $this->vendor_notes,
            ]);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
            return;
        }

        $this->loadVendors();
        $this->closeVendorModal();
        session()->flash('success', 'Vendor updated successfully.');
    }

    public function deleteVendor($vendorId)
    {
        ServiceFeasibilityVendor::findOrFail($vendorId)->delete();
        $this->loadVendors();
        session()->flash('success', 'Vendor removed successfully.');
    }

    public function openMaterialModal()
    {
        $this->resetMaterialForm();
        $this->showMaterialModal = true;
    }

    public function closeMaterialModal()
    {
        $this->showMaterialModal = false;
        $this->resetMaterialForm();
    }

    public function resetMaterialForm()
    {
        $this->material_name = '';
        $this->material_quantity = 1;
        $this->material_unit_cost = 0;
        $this->material_description = '';
        $this->editingMaterialId = null;
    }

    public function addMaterial()
    {
        $this->validate([
            'material_name' => 'required|string|max:255',
            'material_quantity' => 'required|integer|min:1',
            'material_unit_cost' => 'required|numeric|min:0',
        ]);

        if (!$this->feasibility) {
            $this->saveFeasibility();
        }

        ServiceFeasibilityMaterial::create([
            'service_feasibility_id' => $this->feasibility->id,
            'name' => $this->material_name,
            'quantity' => $this->material_quantity,
            'unit_cost' => $this->material_unit_cost,
            'description' => $this->material_description,
        ]);

        $this->loadMaterials();
        $this->closeMaterialModal();
        session()->flash('success', 'Material added successfully.');
    }

    public function editMaterial($materialId)
    {
        $material = ServiceFeasibilityMaterial::findOrFail($materialId);
        $this->editingMaterialId = $materialId;
        $this->material_name = $material->name;
        $this->material_quantity = $material->quantity;
        $this->material_unit_cost = $material->unit_cost;
        $this->material_description = $material->description;
        $this->showMaterialModal = true;
    }

    public function updateMaterial()
    {
        $this->validate([
            'material_name' => 'required|string|max:255',
            'material_quantity' => 'required|integer|min:1',
            'material_unit_cost' => 'required|numeric|min:0',
        ]);

        $material = ServiceFeasibilityMaterial::findOrFail($this->editingMaterialId);
        $material->update([
            'name' => $this->material_name,
            'quantity' => $this->material_quantity,
            'unit_cost' => $this->material_unit_cost,
            'description' => $this->material_description,
        ]);

        $this->loadMaterials();
        $this->closeMaterialModal();
        session()->flash('success', 'Material updated successfully.');
    }

    public function deleteMaterial($materialId)
    {
        ServiceFeasibilityMaterial::findOrFail($materialId)->delete();
        $this->loadMaterials();
        session()->flash('success', 'Material removed successfully.');
    }

    protected function loadVendors()
    {
        if ($this->feasibility) {
            $this->vendors = $this->feasibility->vendors()->with(['vendor', 'vendorService'])->get()->toArray();
        }
    }

    protected function loadMaterials()
    {
        if ($this->feasibility) {
            $this->materials = $this->feasibility->materials()->get()->toArray();
        }
    }

    public function render()
    {
        $allVendors = Vendor::where('status', 'active')->get();
        return view('livewire.service-feasibility.manage-feasibility', [
            'allVendors' => $allVendors,
            'vendorServices' => $this->vendorServices,
        ]);
    }
}
