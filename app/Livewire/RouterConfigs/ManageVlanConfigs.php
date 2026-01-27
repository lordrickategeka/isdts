<?php

namespace App\Livewire\RouterConfigs;

use Livewire\Component;
use App\Models\RouterVlan;

class ManageVlanConfigs extends Component
{
    public $vlans;

    public function mount()
    {
        $this->vlans = RouterVlan::all();
    }

    public function render()
    {
        return view('livewire.router-configs.manage-vlan-configs');
    }
}
