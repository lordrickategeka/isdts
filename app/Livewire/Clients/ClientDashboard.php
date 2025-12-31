<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class ClientDashboard extends Component
{
    public $client;
    public $agreements = [];
    public $services = [];

    public function mount()
    {
        // Find client by authenticated user's email
        if (Auth::check() && Auth::user()->email) {
            $this->client = Client::where('email', Auth::user()->email)->first();

            if ($this->client) {
                // Load client's agreements and services
                $this->services = $this->client->services()->with(['serviceType', 'product'])->get();
            }
        }
    }

    public function render()
    {
        return view('livewire.clients.client-dashboard')
            ->layout('layouts.client');
    }
}
