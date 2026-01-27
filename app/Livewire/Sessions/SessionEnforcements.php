<?php

namespace App\Livewire\Sessions;

use Livewire\Component;
use App\Models\NetworkEnforcement;

class SessionEnforcements extends Component
{
    public $sessionId;
    public $enforcements = [];

    protected $listeners = ['refreshEnforcements' => 'loadEnforcements'];

    public function mount($sessionId)
    {
        $this->sessionId = $sessionId;
        $this->loadEnforcements();
    }

    public function loadEnforcements()
    {
        $this->enforcements = NetworkEnforcement::where('network_session_id', $this->sessionId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function enforce($action)
    {
        $enf = NetworkEnforcement::create([
            'network_session_id' => $this->sessionId,
            'action' => $action,
            'status' => 'pending',
        ]);
        $this->emitSelf('refreshEnforcements');
    }

    public function render()
    {
        return view('livewire.sessions.session-enforcements', [
            'enforcements' => $this->enforcements,
        ]);
    }
}
