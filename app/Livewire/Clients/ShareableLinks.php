<?php

namespace App\Livewire\Clients;

use App\Models\ShareableLink;
use Livewire\Component;
use Livewire\WithPagination;

class ShareableLinks extends Component
{
    use WithPagination;

    public $showCreateModal = false;
    public $showQRModal = false;
    public $title = '';
    public $description = '';
    public $max_uses = null;
    public $expires_in_days = 30;
    public $selectedLink = null;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
            'max_uses' => 'nullable|integer|min:1',
            'expires_in_days' => 'required|integer|min:1|max:365',
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openQRModal($linkId)
    {
        $this->selectedLink = ShareableLink::findOrFail($linkId);
        $this->showQRModal = true;
    }

    public function closeQRModal()
    {
        $this->showQRModal = false;
        $this->selectedLink = null;
    }

    public function createLink()
    {
        $this->validate();

        $expiresAt = null;
        if ($this->expires_in_days) {
            $expiresAt = now()->addDays($this->expires_in_days);
        }

        ShareableLink::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'description' => $this->description,
            'max_uses' => $this->max_uses,
            'expires_at' => $expiresAt,
        ]);

        session()->flash('success', 'Shareable link created successfully!');
        $this->closeCreateModal();
    }

    public function copyLink($linkId)
    {
        $link = ShareableLink::findOrFail($linkId);
        $this->dispatch('copy-to-clipboard', url: $link->getPublicUrl());
        session()->flash('message', 'Link copied to clipboard!');
    }

    public function toggleStatus($linkId)
    {
        $link = ShareableLink::findOrFail($linkId);
        $link->update(['is_active' => !$link->is_active]);
        session()->flash('message', 'Link status updated!');
    }

    public function deleteLink($linkId)
    {
        ShareableLink::findOrFail($linkId)->delete();
        session()->flash('success', 'Link deleted successfully!');
    }

    private function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->max_uses = null;
        $this->expires_in_days = 30;
    }

    public function render()
    {
        $links = ShareableLink::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.clients.shareable-links', [
            'links' => $links,
        ])->layout('layouts.app', ['title' => 'Shareable Registration Links']);
    }
}
