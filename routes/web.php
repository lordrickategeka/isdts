<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\Forms\FormsIndex;
use App\Livewire\Forms\FormBuilderComponent;
use App\Livewire\Forms\FormSubmissionsComponent;
use App\Livewire\Clients\ClientEnrollmentComponent;
use App\Livewire\Clients\ClientEnrollmentWithFormsComponent;
use App\Livewire\Clients\ClientsListComponent;
use App\Livewire\Clients\ClientAgreementDocument;
use App\Livewire\Clients\ShareableLinks;
use App\Livewire\Forms\FormSubmissionComponent;
use App\Livewire\ServiceTypes\ServiceTypesComponent;
use App\Livewire\Products\ProductsComponent;
use App\Livewire\Roles\RoleManagement;
use App\Livewire\Permissions\PermissionManagement;
use App\Livewire\Users\UserRoleManagement;
use App\Livewire\Users\UserSignatureManagement;
use App\Livewire\Users\UserProfile;
use App\Livewire\Vendors\VendorsComponent;
use App\Livewire\ServiceFeasibility\ManageFeasibility;

use App\Livewire\Projects\ProjectList;

use App\Livewire\Projects\ProjectView;
use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\ProjectBudget;
use App\Livewire\Projects\ProjectApprovals;
use App\Livewire\Projects\ProjectItemAvailability;

