<?php

namespace App\Livewire\RouterConfigs;

use Livewire\Component;
use App\Models\RouterBridgePort;

class ManageBridgePortConfigs extends Component
{
    public $bridgePorts;

    public function mount()
    {
        $this->bridgePorts = RouterBridgePort::all();
    }

    public function render()
    {
        return view('livewire.router-configs.manage-bridge-port-configs');
    }
}
