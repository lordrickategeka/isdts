<div class="min-h-screen bg-gray-50">
    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-content, #print-content * {
                visibility: visible;
            }
            #print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .print\:hidden {
                display: none !important;
            }
            .border-2 {
                border-width: 1px !important;
            }
        }
    </style>

    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200 print:hidden">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-1xl font-bold text-black">Customer Agreement</h1>
                <p class="text-sm text-gray-500">Step {{ $currentStep }} of {{ $totalSteps }}</p>
            </div>
            <div class="flex items-center gap-3">
                @can('create_clients')
                <a href="{{ route('clients.shareable-links') }}" class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                    <span>Share Link</span>
                </a>
                @endcan
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">
                    ← Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 print:hidden">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Progress Steps -->
            <div class="mb-8 print:hidden">
                <div class="flex items-center justify-between">
                    @for ($i = 1; $i <= $totalSteps; $i++)
                        <div class="flex-1 {{ $i < $totalSteps ? 'mr-2' : '' }}">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full
                                    {{ $currentStep >= $i ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}
                                    font-semibold">
                                    {{ $i }}
                                </div>
                                @if ($i < $totalSteps)
                                    <div class="flex-1 h-1 mx-2
                                        {{ $currentStep > $i ? 'bg-blue-600' : 'bg-gray-300' }}">
                                    </div>
                                @endif
                            </div>
                            <div class="text-xs mt-2 text-center {{ $currentStep >= $i ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">
                                @if ($i == 1) Client Info
                                @elseif ($i == 2) Contact/Location
                                @elseif ($i == 3) Service Details
                                @elseif ($i == 4) Additional Info.
                                @else Review
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Error/Success Messages -->
                @if (session()->has('error'))
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="@if($currentStep == $totalSteps) submit @else nextStep @endif"
                    x-data="{ submitAttempt: 0 }"
                    @submit="console.log('Form submitted! Step:', {{ $currentStep }}, 'Attempt:', ++submitAttempt); console.log('Action:', '{{ $currentStep == $totalSteps ? 'submit' : 'nextStep' }}')">
                    <!-- Step 1: Client Information -->
                    @if ($currentStep == 1)
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Select Client Category</h2>

                            <!-- Category Selection Cards -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- Home Client Card -->
                                <div wire:click="$set('category', 'Home')"
                                    class="cursor-pointer border-2 rounded-lg p-4 transition-all duration-200 hover:shadow-lg
                                        {{ $category === 'Home' ? 'border-blue-600 bg-blue-50' : 'border-gray-300 hover:border-blue-400' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 h-10 rounded-full {{ $category === 'Home' ? 'bg-blue-600' : 'bg-gray-200' }} flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $category === 'Home' ? 'text-white' : 'text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-semibold {{ $category === 'Home' ? 'text-blue-600' : 'text-gray-800' }}">Home Client</h3>
                                                <p class="text-xs text-gray-600">Individual or residential customer</p>
                                            </div>
                                        </div>
                                        @if($category === 'Home')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>

                                <!-- Corporate Client Card -->
                                <div wire:click="$set('category', 'Corporate')"
                                    class="cursor-pointer border-2 rounded-lg p-4 transition-all duration-200 hover:shadow-lg
                                        {{ $category === 'Corporate' ? 'border-blue-600 bg-blue-50' : 'border-gray-300 hover:border-blue-400' }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-10 h-10 rounded-full {{ $category === 'Corporate' ? 'bg-blue-600' : 'bg-gray-200' }} flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $category === 'Corporate' ? 'text-white' : 'text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-sm font-semibold {{ $category === 'Corporate' ? 'text-blue-600' : 'text-gray-800' }}">Corporate Client</h3>
                                                <p class="text-xs text-gray-600">Business or organizational customer</p>
                                            </div>
                                        </div>
                                        @if($category === 'Corporate')
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                            <!-- Show fields only when a category is selected -->
                            @if($category)
                                <!-- Corporate Subcategory Dropdown (shown only when Corporate is selected) -->
                                @if($category === 'Corporate')
                                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Corporate Category Type <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="category_type"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Select Corporate Type</option>
                                            <option value="SME">SME (Small & Medium Enterprise)</option>
                                            <option value="Business">Business</option>
                                            <option value="Government">Government</option>
                                            <option value="NGO">NGO (Non-Governmental Organization)</option>
                                        </select>
                                        @error('category_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                <h2 class="text-lg font-semibold text-gray-800 mb-3 pt-3 border-t">Client Information</h2>

                                @if($category === 'Home')
                                    <!-- Home Client: Only Full Name -->
                                    <div class="grid grid-cols-1 gap-4">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                                Full Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text"
                                                wire:model="contact_person"
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Enter full name">
                                            @error('contact_person') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                @else
                                    <!-- Corporate Client: All Business Fields -->
                                    <div class="space-y-4">
                                        <!-- Company Name and Nature of Business - Side by Side -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                                    Company/Name
                                                </label>
                                                <input type="text"
                                                    wire:model="company"
                                                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="Company Name">
                                                @error('company') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>

                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">
                                                    Nature of Business
                                                </label>
                                                <input type="text"
                                                    wire:model="nature_of_business"
                                                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="e.g., Technology, Retail">
                                                @error('nature_of_business') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <!-- Business Phone, Business Email, TIN No - Side by Side -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                                Business Contact Details
                                            </label>
                                            <div class="flex gap-2">
                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Business Phone</label>
                                                    <input type="tel"
                                                        wire:model="business_phone"
                                                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                        placeholder="+256 XXX XXXXXX">
                                                    @error('business_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>

                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Business Email</label>
                                                    <input type="email"
                                                        wire:model="business_email"
                                                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                        placeholder="email@company.com">
                                                    @error('business_email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>

                                                <div class="flex-1">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">TIN No.</label>
                                                    <input type="number"
                                                        wire:model="tin_no"
                                                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                        placeholder="Tax Identification Number">
                                                    @error('tin_no') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif

                    <!-- Step 2: Contact Information & Location -->
                    @if ($currentStep == 2)
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Contact Information</h2>

                            @if($category === 'Home')
                                <!-- Home Client: Show Phone and Email fields -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Phone <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel"
                                            wire:model="phone"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="+256 XXX XXXXXX">
                                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Email Address <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email"
                                            wire:model="email"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="email@example.com">
                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Alternative Phone Number
                                        </label>
                                        <input type="tel"
                                            wire:model="alternative_contact"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="+256 XXX XXXXXX">
                                        @error('alternative_contact') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Designation
                                        </label>
                                        <input type="text"
                                            wire:model="designation"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Job Title">
                                        @error('designation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @else
                                <!-- Corporate Client: Show Contact Person details -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Contact Person <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            wire:model="contact_person"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Contact Person Name">
                                        @error('contact_person') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Designation
                                        </label>
                                        <input type="text"
                                            wire:model="designation"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Job Title">
                                        @error('designation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Contact Person Phone <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel"
                                            wire:model="phone"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="+256 XXX XXXXXX">
                                        @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Contact Person Email <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email"
                                            wire:model="email"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="contactperson@email.com">
                                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Alternative Phone Number
                                        </label>
                                        <input type="tel"
                                            wire:model="alternative_contact"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="+256 XXX XXXXXX">
                                        @error('alternative_contact') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endif

                            <h2 class="text-lg font-semibold text-gray-800 mb-3 pt-4 border-t">Location Details</h2>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Physical Address <span class="text-red-500">*</span>
                                    </label>
                                    <textarea
                                        wire:model="address"
                                        rows="2"
                                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Full physical address"></textarea>
                                    @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Coordinates
                                    </label>
                                    <div class="flex gap-2">
                                        <div class="flex-1">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Latitude</label>
                                            <input type="text"
                                                wire:model="latitude"
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Latitude (e.g., 0.3476)">
                                            @error('latitude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Longitude</label>
                                            <input type="text"
                                                wire:model="longitude"
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Longitude (e.g., 32.5825)">
                                            @error('longitude') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <button type="button"
                                            class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg border border-gray-300 flex items-center gap-1.5 whitespace-nowrap"
                                            title="Pick from Google Maps (Coming Soon)"
                                            disabled>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="hidden sm:inline text-xs">Pick Location</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Step 3: Service Details -->
                    @if ($currentStep == 3)
                        @error('services')
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Left: Service Form -->
                            <div class="lg:col-span-2 space-y-4">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-lg font-semibold text-gray-800">Service Details</h2>
                                    @if(session()->has('service_added'))
                                        <span class="text-xs text-green-600 font-medium">{{ session('service_added') }}</span>
                                    @endif
                                </div>

                            <div class="space-y-3">
                                <!-- Service Type -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Service Type <span class="text-red-500">*</span>
                                    </label>
                                    <select wire:model.live="service_type_id"
                                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select Service Type</option>
                                        @foreach($serviceTypes as $serviceType)
                                            <option value="{{ $serviceType->id }}">{{ $serviceType->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('service_type_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Product Type -->
                                @if(count($products) > 0)
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Product <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model.live="product_id"
                                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">
                                                    {{ $product->name }}
                                                    @if($product->subcategory)
                                                        ({{ $product->subcategory->name }})
                                                    @endif
                                                    @if($product->price)
                                                        - UGX {{ number_format($product->price, 0) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('product_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                @endif

                                <!-- Capacity, Installation Charge, Monthly Charge in one row -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Product Details
                                    </label>
                                    <div class="flex gap-2">
                                        <div class="flex-1">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Capacity</label>
                                            <input type="text"
                                                wire:model="capacity"
                                                readonly
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Capacity">
                                            @error('capacity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Installation Charge</label>
                                            <input type="number"
                                                wire:model="installation_charge"
                                                step="0.01"
                                                readonly
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Installation Charge">
                                            @error('installation_charge') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="flex-1">
                                            <label class="block text-xs font-medium text-gray-600 mb-1">Monthly Charge</label>
                                            <input type="number"
                                                wire:model="monthly_charge"
                                                step="0.01"
                                                readonly
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Monthly Charge">
                                            @error('monthly_charge') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">
                                        Contract Start Date
                                    </label>
                                    <input type="date"
                                        wire:model="contract_start_date"
                                        class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    @error('contract_start_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Add Service Button -->
                            <div class="flex justify-end pt-4">
                                <button type="button"
                                    wire:click="addService"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Add Service
                                </button>
                            </div>
                        </div>

                        <!-- Right: Services List -->
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 rounded-lg p-4 sticky top-4">
                                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Added Services ({{ count($services) }})</h3>

                                    @if(count($services) > 0)
                                        <div class="space-y-3">
                                            @foreach($services as $index => $service)
                                                <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                    <div class="flex items-start justify-between mb-2">
                                                        <div class="flex-1">
                                                            <div class="text-xs font-semibold text-gray-800">{{ $service['service_type_name'] }}</div>
                                                            <div class="text-xs text-gray-600">
                                                                {{ $service['product_name'] }}
                                                                @if($service['subcategory_name'])
                                                                    ({{ $service['subcategory_name'] }})
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <button type="button"
                                                            wire:click="removeService({{ $index }})"
                                                            class="text-red-500 hover:text-red-700">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                    <div class="text-xs text-gray-500 space-y-1">
                                                        @if($service['capacity'])
                                                            <div><span class="font-medium">Capacity:</span> {{ $service['capacity'] }}</div>
                                                        @endif
                                                        @if($service['installation_charge'])
                                                            <div><span class="font-medium">Installation:</span> UGX {{ number_format($service['installation_charge'], 0) }}</div>
                                                        @endif
                                                        @if($service['monthly_charge'])
                                                            <div><span class="font-medium">Monthly:</span> UGX {{ number_format($service['monthly_charge'], 0) }}</div>
                                                        @endif
                                                        @if($service['contract_start_date'])
                                                            <div><span class="font-medium">Start:</span> {{ date('M d, Y', strtotime($service['contract_start_date'])) }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-8 text-gray-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                            <p class="text-xs">No services added yet</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Step 4: Additional Information & Review -->
                    @if ($currentStep == 4)
                        <div class="space-y-6">
                            <!-- Payment Information Section -->
                            <div class="border-2 border-blue-300 p-4 bg-blue-50 rounded-lg">
                                <h3 class="text-sm font-bold text-gray-800 mb-4">Payment Information</h3>

                                <!-- Payment Type Selection -->
                                <div class="mb-4">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">
                                        Payment Type <span class="text-red-500">*</span>
                                    </label>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <!-- Pre-paid Option -->
                                        <div wire:click="$set('payment_type', 'prepaid')"
                                            class="cursor-pointer border-2 rounded-lg p-3 transition-all duration-200 hover:shadow-md
                                                {{ $payment_type === 'prepaid' ? 'border-blue-600 bg-white' : 'border-gray-300 bg-white hover:border-blue-400' }}">
                                            <div class="flex items-center gap-2">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                    {{ $payment_type === 'prepaid' ? 'border-blue-600' : 'border-gray-300' }}">
                                                    @if($payment_type === 'prepaid')
                                                        <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold {{ $payment_type === 'prepaid' ? 'text-blue-600' : 'text-gray-800' }}">
                                                        Pre-paid
                                                    </div>
                                                    <div class="text-xs text-gray-600">Payment made in advance</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Post-paid Option -->
                                        <div wire:click="$set('payment_type', 'postpaid')"
                                            class="cursor-pointer border-2 rounded-lg p-3 transition-all duration-200 hover:shadow-md
                                                {{ $payment_type === 'postpaid' ? 'border-blue-600 bg-white' : 'border-gray-300 bg-white hover:border-blue-400' }}">
                                            <div class="flex items-center gap-2">
                                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center
                                                    {{ $payment_type === 'postpaid' ? 'border-blue-600' : 'border-gray-300' }}">
                                                    @if($payment_type === 'postpaid')
                                                        <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-semibold {{ $payment_type === 'postpaid' ? 'text-blue-600' : 'text-gray-800' }}">
                                                        Post-paid
                                                    </div>
                                                    <div class="text-xs text-gray-600">Payment after service delivery</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @error('payment_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Proof of Payment Upload (shown only for prepaid) -->
                                @if($payment_type === 'prepaid')
                                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <label class="block text-xs font-medium text-gray-700 mb-2">
                                            Proof of Payment <span class="text-red-500">*</span>
                                        </label>
                                        <input type="file"
                                            wire:model="proof_of_payment"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        <p class="text-xs text-gray-500 mt-1">Upload PDF, JPG, or PNG (max 2MB)</p>
                                        @error('proof_of_payment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                        @if($proof_of_payment)
                                            <div class="mt-2 flex items-center gap-2 text-xs text-green-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>File selected: {{ $proof_of_payment->getClientOriginalName() }}</span>
                                            </div>
                                        @endif

                                        <div wire:loading wire:target="proof_of_payment" class="mt-2 text-xs text-blue-600">
                                            <svg class="animate-spin h-4 w-4 inline mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Uploading...
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @livewire('clients.client-agreement-document', [
                                'data' => [
                                    'agreement_number' => $agreement_number,
                                    'tin_no' => $tin_no,
                                    'company' => $company,
                                    'contact_person' => $contact_person,
                                    'nature_of_business' => $nature_of_business,
                                    'address' => $address,
                                    'designation' => $designation,
                                    'phone' => $phone,
                                    'alternative_contact' => $alternative_contact,
                                    'email' => $email,
                                    'latitude' => $latitude,
                                    'longitude' => $longitude,
                                    'category' => $category,
                                    'notes' => $notes,
                                    'services' => $this->servicesForAgreement,
                                ],
                                'showPrintButton' => false
                            ])

                            <!-- Notes Section (Editable in enrollment) -->
                            <div class="border-2 border-gray-300 p-4 bg-white">
                                <label class="block text-xs font-bold text-gray-700 mb-2">Additional Notes</label>
                                <textarea
                                    wire:model="notes"
                                    rows="3"
                                    class="w-full px-3 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Any additional notes or comments..."></textarea>
                                @error('notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                        <button type="button"
                            wire:click="previousStep"
                            @if($currentStep == 1) disabled @endif
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>

                        <button type="submit"
                            wire:loading.attr="disabled"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg font-medium disabled:opacity-50 flex items-center gap-2">
                            @if ($currentStep == $totalSteps)
                                <svg wire:loading wire:target="submit" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="submit">Submit & Enroll</span>
                                <span wire:loading wire:target="submit">Submitting...</span>
                            @else
                                <svg wire:loading wire:target="nextStep" class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="nextStep">Next Step</span>
                                <span wire:loading wire:target="nextStep">Processing...</span>
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
