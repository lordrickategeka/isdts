<?php

namespace App\Livewire\Party;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Party;
use App\Models\PartyCategory;
use Illuminate\Support\Facades\DB;

class PartyComponent extends Component
{
    use WithPagination;
    public $query = '';
    public $filterType = '';
    public $filterCategory = '';
    public $selected = null;
    public $activeTab = 0;
    public $perPage = 10;
    public bool $confirmingDelete = false;
    public array $tags = [
        ['id' => 1, 'name' => 'VIP'],
        ['id' => 2, 'name' => 'Contractor'],
        ['id' => 3, 'name' => 'Hot Lead'],
    ];

    public array $categories = [];

    public function getFilteredProperty()
    {
        $q = strtolower(trim($this->query));
        $query = Party::with(['category', 'contacts'])
            ->when($this->filterType, function ($q2) {
                $q2->where('party_type', $this->filterType);
            })
            ->when($this->filterCategory, function ($q2) {
                // filter by category name match
                $q2->whereHas('category', function ($c) {
                    $c->where('name', $this->filterCategory);
                });
            })
            ->when($q !== '', function ($q2) use ($q) {
                $q2->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%$q%")
                      ->orWhere('first_name', 'like', "%$q%")
                      ->orWhere('last_name', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%")
                      ->orWhere('phone', 'like', "%$q%");
                });
            })
            ->orderBy('id', 'desc');

        return $query->paginate($this->perPage);
    }

    public function setQuick($key)
    {
        if ($key === 'leads') $this->filterCategory = 'Lead';
        elseif ($key === 'customers') $this->filterCategory = 'Customer';
        elseif ($key === 'blacklisted') $this->filterCategory = 'Blacklisted';
        else $this->filterCategory = '';
    }

    public function select($id)
    {
        $this->selected = Party::with(['category', 'addresses', 'contacts', 'tags'])->find($id);
        $this->activeTab = 0;
        $this->confirmingDelete = false;
    }

    public function setTab($index)
    {
        $this->activeTab = $index;
    }

    public function toggleTagOnSelected($name)
    {
        if (!$this->selected) return;
        // Toggle tag by name using pivot table
        $tag = \App\Models\Tag::firstOrCreate(['name' => $name]);
        if ($this->selected->tags->contains($tag->id)) {
            $this->selected->tags()->detach($tag->id);
            $this->selected->load('tags');
        } else {
            $this->selected->tags()->attach($tag->id);
            $this->selected->load('tags');
        }
    }

    public function confirmDeleteSelected()
    {
        if (!$this->selected) return;
        $this->confirmingDelete = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDelete = false;
    }

    public function deleteSelected()
    {
        if (!$this->selected) return;

        $party = Party::with(['addresses', 'contacts', 'tags'])->find($this->selected->id);
        if (!$party) {
            $this->confirmingDelete = false;
            $this->selected = null;
            return;
        }

        try {
            DB::transaction(function () use ($party) {
                $party->tags()->detach();
                $party->addresses()->delete();
                $party->contacts()->delete();
                $party->delete();
            });
            session()->flash('success', 'Party deleted successfully.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Failed to delete party.');
            $this->confirmingDelete = false;
            return;
        }

        $this->confirmingDelete = false;
        $this->selected = null;
        $this->resetPage();
    }

    public function render()
    {
        $this->categories = PartyCategory::orderBy('name')->get()->map(function ($c) {
            return ['id' => $c->id, 'name' => $c->name];
        })->all();

        $parties = $this->filtered; // calls accessor getFilteredProperty

        return view('livewire.party.party-component', [
            'parties' => $parties,
        ]);
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterCategory()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }
}
