<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Form;
use App\Models\FormSubmission as FormSubmissionModel;

class FormSubmissionsComponent extends Component
{
    use WithPagination;

    public $formId;
    public $form;

    public function mount($formId)
    {
        $this->formId = $formId;
        $this->form = Form::where('id', $formId)
            ->where('user_id', auth()->id())
            ->with('fields')
            ->firstOrFail();
    }

    public function deleteSubmission($submissionId)
    {
        $submission = FormSubmissionModel::where('id', $submissionId)
            ->where('form_id', $this->formId)
            ->firstOrFail();

        $submission->delete();

        session()->flash('success', 'Submission deleted successfully!');
    }

    public function render()
    {
        $submissions = FormSubmissionModel::where('form_id', $this->formId)
            ->latest()
            ->paginate(6);

        return view('livewire.forms.form-submissions-component', compact('submissions'))
            ->layout('layouts.app');
    }
}
