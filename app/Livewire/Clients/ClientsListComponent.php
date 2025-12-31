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

    protected function getApprovalSequence()
    {
        return [
            'Sales Manager',
            'CCO',
            'Credit Control Manager',
            'CFO',
            'Business Analysis',
            'Network Planning',
            'Implementation Manager',
            'Director',
        ];
    }

    protected function getUserPosition()
    {
        $user = auth()->user();
        if (!$user) return null;

        $rolePositionMap = [
            'sales_manager' => 'Sales Manager',
            'chief_commercial' => 'CCO',
            'credit_control' => 'Credit Control Manager',
            'chief_financial' => 'CFO',
            'business_analyst' => 'Business Analysis',
            'network_planning' => 'Network Planning',
            'implementation' => 'Implementation Manager',
            'director' => 'Director',
        ];

        foreach ($user->roles as $role) {
            if (isset($rolePositionMap[$role->name])) {
                return $rolePositionMap[$role->name];
            }
        }

        return null;
    }

    protected function canUserApprove($client)
    {
        $userPosition = $this->getUserPosition();
        if (!$userPosition) return false;

        $sequence = $this->getApprovalSequence();
        $currentIndex = array_search($userPosition, $sequence);

        if ($currentIndex === false) return false;

        // Check if all previous positions have been completed
        for ($i = 0; $i < $currentIndex; $i++) {
            $previousPosition = $sequence[$i];
            $previousSignature = \App\Models\UserSignature::where('client_id', $client->id)
                ->where('position', $previousPosition)
                ->first();

            // If previous signature is still pending, current position cannot approve
            if (!$previousSignature || $previousSignature->status === 'pending') {
                return false;
            }
        }

        // Check if user's position is still pending
        $userSignature = \App\Models\UserSignature::where('client_id', $client->id)
            ->where('position', $userPosition)
            ->first();

        return $userSignature && $userSignature->status === 'pending';
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

        // Check approval readiness for each client
        $approvalReadiness = [];
        foreach ($clients as $client) {
            $approvalReadiness[$client->id] = $this->canUserApprove($client);
        }

        return view('livewire.clients.clients-list-component', [
            'clients' => $clients,
            'approvalReadiness' => $approvalReadiness,
        ])->layout('layouts.app');
    }
}
