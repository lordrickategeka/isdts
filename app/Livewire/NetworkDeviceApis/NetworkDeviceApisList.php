<?php

namespace App\Livewire\NetworkDeviceApis;

use App\Models\Router;
use Livewire\Component;


class NetworkDeviceApisList extends Component
{
    public $search = '';
    public $statusFilter = 'all';
    public $roleFilter = 'all';
    public $ownershipFilter = 'all';
    public $activeTab = 'networks';

    public function render()
    {
        $query = Router::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('management_ip', 'like', "%{$this->search}%")
                  ->orWhere('serial_number', 'like', "%{$this->search}%");
            });
        }
        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'online') {
                $query->online();
            } elseif ($this->statusFilter === 'offline') {
                $query->where(function($q) {
                    $q->where('last_seen_at', '<', now()->subMinutes(5))
                      ->orWhereNull('last_seen_at');
                });
            }
        }
        if ($this->roleFilter !== 'all') {
            $query->byRole($this->roleFilter);
        }
        if ($this->ownershipFilter !== 'all') {
            $query->where('ownership', $this->ownershipFilter);
        }

        $routers = $query->orderBy('name')->get();

        return view('livewire.network-device-apis.network-device-apis-list', [
            'routers' => $routers,
        ]);
    }
}
