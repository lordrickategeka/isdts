<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use App\Models\Vendor;
use App\Models\VendorService;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\District;
use App\Models\Product;
use App\Models\Region;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Services\ServiceFeasibilityVendorService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ClientsImport;

class ProjectView extends Component
{
    use WithFileUploads, WithPagination;
    public $projectId;
    public $project;
    public $project_clients;

    // Project details
    public $project_code;
    public $name;
    public $description;
    public $start_date;
    public $end_date;
    public $estimated_budget;
    public $actual_budget;
    public $status;
    public $priority;
    public $objectives;
    public $deliverables;

    // Related data
    public $client;
    public $creator;
    public $budgetItems = [];
    public $approvals = [];
    public $itemAvailability = [];

    // UI state
    public $activeTab = 'project-sites';

    // Feasibility / vendor form state (used in the blade)
    public $vendors = [];
    public $selectedVendor;
    public $selectedVendorService;
    public $vendorServices;
    public $productsServices;
    public $vendor = null;
    public $productService = null;
    public $nrcCost = null;
    public $mrcCost = null;
    public $notes = null;

    // New Client form properties
    public $client_name;
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
    public $client_status = 'pending';
    public $customer_type;
    public $districts = [];
    public $regions = [];
    public $editingClientId = null;
    public $isEditMode = false;

    // Statistics
    public $totalBudget = 0;
    public $totalItems = 0;
    public $approvalStatus = '';
    public $showPrintButton = true;

    public $demoClients;

    // Search and filter properties
    public $searchTerm = '';
    public $perPage = 10;
    public $showFilters = false;
    public $filterStatus = '';
    public $filterRegion = '';
    public $filterDistrict = '';
    public $filterVendor = '';
    public $filterCustomerType = '';
    public $filterCapacity = '';
    public $filterTransmission = '';
    public $filterInstallationDateFrom = '';
    public $filterInstallationDateTo = '';
    public $filterVlan = '';

    // Column visibility
    public $showColumnSelector = false;
    public $visibleColumns = [
        'customer_type' => true,      // Default
        'customer_name' => true,      // Default
        'contact_info' => false,      // Optional
        'location' => false,          // Optional
        'coordinates' => false,       // Optional
        'vendor' => true,             // Default
        'transmission' => true,       // Default
        'nrc' => false,               // Optional
        'mrc' => false,               // Optional
        'vlan' => true,               // Default
        'capacity' => true,           // Default
        'capacity_type' => false,      // Default
        'username' => false,          // Optional
        'serial_number' => false,     // Optional
        'installation_date' => true,  // Default
        'installation_engineer' => false, // Optional
        'status' => true,             // Default
    ];

    // Bulk operations
    public $selectedClients = [];
    public $selectAll = false;

    // Import/Export properties
    public $showImportModal = false;
    public $importFile;

    /**
     * Helper service for vendor-feasibility operations.
     *
     * @var ServiceFeasibilityVendorService
     */
    protected $feasibilityVendorService;

    public function mount($project)
    {
        $this->projectId = $project;
        // instantiate helper service for later use in vendor-feasibility operations
        $this->feasibilityVendorService = new ServiceFeasibilityVendorService();
        $this->loadRegions();
        $this->loadProjectData();

        $this->demoClients = collect([
            [
                'id' => 1,
                'customer_name' => 'Acme Corp',
                'contact_person' => 'Jane Doe',
                'category' => 'Corporate',
                'category_type' => 'Enterprise',
                'email' => 'jane.doe@acme.example',
                'phone' => '+256 700 000001',
                'services' => [
                    ['service' => 'Internet', 'product' => 'Fiber 100Mbps', 'capacity' => '100Mbps', 'monthly_charge' => 120000],
                ],
                'payment_type' => 'postpaid',
                'status' => 'active',
            ],
            [
                'id' => 2,
                'customer_name' => 'Beta Solutions',
                'contact_person' => 'John Smith',
                'category' => 'Home',
                'category_type' => null,
                'email' => 'john@beta.example',
                'phone' => '+256 700 000002',
                'services' => [],
                'payment_type' => 'prepaid',
                'status' => 'pending_approval',
            ],
            [
                'id' => 3,
                'customer_name' => 'Gamma Traders',
                'contact_person' => 'Alice N',
                'category' => 'SME',
                'category_type' => 'Retail',
                'email' => 'alice@gamma.example',
                'phone' => '+256 700 000003',
                'services' => [
                    ['service' => 'Hosting', 'product' => 'Business Host', 'capacity' => null, 'monthly_charge' => 30000],
                ],
                'payment_type' => null,
                'status' => 'approved',
            ],
        ]);
    }

