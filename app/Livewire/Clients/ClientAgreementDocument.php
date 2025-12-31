<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use App\Models\UserSignature;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ClientAgreementDocument extends Component
{
    public $client;
    public $services = [];
    public $showPrintButton = true;
    public $showClientSignatureModal = false;
    public $showAuthSignatureModal = false;
    public $showRejectModal = false;
    public $showRemarksModal = false;
    public $showFeasibilityModal = false;
    public $clientSignatureData;
    public $authSignatureData;
    public $hasDrawnNewSignature = false;
    public $currentAuthPosition;
    public $rejectionNote;
    public $viewRemarksPosition;
    public $viewRemarksContent;

    // Feasibility Check
    public $currentServiceId;
    public $currentService;

    // Client properties
    public $agreement_number;
    public $tin_no;
    public $company;
    public $contact_person;
    public $nature_of_business;
    public $address;
    public $designation;
    public $phone;
    public $alternative_contact;
    public $email;
    public $latitude;
    public $longitude;
    public $category;
    public $notes;
    public $payment_type;

    public function mount($client = null, $data = null, $showPrintButton = true)
    {
        $this->showPrintButton = $showPrintButton;

        if ($client instanceof Client) {
            // Load from existing client
            $this->client = $client;
            $this->loadFromClient($client);
        } elseif (is_numeric($client)) {
            // If client is passed as ID from route, load it
            $clientModel = Client::with(['services.serviceType', 'services.product'])->findOrFail($client);
            $this->client = $clientModel;
            $this->loadFromClient($clientModel);
        } elseif ($data) {
            // Load from array data (for preview before saving)
            $this->loadFromData($data);
        }
    }

    protected function loadFromClient(Client $client)
    {
        // Eager load relationships if not already loaded
        if (!$client->relationLoaded('services')) {
            $client->load(['services.serviceType', 'services.product']);
        }

        $this->agreement_number = $client->agreement_number;
        $this->tin_no = $client->tin_no;
        $this->company = $client->company;
        $this->contact_person = $client->contact_person;
        $this->nature_of_business = $client->nature_of_business;
        $this->address = $client->address;
        $this->designation = $client->designation;
        $this->phone = $client->phone;
        $this->alternative_contact = $client->alternative_contact;
        $this->email = $client->email;
        $this->latitude = $client->latitude;
        $this->longitude = $client->longitude;
        $this->category = $client->category;
        $this->notes = $client->notes;
        $this->payment_type = $client->payment_type;

        // Load services
        $this->services = $client->services->map(function ($service) {
            return [
                'id' => $service->id,
                'service_type' => $service->serviceType->name ?? '',
                'product' => $service->product->name ?? '',
                'capacity' => $service->capacity,
                'installation_charge' => $service->installation_charge,
                'monthly_charge' => $service->monthly_charge,
                'contract_start_date' => $service->contract_start_date?->format('Y-m-d'),
            ];
        })->toArray();
    }

    protected function loadFromData(array $data)
    {
        $this->agreement_number = $data['agreement_number'] ?? '';
        $this->tin_no = $data['tin_no'] ?? '';
        $this->company = $data['company'] ?? '';
        $this->contact_person = $data['contact_person'] ?? '';
        $this->nature_of_business = $data['nature_of_business'] ?? '';
        $this->address = $data['address'] ?? '';
        $this->designation = $data['designation'] ?? '';
        $this->phone = $data['phone'] ?? '';
        $this->alternative_contact = $data['alternative_contact'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->latitude = $data['latitude'] ?? '';
        $this->longitude = $data['longitude'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->notes = $data['notes'] ?? '';
        $this->payment_type = $data['payment_type'] ?? 'prepaid';
        $this->services = $data['services'] ?? [];
    }

    protected function getUserAuthorizationPositions()
    {
        $user = auth()->user();
        if (!$user) return [];

        // Map roles to authorization positions
        $rolePositionMap = [
            'sales_manager' => 'Sales Manager',
            'chief_commercial' => 'CCO',
            'credit_control' => 'Credit Control Manager',
            'chief_financial' => 'CFO',
            'business_analyst' => 'Business Analysis',
            'network_planning' => 'Network Planning',
            'implementation' => 'Implementation Manager',
            'director' => 'Director',
        ];

        $positions = [];

        // Get all roles that match
        foreach ($user->roles as $role) {
            if (isset($rolePositionMap[$role->name])) {
                $positions[] = [
                    'position' => $rolePositionMap[$role->name],
                    'name' => $user->name,
                    'date' => date('Y-m-d'),
                ];
            }
        }

        return $positions;
    }

    protected function getApprovalSequence()
    {
        return [
            'Sales Manager',
            'CCO',
            'Credit Control Manager',
            'CFO',
            'Business Analysis',
            'Network Planning',
            'Implementation Manager',
            'Director',
        ];
    }

    protected function canApproveAtPosition($position)
    {
        if (!$this->client) return false;

        $sequence = $this->getApprovalSequence();
        $currentIndex = array_search($position, $sequence);

        if ($currentIndex === false) return false;

        // Check if all previous positions have been completed (signed or rejected)
        for ($i = 0; $i < $currentIndex; $i++) {
            $previousPosition = $sequence[$i];
            $previousSignature = UserSignature::where('client_id', $this->client->id)
                ->where('position', $previousPosition)
                ->first();

            // If previous signature is still pending, current position cannot approve
            if (!$previousSignature || $previousSignature->status === 'pending') {
                return false;
            }
        }

        return true;
    }

    public function openClientSignatureModal()
    {
        // Allow opening modal if client exists
        if ($this->client) {
            $this->showClientSignatureModal = true;
        } else {
            session()->flash('error', 'Client record not found.');
        }
    }

    public function closeClientSignatureModal()
    {
        $this->showClientSignatureModal = false;
        $this->clientSignatureData = null;
    }

    public function saveClientSignature()
    {
        if (!$this->clientSignatureData || !$this->client) {
            session()->flash('error', 'Signature data is required.');
            return;
        }

        try {
            // Decode base64 signature
            $signatureData = $this->clientSignatureData;
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $signature = base64_decode($signatureData);

            // Store signature
            $filename = 'signatures/client_' . $this->client->id . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $signature);

            // Update client record
            $this->client->update([
                'client_signature_data' => $filename,
                'client_signed_at' => now(),
                'status' => 'pending_approval',
            ]);

            // Create user_signatures records for all authorization positions
            $this->createAuthorizationSignatures();

            session()->flash('success', 'Client signature saved successfully!');

            // Close modal and refresh
            $this->showClientSignatureModal = false;
            $this->clientSignatureData = null;

            return $this->redirect(route('clients.agreement', $this->client->id), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save signature: ' . $e->getMessage());
        }
    }

    protected function createAuthorizationSignatures()
    {
        // Define all authorization positions in order
        $positions = [
            'Sales Manager',
            'CCO',
            'Credit Control Manager',
            'CFO',
            'Business Analysis',
            'Network Planning',
            'Implementation Manager',
            'Director',
        ];

        foreach ($positions as $position) {
            UserSignature::create([
                'user_id' => auth()->id(),
                'client_id' => $this->client->id,
                'agreement_number' => $this->client->agreement_number,
                'position' => $position,
                'status' => 'pending',
            ]);
        }
    }

    public function openAuthSignatureModal($position)
    {
        $user = auth()->user();
        if (!$user) return;

        // Check if user has the role for this position
        $userPositions = collect($this->getUserAuthorizationPositions())->pluck('position')->toArray();

        if (!in_array($position, $userPositions)) {
            session()->flash('error', 'You are not authorized for this position.');
            return;
        }

        // Check approval sequence - ensure previous approvers have completed
        if (!$this->canApproveAtPosition($position)) {
            $sequence = $this->getApprovalSequence();
            $currentIndex = array_search($position, $sequence);
            $previousPosition = $currentIndex > 0 ? $sequence[$currentIndex - 1] : null;

            $message = $previousPosition
                ? "You cannot approve yet. Waiting for {$previousPosition} to complete their decision."
                : 'You cannot approve at this time.';

            session()->flash('error', $message);
            return;
        }

        // Get existing signature
        $signature = UserSignature::where('client_id', $this->client->id)
            ->where('position', $position)
            ->first();

        // Check if already signed and user doesn't have edit permission
        if ($signature && $signature->status === 'signed' && $signature->user_id !== $user->id) {
            session()->flash('error', 'This position has already been signed by another user.');
            return;
        }

        // Check if user is editing their own submission
        if ($signature && $signature->status === 'signed' && $signature->user_id === $user->id) {
            if (!$user->can('edit-client-authorization')) {
                session()->flash('error', 'You do not have permission to edit this authorization.');
                return;
            }
        }

        $this->currentAuthPosition = $position;
        $this->showAuthSignatureModal = true;
    }

    public function closeAuthSignatureModal()
    {
        $this->showAuthSignatureModal = false;
        $this->authSignatureData = null;
        $this->hasDrawnNewSignature = false;
        $this->currentAuthPosition = null;
    }

    public function openRejectModal($position)
    {
        $user = auth()->user();
        if (!$user) return;

        // Check if user has the role for this position
        $userPositions = collect($this->getUserAuthorizationPositions())->pluck('position')->toArray();

        if (!in_array($position, $userPositions)) {
            session()->flash('error', 'You are not authorized for this position.');
            return;
        }

        // Check approval sequence - ensure previous approvers have completed
        if (!$this->canApproveAtPosition($position)) {
            $sequence = $this->getApprovalSequence();
            $currentIndex = array_search($position, $sequence);
            $previousPosition = $currentIndex > 0 ? $sequence[$currentIndex - 1] : null;

            $message = $previousPosition
                ? "You cannot reject yet. Waiting for {$previousPosition} to complete their decision."
                : 'You cannot reject at this time.';

            session()->flash('error', $message);
            return;
        }

        // Get existing signature
        $signature = UserSignature::where('client_id', $this->client->id)
            ->where('position', $position)
            ->first();

        // Check if already rejected by another user
        if ($signature && $signature->status === 'rejected' && $signature->user_id !== $user->id) {
            session()->flash('error', 'This position has already been rejected by another user.');
            return;
        }

        // Check if user is editing their own submission
        if ($signature && $signature->status === 'rejected' && $signature->user_id === $user->id) {
            if (!$user->can('edit-client-authorization')) {
                session()->flash('error', 'You do not have permission to edit this authorization.');
                return;
            }
            // Load existing rejection note for editing
            $this->rejectionNote = $signature->remarks ?? '';
        }

        // Check if already signed by another user
        if ($signature && $signature->status === 'signed' && $signature->user_id !== $user->id) {
            session()->flash('error', 'This position has already been processed.');
            return;
        }

        $this->currentAuthPosition = $position;
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectionNote = '';
        $this->currentAuthPosition = null;
    }

    public function saveRejection()
    {
        $this->validate([
            'rejectionNote' => 'required|string|min:10',
        ], [
            'rejectionNote.required' => 'Please provide a reason for rejection.',
            'rejectionNote.min' => 'The rejection reason must be at least 10 characters.',
        ]);

        if (!$this->currentAuthPosition) {
            session()->flash('error', 'Position not specified.');
            return;
        }

        try {
            $user = auth()->user();

            // Get or create signature filename (use existing user signature if available)
            $filename = null;
            if ($user->signature_data) {
                $filename = $user->signature_data;
            }

            // Update user_signature record
            $userSignature = UserSignature::where('client_id', $this->client->id)
                ->where('position', $this->currentAuthPosition)
                ->first();

            if ($userSignature) {
                $userSignature->update([
                    'user_id' => $user->id,
                    'signature_data' => $filename,
                    'status' => 'rejected',
                    'remarks' => $this->rejectionNote,
                    'signed_at' => null, // Clear signed_at since it's rejected
                ]);

                // Update client status to rejected
                $this->client->update(['status' => 'rejected']);

                session()->flash('success', 'Authorization has been rejected.');

                // Close modal and refresh
                $this->showRejectModal = false;
                $this->rejectionNote = '';
                $this->currentAuthPosition = null;

                // Refresh the component data
                $this->dispatch('$refresh');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save rejection: ' . $e->getMessage());
        }
    }

    public function showRemarks($position)
    {
        $signature = UserSignature::where('client_id', $this->client->id)
            ->where('position', $position)
            ->where('status', 'rejected')
            ->first();

        if ($signature && $signature->remarks) {
            $this->viewRemarksPosition = $position;
            $this->viewRemarksContent = $signature->remarks;
            $this->showRemarksModal = true;
        }
    }

    public function closeRemarksModal()
    {
        $this->showRemarksModal = false;
        $this->viewRemarksPosition = null;
        $this->viewRemarksContent = null;
    }

    public function openFeasibilityModal($serviceId)
    {
        $this->currentServiceId = $serviceId;
        $this->currentService = \App\Models\ClientService::with(['serviceType', 'product'])->find($serviceId);
        $this->showFeasibilityModal = true;
    }

    public function closeFeasibilityModal()
    {
        $this->showFeasibilityModal = false;
        $this->currentServiceId = null;
        $this->currentService = null;
    }

    public function resetAuthStatus($position)
    {
        $user = auth()->user();
        if (!$user) return;

        // Check if user has permission
        if (!$user->can('edit-client-authorization')) {
            session()->flash('error', 'You do not have permission to edit authorizations.');
            return;
        }

        // Check if user has the role for this position
        $userPositions = collect($this->getUserAuthorizationPositions())->pluck('position')->toArray();

        if (!in_array($position, $userPositions)) {
            session()->flash('error', 'You are not authorized for this position.');
            return;
        }

        // Get the signature record
        $signature = UserSignature::where('client_id', $this->client->id)
            ->where('position', $position)
            ->where('user_id', $user->id)
            ->first();

        if (!$signature) {
            session()->flash('error', 'Authorization record not found.');
            return;
        }

        try {
            // Reset to pending status
            $signature->update([
                'status' => 'pending',
                'signature_data' => null,
                'signed_at' => null,
                'remarks' => null,
            ]);

            // Update client status back to pending_approval if was rejected
            if ($this->client->status === 'rejected') {
                $this->client->update(['status' => 'pending_approval']);
            }

            session()->flash('success', 'Authorization status has been reset. You can now resubmit.');

            return $this->redirect(route('clients.agreement', $this->client->id), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reset status: ' . $e->getMessage());
        }
    }

    public function saveAuthSignature()
    {
        if (!$this->authSignatureData || !$this->currentAuthPosition) {
            session()->flash('error', 'Signature data is required.');
            return;
        }

        try {
            $user = auth()->user();

            // Decode base64 signature
            $signatureData = $this->authSignatureData;
            $signatureData = str_replace('data:image/png;base64,', '', $signatureData);
            $signatureData = str_replace(' ', '+', $signatureData);
            $signature = base64_decode($signatureData);

            // Store signature
            $filename = 'signatures/auth_' . $user->id . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $signature);

            // Update user_signature record
            $userSignature = UserSignature::where('client_id', $this->client->id)
                ->where('position', $this->currentAuthPosition)
                ->first();

            if ($userSignature) {
                $userSignature->update([
                    'user_id' => $user->id,
                    'signature_data' => $filename,
                    'signed_at' => now(),
                    'status' => 'signed',
                ]);

                // If user drew a new signature manually, update their user signature
                if ($this->hasDrawnNewSignature) {
                    // Delete old signature file if exists
                    if ($user->signature_data && Storage::disk('public')->exists($user->signature_data)) {
                        Storage::disk('public')->delete($user->signature_data);
                    }

                    // Update user's signature
                    $user->update([
                        'signature_data' => $filename,
                    ]);
                } elseif (!$user->signature_data) {
                    // If user has no signature saved, save this one
                    $user->update([
                        'signature_data' => $filename,
                    ]);
                }

                // Check if all signatures are complete
                $this->updateAgreementStatus();

                session()->flash('success', 'Authorization signature saved successfully!');

                // Close modal and refresh
                $this->showAuthSignatureModal = false;
                $this->authSignatureData = null;
                $this->hasDrawnNewSignature = false;
                $this->currentAuthPosition = null;

                // Refresh the component data
                $this->dispatch('$refresh');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to save signature: ' . $e->getMessage());
        }
    }

    protected function updateAgreementStatus()
    {
        $totalSignatures = UserSignature::where('client_id', $this->client->id)->count();
        $signedSignatures = UserSignature::where('client_id', $this->client->id)
            ->where('status', 'signed')
            ->count();

        if ($totalSignatures > 0 && $totalSignatures === $signedSignatures) {
            // All signatures complete
            $this->client->update(['status' => 'approved']);
        } else {
            // Still pending
            $this->client->update(['status' => 'pending_approval']);
        }
    }

    public function render()
    {
        $userPositions = $this->getUserAuthorizationPositions();
        $userPositionsList = collect($userPositions)->pluck('position')->toArray();

        // Get authorization signatures if client exists
        $authorizationSignatures = [];
        $allAuthSignatures = collect();
        $approvalReadiness = [];

        if ($this->client) {
            // Get all signatures for the "FOR OFFICIAL USE ONLY" table
            $allAuthSignatures = UserSignature::where('client_id', $this->client->id)
                ->orderBy('id')
                ->get()
                ->keyBy('position');

            // Only show signatures for the current user's position(s) in AUTHORIZATION table
            $query = UserSignature::where('client_id', $this->client->id);

            if (!empty($userPositionsList)) {
                $query->whereIn('position', $userPositionsList);
            } else {
                // If user has no authorized positions, show none (empty collection)
                $query->whereRaw('1 = 0');
            }

            // Get distinct positions to avoid duplicates
            $authorizationSignatures = $query->orderBy('position')->orderBy('id')->get()->unique('position')->values();

            // Check approval readiness for each position
            foreach ($userPositionsList as $position) {
                $approvalReadiness[$position] = $this->canApproveAtPosition($position);
            }
        }

        return view('livewire.clients.client-agreement-document', [
            'userPositions' => $userPositions,
            'authorizationSignatures' => $authorizationSignatures,
            'allAuthSignatures' => $allAuthSignatures,
            'approvalReadiness' => $approvalReadiness,
        ])
            ->layout('layouts.app', ['title' => 'Client Agreement - ' . $this->agreement_number]);
    }
}
