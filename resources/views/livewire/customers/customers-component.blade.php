<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
                <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">Customers</h1>
                <p class="text-sm text-gray-500">Manage all Your Customers.</p>
            </div>
            <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Customer
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex-1 w-full">
                        <div class="relative">
                            <input type="text"
                                class="w-full px-4 py-1.5 pl-9 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Search by company, contact person, email, phone, or TIN...">
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
                        <select class="px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="6">6</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Clients Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    #
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Company/Name
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact Person
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Phone/Email
                                </th>

                                {{-- <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    TIN No
                                </th> --}}
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Services
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Payment
                                </th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach ($demoClients as $index => $client)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150">
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $index + 1 }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs font-medium text-gray-900">{{ $client['company'] }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-700">{{ $client['contact_person'] }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $client['category'] === 'Home' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $client['category'] ?? 'N/A' }}
                                        </span>
                                        @if(!empty($client['category_type']) && $client['category_type'] !== 'Home')
                                            <div class="text-xs text-gray-500 mt-1">{{ $client['category_type'] }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <div class="text-xs text-gray-900">{{ $client['email'] }}</div>
                                            <div class="text-xs text-gray-900">{{ $client['phone'] }}</div>
                                        </div>
                                    </td>

                                    <td class="px-4 py-2">
                                        @if(count($client['services']) > 0)
                                            <div class="flex flex-col gap-1">
                                                @foreach($client['services'] as $service)
                                                    <div class="text-xs bg-blue-50 px-2 py-1 rounded border border-blue-200">
                                                        <div class="font-semibold text-blue-900">{{ $service['service'] }} - {{ $service['product'] }}</div>
                                                        @if(!empty($service['capacity']))
                                                            <div class="text-blue-600">{{ $service['capacity'] }} - @if(!empty($service['monthly_charge'])) UGX {{ number_format($service['monthly_charge'], 0) }}/mo @endif</div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">No services</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        @if(!empty($client['payment_type']))
                                            <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-full {{ $client['payment_type'] === 'prepaid' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">{{ ucfirst($client['payment_type']) }}</span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            <span class="px-2 inline-flex text-xs leading-4 font-semibold rounded-full {{ $client['status'] === 'active' ? 'bg-green-100 text-green-800' : ($client['status'] === 'pending_approval' ? 'bg-yellow-100 text-yellow-800' : ($client['status'] === 'approved' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">{{ ucfirst(str_replace('_', ' ', $client['status'])) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="#" class="text-purple-600 hover:text-purple-900" title="View Agreement">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                            <button class="text-blue-600 hover:text-blue-900" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button class="text-green-600 hover:text-green-900" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Demo Pagination Placeholder -->
                <div class="px-6 py-4 border-t border-gray-200 text-sm text-gray-600">
                    Showing {{ $demoClients->count() }} demo customers
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="mt-6 bg-white rounded-lg shadow-md p-4">
                <div class="flex items-center justify-between text-sm text-gray-600">
                    <div>
                        Showing <span class="font-semibold text-gray-900">1</span> to
                        <span class="font-semibold text-gray-900">{{ $demoClients->count() }}</span> of
                        <span class="font-semibold text-gray-900">{{ $demoClients->count() }}</span> customers
                    </div>
                    <div>
                        Total: <span class="font-semibold text-gray-900">{{ $demoClients->count() }}</span> customers
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
