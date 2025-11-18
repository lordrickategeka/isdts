# Custom Form Generator

A complete form builder system for Laravel with dynamic form creation, field management, and submission handling.

## Features

✅ **Form Management**
- Create, edit, duplicate, and delete forms
- Draft, active, and inactive status
- Unique URL slug for each form
- Custom success messages and submit button text

✅ **Field Types Supported**
- Text input
- Email
- Number
- Phone (tel)
- URL
- Textarea
- Select dropdown
- Radio buttons
- Checkboxes
- File upload
- Date picker

✅ **Field Customization**
- Required/optional fields
- Placeholder text
- Help text
- Field ordering (move up/down)
- Custom validation rules
- Field-specific options for select/radio/checkbox

✅ **Submission Management**
- View all form submissions
- Tabular display with all field values
- Delete submissions
- IP address and user agent tracking
- File upload support

## Database Structure

### Tables Created
1. **forms** - Stores form definitions
2. **form_fields** - Stores individual field configurations
3. **form_submissions** - Stores user submissions

## Files Created/Modified

### Models
- `app/Models/Form.php` - Form model with relationships
- `app/Models/FormField.php` - Field model with casts
- `app/Models/FormSubmission.php` - Submission model

### Livewire Components
- `app/Livewire/Forms/FormsIndex.php` - List all forms
- `app/Livewire/Forms/FormBuilderComponent.php` - Create/edit forms
- `app/Livewire/Forms/FormSubmissionComponent.php` - Public form submission
- `app/Livewire/Forms/FormSubmissionsComponent.php` - View submissions

### Views
- `resources/views/livewire/forms/forms-index.blade.php` - Forms listing
- `resources/views/livewire/forms/form-builder-component.blade.php` - Form builder interface
- `resources/views/livewire/forms/form-submission-component.blade.php` - Public form display
- `resources/views/livewire/forms/form-submissions-component.blade.php` - Submissions table
- `resources/views/forms/show.blade.php` - Public form layout

### Routes
Updated `routes/web.php` with:
- `/forms` - List all forms
- `/forms/create` - Create new form
- `/forms/{formId}/edit` - Edit existing form
- `/forms/{formId}/submissions` - View form submissions
- `/form/{slug}` - Public form submission page

### Migrations
- `database/migrations/2025_11_14_091555_create_forms_table.php` - Creates all three tables

## Usage

### Creating a Form
1. Navigate to `/forms`
2. Click "Create New Form"
3. Fill in form details (name, description)
4. Add fields using "Add Field" button
5. Configure each field (type, label, name, validation)
6. Set form status (draft/active/inactive)
7. Save the form

### Sharing a Form
- Active forms are accessible at `/form/{slug}`
- Share this URL with users to collect submissions

### Viewing Submissions
1. Go to `/forms`
2. Click the submissions icon for any form
3. View all submissions in a table format
4. Delete individual submissions if needed

## Field Configuration

Each field supports:
- **Field Type**: Select from 11 different input types
- **Label**: Display name for the field
- **Name**: Database column name (auto-generated from label)
- **Placeholder**: Hint text in the input
- **Help Text**: Additional instructions
- **Required**: Mark as mandatory
- **Options**: For select/radio/checkbox (one per line)

## Security Features
- Forms are user-scoped (users can only see/edit their own forms)
- CSRF protection on all forms
- File upload validation (10MB max)
- IP address tracking for submissions

## Validation
Forms automatically validate based on:
- Field type (email, URL, number, date)
- Required fields
- File size limits
- Custom validation rules (extensible)

## Next Steps
Consider adding:
- [ ] Export submissions to CSV
- [ ] Email notifications on submission
- [ ] Conditional field logic
- [ ] Multi-page forms
- [ ] Custom styling options
- [ ] Form analytics/statistics
- [ ] Submission editing
- [ ] Webhook integrations
