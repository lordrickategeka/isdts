<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Form;
use App\Models\FormSubmission as FormSubmissionModel;

class EmbeddedFormComponent extends Component
{
    use WithFileUploads;

    public $formId;
    public $form;
    public $formData = [];
    public $submitted = false;
    public $redirectRoute = null;
    public $successMessage = null;

    public function mount($formId, $redirectRoute = null, $successMessage = null)
    {
        $this->formId = $formId;
        $this->form = Form::with('fields')->findOrFail($formId);
        $this->redirectRoute = $redirectRoute;
        $this->successMessage = $successMessage;

        // Initialize form data
        foreach ($this->form->fields as $field) {
            if ($field->field_type === 'checkbox') {
                $this->formData[$field->name] = [];
            } else {
                $this->formData[$field->name] = '';
            }
        }
    }

    public function submit()
    {
        // Build validation rules
        $rules = [];
        foreach ($this->form->fields as $field) {
            $fieldRules = [];

            if ($field->is_required) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            switch ($field->field_type) {
                case 'email':
                    $fieldRules[] = 'email';
                    break;
                case 'number':
                    $fieldRules[] = 'numeric';
                    break;
                case 'url':
                    $fieldRules[] = 'url';
                    break;
                case 'file':
                    $fieldRules[] = 'file';
                    $fieldRules[] = 'max:10240'; // 10MB max
                    break;
                case 'date':
                    $fieldRules[] = 'date';
                    break;
            }

            $rules["formData.{$field->name}"] = implode('|', $fieldRules);
        }

        $this->validate($rules);

        // Handle file uploads
        $processedData = [];
        foreach ($this->formData as $key => $value) {
            $field = $this->form->fields->firstWhere('name', $key);

            if ($field && $field->field_type === 'file' && $value) {
                $path = $value->store('form-uploads', 'public');
                $processedData[$key] = $path;
            } else {
                $processedData[$key] = $value;
            }
        }

        // Save submission
        FormSubmissionModel::create([
            'form_id' => $this->form->id,
            'data' => $processedData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $message = $this->successMessage ?? $this->form->settings['success_message'] ?? 'Form submitted successfully!';
        session()->flash('success', $message);

        if ($this->redirectRoute) {
            return redirect()->route($this->redirectRoute);
        }

        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.forms.embedded-form-component');
    }
}
