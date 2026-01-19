<?php

namespace App\Livewire\Departments;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DepartmentIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $showModal = false;
    public $editMode = false;
    public $showDeleteConfirm = false;

    // Form fields
    public $departmentId;
    public $name;
    public $code;
    public $description;
    public $parent_id;
    public $manager_id;
    public $is_active = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:departments,code',
        'description' => 'nullable|string',
        'parent_id' => 'nullable|exists:departments,id',
        'manager_id' => 'nullable|exists:users,id',
        'is_active' => 'boolean',
    ];

    protected $messages = [
        'name.required' => 'Department name is required.',
        'code.required' => 'Department code is required.',
        'code.unique' => 'This code is already in use.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->departmentId = null;
        $this->name = '';
        $this->code = '';
        $this->description = '';
        $this->parent_id = null;
        $this->manager_id = null;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);

        $this->departmentId = $department->id;
        $this->name = $department->name;
        $this->code = $department->code;
        $this->description = $department->description;
        $this->parent_id = $department->parent_id;
        $this->manager_id = $department->manager_id;
        $this->is_active = $department->is_active;

        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        if ($this->editMode) {
            $this->rules['code'] = 'required|string|max:50|unique:departments,code,' . $this->departmentId;
        }

        $this->validate();

        // Prevent circular parent relationships
        if ($this->parent_id == $this->departmentId) {
            $this->addError('parent_id', 'A department cannot be its own parent.');
            return;
        }

        DB::beginTransaction();
        try {
            $data = [
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'parent_id' => $this->parent_id,
                'manager_id' => $this->manager_id,
                'is_active' => $this->is_active,
            ];

            if ($this->editMode) {
                $department = Department::findOrFail($this->departmentId);
                $department->update($data);
                $message = 'Department updated successfully.';
            } else {
                Department::create($data);
                $message = 'Department created successfully.';
            }

            DB::commit();

            session()->flash('success', $message);
            $this->closeModal();
            $this->resetPage();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->departmentId = $id;
        $this->showDeleteConfirm = true;
    }

    public function delete()
    {
        try {
            $department = Department::findOrFail($this->departmentId);

            // Check if department has children
            if ($department->children()->count() > 0) {
                session()->flash('error', 'Cannot delete department with child departments.');
                $this->showDeleteConfirm = false;
                return;
            }

            // Check if department has users
            if ($department->users()->count() > 0) {
                session()->flash('error', 'Cannot delete department with assigned users.');
                $this->showDeleteConfirm = false;
                return;
            }

            $department->delete();
            session()->flash('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }

        $this->showDeleteConfirm = false;
        $this->departmentId = null;
        $this->resetPage();
    }

    public function toggleStatus($id)
    {
        try {
            $department = Department::findOrFail($id);
            $department->is_active = !$department->is_active;
            $department->save();

            $status = $department->is_active ? 'activated' : 'deactivated';
            session()->flash('success', "Department {$status} successfully.");
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $departments = Department::with(['parent', 'manager', 'children'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate($this->perPage);

        $parentDepartments = Department::active()->orderBy('name')->get();
        $managers = User::orderBy('name')->get();

        return view('livewire.departments.department-index', [
            'departments' => $departments,
            'parentDepartments' => $parentDepartments,
            'managers' => $managers,
        ]);
    }
}
