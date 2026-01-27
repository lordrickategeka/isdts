<?php

namespace App\Livewire\Routers;

use Livewire\Component;
use App\Models\Router;

class EditRouterDetails extends Component
{
    public Router $router;

    public $name;
    public $site;
    public $management_ip;
    public $api_port;
    public $username;
    public $password;
    public $connection_method;
    public $use_tls = false;
    public $timeout_seconds = 5;
    public $role;
    public $ownership;

    public function mount(Router $router)
    {
        $this->router = $router;
        $this->name = $router->name;
        $this->site = $router->site;
        $this->management_ip = $router->management_ip;
        $this->api_port = $router->api_port;
        $this->username = $router->username;
        $this->password = '';
        $this->connection_method = $router->connection_method;
        $this->use_tls = $router->use_tls;
        $this->timeout_seconds = $router->timeout_seconds;
        $this->role = $router->role;
        $this->ownership = $router->ownership;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'site' => 'nullable|string|max:255',
            'management_ip' => 'required|ip',
            'api_port' => 'required|integer|min:1|max:65535',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string',
            'connection_method' => 'required|string',
            'use_tls' => 'boolean',
            'timeout_seconds' => 'required|integer|min:1|max:60',
            'role' => 'required|string',
            'ownership' => 'required|string',
        ]);

        $this->router->update([
            'name' => $this->name,
            'site' => $this->site,
            'management_ip' => $this->management_ip,
            'api_port' => $this->api_port,
            'username' => $this->username,
            'password' => $this->password ? encrypt($this->password) : $this->router->password,
            'connection_method' => $this->connection_method,
            'use_tls' => $this->use_tls,
            'timeout_seconds' => $this->timeout_seconds,
            'role' => $this->role,
            'ownership' => $this->ownership,
        ]);

        session()->flash('message', 'Router details updated successfully.');
        return redirect()->route('routers.details', $this->router->id);
    }

    public function render()
    {
        return view('livewire.routers.edit-router-details');
    }
}
