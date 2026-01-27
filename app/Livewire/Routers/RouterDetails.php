<?php

namespace App\Livewire\Routers;

use App\Models\Router;
use App\Models\RouterTruthSource;
use App\Models\RouterSnapshot;
use App\Models\NetworkSession;
use Livewire\Component;
use Livewire\WithPagination;

class RouterDetails extends Component
{
    use WithPagination;

    public $router;
    public $activeTab = 'overview';

    // Truth Source Management
    public $showAddSourceModal = false;
    public $selectedSource;
    public $sourceEnabled = true;
    public $pollInterval = 10;

    protected $queryString = ['activeTab'];

    public function mount($router)
    {
        $this->router = Router::with(['truthSources', 'networkSessions'])->findOrFail($router);
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    // Truth Source Management
    public function openAddSourceModal()
    {
        $this->reset(['selectedSource', 'sourceEnabled', 'pollInterval']);
        $this->showAddSourceModal = true;
    }

    public function closeAddSourceModal()
    {
        $this->showAddSourceModal = false;
    }

    public function addTruthSource()
    {
        $this->validate([
            'selectedSource' => 'required|string',
            'pollInterval' => 'required|integer|min:5|max:300',
        ]);

        RouterTruthSource::create([
            'router_id' => $this->router->id,
            'source' => $this->selectedSource,
            'enabled' => $this->sourceEnabled,
            'poll_interval_seconds' => $this->pollInterval,
        ]);

        session()->flash('message', 'Truth source added successfully.');
        $this->closeAddSourceModal();
        $this->router->refresh();
    }

    public function toggleSource($sourceId)
    {
        $source = RouterTruthSource::find($sourceId);
        $source->update([
            'enabled' => !$source->enabled,
            'disabled_at' => $source->enabled ? null : now(),
        ]);

        session()->flash('message', 'Truth source updated.');
        $this->router->refresh();
    }

    public function deleteSource($sourceId)
    {
        RouterTruthSource::find($sourceId)->delete();
        session()->flash('message', 'Truth source removed.');
        $this->router->refresh();
    }

    public function getSnapshotsProperty()
    {
        return RouterSnapshot::where('router_id', $this->router->id)
            ->latest('captured_at')
            ->paginate(20);
    }

    public function getActiveSessionsProperty()
    {
        return NetworkSession::where('router_id', $this->router->id)
            ->where('active', true)
            ->latest('last_seen_at')
            ->paginate(15);
    }

    public function render()
    {
        return view('livewire.routers.router-details', [
            'truthSources' => $this->router->truthSources,
            'snapshots' => $this->activeTab === 'snapshots' ? $this->snapshots : [],
            'activeSessions' => $this->activeTab === 'sessions' ? $this->activeSessions : [],
        ]);
    }
}
