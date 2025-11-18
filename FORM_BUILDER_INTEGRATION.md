# Quick Guide: Using Form Builder Forms on Any Page

## Option 1: Embed a Single Form (Simple)

Use the `EmbeddedFormComponent` to add any form created in the Form Builder to any page.

### Step 1: Create Your Form
1. Go to `/forms` and create a new form
2. Add fields you need
3. Set status to "Active"
4. Note the form ID (you'll see it in the URL when editing)

### Step 2: Embed the Form
In any Blade view, add:

```blade
<div class="container mx-auto p-6">
    <h1>Client Registration</h1>
    
    <!-- Embed form with ID 1 -->
    @livewire('forms.embedded-form-component', [
        'formId' => 1,
        'redirectRoute' => 'dashboard',
        'successMessage' => 'Thank you for registering!'
    ])
</div>
```

**Parameters:**
- `formId` (required): The ID of your form
- `redirectRoute` (optional): Where to redirect after submission
- `successMessage` (optional): Custom success message

---

## Option 2: Multi-Step Wizard with Form Builder Forms

Use `ClientEnrollmentWithFormsComponent` to create a step-by-step wizard using forms from the Form Builder.

### Setup:

**1. Create 3 Forms in Form Builder** (`/forms`):
- Form 1: "Client Basic Info" (name, email, phone)
- Form 2: "Client Address" (address, city, state, zip)
- Form 3: "Additional Info" (company, notes)

**2. Note the Form IDs** (e.g., 1, 2, 3)

**3. Access the Wizard:**
Visit: `/clients/enroll-wizard?step1FormId=1&step2FormId=2&step3FormId=3`

Or update the route to set default form IDs:

```php
// In routes/web.php
Route::get('/clients/enroll-wizard', function() {
    return app(ClientEnrollmentWithFormsComponent::class, [
        'step1FormId' => 1,  // Your form IDs
        'step2FormId' => 2,
        'step3FormId' => 3
    ]);
})->name('clients.enroll.wizard');
```

---

## Option 3: Embed in Your Own Component

You can also embed forms directly in your custom Livewire components:

```php
<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Form;

class MyCustomPage extends Component
{
    public $registrationForm;
    public $formData = [];

    public function mount()
    {
        // Load form by ID or slug
        $this->registrationForm = Form::with('fields')->find(1);
        
        // Initialize form data
        foreach ($this->registrationForm->fields as $field) {
            $this->formData[$field->name] = '';
        }
    }

    public function submit()
    {
        // Your custom logic here
        // Save to your own table, send emails, etc.
    }

    public function render()
    {
        return view('livewire.pages.my-custom-page')
            ->layout('layouts.app');
    }
}
```

Then in your view:

```blade
<form wire:submit.prevent="submit">
    @foreach($registrationForm->fields as $field)
        <div class="mb-4">
            <label>{{ $field->label }}</label>
            <input type="{{ $field->field_type }}" 
                   wire:model="formData.{{ $field->name }}"
                   class="w-full px-4 py-2 border rounded">
        </div>
    @endforeach
    
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
        Submit
    </button>
</form>
```

---

## Quick Examples

### Example 1: Simple Event Registration
```blade
<!-- In your event page -->
<div class="event-details">
    <h1>Annual Conference 2025</h1>
    <p>Register below...</p>
    
    @livewire('forms.embedded-form-component', [
        'formId' => 5, // Event registration form
        'redirectRoute' => 'events.thank-you'
    ])
</div>
```

### Example 2: Contact Us Page
```blade
<!-- resources/views/contact.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1>Contact Us</h1>
    
    @livewire('forms.embedded-form-component', [
        'formId' => 3, // Contact form
        'successMessage' => 'Thanks! We will get back to you soon.'
    ])
</div>
@endsection
```

### Example 3: Job Application with Steps
1. Create 4 forms in Form Builder:
   - Personal Information
   - Work Experience
   - Education
   - Upload Documents

2. Use the wizard component with your form IDs

---

## Tips & Best Practices

### 1. Form Organization
- Use clear, descriptive names for forms
- Add descriptions to help users
- Group related fields together

### 2. Field Naming
- Use consistent naming (e.g., `first_name`, `email`)
- Avoid special characters in field names
- Use underscores for multi-word names

### 3. Validation
Forms created in Form Builder automatically handle validation based on:
- Field type (email, number, etc.)
- Required fields
- Custom rules you set

### 4. Submissions
All submissions are saved to `form_submissions` table with:
- Form ID
- Field data (JSON)
- IP address
- User agent
- Timestamp

View submissions at: `/forms/{formId}/submissions`

---

## Need Custom Behavior?

If you need special logic (save to specific table, send emails, etc.):

1. Copy `EmbeddedFormComponent.php`
2. Rename to your needs (e.g., `EventRegistrationComponent`)
3. Customize the `submit()` method
4. Use your custom component instead

---

## Available Components

| Component | Use Case | Route |
|-----------|----------|-------|
| `EmbeddedFormComponent` | Single form anywhere | N/A (use @livewire) |
| `ClientEnrollmentComponent` | Hardcoded 3-step wizard | `/clients/enroll` |
| `ClientEnrollmentWithFormsComponent` | Form Builder 3-step wizard | `/clients/enroll-wizard` |
| `FormSubmissionComponent` | Public form page | `/form/{slug}` |

---

## Troubleshooting

**Q: Form not showing?**
- Check form ID is correct
- Make sure form status is "Active"
- Check browser console for errors

**Q: Submissions not saving?**
- Check form permissions
- Verify database connection
- Check Laravel logs: `storage/logs/laravel.log`

**Q: Validation not working?**
- Ensure field types are correct
- Check "Required" checkbox in Form Builder
- Verify field names match

---

For more details, see:
- `FORM_GENERATOR.md` - Complete form builder documentation
- `HOW_TO_USE_FORMS.md` - General forms usage guide
