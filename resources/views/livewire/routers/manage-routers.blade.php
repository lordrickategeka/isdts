<div class="p-6">
    {{-- Header --}}
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Network Infrastructure</h1>
                <p class="mt-1 text-sm text-gray-600">Manage routers and network devices</p>
            </div>
            <button wire:click="openAddModal" type="button"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Router
            </button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="mb-6">
        <nav class="flex gap-2 border-b border-gray-200">
            <button wire:click="setTab('routers')"
                class="px-4 py-2 -mb-px border-b-2 font-medium focus:outline-none {{ $activeTab === 'routers' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
                Routers
            </button>
            <button wire:click="setTab('sessions')"
                class="px-4 py-2 -mb-px border-b-2 font-medium focus:outline-none {{ $activeTab === 'sessions' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
                Network Sessions
            </button>
        </nav>
    </div>

    {{-- Tab Content --}}
    <div>
        @if ($activeTab === 'routers')
            {{-- Existing router management UI below --}}

            {{-- Filters --}}
            <div class="mb-6 bg-white rounded-lg shadow p-4">
                <div class="flex flex-wrap items-end gap-3">
                    {{-- Search --}}
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Name, IP, Serial..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    {{-- Status Filter --}}
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model.live="statusFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="all">All Status</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>

                    {{-- Role Filter --}}
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select wire:model.live="roleFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="all">All Roles</option>
                            <option value="core">Core</option>
                            <option value="distribution">Distribution</option>
                            <option value="access">Access</option>
                            <option value="cpe">CPE</option>
                            <option value="test">Test</option>
                        </select>
                    </div>

                    {{-- Ownership Filter --}}
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ownership</label>
                        <select wire:model.live="ownershipFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="all">All</option>
                            <option value="managed">Managed</option>
                            <option value="customer_owned">Customer Owned</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Router List --}}
            <div class="mb-2">
                <span class="text-xs text-gray-500">[debug] testInProgress:
                    {{ var_export($testInProgress, true) }}</span>
            </div>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Router</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Capabilities</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Last Seen</th>
                            <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($routers as $router)
                            <tr class="hover:bg-gray-50" wire:key="router-{{ $router->id }}">
                                {{-- Router Info --}}
                                <td class="px-4 py-2">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-xs font-medium text-gray-900">{{ $router->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $router->management_ip }}</div>
                                            @if ($router->site)
                                                <div class="text-xs text-gray-400">{{ $router->site }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Role --}}
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full
                                        @if ($router->role === 'core') bg-purple-100 text-purple-800
                                        @elseif($router->role === 'distribution') bg-blue-100 text-blue-800
                                        @elseif($router->role === 'access') bg-green-100 text-green-800
                                        @elseif($router->role === 'cpe') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($router->role) }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5">
                                        <span class="flex h-2 w-2 relative">
                                            @if ($router->status === 'online')
                                                <span
                                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                            @elseif($router->status === 'degraded')
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-yellow-500"></span>
                                            @else
                                                <span
                                                    class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                                            @endif
                                        </span>
                                        <span
                                            class="text-xs font-medium
                                            @if ($router->status === 'online') text-green-700
                                            @elseif($router->status === 'degraded') text-yellow-700
                                            @else text-red-700 @endif">
                                            {{ ucfirst($router->status) }}
                                        </span>
                                    </div>
                                </td>

                                {{-- Capabilities --}}
                                <td class="px-4 py-2">
                                    <div class="flex gap-1 flex-wrap">
                                        @foreach ($router->capability_list as $capability)
                                            <span class="px-1.5 py-0.5 text-xs bg-gray-100 text-gray-700 rounded">
                                                {{ $capability }}
                                            </span>
                                        @endforeach
                                        @if (empty($router->capability_list))
                                            <span class="text-xs text-gray-400">Not configured</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Last Seen --}}
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500">
                                    @if ($router->last_seen_at)
                                        {{ $router->last_seen_at->diffForHumans() }}
                                    @else
                                        <span class="text-gray-400">Never</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-medium">
                                    <div class="flex justify-end gap-1.5">
                                        <button wire:click="viewRouter({{ $router->id }})"
                                            class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button wire:click="editRouter({{ $router->id }})"
                                            class="text-yellow-600 hover:text-yellow-900" title="Edit Router">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z" />
                                            </svg>
                                        </button>
                                        @can('can-delete-router-details')
                                            <button wire:click="deleteRouter({{ $router->id }})"
                                                class="text-red-600 hover:text-red-900" title="Delete Router"
                                                onclick="return confirm('Are you sure you want to delete this router?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        @endcan
                                        <button wire:click="testRouterConnection({{ $router->id }})"
                                            class="text-green-600 hover:text-green-900 relative"
                                            title="Test Connection" @if ($testInProgress === $router->id) disabled @endif>
                                            @if ($testInProgress === $router->id)
                                                <svg class="w-4 h-4 animate-spin inline-block mr-1" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8v8z"></path>
                                                </svg>
                                                <span class="text-xs">Testing...</span>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            @endif
                                        </button>
                                    </div>
                                    @if ($testInProgress === $router->id)
                                        <div class="w-full mt-2">
                                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-300">
                                                <div class="bg-blue-600 h-2.5 rounded-full animate-pulse"
                                                    style="width: 100%"></div>
                                            </div>
                                            <div class="text-xs text-blue-600 mt-1">Testing connection, please wait...
                                            </div>
                                        </div>
                                    @endif
                                    <button wire:click="pollNow({{ $router->id }})"
                                        class="text-indigo-600 hover:text-indigo-900" title="Poll Now">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19a7 7 0 100-14 7 7 0 000 14z" />
                                        </svg>
                                    </button>
                                    {{-- <button wire:click="toggleRouter({{ $router->id }})"
                                        class="{{ $router->is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}"
                                        title="{{ $router->is_active ? 'Disable' : 'Enable' }}">
                                        @if ($router->is_active)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </button> --}}
            </div>
            </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                    </svg>
                    <p class="mt-3 text-sm font-medium">No routers found</p>
                    <p class="mt-1 text-xs">Get started by adding your first router</p>
                </td>
            </tr>
        @endforelse
        </tbody>
        </table>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-gray-200">
            {{ $routers->links() }}
        </div>
    </div>

    {{-- Add Router Modal --}}
    @if ($showAddModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Add New Router</h3>
                </div>

                <form wire:submit.prevent="saveRouter" class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Router Name *</label>
                            <input type="text" wire:model="name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Site --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Site / Location</label>
                            <input type="text" wire:model="site"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('site')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Management IP --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Management IP *</label>
                            <input type="text" wire:model="management_ip"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('management_ip')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- API Port --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">API Port *</label>
                            <input type="number" wire:model="api_port"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('api_port')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                            <select wire:model="role"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="core">Core</option>
                                <option value="distribution">Distribution</option>
                                <option value="access">Access</option>
                                <option value="cpe">CPE</option>
                                <option value="test">Test</option>
                            </select>
                            @error('role')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Ownership --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ownership *</label>
                            <select wire:model="ownership"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="managed">Managed</option>
                                <option value="customer_owned">Customer Owned</option>
                            </select>
                            @error('ownership')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Connection Method --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Connection Method
                                *</label>
                            <select wire:model="connection_method"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="api">API</option>
                                <option value="ssh">SSH</option>
                                <option value="radius">RADIUS</option>
                            </select>
                            @error('connection_method')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Username --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                            <input type="text" wire:model="username"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('username')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" wire:model="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('password')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Use TLS --}}

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Use TLS</label>
                            <input type="checkbox" wire:model="use_tls"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <div class="text-xs italic text-gray-500 mt-1">
                                No TLS &rarr; API runs on port 8728<br>
                                With TLS (API-SSL) &rarr; API runs on port 8729
                            </div>
                        </div>

                        {{-- Timeout Seconds --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Timeout
                                (seconds)</label>
                            <input type="number" wire:model="timeout_seconds" min="1" max="60"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            @error('timeout_seconds')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" wire:click="closeAddModal"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Add Router
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Router Details Modal --}}
    @if ($showDetailsModal && $selectedRouter)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-900">Router Details</h3>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Router Name</h4>
                            <p class="text-lg font-semibold">{{ $selectedRouter->name }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                            <span
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-medium
                                        @if ($selectedRouter->status === 'online') bg-green-100 text-green-800
                                        @elseif($selectedRouter->status === 'degraded') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($selectedRouter->status) }}
                            </span>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Management IP</h4>
                            <p>{{ $selectedRouter->management_ip }}:{{ $selectedRouter->api_port }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Role</h4>
                            <p>{{ ucfirst($selectedRouter->role) }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Site</h4>
                            <p>{{ $selectedRouter->site ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Ownership</h4>
                            <p>{{ ucfirst(str_replace('_', ' ', $selectedRouter->ownership)) }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Connection Method</h4>
                            <p>{{ strtoupper($selectedRouter->connection_method) }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">OS Version</h4>
                            <p>{{ $selectedRouter->os_version ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Serial Number</h4>
                            <p>{{ $selectedRouter->serial_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 mb-1">Last Seen</h4>
                            <p>{{ $selectedRouter->last_seen_at?->format('Y-m-d H:i:s') ?? 'Never' }}</p>
                        </div>
                        <div class="col-span-2">
                            <h4 class="text-sm font-medium text-gray-500 mb-2">Capabilities</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($selectedRouter->capability_list as $capability)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ $capability }}
                                    </span>
                                @endforeach
                                @if (empty($selectedRouter->capability_list))
                                    <span class="text-gray-400">No capabilities configured</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                    <button wire:click="closeDetailsModal"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif
@elseif($activeTab === 'sessions')
    @livewire('routers.network-sessions.sessions-list')
    @endif
</div>
</div>
