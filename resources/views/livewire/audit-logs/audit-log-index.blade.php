<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="w-full mx-0 px-2 md:px-6">
            <div class="flex items-center justify-between py-4">
                <div>
                    <h1 class="text-2xl font-bold text-black">Audit Logs</h1>
                    <p class="text-sm text-gray-500">Track all system activities and changes</p>
                </div>
                <button wire:click="resetFilters" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mx-6 mt-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text"
                        wire:model.live.debounce.300ms="search"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Search logs...">
                </div>

                <!-- Event Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                    <select wire:model.live="event"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Events</option>
                        @foreach($events as $eventType)
                            <option value="{{ $eventType }}">{{ ucfirst($eventType) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- User Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                    <select wire:model.live="user_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Model Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Module</label>
                    <select wire:model.live="model_type"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Modules</option>
                        @foreach($models as $model)
                            <option value="{{ $model }}">{{ $model }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                    <input type="date"
                        wire:model.live="date_from"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                    <input type="date"
                        wire:model.live="date_to"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Show</label>
                    <select wire:model.live="perPage"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Audit Logs Table -->
    <div class="mx-6 mt-6 mb-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Module</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $log->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $log->created_at->format('H:i:s') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->user)
                                        <div class="text-sm font-medium text-gray-900">{{ $log->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $log->user->email }}</div>
                                    @else
                                        <div class="text-sm text-gray-400 italic">{{ $log->user_name ?? 'System' }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($log->event === 'created') bg-green-100 text-green-800
                                        @elseif($log->event === 'updated') bg-blue-100 text-blue-800
                                        @elseif($log->event === 'deleted') bg-red-100 text-red-800
                                        @elseif($log->event === 'login') bg-purple-100 text-purple-800
                                        @elseif($log->event === 'logout') bg-gray-100 text-gray-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($log->event) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ class_basename($log->auditable_type) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ Str::limit($log->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $log->ip_address }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="viewDetails({{ $log->id }})"
                                        class="text-blue-600 hover:text-blue-900 transition-colors"
                                        title="View Details">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-lg font-medium mb-2">No audit logs found</p>
                                        <p class="text-sm">Try adjusting your filters</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Details Modal -->
    @if($showDetails && $selectedLog)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeDetails">
            <div class="relative top-20 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Audit Log Details</h3>
                    <button wire:click="closeDetails" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Event</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($selectedLog->event) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Timestamp</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedLog->created_at->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">User</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedLog->user?->name ?? $selectedLog->user_name ?? 'System' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">IP Address</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedLog->ip_address }}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Module</label>
                            <p class="mt-1 text-sm text-gray-900">{{ class_basename($selectedLog->auditable_type) }} #{{ $selectedLog->auditable_id }}</p>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $selectedLog->description }}</p>
                        </div>
                    </div>

                    <!-- Changes -->
                    @if($selectedLog->old_values || $selectedLog->new_values)
                        <div class="border-t pt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Changes</label>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                                @foreach($selectedLog->changes as $field => $values)
                                    <div class="flex items-start gap-4">
                                        <span class="text-sm font-medium text-gray-700 min-w-32">{{ ucfirst(str_replace('_', ' ', $field)) }}:</span>
                                        <div class="flex-1">
                                            <div class="text-sm text-red-600">
                                                <span class="font-medium">Old:</span> {{ is_array($values['old']) ? json_encode($values['old']) : $values['old'] ?? 'null' }}
                                            </div>
                                            <div class="text-sm text-green-600">
                                                <span class="font-medium">New:</span> {{ is_array($values['new']) ? json_encode($values['new']) : $values['new'] ?? 'null' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- User Agent -->
                    <div class="border-t pt-4">
                        <label class="block text-sm font-medium text-gray-700">User Agent</label>
                        <p class="mt-1 text-xs text-gray-600 break-all">{{ $selectedLog->user_agent }}</p>
                    </div>

                    <!-- URL -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">URL</label>
                        <p class="mt-1 text-xs text-gray-600 break-all">{{ $selectedLog->http_method }} {{ $selectedLog->url }}</p>
                    </div>
                </div>

                <div class="flex justify-end mt-6 pt-4 border-t border-gray-200">
                    <button wire:click="closeDetails"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
