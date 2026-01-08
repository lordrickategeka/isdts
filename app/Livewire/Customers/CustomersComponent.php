<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Client;

class CustomersComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clients = Client::with(['services.product.vendorService', 'services.vendor', 'services.project'])
            ->when($this->search, function ($query) {
                $query->where('customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('contact_person', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('phone', 'like', '%' . $this->search . '%')
                    ->orWhere('tin_no', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.customers.customers-component', [
            'clients' => $clients,
        ]);
    }
}
