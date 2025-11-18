<?php

namespace App\Livewire\Users;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserProfile extends Component
{
    public $user;
    public $name;
    public $email;
    public $currentPassword;
    public $newPassword;
    public $newPasswordConfirmation;
    public $signatureData;
    public $showSignatureModal = false;
    public $currentUserSignature;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
    ];

    protected $passwordRules = [
        'currentPassword' => 'required',
        'newPassword' => 'required|min:8|confirmed',
    ];

    public function mount()
    {
        $this->user = auth()->user();
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->currentUserSignature = $this->user->signature_data;
    }

    public function updateProfile()
    {
        $this->validate();

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('success', 'Profile updated successfully!');
    }

    public function updatePassword()
    {
        $this->validate($this->passwordRules);

        // Verify current password
        if (!Hash::check($this->currentPassword, $this->user->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        // Update password
        $this->user->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
        session()->flash('success', 'Password updated successfully!');
    }

    public function openSignatureModal()
    {
        $this->currentUserSignature = $this->user->signature_data;
        $this->showSignatureModal = true;
    }

    public function saveSignature()
    {
        $this->validate([
            'signatureData' => 'required|string',
        ]);

        // Delete old signature if exists
        if ($this->user->signature_data && Storage::disk('public')->exists($this->user->signature_data)) {
            Storage::disk('public')->delete($this->user->signature_data);
        }

        // Save new signature
        $imageData = $this->signatureData;
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $imageDecoded = base64_decode($imageData);

        // Generate unique filename
        $filename = 'signatures/' . $this->user->id . '_' . time() . '.png';
        Storage::disk('public')->put($filename, $imageDecoded);

        // Update user record
        $this->user->signature_data = $filename;
        $this->user->save();

        $this->currentUserSignature = $filename;
        session()->flash('success', 'Signature saved successfully!');

        $this->reset(['showSignatureModal', 'signatureData']);
    }

    public function deleteSignature()
    {
        if ($this->user->signature_data) {
            // Delete file from storage
            if (Storage::disk('public')->exists($this->user->signature_data)) {
                Storage::disk('public')->delete($this->user->signature_data);
            }

            // Clear signature data
            $this->user->signature_data = null;
            $this->user->save();

            $this->currentUserSignature = null;
            session()->flash('success', 'Signature deleted successfully!');
        }
    }

    public function closeModal()
    {
        $this->reset(['showSignatureModal', 'signatureData']);
    }

    public function render()
    {
        return view('livewire.users.user-profile')
            ->layout('layouts.app');
    }
}
