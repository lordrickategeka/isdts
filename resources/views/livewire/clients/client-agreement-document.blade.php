<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200 print:hidden">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-1xl font-bold text-black">Customer Agreement Review</h1>
                <p class="text-sm text-gray-500">Review and print customer agreement document</p>
            </div>
            <a href="{{ route('clients.index') }}" class="text-1xl text-gray-600 hover:text-gray-800">
                ← Back to Client
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

                /* Prevent page breaks inside important sections */
                #print-content>div {
                    page-break-inside: avoid;
                }

                /* Reduce image size for print */
                #print-content img {
                    max-height: 40px !important;
                }

                /* SERVICE TERMS AND CONDITIONS specific styling for print */
                #print-content .text-xs.space-y-2 {
                    font-size: 7px !important;
                    line-height: 1.1 !important;
                }
            }
        </style>

        <div id="print-content" class="max-w-7xl mx-auto text-xs sm:text-sm">
            <!-- Main Agreement Document -->
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
                        <p class="text-[10px] sm:text-xs text-gray-600">1st Floor Nyonyi Gardens, Kololo</p>
                        <p class="text-[10px] sm:text-xs text-gray-600">Plot 16/17, P.O. Box 7493, Kampala</p>
                        <p class="text-[10px] sm:text-xs text-gray-600">Tel: +256 200 306200 / +256 777021479</p>
                        <p class="text-[10px] sm:text-xs text-gray-600">info@bcc.co.ug www.bcc.co.ug</p>
                    </div>
                </div>

                <div class="border-t-2 border-b-2 border-gray-300 py-0 px-0 text-center mb-2">
                    <u>
                        <h2 class="text-sm sm:text-base font-bold uppercase">
                            {{ strtoupper($payment_type ?? 'PRE-PAID') }} CUSTOMER AGREEMENT</h2>
                        <p class="text-right text-sm sm:text-base font-bold"> AGREEMENT No.:{{ $agreement_number }}</p>

                    </u>
                </div>

                <div class="mb-2 text-right">
                    <div class="text-[10px] sm:text-xs text-gray-600">TIN No: <u
                            class="ml-2 sm:ml-8">{{ $tin_no ?: '..................' }}</u> DATE: <u
                            class="ml-1 sm:ml-2">{{ date('Y-m-d') }}</u></div>
                </div>

                <!-- CLIENT INFORMATION -->
                <div class="mb-2 sm:mb-4">
                    <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">CLIENT INFORMATION</div>
                    <table class="w-full border border-gray-300 text-xs">
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/2">Company/ Name:
                            </td>
                            <td class="border border-gray-300 px-2 py-1 w-1/2">{{ $company ?: $contact_person }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Nature of Business:
                            </td>
                            <td class="border border-gray-300 px-2 py-1">{{ $nature_of_business ?: '' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Physical Address:</td>
                            <td class="border border-gray-300 px-2 py-1">{{ $address }}</td>
                        </tr>
                    </table>
                    <table class="w-full border border-gray-300 text-xs mt-2">
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/2">Contact Person:
                            </td>
                            <td class="border border-gray-300 px-2 py-1 w-1/2">{{ $contact_person }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Designation:</td>
                            <td class="border border-gray-300 px-2 py-1">{{ $designation ?: '' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Telephone Number:</td>
                            <td class="border border-gray-300 px-2 py-1">{{ $phone }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Alternative contact:
                            </td>
                            <td class="border border-gray-300 px-2 py-1">{{ $alternative_contact ?: '' }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50">Email Address:</td>
                            <td class="border border-gray-300 px-2 py-1">{{ $email }}</td>
                        </tr>
                    </table>
                    <table class="w-full border border-gray-300 text-xs mt-2">
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/6">Coordinates</td>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/12">Latitude</td>
                            <td class="border border-gray-300 px-2 py-1 w-1/6">{{ $latitude ?: '' }}</td>
                            <td class="border border-gray-300 px-2 py-1 font-semibold bg-gray-50 w-1/12">Longitude</td>
                            <td class="border border-gray-300 px-2 py-1 w-1/6">{{ $longitude ?: '' }}</td>
                        </tr>

                    </table>
                </div>

                <!-- SERVICE DETAILS -->
                <div class="mb-4">
                    <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">SERVICE DETAILS</div>
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-300 text-xs min-w-max">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">Service Type</th>
                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">Product</th>
                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">Capacity</th>
                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">Installation/One-time
                                        charge</th>
                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">Monthly charge</th>
                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap">Contract Start date
                                    </th>
                                    <th class="border border-gray-300 px-2 py-1 whitespace-nowrap print:hidden">Feasibility</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                            {{ $service['service_type'] ?? '' }}</td>
                                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                            {{ $service['product'] ?? '' }}</td>
                                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                            {{ $service['capacity'] ?? '' }}</td>
                                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">UGX
                                            {{ number_format((float) ($service['installation_charge'] ?? 0), 0) }}</td>
                                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">UGX
                                            {{ number_format((float) ($service['monthly_charge'] ?? 0), 0) }}</td>
                                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap">
                                            {{ $service['contract_start_date'] ? date('M d, Y', strtotime($service['contract_start_date'])) : '' }}
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 whitespace-nowrap text-center print:hidden">
                                            @can('view_feasibility')
                                                @if(isset($service['id']))
                                                    <button wire:click="openFeasibilityModal({{ $service['id'] }})"
                                                       class="inline-flex items-center gap-1 px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded font-medium"
                                                       title="Manage Feasibility">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                                        </svg>
                                                        Check
                                                    </button>
                                                @else
                                                    <span class="text-gray-400 text-xs">N/A</span>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="border border-gray-300 px-2 py-2 text-center text-gray-500">No
                                            services added</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ACCEPTANCE -->
                <div class="mb-4">
                    <div class="text-xs">
                        <span class="font-bold">ACCEPTANCE:</span> I hereby confirm that the information provided is
                        correct and wish to subscribe for this service indicated here-in. I have read the terms and
                        conditions as stated and undertake to abide by the same. I hereby certify that I am duly
                        authorised to bind the entity indicated herein as customer. This form together with the said
                        terms constitute a binding agreement with BCC.
                    </div>
                </div>

                <!-- SIGNATURES -->
                <div class="mb-4">
                    <table class="w-full border border-gray-300 text-xs">
                        <tr>
                            <th class="border border-gray-300 px-2 py-1 bg-gray-50">Client Name, signature and Date</th>
                            <th class="border border-gray-300 px-2 py-1 bg-gray-50">Sales Person Name, signature and
                                Date</th>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-0 py-0 align-top">
                                <div class="text-xs p-2">Name: {{ $category === 'Corporate' ? $company : $contact_person }}</div>
                                @if($client && $client->client_signature_data)
                                    <div class="my-2 px-2">
                                        <img src="{{ asset('storage/' . $client->client_signature_data) }}"
                                             alt="Client Signature"
                                             class="h-12 max-w-full object-contain">
                                    </div>
                                    <div class="text-xs px-2 pb-2">Date: {{ $client->client_signed_at->format('Y-m-d') }}</div>
                                @else
                                    <div class="px-2 py-2">
                                        @if($showPrintButton)
                                            <button wire:click="openClientSignatureModal"
                                                    type="button"
                                                    class="text-blue-600 hover:text-blue-800 text-xs underline print:hidden">
                                                Sign Agreement
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Signature will be captured after enrollment</span>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="border border-gray-300 px-0 py-0 align-top">
                                <div class="text-xs p-2">Name: {{ auth()->check() ? auth()->user()->name : 'N/A' }}</div>
                                @if(auth()->check() && auth()->user()->signature_data)
                                    <div class="my-2 px-2">
                                        <img src="{{ asset('storage/' . auth()->user()->signature_data) }}"
                                             alt="Sales Person Signature"
                                             class="h-12 max-w-full object-contain">
                                    </div>
                                @else
                                    <div class="text-xs px-2 text-gray-400 italic">Signature: Not captured</div>
                                @endif
                                <div class="text-xs px-2 pb-2">Date: {{ date('Y-m-d') }}</div>
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-2 py-1 text-xs italic text-gray-600">
                                I warrant that I have been duly authorized to sign this agreement
                            </td>
                            <td class="border border-gray-300 px-2 py-1 text-xs italic text-gray-600">
                                 {{-- @if(auth()->user()->signature_data)
                                    <div class="my-2">
                                        <img src="{{ asset('storage/' . auth()->user()->signature_data) }}"
                                             alt="Signature"
                                             class="h-12 max-w-full object-contain">
                                    </div>
                                @else
                                    <div class="text-xs mt-2 text-gray-400 italic">Signature: Not captured</div>
                                @endif --}}
                                 warrant that I have been duly authorized to sign this agreement
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- FOR OFFICIAL USE ONLY -->
                <div class="mb-4">
                    <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">FOR OFFICIAL USE ONLY:</div>
                    @php
                        $positionsGrid = [
                            ['Sales Manager', 'CCO', 'Credit Control Manager', 'CFO'],
                            ['Business Analysis', 'Network Planning', 'Implementation Manager', 'Director']
                        ];
                    @endphp
                    <table class="w-full border border-gray-300 text-[10px]">
                        @foreach($positionsGrid as $row)
                        <tr>
                            @foreach($row as $position)
                                @php
                                    $authSig = $allAuthSignatures[$position] ?? null;
                                @endphp
                                <td class="border border-gray-300 px-2 py-2 align-top">
                                    <div class="font-semibold mb-2">{{ $position }}</div>
                                    @if($authSig)
                                        @if($authSig->status === 'signed')
                                            <div class="flex flex-col gap-1">
                                                <span class="inline-block px-2 py-0.5 text-[9px] bg-green-100 text-green-700 rounded font-semibold w-fit">Approved</span>
                                                <div class="text-[9px] text-gray-600">{{ $authSig->user->name ?? 'N/A' }}</div>
                                                @if($authSig->signed_at)
                                                    <div class="text-[8px] text-gray-500">{{ $authSig->signed_at->format('M d, Y') }}</div>
                                                @endif
                                            </div>
                                        @elseif($authSig->status === 'rejected')
                                            <div class="flex flex-col gap-1">
                                                <span class="inline-block px-2 py-0.5 text-[9px] bg-red-100 text-red-700 rounded font-semibold w-fit">Rejected</span>
                                                <div class="text-[9px] text-gray-600">{{ $authSig->user->name ?? 'N/A' }}</div>
                                                @if($authSig->updated_at)
                                                    <div class="text-[8px] text-gray-500">{{ $authSig->updated_at->format('M d, Y') }}</div>
                                                @endif
                                                @if($authSig->remarks)
                                                    <div class="mt-1 text-[8px] text-red-600 italic border-t border-red-200 pt-1">
                                                        Note: {{ Str::limit($authSig->remarks, 50) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-block px-2 py-0.5 text-[9px] bg-yellow-100 text-yellow-700 rounded font-semibold w-fit">Pending</span>
                                        @endif
                                    @else
                                        <span class="inline-block px-2 py-0.5 text-[9px] bg-gray-100 text-gray-500 rounded font-semibold w-fit">Awaiting</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </table>
                </div>

                <!-- AUTHORIZATION -->
                <div class="mb-4">
                    <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1 mb-2">AUTHORIZATION:</div>
                    <table class="w-full border-collapse text-[10px]">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border border-gray-300 px-2 py-1 text-left font-semibold">Position/Office</th>
                                <th class="border border-gray-300 px-2 py-1 text-left font-semibold">Name</th>
                                <th class="border border-gray-300 px-2 py-1 text-left font-semibold">Signature</th>
                                <th class="border border-gray-300 px-2 py-1 text-left font-semibold">Date</th>
                                <th class="border border-gray-300 px-2 py-1 text-center font-semibold">Status</th>
                                <th class="border border-gray-300 px-2 py-1 text-center font-semibold print:hidden">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($authorizationSignatures as $authSig)
                            @php
                                $userPositionsList = collect($userPositions)->pluck('position')->toArray();
                                $isUserPosition = in_array($authSig->position, $userPositionsList);
                                $canApproveSequence = $approvalReadiness[$authSig->position] ?? false;
                                $canSign = $isUserPosition && $authSig->status === 'pending' && $canApproveSequence;
                                $isReadOnly = $isUserPosition && $authSig->status === 'pending' && !$canApproveSequence;
                                $canEdit = auth()->user()->can('edit-client-authorization') &&
                                          $isUserPosition &&
                                          ($authSig->status === 'signed' || $authSig->status === 'rejected') &&
                                          $authSig->user_id === auth()->id();
                            @endphp
                            <tr>
                                <td class="border border-gray-300 px-2 py-2 align-top">{{ $authSig->position }}</td>
                                <td class="border border-gray-300 px-2 py-2 align-top">
                                    @if($authSig->status === 'signed')
                                        {{ $authSig->user->name ?? 'N/A' }}
                                    @elseif($authSig->status === 'rejected')
                                        {{ $authSig->user->name ?? 'N/A' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 align-top">
                                    @if($authSig->signature_data && ($authSig->status === 'signed' || $authSig->status === 'rejected'))
                                        <img src="{{ asset('storage/' . $authSig->signature_data) }}"
                                             alt="Signature"
                                             class="h-10 max-w-full object-contain">
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 align-top">
                                    @if($authSig->signed_at)
                                        {{ $authSig->signed_at->format('Y-m-d') }}
                                    @elseif($authSig->status === 'rejected' && $authSig->updated_at)
                                        {{ $authSig->updated_at->format('Y-m-d') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="border border-gray-300 px-2 py-2 align-top">
                                    <div class="flex flex-col items-center gap-1">
                                        @if($authSig->status === 'signed')
                                            <span class="inline-block px-2 py-0.5 text-[9px] bg-green-100 text-green-700 rounded font-semibold">Approved</span>
                                        @elseif($authSig->status === 'rejected')
                                            <span class="inline-block px-2 py-0.5 text-[9px] bg-red-100 text-red-700 rounded font-semibold">Rejected</span>
                                            @if($authSig->remarks)
                                                <button wire:click="showRemarks('{{ $authSig->position }}')"
                                                        type="button"
                                                        class="text-blue-600 hover:text-blue-800 text-[8px] underline print:hidden"
                                                        title="View rejection reason">
                                                    View Note
                                                </button>
                                            @endif
                                        @else
                                            <span class="inline-block px-2 py-0.5 text-[9px] bg-yellow-100 text-yellow-700 rounded font-semibold">Pending</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="border border-gray-300 px-2 py-2 text-center align-top print:hidden">
                                    @if($canSign)
                                        <div class="flex flex-col gap-1">
                                            <button wire:click="openAuthSignatureModal('{{ $authSig->position }}')"
                                                    type="button"
                                                    class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-[9px] rounded font-medium">
                                                Approve
                                            </button>
                                            <button wire:click="openRejectModal('{{ $authSig->position }}')"
                                                    type="button"
                                                    class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-[9px] rounded font-medium">
                                                Reject
                                            </button>
                                        </div>
                                    @elseif($isReadOnly)
                                        <div class="flex flex-col gap-1 items-center">
                                            <span class="inline-block px-2 py-0.5 text-[9px] bg-gray-100 text-gray-600 rounded font-semibold">Read Only</span>
                                            <span class="text-[8px] text-gray-500 text-center leading-tight">Waiting for previous approval</span>
                                        </div>
                                    @elseif($canEdit)
                                        <div class="flex flex-col gap-1">
                                            <button wire:click="resetAuthStatus('{{ $authSig->position }}')"
                                                    type="button"
                                                    class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-[9px] rounded font-medium"
                                                    title="Reset and resubmit">
                                                Edit Status
                                            </button>
                                            @if($authSig->status === 'signed')
                                                <button wire:click="openRejectModal('{{ $authSig->position }}')"
                                                        type="button"
                                                        class="px-2 py-1 bg-orange-600 hover:bg-orange-700 text-white text-[9px] rounded font-medium"
                                                        title="Change to rejected">
                                                    Change to Reject
                                                </button>
                                            @else
                                                <button wire:click="openAuthSignatureModal('{{ $authSig->position }}')"
                                                        type="button"
                                                        class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-[9px] rounded font-medium"
                                                        title="Change to approved">
                                                    Change to Approve
                                                </button>
                                            @endif
                                        </div>
                                    @elseif($authSig->status === 'signed')
                                        <span class="text-[9px] text-gray-400 italic">Completed</span>
                                    @elseif($authSig->status === 'rejected')
                                        <span class="text-[9px] text-gray-400 italic">Rejected</span>
                                    @else
                                        <span class="text-[9px] text-gray-400 italic">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="text-[9px] italic text-gray-600 px-2 py-2 border border-gray-300 border-t-0 bg-gray-50">
                        All persons in the approval chain must satisfy that the content in the request form is complete, accurate & serves value for money
                    </div>
                </div>

                <!-- SERVICE TERMS AND CONDITIONS -->
                <div x-data="{ expanded: false }">
                    <div class="flex items-center justify-between mb-2">
                        <div class="bg-gray-800 text-white text-xs font-bold px-2 py-1">SERVICE TERMS AND CONDITIONS
                        </div>
                        <button type="button" @click="expanded = !expanded"
                            class="px-3 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 flex items-center gap-1 print:hidden">
                            <span x-text="expanded ? 'Show Less' : 'Read More'"></span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-200"
                                :class="expanded ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative">
                        <!-- Collapsed View with Blur Effect -->
                        <div x-show="!expanded" class="relative">
                            <div class="text-xs space-y-2 max-h-32 overflow-hidden">
                                <div>
                                    <span class="font-bold">Provision of service</span><br>
                                    Subject to terms and conditions of this agreement, Blue Crane Communications will
                                    provide connectivity services and will endeavour to make service available to the
                                    customer throughout the agreed period of service.
                                </div>
                                <div>
                                    Blue Crane Communications has the right to change terms and conditions upon which
                                    service is offered in this agreement, as a result of new legislation, gov't
                                    directive, policy, technological and/or deemed fit.
                                </div>
                                <div>
                                    The customer recognises that the service by its very nature, may from time to time
                                    be adversely affected by natural and man-made phenomena including but not limited
                                    to: weather conditions, construction, civil unrest or radiation from other radio
                                    sources etc.; BCC shall not be liable for any disruption/interruption resulting from
                                    such phenomena, but shall endeavour to inform the customer by email, SMS, phone call
                                    or any other means available at that material time of such occur.
                                </div>
                                <div>
                                    <span class="font-bold">The customer hereby agrees:-</span>
                                    <ul class="list-disc ml-5 mt-1">
                                        <li>To pay fees, for the provision of use of connectivity services, all fees,
                                            payable in full, before use (prepaid)</li>
                                        <li>To use the network lawfully and abide by all rules and regulations that
                                            govern such use by UCC and the government of Uganda</li>
                                        <li>To allow BCC reasonable access to the premises and transit-controlled areas
                                            where it shall install, service, maintain and repair equipment.</li>
                                        <li>To pay BCC the invoiced amounts net of any lawful deductions or charges by
                                            the date indicated on the invoice, and failure to do so may result in
                                            suspension or disconnection of the service</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Gradient Blur Overlay -->
                            <div
                                class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-b from-transparent via-white/40 to-white pointer-events-none backdrop-blur-sm">
                            </div>
                        </div>

                        <!-- Expanded View -->
                        <div x-show="expanded" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            class="text-xs space-y-2">
                            <div>
                                <span class="font-bold">Provision of service</span><br>
                                Subject to terms and conditions of this agreement, Blue Crane Communications will
                                provide connectivity services and will endeavour to make service available to the
                                customer throughout the agreed period of service.
                            </div>
                            <div>
                                Blue Crane Communications has the right to change terms and conditions upon which
                                service is offered in this agreement, as a result of new legislation, gov't directive,
                                policy, technological and/or deemed fit.
                            </div>
                            <div>
                                The customer recognises that the service by its very nature, may from time to time be
                                adversely affected by natural and man-made phenomena including but not limited to:
                                weather conditions, construction, civil unrest or radiation from other radio sources
                                etc.; BCC shall not be liable for any disruption/interruption resulting from such
                                phenomena, but shall endeavour to inform the customer by email, SMS, phone call or any
                                other means available at that material time of such occur.
                            </div>
                            <div>
                                <span class="font-bold">The customer hereby agrees:-</span>
                                <ul class="list-disc ml-5 mt-1">
                                    <li>To pay fees, for the provision of use of connectivity services, all fees,
                                        payable in full, before use (prepaid)</li>
                                    <li>To use the network lawfully and abide by all rules and regulations that govern
                                        such use by UCC and the government of Uganda</li>
                                    <li>To allow BCC reasonable access to the premises and transit-controlled areas
                                        where it shall install, service, maintain and repair equipment.</li>
                                    <li>To pay BCC the invoiced amounts net of any lawful deductions or charges by the
                                        date indicated on the invoice, and failure to do so may result in suspension or
                                        disconnection of the service</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section (if exists) -->
                @if ($notes)
                    <div class="mt-4 pt-4 border-t border-gray-300">
                        <div class="text-xs">
                            <span class="font-bold">Additional Notes:</span><br>
                            {{ $notes }}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            @if ($showPrintButton)
                <div class="mt-4 sm:mt-6 flex gap-2 sm:gap-3 print:hidden">
                    <button type="button" wire:loading.attr="disabled" onclick="window.print()"
                        class="px-4 sm:px-6 py-2 bg-green-600 hover:bg-green-700 disabled:bg-green-400 text-white rounded-lg font-medium flex items-center gap-2 text-sm">
                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:w-5 sm:h-5"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        <svg wire:loading class="animate-spin w-4 h-4 sm:w-5 sm:h-5"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="hidden sm:inline">Print Document</span>
                        <span class="sm:hidden">Print</span>
                    </button>
                </div>
            @endif
            </div>
            <!-- End Main Document -->
        </div>
    </div>

    <!-- Client Signature Modal -->
    @if($showClientSignatureModal)
        <div class="fixed inset-0 z-[9999] overflow-y-auto" style="background: rgba(0,0,0,0.5);">
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div class="fixed inset-0 bg-black opacity-50" wire:click="closeClientSignatureModal"></div>

                <div class="relative bg-white rounded-lg shadow-2xl max-w-2xl w-full p-6 z-[10000]" style="border: 3px solid red;" x-data="clientSignaturePad()" x-init="init()">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Client Signature</h3>
                        <button wire:click="closeClientSignatureModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Draw your signature below:</label>
                            <div class="border-2 border-gray-300 rounded-lg overflow-hidden">
                                <canvas x-ref="canvas"
                                        width="600"
                                        height="200"
                                        class="w-full cursor-crosshair bg-white"
                                        style="touch-action: none; display: block;"
                                        x-on:mousedown="startDrawing($event)"
                                        x-on:mousemove="draw($event)"
                                        x-on:mouseup="stopDrawing()"
                                        x-on:mouseleave="stopDrawing()"
                                        x-on:touchstart.prevent="startDrawing($event)"
                                        x-on:touchmove.prevent="draw($event)"
                                        x-on:touchend.prevent="stopDrawing()">
                                </canvas>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <button x-on:click="clearCanvas()"
                                    type="button"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                Clear
                            </button>
                            <div class="flex gap-2">
                                <button wire:click="closeClientSignatureModal"
                                        type="button"
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                    Cancel
                                </button>
                                <button x-on:click="saveSignature()"
                                        type="button"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                                    Save Signature
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Authorization Signature Modal -->
    @if($showAuthSignatureModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" wire:click="closeAuthSignatureModal"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6"
                     x-data="signaturePad('auth', '{{ (auth()->check() && auth()->user()->signature_data) ? asset('storage/' . auth()->user()->signature_data) : '' }}')"
                     x-init="init()">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Authorization Signature</h3>
                            <p class="text-sm text-gray-600">Position: {{ $currentAuthPosition }}</p>
                        </div>
                        <button wire:click="closeAuthSignatureModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        @if(auth()->check() && auth()->user()->signature_data)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-blue-900">Your saved signature has been loaded</span>
                                </div>
                                <p class="text-xs text-blue-700">You can use your existing signature or draw a new one. If you draw a new signature, it will update your saved signature for future use.</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                @if(auth()->check() && auth()->user()->signature_data)
                                    Use existing signature or draw a new one:
                                @else
                                    Draw your signature below:
                                @endif
                            </label>
                            <div class="border-2 border-gray-300 rounded-lg overflow-hidden">
                                <canvas x-ref="canvas"
                                        width="600"
                                        height="200"
                                        class="w-full cursor-crosshair bg-white"
                                        style="touch-action: none; display: block;"
                                        x-on:mousedown="startDrawing($event)"
                                        x-on:mousemove="draw($event)"
                                        x-on:mouseup="stopDrawing()"
                                        x-on:mouseleave="stopDrawing()"
                                        x-on:touchstart.prevent="startDrawing($event)"
                                        x-on:touchmove.prevent="draw($event)"
                                        x-on:touchend.prevent="stopDrawing()">
                                </canvas>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <button x-on:click="clearCanvas()"
                                    type="button"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                Clear
                            </button>
                            <div class="flex gap-2">
                                <button wire:click="closeAuthSignatureModal"
                                        type="button"
                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                    Cancel
                                </button>
                                <button x-on:click="saveSignature()"
                                        type="button"
                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                                    Approve & Sign
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Reject Modal -->
    @if($showRejectModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" wire:click="closeRejectModal"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Reject Authorization</h3>
                            <p class="text-sm text-gray-600">Position: {{ $currentAuthPosition }}</p>
                        </div>
                        <button wire:click="closeRejectModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Reason for rejection <span class="text-red-500">*</span>
                            </label>
                            <textarea wire:model="rejectionNote"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                      placeholder="Please provide a reason for rejecting this authorization..."></textarea>
                            @error('rejectionNote')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end gap-2">
                            <button wire:click="closeRejectModal"
                                    type="button"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                Cancel
                            </button>
                            <button wire:click="saveRejection"
                                    type="button"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                                Confirm Rejection
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- View Remarks Modal -->
    @if($showRemarksModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" wire:click="closeRemarksModal"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Rejection Note</h3>
                            <p class="text-sm text-gray-600">Position: {{ $viewRemarksPosition }}</p>
                        </div>
                        <button wire:click="closeRemarksModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-sm text-gray-700">{{ $viewRemarksContent }}</p>
                        </div>

                        <div class="flex justify-end">
                            <button wire:click="closeRemarksModal"
                                    type="button"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        function signaturePad(type, existingSignatureUrl = '') {
            return {
                canvas: null,
                ctx: null,
                isDrawing: false,
                lastX: 0,
                lastY: 0,
                hasDrawn: false,

                init() {
                    console.log('SignaturePad init called for type:', type);
                    this.canvas = this.$refs.canvas;
                    if (!this.canvas) {
                        console.error('Canvas not found');
                        return;
                    }

                    console.log('Canvas found and initialized');
                    this.ctx = this.canvas.getContext('2d');
                    this.ctx.strokeStyle = '#000000';
                    this.ctx.lineWidth = 2;
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';

                    // Load existing signature if available
                    if (existingSignatureUrl && type === 'auth') {
                        this.loadExistingSignature(existingSignatureUrl);
                    }
                },

                loadExistingSignature(url) {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = () => {
                        // Clear canvas first
                        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                        // Draw image centered
                        const scale = Math.min(
                            this.canvas.width / img.width,
                            this.canvas.height / img.height
                        );
                        const x = (this.canvas.width - img.width * scale) / 2;
                        const y = (this.canvas.height - img.height * scale) / 2;
                        this.ctx.drawImage(img, x, y, img.width * scale, img.height * scale);
                    };
                    img.onerror = () => {
                        console.error('Failed to load existing signature');
                    };
                    img.src = url;
                },

                getCoordinates(e) {
                    const rect = this.canvas.getBoundingClientRect();
                    const scaleX = this.canvas.width / rect.width;
                    const scaleY = this.canvas.height / rect.height;

                    let clientX, clientY;

                    if (e.touches && e.touches.length > 0) {
                        clientX = e.touches[0].clientX;
                        clientY = e.touches[0].clientY;
                    } else {
                        clientX = e.clientX;
                        clientY = e.clientY;
                    }

                    return {
                        x: (clientX - rect.left) * scaleX,
                        y: (clientY - rect.top) * scaleY
                    };
                },

                startDrawing(e) {
                    e.preventDefault();
                    this.isDrawing = true;
                    this.hasDrawn = true;
                    const coords = this.getCoordinates(e);
                    this.lastX = coords.x;
                    this.lastY = coords.y;

                    this.ctx.beginPath();
                    this.ctx.arc(coords.x, coords.y, this.ctx.lineWidth / 2, 0, Math.PI * 2);
                    this.ctx.fill();
                },

                draw(e) {
                    if (!this.isDrawing) return;
                    e.preventDefault();

                    const coords = this.getCoordinates(e);

                    this.ctx.beginPath();
                    this.ctx.moveTo(this.lastX, this.lastY);
                    this.ctx.lineTo(coords.x, coords.y);
                    this.ctx.stroke();

                    this.lastX = coords.x;
                    this.lastY = coords.y;
                },

                stopDrawing() {
                    this.isDrawing = false;
                },

                clearCanvas() {
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                },

                saveSignature() {
                    console.log('Save signature called for type:', type);
                    const dataURL = this.canvas.toDataURL('image/png');
                    console.log('Signature data length:', dataURL.length);
                    if (type === 'client') {
                        console.log('Saving client signature...');
                        @this.set('clientSignatureData', dataURL);
                        @this.call('saveClientSignature');
                    } else if (type === 'auth') {
                        console.log('Saving auth signature...');
                        @this.set('authSignatureData', dataURL);
                        @this.set('hasDrawnNewSignature', this.hasDrawn);
                        @this.call('saveAuthSignature');
                    }
                }
            }
        }

        // Separate simple signature pad for client signatures
        function clientSignaturePad() {
            return {
                canvas: null,
                ctx: null,
                isDrawing: false,
                lastX: 0,
                lastY: 0,

                init() {
                    console.log('Client signature pad initializing...');
                    this.canvas = this.$refs.canvas;
                    if (!this.canvas) {
                        console.error('Canvas not found');
                        return;
                    }

                    this.ctx = this.canvas.getContext('2d');
                    this.ctx.strokeStyle = '#000000';
                    this.ctx.lineWidth = 2;
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';

                    console.log('Client signature pad initialized successfully');
                },

                getCoordinates(e) {
                    const rect = this.canvas.getBoundingClientRect();
                    const scaleX = this.canvas.width / rect.width;
                    const scaleY = this.canvas.height / rect.height;

                    let clientX, clientY;

                    if (e.touches && e.touches.length > 0) {
                        clientX = e.touches[0].clientX;
                        clientY = e.touches[0].clientY;
                    } else {
                        clientX = e.clientX;
                        clientY = e.clientY;
                    }

                    return {
                        x: (clientX - rect.left) * scaleX,
                        y: (clientY - rect.top) * scaleY
                    };
                },

                startDrawing(e) {
                    e.preventDefault();
                    this.isDrawing = true;
                    const coords = this.getCoordinates(e);
                    this.lastX = coords.x;
                    this.lastY = coords.y;

                    this.ctx.beginPath();
                    this.ctx.arc(coords.x, coords.y, this.ctx.lineWidth / 2, 0, Math.PI * 2);
                    this.ctx.fill();
                },

                draw(e) {
                    if (!this.isDrawing) return;
                    e.preventDefault();

                    const coords = this.getCoordinates(e);

                    this.ctx.beginPath();
                    this.ctx.moveTo(this.lastX, this.lastY);
                    this.ctx.lineTo(coords.x, coords.y);
                    this.ctx.stroke();

                    this.lastX = coords.x;
                    this.lastY = coords.y;
                },

                stopDrawing() {
                    this.isDrawing = false;
                },

                clearCanvas() {
                    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                },

                saveSignature() {
                    console.log('Saving client signature...');
                    const dataURL = this.canvas.toDataURL('image/png');
                    console.log('Signature data length:', dataURL.length);
                    @this.set('clientSignatureData', dataURL);
                    @this.call('saveClientSignature');
                }
            }
        }
    </script>

    <!-- Feasibility Check Modal -->
    @if($showFeasibilityModal && $currentService)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div class="fixed inset-0 bg-black opacity-50" wire:click="closeFeasibilityModal"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                    <!-- Modal Header -->
                    <div class="bg-white border-b border-gray-200 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Service Feasibility Check</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Service: {{ $currentService->product->vendorService->service_name ?? 'N/A' }} - {{ $currentService->product->name ?? 'N/A' }}
                                </p>
                            </div>
                            <button wire:click="closeFeasibilityModal" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="flex-1 overflow-y-auto">
                        @livewire('service-feasibility.manage-feasibility', ['clientServiceId' => $currentServiceId], key('feasibility-'.$currentServiceId))
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