    /**
     * Livewire hook: called when $region is updated. Load districts for selected region.
     */
    public function updatedRegion($value)
    {
        $this->district = null; // Reset district when region changes

        if ($value) {
            $region = \App\Models\Region::where('name', $value)->first();
            if ($region) {
                $this->districts = \App\Models\District::where('region_id', $region->id)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->toArray();
            } else {
                $this->districts = [];
            }
        } else {
            $this->districts = [];
        }
    }

    /**
     * Livewire hook: called when $vendor is updated. Filter vendor services.
     */
    public function updatedVendor($value)
    {
        // reset selected product/service when vendor changes
        $this->productService = null;

        // ensure the component's vendor property matches the updated value
        $this->vendor = $value;
        // keep selectedVendor in sync for rendering logic
        $this->selectedVendor = $value;

        // Use centralized loader to populate product/services for the selected vendor
        $this->loadProductServices();
        // Log and notify front-end using the actual `vendorServices` property
        $count = is_countable($this->vendorServices) ? count($this->vendorServices) : ($this->vendorServices->count() ?? 0);
        Log::info('ProjectView: updatedVendor called', ['vendor' => $value, 'products_count' => $count]);
        $this->dispatch('vendor-services-loaded', ['vendor' => $value, 'count' => $count]);
    }


    public function loadProjectData()
    {
        $this->project = Project::with([
            'client',
            'creator',
            'budgetItems.addedBy',
            'budgetItems.availability.vendor',
            'approvals.approver',
            'itemAvailability.budgetItem',
            'itemAvailability.vendor'
        ])->findOrFail($this->projectId);

        // Project basic info
        $this->project_code = $this->project->project_code;
        $this->name = $this->project->name;
        $this->description = $this->project->description;
        $this->start_date = $this->project->start_date;
        $this->end_date = $this->project->end_date;
        $this->estimated_budget = $this->project->estimated_budget;
        $this->status = $this->project->status;
        $this->priority = $this->project->priority;
        $this->objectives = $this->project->objectives;
        $this->deliverables = $this->project->deliverables;

        // Related data

        // Ensure product/service list is filtered if a vendor is already selected
        if ($this->vendor) {
            $this->updatedVendor($this->vendor);
        }
        $this->client = $this->project->client;

        $this->creator = $this->project->creator;
        $this->budgetItems = $this->project->budgetItems;
        $this->approvals = $this->project->approvals;
        $this->itemAvailability = $this->project->itemAvailability;

        // Load vendors and product/services using helper methods (keeps logic centralized)
        $this->loadVendors();
        $this->loadProductServices();

        // Calculate statistics
        $this->totalBudget = $this->project->total_budget;
        $this->actual_budget = $this->totalBudget;
        $this->totalItems = collect($this->budgetItems)->count();
        $this->approvalStatus = $this->project->getApprovalStatus();
    }

