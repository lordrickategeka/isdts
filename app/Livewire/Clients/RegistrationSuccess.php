<?php

namespace App\Livewire\Clients;

use Livewire\Component;

class RegistrationSuccess extends Component
{
    public function render()
    {
        return view('livewire.clients.registration-success')
            ->layout('layouts.client');
    }
}
