<?php

namespace App\Livewire\AuditLogs;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $event = '';
    public $user_id = '';
    public $model_type = '';
    public $date_from = '';
    public $date_to = '';
    public $perPage = 25;
    public $showDetails = false;
    public $selectedLog = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'event' => ['except' => ''],
        'user_id' => ['except' => ''],
        'model_type' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEvent()
    {
        $this->resetPage();
    }

    public function updatingUserId()
    {
        $this->resetPage();
    }

    public function updatingModelType()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->event = '';
        $this->user_id = '';
        $this->model_type = '';
        $this->date_from = '';
        $this->date_to = '';
        $this->resetPage();
    }

    public function viewDetails($logId)
    {
        $this->selectedLog = AuditLog::with(['user'])->find($logId);
        $this->showDetails = true;
    }

    public function closeDetails()
    {
        $this->showDetails = false;
        $this->selectedLog = null;
    }

    public function render()
    {
        $logs = AuditLog::with(['user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('description', 'like', '%' . $this->search . '%')
                      ->orWhere('user_name', 'like', '%' . $this->search . '%')
                      ->orWhere('auditable_type', 'like', '%' . $this->search . '%')
                      ->orWhere('ip_address', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->event, function ($query) {
                $query->where('event', $this->event);
            })
            ->when($this->user_id, function ($query) {
                $query->where('user_id', $this->user_id);
            })
            ->when($this->model_type, function ($query) {
                $query->where('auditable_type', 'like', '%' . $this->model_type . '%');
            })
            ->when($this->date_from, function ($query) {
                $query->whereDate('created_at', '>=', $this->date_from);
            })
            ->when($this->date_to, function ($query) {
                $query->whereDate('created_at', '<=', $this->date_to);
            })
            ->latest()
            ->paginate($this->perPage);

        $users = User::orderBy('name')->get();
        $events = AuditLog::select('event')->distinct()->pluck('event');
        $models = AuditLog::select('auditable_type')->distinct()->pluck('auditable_type')
            ->map(function ($type) {
                return class_basename($type);
            })->unique()->sort()->values();

        return view('livewire.audit-logs.audit-log-index', [
            'logs' => $logs,
            'users' => $users,
            'events' => $events,
            'models' => $models,
        ]);
    }
}
