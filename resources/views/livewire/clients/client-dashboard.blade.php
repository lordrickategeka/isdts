<div class="container mx-auto px-4 py-8 max-w-7xl">
    @if(!$client)
        <!-- No Client Record Found -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-8 text-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-yellow-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">No Client Record Found</h2>
            <p class="text-gray-600 mb-6">We couldn't find a client record associated with your account. Please contact support for assistance.</p>
            <a href="#" class="btn btn-primary">Contact Support</a>
        </div>
    @else
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ $client->contact_person ?? 'Client' }}!</h1>
        <p class="text-gray-600">Track your agreements, services, and approvals all in one place.</p>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Agreement Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-500">Agreement</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $client->agreement_number ?? 'N/A' }}</h3>
            <p class="text-sm text-gray-600">Your agreement number</p>
        </div>

        <!-- Services Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-500">Services</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $services->count() }}</h3>
            <p class="text-sm text-gray-600">Active services</p>
        </div>

        <!-- Payment Type Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-500">Payment</span>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-1 capitalize">{{ $client->payment_type ?? 'N/A' }}</h3>
            <p class="text-sm text-gray-600">Payment type</p>
        </div>
    </div>

    <!-- Approval Progress -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Agreement Approval Progress
        </h2>

        @php
            $approvalSteps = [
                'Sales Manager' => 'pending',
                'CCO' => 'pending',
                'Credit Control' => 'pending',
                'CFO' => 'pending',
                'Business Analysis' => 'pending',
                'Network Planning' => 'pending',
                'Implementation' => 'pending',
                'Director' => 'pending'
            ];

            // Get actual approval statuses from client signatures
            if ($client) {
                $signatures = $client->signatures()->orderBy('position')->get();
                foreach ($signatures as $signature) {
                    if (isset($approvalSteps[$signature->position])) {
                        $approvalSteps[$signature->position] = $signature->status;
                    }
                }
            }
        @endphp

        <!-- Progress Steps -->
        <div class="space-y-4">
            @foreach($approvalSteps as $step => $status)
                <div class="flex items-center gap-4">
                    <!-- Status Icon -->
                    <div class="flex-shrink-0">
                        @if($status === 'signed')
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        @elseif($status === 'rejected')
                            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        @else
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Step Info -->
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900">{{ $step }}</h3>
                            @if($status === 'signed')
                                <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Approved</span>
                            @elseif($status === 'rejected')
                                <span class="px-3 py-1 text-xs font-medium bg-red-100 text-red-700 rounded-full">Rejected</span>
                            @else
                                <span class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">Pending</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1">
                            @if($status === 'signed')
                                This approval has been completed
                            @elseif($status === 'rejected')
                                This approval was rejected
                            @else
                                Waiting for approval
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Services List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
            </svg>
            Your Services
        </h2>

        @if($services->count() > 0)
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="text-left text-xs font-semibold text-gray-700 uppercase">Service Type</th>
                            <th class="text-left text-xs font-semibold text-gray-700 uppercase">Product</th>
                            <th class="text-left text-xs font-semibold text-gray-700 uppercase">Capacity</th>
                            <th class="text-left text-xs font-semibold text-gray-700 uppercase">Monthly Charge</th>
                            <th class="text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                            <tr class="border-t border-gray-200 hover:bg-gray-50">
                                <td class="py-3">
                                    <span class="font-medium text-gray-900">{{ $service->serviceType->name ?? 'N/A' }}</span>
                                </td>
                                <td class="py-3 text-gray-700">{{ $service->product->name ?? 'N/A' }}</td>
                                <td class="py-3 text-gray-700">{{ $service->capacity ?? 'N/A' }}</td>
                                <td class="py-3">
                                    <span class="font-semibold text-gray-900">UGX {{ number_format($service->monthly_charge ?? 0) }}</span>
                                </td>
                                <td class="py-3">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $service->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($service->status ?? 'pending') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Services Yet</h3>
                <p class="text-gray-600">Your services will appear here once they are activated.</p>
            </div>
        @endif
    </div>

    <!-- Contact Support -->
    <div class="mt-8 bg-gradient-to-r from-primary to-primary-dark rounded-xl shadow-sm p-6 text-white">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold mb-2">Need Help?</h3>
                <p class="text-blue-100">Our support team is here to assist you with any questions.</p>
            </div>
            <a href="#" class="btn btn-light bg-white text-primary hover:bg-gray-100 border-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                Contact Support
            </a>
        </div>
    </div>
    @endif
</div>
