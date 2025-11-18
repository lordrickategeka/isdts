<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Form;

class FormsIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function deleteForm($formId)
    {
        $form = Form::where('id', $formId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $form->delete();

        session()->flash('success', 'Form deleted successfully!');
    }

    public function duplicateForm($formId)
    {
        $form = Form::with('fields')->where('id', $formId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $newForm = $form->replicate();
        $newForm->name = $form->name . ' (Copy)';
        $newForm->slug = $form->slug . '-copy-' . time();
        $newForm->status = 'draft';
        $newForm->save();

        foreach ($form->fields as $field) {
            $newField = $field->replicate();
            $newField->form_id = $newForm->id;
            $newField->save();
        }

        session()->flash('success', 'Form duplicated successfully!');

        return redirect()->route('forms.edit', ['formId' => $newForm->id]);
    }

    public function render()
    {
        $query = Form::where('user_id', auth()->id());

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        $forms = $query->withCount('submissions')
            ->latest()
            ->paginate(10);

        return view('livewire.forms.forms-index', compact('forms'))
            ->layout('layouts.app');
    }
}
