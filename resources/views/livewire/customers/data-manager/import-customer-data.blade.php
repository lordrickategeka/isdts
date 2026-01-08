<div>
     <div class="p-4 border rounded-lg bg-white">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-900">Import Customers</h3>
                <div class="flex items-center gap-2">
                    <button wire:click="downloadTemplate"
                        class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">Download Template</button>
                </div>
            </div>

            <p class="text-xs text-gray-600 mb-3">Upload a CSV or Excel file with customer data. The file should follow the provided template.</p>

            <form wire:submit.prevent="importClients">
                <!-- Vendor Selection -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Vendor <span class="text-red-500">*</span></label>
                    <select wire:model="importVendorId"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Vendor</option>
                        @foreach($vendors ?? [] as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('importVendorId')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Customer Type Selection -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Type <span class="text-red-500">*</span></label>
                    <select wire:model="importCustomerType"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Customer Type</option>
                        <option value="home">Home</option>
                        <option value="company">Company</option>
                    </select>
                    @error('importCustomerType')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- File Upload -->
                <div class="mb-3">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Upload File <span class="text-red-500">*</span></label>
                    <input type="file" wire:model="importFile" accept=".csv,.txt,.xlsx,.xls"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('importFile')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div wire:loading wire:target="importFile" class="mb-2">
                    <p class="text-sm text-blue-600">Uploading file...</p>
                </div>

                @error('import')
                    <div class="mb-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                        {{ $message }}
                    </div>
                @enderror

                <div class="flex gap-2">
                    <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded text-sm"
                        wire:loading.attr="disabled" wire:target="importClients">
                        <span wire:loading.remove wire:target="importClients">Import</span>
                        <span wire:loading wire:target="importClients">Importing...</span>
                    </button>
                    <button type="button" wire:click="resetFile" class="px-3 py-2 bg-gray-100 rounded text-sm">Clear</button>
                </div>
            </form>
        </div>
</div>
