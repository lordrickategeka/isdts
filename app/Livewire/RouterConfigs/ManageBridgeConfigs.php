<?php

namespace App\Livewire\RouterConfigs;

use Livewire\Component;
use App\Models\RouterBridge;

class ManageBridgeConfigs extends Component
{
    public $bridges;

    public function mount()
    {
        $this->bridges = RouterBridge::all();
    }

    public function render()
    {
        return view('livewire.router-configs.manage-bridge-configs');
    }
}
