<div class="p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Edit Router Details</h2>
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Router Name *</label>
                <input type="text" wire:model.defer="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Site / Location</label>
                <input type="text" wire:model.defer="site" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('site') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Management IP *</label>
                <input type="text" wire:model.defer="management_ip" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('management_ip') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">API Port *</label>
                <input type="number" wire:model.defer="api_port" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('api_port') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                <select wire:model.defer="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="core">Core</option>
                    <option value="distribution">Distribution</option>
                    <option value="access">Access</option>
                    <option value="cpe">CPE</option>
                    <option value="test">Test</option>
                </select>
                @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ownership *</label>
                <select wire:model.defer="ownership" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="managed">Managed</option>
                    <option value="customer_owned">Customer Owned</option>
                </select>
                @error('ownership') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Connection Method *</label>
                <select wire:model.defer="connection_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="api">API</option>
                    <option value="ssh">SSH</option>
                    <option value="radius">RADIUS</option>
                </select>
                @error('connection_method') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" wire:model.defer="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('username') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" wire:model.defer="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Use TLS</label>
                <input type="checkbox" wire:model.defer="use_tls" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                <div class="text-xs italic text-gray-500 mt-1">
                    No TLS &rarr; API runs on port 8728<br>
                    With TLS (API-SSL) &rarr; API runs on port 8729
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Timeout (seconds)</label>
                <input type="number" wire:model.defer="timeout_seconds" min="1" max="60" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                @error('timeout_seconds') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('routers.details', $router->id) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Changes</button>
        </div>
    </form>
</div>
