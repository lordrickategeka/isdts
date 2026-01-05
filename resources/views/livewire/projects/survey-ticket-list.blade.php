<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">Survey Tickets</h1>
                <p class="text-sm text-gray-500">List of all created survey tickets</p>
            </div>
            <a href="{{ route('survey.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Survey Ticket
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex-1 w-full">
                        <div class="relative">
                            <input type="text"
                                class="w-full px-4 py-1.5 pl-9 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Search survey tickets...">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button class="px-2 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5 text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <button class="px-2 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5 text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Import
                        </button>
                        <button class="px-2 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5 text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </button>
                        <label class="text-xs text-gray-600">Show:</label>
                        <select class="px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Survey Tickets Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Survey Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned User</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($tickets as $index => $ticket)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-100' }} hover:bg-blue-50 transition-colors duration-150">
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2 text-xs font-medium text-gray-900">
                                        <button wire:click="show({{ $ticket->id }})" class="text-blue-600 hover:underline focus:outline-none">
                                            {{ $ticket->survey_name }}
                                        </button>
                                    </td>
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $ticket->assignedUser?->name }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $ticket->project?->name }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $ticket->client?->name }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $ticket->start_date }}</td>
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $ticket->status }}</td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-1">
                                            <button wire:click="edit({{ $ticket->id }})" class="px-2 py-1.5 text-xs text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="delete({{ $ticket->id }})" wire:confirm="Are you sure you want to delete this survey ticket?" class="px-2 py-1.5 text-xs text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-sm">No survey tickets found.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Survey Ticket Modal -->
    @if($editId)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('editId', null)"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="update">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Survey Ticket</h3>
                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Survey Name</label>
                                    <input type="text" wire:model.defer="editData.survey_name" class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Contact Person</label>
                                    <input type="text" wire:model.defer="editData.contact_person" class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                                    <input type="date" wire:model.defer="editData.start_date" class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                    <textarea wire:model.defer="editData.description" rows="2" class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Location</label>
                                    <input type="text" wire:model.defer="editData.location" class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Priority</label>
                                    <input type="text" wire:model.defer="editData.priority" class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                    <input type="text" wire:model.defer="editData.status" class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Update</button>
                            <button type="button" wire:click="$set('editId', null)" class="mt-3 sm:mt-0 w-full sm:w-auto px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Show Survey Ticket Modal -->
    @if($showId)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeShow"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Survey Ticket Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="block text-xs font-medium text-gray-700 mb-1">Survey Name</span>
                                <span class="block text-sm text-gray-900">{{ $showData['survey_name'] ?? '' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-700 mb-1">Contact Person</span>
                                <span class="block text-sm text-gray-900">{{ $showData['contact_person'] ?? '' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-700 mb-1">Start Date</span>
                                <span class="block text-sm text-gray-900">{{ $showData['start_date'] ?? '' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-700 mb-1">Assigned User</span>
                                <span class="block text-sm text-gray-900">{{ $tickets->find($showId)?->assignedUser?->name ?? '' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-700 mb-1">Project</span>
                                <span class="block text-sm text-gray-900">{{ $tickets->find($showId)?->project?->name ?? '' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-700 mb-1">Client</span>
                                <span class="block text-sm text-gray-900">{{ $tickets->find($showId)?->client?->name ?? '' }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <span class="block text-xs font-medium text-gray-700 mb-1">Description</span>
                                <span class="block text-sm text-gray-900">{{ $showData['description'] ?? '' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-700 mb-1">Location</span>
                                <span class="block text-sm text-gray-900">{{ $showData['location'] ?? '' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-700 mb-1">Priority</span>
                                <span class="block text-sm text-gray-900">{{ $showData['priority'] ?? '' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs font-medium text-gray-700 mb-1">Status</span>
                                <span class="block text-sm text-gray-900">{{ $showData['status'] ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="button" wire:click="closeShow" class="mt-3 sm:mt-0 w-full sm:w-auto px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
