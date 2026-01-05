<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 border-b border-blue-800">
        <div class="max-w-7xl  px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('vendors.index') }}" 
                        class="text-white/80 hover:text-white transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div class="bg-white/20 p-3 rounded-lg hidden">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="">
                        <h1 class="text-2xl font-bold text-gray-800">Create New Vendor</h1>
                        <p class="text-sm text-gray-800">Add a new vendor to the system</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Vendor Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Basic Information
                            </h3>
                        </div>
                        <div class="px-6 py-6 space-y-4">
                            <!-- Vendor Name -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Vendor Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="name" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter vendor name">
                                @error('name') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" wire:model="email" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="vendor@example.com">
                                @error('email') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" wire:model="phone" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="+256 XXX XXX XXX">
                                @error('phone') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Address -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                <textarea wire:model="address" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter vendor address"></textarea>
                                @error('address') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                <textarea wire:model="notes" rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Add any additional notes about this vendor"></textarea>
                                @error('notes') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Services Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-green-100 border-b border-green-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                Vendor Services
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <!-- Add Service Form -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Service Name</label>
                                        <input type="text" wire:model="newServiceName" 
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="e.g., Internet Service">
                                        @error('newServiceName') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Description (Optional)</label>
                                        <input type="text" wire:model="newServiceDescription" 
                                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Service description">
                                    </div>
                                </div>
                                <button type="button" wire:click="addService"
                                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium flex items-center justify-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Service
                                </button>
                            </div>

                            <!-- Services List -->
                            @if(count($services) > 0)
                                <div class="space-y-4">
                                    @foreach($services as $index => $service)
                                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                                            <!-- Service Header -->
                                            <div class="bg-gray-50 px-3 py-2 border-b border-gray-200 flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="text-sm font-medium text-gray-900">{{ $service['service_name'] }}</div>
                                                    @if($service['description'])
                                                        <div class="text-xs text-gray-500 mt-1">{{ $service['description'] }}</div>
                                                    @endif
                                                </div>
                                                <button type="button" wire:click="removeService({{ $index }})"
                                                    class="ml-3 text-red-600 hover:text-red-800">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Products Section -->
                                            <div class="p-3 bg-white">
                                                <!-- Add Product Form -->
                                                <div class="bg-blue-50 p-3 rounded-lg mb-3">
                                                    <div class="text-xs font-medium text-gray-700 mb-2">Add Product to {{ $service['service_name'] }}</div>
                                                    <div class="grid grid-cols-2 gap-2 mb-2">
                                                        <div>
                                                            <input type="text" wire:model="newProductName" 
                                                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                                                                placeholder="Product Name *">
                                                            @error('newProductName') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div>
                                                            <input type="text" wire:model="newProductCapacity" 
                                                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                                                                placeholder="Capacity (e.g., 10 Mbps)">
                                                        </div>
                                                        <div>
                                                            <input type="number" step="0.01" wire:model="newProductInstallationCharge" 
                                                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                                                                placeholder="Installation Charge">
                                                        </div>
                                                        <div>
                                                            <input type="number" step="0.01" wire:model="newProductMonthlyCharge" 
                                                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                                                                placeholder="Monthly Charge">
                                                        </div>
                                                        <div>
                                                            <select wire:model="newProductBillingCycle"
                                                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                                                                <option value="monthly">Monthly</option>
                                                                <option value="quarterly">Quarterly</option>
                                                                <option value="annually">Annually</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <select wire:model="newProductStatus"
                                                                class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500">
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Inactive</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2">
                                                        <textarea wire:model="newProductDescription" rows="2"
                                                            class="w-full px-2 py-1.5 text-xs border border-gray-300 rounded focus:ring-1 focus:ring-blue-500"
                                                            placeholder="Product Description"></textarea>
                                                    </div>
                                                    <button type="button" wire:click="addProduct({{ $index }})"
                                                        class="w-full px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded font-medium flex items-center justify-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                        </svg>
                                                        Add Product
                                                    </button>
                                                </div>

                                                <!-- Products List -->
                                                @if(isset($service['products']) && count($service['products']) > 0)
                                                    <div class="space-y-2">
                                                        @foreach($service['products'] as $pIndex => $product)
                                                            <div class="p-2 bg-gray-50 rounded border border-gray-200">
                                                                <div class="flex items-start justify-between">
                                                                    <div class="flex-1">
                                                                        <div class="flex items-center gap-2">
                                                                            <span class="text-xs font-medium text-gray-900">{{ $product['name'] }}</span>
                                                                            <span class="px-1.5 py-0.5 bg-green-100 text-green-800 text-xs rounded">{{ $product['status'] }}</span>
                                                                        </div>
                                                                        @if($product['description'])
                                                                            <div class="text-xs text-gray-600 mt-1">{{ $product['description'] }}</div>
                                                                        @endif
                                                                        <div class="grid grid-cols-4 gap-2 mt-1">
                                                                            @if($product['capacity'])
                                                                                <div>
                                                                                    <span class="text-xs text-gray-500">Capacity:</span>
                                                                                    <span class="text-xs text-gray-900">{{ $product['capacity'] }}</span>
                                                                                </div>
                                                                            @endif
                                                                            @if($product['installation_charge'])
                                                                                <div>
                                                                                    <span class="text-xs text-gray-500">Install:</span>
                                                                                    <span class="text-xs text-gray-900">{{ number_format($product['installation_charge'], 0) }}</span>
                                                                                </div>
                                                                            @endif
                                                                            @if($product['monthly_charge'])
                                                                                <div>
                                                                                    <span class="text-xs text-gray-500">Monthly:</span>
                                                                                    <span class="text-xs text-gray-900">{{ number_format($product['monthly_charge'], 0) }}</span>
                                                                                </div>
                                                                            @endif
                                                                            @if($product['billing_cycle'])
                                                                                <div>
                                                                                    <span class="text-xs text-gray-500">Cycle:</span>
                                                                                    <span class="text-xs text-gray-900 capitalize">{{ $product['billing_cycle'] }}</span>
                                                                                </div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                    <button type="button" wire:click="removeProduct({{ $index }}, {{ $pIndex }})"
                                                                        class="ml-2 text-red-600 hover:text-red-800">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center py-4 text-gray-400">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                        </svg>
                                                        <p class="text-xs">No products yet</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <p class="text-sm">No services added yet</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column - Status & Actions -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-purple-100 border-b border-purple-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Status
                            </h3>
                        </div>
                        <div class="px-6 py-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Vendor Status <span class="text-red-500">*</span>
                                </label>
                                <div class="space-y-2">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" wire:model="status" value="active" 
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Active</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" wire:model="status" value="inactive" 
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Inactive</span>
                                    </label>
                                </div>
                                @error('status') <span class="text-xs text-red-500 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900">Actions</h3>
                        </div>
                        <div class="px-6 py-6 space-y-3">
                            <button type="submit"
                                class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Create Vendor
                            </button>
                            <button type="button" wire:click="cancel"
                                class="w-full px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg font-medium flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>

                    <!-- Help Card -->
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="flex items-start gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <h4 class="text-sm font-semibold text-blue-900 mb-1">Quick Tips</h4>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li>• Vendor name is required</li>
                                    <li>• Add services after creating vendor</li>
                                    <li>• Services can have multiple products</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
