<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class ClientsListComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 6;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clients = Client::with(['services.serviceType', 'services.product'])
            ->when($this->search, function ($query) {
                $query->where('company', 'like', '%' . $this->search . '%')
                    ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('tin_no', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.clients.clients-list-component', [
            'clients' => $clients
        ])->layout('layouts.app');
    }
}
