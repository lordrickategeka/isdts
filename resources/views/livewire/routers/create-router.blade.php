<div class="p-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Network Infrastructure</h1>
                <p class="mt-1 text-sm text-gray-600">Manage routers and network devices</p>
            </div>
            <a href="{{ route('routers.index') }}"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Routers
            </a>
        </div>
    </div>
    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="max-w-3xl bg-white rounded-lg shadow p-6">

            {{-- Basic Information --}}
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-800 mb-2">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Tenant ID</label>
                        <input type="text" wire:model.defer="tenant_id" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('tenant_id')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Router Name *</label>
                        <input type="text" wire:model.defer="name" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Site / Location</label>
                        <input type="text" wire:model.defer="site" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('site')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Ownership *</label>
                        <select wire:model.defer="ownership" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                            <option value="">Select</option>
                            <option value="managed">Managed</option>
                            <option value="customer_owned">Customer Owned</option>
                        </select>
                        @error('ownership')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Identity</label>
                        <input type="text" wire:model.defer="identity" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('identity')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Serial Number</label>
                        <input type="text" wire:model.defer="serial_number" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('serial_number')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Board Name</label>
                        <input type="text" wire:model.defer="board_name" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('board_name')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Network & Access --}}
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-800 mb-2">Network & Access</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Management IP *</label>
                        <input type="text" wire:model.defer="management_ip" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('management_ip')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">API Port *</label>
                        <input type="number" wire:model.defer="api_port" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('api_port')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Connection Method *</label>
                        <select wire:model.defer="connection_method" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                            <option value="api">API</option>
                            <option value="ssh">SSH</option>
                            <option value="radius">RADIUS</option>
                        </select>
                        @error('connection_method')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" wire:model.defer="use_tls" id="use_tls" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="use_tls" class="text-xs text-gray-700">Use TLS</label>
                        @error('use_tls')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Timeout (seconds)</label>
                        <input type="number" wire:model.defer="timeout_seconds" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('timeout_seconds')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Username</label>
                        <input type="text" wire:model.defer="username" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('username')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" wire:model.defer="password" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('password')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Credential Ref</label>
                        <input type="text" wire:model.defer="credential_ref" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('credential_ref')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" wire:model.defer="rotate_credentials" id="rotate_credentials" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="rotate_credentials" class="text-xs text-gray-700">Rotate Credentials</label>
                        @error('rotate_credentials')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Credentials Rotated At</label>
                        <input type="datetime-local" wire:model.defer="credentials_rotated_at" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('credentials_rotated_at')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Role & Capabilities --}}
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-800 mb-2">Role & Capabilities</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Role *</label>
                        <select wire:model.defer="role" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                            <option value="core">Core</option>
                            <option value="distribution">Distribution</option>
                            <option value="access">Access</option>
                            <option value="cpe">CPE</option>
                            <option value="test">Test</option>
                        </select>
                        @error('role')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Uplink Type</label>
                        <input type="text" wire:model.defer="uplink_type" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('uplink_type')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Capabilities (comma separated)</label>
                        <input type="text" wire:model.defer="capabilities" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs" placeholder="e.g. snmp,netflow,api">
                        @error('capabilities')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" wire:model.defer="is_active" id="is_active" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                        <label for="is_active" class="text-xs text-gray-700">Is Active</label>
                        @error('is_active')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            {{-- Status & Metadata --}}
            <div class="mb-6">
                <h3 class="text-base font-semibold text-gray-800 mb-2">Status & Metadata</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Last Seen At</label>
                        <input type="datetime-local" wire:model.defer="last_seen_at" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('last_seen_at')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Last Polled At</label>
                        <input type="datetime-local" wire:model.defer="last_polled_at" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('last_polled_at')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Poll Failures</label>
                        <input type="number" wire:model.defer="poll_failures" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('poll_failures')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Disabled At</label>
                        <input type="datetime-local" wire:model.defer="disabled_at" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('disabled_at')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">OS Type</label>
                        <input type="text" wire:model.defer="os_type" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('os_type')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">OS Version</label>
                        <input type="text" wire:model.defer="os_version" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs">
                        @error('os_version')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Metadata (JSON)</label>
                        <textarea wire:model.defer="metadata" rows="2" class="w-full px-2 py-1 border border-gray-300 rounded focus:ring focus:ring-blue-200 text-xs"></textarea>
                        @error('metadata')<span class="text-red-500 text-xs">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-xs font-semibold">Save
                    Router</button>
            </div>
        </form>
    </div>
</div>
