<div class="min-h-screen bg-gray-50">
    <div class="bg-base-100 border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-black">Manage Party</h1>
                <p class="text-sm text-gray-700">Add a new person or company</p>
            </div>
            <div>
                <button type="button" wire:click="openModal" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg">New Party</button>
            </div>
        </div>
    </div>

    <div class="p-4">
        <div class="max-w-6xl mx-auto">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('success') }}</div>
            @endif

            <!-- Delete confirmation modal -->
            @if($showDeleteConfirmModal)
                <div class="fixed inset-0 z-60 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black opacity-50" wire:click="cancelDelete"></div>
                    <div class="relative w-full max-w-2xl mx-4">
                        <div class="bg-white rounded-lg shadow-lg p-4" role="dialog" aria-modal="true">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-semibold">Confirm Delete Party</h3>
                                <button type="button" wire:click="cancelDelete" class="text-gray-500 hover:text-gray-700">✕</button>
                            </div>

                            <p class="text-sm text-gray-700 mb-3">This party has the following associations. Deleting the party will remove these associations. Confirm to proceed.</p>

                            <div class="max-h-56 overflow-y-auto space-y-2 mb-4">
                                @foreach($partyToDeleteAssociations as $a)
                                    <div class="p-2 bg-gray-50 border rounded flex items-start gap-3">
                                        <div class="text-xs text-gray-600">Related:</div>
                                        <div class="text-sm font-medium">{{ $a['related_party_display'] }}</div>
                                        <div class="flex flex-wrap gap-1 ml-2">
                                            @foreach($a['types'] as $t)
                                                <span class="text-xs px-2 py-0.5 bg-indigo-100 text-indigo-800 rounded">{{ $t }}</span>
                                            @endforeach
                                        </div>
                                        <div class="ml-auto text-xs text-gray-500">{{ ucfirst($a['status'] ?? 'active') }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex justify-end gap-2">
                                <button type="button" wire:click="cancelDelete" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded">Cancel</button>
                                <button type="button" wire:click="performDelete" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded">Delete Party</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Left: Submitted Parties Table (span 2) -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-800">Submitted Parties</h3>
                            <span class="text-xs text-gray-500">Latest</span>
                        </div>

                        @if (!empty($submittedParties) && $submittedParties->count())
                            <div class="overflow-y-auto" style="max-height:420px;">
                                <table class="min-w-full text-sm text-left">
                                    <thead>
                                        <tr class="border-b">
                                            <th class="py-2 px-2 text-gray-600">#</th>
                                            <th class="py-2 px-2 text-gray-600">Name</th>
                                            <th class="py-2 px-2 text-gray-600">Assoc Types</th>
                                            <th class="py-2 px-2 text-gray-600">Status</th>
                                            <th class="py-2 px-2 text-gray-600">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($submittedParties as $p)
                                            <tr wire:click="select({{ $p->id }})" class="border-b hover:bg-gray-50 cursor-pointer">
                                                    <td class="py-2 px-2">{{ $loop->iteration }}</td>
                                                    <td class="py-2 px-2">{{ $p->display_name ?? $p->first_name . ' ' . $p->last_name }}</td>
                                                    <td class="py-2 px-2 text-gray-700 font-medium">{{ $assocTypeCounts[$p->id] ?? 0 }}</td>
                                                    <td class="py-2 px-2 text-gray-600">{{ ucfirst($p->status) }}</td>
                                                    {{-- <td class="py-2 px-2 text-gray-500">{{ $p->created_at ? $p->created_at->diffForHumans() : '' }}</td>mo --}}
                                                    <td class="py-2 px-2">
                                                        <div class="relative">
                                                            <button type="button" wire:click.stop="toggleActionMenu({{ $p->id }})" class="p-1 rounded hover:bg-gray-100">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01" />
                                                                </svg>
                                                            </button>

                                                            @if($actionMenuFor === $p->id)
                                                                <div class="absolute right-0 mt-2 w-36 bg-white border rounded shadow-md z-40">
                                                                    <ul class="py-1">
                                                                        <li>
                                                                            <button type="button" wire:click.stop="edit({{ $p->id }})" class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100">Edit</button>
                                                                        </li>
                                                                        <li>
                                                                            <button type="button" onclick="if(!confirm('Delete this party?')) event.stopImmediatePropagation();" wire:click.stop="delete({{ $p->id }})" class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100">Delete</button>
                                                                        </li>
                                                                        <li>
                                                                            <button type="button" wire:click.stop="openAssocModal({{ $p->id }})" class="w-full text-left px-3 py-2 text-xs hover:bg-gray-100">Add Association</button>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-xs text-gray-500">No parties yet.</div>
                        @endif
                    </div>
                </div>

                <!-- Right: Association Types (span 1) -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-semibold text-gray-800">Association Types</h4>
                            @if($selectedPartyId)
                                <span class="text-xs text-gray-500">For selected party</span>
                            @else
                                <span class="text-xs text-gray-500">Select a party</span>
                            @endif
                        </div>

                        @if($selectedPartyId && !empty($associationTypes) && count($associationTypes))
                            <div class="overflow-y-auto" style="max-height:420px;">
                                <table class="w-full text-xs">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-2 py-1 text-left text-gray-600">#</th>
                                            <th class="px-2 py-1 text-left text-gray-600">Type</th>
                                            <th class="px-2 py-1 text-left text-gray-600">Count</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
                                        @foreach($associationTypes as $at)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="px-2 py-1">{{ $loop->iteration }}</td>
                                                <td class="px-2 py-1">{{ $at['type'] }}</td>
                                                <td class="px-2 py-1 text-gray-600">{{ $at['count'] }}</td>
                                                <td class="px-2 py-1 text-right">
                                                    <button type="button" wire:click.prevent="addAssocTypeFromList('{{ addslashes($at['type']) }}')" class="text-xs px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 rounded">Use</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @elseif(!$selectedPartyId)
                            <div class="text-xs text-gray-500">Select a party to view its association types.</div>
                        @else
                            <div class="text-xs text-gray-500">No association types for this party.</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Modal -->
            @if($showModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black opacity-50" wire:click="closeModal"></div>

                    <div class="relative w-full max-w-lg mx-4">
                        <div class="bg-white rounded-lg shadow-lg p-4" role="dialog" aria-modal="true">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold">{{ $editingId ? 'Edit Party' : 'New Party' }}</h3>
                                <button type="button" wire:click="closeModal" class="text-gray-500 hover:text-gray-700">✕</button>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Display Name</label>
                                    <input wire:model.defer="display_name" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                                    @error('display_name')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                    <select wire:model.defer="status" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="blacklisted">Blacklisted</option>
                                    </select>
                                    @error('status')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                                    <textarea wire:model.defer="notes" rows="4" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                                    @error('notes')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end items-center gap-2">
                                <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                                <button type="button" wire:click="save" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Association Modal -->
            @if($showAssocModal)
                <div class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black opacity-50" wire:click="closeAssocModal"></div>

                    <div class="relative w-full max-w-lg mx-4">
                        <div class="bg-white rounded-lg shadow-lg p-4" role="dialog" aria-modal="true">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold">New Association</h3>
                                <button type="button" wire:click="closeAssocModal" class="text-gray-500 hover:text-gray-700">✕</button>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Related Party</label>
                                    <select wire:model.defer="assoc_related_party_id" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                        <option value="">Select related party</option>
                                        @foreach($partyOptions as $opt)
                                            <option value="{{ $opt['id'] }}">{{ $opt['display_name'] ?? 'Party '.$opt['id'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('assoc_related_party_id')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Association Types</label>
                                    <div class="flex gap-2">
                                        <input wire:model.defer="assoc_new_type" placeholder="Type name" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg" />
                                        <button type="button" wire:click.prevent="addAssocType" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded">Add</button>
                                    </div>
                                    @error('assoc_types')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror

                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($assoc_types as $i => $t)
                                            <span class="flex items-center gap-2 bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">
                                                <span>{{ $t }}</span>
                                                <button type="button" wire:click.prevent="removeAssocType({{ $i }})" class="text-xs text-red-600">✕</button>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                    <select wire:model.defer="assoc_status" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                    @error('assoc_status')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="mt-4 flex justify-end items-center gap-2">
                                <button type="button" wire:click="closeAssocModal" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                                <button type="button" wire:click="createAssociation" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">Save Association</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
