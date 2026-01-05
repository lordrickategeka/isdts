<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">Service Types</h1>
                <p class="text-sm text-gray-500">Manage your service offerings</p>
            </div>
            <button wire:click="openModal" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Service Type
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="max-w-7xl">
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tab Menu -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button wire:click="$set('activeTab', 'service-types')"
                            class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'service-types' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Service Types
                        </button>
                        <button wire:click="$set('activeTab', 'subcategories')"
                            class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'subcategories' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Subcategories
                        </button>
                        <button wire:click="$set('activeTab', 'products')"
                            class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ $activeTab === 'products' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Products
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex-1 w-full">
                        <div class="relative">
                            <input type="text"
                                wire:model.live.debounce.300ms="search"
                                class="w-full px-4 py-1.5 pl-9 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Search service types...">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Filter Button -->
                        <button class="px-2 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5 text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            Filter
                        </button>
                        <!-- Import Button -->
                        <button class="px-2 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5 text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Import
                        </button>
                        <!-- Export Button -->
                        <button class="px-2 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5 text-xs text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </button>
                        <label class="text-xs text-gray-600">Show:</label>
                        <select wire:model.live="perPage" class="px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="6">6</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Service Types Table -->
            @if($activeTab === 'service-types')
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subcategories/Products</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($serviceTypes as $index => $serviceType)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $serviceTypes->firstItem() + $index }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs font-medium text-gray-900">{{ $serviceType->name }}</div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="text-xs text-gray-700">
                                            @if($serviceType->subcategories->count() > 0)
                                                <div class="grid grid-cols-2 gap-3">
                                                    @foreach($serviceType->subcategories as $subcategory)
                                                        <div class="mb-2">
                                                            <span class="text-gray-400 italic">Service Subcategory:</span>
                                                            <div class="font-semibold text-purple-700">• {{ $subcategory->name }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <span class="text-gray-400 italic">No subcategories</span>
                                            @endif
                                            <div class="mt-2 text-xs text-blue-600">
                                                <span class="italic">Note: Products are now managed through Vendors → Vendor Services → Products</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-4 font-semibold rounded-full
                                            {{ $serviceType->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($serviceType->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button wire:click="edit({{ $serviceType->id }})" class="text-green-600 hover:text-green-900" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="delete({{ $serviceType->id }})"
                                                wire:confirm="Are you sure you want to delete this service type?"
                                                class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <p class="text-lg font-medium">No service types found</p>
                                            <p class="text-sm">Create your first service type to get started</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (!empty($serviceTypes) && method_exists($serviceTypes, 'hasPages') && $serviceTypes->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $serviceTypes->links() }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Subcategories Table -->
            @if($activeTab === 'subcategories')
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service Type</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($subcategories as $index => $subcategory)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $subcategories->firstItem() + $index }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs font-medium text-gray-900">{{ $subcategory->name }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-700">{{ $subcategory->serviceType->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2">
                                        <div class="text-xs text-gray-700">
                                            <span class="text-gray-400 italic">See Vendor Products</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-4 font-semibold rounded-full
                                            {{ $subcategory->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($subcategory->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <button class="text-green-600 hover:text-green-900" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:confirm="Are you sure you want to delete this subcategory?"
                                                class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            <p class="text-lg font-medium">No subcategories found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (!empty($subcategories) && method_exists($subcategories, 'hasPages') && $subcategories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $subcategories->links() }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Products Component -->
            @if($activeTab === 'products')
                @livewire('products.products-component')
            @endif
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-10xl max-h-[90vh] overflow-y-auto">
                    <form wire:submit.prevent="save">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ $isEditing ? 'Edit Service Type' : 'Add Service Type' }}
                            </h3>

                            <!-- Service Type Details -->
                            <div class="mb-6">
                                <h4 class="text-sm font-semibold text-gray-700 mb-3 border-b pb-2">Service Type Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" wire:model="name"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="e.g., Internet & Connectivity">
                                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                        <textarea wire:model="description" rows="2"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Service description..."></textarea>
                                        @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Status <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="status"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" wire:model.live="hasSubcategories"
                                                class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="text-xs font-medium text-gray-700">Has Subcategories</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Subcategories Section -->
                            @if($hasSubcategories && !$isEditing)
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-3 border-b pb-2">
                                        <h4 class="text-sm font-semibold text-gray-700">Subcategories</h4>
                                    </div>

                                    @forelse($subcategories as $index => $subcategory)
                                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-3">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-medium text-gray-600">Subcategory #{{ $index + 1 }}</span>
                                                <button type="button" wire:click="removeSubcategory({{ $index }})"
                                                    class="text-red-600 hover:text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                                        Name <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" wire:model="subcategories.{{ $index }}.name"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                        placeholder="e.g., Home">
                                                    @error('subcategories.' . $index . '.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                                    <input type="text" wire:model="subcategories.{{ $index }}.description"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                        placeholder="Subcategory description">
                                                    @error('subcategories.' . $index . '.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-500 italic mb-3">No subcategories added yet.</p>
                                    @endforelse
                                    
                                    <button type="button" wire:click="addSubcategory"
                                        class="w-full px-3 py-2 text-xs bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center justify-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Subcategory
                                    </button>
                                </div>
                            @endif

                            <!-- Products Section -->
                            @if(!$isEditing)
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-3 border-b pb-2">
                                        <h4 class="text-sm font-semibold text-gray-700">Products</h4>
                                    </div>

                                    @forelse($products as $index => $product)
                                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-medium text-blue-700">Product #{{ $index + 1 }}</span>
                                                <button type="button" wire:click="removeProduct({{ $index }})"
                                                    class="text-red-600 hover:text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                                @if($hasSubcategories)
                                                    <div class="md:col-span-2">
                                                        <label class="block text-xs font-medium text-gray-700 mb-1">Subcategory</label>
                                                        <select wire:model="products.{{ $index }}.subcategory_index"
                                                            class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                                            <option value="">Select Subcategory (Optional)</option>
                                                            @foreach($subcategories as $subIndex => $subcategory)
                                                                <option value="{{ $subIndex }}">{{ $subcategory['name'] ?? 'Subcategory ' . ($subIndex + 1) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                @endif

                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                                        Name <span class="text-red-500">*</span>
                                                    </label>
                                                    <input type="text" wire:model="products.{{ $index }}.name"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                        placeholder="e.g., 50 Mbps">
                                                    @error('products.' . $index . '.name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>

                                                <div class="md:col-span-2">
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                                    <input type="text" wire:model="products.{{ $index }}.description"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                        placeholder="Product description">
                                                    @error('products.' . $index . '.description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Price</label>
                                                    <input type="number" step="0.01" wire:model="products.{{ $index }}.price"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                        placeholder="0.00">
                                                    @error('products.' . $index . '.price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Capacity</label>
                                                    <input type="text" wire:model="products.{{ $index }}.capacity"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                        placeholder="e.g., 50 Mbps">
                                                    @error('products.' . $index . '.capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Installation Charge</label>
                                                    <input type="number" step="0.01" wire:model="products.{{ $index }}.installation_charge"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                        placeholder="0.00">
                                                    @error('products.' . $index . '.installation_charge') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Monthly Charge</label>
                                                    <input type="number" step="0.01" wire:model="products.{{ $index }}.monthly_charge"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                                                        placeholder="0.00">
                                                    @error('products.' . $index . '.monthly_charge') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-medium text-gray-700 mb-1">Billing Cycle</label>
                                                    <select wire:model="products.{{ $index }}.billing_cycle"
                                                        class="w-full px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                                                        <option value="monthly">Monthly</option>
                                                        <option value="quarterly">Quarterly</option>
                                                        <option value="annually">Annually</option>
                                                        <option value="one-time">One-Time</option>
                                                    </select>
                                                    @error('products.' . $index . '.billing_cycle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-gray-500 italic mb-3">No products added yet.</p>
                                    @endforelse
                                    
                                    <button type="button" wire:click="addProduct"
                                        class="w-full px-3 py-2 text-xs bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Product
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                {{ $isEditing ? 'Update' : 'Create' }}
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="mt-3 sm:mt-0 w-full sm:w-auto px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
