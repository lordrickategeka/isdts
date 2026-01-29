<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200 print:hidden">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-1xl font-bold text-black">Project Details</h1>
                <p class="text-sm text-gray-500">Comprehensive project information and status</p>
            </div>
            <a href="{{ route('projects.list') }}" class="text-1xl text-gray-600 hover:text-gray-800">
                ← Back to Projects
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-4 sm:p-6">
        <!-- Print Styles -->
        <style>
            @media print {
                @page {
                    size: A4;
                    margin: 10mm;
                }

                body {
                    margin: 0;
                    padding: 0;
                }

                body * {
                    visibility: hidden;
                }

                #print-content,
                #print-content * {
                    visibility: visible;
                }

                #print-content {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    font-size: 9px !important;
                    line-height: 1.2 !important;
                }

                #print-content h1 {
                    font-size: 12px !important;
                }

                #print-content h2 {
                    font-size: 11px !important;
                }

                #print-content table {
                    font-size: 8px !important;
                }

                #print-content .p-2,
                #print-content .p-3,
                #print-content .sm\:p-3 {
                    padding: 0.25rem !important;
                }

                #print-content .mb-2,
                #print-content .mb-3,
                #print-content .sm\:mb-3 {
                    margin-bottom: 0.25rem !important;
                }

                #print-content .py-2,
                #print-content .py-3,
                #print-content .sm\:py-3 {
                    padding-top: 0.25rem !important;
                    padding-bottom: 0.25rem !important;
                }

                .print\:hidden {
                    display: none !important;
                }

                .border-2 {
                    border-width: 1px !important;
                }

                #print-content>div {
                    page-break-inside: avoid;
                }

                #print-content img {
                    max-height: 40px !important;
                }
            }
        </style>

        {{-- tabs here --}}
        <div class="tabs">
            @include('livewire.projects.projects-tab-menu')

            <div class="mt-4">
                @if ($activeTab === 'project-sites')
                    <div>
                        <div class="mt-4">
                            <!-- Flash Messages -->
                            @if (session()->has('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"
                                    role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session()->has('error'))
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
                                    role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <!-- Project Clients / Sites Content -->
                            <div class="p-4">
                                <div class="max-w-7xl">

                                    <!-- Search and Filter Bar -->
                                    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                                        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                                            <div class="flex-1 w-full flex gap-2">
                                                <div class="relative flex-1">
                                                    <input type="text" wire:model.live.debounce.300ms="searchTerm"
                                                        class="w-full px-4 py-1.5 pl-9 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                        placeholder="Search by client name, phone, email, or region...">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                    </svg>
                                                    <div wire:loading wire:target="searchTerm"
                                                        class="absolute right-3 top-2">
                                                        <svg class="animate-spin h-4 w-4 text-blue-500"
                                                            xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                @if ($searchTerm)
                                                    <button wire:click="$set('searchTerm', '')"
                                                        class="px-4 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-1 transition-colors duration-200">
                                                        Clear Search
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <!-- Bulk Delete Button -->
                                                @if (count($selectedClients) > 0)
                                                    <button wire:click="bulkDeleteClients"
                                                        wire:confirm="Are you sure you want to delete {{ count($selectedClients) }} selected client(s)? This action cannot be undone."
                                                        class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg flex items-center gap-1.5 text-xs font-medium transition-colors duration-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete {{ count($selectedClients) }} Selected
                                                    </button>
                                                @endif
                                                <!-- Add Customer Button -->
                                                <button
                                                    class="px-2 py-1.5 border border-blue-300 rounded-lg hover:bg-blue-50 flex items-center gap-1.5 text-xs text-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Add Customer
                                                </button>
                                                <!-- Filter Button -->
                                                <button wire:click="toggleFilters"
                                                    class="px-2 py-1.5 border rounded-lg flex items-center gap-1.5 text-xs
                                                    {{ $showFilters ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 hover:bg-gray-50 text-gray-700' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                                    </svg>
                                                    <span>Filter</span>
                                                    <span wire:loading wire:target="toggleFilters"
                                                        class="inline-block w-3 h-3 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></span>
                                                    @if (
                                                        $filterStatus ||
                                                            $filterRegion ||
                                                            $filterDistrict ||
                                                            $filterVendor ||
                                                            $filterCustomerType ||
                                                            $filterCapacity ||
                                                            $filterTransmission ||
                                                            $filterVlan ||
                                                            $filterInstallationDateFrom ||
                                                            $filterInstallationDateTo)
                                                        <span
                                                            class="inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-blue-600 rounded-full">
                                                            {{ collect([$filterStatus, $filterRegion, $filterDistrict, $filterVendor, $filterCustomerType, $filterCapacity, $filterTransmission, $filterVlan, $filterInstallationDateFrom, $filterInstallationDateTo])->filter()->count() }}
                                                        </span>
                                                    @endif
                                                </button>
                                                <!-- Columns Button -->
                                                <div class="relative">
                                                    <button wire:click="toggleColumnSelector"
                                                        class="px-2 py-1.5 border rounded-lg flex items-center gap-1.5 text-xs
                                                        {{ $showColumnSelector ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-gray-300 hover:bg-gray-50 text-gray-700' }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                                                        </svg>
                                                        Columns
                                                    </button>

                                                    @if ($showColumnSelector)
                                                        <div
                                                            class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                                            <div class="p-3">
                                                                <div
                                                                    class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200">
                                                                    <h3 class="text-xs font-semibold text-gray-700">
                                                                        Show/Hide Columns</h3>
                                                                    <button wire:click="toggleColumnSelector"
                                                                        class="text-gray-400 hover:text-gray-600">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-4 h-4" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                <div class="space-y-2 max-h-96 overflow-y-auto">
                                                                    <!-- Default Columns (Always visible note) -->
                                                                    <div class="mb-2">
                                                                        <p class="text-xs text-gray-500 italic mb-2">
                                                                            Default columns:</p>
                                                                    </div>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.customer_type"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span class="text-xs text-gray-700">Customer
                                                                            Type</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.customer_name"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span class="text-xs text-gray-700">Customer
                                                                            Name</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.vendor"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span
                                                                            class="text-xs text-gray-700">Vendor</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.transmission"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span
                                                                            class="text-xs text-gray-700">Transmission</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.vlan"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span class="text-xs text-gray-700">VLAN</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.capacity"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span
                                                                            class="text-xs text-gray-700">Capacity</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.capacity_type"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span class="text-xs text-gray-700">Capacity
                                                                            Type</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.installation_date"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span
                                                                            class="text-xs text-gray-700">Installation
                                                                            Date</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.status"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span
                                                                            class="text-xs text-gray-700">Status</span>
                                                                    </label>

                                                                    <!-- Optional Columns -->
                                                                    <div class="mt-3 pt-2 border-t border-gray-200">
                                                                        <p class="text-xs text-gray-500 italic mb-2">
                                                                            Optional columns:</p>
                                                                    </div>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.contact_info"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span class="text-xs text-gray-700">Contact
                                                                            Info</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.location"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span
                                                                            class="text-xs text-gray-700">Location</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.coordinates"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span
                                                                            class="text-xs text-gray-700">Coordinates</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.nrc"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span class="text-xs text-gray-700">NRC</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.mrc"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span class="text-xs text-gray-700">MRC</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.username"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span
                                                                            class="text-xs text-gray-700">Username</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.serial_number"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span class="text-xs text-gray-700">Serial
                                                                            Number</span>
                                                                    </label>

                                                                    <label
                                                                        class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 p-1.5 rounded">
                                                                        <input type="checkbox"
                                                                            wire:model.live="visibleColumns.installation_engineer"
                                                                            class="w-3.5 h-3.5 text-blue-600 rounded focus:ring-blue-500">
                                                                        <span
                                                                            class="text-xs text-gray-700">Installation
                                                                            Engineer</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <!-- Import/Export Button -->
                                                <button wire:click="$set('activeTab', 'import-export')"
                                                    class="px-2 py-1.5 border border-blue-300 bg-blue-50 rounded-lg hover:bg-blue-100 flex items-center gap-1.5 text-xs text-blue-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                                    </svg>
                                                    Import/Export
                                                </button>
                                                <label class="text-xs text-gray-600">Show:</label>
                                                <select wire:model.live="perPage"
                                                    class="px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                    <option value="6">6</option>
                                                    <option value="10">10</option>
                                                    <option value="25">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Filter Drawer -->
                                        @if ($showFilters)
                                            <!-- Backdrop -->
                                            <div class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300"
                                                wire:click="toggleFilters"></div>

                                            <!-- Drawer -->
                                            <div
                                                class="fixed top-0 right-0 h-full w-96 bg-white shadow-2xl z-50 transform transition-transform duration-300 ease-in-out overflow-y-auto">
                                                <!-- Drawer Header -->
                                                <div
                                                    class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                                                    <div class="flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-5 h-5 text-blue-600" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                                        </svg>
                                                        <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                                                        @if (
                                                            $filterStatus ||
                                                                $filterRegion ||
                                                                $filterDistrict ||
                                                                $filterVendor ||
                                                                $filterCustomerType ||
                                                                $filterCapacity ||
                                                                $filterTransmission ||
                                                                $filterVlan ||
                                                                $filterInstallationDateFrom ||
                                                                $filterInstallationDateTo)
                                                            <span
                                                                class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold text-white bg-blue-600 rounded-full">
                                                                {{ collect([$filterStatus, $filterRegion, $filterDistrict, $filterVendor, $filterCustomerType, $filterCapacity, $filterTransmission, $filterVlan, $filterInstallationDateFrom, $filterInstallationDateTo])->filter()->count() }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <button wire:click="toggleFilters"
                                                        class="text-gray-400 hover:text-gray-600 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>

                                                <!-- Drawer Content -->
                                                <div class="px-6 py-6 space-y-6">
                                                    <!-- Customer Type Filter -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-semibold text-gray-700 mb-2">Customer
                                                            Type</label>
                                                        <select wire:model.live="filterCustomerType"
                                                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                            <option value="">All Types</option>
                                                            <option value="Home">Home</option>
                                                            <option value="Corporate">Corporate</option>
                                                        </select>
                                                    </div>

                                                    <!-- Status Filter -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
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
                                                        <label
                                                            class="block text-sm font-semibold text-gray-700 mb-2">Region</label>
                                                        <select wire:model.live="filterRegion"
                                                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                            <option value="">All Regions</option>
                                                            @foreach ($regions as $id => $regionName)
                                                                <option value="{{ $regionName }}">
                                                                    {{ $regionName }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- District Filter -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-semibold text-gray-700 mb-2">District</label>
                                                        <select wire:model.live="filterDistrict"
                                                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all {{ !$filterRegion ? 'bg-gray-50 cursor-not-allowed' : '' }}"
                                                            {{ !$filterRegion ? 'disabled' : '' }}>
                                                            <option value="">All Districts</option>
                                                            @foreach ($filterDistricts as $districtName)
                                                                <option value="{{ $districtName }}">
                                                                    {{ $districtName }}</option>
                                                            @endforeach
                                                        </select>
                                                        @if (!$filterRegion)
                                                            <p class="mt-1 text-xs text-gray-500">Please select a
                                                                region first</p>
                                                        @endif
                                                    </div>

                                                    <!-- Vendor Filter -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-semibold text-gray-700 mb-2">Vendor</label>
                                                        <select wire:model.live="filterVendor"
                                                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                            <option value="">All Vendors</option>
                                                            @foreach ($vendors as $vendor)
                                                                <option value="{{ $vendor->id }}">
                                                                    {{ $vendor->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Capacity Filter -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-semibold text-gray-700 mb-2">Capacity</label>
                                                        <input type="text"
                                                            wire:model.live.debounce.300ms="filterCapacity"
                                                            placeholder="e.g., 10, 50, 100"
                                                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                    </div>

                                                    <!-- Transmission Filter -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-semibold text-gray-700 mb-2">Transmission</label>
                                                        <input type="text"
                                                            wire:model.live.debounce.300ms="filterTransmission"
                                                            placeholder="e.g., Fiber, Wireless"
                                                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                    </div>

                                                    <!-- VLAN Filter -->
                                                    <div>
                                                        <label
                                                            class="block text-sm font-semibold text-gray-700 mb-2">VLAN</label>
                                                        <input type="text"
                                                            wire:model.live.debounce.300ms="filterVlan"
                                                            placeholder="e.g., 100, 200"
                                                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                    </div>

                                                    <!-- Installation Date Range -->
                                                    <div class="col-span-2">
                                                        <label
                                                            class="block text-sm font-semibold text-gray-700 mb-2">Installation
                                                            Date Range</label>
                                                        <div class="grid grid-cols-2 gap-3">
                                                            <div>
                                                                <label
                                                                    class="block text-xs text-gray-600 mb-1">From</label>
                                                                <input type="date"
                                                                    wire:model.live="filterInstallationDateFrom"
                                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                            </div>
                                                            <div>
                                                                <label
                                                                    class="block text-xs text-gray-600 mb-1">To</label>
                                                                <input type="date"
                                                                    wire:model.live="filterInstallationDateTo"
                                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Drawer Footer -->
                                                <div
                                                    class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4">
                                                    @if (
                                                        $filterStatus ||
                                                            $filterRegion ||
                                                            $filterDistrict ||
                                                            $filterVendor ||
                                                            $filterCustomerType ||
                                                            $filterCapacity ||
                                                            $filterTransmission ||
                                                            $filterVlan ||
                                                            $filterInstallationDateFrom ||
                                                            $filterInstallationDateTo)
                                                        <button wire:click="clearFilters"
                                                            class="w-full px-4 py-2.5 text-sm font-medium text-red-700 bg-red-50 border border-red-300 rounded-lg hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                                                            <div class="flex items-center justify-center gap-2">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="w-4 h-4" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                                Clear All Filters
                                                            </div>
                                                        </button>
                                                    @else
                                                        <div class="text-center text-sm text-gray-500 py-2">
                                                            No filters applied
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- filters menu --}}

                                    <!-- Clients Table -->
                                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                        <div class="overflow-x-auto">
                                            <table class="w-full">
                                                <thead class="bg-gray-50 border-b border-gray-200">
                                                    <tr>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">
                                                            <input type="checkbox" wire:model.live="selectAll"
                                                                class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                                        </th>
                                                        <th
                                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            #</th>
                                                        @if ($visibleColumns['customer_type'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Customer Type</th>
                                                        @endif
                                                        @if ($visibleColumns['customer_name'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Customer Name</th>
                                                        @endif
                                                        @if ($visibleColumns['contact_info'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Email/Phone</th>
                                                        @endif
                                                        @if ($visibleColumns['location'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                District/Region</th>
                                                        @endif
                                                        @if ($visibleColumns['coordinates'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Coordinates</th>
                                                        @endif
                                                        @if ($visibleColumns['vendor'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Vendor</th>
                                                        @endif
                                                        @if ($visibleColumns['transmission'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Transmission</th>
                                                        @endif
                                                        @if ($visibleColumns['nrc'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                NRC</th>
                                                        @endif
                                                        @if ($visibleColumns['mrc'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                MRC</th>
                                                        @endif
                                                        @if ($visibleColumns['vlan'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Vlan</th>
                                                        @endif
                                                        @if ($visibleColumns['capacity'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Capacity</th>
                                                        @endif
                                                        @if ($visibleColumns['capacity_type'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Capacity Type</th>
                                                        @endif
                                                        @if ($visibleColumns['username'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Username</th>
                                                        @endif
                                                        @if ($visibleColumns['serial_number'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Serial Number</th>
                                                        @endif
                                                        @if ($visibleColumns['installation_date'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Installation Date</th>
                                                        @endif
                                                        @if ($visibleColumns['installation_engineer'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Installer</th>
                                                        @endif
                                                        @if ($visibleColumns['status'])
                                                            <th
                                                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                                Status</th>
                                                        @endif
                                                        <th
                                                            class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white">
                                                    @forelse($projectClients as $index => $client)
                                                        @php
                                                            $service = $client->clientServices->first();
                                                            // Calculate proper index for pagination
                                                            $rowNumber =
                                                                ($projectClients->currentPage() - 1) *
                                                                    $projectClients->perPage() +
                                                                $loop->iteration;
                                                        @endphp
                                                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                                                            <td class="px-4 py-3 text-xs">
                                                                <input type="checkbox"
                                                                    wire:model.live="selectedClients"
                                                                    value="{{ $client->id }}"
                                                                    class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                                                            </td>
                                                            <td class="px-4 py-3 text-xs text-gray-900">
                                                                {{ $rowNumber }}</td>
                                                            @if ($visibleColumns['customer_type'])
                                                                <td class="px-4 py-3 text-xs">
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                                        {{ $client->category === 'company'
                                                                            ? 'bg-blue-100 text-blue-800'
                                                                            : ($client->category === 'government'
                                                                                ? 'bg-purple-100 text-purple-800'
                                                                                : 'bg-green-100 text-green-800') }}">
                                                                        {{ ucfirst($client->category_type ?? $client->category) }}
                                                                    </span>
                                                                </td>
                                                            @endif
                                                            @if ($visibleColumns['customer_name'])
                                                                <td class="px-4 py-3 text-xs">
                                                                    <div class="font-medium text-gray-900">
                                                                        {{ $client->customer_name }}</div>
                                                                </td>
                                                            @endif
                                                            @if ($visibleColumns['contact_info'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    <div class="font-medium text-gray-900">
                                                                        {{ $client->email ?? '-' }}</div>
                                                                    <div class="text-gray-500">
                                                                        {{ $client->phone ?? '-' }}</div>
                                                                </td>
                                                            @endif
                                                            @if ($visibleColumns['location'])
                                                                <td class="px-4 py-3 text-xs">
                                                                    <div class="font-medium text-gray-900">
                                                                        {{ $client->district ?? '-' }}</div>
                                                                    <div class="text-gray-500">
                                                                        {{ $client->region ?? '-' }}</div>
                                                                </td>
                                                            @endif
                                                            @if ($visibleColumns['coordinates'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    @if ($client->latitude && $client->longitude)
                                                                        {{ $client->latitude }},
                                                                        {{ $client->longitude }}
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            @endif
                                                            @if ($visibleColumns['vendor'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service->vendor->name ?? '-' }}</td>
                                                            @endif
                                                            @if ($visibleColumns['transmission'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service->service_type ?? '-' }}</td>
                                                            @endif
                                                            @if ($visibleColumns['nrc'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service && $service->nrc ? 'UGX ' . number_format($service->nrc, 0) : '-' }}
                                                                </td>
                                                            @endif
                                                            @if ($visibleColumns['mrc'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service && $service->mrc ? 'UGX ' . number_format($service->mrc, 0) : '-' }}
                                                                </td>
                                                            @endif
                                                            @if ($visibleColumns['vlan'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service->vlan ?? '-' }}</td>
                                                            @endif
                                                            @if ($visibleColumns['capacity'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service->capacity ?? '-' }}</td>
                                                            @endif
                                                            @if ($visibleColumns['capacity_type'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service->capacity_type ?? '-' }}</td>
                                                            @endif
                                                            @if ($visibleColumns['username'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service->username ?? '-' }}</td>
                                                            @endif
                                                            @if ($visibleColumns['serial_number'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service->serial_number ?? '-' }}</td>
                                                            @endif
                                                            @if ($visibleColumns['installation_date'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $service && $service->installation_date ? $service->installation_date->format('M d, Y H:i') : '-' }}
                                                                </td>
                                                            @endif
                                                            @if ($visibleColumns['installation_engineer'])
                                                                <td class="px-4 py-3 text-xs text-gray-900">
                                                                    {{ $client->contact_person ?? '-' }}</td>
                                                            @endif
                                                            @if ($visibleColumns['status'])
                                                                <td class="px-4 py-3 text-xs">
                                                                    <span
                                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                                        {{ $service && $service->status === 'active'
                                                                            ? 'bg-green-100 text-green-800'
                                                                            : ($service && $service->status === 'inactive'
                                                                                ? 'bg-red-100 text-red-800'
                                                                                : 'bg-yellow-100 text-yellow-800') }}">
                                                                        {{ $service ? ucfirst($service->status) : 'N/A' }}
                                                                    </span>
                                                                </td>
                                                            @endif
                                                            <td class="px-4 py-3 text-xs text-right">
                                                                <div class="flex items-center justify-end gap-2">
                                                                    <button class="text-blue-600 hover:text-blue-800"
                                                                        title="View">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-4 h-4" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                        </svg>
                                                                    </button>
                                                                    <button class="text-green-600 hover:text-green-800"
                                                                        wire:click="editClient({{ $client->id }})"
                                                                        title="Edit">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-4 h-4" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                                        </svg>
                                                                    </button>
                                                                    <button class="text-red-600 hover:text-red-800"
                                                                        wire:click="deleteClient({{ $client->id }})"
                                                                        wire:confirm="Are you sure you want to delete this client? This action cannot be undone."
                                                                        title="Delete">
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-4 h-4" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="{{ count(array_filter($visibleColumns)) + 3 }}"
                                                                class="px-4 py-8 text-center text-gray-500">
                                                                <div class="flex flex-col items-center justify-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-12 h-12 text-gray-400 mb-2"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                                    </svg>
                                                                    <p class="text-sm">No Customers added to this
                                                                        project
                                                                        yet</p>
                                                                    <p class="text-xs mt-1">Add Customers using the
                                                                        "New
                                                                        Customers" tab</p>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Pagination -->
                                        <div class="px-6 py-4 border-t border-gray-200">
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm text-gray-600">
                                                    Showing {{ $projectClients->firstItem() ?? 0 }} to
                                                    {{ $projectClients->lastItem() ?? 0 }} of
                                                    {{ $projectClients->total() }} results
                                                </div>
                                                <div>
                                                    {{ $projectClients->links() }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary Stats -->
                                    <div class="mt-6 bg-white rounded-lg shadow-md p-4">
                                        <div class="flex items-center justify-between text-sm text-gray-600">
                                            <div>
                                                Showing <span
                                                    class="font-semibold text-gray-900">{{ $projectClients->firstItem() ?? 0 }}</span>
                                                to
                                                <span
                                                    class="font-semibold text-gray-900">{{ $projectClients->lastItem() ?? 0 }}</span>
                                                of
                                                <span
                                                    class="font-semibold text-gray-900">{{ $projectClients->total() }}</span>
                                                clients
                                            </div>
                                            <div>
                                                Total: <span
                                                    class="font-semibold text-gray-900">{{ $projectClients->total() }}</span>
                                                client(s)
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($activeTab === 'import-export')
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Import/Export Customer Data</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Import customers from CSV/Excel files or export current customer data.
                            </p>
                        </div>

                        <!-- Import/Export Sub-tabs -->
                        <div class="mb-4">
                            <div class="flex items-center gap-2 border-b border-gray-200">
                                <button wire:click="$set('importExportSubTab', 'import')"
                                    class="px-3 py-2 text-sm -mb-px {{ $importExportSubTab === 'import' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">Import</button>
                                <button wire:click="$set('importExportSubTab', 'export')"
                                    class="px-3 py-2 text-sm -mb-px {{ $importExportSubTab === 'export' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">Export</button>
                                <button wire:click="$set('importExportSubTab', 'filters')"
                                    class="px-3 py-2 text-sm -mb-px {{ $importExportSubTab === 'filters' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">Filters</button>
                                <button wire:click="$set('importExportSubTab', 'templates')"
                                    class="px-3 py-2 text-sm -mb-px {{ $importExportSubTab === 'templates' ? 'border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">Templates</button>
                            </div>
                        </div>

                        <div class="mt-4">
                            {{-- Alerts for Import/Export --}}
                            @if (session()->has('success'))
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session()->has('error'))
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session()->has('message'))
                                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4" role="alert">
                                    {{ session('message') }}
                                </div>
                            @endif

                            @if($importExportSubTab === 'import')
                                <div class="flex flex-col md:flex-row gap-6">
                                    <div class="flex-1">
                                        @include('livewire.customers.data-manager.import-customer-data', ['projectId' => $projectId])
                                    </div>
                                    @if (!empty($importConflicts))
                                        <div class="flex-1">
                                            <div x-data="{ showConflicts: true }" class="">
                                                <button type="button" @click="showConflicts = !showConflicts"
                                                    class="mb-2 px-3 py-1 bg-yellow-100 text-yellow-800 border border-yellow-300 rounded hover:bg-yellow-200">
                                                    <span x-show="!showConflicts">Show Conflicts</span>
                                                    <span x-show="showConflicts">Hide Conflicts</span>
                                                </button>
                                                <div x-show="showConflicts">
                                                    @include('livewire.customers.data-manager.import-conflicts', [
                                                        'conflicts' => $importConflicts,
                                                        'importConflictsPage' => $importConflictsPage,
                                                        'importConflictsPerPage' => $importConflictsPerPage
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @elseif($importExportSubTab === 'export')
                                @include('livewire.customers.data-manager.export-customer-data', ['projectId' => $projectId])
                            @elseif($importExportSubTab === 'filters')
                                @include('livewire.customers.data-manager.filter-customer-data', ['projectId' => $projectId])
                            @elseif($importExportSubTab === 'templates')
                                @include('livewire.customers.data-manager.customer-data-templates', ['projectId' => $projectId])
                            @endif
                        </div>
                    </div>
                @elseif ($activeTab === 'project-summary')
                    <div>
                        <div class="mt-4">
                            {{-- <h2 class="text-lg font-semibold text-gray-800">Current Project Details</h2> --}}
                            <div class="max-w-4xl">
                                <table class="w-full mt-2 border border-gray-300 text-xs sm:text-sm text-gray-600">
                                    <tbody>
                                        <tr>
                                            <td
                                                class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/4">
                                                Project Code:</td>
                                            <td class="border border-gray-300 px-2 py-1 w-3/4">{{ $project_code }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Project Name:</td>
                                            <td class="border border-gray-300 px-2 py-1">{{ $name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Status:</td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <span
                                                    class="inline-block px-2 py-0.5 text-[9px] rounded font-semibold {{ $this->getStatusBadgeClass($status) }}">
                                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Priority:</td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <span
                                                    class="inline-block px-2 py-0.5 text-[9px] rounded font-semibold {{ $this->getPriorityBadgeClass($priority) }}">
                                                    {{ ucwords($priority) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Start
                                                Date:</td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                {{ $start_date ? $start_date->format('M d, Y') : 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">End
                                                Date:</td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                {{ $end_date ? $end_date->format('M d, Y') : 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Estimated Budget:</td>
                                            <td class="border border-gray-300 px-2 py-1">UGX
                                                {{ number_format((float) $estimated_budget, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Actual
                                                Budget:</td>
                                            <td class="border border-gray-300 px-2 py-1">UGX
                                                {{ number_format((float) $actual_budget, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Created By:</td>
                                            <td class="border border-gray-300 px-2 py-1">{{ $creator->name ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @elseif($activeTab === 'new-client')
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900">
                                {{ $isEditMode ? 'Edit Client' : 'Add New Client to Project' }}</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                {{ $isEditMode ? 'Update the client details below.' : 'Fill in the client details below to add them to this project.' }}
                            </p>
                        </div>

                        @if (session()->has('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"
                                role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @error('save')
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
                                role="alert">
                                {{ $message }}
                            </div>
                        @enderror

                        <form wire:submit.prevent="saveClient">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Row 1: Customer Type, Customer Name, Phone -->
                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Customer Type</label>
                                    <select wire:model.defer="customer_type"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Select Customer Type --</option>
                                        <option value="Home">Home</option>
                                        <option value="Corporate">Corporate</option>
                                    </select>
                                    @error('customer_type')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Customer Name *</label>
                                    <input wire:model.defer="client_name" type="text"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('client_name')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Phone</label>
                                    <input wire:model.defer="phone" type="number"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('phone')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Row 2: Email, Region, District -->
                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Email</label>
                                    <input wire:model.defer="email" type="email"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('email')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Region</label>
                                    <select wire:model.live="region"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Select Region --</option>
                                        @foreach ($regions as $id => $regionName)
                                            <option value="{{ $regionName }}">{{ $regionName }}</option>
                                        @endforeach
                                    </select>
                                    @error('region')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700">District</label>
                                    <select wire:model.defer="district"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        @if (empty($region)) disabled @endif>
                                        <option value="">-- Select District --</option>
                                        @foreach ($districts as $id => $districtName)
                                            <option value="{{ $districtName }}">{{ $districtName }}</option>
                                        @endforeach
                                    </select>
                                    @if (empty($region))
                                        <div class="text-gray-500 text-xs mt-1">Please select a region first</div>
                                    @endif
                                    @error('district')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Row 3: Coordinates -->
                                <div class="md:col-span-3">
                                    <label class="text-xs font-semibold text-gray-700">Coordinates (Latitude /
                                        Longitude)</label>
                                    <div class="flex gap-2 mt-1">
                                        <input wire:model.defer="latitude" type="number" step="any"
                                            placeholder="Latitude"
                                            class="w-1/2 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        <input wire:model.defer="longitude" type="number" step="any"
                                            placeholder="Longitude"
                                            class="w-1/2 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                    @error('latitude')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                    @error('longitude')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Row 4: Vendor, Transmission, Capacity -->
                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Vendor</label>
                                    <select wire:model="vendor_id"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Select vendor --</option>
                                        @foreach ($vendors as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('vendor_id')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Transmission</label>
                                    <select wire:model.defer="transmission"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">-- Select Transmission --</option>
                                        @php
                                            // Get all products from vendors that have "Internet" services
                                            $internetProducts = \App\Models\Product::whereHas(
                                                'vendorService',
                                                function ($query) {
                                                    $query
                                                        ->where('service_name', 'like', '%Internet%')
                                                        ->orWhere('service_name', 'like', '%internet%');
                                                },
                                            )
                                                ->with('vendorService.vendor')
                                                ->get()
                                                ->unique('name');
                                        @endphp
                                        @foreach ($internetProducts as $product)
                                            <option value="{{ $product->id }}">{{ $product->name ?? '' }}</option>
                                        @endforeach
                                    </select>
                                    @error('transmission')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Capacity</label>
                                    <input wire:model.defer="capacity" type="number"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('capacity')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Row 5: VLAN -->
                                <div>
                                    <label class="text-xs font-semibold text-gray-700">VLAN</label>
                                    <input wire:model.defer="vlan" type="number"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('vlan')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Row 6: NRC, MRC, Installation Date -->
                                <div>
                                    <label class="text-xs font-semibold text-gray-700">NRC (Installation
                                        Charge)</label>
                                    <input wire:model.defer="nrc" type="number"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('nrc')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700">MRC (Monthly Charge)</label>
                                    <input wire:model.defer="mrc" type="number" step="0.01"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('mrc')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Installation Date</label>
                                    <input wire:model.defer="installation_date" type="date"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('installation_date')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Row 7: Installation Engineer, Status -->
                                <div>
                                    <label class="text-xs font-semibold text-gray-700">Installation Engineer /
                                        Leader</label>
                                    <input wire:model.defer="installation_engineer" type="text"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                    @error('installation_engineer')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-xs font-semibold text-gray-700">Status</label>
                                    <select wire:model.defer="client_status"
                                        class="w-full mt-1 border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                    @error('client_status')
                                        <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex items-center gap-3">
                                <button type="submit"
                                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium flex items-center gap-2">
                                    <svg wire:loading.remove wire:target="saveClient"
                                        xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    <svg wire:loading wire:target="saveClient" class="animate-spin w-5 h-5"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                        </path>
                                    </svg>
                                    <span wire:loading.remove
                                        wire:target="saveClient">{{ $isEditMode ? 'Update Client' : 'Add Client' }}</span>
                                    <span wire:loading
                                        wire:target="saveClient">{{ $isEditMode ? 'Updating...' : 'Adding...' }}</span>
                                </button>
                                <button type="button" wire:click="resetClientForm"
                                    class="px-6 py-2 border border-gray-300 hover:bg-gray-50 rounded-lg text-sm text-gray-700">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                @elseif($activeTab === 'feasibility-details')
                    <div>
                        <div class="flex flex-row gap-4">
                            <!-- Left: Feasibility Form -->
                            <div class="bg-white rounded-lg shadow-md p-4 w-1/2">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Add Vendor</h3>
                                <form wire:submit.prevent="addVendor" class="space-y-4">
                                    <div>
                                        <label for="vendor" class="block text-sm font-medium text-gray-700">Vendor
                                            *</label>
                                        <select id="vendor" wire:model.live="vendor"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select Vendor</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('vendor')
                                            <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="productService"
                                            class="block text-sm font-medium text-gray-700">Vendor
                                            Product/Service</label>
                                        <select id="productService" wire:model="productService"
                                            wire:key="product-service-{{ $vendor ?? 'none' }}"
                                            wire:loading.attr="disabled" wire:target="vendor"
                                            @if (!$vendor) disabled @endif
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select Product/Service</option>
                                            @php($__svcList = $productsServices ?? collect())
                                            @if ($__svcList->isEmpty())
                                                <option value="">No services available for selected vendor
                                                </option>
                                            @else
                                                @foreach ($__svcList as $svc)
                                                    <option value="{{ $svc->id }}">
                                                        {{ $svc->service_name ?? ($svc->name ?? 'Service') }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="ml-2 inline-block" wire:loading wire:target="vendor">
                                            <svg class="animate-spin h-4 w-4 text-gray-600"
                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </span>
                                        <div class="mt-2 text-xs text-gray-500">Services found:
                                            {{ ($productsServices ?? collect())->count() }}</div>
                                        @error('vendorService')
                                            <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="nrcCost" class="block text-sm font-medium text-gray-700">NRC Cost
                                            (Non-Recurring Cost) *</label>
                                        <input type="number" id="nrcCost" wire:model="nrcCost"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('nrcCost')
                                            <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="mrcCost" class="block text-sm font-medium text-gray-700">MRC Cost
                                            (Monthly Recurring Cost) *</label>
                                        <input type="number" id="mrcCost" wire:model="mrcCost"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('mrcCost')
                                            <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="notes"
                                            class="block text-sm font-medium text-gray-700">Notes</label>
                                        <textarea id="notes" wire:model="notes" rows="4"
                                            class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700"
                                            placeholder="Additional notes..."></textarea>
                                        @error('notes')
                                            <span class="text-xs text-red-600">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="flex justify-end space-x-2">
                                        <button type="button" wire:click="resetForm"
                                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-sm">Cancel</button>
                                        <button type="submit"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">Add
                                            Vendor</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Right: Vendors Table -->
                            <div class="bg-white rounded-lg shadow-md p-4 w-1/2">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Added Vendors</h3>

                                <div class="overflow-x-auto">
                                    <table class="w-full border border-gray-200 rounded-lg">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Vendor</th>
                                                <th
                                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                                    Service</th>
                                                <th
                                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                    NRC</th>
                                                <th
                                                    class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                                    MRC</th>
                                                <th
                                                    class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">
                                                    Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td colspan="5"
                                                    class="px-4 py-8 text-center text-sm text-gray-500">
                                                    No vendors added yet
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @elseif($activeTab === 'materials')
                    <div>
                        <!-- Materials Sub-tabs -->
                        <div class="bg-white border-b border-gray-200 mb-4">
                            <div class="flex space-x-1 px-4">
                                <button wire:click="$set('materialsSubTab', 'budget-items')"
                                    class="py-3 px-4 text-sm font-medium transition-colors {{ (!isset($materialsSubTab) || $materialsSubTab === 'budget-items') ? 'text-blue-600 border-b-2 border-blue-500' : 'text-gray-600 hover:text-gray-800' }}">
                                    Add Budget Items
                                </button>
                                <button wire:click="$set('materialsSubTab', 'item-availability')"
                                    class="py-3 px-4 text-sm font-medium transition-colors {{ (isset($materialsSubTab) && $materialsSubTab === 'item-availability') ? 'text-blue-600 border-b-2 border-blue-500' : 'text-gray-600 hover:text-gray-800' }}">
                                    Check Item Availability
                                </button>
                            </div>
                        </div>

                        <!-- Materials Sub-tab Content -->
                        @if(!isset($materialsSubTab) || $materialsSubTab === 'budget-items')
                            <div>
                                @livewire('projects.project-budget', ['project' => $project])
                            </div>
                        @elseif($materialsSubTab === 'item-availability')
                            <div>
                                @livewire('projects.project-item-availability', ['project' => $project])
                            </div>
                        @endif
                    </div>
                @elseif($activeTab === 'cost-summary')
                    <div>
                        <!-- Cost Summary Content -->
                        <p>Cost summary details go here...</p>
                    </div>
                @elseif($activeTab === 'project-milestones')
                    <div>
                        <livewire:projects.advanced-details.milestones-component :projectId="$projectId" :key="'milestones-'.$projectId" />
                    </div>
                @elseif($activeTab === 'project-tasks')
                    <div>
                        <livewire:projects.advanced-details.tasks-component :projectId="$projectId" :key="'tasks-'.$projectId" />
                    </div>
                @elseif($activeTab === 'project-hierarchy')
                    <div>
                        <livewire:projects.advanced-details.ownership-governance-component :projectId="$projectId" :key="'ownership-'.$projectId" />
                    </div>

                @elseif($activeTab === 'print-content')
                    <div id="print-content" class="max-w-7xl mx-auto text-xs sm:text-sm">
                        <!-- Main Project Document -->
                        <div class="w-full">
                            <!-- Document Header -->
                            <div class="border-2 border-gray-300 p-2 sm:p-3 bg-white">
                                <div class="flex flex-row items-center justify-between mb-2 gap-4">
                                    <div class="flex items-center gap-2 sm:gap-4">
                                        <img src="{{ asset('images/blue_crane_communications_u_ltd_logo.jpg') }}"
                                            alt="Blue Crane Communications Logo" class="h-16 sm:h-20 object-contain">
                                    </div>
                                    <div class="text-left flex-1">
                                        <h1 class="text-sm sm:text-lg font-bold">Blue Crane Communications (U) Ltd</h1>
                                        <p class="text-[10px] sm:text-xs text-gray-600">1st Floor Nyonyi Gardens,
                                            Kololo
                                        </p>
                                        <p class="text-[10px] sm:text-xs text-gray-600">Plot 16/17, P.O. Box 7493,
                                            Kampala</p>
                                        <p class="text-[10px] sm:text-xs text-gray-600">Tel: +256 200 306200 / +256
                                            777021479</p>
                                        <p class="text-[10px] sm:text-xs text-gray-600">info@bcc.co.ug www.bcc.co.ug
                                        </p>
                                    </div>
                                </div>

                                <div class="border-t-2 border-b-2 border-gray-300 py-0 px-0 text-center mb-2">
                                    <u>
                                        <h2 class="text-sm sm:text-base font-bold uppercase">PROJECT DETAILS</h2>
                                        <p class="text-right text-sm sm:text-base font-bold">PROJECT No.:
                                            {{ $project_code }}</p>
                                    </u>
                                </div>

                                <div class="mb-2 text-right">
                                    <div class="text-[10px] sm:text-xs text-gray-600">
                                        CREATED DATE: <u
                                            class="ml-1 sm:ml-2">{{ $project->created_at->format('Y-m-d') }}</u>
                                    </div>
                                </div>

                                <!-- PROJECT OVERVIEW -->
                                <div class="mb-2 sm:mb-4">
                                    <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">PROJECT
                                        OVERVIEW</div>
                                    <table class="w-full border border-gray-300 text-xs">
                                        <tr>
                                            <td
                                                class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/4">
                                                Project Name:</td>
                                            <td class="border border-gray-300 px-2 py-1 w-3/4" colspan="3">
                                                {{ $name }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Status:</td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <span
                                                    class="inline-block px-2 py-0.5 text-[9px] rounded font-semibold {{ $this->getStatusBadgeClass($status) }}">
                                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                                </span>
                                            </td>
                                            <td
                                                class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/6">
                                                Priority:</td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                <span
                                                    class="inline-block px-2 py-0.5 text-[9px] rounded font-semibold {{ $this->getPriorityBadgeClass($priority) }}">
                                                    {{ ucwords($priority) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Start
                                                Date:</td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                {{ $start_date ? $start_date->format('M d, Y') : 'N/A' }}</td>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">End
                                                Date:</td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                {{ $end_date ? $end_date->format('M d, Y') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Estimated Budget:</td>
                                            <td class="border border-gray-300 px-2 py-1">UGX
                                                {{ number_format((float) $estimated_budget, 0) }}</td>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Actual
                                                Budget:</td>
                                            <td class="border border-gray-300 px-2 py-1">UGX
                                                {{ number_format((float) $actual_budget, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Created By:</td>
                                            <td class="border border-gray-300 px-2 py-1">
                                                {{ $creator->name ?? 'N/A' }}
                                            </td>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Total
                                                Items:</td>
                                            <td class="border border-gray-300 px-2 py-1">{{ $totalItems }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- DESCRIPTION -->
                                @if ($description)
                                    <div class="mb-2 sm:mb-4">
                                        <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">PROJECT
                                            DESCRIPTION</div>
                                        <div class="border border-gray-300 px-2 py-2 text-xs bg-white">
                                            {{ $description }}
                                        </div>
                                    </div>
                                @endif

                                <!-- CLIENT INFORMATION -->
                                @if ($client)
                                    <div class="mb-2 sm:mb-4">
                                        <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">CLIENT
                                            INFORMATION</div>
                                        <table class="w-full border border-gray-300 text-xs">
                                            <tr>
                                                <td
                                                    class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/4">
                                                    Company/Name:</td>
                                                <td class="border border-gray-300 px-2 py-1 w-3/4">
                                                    {{ $client->customer_name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                    Contact Person:</td>
                                                <td class="border border-gray-300 px-2 py-1">
                                                    {{ $client->contact_person }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                    Phone:</td>
                                                <td class="border border-gray-300 px-2 py-1">{{ $client->phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                    Email:</td>
                                                <td class="border border-gray-300 px-2 py-1">{{ $client->email }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                    Address:</td>
                                                <td class="border border-gray-300 px-2 py-1">{{ $client->address }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                @endif

                                <!-- OBJECTIVES -->
                                @if ($objectives)
                                    <div class="mb-2 sm:mb-4">
                                        <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">PROJECT
                                            OBJECTIVES</div>
                                        <div
                                            class="border border-gray-300 px-2 py-2 text-xs bg-white whitespace-pre-wrap">
                                            {{ $objectives }}</div>
                                    </div>
                                @endif

                                <!-- DELIVERABLES -->
                                @if ($deliverables)
                                    <div class="mb-2 sm:mb-4">
                                        <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">PROJECT
                                            DELIVERABLES</div>
                                        <div
                                            class="border border-gray-300 px-2 py-2 text-xs bg-white whitespace-pre-wrap">
                                            {{ $deliverables }}</div>
                                    </div>
                                @endif

                                <!-- BUDGET ITEMS -->
                                <div class="mb-4">
                                    <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">BUDGET ITEMS
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full border border-gray-300 text-xs min-w-max">
                                            <thead>
                                                <tr class="bg-gray-50">
                                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">#
                                                    </th>
                                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">Item
                                                        Name</th>
                                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                        Category</th>
                                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                        Description</th>
                                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                        Quantity</th>
                                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">Unit
                                                    </th>
                                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">Unit
                                                        Cost</th>
                                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                        Total Cost</th>
                                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                        Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($budgetItems as $index => $item)
                                                    <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                        <td class="border border-gray-300 px-2 py-1 text-center">
                                                            {{ $index + 1 }}</td>
                                                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                            {{ $item->item_name }}</td>
                                                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                            {{ $item->category ?? 'N/A' }}</td>
                                                        <td class="border border-gray-300 px-2 py-1">
                                                            {{ $item->description ?? '-' }}</td>
                                                        <td class="border border-gray-300 px-2 py-1 text-right">
                                                            {{ $item->quantity }}</td>
                                                        <td class="border border-gray-300 px-2 py-1">
                                                            {{ $item->unit }}</td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">
                                                            UGX {{ number_format($item->unit_cost, 0) }}</td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">
                                                            UGX {{ number_format($item->total_cost, 0) }}</td>
                                                        <td class="border border-gray-300 px-2 py-1 text-center">
                                                            <span
                                                                class="inline-block px-2 py-0.5 text-[9px] rounded font-semibold
                                                    {{ $item->status === 'approved'
                                                        ? 'bg-green-100 text-green-700'
                                                        : ($item->status === 'pending'
                                                            ? 'bg-yellow-100 text-yellow-700'
                                                            : 'bg-gray-100 text-gray-700') }}">
                                                                {{ ucfirst($item->status ?? 'pending') }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="9"
                                                            class="border border-gray-300 px-2 py-2 text-center text-gray-500">
                                                            No budget items added</td>
                                                    </tr>
                                                @endforelse
                                                @if ($budgetItems->count() > 0)
                                                    <tr class="bg-gray-100 font-bold">
                                                        <td colspan="7"
                                                            class="border border-gray-300 px-2 py-1 text-right">TOTAL:
                                                        </td>
                                                        <td
                                                            class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">
                                                            UGX {{ number_format($totalBudget, 0) }}</td>
                                                        <td class="border border-gray-300 px-2 py-1"></td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- ITEM AVAILABILITY STATUS -->
                                @if ($itemAvailability->count() > 0)
                                    <div class="mb-4">
                                        <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">ITEM
                                            AVAILABILITY STATUS</div>
                                        <div class="overflow-x-auto">
                                            <table class="w-full border border-gray-300 text-xs min-w-max">
                                                <thead>
                                                    <tr class="bg-gray-50">
                                                        <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                            Item Name</th>
                                                        <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                            Vendor</th>
                                                        <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                            Available Qty</th>
                                                        <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                            Lead Time</th>
                                                        <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                            Unit Price</th>
                                                        <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                            Status</th>
                                                        <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                                            Notes</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($itemAvailability as $index => $availability)
                                                        <tr
                                                            class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                            <td class="border border-gray-300 px-2 py-1">
                                                                {{ $availability->budgetItem->item_name ?? 'N/A' }}
                                                            </td>
                                                            <td class="border border-gray-300 px-2 py-1">
                                                                {{ $availability->vendor->name ?? 'N/A' }}</td>
                                                            <td class="border border-gray-300 px-2 py-1 text-center">
                                                                {{ $availability->available_quantity }}</td>
                                                            <td class="border border-gray-300 px-2 py-1">
                                                                {{ $availability->lead_time_days }} days</td>
                                                            <td
                                                                class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">
                                                                UGX {{ number_format($availability->unit_price, 0) }}
                                                            </td>
                                                            <td class="border border-gray-300 px-2 py-1 text-center">
                                                                <span
                                                                    class="inline-block px-2 py-0.5 text-[9px] rounded font-semibold
                                                    {{ $availability->status === 'available'
                                                        ? 'bg-green-100 text-green-700'
                                                        : ($availability->status === 'out_of_stock'
                                                            ? 'bg-red-100 text-red-700'
                                                            : 'bg-yellow-100 text-yellow-700') }}">
                                                                    {{ ucwords(str_replace('_', ' ', $availability->status)) }}
                                                                </span>
                                                            </td>
                                                            <td class="border border-gray-300 px-2 py-1">
                                                                {{ $availability->notes ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endif

                                <!-- APPROVALS -->
                                <div class="mb-4">
                                    <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">PROJECT
                                        APPROVALS</div>
                                    <div class="mb-2">
                                        <span class="text-xs font-semibold">Overall Approval Status: </span>
                                        <span
                                            class="inline-block px-3 py-1 text-xs rounded font-semibold
                                {{ $approvalStatus === 'approved'
                                    ? 'bg-green-100 text-green-700'
                                    : ($approvalStatus === 'rejected'
                                        ? 'bg-red-100 text-red-700'
                                        : ($approvalStatus === 'partially_approved'
                                            ? 'bg-yellow-100 text-yellow-700'
                                            : 'bg-gray-100 text-gray-700')) }}">
                                            {{ ucwords(str_replace('_', ' ', $approvalStatus)) }}
                                        </span>
                                    </div>
                                    <table class="w-full border-collapse text-xs">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="border border-gray-300 px-2 py-1 text-left font-semibold">
                                                    Approver Role</th>
                                                <th class="border border-gray-300 px-2 py-1 text-left font-semibold">
                                                    Approver Name</th>
                                                <th class="border border-gray-300 px-2 py-1 text-center font-semibold">
                                                    Status</th>
                                                <th class="border border-gray-300 px-2 py-1 text-left font-semibold">
                                                    Comments</th>
                                                <th class="border border-gray-300 px-2 py-1 text-left font-semibold">
                                                    Review Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($approvals as $approval)
                                                <tr class="{{ $loop->index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                    <td class="border border-gray-300 px-2 py-2">
                                                        {{ strtoupper($approval->approver_role) }}</td>
                                                    <td class="border border-gray-300 px-2 py-2">
                                                        {{ $approval->approver->name ?? 'N/A' }}</td>
                                                    <td class="border border-gray-300 px-2 py-2 text-center">
                                                        <span
                                                            class="inline-block px-2 py-0.5 text-[9px] rounded font-semibold
                                            {{ $approval->status === 'approved'
                                                ? 'bg-green-100 text-green-700'
                                                : ($approval->status === 'rejected'
                                                    ? 'bg-red-100 text-red-700'
                                                    : 'bg-yellow-100 text-yellow-700') }}">
                                                            {{ ucfirst($approval->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="border border-gray-300 px-2 py-2">
                                                        {{ $approval->comments ?? '-' }}</td>
                                                    <td class="border border-gray-300 px-2 py-2">
                                                        {{ $approval->reviewed_at ? $approval->reviewed_at->format('M d, Y H:i') : '-' }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5"
                                                        class="border border-gray-300 px-2 py-2 text-center text-gray-500">
                                                        No approvals yet</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- PROJECT TIMELINE -->
                                <div class="mb-4">
                                    <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">PROJECT
                                        TIMELINE</div>
                                    <div class="border border-gray-300 p-2">
                                        <div class="space-y-2 text-xs">
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 font-semibold">Created:</div>
                                                <div>{{ $project->created_at->format('M d, Y H:i') }}</div>
                                            </div>
                                            @if ($project->updated_at->ne($project->created_at))
                                                <div class="flex items-center gap-2">
                                                    <div class="w-24 font-semibold">Last Updated:</div>
                                                    <div>{{ $project->updated_at->format('M d, Y H:i') }}</div>
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 font-semibold">Start Date:</div>
                                                <div>{{ $start_date ? $start_date->format('M d, Y') : 'Not set' }}
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 font-semibold">End Date:</div>
                                                <div>{{ $end_date ? $end_date->format('M d, Y') : 'Not set' }}</div>
                                            </div>
                                            @if ($start_date && $end_date)
                                                <div class="flex items-center gap-2">
                                                    <div class="w-24 font-semibold">Duration:</div>
                                                    <div>{{ $start_date->diffInDays($end_date) }} days</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            @if ($showPrintButton)
                                <div class="mt-4 sm:mt-6 flex gap-2 sm:gap-3 print:hidden">
                                    <button type="button" wire:loading.attr="disabled" onclick="window.print()"
                                        class="px-4 sm:px-6 py-2 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white rounded-lg font-medium flex items-center gap-2 text-sm">
                                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4 sm:w-5 sm:h-5" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        <svg wire:loading class="animate-spin w-4 h-4 sm:w-5 sm:h-5"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                        <span class="hidden sm:inline">Print Document</span>
                                        <span class="sm:hidden">Print</span>
                                    </button>

                                    <a href="{{ route('projects.budget', $project->id) }}"
                                        class="px-4 sm:px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span>Manage Budget</span>
                                    </a>

                                    <a href="{{ route('projects.approvals', $project->id) }}"
                                        class="px-4 sm:px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium flex items-center gap-2 text-sm">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>Approvals</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
