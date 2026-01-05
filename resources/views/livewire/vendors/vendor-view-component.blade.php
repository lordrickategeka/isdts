<div class="min-h-screen bg-gray-50">
    @if($vendor)
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 border-b border-blue-800">
            <div class="max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('vendors.index') }}" 
                            class="text-white/80 hover:text-white transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <div class="bg-blue-800 p-3 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-600">{{ $vendor->name }}</h1>
                            <p class="text-sm text-blue-300">{{ $vendor->vendor_code }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full {{ $vendor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($vendor->status) }}
                        </span>
                        <a href="{{ route('vendors.index') }}" 
                            class="px-4 py-2 bg-white text-blue-600 text-sm font-medium rounded-lg hover:bg-blue-50 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit Vendor
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Contact Info & Notes -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Contact Information Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Contact Information
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-4">
                            @if($vendor->email)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <a href="mailto:{{ $vendor->email }}" class="text-sm text-blue-600 hover:underline">{{ $vendor->email }}</a>
                                    </div>
                                </div>
                            @endif
                            @if($vendor->phone)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <a href="tel:{{ $vendor->phone }}" class="text-sm text-blue-600 hover:underline">{{ $vendor->phone }}</a>
                                    </div>
                                </div>
                            @endif
                            @if($vendor->address)
                                <div>
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Address</label>
                                    <div class="mt-1 flex items-start gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span class="text-sm text-gray-900">{{ $vendor->address }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes Card -->
                    @if($vendor->notes)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-yellow-100 border-b border-yellow-200">
                                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Notes
                                </h3>
                            </div>
                            <div class="px-6 py-4">
                                <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $vendor->notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Metadata Card -->
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Information
                            </h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created</label>
                                <p class="text-sm text-gray-900 mt-1">{{ $vendor->created_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</label>
                                <p class="text-sm text-gray-900 mt-1">{{ $vendor->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Services & Products -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-blue-100 border-b border-blue-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Vendor Services & Products
                                </h3>
                                <span class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full">
                                    {{ $vendor->services->count() }} {{ Str::plural('Service', $vendor->services->count()) }}
                                </span>
                            </div>
                        </div>
                        <div class="px-6 py-6">
                            @if($vendor->services->count() > 0)
                                @foreach($vendor->services as $service)
                                    <div class="mb-8 last:mb-0">
                                        <!-- Service Header -->
                                        <div class="bg-blue-50 px-4 py-3 rounded-t-lg border border-blue-200">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h4 class="text-base font-semibold text-blue-900">{{ $service->service_name }}</h4>
                                                    @if($service->description)
                                                        <p class="text-sm text-gray-600 mt-1">{{ $service->description }}</p>
                                                    @endif
                                                </div>
                                                <span class="px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded">
                                                    {{ $service->products->count() }} {{ Str::plural('Product', $service->products->count()) }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Products Table -->
                                        @if($service->products->count() > 0)
                                            <div class="overflow-x-auto border-x border-b border-blue-200 rounded-b-lg">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product Name</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Capacity</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installation</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Billing Cycle</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @foreach($service->products as $product)
                                                            <tr class="hover:bg-gray-50">
                                                                <td class="px-4 py-3 whitespace-nowrap">
                                                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                                                </td>
                                                                <td class="px-4 py-3">
                                                                    <div class="text-sm text-gray-600">{{ $product->description ?? '-' }}</div>
                                                                </td>
                                                                <td class="px-4 py-3 whitespace-nowrap">
                                                                    <div class="text-sm text-gray-900">{{ $product->capacity ?? '-' }}</div>
                                                                </td>
                                                                <td class="px-4 py-3 whitespace-nowrap">
                                                                    <div class="text-sm text-gray-900">
                                                                        {{ $product->installation_charge ? 'UGX ' . number_format($product->installation_charge, 0) : '-' }}
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 whitespace-nowrap">
                                                                    <div class="text-sm text-gray-900">
                                                                        {{ $product->monthly_charge ? 'UGX ' . number_format($product->monthly_charge, 0) : '-' }}
                                                                    </div>
                                                                </td>
                                                                <td class="px-4 py-3 whitespace-nowrap">
                                                                    <div class="text-sm text-gray-900 capitalize">{{ $product->billing_cycle ?? '-' }}</div>
                                                                </td>
                                                                <td class="px-4 py-3 whitespace-nowrap">
                                                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $product->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                                        {{ ucfirst($product->status) }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="px-4 py-8 text-center bg-gray-50 border-x border-b border-blue-200 rounded-b-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                                <p class="text-sm text-gray-500">No products added yet</p>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="py-12 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Services Yet</h3>
                                    <p class="text-sm text-gray-500 mb-4">This vendor doesn't have any services added.</p>
                                    <a href="{{ route('vendors.index') }}" 
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Services
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
