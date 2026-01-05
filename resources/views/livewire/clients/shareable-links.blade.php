<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">Shareable Registration Links</h1>
                <p class="text-sm text-gray-500">Generate and manage client self-registration links</p>
            </div>
            <button wire:click="openCreateModal" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Link
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="max-w-6xl">
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session()->has('message'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Links Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 border-b border-gray-300">
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">#</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Title/Description</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Link</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Usage</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Expires</th>
                                <th class="px-4 py-2 text-left text-xs font-semibold text-gray-700">Status</th>
                                <th class="px-4 py-2 text-center text-xs font-semibold text-gray-700">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($links as $index => $link)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150">
                                    <td class="px-4 py-2">
                                        <div class="text-xs text-gray-900">{{ $links->firstItem() + $index }}</div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="text-xs font-medium text-gray-900">{{ $link->title ?: 'Untitled Link' }}</div>
                                        @if($link->description)
                                            <div class="text-xs text-gray-500">{{ Str::limit($link->description, 50) }}</div>
                                        @endif
                                        <div class="text-xs text-gray-400 mt-1">Created {{ $link->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center gap-2">
                                            <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ Str::limit($link->token, 20) }}</code>
                                            <button wire:click="copyLink({{ $link->id }})" class="text-blue-600 hover:text-blue-800" title="Copy full link">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="text-xs text-gray-900">
                                            {{ $link->uses_count }}
                                            @if($link->max_uses)
                                                / {{ $link->max_uses }}
                                            @else
                                                / Unlimited
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($link->expires_at)
                                            <div class="text-xs {{ $link->expires_at->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                                                {{ $link->expires_at->format('M d, Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">{{ $link->expires_at->diffForHumans() }}</div>
                                        @else
                                            <div class="text-xs text-gray-500">Never</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($link->isValid())
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="flex items-center justify-center gap-2">
                                            <button wire:click="openQRModal({{ $link->id }})" class="text-purple-600 hover:text-purple-900" title="View QR Code">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                                </svg>
                                            </button>
                                            <button wire:click="toggleStatus({{ $link->id }})" class="text-yellow-600 hover:text-yellow-900" title="Toggle Status">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                            </button>
                                            <button wire:click="deleteLink({{ $link->id }})" wire:confirm="Are you sure you want to delete this link?" class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                            </svg>
                                            <p class="text-lg font-medium">No shareable links created yet</p>
                                            <p class="text-sm">Create your first link to get started</p>
                                            <button wire:click="openCreateModal" class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                                Create Link
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($links->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $links->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create Link Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-lg w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Create Shareable Link</h3>
                    <button wire:click="closeCreateModal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="createLink" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title (Optional)</label>
                        <input type="text" wire:model="title" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g., New Customers Registration">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Add a note about this link..."></textarea>
                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Uses (Optional)</label>
                        <input type="number" wire:model="max_uses" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Leave empty for unlimited">
                        <p class="text-xs text-gray-500 mt-1">How many times this link can be used</p>
                        @error('max_uses') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expires in (Days)</label>
                        <input type="number" wire:model="expires_in_days" min="1" max="365" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('expires_in_days') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <button type="button" wire:click="closeCreateModal" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Create Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- QR Code Modal -->
    @if($showQRModal && $selectedLink)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">QR Code</h3>
                    <button wire:click="closeQRModal" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="text-center">
                    <div class="bg-white p-4 rounded-lg border-2 border-gray-200 inline-block mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($selectedLink->getPublicUrl()) }}" alt="QR Code" class="w-64 h-64">
                    </div>

                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-700 mb-2">Link URL:</p>
                        <div class="bg-gray-50 p-3 rounded-lg break-all text-xs">
                            {{ $selectedLink->getPublicUrl() }}
                        </div>
                    </div>

                    <button wire:click="copyLink({{ $selectedLink->id }})" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg w-full">
                        Copy Link
                    </button>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('copy-to-clipboard', (event) => {
                const url = event[0]?.url || event.url;
                if (url) {
                    navigator.clipboard.writeText(url).then(() => {
                        console.log('Copied to clipboard:', url);
                        alert('Link copied to clipboard!');
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                        alert('Failed to copy link. Please try again.');
                    });
                }
            });
        });
    </script>
</div>
