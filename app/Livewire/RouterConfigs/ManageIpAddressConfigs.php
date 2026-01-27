<?php

namespace App\Livewire\RouterConfigs;

use Livewire\Component;
use App\Models\RouterIpAddress;

class ManageIpAddressConfigs extends Component
{
    public $ipAddresses;

    public function mount()
    {
        $this->ipAddresses = RouterIpAddress::all();
    }

    public function render()
    {
        return view('livewire.router-configs.manage-ip-address-configs');
    }
}
