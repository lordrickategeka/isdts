<?php

namespace App\Livewire\Permissions;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

class PermissionManagement extends Component
{
    use WithPagination;

    public $name;
    public $editingPermissionId = null;
    public $showModal = false;
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255|unique:permissions,name',
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
        $this->editingPermissionId = null;
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editingPermissionId) {
                $permission = Permission::find($this->editingPermissionId);
                $permission->update(['name' => $this->name]);
                $message = 'Permission updated successfully!';
            } else {
                Permission::create(['name' => $this->name]);
                $message = 'Permission created successfully!';
            }

            session()->flash('success', $message);
            $this->closeModal();
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($permissionId)
    {
        $permission = Permission::find($permissionId);
        $this->editingPermissionId = $permission->id;
        $this->name = $permission->name;
        $this->showModal = true;

        $this->rules['name'] = 'required|string|max:255|unique:permissions,name,' . $permissionId;
    }

    public function delete($permissionId)
    {
        try {
            $permission = Permission::find($permissionId);

            // Check if permission is assigned to roles
            if ($permission->roles()->count() > 0) {
                session()->flash('error', 'Cannot delete permission. It is assigned to ' . $permission->roles()->count() . ' role(s).');
                return;
            }

            $permission->delete();
            session()->flash('success', 'Permission deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $permissions = Permission::withCount('roles')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->paginate(15);

        return view('livewire.permissions.permission-management', [
            'permissions' => $permissions,
        ])->layout('layouts.app');
    }
}
