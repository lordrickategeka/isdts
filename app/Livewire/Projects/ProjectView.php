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
    public $milestones = [];

    // UI state
    public $activeTab = 'project-sites';
    public $materialsSubTab = 'budget-items';

    protected $queryString = [
        'activeTab' => ['except' => 'project-sites'],
        'materialsSubTab' => ['except' => 'budget-items'],
        'importExportSubTab' => ['except' => 'import'],
    ];

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
    // Import/Export sub-tab (import | export | templates)
    public $importExportSubTab = 'import';
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

    // Milestone modal properties
    public $showMilestoneModal = false;
    public $editingMilestoneId = null;
    public $milestone_name = '';
    public $milestone_description = '';
    public $milestone_start_date = '';
    public $milestone_due_date = '';
    public $milestone_amount = '';
    public $milestone_percentage = '';
    public $milestone_is_billable = false;
    public $milestone_status = 'pending';
    public $milestone_priority = 'medium';
    public $milestone_progress = 0;
    public $milestone_deliverables = '';
    public $milestone_notes = '';
    public $milestone_assigned_to = null;
    public $milestone_depends_on = null;

    // Import/Export
    public $importFile;
    public $importVendorId;
    public $importCustomerType;

    // Export field selection
    public $exportFields = [
        'customer_name' => true,
        'customer_type' => true,
        'phone' => true,
        'email' => true,
        'address' => false,
        'region' => true,
        'district' => true,
        'latitude' => false,
        'longitude' => false,
        'installation_engineer' => true,
        'vendor' => true,
        'transmission' => true,
        'capacity' => true,
        'capacity_type' => true,
        'username' => false,
        'serial_number' => false,
        'vlan' => true,
        'nrc' => true,
        'mrc' => true,
        'installation_date' => true,
        'status' => true,
    ];


    // For import conflict pagination
    public $importConflictsPage = 1;
    public $importConflictsPerPage = 10;

    public function setImportConflictsPage($page)
    {
        $this->importConflictsPage = $page;
    }
    // Event listeners
    protected $listeners = ['clients-imported' => 'refreshClients'];

    /**
     * Helper service for vendor-feasibility operations.
     *
     * @var ServiceFeasibilityVendorService
     */
    protected $feasibilityVendorService;

    // For import conflict resolution
    public $importConflicts = [];
    public $importConflictActions = [];
    /**
     * Dry-run import to detect conflicts without saving.
     */
    public function dryRunImportClients()
    {
        $this->validate([
            'importVendorId' => 'required|exists:vendors,id',
            'importCustomerType' => 'required|in:home,company',
            'importFile' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);
        $conflicts = [];
        try {
            $rows = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\ClientsImport($this->projectId, $this->importVendorId, $this->importCustomerType), $this->importFile)[0];
            $expected = \App\Imports\ClientsImport::expectedHeaders();
            foreach ($rows as $i => $row) {
                // Normalize keys
                $rowNorm = [];
                foreach ($row as $k => $v) {
                    $rowNorm[\App\Imports\ClientsImport::normalizeHeaderStatic($k)] = $v;
                }
                $customerName = $rowNorm['customer_name'] ?? null;
                if (!$customerName) continue;
                $existing = \App\Models\Client::where('customer_name', $customerName)->first();
                if ($existing) {
                    $diffs = [];
                    foreach ($expected as $field) {
                        $importVal = $rowNorm[$field] ?? null;
                        $existingVal = $existing->$field ?? null;
                        if ($importVal != $existingVal) {
                            $diffs[$field] = [
                                'existing' => $existingVal,
                                'import' => $importVal
                            ];
                        }
                    }
                    if (!empty($diffs)) {
                        // Store as array, not object
                        $conflicts[] = [
                            'row' => $i+2, // +2 for header and 0-index
                            'customer_name' => $customerName,
                            'existing' => $existing->toArray(),
                            'import' => $rowNorm,
                            'diffs' => $diffs
                        ];
                    }
                }
            }
            $this->importConflicts = $conflicts;
        } catch (\Exception $e) {
            $this->addError('import', 'Dry-run failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle user choices for grouped conflicts and perform import.
     * This is called from the grouped import-conflicts UI.
     */
    public function resolveConflicts()
    {
        // Alias to existing resolveImportConflicts for compatibility
        return $this->resolveImportConflicts();
    }

    /**
     * Handle user choices for conflicts and perform import.
     */
    public function resolveImportConflicts()
    {
        try {
            $rows = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\ClientsImport($this->projectId, $this->importVendorId, $this->importCustomerType), $this->importFile)[0];
            $expected = \App\Imports\ClientsImport::expectedHeaders();
            $importedCount = 0;
            foreach ($rows as $i => $row) {
                $rowNorm = [];
                foreach ($row as $k => $v) {
                    $rowNorm[\App\Imports\ClientsImport::normalizeHeaderStatic($k)] = $v;
                }
                $customerName = $rowNorm['customer_name'] ?? null;
                if (!$customerName) continue;
                $existing = \App\Models\Client::where('customer_name', $customerName)->first();
                if ($existing) {
                    $update = false;
                    foreach ($expected as $field) {
                        $action = $this->importConflictActions[$i+2][$field] ?? 'skip';
                        if ($action === 'update') {
                            $existing->$field = $rowNorm[$field] ?? null;
                            $update = true;
                        }
                    }
                    if ($update) {
                        $existing->save();
                        $importedCount++;
                    }
                } else {
                    // New client, create as usual
                    $client = \App\Models\Client::create([
                        'customer_name' => $rowNorm['customer_name'],
                        'category' => $this->importCustomerType,
                        'phone' => $rowNorm['phone'] ?? null,
                        'email' => $rowNorm['email'] ?? null,
                        'address' => $rowNorm['address'] ?? null,
                        'latitude' => $rowNorm['latitude'] ?? null,
                        'longitude' => $rowNorm['longitude'] ?? null,
                        'region' => $rowNorm['region'] ?? null,
                        'district' => $rowNorm['district'] ?? null,
                        'contact_person' => $rowNorm['installation_engineer'] ?? null,
                        'created_by' => Auth::user()->id,
                    ]);
                    $importedCount++;
                }
            }
            session()->flash('success', "$importedCount clients imported/updated successfully.");
            $this->resetFile();
            $this->importConflicts = [];
        } catch (\Exception $e) {
            $this->addError('import', 'Import failed: ' . $e->getMessage());
        }
    }

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
     * Refresh clients list when import is completed
     */
    public function refreshClients()
    {
        // This will trigger a re-render and reload the clients
        $this->dispatch('$refresh');
        session()->flash('success', 'Client data has been refreshed.');
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
            'itemAvailability.vendor',
            'milestones.assignedTo',
            'milestones.approvedBy',
            'milestones.createdBy'
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
        $this->milestones = $this->project->milestones;

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
        // Use join to avoid multiple whereHas subqueries for better performance
        $query = Client::select('clients.*')
            ->join('client_services', 'clients.id', '=', 'client_services.client_id')
            ->where('client_services.project_id', $this->projectId)
            ->with(['clientServices' => function($q) {
                // Only load services that belong to this specific project
                $q->where('project_id', $this->projectId)
                  ->with(['vendor', 'product.vendorService']);
            }])
            ->distinct();

        // Apply search filter if search term exists
        if (!empty($this->searchTerm)) {
            $searchTerm = '%' . $this->searchTerm . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('clients.customer_name', 'like', $searchTerm)
                  ->orWhere('clients.contact_person', 'like', $searchTerm)
                  ->orWhere('clients.phone', 'like', $searchTerm)
                  ->orWhere('clients.email', 'like', $searchTerm)
                  ->orWhere('clients.region', 'like', $searchTerm)
                  ->orWhere('clients.district', 'like', $searchTerm)
                  ->orWhere('client_services.serial_number', 'like', $searchTerm)
                  ->orWhere('client_services.username', 'like', $searchTerm)
                  ->orWhere('client_services.vlan', 'like', $searchTerm)
                  ->orWhere('client_services.capacity', 'like', $searchTerm)
                  ->orWhere('client_services.capacity_type', 'like', $searchTerm)
                  ->orWhere('client_services.service_type', 'like', $searchTerm)
                  ->orWhere('client_services.status', 'like', $searchTerm);
            });
        }

        // Apply customer type filter
        if (!empty($this->filterCustomerType)) {
            $query->where('clients.category', $this->filterCustomerType);
        }

        // Apply region filter
        if (!empty($this->filterRegion)) {
            $query->where('clients.region', $this->filterRegion);
        }

        // Apply district filter
        if (!empty($this->filterDistrict)) {
            $query->where('clients.district', $this->filterDistrict);
        }

        // Apply status filter (on client services)
        if (!empty($this->filterStatus)) {
            $query->where('client_services.status', $this->filterStatus);
        }

        // Apply vendor filter (on client services)
        if (!empty($this->filterVendor)) {
            $query->where('client_services.vendor_id', $this->filterVendor);
        }

        // Apply capacity filter
        if (!empty($this->filterCapacity)) {
            $query->where('client_services.capacity', $this->filterCapacity);
        }

        // Apply transmission filter
        if (!empty($this->filterTransmission)) {
            $query->where('client_services.service_type', $this->filterTransmission);
        }

        // Apply VLAN filter
        if (!empty($this->filterVlan)) {
            $query->where('client_services.vlan', $this->filterVlan);
        }

        // Apply installation date range filter
        if (!empty($this->filterInstallationDateFrom)) {
            $query->whereDate('client_services.installation_date', '>=', $this->filterInstallationDateFrom);
        }

        if (!empty($this->filterInstallationDateTo)) {
            $query->whereDate('client_services.installation_date', '<=', $this->filterInstallationDateTo);
        }

        return $query->orderBy('clients.created_at', 'desc');
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


    // Get the client count for the current project with filters applied
    public function getClientCount()
    {
        // Use the same optimized query but just count
        return $this->getProjectClientsQuery()->count(DB::raw('DISTINCT clients.id'));
    }

    // Static method to get client count for any project
    public static function getProjectClientCount($projectId)
    {
        // Optimized to use join instead of whereHas
        return Client::select('clients.id')
            ->join('client_services', 'clients.id', '=', 'client_services.client_id')
            ->where('client_services.project_id', $projectId)
            ->distinct()
            ->count();
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
     * Reset import file
     */
    public function resetFile()
    {
        $this->importFile = null;
        $this->importVendorId = null;
        $this->importCustomerType = null;
        $this->resetValidation(['importFile', 'importVendorId', 'importCustomerType']);
    }

    /**
     * Download import template with reference data
     */
    public function downloadTemplate()
    {
        try {
            $filename = 'client_import_template_' . date('Y-m-d_His') . '.xlsx';

            return Excel::download(new \App\Exports\ClientImportTemplateExport(), $filename);

        } catch (\Exception $e) {
            Log::error('Template download failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to download template: ' . $e->getMessage());
        }
    }

    /**
     * Import clients from uploaded file
     */
    public function importClients()
    {
        $this->dryRunImportClients();
        if (!empty($this->importConflicts)) {
            // Show conflict UI, do not import yet
            return;
        }
        // No conflicts, proceed with normal import
        try {
            set_time_limit(600);
            ini_set('max_execution_time', '600');
            Log::info('ClientImport: Starting import', [
                'project_id' => $this->projectId,
                'vendor_id' => $this->importVendorId,
                'customer_type' => $this->importCustomerType,
                'file_name' => $this->importFile->getClientOriginalName()
            ]);
            $import = new ClientsImport($this->projectId, $this->importVendorId, $this->importCustomerType);
            Excel::import($import, $this->importFile);
            $this->resetFile();
            $this->refreshClients();
            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();
            if ($importedCount > 0 && empty($errors)) {
                session()->flash('success', "Successfully imported {$importedCount} client(s)!");
                Log::info('ClientImport: Import completed', [
                    'project_id' => $this->projectId,
                    'imported_count' => $importedCount,
                    'error_count' => count($errors)
                ]);
                return redirect()->route('projects.view', ['project' => $this->projectId]);
            } elseif ($importedCount > 0 && !empty($errors)) {
                $errorCount = count($errors);
                session()->flash('warning', "Imported {$importedCount} client(s) with {$errorCount} error(s). Check logs for details.");
                Log::info('ClientImport: Import completed with errors', [
                    'project_id' => $this->projectId,
                    'imported_count' => $importedCount,
                    'error_count' => count($errors)
                ]);
                return redirect()->route('projects.view', ['project' => $this->projectId]);
            } elseif ($importedCount === 0 && !empty($errors)) {
                $errorSummary = implode('; ', array_slice($errors, 0, 3));
                session()->flash('error', "Import failed. Errors: {$errorSummary}");
            } else {
                session()->flash('warning', 'No clients were imported. Please check your file format.');
            }
            Log::info('ClientImport: Import completed', [
                'project_id' => $this->projectId,
                'imported_count' => $importedCount,
                'error_count' => count($errors)
            ]);
        } catch (ValidationException $e) {
            Log::error('ClientImport: Validation failed', [
                'project_id' => $this->projectId,
                'errors' => $e->errors()
            ]);
            throw $e;
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
    // Static helper for header normalization
    public static function normalizeHeaderStatic($header)
    {
        $header = strtolower($header);
        $header = preg_replace('/[^a-z0-9]+/', '_', $header);
        $header = trim($header, '_');
        return $header;
    }

    /**
     * Export clients with selected fields
     */
    public function exportClients()
    {
        try {
            // Increase execution time for large exports
            set_time_limit(300); // 5 minutes
            ini_set('memory_limit', '512M');

            // Get all clients for this project using optimized query
            $clients = Client::select('clients.*')
                ->join('client_services', 'clients.id', '=', 'client_services.client_id')
                ->where('client_services.project_id', $this->projectId)
                ->with(['clientServices' => function($q) {
                    $q->where('project_id', $this->projectId)
                      ->with(['vendor', 'product']);
                }])
                ->distinct()
                ->get();

            $filename = 'project_' . $this->projectId . '_clients_' . date('Y-m-d_His') . '.xlsx';

            // Define field mappings
            $fieldMappings = [
                'customer_name' => ['label' => 'Customer Name', 'accessor' => fn($c, $s) => $c->customer_name],
                'customer_type' => ['label' => 'Customer Type', 'accessor' => fn($c, $s) => ucfirst($c->category ?? 'home')],
                'phone' => ['label' => 'Phone', 'accessor' => fn($c, $s) => $c->phone],
                'email' => ['label' => 'Email', 'accessor' => fn($c, $s) => $c->email],
                'address' => ['label' => 'Address', 'accessor' => fn($c, $s) => $c->address],
                'region' => ['label' => 'Region', 'accessor' => fn($c, $s) => $c->region],
                'district' => ['label' => 'District', 'accessor' => fn($c, $s) => $c->district],
                'latitude' => ['label' => 'Latitude', 'accessor' => fn($c, $s) => $c->latitude],
                'longitude' => ['label' => 'Longitude', 'accessor' => fn($c, $s) => $c->longitude],
                'installation_engineer' => ['label' => 'Installation Engineer', 'accessor' => fn($c, $s) => $c->contact_person],
                'vendor' => ['label' => 'Vendor', 'accessor' => fn($c, $s) => $s->vendor->name ?? ''],
                'transmission' => ['label' => 'Transmission', 'accessor' => fn($c, $s) => $s->product->name ?? ''],
                'capacity' => ['label' => 'Capacity', 'accessor' => fn($c, $s) => $s->capacity],
                'capacity_type' => ['label' => 'Capacity Type', 'accessor' => fn($c, $s) => $s->capacity_type],
                'username' => ['label' => 'Username', 'accessor' => fn($c, $s) => $s->username],
                'serial_number' => ['label' => 'Serial Number', 'accessor' => fn($c, $s) => $s->serial_number],
                'vlan' => ['label' => 'VLAN', 'accessor' => fn($c, $s) => $s->vlan],
                'nrc' => ['label' => 'NRC', 'accessor' => fn($c, $s) => $s->nrc],
                'mrc' => ['label' => 'MRC', 'accessor' => fn($c, $s) => $s->mrc],
                'installation_date' => ['label' => 'Installation Date', 'accessor' => fn($c, $s) => $s->installation_date ? $s->installation_date->format('Y-m-d') : ''],
                'status' => ['label' => 'Status', 'accessor' => fn($c, $s) => $s->status],
            ];

            // Build headers from selected fields
            $headers = [];
            foreach ($this->exportFields as $field => $selected) {
                if ($selected && isset($fieldMappings[$field])) {
                    $headers[] = $fieldMappings[$field]['label'];
                }
            }

            // Build data rows
            $data = [];
            foreach ($clients as $client) {
                foreach ($client->clientServices as $service) {
                    $row = [];
                    foreach ($this->exportFields as $field => $selected) {
                        if ($selected && isset($fieldMappings[$field])) {
                            $row[] = $fieldMappings[$field]['accessor']($client, $service);
                        }
                    }
                    $data[] = $row;
                }
            }

            return Excel::download(new \App\Exports\ClientsExport($headers, $data), $filename);

        } catch (\Exception $e) {
            Log::error('Export failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to export clients: ' . $e->getMessage());
        }
    }

    // Milestone Management Methods
    public function openAddMilestoneModal()
    {
        $this->resetMilestoneForm();
        $this->showMilestoneModal = true;
    }

    public function closeMilestoneModal()
    {
        $this->showMilestoneModal = false;
        $this->resetMilestoneForm();
        $this->resetValidation();
    }

    public function resetMilestoneForm()
    {
        $this->editingMilestoneId = null;
        $this->milestone_name = '';
        $this->milestone_description = '';
        $this->milestone_start_date = '';
        $this->milestone_due_date = '';
        $this->milestone_amount = '';
        $this->milestone_percentage = '';
        $this->milestone_is_billable = false;
        $this->milestone_status = 'pending';
        $this->milestone_priority = 'medium';
        $this->milestone_progress = 0;
        $this->milestone_deliverables = '';
        $this->milestone_notes = '';
        $this->milestone_assigned_to = null;
        $this->milestone_depends_on = null;
    }

    public function saveMilestone()
    {
        $validated = $this->validate([
            'milestone_name' => 'required|string|max:255',
            'milestone_description' => 'nullable|string',
            'milestone_start_date' => 'nullable|date',
            'milestone_due_date' => 'nullable|date',
            'milestone_amount' => 'nullable|numeric|min:0',
            'milestone_percentage' => 'nullable|numeric|min:0|max:100',
            'milestone_is_billable' => 'boolean',
            'milestone_status' => 'required|in:pending,in_progress,completed,delayed,cancelled,invoiced',
            'milestone_priority' => 'required|in:low,medium,high,critical',
            'milestone_progress' => 'required|integer|min:0|max:100',
            'milestone_deliverables' => 'nullable|string',
            'milestone_notes' => 'nullable|string',
            'milestone_assigned_to' => 'nullable|exists:users,id',
            'milestone_depends_on' => 'nullable|exists:project_milestones,id',
        ]);

        try {
            if ($this->editingMilestoneId) {
                $milestone = \App\Models\ProjectMilestone::findOrFail($this->editingMilestoneId);
                $milestone->update([
                    'name' => $validated['milestone_name'],
                    'description' => $validated['milestone_description'],
                    'start_date' => $validated['milestone_start_date'],
                    'due_date' => $validated['milestone_due_date'],
                    'amount' => $validated['milestone_amount'],
                    'percentage' => $validated['milestone_percentage'],
                    'is_billable' => $validated['milestone_is_billable'],
                    'status' => $validated['milestone_status'],
                    'priority' => $validated['milestone_priority'],
                    'progress_percentage' => $validated['milestone_progress'],
                    'deliverables' => $validated['milestone_deliverables'],
                    'notes' => $validated['milestone_notes'],
                    'assigned_to' => $validated['milestone_assigned_to'],
                    'depends_on_milestone_id' => $validated['milestone_depends_on'],
                ]);
                session()->flash('success', 'Milestone updated successfully.');
            } else {
                \App\Models\ProjectMilestone::create([
                    'project_id' => $this->projectId,
                    'name' => $validated['milestone_name'],
                    'description' => $validated['milestone_description'],
                    'start_date' => $validated['milestone_start_date'],
                    'due_date' => $validated['milestone_due_date'],
                    'amount' => $validated['milestone_amount'],
                    'percentage' => $validated['milestone_percentage'],
                    'is_billable' => $validated['milestone_is_billable'],
                    'status' => $validated['milestone_status'],
                    'priority' => $validated['milestone_priority'],
                    'progress_percentage' => $validated['milestone_progress'],
                    'deliverables' => $validated['milestone_deliverables'],
                    'notes' => $validated['milestone_notes'],
                    'assigned_to' => $validated['milestone_assigned_to'],
                    'depends_on_milestone_id' => $validated['milestone_depends_on'],
                    'created_by' => Auth::id(),
                ]);
                session()->flash('success', 'Milestone created successfully.');
            }

            $this->loadProjectData();
            $this->closeMilestoneModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save milestone: ' . $e->getMessage());
        }
    }

    public function editMilestone($milestoneId)
    {
        $milestone = \App\Models\ProjectMilestone::findOrFail($milestoneId);

        $this->editingMilestoneId = $milestone->id;
        $this->milestone_name = $milestone->name;
        $this->milestone_description = $milestone->description;
        $this->milestone_start_date = $milestone->start_date?->format('Y-m-d');
        $this->milestone_due_date = $milestone->due_date?->format('Y-m-d');
        $this->milestone_amount = $milestone->amount;
        $this->milestone_percentage = $milestone->percentage;
        $this->milestone_is_billable = $milestone->is_billable;
        $this->milestone_status = $milestone->status;
        $this->milestone_priority = $milestone->priority;
        $this->milestone_progress = $milestone->progress_percentage;
        $this->milestone_deliverables = $milestone->deliverables;
        $this->milestone_notes = $milestone->notes;
        $this->milestone_assigned_to = $milestone->assigned_to;
        $this->milestone_depends_on = $milestone->depends_on_milestone_id;

        $this->showMilestoneModal = true;
    }

    public function deleteMilestone($milestoneId)
    {
        try {
            $milestone = \App\Models\ProjectMilestone::findOrFail($milestoneId);
            $milestone->delete();

            $this->loadProjectData();
            session()->flash('success', 'Milestone deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete milestone: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Cache active vendors to avoid repeated queries
        $allVendors = cache()->remember('active_vendors', 300, function() {
            return Vendor::where('status', 'active')->get();
        });

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
            'vendors' => $allVendors, // Reuse cached vendors
        ]);
    }
}
