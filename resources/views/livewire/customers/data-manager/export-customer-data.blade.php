<div class="p-4 border rounded-lg bg-white">
    <h4 class="text-sm font-semibold mb-2">Export Customers</h4>
    <p class="text-xs text-gray-600 mb-4">Export current project customers to Excel. Select the fields you want to include in the export.</p>

    <!-- Field Selection -->
    <div class="mb-4">
        <div class="flex items-center justify-between mb-2">
            <label class="text-sm font-medium text-gray-700">Select Fields to Export</label>
            <div class="flex gap-2">
                <button type="button" wire:click="$set('exportFields', {
                    'customer_name': true, 'customer_type': true, 'phone': true, 'email': true,
                    'address': true, 'region': true, 'district': true, 'latitude': true,
                    'longitude': true, 'installation_engineer': true, 'vendor': true,
                    'transmission': true, 'capacity': true, 'capacity_type': true,
                    'username': true, 'serial_number': true, 'vlan': true,
                    'nrc': true, 'mrc': true, 'installation_date': true, 'status': true
                })" class="text-xs text-blue-600 hover:text-blue-800">Select All</button>
                <button type="button" wire:click="$set('exportFields', {
                    'customer_name': false, 'customer_type': false, 'phone': false, 'email': false,
                    'address': false, 'region': false, 'district': false, 'latitude': false,
                    'longitude': false, 'installation_engineer': false, 'vendor': false,
                    'transmission': false, 'capacity': false, 'capacity_type': false,
                    'username': false, 'serial_number': false, 'vlan': false,
                    'nrc': false, 'mrc': false, 'installation_date': false, 'status': false
                })" class="text-xs text-gray-600 hover:text-gray-800">Deselect All</button>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2 max-h-60 overflow-y-auto p-3 bg-gray-50 rounded border">
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.customer_name" class="mr-2">
                Customer Name
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.customer_type" class="mr-2">
                Customer Type
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.phone" class="mr-2">
                Phone
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.email" class="mr-2">
                Email
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.address" class="mr-2">
                Address
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.region" class="mr-2">
                Region
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.district" class="mr-2">
                District
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.latitude" class="mr-2">
                Latitude
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.longitude" class="mr-2">
                Longitude
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.installation_engineer" class="mr-2">
                Installation Engineer
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.vendor" class="mr-2">
                Vendor
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.transmission" class="mr-2">
                Transmission
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.capacity" class="mr-2">
                Capacity
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.capacity_type" class="mr-2">
                Capacity Type
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.username" class="mr-2">
                Username
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.serial_number" class="mr-2">
                Serial Number
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.vlan" class="mr-2">
                VLAN
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.nrc" class="mr-2">
                NRC
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.mrc" class="mr-2">
                MRC
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.installation_date" class="mr-2">
                Installation Date
            </label>
            <label class="flex items-center text-sm">
                <input type="checkbox" wire:model="exportFields.status" class="mr-2">
                Status
            </label>
        </div>
    </div>

    <div class="flex gap-2">
        <button wire:click="exportClients"
            class="px-3 py-2 bg-green-600 text-white rounded text-sm hover:bg-green-700"
            wire:loading.attr="disabled" wire:target="exportClients">
            <span wire:loading.remove wire:target="exportClients">Export to Excel</span>
            <span wire:loading wire:target="exportClients">Exporting...</span>
        </button>
    </div>

    @if(session()->has('error'))
        <div class="mt-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded text-sm">
            {{ session('error') }}
        </div>
    @endif
</div>
