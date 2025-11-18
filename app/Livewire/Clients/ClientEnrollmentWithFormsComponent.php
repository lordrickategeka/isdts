<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use App\Models\Form;
use App\Models\FormSubmission as FormSubmissionModel;

class ClientEnrollmentWithFormsComponent extends Component
{
    // Step management
    public $currentStep = 1;
    public $totalSteps = 3;

    // Store form IDs for each step
    public $step1FormId;
    public $step2FormId;
    public $step3FormId;

    // Store forms
    public $step1Form;
    public $step2Form;
    public $step3Form;

    // Store form data
    public $formData = [];

    public function mount($step1FormId = null, $step2FormId = null, $step3FormId = null)
    {
        // You can pass form IDs or use default forms
        $this->step1FormId = $step1FormId;
        $this->step2FormId = $step2FormId;
        $this->step3FormId = $step3FormId;

        // Load the forms
        if ($this->step1FormId) {
            $this->step1Form = Form::with('fields')->find($this->step1FormId);
        }
        if ($this->step2FormId) {
            $this->step2Form = Form::with('fields')->find($this->step2FormId);
        }
        if ($this->step3FormId) {
            $this->step3Form = Form::with('fields')->find($this->step3FormId);
        }

        // Initialize form data for all forms
        $this->initializeFormData($this->step1Form);
        $this->initializeFormData($this->step2Form);
        $this->initializeFormData($this->step3Form);
    }

    private function initializeFormData($form)
    {
        if (!$form) return;

        foreach ($form->fields as $field) {
            if ($field->field_type === 'checkbox') {
                $this->formData[$field->name] = [];
            } else {
                $this->formData[$field->name] = '';
            }
        }
    }

    public function nextStep()
    {
        $this->validateCurrentStep();

        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function validateCurrentStep()
    {
        $currentForm = $this->getCurrentForm();
        if (!$currentForm) return;

        $rules = [];
        foreach ($currentForm->fields as $field) {
            if (!$field->is_required) continue;

            $fieldRules = ['required'];

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
            }

            $rules["formData.{$field->name}"] = implode('|', $fieldRules);
        }

        $this->validate($rules);
    }

    private function getCurrentForm()
    {
        return match($this->currentStep) {
            1 => $this->step1Form,
            2 => $this->step2Form,
            3 => $this->step3Form,
            default => null
        };
    }

    public function submit()
    {
        // Validate all steps
        for ($i = 1; $i <= $this->totalSteps; $i++) {
            $this->currentStep = $i;
            $this->validateCurrentStep();
        }

        // Save submissions for each form
        if ($this->step1Form) {
            $this->saveFormSubmission($this->step1Form);
        }
        if ($this->step2Form) {
            $this->saveFormSubmission($this->step2Form);
        }
        if ($this->step3Form) {
            $this->saveFormSubmission($this->step3Form);
        }

        session()->flash('success', 'Client enrollment completed successfully!');

        return redirect()->route('dashboard');
    }

    private function saveFormSubmission($form)
    {
        $formFieldNames = $form->fields->pluck('name')->toArray();
        $formSpecificData = array_intersect_key($this->formData, array_flip($formFieldNames));

        FormSubmissionModel::create([
            'form_id' => $form->id,
            'data' => $formSpecificData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        return view('livewire.clients.client-enrollment-with-forms-component')
            ->layout('layouts.app');
    }
}
