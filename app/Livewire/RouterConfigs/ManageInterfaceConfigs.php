<?php

namespace App\Livewire\RouterConfigs;

use Livewire\Component;
use App\Models\RouterInterface;

class ManageInterfaceConfigs extends Component
{
    public $interfaces;

    public function mount()
    {
        $this->interfaces = RouterInterface::all();
    }

    public function render()
    {
        return view('livewire.router-configs.manage-interface-configs');
    }
}
