<?php

namespace App\Livewire\Sessions;

use Livewire\Component;
use App\Models\BillingSession;

class BillingSessions extends Component
{
    public $sessionId;
    public $billingSessions = [];

    public function mount($sessionId)
    {
        $this->sessionId = $sessionId;
        $this->loadBilling();
    }

    public function loadBilling()
    {
        $this->billingSessions = BillingSession::where('network_session_id', $this->sessionId)
            ->orderBy('billable_start_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.sessions.billing-sessions', [
            'billingSessions' => $this->billingSessions,
        ]);
    }
}
