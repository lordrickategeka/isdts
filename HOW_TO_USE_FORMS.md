# Using Custom Forms in Your Application

This guide shows you different ways to use the custom form generator in your application, including embedding forms dynamically and creating step-by-step wizards.

## Table of Contents
1. [Step-by-Step Form Example (Client Enrollment)](#step-by-step-form-example)
2. [Embedding Custom Forms Dynamically](#embedding-custom-forms-dynamically)
3. [Creating Your Own Forms](#creating-your-own-forms)

---

## Step-by-Step Form Example (Client Enrollment)

### What Was Created
A complete client enrollment system with a 3-step wizard:
- **Step 1**: Basic Information (name, email, phone)
- **Step 2**: Address Information (street, city, state, zip, country)
- **Step 3**: Additional Information (company, notes)

### Files Created:
1. **Component**: `app/Livewire/Clients/ClientEnrollmentComponent.php`
2. **View**: `resources/views/livewire/clients/client-enrollment-component.blade.php`
3. **Model**: `app/Models/Client.php`
4. **Migration**: `database/migrations/XXXX_create_clients_table.php`

### Setup Steps:

1. **Run the migration**:
```bash
php artisan migrate
```

2. **Access the enrollment page**:
Visit: `http://yourapp.test/clients/enroll`

### Features:
- ✅ Step-by-step validation (validates each step before moving forward)
- ✅ Progress indicator showing current step
- ✅ Previous/Next navigation
- ✅ Responsive design matching your app's layout
- ✅ Mobile-friendly with drawer menu

### How to Customize:

**Add more steps:**
```php
// In ClientEnrollmentComponent.php
public $totalSteps = 4; // Change from 3 to 4

// Add your new step fields
public $new_field = '';

// Add validation
protected $rules = [
    // ... existing rules
    'new_field' => 'required|string',
];

// Add step validation
public function validateCurrentStep()
{
    // ... existing validations
    elseif ($this->currentStep == 4) {
        $this->validate(['new_field' => 'required|string']);
    }
}
```

**Modify the view** to add your new step section:
```blade
@if ($currentStep == 4)
    <div class="space-y-6">
        <h2>Your New Step Title</h2>
        <!-- Your fields here -->
    </div>
@endif
```

---

## Embedding Custom Forms Dynamically

You can embed forms created in the Form Builder into any page dynamically.

### Method 1: Embed Form by ID

Create a reusable component to render any form:

**1. Create EmbeddedFormComponent.php:**
```php
<?php

namespace App\Livewire\Forms;

use Livewire\Component;
use App\Models\Form;

class EmbeddedFormComponent extends Component
{
    public $formId;
    public $form;
    public $formData = [];
    public $submitted = false;

    public function mount($formId)
    {
        $this->formId = $formId;
        $this->form = Form::with('fields')->findOrFail($formId);

        foreach ($this->form->fields as $field) {
            $this->formData[$field->name] = $field->field_type === 'checkbox' ? [] : '';
        }
    }

    public function submit()
    {
        // Your validation and submission logic
        // Copy from FormSubmissionComponent.php
        
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.forms.embedded-form-component');
    }
}
```

**2. Use it anywhere:**
```blade
<!-- In any Blade view -->
<div class="my-custom-page">
    <h1>Register for Event</h1>
    
    <!-- Embed the form (use the form ID from your forms list) -->
    @livewire('forms.embedded-form-component', ['formId' => 1])
</div>
```

### Method 2: Embed by Slug

Use the form's slug instead of ID:

```php
// In your component
$this->form = Form::where('slug', $slug)->where('status', 'active')->firstOrFail();
```

```blade
@livewire('forms.embedded-form-component', ['slug' => 'event-registration'])
```

### Method 3: Inline Form in Livewire Component

Directly integrate form fields into your existing component:

```php
// In your existing Livewire component
public function mount()
{
    $registrationForm = Form::where('slug', 'event-registration')->with('fields')->first();
    
    // Dynamically create properties for each field
    foreach ($registrationForm->fields as $field) {
        $this->formData[$field->name] = '';
    }
}
```

```blade
<!-- In your view -->
@foreach($registrationForm->fields as $field)
    <div class="mb-4">
        <label>{{ $field->label }}</label>
        <input type="{{ $field->field_type }}" 
               wire:model="formData.{{ $field->name }}"
               placeholder="{{ $field->placeholder }}">
    </div>
@endforeach
```

---

## Creating Your Own Forms

### Example 1: Simple Contact Form

Create directly in your component without the form builder:

```php
<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class ContactFormComponent extends Component
{
    public $name = '';
    public $email = '';
    public $message = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();
        
        // Handle submission (email, database, etc.)
        
        session()->flash('success', 'Message sent!');
        $this->reset();
    }

    public function render()
    {
        return view('livewire.pages.contact-form-component')
            ->layout('layouts.app');
    }
}
```

### Example 2: Multi-Step Application Form

```php
public $currentStep = 1;
public $steps = ['Personal', 'Education', 'Experience', 'Review'];

public function nextStep()
{
    $this->validateStep();
    $this->currentStep++;
}

public function previousStep()
{
    $this->currentStep--;
}

private function validateStep()
{
    match($this->currentStep) {
        1 => $this->validate(['name' => 'required', 'email' => 'required|email']),
        2 => $this->validate(['school' => 'required', 'degree' => 'required']),
        3 => $this->validate(['company' => 'required', 'position' => 'required']),
        default => null
    };
}
```

### Example 3: Form with File Upload

```php
use Livewire\WithFileUploads;

class ApplicationForm extends Component
{
    use WithFileUploads;

    public $resume;
    public $cover_letter;

    protected $rules = [
        'resume' => 'required|file|mimes:pdf,doc,docx|max:2048',
        'cover_letter' => 'required|file|mimes:pdf,doc,docx|max:2048',
    ];

    public function submit()
    {
        $this->validate();

        $resumePath = $this->resume->store('resumes', 'public');
        $coverLetterPath = $this->cover_letter->store('cover-letters', 'public');

        // Save to database...
    }
}
```

---

## Best Practices

### 1. **Separate Concerns**
- Use Form Builder for: Client-facing forms, surveys, public submissions
- Use Custom Components for: Complex workflows, multi-step processes, admin forms

### 2. **Validation**
Always validate on both steps (step validation) and final submit:
```php
public function validateCurrentStep()
{
    // Validate only current step fields
}

public function submit()
{
    $this->validate(); // Validate everything before final submit
}
```

### 3. **User Feedback**
```php
// Success messages
session()->flash('success', 'Form submitted successfully!');

// Error handling
try {
    // Submit logic
} catch (\Exception $e) {
    session()->flash('error', 'Something went wrong: ' . $e->getMessage());
}
```

### 4. **Reusable Components**
Create trait for common form functionality:
```php
trait HasFormWizard
{
    public $currentStep = 1;
    
    public function nextStep() { /* ... */ }
    public function previousStep() { /* ... */ }
    public function goToStep($step) { /* ... */ }
}
```

---

## Quick Reference

### Routes to Add:
```php
// In routes/web.php
Route::middleware(['auth'])->group(function () {
    // Your form route
    Route::get('/clients/enroll', ClientEnrollmentComponent::class)->name('clients.enroll');
});
```

### Common Patterns:

**Progress Indicator:**
```blade
<div class="flex justify-between mb-8">
    @foreach($steps as $index => $step)
        <div class="{{ $currentStep >= $index + 1 ? 'text-blue-600' : 'text-gray-400' }}">
            {{ $step }}
        </div>
    @endforeach
</div>
```

**Step Navigation:**
```blade
<div class="flex justify-between">
    <button wire:click="previousStep" @if($currentStep == 1) disabled @endif>
        Previous
    </button>
    <button wire:click="{{ $currentStep == $totalSteps ? 'submit' : 'nextStep' }}">
        {{ $currentStep == $totalSteps ? 'Submit' : 'Next' }}
    </button>
</div>
```

---

## Need Help?

- Check the Form Builder documentation: `FORM_GENERATOR.md`
- Review existing components in `app/Livewire/Forms/`
- Laravel Livewire docs: https://livewire.laravel.com
