<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRoleManagement extends Component
{
    use WithPagination;

    public $selectedUser = null;
    public $selectedRoles = [];
    public $showModal = false;
    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal($userId)
    {
        $user = User::with(['roles'])->findOrFail($userId);
        $this->selectedUser = $user;
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedUser = null;
        $this->selectedRoles = [];
    }

    public function save()
    {
        try {
            $user = User::findOrFail($this->selectedUser->id);

            // Sync roles - permissions are automatically inherited from roles
            $user->syncRoles($this->selectedRoles);

            session()->flash('success', 'User roles updated successfully! All permissions from assigned roles are now active.');
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $users = User::with(['roles', 'permissions'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        return view('livewire.users.user-role-management', [
            'users' => $users,
            'roles' => $roles,
            'permissions' => $permissions,
        ])->layout('layouts.app');
    }
}
