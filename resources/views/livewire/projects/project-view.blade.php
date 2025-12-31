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
            <ul class="flex border-b border-gray-200">
                <li class="mr-4">
                    <button wire:click="$set('activeTab', 'project-sites')"
                        class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'project-sites' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
                        Project Clients / Sites
                    </button>
                </li>
                <li class="mr-4">
                    <button wire:click="$set('activeTab', 'project-summary')"
                        class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'project-summary' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
                        Project Summary
                    </button>
                </li>
                <li class="mr-4">
                    <button wire:click="$set('activeTab', 'feasibility-details')"
                        class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'feasibility-details' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
                        Feasibility Details
                    </button>
                </li>
                <li class="mr-4">
                    <button wire:click="$set('activeTab', 'vendor-cost')"
                        class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'vendor-cost' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
                        Vendor & Cost
                    </button>
                </li>
                <li class="mr-4">
                    <button wire:click="$set('activeTab', 'materials')"
                        class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'materials' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
                        Materials
                    </button>
                </li>
                <li class="mr-4">
                    <button wire:click="$set('activeTab', 'cost-summary')"
                        class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'cost-summary' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
                        Cost Summary
                    </button>
                </li>
                <li>
                    <button wire:click="$set('activeTab', 'print-content')"
                        class="py-2 px-4 text-sm font-medium text-gray-600 hover:text-gray-800 focus:outline-none {{ $activeTab === 'print-content' ? 'border-b-2 border-blue-500 text-blue-600' : '' }}">
                        Print Content
                    </button>
                </li>
            </ul>

            <div class="mt-4">
                @if ($activeTab === 'project-sites')
                    <div>
                        <div class="mt-4">
                            <!-- Project Clients / Sites Content -->
                            <div class="p-4">
                                <div class="max-w-7xl">
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
                                                <!-- Add Customer Button -->

                                                <button class="px-2 py-1.5 border border-blue-300 rounded-lg hover:bg-blue-50 flex items-center gap-1.5 text-xs text-gray-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                    Add Client / Site
                                                </button>
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
                    </div>
                @elseif ($activeTab === 'project-summary')
                    <div>
                        <div class="mt-4">
                            {{-- <h2 class="text-lg font-semibold text-gray-800">Current Project Details</h2> --}}
                            <div class="max-w-4xl">
                                <table class="w-full mt-2 border border-gray-300 text-sm text-gray-600">
                                    <tbody>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Project Code:</td>
                                            <td class="border border-gray-300 px-2 py-1">{{ $project_code }}</td>
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
                                                {{ ucwords(str_replace('_', ' ', $status)) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Priority:</td>
                                            <td class="border border-gray-300 px-2 py-1">{{ ucwords($priority) }}</td>
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
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Actual
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
                @elseif($activeTab === 'feasibility-details')
                    <div>
                        <!-- Feasibility Details Content -->
                        <p>Feasibility details go here...</p>
                    </div>
                @elseif($activeTab === 'vendor-cost')
                    <div>
                        <!-- Vendor & Cost Content -->
                        <p>Vendor and cost details go here...</p>
                    </div>
                @elseif($activeTab === 'materials')
                    <div>
                        <!-- Materials Content -->
                        <p>Materials details go here...</p>
                    </div>
                @elseif($activeTab === 'cost-summary')
                    <div>
                        <!-- Cost Summary Content -->
                        <p>Cost summary details go here...</p>
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
                                        <p class="text-[10px] sm:text-xs text-gray-600">1st Floor Nyonyi Gardens, Kololo
                                        </p>
                                        <p class="text-[10px] sm:text-xs text-gray-600">Plot 16/17, P.O. Box 7493,
                                            Kampala</p>
                                        <p class="text-[10px] sm:text-xs text-gray-600">Tel: +256 200 306200 / +256
                                            777021479</p>
                                        <p class="text-[10px] sm:text-xs text-gray-600">info@bcc.co.ug www.bcc.co.ug</p>
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
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/4">
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
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/6">
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
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Actual
                                                Budget:</td>
                                            <td class="border border-gray-300 px-2 py-1">UGX
                                                {{ number_format((float) $actual_budget, 0) }}</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                Created By:</td>
                                            <td class="border border-gray-300 px-2 py-1">{{ $creator->name ?? 'N/A' }}
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
                                                    {{ $client->company ?: $client->contact_person }}</td>
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
                                                <td class="border border-gray-300 px-2 py-1">{{ $client->phone }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">
                                                    Email:</td>
                                                <td class="border border-gray-300 px-2 py-1">{{ $client->email }}</td>
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
