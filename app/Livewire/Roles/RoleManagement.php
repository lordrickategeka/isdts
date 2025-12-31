<?php

namespace App\Livewire\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManagement extends Component
{
    use WithPagination;

    public $name;
    public $selectedPermissions = [];
    public $editingRoleId = null;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        'selectedPermissions' => 'array',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->selectedPermissions = [];
        $this->editingRoleId = null;
        $this->resetValidation();
    }

    public function save()
    {
        // Update validation rules before validating
        if ($this->editingRoleId) {
            $this->rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->editingRoleId;
        } else {
            $this->rules['name'] = 'required|string|max:255|unique:roles,name';
        }

        $this->validate();

        try {
            if ($this->editingRoleId) {
                $role = Role::find($this->editingRoleId);
                $role->update(['name' => $this->name]);
                $message = 'Role updated successfully!';
            } else {
                $role = Role::create(['name' => $this->name]);
                $message = 'Role created successfully!';
            }

            // Sync permissions
            $role->syncPermissions($this->selectedPermissions);

            session()->flash('success', $message);
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($roleId)
    {
        $role = Role::with('permissions')->find($roleId);
        $this->editingRoleId = $role->id;
        $this->name = $role->name;
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        $this->showModal = true;
    }

    public function delete($roleId)
    {
        try {
            $role = Role::find($roleId);

            // Check if role has users
            if ($role->users()->count() > 0) {
                session()->flash('error', 'Cannot delete role. It is assigned to ' . $role->users()->count() . ' user(s).');
                return;
            }

            $role->delete();
            session()->flash('success', 'Role deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $roles = Role::withCount('permissions', 'users')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(10);

        $permissions = Permission::orderBy('name')->get();

        return view('livewire.roles.role-management', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }
}
