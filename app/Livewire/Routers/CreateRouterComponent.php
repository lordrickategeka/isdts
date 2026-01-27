<?php

namespace App\Livewire\Routers;

use Livewire\Component;
use App\Models\Router;

class CreateRouterComponent extends Component
{
    public $tenant_id;
    public $name;
    public $site;
    public $ownership;
    public $identity;
    public $serial_number;
    public $board_name;
    public $management_ip;
    public $api_port;
    public $connection_method = 'api';
    public $use_tls = false;
    public $timeout_seconds = 30;
    public $username;
    public $password;
    public $credential_ref;
    public $rotate_credentials = false;
    public $credentials_rotated_at;
    public $role;
    public $uplink_type;
    public $capabilities = [];
    public $is_active = true;
    public $last_seen_at;
    public $last_polled_at;
    public $poll_failures = 0;
    public $disabled_at;
    public $os_type;
    public $os_version;
    public $metadata = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'site' => 'nullable|string|max:255',
        'ownership' => 'required|string',
        'identity' => 'nullable|string|max:255',
        'serial_number' => 'nullable|string|max:255',
        'board_name' => 'nullable|string|max:255',
        'management_ip' => 'required|string|max:255',
        'api_port' => 'required|integer',
        'connection_method' => 'required|string',
        'use_tls' => 'boolean',
        'timeout_seconds' => 'nullable|integer',
        'username' => 'nullable|string|max:255',
        'password' => 'nullable|string|max:255',
        'credential_ref' => 'nullable|string|max:255',
        'rotate_credentials' => 'boolean',
        'credentials_rotated_at' => 'nullable|date',
        'role' => 'required|string',
        'uplink_type' => 'nullable|string|max:255',
        'capabilities' => 'nullable|array',
        'is_active' => 'boolean',
        'last_seen_at' => 'nullable|date',
        'last_polled_at' => 'nullable|date',
        'poll_failures' => 'nullable|integer',
        'disabled_at' => 'nullable|date',
        'os_type' => 'nullable|string|max:255',
        'os_version' => 'nullable|string|max:255',
        'metadata' => 'nullable|array',
    ];

    public function save()
    {
        $this->validate();
        Router::create($this->only(array_keys($this->rules)));
        session()->flash('message', 'Router created successfully!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.routers.create-router');
    }
}
