<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class UserSignatureManagement extends Component
{
    public $users;
    public $selectedUserId;
    public $signatureData;
    public $showSignatureModal = false;
    public $currentUserSignature;

    protected $rules = [
        'signatureData' => 'required|string',
    ];

    public function mount()
    {
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::with('roles')
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->implode(', '),
                    'has_signature' => !empty($user->signature_data),
                    'signature_data' => $user->signature_data,
                ];
            });
    }

    public function openSignatureModal($userId)
    {
        $this->selectedUserId = $userId;
        $user = User::find($userId);
        $this->currentUserSignature = $user->signature_data ?? null;
        $this->showSignatureModal = true;
    }

    public function saveSignature()
    {
        $this->validate();

        $user = User::find($this->selectedUserId);

        if ($user) {
            // Delete old signature if exists
            if ($user->signature_data && Storage::disk('public')->exists($user->signature_data)) {
                Storage::disk('public')->delete($user->signature_data);
            }

            // Save new signature
            $imageData = $this->signatureData;
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageDecoded = base64_decode($imageData);

            // Generate unique filename
            $filename = 'signatures/' . $user->id . '_' . time() . '.png';
            Storage::disk('public')->put($filename, $imageDecoded);

            // Update user record
            $user->signature_data = $filename;
            $user->save();

            session()->flash('success', 'Signature saved successfully for ' . $user->name);

            $this->reset(['showSignatureModal', 'signatureData', 'selectedUserId']);
            $this->loadUsers();
        }
    }

    public function deleteSignature($userId)
    {
        $user = User::find($userId);

        if ($user && $user->signature_data) {
            // Delete file from storage
            if (Storage::disk('public')->exists($user->signature_data)) {
                Storage::disk('public')->delete($user->signature_data);
            }

            // Clear signature data
            $user->signature_data = null;
            $user->save();

            session()->flash('success', 'Signature deleted successfully for ' . $user->name);
            $this->loadUsers();
        }
    }

    public function closeModal()
    {
        $this->reset(['showSignatureModal', 'signatureData', 'selectedUserId']);
    }

    public function render()
    {
        return view('livewire.users.user-signature-management')
            ->layout('layouts.app');
    }
}
