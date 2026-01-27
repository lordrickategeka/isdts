<div class="p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Network / Device APIs</h1>
        <p class="mt-1 text-sm text-gray-600">List of all manageable networks and devices integrated into the network.
        </p>
    </div>


    {{-- Tabs for Devices and Networks --}}
    <div class="mb-6">
        <nav class="flex gap-2 border-b border-gray-200">
            <button wire:click="$set('activeTab', 'networks')" type="button"
                class="px-4 py-2 -mb-px border-b-2 font-medium focus:outline-none {{ $activeTab === 'networks' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
                Networks
            </button>
            <button wire:click="$set('activeTab', 'devices')" type="button"
                class="px-4 py-2 -mb-px border-b-2 font-medium focus:outline-none {{ $activeTab === 'devices' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-blue-600' }}">
                Devices
            </button>
        </nav>
    </div>

    {{-- Tab Content --}}
    <div>
        @if ($activeTab === 'networks')
            {{-- Filters (shared for both tabs) --}}
            <div class="mb-6 bg-white rounded-lg shadow p-4">
                <div class="flex flex-wrap items-end gap-3">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Name, IP, Serial..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model.live="statusFilter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="all">All Status</option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
                        </select>
                    </div>
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
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($routers as $router)
                            <tr class="hover:bg-gray-50">
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
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span
                                        class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-800">{{ ucfirst($router->role) }}</span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full"
                                        style="background-color: {{ $router->status_color }}; color: white;">
                                        {{ ucfirst($router->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    @foreach ($router->capability_list as $cap)
                                        <span
                                            class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded mr-1 mb-1">{{ $cap }}</span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <span
                                        class="text-xs text-gray-700">{{ $router->last_seen_at?->diffForHumans() ?? 'Never' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-gray-400">No routers or devices
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @elseif($activeTab === 'devices')
            @livewire('routers.manage-routers', [], key('devices-manage-routers'))
        @endif
    </div>
</div>