use App\Http\Controllers\SurveyjsBuilderController;
use App\Livewire\Customers\CustomerCreateComponent;
use App\Livewire\Customers\CustomersComponent;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// Protected Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Generic Site Survey Form (no project/client required)
    Route::get('/site-survey', App\Livewire\Projects\SurveyForm::class)->name('site-survey');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // User Profile
    Route::get('/profile', UserProfile::class)->name('profile');


    // ===========================================================================
    // Form management
    Route::get('/forms', FormsIndex::class)->name('forms.index');
    Route::get('/forms/create', FormBuilderComponent::class)->name('forms.create');
    Route::get('/forms/{formId}/edit', FormBuilderComponent::class)->name('forms.edit');

    // View submissions (you'll need to create this component)
    Route::get('/forms/{formId}/submissions', FormSubmissionsComponent::class)->name('forms.submissions');

    // Form submission (generic)
    Route::get('/forms/{formId}/submit', FormSubmissionComponent::class)->name('forms.submit');

    // Project-specific form submission
    Route::get('/projects/{project}/form/submit', function ($project) {
        $project = \App\Models\Project::with('form')->findOrFail($project);
        abort_unless($project->form, 404);
        return app(FormSubmissionComponent::class, ['formId' => $project->form->id]);
    })->name('projects.form.submit');


    // SurveyJS Builder
    Route::get('/surveyjs-builder', [SurveyjsBuilderController::class, 'index'])->name('surveyjs.builder');
    Route::post('/surveyjs-builder/save', [SurveyjsBuilderController::class, 'save'])->name('surveyjs.builder.save');


    // ===========================================================================


    // Client Management
    Route::get('/clients', ClientsListComponent::class)->name('clients.index');
    Route::get('/clients/enroll', ClientEnrollmentComponent::class)->name('clients.enroll');
    Route::get('/clients/enroll-wizard', ClientEnrollmentWithFormsComponent::class)->name('clients.enroll.wizard');
    Route::get('/clients/{client}/agreement', ClientAgreementDocument::class)->name('clients.agreement');
    Route::get('/clients/shareable-links', ShareableLinks::class)->name('clients.shareable-links');

    // Service Types Management
    Route::get('/service-types', ServiceTypesComponent::class)->name('service-types.index');


    // Customers (sidebar) - Livewire-backed routes
    Route::get('/customers', CustomersComponent::class)->name('customers.index');
    Route::get('/customers/create', CustomerCreateComponent::class)->name('customers.create');

    Route::get('/customers/import', function () {
        return view('customers.import');
    })->name('customers.import');

    Route::get('/customers/groups', function () {
        return view('customers.groups');
    })->name('customers.groups');

    // Products Management
    Route::get('/products', ProductsComponent::class)->name('products.index');

    // Vendors Management
    Route::get('/vendors', VendorsComponent::class)->name('vendors.index');

    // Leads Management
    Route::get('/leads', App\Livewire\Leads\LeadsListComponent::class)->name('leads.index');
    Route::get('/leads/create', App\Livewire\Leads\LeadCreateComponent::class)->name('leads.create');

    // Service Feasibility Management
    Route::get('/services/{clientServiceId}/feasibility', ManageFeasibility::class)->name('services.feasibility');

    // Project Management
    Route::get('/projects', ProjectList::class)->name('projects.list');
    // Parties (protected)
    Route::get('/parties', App\Livewire\Party\CreatePartyComponent::class)->name('parties.index');
    Route::get('/parties/categories', App\Livewire\Party\CategoriesComponent::class)->name('parties.categories');
    // Party profiles view (uses same PartyComponent for now)
    Route::get('/parties/profiles', App\Livewire\Party\PartyComponent::class)->name('parties.profiles');
    Route::get('/projects/create', CreateProject::class)->name('projects.create');
    Route::get('/projects/{project}/view', ProjectView::class)->name('projects.view');
    Route::get('/projects/{project}/budget', ProjectBudget::class)->name('projects.budget');
    Route::get('/projects/{project}/approvals', ProjectApprovals::class)->name('projects.approvals');
    Route::get('/projects/{project}/availability', ProjectItemAvailability::class)->name('projects.availability');

    // Survey Management (Livewire)
    Route::get('/projects/{project}/survey/create', App\Livewire\Projects\SurveyForm::class)->name('projects.survey.create');
    Route::get('/clients/{client}/survey/create', App\Livewire\Projects\SurveyForm::class)->name('clients.survey.create');

    // Survey Ticket Creation
    Route::get('/survey/ticket/create', App\Livewire\Projects\SurveyTicketCreate::class)->name('survey.ticket.create');
    // Survey Ticket List
    Route::get('/survey/tickets', App\Livewire\Projects\SurveyTicketList::class)->name('survey.tickets.list');
    // Survey form by token for engineers
    Route::get('/survey/form/{token}', [App\Livewire\Projects\SurveyForm::class, 'showByToken'])->name('survey.form.token');

    // Roles & Permissions Management
    Route::get('/roles', RoleManagement::class)->name('roles.index');
    Route::get('/permissions', PermissionManagement::class)->name('permissions.index');
    Route::get('/users/roles', UserRoleManagement::class)->name('users.roles');
    Route::get('/users/signatures', UserSignatureManagement::class)->name('users.signatures');

    //feasibility management
    // Route::get('/feasibility/manage/{clientServiceId}', ManageFeasibility::class)->name('feasibility.manage');


});

Route::get('/form/{slug}', function ($slug) {
    $form = \App\Models\Form::where('slug', $slug)->where('status', 'active')->firstOrFail();
    return view('forms.show', compact('form'));
})->name('forms.show');

// Public client registration via shareable link
Route::get('/register/{token}', ClientEnrollmentComponent::class)->name('client.register');
Route::get('/registration/success', \App\Livewire\Clients\RegistrationSuccess::class)->name('client.registration.success');

// Client Portal Routes (for authenticated clients)
Route::middleware(['auth'])->prefix('client')->group(function () {
    Route::get('/dashboard', \App\Livewire\Clients\ClientDashboard::class)->name('client.dashboard');
    Route::get('/agreements', function () {
        return view('client.agreements');
    })->name('client.agreements');
    Route::get('/services', function () {
        return view('client.services');
    })->name('client.services');
    Route::get('/profile', function () {
        return view('client.profile');
    })->name('client.profile');
    Route::get('/settings', function () {
        return view('client.settings');
    })->name('client.settings');
});

Route::get('/contact-form', \App\Livewire\Contact\ContactFormComponent::class)->name('contact.form');
