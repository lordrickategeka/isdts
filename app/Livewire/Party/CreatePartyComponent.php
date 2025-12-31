<?php

namespace App\Livewire\Party;

use App\Models\Party;
use App\Models\PartyAssociation;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreatePartyComponent extends Component
{
    public string $display_name = '';
    public string $status = 'active';
    public string $notes = '';
    public bool $showModal = false;
    public ?int $editingId = null;
    // association modal state
    public bool $showAssocModal = false;
    public ?int $assocPartyId = null; // the party to which we're adding association
    public ?int $assoc_related_party_id = null;
    public string $assoc_status = 'active';
    public array $assoc_types = [];
    public string $assoc_new_type = '';
    public array $partyOptions = [];
    public ?int $editingAssocId = null;
    public array $expandedParties = [];
    // delete confirmation modal state
    public bool $showDeleteConfirmModal = false;
    public ?int $partyToDeleteId = null;
    public array $partyToDeleteAssociations = [];
    // selected party for context-sensitive UI
    public ?int $selectedPartyId = null;
    public ?int $actionMenuFor = null;

    public function mount()
    {
        // no special mount actions required for the simplified form
    }

    protected function rules()
    {
        return [
            'display_name' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(['active','inactive','blacklisted'])],
            'notes' => ['nullable', 'string'],
        ];
    }

    protected function assocRules()
    {
        return [
            'assoc_related_party_id' => ['required','integer','exists:parties,id'],
            'assoc_status' => ['required', Rule::in(['active','inactive'])],
            'assoc_types' => ['nullable','array'],
            'assoc_types.*' => ['string','max:255'],
        ];
    }

    public function save()
    {
        $data = $this->validate();
        if ($this->editingId) {
            $party = Party::find($this->editingId);
            if ($party) {
                $party->update([
                    'display_name' => $data['display_name'],
                    'status' => $data['status'],
                    'notes' => $data['notes'] ?? null,
                ]);
            }
        } else {
            Party::create([
                'display_name' => $data['display_name'],
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);
        }

        // reset fields so the form is ready for a new entry and the table updates
        $this->display_name = '';
        $this->status = 'active';
        $this->notes = '';
        session()->flash('success', 'Party saved successfully.');
        // close modal after successful save
        $this->showModal = false;
        // clear editing state
        $this->editingId = null;
        // do not redirect — keep user on the form so submitted parties list refreshes
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->display_name = '';
        $this->status = 'active';
        $this->notes = '';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function edit(int $id)
    {
        $party = Party::find($id);
        if (! $party) return;

        $this->editingId = $party->id;
        $this->display_name = $party->display_name;
        $this->status = $party->status;
        $this->notes = $party->notes;
        $this->resetValidation();
        $this->showModal = true;
    }

    public function delete(int $id)
    {
        // show confirmation modal if there are associations
        $this->confirmDelete($id);
    }

    public function select(int $id)
    {
        $this->selectedPartyId = $id;
        // ensure assoc modal context is set to this party when adding types from the list
        $this->assocPartyId = $id;
        $this->resetValidation();
    }

    public function toggleActionMenu(int $id)
    {
        $this->actionMenuFor = $this->actionMenuFor === $id ? null : $id;
    }

    public function addAssocTypeFromList(string $type)
    {
        $t = trim($type);
        if ($t === '') return;
        if (! in_array($t, $this->assoc_types)) {
            $this->assoc_types[] = $t;
        }
        // ensure the assoc modal is pointing at the selected party
        if ($this->selectedPartyId) {
            $this->assocPartyId = $this->selectedPartyId;
            $this->assoc_related_party_id = $this->selectedPartyId;
        }
    }

    public function confirmDelete(int $id)
    {
        $party = Party::find($id);
        if (! $party) return;

        $assocs = PartyAssociation::where('party_id', $id)
            ->orWhere('related_party_id', $id)
            ->with('relatedParty')
            ->get();

        if ($assocs->count() === 0) {
            // nothing to confirm, perform delete immediately
            $party->delete();
            session()->flash('success', 'Party deleted successfully.');
            return;
        }

        // prepare simple array for the modal
        $rows = [];
        foreach ($assocs as $a) {
            $rows[] = [
                'id' => $a->id,
                'related_party_display' => $a->relatedParty ? ($a->relatedParty->display_name ?? ($a->relatedParty->first_name . ' ' . ($a->relatedParty->last_name ?? ''))) : '—',
                'types' => is_array($a->association_type) ? $a->association_type : ($a->association_type ? [$a->association_type] : []),
                'status' => $a->status,
            ];
        }

        $this->partyToDeleteId = $id;
        $this->partyToDeleteAssociations = $rows;
        $this->showDeleteConfirmModal = true;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmModal = false;
        $this->partyToDeleteId = null;
        $this->partyToDeleteAssociations = [];
    }

    public function performDelete()
    {
        if (! $this->partyToDeleteId) return;

        $id = $this->partyToDeleteId;
        $assocCount = PartyAssociation::where('party_id', $id)->orWhere('related_party_id', $id)->count();
        if ($assocCount) {
            PartyAssociation::where('party_id', $id)->orWhere('related_party_id', $id)->delete();
        }

        $party = Party::find($id);
        if ($party) {
            $party->delete();
        }

        $this->showDeleteConfirmModal = false;
        $this->partyToDeleteId = null;
        $this->partyToDeleteAssociations = [];

        if ($assocCount) {
            session()->flash('success', "Party deleted and {$assocCount} related association(s) removed.");
        } else {
            session()->flash('success', 'Party deleted successfully.');
        }
    }

    public function render()
    {
        $submittedParties = Party::with('associations')->orderBy('created_at', 'desc')->limit(12)->get();
        $this->partyOptions = Party::orderBy('display_name')->get(['id','display_name'])->toArray();

        // build a list of association types and counts for the right-side table
        $counts = [];
        if ($this->selectedPartyId) {
            $assocs = PartyAssociation::where('party_id', $this->selectedPartyId)
                ->orWhere('related_party_id', $this->selectedPartyId)
                ->get();
        } else {
            $assocs = collect();
        }

        foreach ($assocs as $a) {
            $entry = $a->association_type;
            if (is_array($entry)) {
                foreach ($entry as $t) {
                    $t = trim((string) $t);
                    if ($t === '') continue;
                    $counts[$t] = ($counts[$t] ?? 0) + 1;
                }
            } else {
                $t = trim((string) $entry);
                if ($t === '') continue;
                $counts[$t] = ($counts[$t] ?? 0) + 1;
            }
        }

        $associationTypes = collect($counts)->map(function ($count, $type) {
            return ['type' => $type, 'count' => $count];
        })->sortByDesc('count')->values()->all();

        // compute unique association-type counts per displayed party
        $assocTypeSets = [];
        $partyIds = $submittedParties->pluck('id')->all();
        if (!empty($partyIds)) {
            $relatedAssocs = PartyAssociation::whereIn('party_id', $partyIds)
                ->orWhereIn('related_party_id', $partyIds)
                ->get();

            foreach ($relatedAssocs as $a) {
                $types = $a->association_type;
                $typesArr = [];
                if (is_array($types)) {
                    $typesArr = array_map('trim', $types);
                } elseif ($types) {
                    $typesArr = [trim((string)$types)];
                }

                $owner = $a->party_id;
                $related = $a->related_party_id;

                foreach ([$owner, $related] as $pid) {
                    if (!in_array($pid, $partyIds)) continue;
                    if (!isset($assocTypeSets[$pid])) $assocTypeSets[$pid] = [];
                    foreach ($typesArr as $t) {
                        if ($t === '') continue;
                        $assocTypeSets[$pid][$t] = true;
                    }
                }
            }
        }

        $assocTypeCounts = [];
        foreach ($assocTypeSets as $pid => $set) {
            $assocTypeCounts[$pid] = count($set);
        }

        return view('livewire.party.create-party-component', compact('submittedParties', 'associationTypes', 'assocTypeCounts'));
    }

    // association modal methods
    public function openAssocModal(int $partyId, ?int $assocId = null)
    {
        $this->resetValidation();
        $this->assocPartyId = $partyId;
        $this->editingAssocId = null;

        if ($assocId) {
            $assoc = PartyAssociation::find($assocId);
            if ($assoc) {
                $this->editingAssocId = $assoc->id;
                $this->assoc_related_party_id = $assoc->related_party_id;
                $this->assoc_types = is_array($assoc->association_type) ? $assoc->association_type : ($assoc->association_type ? [$assoc->association_type] : []);
                $this->assoc_status = $assoc->status ?? 'active';
            }
        } else {
            // default related party to the clicked party
            $this->assoc_related_party_id = $partyId;
            $this->assoc_types = [];
            $this->assoc_new_type = '';
            $this->assoc_status = 'active';
        }

        $this->showAssocModal = true;
    }

    public function closeAssocModal()
    {
        $this->showAssocModal = false;
    }

    public function addAssocType()
    {
        $type = trim($this->assoc_new_type);
        if ($type === '') return;
        $this->assoc_types[] = $type;
        $this->assoc_new_type = '';
        $this->resetValidation('assoc_types');
    }

    public function removeAssocType(int $index)
    {
        if (! isset($this->assoc_types[$index])) return;
        array_splice($this->assoc_types, $index, 1);
    }

    public function createAssociation()
    {
        // if user has typed a new type but not clicked "Add" push it in
        if (!empty(trim($this->assoc_new_type))) {
            $this->assoc_types[] = trim($this->assoc_new_type);
            $this->assoc_new_type = '';
        }

        $data = $this->validate($this->assocRules());

        // normalize types: trim, remove empty, unique, sort
        $types = array_values(array_filter(array_unique(array_map('trim', $this->assoc_types ?? [])), fn($v) => $v !== ''));
        sort($types);

        // check for duplicates: any existing association with same party_id, related_party_id and same types (or both null)
        $existing = PartyAssociation::where('party_id', $this->assocPartyId)
            ->where('related_party_id', $this->assoc_related_party_id)
            ->get();

        foreach ($existing as $e) {
            $existingTypes = $e->association_type;
            if (is_null($existingTypes) && empty($types)) {
                $this->addError('assoc_types', 'An identical association already exists.');
                return;
            }

            if (is_array($existingTypes)) {
                $et = array_values(array_filter(array_unique(array_map('trim', $existingTypes)), fn($v) => $v !== ''));
                sort($et);
                $ct = $types;
                sort($ct);
                if ($et === $ct) {
                    $this->addError('assoc_types', 'An identical association already exists.');
                    return;
                }
            }
        }

        if ($this->editingAssocId) {
            $assoc = PartyAssociation::find($this->editingAssocId);
            if ($assoc) {
                $assoc->update([
                    'related_party_id' => $this->assoc_related_party_id,
                    'association_type' => $types ?: null,
                    'status' => $this->assoc_status,
                ]);
                session()->flash('success', 'Association updated successfully.');
            }
        } else {
            // save association as JSON array or null
            PartyAssociation::create([
                'party_id' => $this->assocPartyId,
                'related_party_id' => $this->assoc_related_party_id,
                'association_type' => $types ?: null,
                'status' => $this->assoc_status,
            ]);
            session()->flash('success', 'Association created successfully.');
        }

        $this->showAssocModal = false;
        $this->editingAssocId = null;
    }

    public function deleteAssociation(int $assocId)
    {
        $assoc = PartyAssociation::find($assocId);
        if ($assoc) {
            $assoc->delete();
            session()->flash('success', 'Association deleted.');
        }
    }

    public function togglePartyAssociations(int $partyId)
    {
        if (in_array($partyId, $this->expandedParties)) {
            $this->expandedParties = array_values(array_diff($this->expandedParties, [$partyId]));
        } else {
            $this->expandedParties[] = $partyId;
        }
    }
}
