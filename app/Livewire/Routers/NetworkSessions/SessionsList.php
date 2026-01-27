<?php

namespace App\Livewire\Routers\NetworkSessions;

use App\Models\NetworkSession;
use App\Models\Router;
use Livewire\Component;
use Livewire\WithPagination;

class SessionsList extends Component
{
    use WithPagination;

    public $search = '';
    public $routerFilter = 'all';
    public $accessTypeFilter = 'all';
    public $activeFilter = 'active';

    public $showDetailsModal = false;
    public $selectedSession = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function viewSession($sessionId)
    {
        $this->selectedSession = NetworkSession::with(['router', 'events' => function($q) {
            $q->latest('occurred_at')->limit(10);
        }])->find($sessionId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedSession = null;
    }

    public function getSessionsProperty()
    {
        $query = NetworkSession::with('router');

        // Search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('mac_address', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%')
                  ->orWhere('ip_address', 'like', '%' . $this->search . '%');
            });
        }

        // Router filter
        if ($this->routerFilter !== 'all') {
            $query->where('router_id', $this->routerFilter);
        }

        // Access type filter
        if ($this->accessTypeFilter !== 'all') {
            $query->where('access_type', $this->accessTypeFilter);
        }

        // Active filter
        if ($this->activeFilter === 'active') {
            $query->where('active', true);
        } elseif ($this->activeFilter === 'ended') {
            $query->where('active', false);
        }

        return $query->latest('last_seen_at')->paginate(20);
    }

    public function render()
    {
        return view('livewire.routers.network-sessions.sessions-list', [
            'sessions' => $this->sessions,
            'routers' => Router::active()->get(),
        ]);
    }
}
