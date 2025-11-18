<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Support\Str;

class FormBuilderComponent extends Component
{
     public $formId;
    public $name = '';
    public $description = '';
    public $status = 'draft';
    public $submitButtonText = 'Submit';
    public $successMessage = 'Form submitted successfully!';

    public $fields = [];
    public $fieldTypes = [
        'text' => 'Text',
        'email' => 'Email',
        'number' => 'Number',
        'tel' => 'Phone',
        'url' => 'URL',
        'textarea' => 'Textarea',
        'select' => 'Select Dropdown',
        'radio' => 'Radio Buttons',
        'checkbox' => 'Checkboxes',
        'file' => 'File Upload',
        'date' => 'Date',
    ];

    public function mount($formId = null)
    {
        if ($formId) {
            $form = Form::with('fields')->findOrFail($formId);

            // Check if user owns this form
            if ($form->user_id !== auth()->id()) {
                abort(403);
            }

            $this->formId = $form->id;
            $this->name = $form->name;
            $this->description = $form->description;
            $this->status = $form->status;
            $this->submitButtonText = $form->settings['submit_button_text'] ?? 'Submit';
            $this->successMessage = $form->settings['success_message'] ?? 'Form submitted successfully!';

            foreach ($form->fields as $field) {
                $this->fields[] = [
                    'id' => $field->id,
                    'field_type' => $field->field_type,
                    'name' => $field->name,
                    'label' => $field->label,
                    'placeholder' => $field->placeholder,
                    'is_required' => $field->is_required,
                    'help_text' => $field->help_text,
                    'options' => $field->options ?? [],
                    'validation_rules' => $field->validation_rules ?? [],
                    'settings' => $field->settings ?? [],
                ];
            }
        }
    }

    public function addField()
    {
        $this->fields[] = [
            'id' => null,
            'field_type' => 'text',
            'name' => '',
            'label' => '',
            'placeholder' => '',
            'is_required' => false,
            'help_text' => '',
            'options' => [],
            'validation_rules' => [],
            'settings' => [],
        ];
    }

    public function removeField($index)
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function moveFieldUp($index)
    {
        if ($index > 0) {
            $temp = $this->fields[$index];
            $this->fields[$index] = $this->fields[$index - 1];
            $this->fields[$index - 1] = $temp;
        }
    }

    public function moveFieldDown($index)
    {
        if ($index < count($this->fields) - 1) {
            $temp = $this->fields[$index];
            $this->fields[$index] = $this->fields[$index + 1];
            $this->fields[$index + 1] = $temp;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'fields.*.field_type' => 'required',
            'fields.*.name' => 'required|string',
            'fields.*.label' => 'required|string',
        ]);

        $form = Form::updateOrCreate(
            ['id' => $this->formId],
            [
                'user_id' => auth()->id(),
                'name' => $this->name,
                'description' => $this->description,
                'status' => $this->status,
                'settings' => [
                    'submit_button_text' => $this->submitButtonText,
                    'success_message' => $this->successMessage,
                ],
            ]
        );

        // Delete existing fields and recreate
        if ($this->formId) {
            $form->fields()->delete();
        }

        foreach ($this->fields as $index => $field) {
            // Generate field name if empty
            if (empty($field['name'])) {
                $field['name'] = Str::slug($field['label'], '_');
            }

            FormField::create([
                'form_id' => $form->id,
                'field_type' => $field['field_type'],
                'name' => $field['name'],
                'label' => $field['label'],
                'placeholder' => $field['placeholder'] ?? null,
                'is_required' => $field['is_required'] ?? false,
                'help_text' => $field['help_text'] ?? null,
                'options' => $field['options'] ?? null,
                'validation_rules' => $field['validation_rules'] ?? null,
                'settings' => $field['settings'] ?? null,
                'order' => $index,
            ]);
        }

        session()->flash('success', 'Form saved successfully!');

        return redirect()->route('forms.index');
    }

    public function render()
    {
        return view('livewire.forms.form-builder-component')
            ->layout('layouts.app');
    }
}
