<?php

namespace App\Livewire\Party;

use App\Models\PartyCategory;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class CategoriesComponent extends Component
{
    use WithPagination;

    public $query = '';
    public $perPage = 10;

    public $showForm = false;
    public $editId = null;
    public $name = '';
    public $description = '';

    public $confirmDeleteId = null;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('party_categories', 'name')->ignore($this->editId),
            ],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function getCategoriesProperty()
    {
        $search = trim(strtolower($this->query));
        return PartyCategory::withCount('parties')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('name', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);
    }

    public function createNew()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $cat = PartyCategory::findOrFail($id);
        $this->editId = $cat->id;
        $this->name = $cat->name;
        $this->description = $cat->description ?? '';
        $this->showForm = true;
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editId) {
            $cat = PartyCategory::findOrFail($this->editId);
            $cat->update($data);
        } else {
            $payload = $data;
            if (auth()->check() && isset(auth()->user()->tenant_id)) {
                $payload['tenant_id'] = auth()->user()->tenant_id;
            }
            PartyCategory::create($payload);
        }

        $this->showForm = false;
        $this->resetForm();
        $this->resetPage();
    }

    public function cancel()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function deleteConfirmed()
    {
        if ($this->confirmDeleteId) {
            $cat = PartyCategory::find($this->confirmDeleteId);
            if ($cat) {
                $cat->delete();
            }
        }
        $this->confirmDeleteId = null;
        $this->resetPage();
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    protected function resetForm()
    {
        $this->editId = null;
        $this->name = '';
        $this->description = '';
    }

    public function render()
    {
        $search = trim(strtolower($this->query));
        $items = PartyCategory::withCount('parties')
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($w) use ($search) {
                    $w->where('name', 'like', "%$search%")
                      ->orWhere('description', 'like', "%$search%");
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.party.categories-component', [
            'items' => $items,
        ]);
    }
}