    protected function getProjectClientsQuery()
    {
        $query = Client::whereHas('clientServices', function($q) {
            $q->where('project_id', $this->projectId);
        })->with(['clientServices' => function($q) {
            // Only load services that belong to this specific project
            $q->where('project_id', $this->projectId)
              ->with(['vendor', 'serviceType']);
        }]);

        // Apply search filter if search term exists
        if (!empty($this->searchTerm)) {
            $searchTerm = '%' . $this->searchTerm . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('customer_name', 'like', $searchTerm)
                  ->orWhere('contact_person', 'like', $searchTerm)
                  ->orWhere('phone', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm)
                  ->orWhere('region', 'like', $searchTerm)
                  ->orWhere('district', 'like', $searchTerm)
                  ->orWhereHas('clientServices', function($serviceQuery) use ($searchTerm) {
                      $serviceQuery->where('serial_number', 'like', $searchTerm)
                                  ->orWhere('username', 'like', $searchTerm)
                                  ->orWhere('vlan', 'like', $searchTerm)
                                  ->orWhere('capacity', 'like', $searchTerm)
                                  ->orWhere('capacity_type', 'like', $searchTerm)
                                  ->orWhere('service_type', 'like', $searchTerm)
                                  ->orWhere('status', 'like', $searchTerm);
                  });
            });
        }

        // Apply customer type filter
        if (!empty($this->filterCustomerType)) {
            $query->where('category', $this->filterCustomerType);
        }

        // Apply region filter
        if (!empty($this->filterRegion)) {
            $query->where('region', $this->filterRegion);
        }

        // Apply district filter
        if (!empty($this->filterDistrict)) {
            $query->where('district', $this->filterDistrict);
        }

        // Apply status filter (on client services)
        if (!empty($this->filterStatus)) {
            $query->whereHas('clientServices', function($q) {
                $q->where('project_id', $this->projectId)
                  ->orwhere('status', $this->filterStatus);

            });
        }

        // Apply vendor filter (on client services)
        if (!empty($this->filterVendor)) {
            $query->whereHas('clientServices', function($q) {
                $q->where('project_id', $this->projectId)
                  ->where('vendor_id', $this->filterVendor);
            });
        }

        // Apply capacity filter
        if (!empty($this->filterCapacity)) {
            $query->whereHas('clientServices', function($q) {
                $q->where('project_id', $this->projectId)
                  ->where('capacity', $this->filterCapacity);
            });
        }

        // Apply transmission filter
        if (!empty($this->filterTransmission)) {
            $query->whereHas('clientServices', function($q) {
                $q->where('project_id', $this->projectId)
                  ->where('service_type', $this->filterTransmission);
            });
        }

        // Apply VLAN filter
        if (!empty($this->filterVlan)) {
            $query->whereHas('clientServices', function($q) {
                $q->where('project_id', $this->projectId)
                  ->where('vlan', $this->filterVlan);
            });
        }

        // Apply installation date range filter
        if (!empty($this->filterInstallationDateFrom)) {
            $query->whereHas('clientServices', function($q) {
                $q->where('project_id', $this->projectId)
                  ->whereDate('installation_date', '>=', $this->filterInstallationDateFrom);
            });
        }

        if (!empty($this->filterInstallationDateTo)) {
            $query->whereHas('clientServices', function($q) {
                $q->where('project_id', $this->projectId)
                  ->whereDate('installation_date', '<=', $this->filterInstallationDateTo);
            });
        }

        return $query->orderBy('created_at', 'desc');
    }

    protected function loadProjectClients()
    {
        Log::info('ProjectView: Loaded project clients', [
            'project_id' => $this->projectId,
            'search_term' => $this->searchTerm,
            // 'client_count' => $this->projectClients->count()
        ]);
    }

    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function toggleColumnSelector()
    {
        $this->showColumnSelector = !$this->showColumnSelector;
    }

    public function clearFilters()
    {
        $this->filterStatus = '';
        $this->filterRegion = '';
        $this->filterDistrict = '';
        $this->filterVendor = '';
        $this->filterCustomerType = '';
        $this->filterCapacity = '';
        $this->filterTransmission = '';
        $this->filterInstallationDateFrom = '';
        $this->filterInstallationDateTo = '';
        $this->filterVlan = '';
        $this->resetPage();
    }

    // Reset pagination when any filter changes
    public function updatedFilterStatus() { $this->resetPage(); }
    public function updatedFilterRegion() { $this->resetPage(); $this->filterDistrict = ''; }
    public function updatedFilterDistrict() { $this->resetPage(); }
    public function updatedFilterVendor() { $this->resetPage(); }
    public function updatedFilterCustomerType() { $this->resetPage(); }
    public function updatedFilterCapacity() { $this->resetPage(); }
    public function updatedFilterTransmission() { $this->resetPage(); }
    public function updatedFilterInstallationDateFrom() { $this->resetPage(); }
    public function updatedFilterInstallationDateTo() { $this->resetPage(); }
    public function updatedFilterVlan() { $this->resetPage(); }


    // Get the client count for the current project
    public function getClientCount()
    {
        return $this->clientsCount;
    }

    // Static method to get client count for any project
    public static function getProjectClientCount($projectId)
    {
        return Client::whereHas('clientServices', function($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->count();
    }

    /**
     * Load active vendors into the `vendors` property as a Collection.
     */
    protected function loadVendors()
    {
        try {
            $vendorTable = (new Vendor)->getTable();
            $vendorOrder = Schema::hasColumn($vendorTable, 'name') ? 'name' : 'id';
            $this->vendors = Vendor::orderBy($vendorOrder)->get();
        } catch (\Throwable $e) {
            // fallback: retrieve without ordering
            $this->vendors = Vendor::get();
        }
        // guarantee a Collection
        $this->vendors = collect($this->vendors);
    }

    /**
     * Load active regions into the `regions` property.
     */
    protected function loadRegions()
    {
        $this->regions = \App\Models\Region::where('is_active', true)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Load product/services for the currently selected vendor into `productsServices` as a Collection.
     */
    protected function loadProductServices()
    {
        try {
            $vsTable = (new VendorService)->getTable();
            if (Schema::hasColumn($vsTable, 'service_name')) {
                $vsOrder = 'service_name';
            } elseif (Schema::hasColumn($vsTable, 'name')) {
                $vsOrder = 'name';
            } else {
                $vsOrder = 'id';
            }

            if ($this->vendor) {
                $this->vendorServices = VendorService::where('vendor_id', $this->vendor)->orderBy($vsOrder)->get();
            } else {
                $this->vendorServices = collect([]);
            }
        } catch (\Throwable $e) {
            $this->vendorServices = $this->vendor ? VendorService::where('vendor_id', $this->vendor)->get() : collect([]);
        }
        $this->vendorServices = collect($this->vendorServices);
        // provide alias used by the Blade template
        $this->productsServices = $this->vendorServices;
        Log::info('ProjectView: loadProductServices finished', ['vendor' => $this->vendor, 'count' => $this->vendorServices->count()]);
        // also dispatch Livewire event so front-end can detect changes quickly (Livewire 3 uses dispatch)
        $this->dispatch('vendor-services-loaded', ['vendor' => $this->vendor, 'count' => $this->vendorServices->count()]);
    }

    /**
     * Handle add vendor form submission from the feasibility tab.
     * Validates input, ensures selected product belongs to vendor, and
     * emits an event with the prepared payload for downstream handling.
     */
    public function addVendor()
    {
        $this->validate([
            'vendor' => 'required|exists:vendors,id',
            'productService' => 'nullable|exists:vendor_services,id',
            'nrcCost' => 'required|numeric|min:0',
            'mrcCost' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        try {
            $feasibilityVendorService = new ServiceFeasibilityVendorService();
            $feasibilityVendorService->ensureServiceBelongsToVendor($this->productService, $this->vendor);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                $this->addError($field, $messages[0]);
            }
            return;
        }

        $payload = [
            'vendor_id' => $this->vendor,
            'vendor_service_id' => $this->productService,
            'nrc_cost' => $this->nrcCost,
            'mrc_cost' => $this->mrcCost,
            'notes' => $this->notes,
            'project_id' => $this->projectId,
        ];

        Log::info('ProjectView: addVendor validated', $payload);

        // Emit an internal Livewire dispatch so other components can handle persistence.
        $this->dispatch('project-vendor-added', $payload);

        session()->flash('success', 'Vendor selection validated.');

        // reset form fields
        $this->vendor = null;
        $this->productService = null;
        $this->nrcCost = null;
        $this->mrcCost = null;
        $this->notes = null;
    }

    public function saveClient()
    {
        // If in edit mode, call updateClient instead
        if ($this->isEditMode && $this->editingClientId) {
            return $this->updateClient();
        }

        $this->validate([
            'client_name' => 'required|string|max:191',
            'phone' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191|unique:clients,email',
            'latitude' => 'nullable|string|max:191',
            'longitude' => 'nullable|string|max:191',
            'region' => 'nullable|string|max:191',
            'district' => 'nullable|string|max:191',
            'vendor_id' => 'required_with:capacity,nrc,mrc|nullable|exists:vendors,id',
            'transmission' => 'nullable|exists:products,id',
            'nrc' => 'nullable|numeric|min:0',
            'mrc' => 'nullable|numeric|min:0',
            'vlan' => 'nullable|string|max:191',
            'capacity' => 'nullable|string|max:191',
            'installation_date' => 'nullable|date',
            'installation_engineer' => 'nullable|string|max:191',
            'client_status' => 'nullable|in:active,suspended,inactive',
            'customer_type' => 'nullable|in:Home,Corporate',
        ]);

        DB::beginTransaction();
        try {
            $client = Client::create([
                'customer_name' => $this->client_name,
                'contact_person' => $this->installation_engineer ?: null,
                'phone' => $this->phone,
                'email' => $this->email,
                'city' => $this->district,
                'state' => $this->region,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'status' => in_array($this->client_status, ['active', 'suspended', 'archived']) ? $this->client_status : 'active',
                'category' => $this->customer_type,
                'created_by' => Auth::user()->id,
            ]);

            // If service details present, create a ClientService
            if ($this->capacity || $this->mrc || $this->nrc || $this->vendor_id) {
                // Validate that vendor_id is provided when creating service
                if (!$this->vendor_id) {
                    throw new \Exception('Vendor is required when adding service details.');
                }

                $vendor = Vendor::find($this->vendor_id);
                $product = $this->transmission ? Product::find($this->transmission) : null;

                ClientService::create([
                    'client_id' => $client->id,
                    'vendor_id' => $this->vendor_id,
                    'vendor_name' => $vendor?->name,
                    'project_id' => $this->projectId,
                    'product_id' => $this->transmission ?: null,
                    'product_name' => $product?->name,
                    'service_type' => $product?->name ?? 'Not Specified',
                    'capacity' => $this->capacity,
                    'vlan' => $this->vlan,
                    'nrc' => $this->nrc ?: 0,
                    'mrc' => $this->mrc ?: 0,
                    'installation_date' => $this->installation_date,
                    'status' => in_array($this->client_status, ['active', 'inactive', 'suspended']) ? $this->client_status : 'active',
                ]);
            }

            DB::commit();

            session()->flash('success', 'Client added to project successfully.');

            // Reset form fields
            $this->resetClientForm();

            // Reload project clients list
            $this->resetPage();

            // Switch back to project-sites tab to see the new client
            $this->activeTab = 'project-sites';
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('save', 'Failed to create client: ' . $e->getMessage());
        }
    }

    public function resetClientForm()
    {
        $this->client_name = null;
        $this->phone = null;
        $this->email = null;
        $this->latitude = null;
        $this->longitude = null;
        $this->region = null;
        $this->district = null;
        $this->vendor_id = null;
        $this->transmission = null;
        $this->nrc = null;
        $this->mrc = null;
        $this->vlan = null;
        $this->capacity = null;
        $this->installation_date = null;
        $this->installation_engineer = null;
        $this->customer_type = null;
        $this->client_status = 'active';
        $this->editingClientId = null;
        $this->isEditMode = false;
    }

    public function editClient($clientId)
    {
        $this->isEditMode = true;
        $this->editingClientId = $clientId;

        $client = Client::with(['clientServices' => function($q) {
            $q->where('project_id', $this->projectId);
        }])->findOrFail($clientId);

        // Load client data into form
        $this->client_name = $client->customer_name;
        $this->phone = $client->phone;
        $this->email = $client->email;
        $this->latitude = $client->latitude;
        $this->longitude = $client->longitude;
        $this->region = $client->region;
        $this->customer_type = $client->category;
        $this->installation_engineer = $client->contact_person;

        // Load districts for the selected region first, then set district
        if ($this->region) {
            $region = \App\Models\Region::where('name', $this->region)->first();
            if ($region) {
                $this->districts = \App\Models\District::where('region_id', $region->id)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->pluck('name', 'id')
                    ->toArray();
            }
        }

        // Set district after districts array is populated
        $this->district = $client->district;

        // Load service data if exists
        $service = $client->clientServices->first();
        if ($service) {
            $this->vendor_id = $service->vendor_id;
            $this->transmission = $service->product_id;
            $this->capacity = $service->capacity;
            $this->vlan = $service->vlan;
            $this->nrc = $service->nrc;
            $this->mrc = $service->mrc;
            $this->installation_date = $service->installation_date ? $service->installation_date->format('Y-m-d') : null;
            $this->client_status = $service->status;
        }

        // Switch to the new client tab
        $this->activeTab = 'new-client';
    }

    public function updateClient()
    {
        $this->validate([
            'client_name' => 'required|string|max:191',
            'phone' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191|unique:clients,email,' . $this->editingClientId,
            'latitude' => 'nullable|string|max:191',
            'longitude' => 'nullable|string|max:191',
            'region' => 'nullable|string|max:191',
            'district' => 'nullable|string|max:191',
            'vendor_id' => 'required_with:capacity,nrc,mrc|nullable|exists:vendors,id',
            'transmission' => 'nullable|exists:products,id',
            'nrc' => 'nullable|numeric|min:0',
            'mrc' => 'nullable|numeric|min:0',
            'vlan' => 'nullable|string|max:191',
            'capacity' => 'nullable|string|max:191',
            'installation_date' => 'nullable|date',
            'installation_engineer' => 'nullable|string|max:191',
            'client_status' => 'nullable|in:active,suspended,inactive',
            'customer_type' => 'nullable|in:Home,Corporate',
        ]);

        DB::beginTransaction();
        try {
            $client = Client::findOrFail($this->editingClientId);

            $client->update([
                'customer_name' => $this->client_name,
                'contact_person' => $this->installation_engineer ?: null,
                'phone' => $this->phone,
                'email' => $this->email,
                'district' => $this->district,
                'region' => $this->region,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'status' => in_array($this->client_status, ['active', 'suspended', 'archived']) ? $this->client_status : 'active',
                'category' => $this->customer_type,
            ]);

            // Update or create ClientService
            if ($this->capacity || $this->mrc || $this->nrc || $this->vendor_id) {
                if (!$this->vendor_id) {
                    throw new \Exception('Vendor is required when adding service details.');
                }

                $service = ClientService::where('client_id', $client->id)
                    ->where('project_id', $this->projectId)
                    ->first();

                $vendor = Vendor::find($this->vendor_id);
                $product = $this->transmission ? Product::find($this->transmission) : null;

                $serviceData = [
                    'client_id' => $client->id,
                    'vendor_id' => $this->vendor_id,
                    'vendor_name' => $vendor?->name,
                    'project_id' => $this->projectId,
                    'product_id' => $this->transmission ?: null,
                    'product_name' => $product?->name,
                    'service_type' => $product?->name ?? 'Not Specified',
                    'capacity' => $this->capacity,
                    'vlan' => $this->vlan,
                    'nrc' => $this->nrc ?: 0,
                    'mrc' => $this->mrc ?: 0,
                    'installation_date' => $this->installation_date,
                    'status' => in_array($this->client_status, ['active', 'inactive', 'suspended']) ? $this->client_status : 'active',
                ];

                if ($service) {
                    $service->update($serviceData);
                } else {
                    ClientService::create($serviceData);
                }
            }

            DB::commit();

            session()->flash('success', 'Client updated successfully.');

            // Reset form fields
            $this->resetClientForm();

            // Reload project clients list
            $this->resetPage();

            // Switch back to project-sites tab
            $this->activeTab = 'project-sites';
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('save', 'Failed to update client: ' . $e->getMessage());
        }
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedClients = $this->getProjectClientsQuery()->pluck('id')->toArray();
        } else {
            $this->selectedClients = [];
        }
    }

    public function bulkDeleteClients()
    {
        if (empty($this->selectedClients)) {
            session()->flash('error', 'No clients selected for deletion.');
            return;
        }

        DB::beginTransaction();
        try {
            $deletedCount = 0;

            foreach ($this->selectedClients as $clientId) {
                $client = Client::find($clientId);

                if (!$client) {
                    continue;
                }

                // Delete associated client services for this project
                ClientService::where('client_id', $clientId)
                    ->where('project_id', $this->projectId)
                    ->delete();

                // Check if client has services in other projects
                $hasOtherServices = ClientService::where('client_id', $clientId)
                    ->where('project_id', '!=', $this->projectId)
                    ->exists();

                // If no other services, delete the client entirely
                if (!$hasOtherServices) {
                    $client->delete();
                }

                $deletedCount++;
            }

            DB::commit();

            // Reset selections
            $this->selectedClients = [];
            $this->selectAll = false;

            session()->flash('success', "Successfully deleted {$deletedCount} client(s) from this project.");
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk delete clients failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to delete clients: ' . $e->getMessage());
        }
    }

    public function deleteClient($clientId)
    {
        DB::beginTransaction();
        try {
            $client = Client::findOrFail($clientId);

            // Check if client has services in this project
            $hasServices = ClientService::where('client_id', $clientId)
                ->where('project_id', $this->projectId)
                ->exists();

            if ($hasServices) {
                // Delete associated client services for this project
                ClientService::where('client_id', $clientId)
                    ->where('project_id', $this->projectId)
                    ->delete();
            }

            // Check if client has services in other projects
            $hasOtherServices = ClientService::where('client_id', $clientId)
                ->where('project_id', '!=', $this->projectId)
                ->exists();

            // Only delete the client if they have no services in other projects
            if (!$hasOtherServices) {
                $client->delete();
                $message = 'Client and associated services deleted successfully.';
            } else {
                $message = 'Client services removed from this project successfully.';
            }

            DB::commit();

            session()->flash('success', $message);

            // Reload project clients list
            $this->resetPage();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete client: ' . $e->getMessage());
        }
    }

    public function getStatusBadgeClass($status)
    {
        return match($status) {
            'draft' => 'bg-gray-100 text-gray-700',
            'budget_planning' => 'bg-blue-100 text-blue-700',
            'pending_approval' => 'bg-yellow-100 text-yellow-700',
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700',
            'in_progress' => 'bg-indigo-100 text-indigo-700',
            'checking_availability' => 'bg-purple-100 text-purple-700',
            'on_hold' => 'bg-orange-100 text-orange-700',
            'completed' => 'bg-teal-100 text-teal-700',
            'cancelled' => 'bg-gray-100 text-gray-500',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function getPriorityBadgeClass($priority)
    {
        return match($priority) {
            'low' => 'bg-gray-100 text-gray-700',
            'medium' => 'bg-blue-100 text-blue-700',
            'high' => 'bg-orange-100 text-orange-700',
            'critical' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Open import modal
     */
    public function openImportModal()
    {
        $this->showImportModal = true;
        $this->importFile = null;
    }

    /**
     * Close import modal
     */
    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->importFile = null;
    }

    /**
     * Process the import file
     */
    public function importClients()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt,xlsx,xls|max:20048',
        ]);

        Log::info('ClientImport: Starting import process', [
            'project_id' => $this->projectId,
            'filename' => $this->importFile->getClientOriginalName(),
            'size' => $this->importFile->getSize(),
            'mime_type' => $this->importFile->getMimeType()
        ]);

        try {
            // Create import instance
            $import = new ClientsImport($this->projectId);

            Log::info('ClientImport: Processing file with Laravel Excel');

            // Process the import
            Excel::import($import, $this->importFile);

            // Get results
            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();
            $failures = $import->failures();

            Log::info('ClientImport: Import completed', [
                'imported_count' => $importedCount,
                'validation_failures' => count($failures),
                'processing_errors' => count($errors)
            ]);

            // Build result message
            if ($importedCount > 0) {
                $message = "Successfully imported {$importedCount} client(s)";

                if (count($failures) > 0) {
                    $message .= ". " . count($failures) . " row(s) failed validation.";

                    Log::warning('ClientImport: Validation failures summary', [
                        'total_failures' => count($failures),
                        'failed_rows' => array_map(fn($f) => $f->row(), $failures->toArray())
                    ]);
                }

                session()->flash('success', $message);

                // Show first few validation errors if any
                if (count($failures) > 0 && count($failures) <= 5) {
                    $errorMessages = [];
                    foreach ($failures as $failure) {
                        $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
                    }
                    session()->flash('warning', implode(' | ', $errorMessages));
                }
            } else {
                Log::warning('ClientImport: No clients imported', [
                    'validation_failures' => count($failures),
                    'processing_errors' => count($errors)
                ]);
                session()->flash('warning', 'No clients were imported. Please check the file format and logs.');
            }

            // Show other errors if any
            if (count($errors) > 0) {
                Log::error('ClientImport: Processing errors occurred', [
                    'error_count' => count($errors),
                    'errors' => $errors
                ]);
            }

            // Reload project clients and close modal
            $this->resetPage();
            $this->closeImportModal();

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            Log::error('ClientImport: Validation exception', [
                'failure_count' => count($failures),
                'project_id' => $this->projectId
            ]);

            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());

                Log::error('ClientImport: Validation failure detail', [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values()
                ]);
            }

            $this->addError('import', 'Validation failed: ' . implode(' | ', array_slice($errorMessages, 0, 3)));

        } catch (\Exception $e) {
            Log::error('ClientImport: Import failed with exception', [
                'project_id' => $this->projectId,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('import', 'Failed to import clients: ' . $e->getMessage());
        }
    }

    /**
     * Download import template with dropdown options
     */
    public function downloadTemplate()
    {
        try {
            $filename = 'client_import_template_' . date('Y-m-d_His') . '.csv';
            $filepath = storage_path('app/exports/' . $filename);

            // Create exports directory if it doesn't exist
            if (!file_exists(storage_path('app/exports'))) {
                mkdir(storage_path('app/exports'), 0755, true);
            }

            $file = fopen($filepath, 'w');

            // Write header
            fputcsv($file, [
                'Customer Name',
                'Customer Type',
                'Phone',
                'Email',
                'Address',
                'Latitude',
                'Longitude',
                'Region',
                'District',
                'Installation Engineer',
                'Vendor ID',
                'Transmission (Product ID)',
                'Username',
                'Serial Number',
                'Capacity',
                'Capacity Type',
                'VLAN',
                'NRC',
                'MRC',
                'Auth Date',
                'Administrative Status'
            ]);

            // Add reference data section
            fputcsv($file, []); // Empty row
            fputcsv($file, ['REFERENCE DATA - Use these values in your import']);
            fputcsv($file, []);

            // Customer Types
            fputcsv($file, ['Customer Types:']);
            fputcsv($file, ['Home', 'Corporate']);
            fputcsv($file, []);

            // Regions
            $regions = Region::where('is_active', true)->get();
            fputcsv($file, ['Regions:']);
            foreach ($regions as $region) {
                fputcsv($file, [$region->name]);
            }
            fputcsv($file, []);

            // Districts by Region
            fputcsv($file, ['Districts by Region:']);
            foreach ($regions as $region) {
                $districts = District::where('region_id', $region->id)
                    ->where('is_active', true)
                    ->pluck('name')
                    ->toArray();
                fputcsv($file, array_merge([$region->name . ':'], $districts));
            }
            fputcsv($file, []);

            // Vendors
            $vendors = Vendor::where('status', 'active')->get();
            fputcsv($file, ['Vendors (ID - Name):']);
            foreach ($vendors as $vendor) {
                fputcsv($file, [$vendor->id, $vendor->name]);
            }
            fputcsv($file, []);

            // Products by Vendor
            fputcsv($file, ['Transmission Products by Vendor (Product ID - Product Name - Vendor):']);
            foreach ($vendors as $vendor) {
                $products = Product::whereHas('vendorService', function($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                })->get();

                foreach ($products as $product) {
                    fputcsv($file, [$product->id, $product->name, $vendor->name]);
                }
            }
            fputcsv($file, []);

            // Capacity Types
            fputcsv($file, ['Capacity Types:']);
            fputcsv($file, ['Shared', 'Dedicated']);
            fputcsv($file, []);

            // Administrative Status options
            fputcsv($file, ['Administrative Status Options:']);
            fputcsv($file, ['Enabled', 'Disabled']);

            fclose($file);

            session()->flash('success', 'Template downloaded successfully with reference data');

            return response()->download($filepath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to generate template: ' . $e->getMessage());
        }
    }

    /**
     * Export clients to CSV
     */
    public function exportClients()
    {
        try {
            // Use the same query as the display (with filters applied)
            $clients = $this->getProjectClientsQuery()->with(['clientServices' => function($q) {
                $q->where('project_id', $this->projectId)
                  ->with(['vendor', 'serviceType']);
            }])->get();

            $filename = 'project_' . $this->projectId . '_clients_' . date('Y-m-d_His') . '.csv';
            $filepath = storage_path('app/exports/' . $filename);

            // Create exports directory if it doesn't exist
            if (!file_exists(storage_path('app/exports'))) {
                mkdir(storage_path('app/exports'), 0755, true);
            }

            $file = fopen($filepath, 'w');

            // Write header
            fputcsv($file, [
                'Customer Name',
                'Customer Type',
                'Phone',
                'Email',
                'Region',
                'District',
                'Latitude',
                'Longitude',
                'Installation Engineer',
                'Vendor',
                'Transmission',
                'Capacity',
                'Capacity Type',
                'Username',
                'Serial Number',
                'VLAN',
                'NRC',
                'MRC',
                'Installation Date',
                'Status'
            ]);

            // Write data rows
            foreach ($clients as $client) {
                foreach ($client->clientServices as $service) {
                    fputcsv($file, [
                        $client->customer_name,
                        $client->category ?? 'Home',
                        $client->phone,
                        $client->email,
                        $client->region,
                        $client->district,
                        $client->latitude,
                        $client->longitude,
                        $client->contact_person,
                        $service->vendor->name ?? '',
                        $service->service_type,
                        $service->capacity,
                        $service->capacity_type,
                        $service->username,
                        $service->serial_number,
                        $service->vlan,
                        $service->nrc,
                        $service->mrc,
                        $service->installation_date ? $service->installation_date->format('Y-m-d H:i') : '',
                        $service->status
                    ]);
                }
            }

            fclose($file);

            // Build filter summary for the flash message
            $filterSummary = [];
            if ($this->searchTerm) $filterSummary[] = "Search: '{$this->searchTerm}'";
            if ($this->filterCustomerType) $filterSummary[] = "Type: {$this->filterCustomerType}";
            if ($this->filterStatus) $filterSummary[] = "Status: {$this->filterStatus}";
            if ($this->filterRegion) $filterSummary[] = "Region: {$this->filterRegion}";
            if ($this->filterDistrict) $filterSummary[] = "District: {$this->filterDistrict}";
            if ($this->filterVendor) {
                $vendor = Vendor::find($this->filterVendor);
                if ($vendor) $filterSummary[] = "Vendor: {$vendor->name}";
            }
            if ($this->filterCapacity) $filterSummary[] = "Capacity: {$this->filterCapacity}";
            if ($this->filterTransmission) $filterSummary[] = "Transmission: {$this->filterTransmission}";
            if ($this->filterVlan) $filterSummary[] = "VLAN: {$this->filterVlan}";
            if ($this->filterInstallationDateFrom) $filterSummary[] = "Install From: {$this->filterInstallationDateFrom}";
            if ($this->filterInstallationDateTo) $filterSummary[] = "Install To: {$this->filterInstallationDateTo}";

            $message = "Exported {$clients->count()} client(s)";
            if (!empty($filterSummary)) {
                $message .= " with filters: " . implode(', ', $filterSummary);
            }
            $message .= " to {$filename}";

            session()->flash('success', $message);

            return response()->download($filepath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Export clients failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to export clients: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $allVendors = Vendor::where('status', 'active')->get();
        $vendorServices = $this->selectedVendor
            ? VendorService::where('vendor_id', $this->selectedVendor)->get()
            : collect();

        $projectClients = $this->getProjectClientsQuery()->paginate($this->perPage);

        // Get filter districts based on selected region
        $filterDistricts = [];
        if ($this->filterRegion) {
            $region = Region::where('name', $this->filterRegion)->first();
            if ($region) {
                $filterDistricts = District::where('region_id', $region->id)
                    ->where('is_active', true)
                    ->orderBy('name')
                    ->pluck('name', 'name')
                    ->toArray();
            }
        }

        return view('livewire.projects.project-view' , [
            'allVendors' => $allVendors,
            'vendorServices' => $vendorServices,
            'projectClients' => $projectClients,
            'filterDistricts' => $filterDistricts,
        ]);
    }
}
