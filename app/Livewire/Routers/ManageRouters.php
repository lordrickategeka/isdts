<?php

namespace App\Livewire\Routers;

use App\Models\Router;
use App\Services\Routers\RouterSnapshotService;
use App\Services\Sessions\SessionCorrelationEngine;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class ManageRouters extends Component
{
    use WithPagination;

    // Filters
    public $search = '';
    public $statusFilter = 'all';
    public $roleFilter = 'all';
    public $ownershipFilter = 'all';

    // Modal states
    public $showAddModal = false;
    public $showDetailsModal = false;
    public $selectedRouter = null;

    // Form fields for adding router
    public $name;
    public $site;
    public $management_ip;
    public $role = 'access';
    public $ownership = 'managed';
    public $connection_method = 'api';
    public $api_port = 8728;
    public $username;
    public $password;

    // Tab state
    public $activeTab = 'routers';

    // Track which router is being tested
    public $testInProgress = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'site' => 'nullable|string|max:255',
        'management_ip' => 'required|ip',
        'role' => 'required|in:core,distribution,access,cpe,test',
        'ownership' => 'required|in:managed,customer_owned',
        'connection_method' => 'required|in:api,ssh,radius',
        'api_port' => 'required|integer|min:1|max:65535',
        'username' => 'required|string|max:255',
        'password' => 'required|string',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    public function openAddModal()
    {
        $this->reset(['name', 'site', 'management_ip', 'role', 'ownership', 'connection_method', 'api_port', 'username', 'password']);
        $this->showAddModal = true;
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->resetValidation();
    }

    public function saveRouter()
    {
        $this->validate();

        Router::create([
            'name' => $this->name,
            'site' => $this->site,
            'management_ip' => $this->management_ip,
            'role' => $this->role,
            'ownership' => $this->ownership,
            'connection_method' => $this->connection_method,
            'api_port' => $this->api_port,
            'username' => $this->username,
            'password' => encrypt($this->password) ?: null,
            'is_active' => true,
        ]);

        session()->flash('message', 'Router added successfully.');
        $this->closeAddModal();
    }

    public function viewRouter($routerId)
    {
        return redirect()->route('routers.details', ['router' => $routerId]);
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedRouter = null;
    }


    public function toggleRouter($routerId)
    {
        $router = Router::find($routerId);
        $router->update([
            'is_active' => !$router->is_active,
            'disabled_at' => $router->is_active ? null : now(),
        ]);

        session()->flash('message', "Router {$router->name} " . ($router->is_active ? 'enabled' : 'disabled'));
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function getRoutersProperty()
    {
        $query = Router::query();

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('site', 'like', '%' . $this->search . '%')
                    ->orWhere('management_ip', 'like', '%' . $this->search . '%')
                    ->orWhere('serial_number', 'like', '%' . $this->search . '%');
            });
        }

        // Status filter
        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'online') {
                $query->online();
            } elseif ($this->statusFilter === 'offline') {
                $query->where(function ($q) {
                    $q->where('is_active', false)
                        ->orWhereNotNull('disabled_at')
                        ->orWhere('last_seen_at', '<', now()->subMinutes(15));
                });
            }
        }

        // Role filter
        if ($this->roleFilter !== 'all') {
            $query->byRole($this->roleFilter);
        }

        // Ownership filter
        if ($this->ownershipFilter !== 'all') {
            $query->where('ownership', $this->ownershipFilter);
        }

        return $query->latest()->paginate(15);
    }

    public function render()
    {
        return view('livewire.routers.manage-routers', [
            'routers' => $this->routers,
            'activeTab' => $this->activeTab,
        ]);
    }

    public function testRouterConnection($routerId, \App\Services\Routers\RouterDiscoveryService $discovery)
    {
        $this->testInProgress = $routerId;
        try {
            Log::debug('testRouterConnection called', ['routerId' => $routerId]);
            $router = \App\Models\Router::findOrFail($routerId);
            Log::debug('Router loaded', ['router' => $router]);
            $result = $discovery->discover($router);
            Log::debug('Discovery result', ['result' => $result]);

            if (! $result['success']) {
                Log::error('Router discovery failed', ['routerId' => $routerId, 'error' => $result['error']]);
                // Mark router as inactive and set disabled_at
                $router->update([
                    'is_active' => false,
                    'disabled_at' => now(),
                ]);
                session()->flash('error', $result['error'] . ' (Router marked as inactive)');
                return;
            }

            // If connection is successful, ensure router is active
            $router->update([
                'is_active' => true,
                'disabled_at' => null,
            ]);
            $this->seedTruthSources($router);
            $router->refresh();
            Log::info('Router connected and verified', ['routerId' => $routerId]);
            session()->flash('message', 'Router connected and verified');
        } finally {
            $this->testInProgress = null;
        }
    }

    protected function seedTruthSources($router)
    {

        $sources = [
            'hotspot_active',
            'dhcp_lease',
            'firewall_connection',
            'ppp_active',
        ];

        foreach ($sources as $source) {
            $router->truthSources()->firstOrCreate([
                'source' => $source,
            ], [
                'enabled' => true,
                'poll_interval_seconds' => 10,
            ]);
        }
    }

    public function pollNow($routerId, RouterSnapshotService $service)
    {
        $router = \App\Models\Router::findOrFail($routerId);
        foreach ($router->truthSources()->where('enabled', true)->get() as $source) {
            $service->capture($router, $source->source, app(\App\Services\Sessions\SessionCorrelationEngine::class));
        }
        session()->flash('message', 'Router polled successfully');
    }

    public function editRouter($routerId)
    {
        return redirect()->route('routers.edit', ['router' => $routerId]);
    }

    public function deleteRouter($routerId)
    {
        if (!request()->user() || !request()->user()->can('can-delete-router-details')) {
            session()->flash('error', 'You do not have permission to delete routers.');
            return;
        }
        $router = \App\Models\Router::findOrFail($routerId);
        $router->delete();
        session()->flash('message', 'Router deleted successfully.');
    }
}
