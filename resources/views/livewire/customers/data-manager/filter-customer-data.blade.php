<div class="p-4 border rounded-lg bg-white">
    <div class="flex items-center justify-between mb-2">
        <h4 class="text-sm font-semibold">Filter Customers</h4>
        <!-- Results Count Badge -->
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                {{ $this->getClientCount() }} {{ $this->getClientCount() === 1 ? 'customer' : 'customers' }} found
            </span>
        </div>
    </div>
    <p class="text-xs text-gray-600 mb-4">Apply filters to refine the customer list displayed in the Project Sites tab.</p>

    <!-- Filter Form -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Customer Type Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Customer Type</label>
            <select wire:model.live="filterCustomerType"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <option value="">All Types</option>
                <option value="Home">Home</option>
                <option value="Corporate">Corporate</option>
            </select>
        </div>

        <!-- Status Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
            <select wire:model.live="filterStatus"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>

        <!-- Region Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Region</label>
            <select wire:model.live="filterRegion"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <option value="">All Regions</option>
                @foreach ($regions as $id => $regionName)
                    <option value="{{ $regionName }}">{{ $regionName }}</option>
                @endforeach
            </select>
        </div>

        <!-- District Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">District</label>
            <select wire:model.live="filterDistrict"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all {{ !$filterRegion ? 'bg-gray-50 cursor-not-allowed' : '' }}"
                {{ !$filterRegion ? 'disabled' : '' }}>
                <option value="">All Districts</option>
                @foreach ($filterDistricts as $districtName)
                    <option value="{{ $districtName }}">{{ $districtName }}</option>
                @endforeach
            </select>
            @if (!$filterRegion)
                <p class="mt-1 text-xs text-gray-500">Please select a region first</p>
            @endif
        </div>

        <!-- Vendor Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
            <select wire:model.live="filterVendor"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                <option value="">All Vendors</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Capacity Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
            <input type="text"
                wire:model.live.debounce.300ms="filterCapacity"
                placeholder="e.g., 10, 50, 100"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <!-- Transmission Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Transmission</label>
            <input type="text"
                wire:model.live.debounce.300ms="filterTransmission"
                placeholder="e.g., Fiber, Wireless"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <!-- VLAN Filter -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">VLAN</label>
            <input type="text"
                wire:model.live.debounce.300ms="filterVlan"
                placeholder="e.g., 100, 200"
                class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
        </div>

        <!-- Installation Date Range -->
        <div class="md:col-span-3">
            <label class="block text-sm font-medium text-gray-700 mb-2">Installation Date Range</label>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">From</label>
                    <input type="date"
                        wire:model.live="filterInstallationDateFrom"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">To</label>
                    <input type="date"
                        wire:model.live="filterInstallationDateTo"
                        class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 space-y-4">
        <!-- Applied Filters Summary -->
        @if ($filterStatus || $filterRegion || $filterDistrict || $filterVendor || $filterCustomerType || $filterCapacity || $filterTransmission || $filterVlan || $filterInstallationDateFrom || $filterInstallationDateTo)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span class="text-sm font-semibold text-blue-900">
                            Active Filters ({{ collect([$filterStatus, $filterRegion, $filterDistrict, $filterVendor, $filterCustomerType, $filterCapacity, $filterTransmission, $filterVlan, $filterInstallationDateFrom, $filterInstallationDateTo])->filter()->count() }})
                        </span>
                    </div>
                    <button wire:click="clearFilters"
                        class="text-xs font-medium text-red-600 hover:text-red-800 transition-colors flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Clear All
                    </button>
                </div>
                
                <!-- Filter Tags -->
                <div class="flex flex-wrap gap-2">
                    @if($filterCustomerType)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">Type:</span> {{ $filterCustomerType }}
                            <button wire:click="$set('filterCustomerType', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($filterStatus)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">Status:</span> {{ ucfirst($filterStatus) }}
                            <button wire:click="$set('filterStatus', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($filterRegion)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">Region:</span> {{ $filterRegion }}
                            <button wire:click="$set('filterRegion', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($filterDistrict)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">District:</span> {{ $filterDistrict }}
                            <button wire:click="$set('filterDistrict', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($filterVendor)
                        @php
                            $vendorName = $vendors->firstWhere('id', $filterVendor)?->name ?? 'Unknown';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">Vendor:</span> {{ $vendorName }}
                            <button wire:click="$set('filterVendor', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($filterCapacity)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">Capacity:</span> {{ $filterCapacity }}
                            <button wire:click="$set('filterCapacity', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($filterTransmission)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">Transmission:</span> {{ $filterTransmission }}
                            <button wire:click="$set('filterTransmission', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($filterVlan)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">VLAN:</span> {{ $filterVlan }}
                            <button wire:click="$set('filterVlan', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($filterInstallationDateFrom)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">From:</span> {{ $filterInstallationDateFrom }}
                            <button wire:click="$set('filterInstallationDateFrom', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif

                    @if($filterInstallationDateTo)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white border border-blue-300 text-blue-800">
                            <span class="font-semibold mr-1">To:</span> {{ $filterInstallationDateTo }}
                            <button wire:click="$set('filterInstallationDateTo', '')" class="ml-2 hover:text-blue-900">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </span>
                    @endif
                </div>
            </div>
        @else
            <div class="text-center text-sm text-gray-500 py-3 bg-gray-50 rounded-lg">
                No filters applied
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-2 pt-2">
            <button wire:click="clearFilters"
                class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                Reset Filters
            </button>
        </div>
    </div>
</div>
