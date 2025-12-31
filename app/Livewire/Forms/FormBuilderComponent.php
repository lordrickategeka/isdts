<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\Form;
use App\Models\FormField;
use Illuminate\Support\Str;

class FormBuilderComponent extends Component
{

    public function addOption($index)
    {
        $options = $this->fields[$index]['options'] ?? '';
        $options = trim($options);
        if ($options === '') {
            $options = 'Option';
        } else {
            $options .= "\nOption";
        }
        $this->fields[$index]['options'] = $options;
    }
    public $formId;
    public $name = '';
    public $description = '';
    public $status = 'draft';
    public $submitButtonText = 'Submit';
    public $successMessage = 'Form submitted successfully!';

    public $showFieldModal = false;

    public $fields = [];
    public $fieldTypes = [
        'text' => 'Text',
        'email' => 'Email',
        'number' => 'Number',
        'tel' => 'Phone',
        'url' => 'URL',
        'textarea' => 'Textarea',
        'select' => 'Select',
        'radio' => 'Radio',
        'checkbox' => 'Checkbox',
        'file' => 'File Upload',
        'date' => 'Date',
    ];

    public function updatedFields($value, $name)
    {
        $parts = explode('.', $name);

        if ($parts[1] === 'field_type') {
            $index = $parts[0];

            // Ensure Livewire mutates the actual public property
            $this->fields[$index]['field_type'] = $value;

            if (in_array($value, ['select', 'radio', 'checkbox'])) {
                $this->fields[$index]['options'] = '';
            } else {
                unset($this->fields[$index]['options']);
            }
        }
    }

    public $newField = [
        'field_type' => '',
        'label' => '',
        'name' => '',
        'placeholder' => '',
        'help_text' => '',
        'options' => '',
        'is_required' => false,
    ];

    public function saveNewField()
    {
        $this->validate([
            'newField.field_type' => 'required',
            'newField.label' => 'required|string',
            'newField.name' => 'required|string',
        ]);

        $this->fields[] = $this->newField;

        // Reset modal
        $this->newField = [
            'field_type' => '',
            'label' => '',
            'name' => '',
            'placeholder' => '',
            'help_text' => '',
            'options' => '',
            'is_required' => false,
        ];

        $this->showFieldModal = false;
    }

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
                $options = '';
                if (in_array($field->field_type, ['select', 'radio', 'checkbox'])) {
                    if (is_array($field->options)) {
                        $options = implode("\n", $field->options);
                    } elseif (is_string($field->options)) {
                        $options = $field->options;
                    }
                }
                $this->fields[] = [
                    'id' => $field->id,
                    'field_type' => $field->field_type,
                    'name' => $field->name,
                    'label' => $field->label,
                    'placeholder' => $field->placeholder,
                    'is_required' => $field->is_required,
                    'help_text' => $field->help_text,
                    'options' => $options,
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
            'options' => '', // Always a string for textarea
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

            // Convert options textarea to array for select/radio/checkbox
            $options = null;
            if (in_array($field['field_type'], ['select', 'radio', 'checkbox'])) {
                if (is_array($field['options'])) {
                    $options = array_filter(array_map('trim', $field['options']));
                } elseif (is_string($field['options'])) {
                    $options = array_filter(array_map('trim', preg_split('/\r?\n/', $field['options'])));
                }
            }

            FormField::create([
                'form_id' => $form->id,
                'field_type' => $field['field_type'],
                'name' => $field['name'],
                'label' => $field['label'],
                'placeholder' => $field['placeholder'] ?? null,
                'is_required' => $field['is_required'] ?? false,
                'help_text' => $field['help_text'] ?? null,
                'options' => $options,
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
