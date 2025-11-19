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
use App\Livewire\Projects\CreateProject;
use App\Livewire\Projects\ProjectBudget;
use App\Livewire\Projects\ProjectApprovals;
use App\Livewire\Projects\ProjectItemAvailability;

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

// Protected Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // User Profile
    Route::get('/profile', UserProfile::class)->name('profile');

    // Form management
    Route::get('/forms', FormsIndex::class)->name('forms.index');
    Route::get('/forms/create', FormBuilderComponent::class)->name('forms.create');
    Route::get('/forms/{formId}/edit', FormBuilderComponent::class)->name('forms.edit');

    // View submissions (you'll need to create this component)
    Route::get('/forms/{formId}/submissions', FormSubmissionsComponent::class)->name('forms.submissions');

    // Client Management
    Route::get('/clients', ClientsListComponent::class)->name('clients.index');
    Route::get('/clients/enroll', ClientEnrollmentComponent::class)->name('clients.enroll');
    Route::get('/clients/enroll-wizard', ClientEnrollmentWithFormsComponent::class)->name('clients.enroll.wizard');
    Route::get('/clients/{client}/agreement', ClientAgreementDocument::class)->name('clients.agreement');

    // Service Types Management
    Route::get('/service-types', ServiceTypesComponent::class)->name('service-types.index');

    // Products Management
    Route::get('/products', ProductsComponent::class)->name('products.index');

    // Vendors Management
    Route::get('/vendors', VendorsComponent::class)->name('vendors.index');

    // Service Feasibility Management
    Route::get('/services/{clientServiceId}/feasibility', ManageFeasibility::class)->name('services.feasibility');

    // Project Management
    Route::get('/projects', ProjectList::class)->name('projects.list');
    Route::get('/projects/create', CreateProject::class)->name('projects.create');
    Route::get('/projects/{project}/budget', ProjectBudget::class)->name('projects.budget');
    Route::get('/projects/{project}/approvals', ProjectApprovals::class)->name('projects.approvals');
    Route::get('/projects/{project}/availability', ProjectItemAvailability::class)->name('projects.availability');

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
