<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('routers.index') }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $router->name }}</h1>
                        <p class="mt-1 text-sm text-gray-600">{{ $router->management_ip }} • {{ ucfirst($router->role) }}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex h-3 w-3 relative">
                    @if($router->status === 'online')
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    @elseif($router->status === 'degraded')
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span>
                    @else
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                    @endif
                </span>
                <span class="text-sm font-medium
                    @if($router->status === 'online') text-green-700
                    @elseif($router->status === 'degraded') text-yellow-700
                    @else text-red-700
                    @endif">
                    {{ ucfirst($router->status) }}
                </span>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    {{-- Quick Stats --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <div class="bg-white rounded-lg shadow p-2 min-w-[120px] flex-1 max-w-[160px]">
            <div class="text-xs text-gray-500">Active Sessions</div>
            <div class="text-xl font-bold text-gray-900">{{ $router->networkSessions()->where('active', true)->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-2 min-w-[120px] flex-1 max-w-[160px]">
            <div class="text-xs text-gray-500">Truth Sources</div>
            <div class="text-xl font-bold text-gray-900">{{ $router->truthSources()->where('enabled', true)->count() }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-2 min-w-[120px] flex-1 max-w-[160px]">
            <div class="text-xs text-gray-500">Last Seen</div>
            <div class="text-base font-bold text-gray-900">{{ $router->last_seen_at?->diffForHumans() ?? 'Never' }}</div>
        </div>
        <div class="bg-white rounded-lg shadow p-2 min-w-[120px] flex-1 max-w-[160px]">
            <div class="text-xs text-gray-500">Poll Failures</div>
            <div class="text-xl font-bold {{ $router->poll_failures > 0 ? 'text-red-600' : 'text-gray-900' }}">
                {{ $router->poll_failures }}
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="bg-white rounded-lg shadow">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button wire:click="switchTab('overview')" 
                        class="px-6 py-3 border-b-2 font-medium text-sm {{ $activeTab === 'overview' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Overview
                </button>
                <button wire:click="switchTab('truth-sources')" 
                        class="px-6 py-3 border-b-2 font-medium text-sm {{ $activeTab === 'truth-sources' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Data Sources
                </button>
                <button wire:click="switchTab('snapshots')" 
                        class="px-6 py-3 border-b-2 font-medium text-sm {{ $activeTab === 'snapshots' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Raw Data
                </button>
                <button wire:click="switchTab('sessions')" 
                        class="px-6 py-3 border-b-2 font-medium text-sm {{ $activeTab === 'sessions' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    Active Sessions
                </button>
            </nav>
        </div>

        <div class="p-6">
            {{-- Overview Tab --}}
            @if($activeTab === 'overview')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Router Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="text-sm text-gray-900">{{ $router->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Site / Location</dt>
                                <dd class="text-sm text-gray-900">{{ $router->site ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Management IP</dt>
                                <dd class="text-sm text-gray-900">{{ $router->management_ip }}:{{ $router->api_port }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Role</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($router->role) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ownership</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $router->ownership)) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Connection Method</dt>
                                <dd class="text-sm text-gray-900">{{ strtoupper($router->connection_method) }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-4">System Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Identity</dt>
                                <dd class="text-sm text-gray-900">{{ $router->identity ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Serial Number</dt>
                                <dd class="text-sm text-gray-900">{{ $router->serial_number ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Board Name</dt>
                                <dd class="text-sm text-gray-900">{{ $router->board_name ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">OS Type</dt>
                                <dd class="text-sm text-gray-900">{{ strtoupper($router->os_type) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">OS Version</dt>
                                <dd class="text-sm text-gray-900">{{ $router->os_version ?? 'Unknown' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Uplink Type</dt>
                                <dd class="text-sm text-gray-900">{{ $router->uplink_type ?? 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold mb-4">Capabilities</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($router->capability_list as $capability)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                    {{ $capability }}
                                </span>
                            @endforeach
                            @if(empty($router->capability_list))
                                <span class="text-gray-400">No capabilities configured</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Truth Sources Tab --}}
            @if($activeTab === 'truth-sources')
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Data Sources Configuration</h3>
                        <button wire:click="openAddSourceModal" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            Add Source
                        </button>
                    </div>

                    <div class="space-y-4">
                        @forelse($truthSources as $source)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <h4 class="font-medium text-gray-900">{{ str_replace('_', ' ', ucwords($source->source, '_')) }}</h4>
                                            <span class="px-2 py-1 text-xs rounded-full {{ $source->enabled ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $source->enabled ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </div>
                                        <div class="mt-2 grid grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">Poll Interval:</span>
                                                <span class="font-medium">{{ $source->poll_interval_seconds }}s</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Last Polled:</span>
                                                <span class="font-medium">{{ $source->last_polled_at?->diffForHumans() ?? 'Never' }}</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Failures:</span>
                                                <span class="font-medium {{ $source->failures > 0 ? 'text-red-600' : '' }}">{{ $source->failures }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-2">
                                        <button wire:click="toggleSource({{ $source->id }})" 
                                                class="text-sm {{ $source->enabled ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700' }}">
                                            {{ $source->enabled ? 'Disable' : 'Enable' }}
                                        </button>
                                        <button wire:click="deleteSource({{ $source->id }})" 
                                                onclick="return confirm('Remove this data source?')"
                                                class="text-sm text-red-600 hover:text-red-700">
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                <p>No data sources configured</p>
                                <p class="text-sm mt-1">Add sources to start collecting network data</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endif

            {{-- Snapshots Tab --}}
            @if($activeTab === 'snapshots')
                <div>
                    <h3 class="text-lg font-semibold mb-4">Raw Data Snapshots</h3>
                    <div class="space-y-4">
                        @forelse($snapshots as $snapshot)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="font-medium text-gray-900">{{ str_replace('_', ' ', ucwords($snapshot->source, '_')) }}</span>
                                        <span class="ml-2 text-sm text-gray-500">{{ $snapshot->captured_at->format('Y-m-d H:i:s') }}</span>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $snapshot->success ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $snapshot->success ? 'Success' : 'Failed' }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-3 gap-4 text-sm mb-2">
                                    <div>
                                        <span class="text-gray-500">Records:</span>
                                        <span class="font-medium">{{ $snapshot->record_count ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500">Duration:</span>
                                        <span class="font-medium">{{ $snapshot->duration_ms ?? 'N/A' }}ms</span>
                                    </div>
                                    <div>
                                        @if(!$snapshot->success && $snapshot->error_message)
                                            <span class="text-red-600 text-xs">{{ $snapshot->error_message }}</span>
                                        @endif
                                    </div>
                                </div>
                                <details class="mt-2">
                                    <summary class="text-sm text-blue-600 cursor-pointer hover:text-blue-700">View Raw Payload</summary>
                                    <pre class="mt-2 p-3 bg-gray-50 rounded text-xs overflow-x-auto">{{ json_encode($snapshot->payload, JSON_PRETTY_PRINT) }}</pre>
                                </details>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">
                                No snapshots available
                            </div>
                        @endforelse
                    </div>
                    @if($snapshots instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $snapshots->links() }}
                        </div>
                    @endif
                </div>
            @endif

            {{-- Active Sessions Tab --}}
            @if($activeTab === 'sessions')
                <div>
                    <h3 class="text-lg font-semibold mb-4">Connected Users</h3>
                    <form wire:submit.prevent="filterSessions" class="mb-4">
                        <div class="flex flex-wrap items-end gap-3">
                            <div class="min-w-[160px]">
                                <label for="session_mac" class="block text-xs font-medium text-gray-700">MAC Address</label>
                                <input type="text" id="session_mac" wire:model.defer="sessionFilters.mac_address" placeholder="MAC Address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 text-xs py-1 px-2">
                            </div>
                            <div class="min-w-[160px]">
                                <label for="session_username" class="block text-xs font-medium text-gray-700">Username</label>
                                <input type="text" id="session_username" wire:model.defer="sessionFilters.username" placeholder="Username" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 text-xs py-1 px-2">
                            </div>
                            <div class="min-w-[160px]">
                                <label for="session_status" class="block text-xs font-medium text-gray-700">Status</label>
                                <select id="session_status" wire:model.defer="sessionFilters.status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 text-xs py-1 px-2">
                                    <option value="">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">MAC Address</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Access Type</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Data Usage</th>
                                    <th class="px-2 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">Confidence</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($activeSessions as $session)
                                    <tr>
                                        <td class="px-2 py-1 whitespace-nowrap">{{ $session->mac_address }}</td>
                                        <td class="px-2 py-1 whitespace-nowrap">{{ $session->username ?? 'N/A' }}</td>
                                        <td class="px-2 py-1 whitespace-nowrap">{{ $session->ip_address ?? 'N/A' }}</td>
                                        <td class="px-2 py-1">
                                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst($session->access_type) }}
                                            </span>
                                        </td>
                                        <td class="px-2 py-1 whitespace-nowrap">
                                            {{ $session->started_at ? $session->started_at->diffForHumans(null, true) : 'N/A' }}
                                        </td>
                                        <td class="px-2 py-1 whitespace-nowrap">
                                            {{ number_format(($session->bytes_in + $session->bytes_out) / 1024 / 1024, 2) }} MB
                                        </td>
                                        <td class="px-2 py-1">
                                            <div class="flex items-center gap-2">
                                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $session->confidence_score }}%"></div>
                                                </div>
                                                <span class="text-xs text-gray-600">{{ $session->confidence_score }}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-2 py-6 text-center text-gray-500">
                                            No active sessions
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($activeSessions instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="mt-4">
                            {{ $activeSessions->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Add Truth Source Modal --}}
    @if($showAddSourceModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Add Data Source</h3>
                </div>
                
                <form wire:submit.prevent="addTruthSource" class="px-6 py-4">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Source Type</label>
                            <select wire:model="selectedSource" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">Select source...</option>
                                <option value="hotspot_active">Hotspot Active</option>
                                <option value="ppp_active">PPP Active</option>
                                <option value="dhcp_lease">DHCP Lease</option>
                                <option value="bridge_host">Bridge Host</option>
                                <option value="firewall_connection">Firewall Connection</option>
                                <option value="radius_accounting">RADIUS Accounting</option>
                            </select>
                            @error('selectedSource') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Poll Interval (seconds)</label>
                            <input type="number" wire:model="pollInterval" min="5" max="300" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('pollInterval') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" wire:model="sourceEnabled" id="sourceEnabled" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="sourceEnabled" class="ml-2 text-sm text-gray-700">Enable immediately</label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="closeAddSourceModal" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Add Source
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
