<?php

namespace App\Livewire\Clients;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Client;
use App\Models\ClientService;
use App\Models\VendorService;
use App\Models\Product;
use App\Models\ShareableLink;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientEnrollmentComponent extends Component
{
    use WithFileUploads;

    // Shareable link tracking
    public $token = null;
    public $shareableLink = null;

    // Step management
    public $currentStep = 1;
    public $totalSteps = 4;

    // Category selection
    public $category = '';
    public $category_type = '';

    public function mount($token = null)
    {
        // Generate agreement number on component mount
        $this->agreement_number = $this->generateAgreementNumber();

        // Handle shareable link token if provided
        if ($token) {
            $this->token = $token;
            $link = ShareableLink::where('token', $token)->first();

            if ($link && $link->isValid()) {
                $this->shareableLink = $link;
                session()->flash('info', 'You are registering through a shared link.');
            } else {
                session()->flash('error', 'Invalid or expired registration link.');
                return $this->redirect(route('login'));
            }
        }
    }

    public function updatedCategory($value)
    {
        // Reset category_type when switching from Corporate to Home
        if ($value !== 'Corporate') {
            $this->category_type = '';
        }
    }

    public function updatedServiceTypeId($value)
    {
        // Load products when service type changes
        if ($value) {
            $this->products = Product::where('service_type_id', $value)
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->get();
        } else {
            $this->products = [];
        }
        $this->product_id = '';
        $this->capacity = '';
        $this->installation_charge = '';
        $this->monthly_charge = '';
    }

    public function updatedProductId($value)
    {
        // Populate capacity and charges when product changes
        if ($value) {
            $product = Product::find($value);
            if ($product) {
                $this->capacity = $product->capacity ?? '';
                $this->installation_charge = $product->installation_charge ?? '';
                $this->monthly_charge = $product->monthly_charge ?? '';
            }
        } else {
            $this->capacity = '';
            $this->installation_charge = '';
            $this->monthly_charge = '';
        }
    }

    // Step 1: Basic Information
    public $company = '';
    public $contact_person = '';
    public $nature_of_business = '';
    public $tin_no = '';

    // Step 2: Contact Information
    public $designation = '';
    public $telephone_number = '';
    public $phone = '';
    public $alternative_contact = '';
    public $email = '';
    public $business_phone = '';
    public $business_email = '';

    // Step 3: Address & Coordinates
    public $address = '';
    public $latitude = '';
    public $longitude = '';

    // Step 3: Service Details
    public $service_type_id = '';
    public $product_id = '';
    public $products = [];
    public $connection_type = '';
    public $microwave = '';
    public $fibre = '';
    public $service_type = '';
    public $capacity = '';
    public $installation_charge = '';
    public $monthly_charge = '';
    public $router = '';
    public $other_equipment = '';
    public $contract_start_date = '';

    // Multiple Services
    public $services = [];

    // Computed property to transform services for agreement document
    public function getServicesForAgreementProperty()
    {
        return collect($this->services)->map(function ($service) {
            return [
                'service_type' => $service['service_type_name'] ?? '',
                'product' => $service['product_name'] ?? '',
                'capacity' => $service['capacity'] ?? '',
                'installation_charge' => $service['installation_charge'] ?? 0,
                'monthly_charge' => $service['monthly_charge'] ?? 0,
                'contract_start_date' => $service['contract_start_date'] ?? '',
            ];
        })->toArray();
    }

    // Step 5: Additional Information
    public $agreement_number = '';
    public $notes = '';
    public $client_signature_date = '';
    public $salesperson_signature_date = '';

    // Payment Information
    public $payment_type = 'postpaid';
    public $proof_of_payment;

    // Legacy fields (kept for backward compatibility)
    public $first_name = '';
    public $last_name = '';
    public $city = '';
    public $state = '';
    public $zip_code = '';
    public $country = 'Uganda';

    protected $rules = [
        'category' => 'required|string',
        'category_type' => 'nullable|string',
        'company' => 'nullable|string|max:255',
        'contact_person' => 'nullable|string|max:255',
        'nature_of_business' => 'nullable|string|max:255',
        'tin_no' => 'nullable|string|max:255',
        'designation' => 'nullable|string|max:255',
        'telephone_number' => 'nullable|string|max:20',
        'phone' => 'required|string|max:20',
        'alternative_contact' => 'nullable|string|max:20',
        'email' => 'required|email|unique:clients,email',
        'business_phone' => 'nullable|string|max:20',
        'business_email' => 'nullable|email',
        'address' => 'required|string',
        'latitude' => 'nullable|string|max:255',
        'longitude' => 'nullable|string|max:255',
        'connection_type' => 'nullable|string|max:255',
        'microwave' => 'nullable|string|max:255',
        'fibre' => 'nullable|string|max:255',
        'service_type' => 'nullable|string|max:255',
        'capacity' => 'nullable|string|max:255',
        'installation_charge' => 'nullable|numeric',
        'monthly_charge' => 'nullable|numeric',
        'router' => 'nullable|string|max:255',
        'other_equipment' => 'nullable|string|max:255',
        'contract_start_date' => 'nullable|date',
        'payment_type' => 'required|in:prepaid,postpaid',
        'proof_of_payment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
    ];

    public function addService()
    {
        // Validate service fields
        $this->validate([
            'service_type_id' => 'required',
            'product_id' => 'required',
            'capacity' => 'nullable|string',
            'installation_charge' => 'nullable|numeric',
            'monthly_charge' => 'nullable|numeric',
            'contract_start_date' => 'nullable|date',
        ]);

        // Get vendor service and product names
        $vendorService = VendorService::find($this->service_type_id);
        $product = Product::find($this->product_id);

        // Add service to array
        $this->services[] = [
            'service_type_id' => $this->service_type_id,
            'service_type_name' => $vendorService ? $vendorService->service_name : 'N/A',
            'product_id' => $this->product_id,
            'product_name' => $product ? $product->name : 'N/A',
            'subcategory_name' => $product && $product->subcategory ? $product->subcategory->name : null,
            'capacity' => $this->capacity,
            'installation_charge' => $this->installation_charge,
            'monthly_charge' => $this->monthly_charge,
            'contract_start_date' => $this->contract_start_date,
        ];

        // Reset service fields
        $this->resetServiceFields();

        session()->flash('service_added', 'Service added successfully!');
    }

    public function removeService($index)
    {
        unset($this->services[$index]);
        $this->services = array_values($this->services); // Re-index array
    }

    private function resetServiceFields()
    {
        $this->service_type_id = '';
        $this->product_id = '';
        $this->products = [];
        $this->capacity = '';
        $this->installation_charge = '';
        $this->monthly_charge = '';
        $this->contract_start_date = '';
    }

    public function nextStep()
    {
        // If on step 3 and there are service fields filled but not added, add them automatically
        if ($this->currentStep == 3 && $this->service_type_id && $this->product_id) {
            $this->addService();
        }

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
        if ($this->currentStep == 1) {
            $this->validate([
                'category' => 'required|string',
                'category_type' => $this->category === 'Corporate' ? 'required|string' : 'nullable|string',
                'company' => 'nullable|string|max:255',
                'contact_person' => 'nullable|string|max:255',
                'nature_of_business' => 'nullable|string|max:255',
                'tin_no' => 'nullable|string|max:255',
            ]);
        } elseif ($this->currentStep == 2) {
            $this->validate([
                'designation' => 'nullable|string|max:255',
                'telephone_number' => 'nullable|string|max:20',
                'phone' => 'required|string|max:20',
                'alternative_contact' => 'nullable|string|max:20',
                'email' => 'required|email',
                'address' => 'required|string',
                'latitude' => 'nullable|string|max:255',
                'longitude' => 'nullable|string|max:255',
            ]);
        } elseif ($this->currentStep == 3) {
            // Validate that at least one service has been added
            if (empty($this->services)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'services' => 'Please add at least one service before proceeding.'
                ]);
            }
        } elseif ($this->currentStep == 4) {
            $rules = [
                'connection_type' => 'nullable|string|max:255',
                'microwave' => 'nullable|string|max:255',
                'fibre' => 'nullable|string|max:255',
                'service_type' => 'nullable|string|max:255',
                'capacity' => 'nullable|string|max:255',
                'installation_charge' => 'nullable|numeric',
                'monthly_charge' => 'nullable|numeric',
                'router' => 'nullable|string|max:255',
                'other_equipment' => 'nullable|string|max:255',
                'contract_start_date' => 'nullable|date',
                'payment_type' => 'required|in:prepaid,postpaid',
            ];

            // If prepaid, proof of payment is required
            if ($this->payment_type === 'prepaid') {
                $rules['proof_of_payment'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
            }

            $this->validate($rules);
        }
    }

    private function generateAgreementNumber()
    {
        // Generate agreement number format: BCC-AGR-XXXXXX (random alphanumeric)
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomPart = '';

        for ($i = 0; $i < 6; $i++) {
            $randomPart .= $characters[rand(0, strlen($characters) - 1)];
        }

        return 'BCC-AGR-' . $randomPart;
    }

    public function submit()
    {
        // Log at the very start to confirm method is called
        logger('============ SUBMIT METHOD CALLED ============');
        logger('Current Step: ' . $this->currentStep);
        logger('Form Data:', [
            'category' => $this->category,
            'contact_person' => $this->contact_person,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'services_count' => count($this->services),
        ]);

        try {
            logger('Starting validation...');

            // Validate with unique email check
            $this->validate([
                'category' => 'required|string',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:clients,email',
                'address' => 'required|string',
            ]);

            logger('Validation passed!');

            // Check if services exist
            if (empty($this->services)) {
                logger('ERROR: No services added');
                session()->flash('error', 'Please add at least one service before submitting.');
                $this->currentStep = 3; // Go back to service step
                return;
            }

            logger('Services check passed. Service count: ' . count($this->services));

            // Business logic for category_type
            $categoryType = $this->category === 'Home' ? 'Home' : $this->category_type;

            // Business logic for company - use contact_person as fallback
            $company = $this->company ?: $this->contact_person;

            // Handle proof of payment file upload
            $popPath = null;
            if ($this->payment_type === 'prepaid' && $this->proof_of_payment) {
                $popPath = $this->proof_of_payment->store('proof_of_payments', 'public');
            }

            logger('About to create client with data:', [
                'category' => $this->category,
                'category_type' => $categoryType,
                'company' => $company,
                'email' => $this->email,
                'payment_type' => $this->payment_type,
            ]);

            // Create the client
            // If via shareable link, use the link creator's user_id; otherwise use current authenticated user
            $userId = $this->shareableLink ? $this->shareableLink->user_id : Auth::user()->id;

            $client = Client::create([
                'user_id' => $userId,
                'category' => $this->category,
                'category_type' => $categoryType,
                'contact_person' => $this->contact_person,
                'company' => $company,
                'nature_of_business' => $this->nature_of_business ?: null,
                'tin_no' => $this->tin_no ?: null,
                'designation' => $this->designation,
                'phone' => $this->phone,
                'alternative_contact' => $this->alternative_contact,
                'email' => $this->email,
                'business_phone' => $this->business_phone ?: null,
                'business_email' => $this->business_email ?: null,
                'address' => $this->address,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'agreement_number' => $this->agreement_number,
                'notes' => $this->notes,
                'payment_type' => $this->payment_type,
                'proof_of_payment' => $popPath,
                // Set legacy fields
                'first_name' => $this->contact_person ?? '',
                'last_name' => '',
                'city' => '',
                'state' => '',
                'zip_code' => '',
                'country' => $this->country,
            ]);

            logger('Client created successfully! ID: ' . $client->id);

            // Save all services for this client
            foreach ($this->services as $index => $service) {
                logger('Creating service ' . ($index + 1) . '/' . count($this->services));
                ClientService::create([
                    'client_id' => $client->id,
                    'service_type_id' => $service['service_type_id'],
                    'product_id' => $service['product_id'],
                    'capacity' => $service['capacity'] ?: null,
                    'installation_charge' => $service['installation_charge'] ?: null,
                    'monthly_charge' => $service['monthly_charge'] ?: null,
                    'contract_start_date' => $service['contract_start_date'] ?: null,
                    'status' => 'active',
                ]);
            }

            logger('All services created successfully!');

            // Increment shareable link use count if applicable
            if ($this->shareableLink) {
                $this->shareableLink->incrementUses();
                logger('Shareable link use count incremented. Link ID: ' . $this->shareableLink->id);
            }

            session()->flash('success', 'Client enrolled successfully with ' . count($this->services) . ' service(s)!');

            logger('About to redirect...');

            // Redirect based on whether this was a public registration or staff-initiated
            if ($this->shareableLink) {
                // Public client registration - show success page or redirect to login
                session()->flash('registration_success', 'Registration successful! Please check your email for login credentials.');
                return $this->redirect(route('client.registration.success'), navigate: true);
            }

            // Staff-initiated registration - go to client list
            return $this->redirect(route('clients.index'), navigate: true);

        } catch (\Illuminate\Validation\ValidationException $e) {
            logger('VALIDATION EXCEPTION:', [
                'errors' => $e->errors(),
                'message' => $e->getMessage(),
            ]);
            // Re-throw validation exceptions so they show in the form
            throw $e;
        } catch (\Exception $e) {
            // Log the error for debugging
            logger('CLIENT ENROLLMENT EXCEPTION:', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            session()->flash('error', 'Failed to enroll client: ' . $e->getMessage());
            return; // Stay on current page to show error
        }
    }

    public function render()
    {
        $serviceTypes = VendorService::orderBy('service_name')->get();

        // Use client portal layout if accessed via shareable link, otherwise use staff layout
        $layout = $this->token ? 'layouts.client' : 'layouts.app';

        return view('livewire.clients.client-enrollment-component', [
            'serviceTypes' => $serviceTypes,
        ]);
    }
}
