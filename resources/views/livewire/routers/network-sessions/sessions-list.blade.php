<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Connected Users / Active Sessions</h1>
        <p class="mt-1 text-sm text-gray-600">Monitor and manage network sessions across all routers</p>
    </div>

    {{-- Filters --}}
    <div class="mb-6 bg-white rounded-lg shadow p-4">
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="MAC, Username, IP..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Router</label>
                <select wire:model.live="routerFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Routers</option>
                    @foreach($routers as $router)
                        <option value="{{ $router->id }}">{{ $router->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Access Type</label>
                <select wire:model.live="accessTypeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="all">All Types</option>
                    <option value="hotspot">Hotspot</option>
                    <option value="ppp">PPP</option>
                    <option value="dhcp_only">DHCP Only</option>
                    <option value="unknown">Unknown</option>
                </select>
            </div>

            <div class="flex-1 min-w-[150px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select wire:model.live="activeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="all">All</option>
                    <option value="active">Active</option>
                    <option value="ended">Ended</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Sessions Table --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Router</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">MAC Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Data Usage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sessions as $session)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $session->router->name }}</div>
                            <div class="text-xs text-gray-500">{{ $session->router->site }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                            {{ $session->mac_address }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $session->username ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                            {{ $session->ip_address ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                @if($session->access_type === 'hotspot') bg-blue-100 text-blue-800
                                @elseif($session->access_type === 'ppp') bg-purple-100 text-purple-800
                                @elseif($session->access_type === 'dhcp_only') bg-green-100 text-green-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($session->access_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $session->started_at ? $session->started_at->diffForHumans(null, true) : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format(($session->bytes_in + $session->bytes_out) / 1024 / 1024, 2) }} MB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                @if($session->active)
                                    <span class="flex h-2 w-2 relative">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                    </span>
                                    <span class="text-xs text-green-700 font-medium">Active</span>
                                @else
                                    <span class="flex h-2 w-2 rounded-full bg-gray-400"></span>
                                    <span class="text-xs text-gray-700 font-medium">Ended</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button wire:click="viewSession({{ $session->id }})" 
                                    class="text-blue-600 hover:text-blue-900"
                                    title="View Details">
                                View Details
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            No sessions found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $sessions->links() }}
        </div>
    </div>

    {{-- Session Details Modal --}}
    @if($showDetailsModal && $selectedSession)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Session Details</h3>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <div class="px-6 py-4">
                    {{-- Session Info --}}
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Router</h4>
                            <p class="text-lg font-semibold">{{ $selectedSession->router->name }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium {{ $selectedSession->active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $selectedSession->active ? 'Active' : 'Ended' }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">MAC Address</h4>
                            <p class="font-mono">{{ $selectedSession->mac_address }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Username</h4>
                            <p>{{ $selectedSession->username ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">IP Address</h4>
                            <p class="font-mono">{{ $selectedSession->ip_address ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Access Type</h4>
                            <p>{{ ucfirst($selectedSession->access_type) }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Started At</h4>
                            <p>{{ $selectedSession->started_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Last Seen</h4>
                            <p>{{ $selectedSession->last_seen_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Data In</h4>
                            <p>{{ number_format($selectedSession->bytes_in / 1024 / 1024, 2) }} MB</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Data Out</h4>
                            <p>{{ number_format($selectedSession->bytes_out / 1024 / 1024, 2) }} MB</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Confidence Score</h4>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $selectedSession->confidence_score }}%"></div>
                                </div>
                                <span class="text-sm font-medium">{{ $selectedSession->confidence_score }}%</span>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Authenticated</h4>
                            <p>{{ $selectedSession->authenticated ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>

                    {{-- Timeline / Events --}}
                    @if($selectedSession->events->count() > 0)
                        <div class="border-t pt-6">
                            <h4 class="text-lg font-semibold mb-4">Session Timeline</h4>
                            <div class="space-y-3">
                                @foreach($selectedSession->events as $event)
                                    <div class="flex gap-4">
                                        <div class="flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ str_replace('_', ' ', ucwords($event->event_type, '_')) }}</p>
                                                    <p class="text-sm text-gray-500">{{ $event->source }}</p>
                                                    @if($event->reason)
                                                        <p class="text-sm text-gray-600 mt-1">{{ $event->reason }}</p>
                                                    @endif
                                                </div>
                                                <span class="text-xs text-gray-500">{{ $event->occurred_at->format('H:i:s') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Data Sources --}}
                    @if($selectedSession->sources)
                        <div class="border-t pt-6 mt-6">
                            <h4 class="text-lg font-semibold mb-4">Data Sources</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedSession->sources as $source => $value)
                                    @if($value)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                            {{ str_replace('_', ' ', ucwords($source, '_')) }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button wire:click="closeDetailsModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
