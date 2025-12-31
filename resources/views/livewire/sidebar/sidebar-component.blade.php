<div class="drawer-side z-40">
    <label for="main-drawer" class="drawer-overlay"></label>

    <!-- Sidebar Content -->
    <aside class="bg-base-100 w-64 min-h-screen  border-r border-gray-200 flex flex-col">
        <!-- Logo/Brand Section -->
        <div class="p-6 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-black">ISDTS Core</h2>
                    <p class="text-xs text-gray-500">Management System</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="menu p-4 space-y-2 flex-1 overflow-y-auto">
            <!-- Dashboard -->
            @can('access_dashboard')
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-black' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
            @endcan

            <!-- Clients -->
            @can('view_clients')
                <li>
                    <details class="dropdown" {{ request()->routeIs('clients.*') ? 'open' : '' }}>
                        <summary
                            class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('clients.*') ? 'bg-primary text-white' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 {{ request()->routeIs('clients.*') ? 'text-white' : 'text-black' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Clients</span>
                        </summary>
                        <ul class="ml-8 mt-2 space-y-1">
                            <li>
                                <a href="{{ route('clients.index') }}"
                                    class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('clients.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    All Clients
                                </a>
                            </li>
                            @can('create_clients')
                                <li>
                                    <a href="{{ route('clients.enroll') }}"
                                        class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('clients.enroll') ? 'bg-blue-100 font-semibold' : '' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                        Add Client
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('clients.shareable-links') }}"
                                        class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('clients.shareable-links') ? 'bg-blue-100 font-semibold' : '' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        Share Link
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </details>
                </li>
            @endcan


            <!-- Leads -->
            {{-- @can('view_leads') --}}
            <li>
                <details class="dropdown" {{ request()->routeIs('leads.*') ? 'open' : '' }}>
                    <summary
                        class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('leads.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 {{ request()->routeIs('leads.*') ? 'text-white' : 'text-black' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7a4 4 0 118 0 4 4 0 01-8 0zM3 21a8 8 0 1118 0H3z" />
                        </svg>
                        <span>Leads</span>
                    </summary>
                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('leads.index') }}"
                                class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('leads.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                All Leads
                            </a>
                        </li>
                        {{-- @can('create_leads') --}}
                        <li>
                            <a href="{{ route('leads.create') }}"
                                class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('leads.create') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add Lead
                            </a>
                        </li>
                        {{-- @endcan --}}

                    </ul>
                </details>
            </li>
            {{-- @endcan --}}

            <!-- Users -->
            @can('view_users')
                <li>
                    <a href="#" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Users</span>
                    </a>
                </li>
            @endcan



            <!-- Survey Tickets Dropdown -->
            @can('create_survey_ticket')
                <li>
                    <details class="dropdown"
                        {{ request()->routeIs('survey.ticket.*') || request()->routeIs('survey.form.*') || request()->routeIs('surveys.*') ? 'open' : '' }}>
                        <summary
                            class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('survey.ticket.*') || request()->routeIs('survey.form.*') || request()->routeIs('surveys.*') ? 'bg-primary text-white' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 {{ request()->routeIs('survey.ticket.*') || request()->routeIs('survey.form.*') || request()->routeIs('surveys.*') ? 'text-white' : 'text-black' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 17l4 4 4-4m-4-5v9" />
                            </svg>
                            <span>Survey</span>
                        </summary>
                        <ul class="ml-8 mt-2 space-y-1">
                            <li>
                                <a href='{{ route('survey.tickets.list') }}'
                                    class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('survey.ticket.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                    </svg>
                                    All Tickets
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('survey.ticket.create') }}"
                                    class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('survey.ticket.create') ? 'bg-blue-100 font-semibold' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Create Ticket
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('surveys.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                    All Surveys
                                </a>
                            </li>
                            <li>
                                <a href="#"
                                    class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('survey.form.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    Survey Forms
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
            @endcan


            <!-- Reports -->
            @can('generate_reports')
                <li>
                    <a href="#" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Reports</span>
                    </a>
                </li>
            @endcan

            <!-- Service Types -->
            @can('view_services')
                <li>
                    <a href="{{ route('service-types.index') }}"
                        class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg {{ request()->routeIs('service-types.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 {{ request()->routeIs('service-types.*') ? 'text-white' : 'text-black' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span>Service Types</span>
                    </a>
                </li>
            @endcan

            <!-- Products -->
            @can('view_products')
                <li>
                    <a href="{{ route('products.index') }}"
                        class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg {{ request()->routeIs('products.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 {{ request()->routeIs('products.*') ? 'text-white' : 'text-black' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Products</span>
                    </a>
                </li>
            @endcan

            <!-- Vendors -->
            @can('view_vendors')
                <li>
                    <a href="{{ route('vendors.index') }}"
                        class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg {{ request()->routeIs('vendors.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5 {{ request()->routeIs('vendors.*') ? 'text-white' : 'text-black' }}"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span>Vendors</span>
                    </a>
                </li>
            @endcan
            <!-- Divider before Business Areas -->
            <li class="pt-4 border-t border-gray-200"></li>

            <!-- Business Areas Header -->
            <li class="mt-2 px-4 text-xs text-gray-500 uppercase font-semibold">Business Areas</li>

            <li>
                <details class="dropdown" {{ request()->routeIs('customers.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('customers.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('customers.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M12 7a4 4 0 110 8 4 4 0 010-8z" />
                        </svg>
                        <span>Customers</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('customers.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('customers.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7a4 4 0 118 0 4 4 0 01-8 0zM3 21a8 8 0 1118 0H3z" />
                                </svg>
                                All Customers
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('customers.create') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('customers.create') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Customer
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('customers.import') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('customers.import') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5" />
                                </svg>
                                Import
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('customers.groups') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('customers.groups') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                Groups
                            </a>
                        </li>
                    </ul>
                </details>
            </li>

            <li>
                <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    Admin Menu
                </a>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('sales.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('sales.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('sales.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        <span>Sales</span>
                    </summary>
                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('leads.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('leads.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7a4 4 0 118 0 4 4 0 01-8 0zM3 21a8 8 0 1118 0H3z" />
                                </svg>
                                Leads / Prospects
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M20.4 15a8 8 0 11-16.8 0" />
                                </svg>
                                Opportunities
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18" />
                                </svg>
                                Pipelines
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v6h6v-6c0-1.657-1.343-3-3-3zM12 5V4" />
                                </svg>
                                Offers (Quotes / Proposals)
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M12 8v8m-5 4h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v15a2 2 0 002 2z" />
                                </svg>
                                Contracts / Subscriptions
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3" />
                                </svg>
                                Revenue &amp; Payments
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" />
                                </svg>
                                Sales Reports
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('marketing.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('marketing.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('marketing.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8h18M3 12h18M3 16h18" />
                        </svg>
                        <span>Marketing</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-3.333 0-6 1.79-6 4v6h12v-6c0-2.21-2.667-4-6-4zM12 4v4" />
                                </svg>
                                Campaigns
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8h18M7 8v10M17 8v10" />
                                </svg>
                                Audiences / Segments
                            </a>
                        </li>

                        <li>
                            <details class="dropdown ml-2" {{ request()->routeIs('marketing.messaging.*') ? 'open' : '' }}>
                                <summary class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg cursor-pointer p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h6m-9 4h12" />
                                    </svg>
                                    Messaging
                                </summary>
                                <ul class="ml-6 mt-1 space-y-1">
                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                                            </svg>
                                            Email
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m7 4v6m0 0l3 3m-3-3l-3 3" />
                                            </svg>
                                            SMS
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12c0 4.418-3.582 8-8 8a8 8 0 01-8-8 8 8 0 018-8c1.657 0 3.18.448 4.5 1.214L21 3v9z" />
                                            </svg>
                                            WhatsApp
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3l2 3h6a2 2 0 012 2v5a2 2 0 01-2 2h-1l-3 3-3-3H6a2 2 0 01-2-2V5z" />
                                            </svg>
                                            Voice
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v18h14V3H5zm7 14a3 3 0 100-6 3 3 0 000 6z" />
                                </svg>
                                Templates
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                                </svg>
                                Automation Rules
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 3v18M3 7h18" />
                                </svg>
                                Marketing Analytics
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('parties.*') || request()->routeIs('parties.profiles') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('parties.*') || request()->routeIs('parties.profiles') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('parties.*') || request()->routeIs('parties.profiles') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 1.79-8 4v1h16v-1c0-2.21-3.582-4-8-4z" />
                        </svg>
                        <span>Parties</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('parties.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('parties.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                All Parties
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4M6 20h12M4 8h16" />
                                </svg>
                                Individuals
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18v10H3z" />
                                </svg>
                                Organizations
                            </a>
                        </li>

                        <li>
                            <details class="dropdown ml-2" {{ request()->routeIs('parties.roles.*') ? 'open' : '' }}>
                                <summary class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg cursor-pointer p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8M8 15h8" />
                                    </svg>
                                    Roles &amp; Associations
                                </summary>

                                <ul class="ml-6 mt-1 space-y-1">
                                    <li>
                                        <a href="{{ route('clients.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('clients.*') ? 'bg-blue-100 font-semibold' : '' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7a4 4 0 118 0 4 4 0 01-8 0zM3 21a8 8 0 1118 0H3z" />
                                            </svg>
                                            Clients
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('vendors.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('vendors.*') ? 'bg-blue-100 font-semibold' : '' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
                                            </svg>
                                            Vendors
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18v10H3z" />
                                            </svg>
                                            Partners
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11c1.657 0 3-1.343 3-3S17.657 5 16 5s-3 1.343-3 3 1.343 3 3 3zM8 21v-6a4 4 0 018 0v6" />
                                </svg>
                                Contacts
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                Addresses
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('support.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('support.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('support.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8a6 6 0 00-12 0v4a6 6 0 0012 0V8zM12 20v-4" />
                        </svg>
                        <span>Service &amp; Support</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18v4H3V7zM7 15h10v6H7v-6z" />
                                </svg>
                                Service Requests
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 10a1 1 0 00-1-1h-3V7a2 2 0 00-2-2H9a2 2 0 00-2 2v2H4a1 1 0 00-1 1v8a1 1 0 001 1h16a1 1 0 001-1v-8z" />
                                </svg>
                                Tickets / Cases
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M20.4 15a8 8 0 11-16.8 0" />
                                </svg>
                                SLA Management
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8M8 15h5" />
                                </svg>
                                Knowledge Base
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 8l-6 6-6-6" />
                                </svg>
                                Escalations
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18M3 10h18M3 15h12" />
                                </svg>
                                Feedback &amp; Surveys
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 3v18M3 7h18" />
                                </svg>
                                Support Reports
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('operations.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('operations.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('operations.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M20.4 15a8 8 0 11-16.8 0" />
                        </svg>
                        <span>Operations</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18v4H3V7zM5 11v8a1 1 0 001 1h12a1 1 0 001-1v-8H5z" />
                                </svg>
                                Work Orders
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M12 8v8M7 21h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Tasks &amp; Assignments
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('projects.list') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('projects.*') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ request()->routeIs('projects.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                Projects
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v6l4 2 4-2V7M3 21h18" />
                                </svg>
                                Field Operations
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                Activity Logs
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 3v18M3 7h18" />
                                </svg>
                                Operational Reports
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('assets.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('assets.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('assets.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4v8l-9 4-9-4V7z" />
                        </svg>
                        <span>Assets</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <details class="dropdown" {{ request()->routeIs('assets.registry.*') ? 'open' : '' }}>
                                <summary class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg cursor-pointer p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Assets Registry
                                </summary>

                                <ul class="ml-6 mt-1 space-y-1">
                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6" />
                                            </svg>
                                            Hardware
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M12 8v8m-5 4h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v15a2 2 0 002 2z" />
                                            </svg>
                                            Software
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18" />
                                            </svg>
                                            Network
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Licenses
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18v10H3z" />
                                </svg>
                                Asset Assignments
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3" />
                                </svg>
                                Asset Status &amp; Events
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M20.4 15a8 8 0 11-16.8 0" />
                                </svg>
                                Maintenance
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Inventory
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('billing.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('billing.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('billing.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3" />
                        </svg>
                        <span>Billing &amp; Finance</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 .895-4 2s1.79 2 4 2 4-.895 4-2-1.79-2-4-2zM6 20h12v-2a4 4 0 00-4-4H10a4 4 0 00-4 4v2z" />
                                </svg>
                                Pricing Plans
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6M21 12V7a2 2 0 00-2-2H5a2 2 0 00-2 2v5" />
                                </svg>
                                Invoices
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M20 12a8 8 0 11-16 0 8 8 0 0116 0z" />
                                </svg>
                                Payments
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5v14M7 7h.01M17 7h.01" />
                                </svg>
                                Wallets / Credits
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3M3 7h18" />
                                </svg>
                                Taxes &amp; Discounts
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 3v18M3 7h18" />
                                </svg>
                                Financial Reports
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('analytics.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('analytics.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('analytics.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" />
                        </svg>
                        <span>Analytics</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6l6-3v12" />
                                </svg>
                                Sales Analytics
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" />
                                </svg>
                                Marketing Analytics
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8v8l5-3 5 3V8" />
                                </svg>
                                Support Analytics
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M20.4 15a8 8 0 11-16.8 0" />
                                </svg>
                                Revenue &amp; Churn
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h6v6M3 21h18" />
                                </svg>
                                Custom Reports
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 3v18M3 7h18" />
                                </svg>
                                Data Exports
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('automation.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('automation.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('automation.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2 2 2m0-6l-2 2-2-2" />
                        </svg>
                        <span>Automation</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h8M8 11h8M8 15h8" />
                                </svg>
                                Workflow Rules
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M20.4 15a8 8 0 11-16.8 0" />
                                </svg>
                                Event Triggers
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v6l4 2 4-2V7M3 21h18" />
                                </svg>
                                Scheduled Jobs
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118.6 14.6L19 10h-4" />
                                </svg>
                                Notifications
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                                </svg>
                                Webhooks
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 3v18M3 7h18" />
                                </svg>
                                Integration Logs
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('integrations.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('integrations.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('integrations.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2 2 2m0-6l-2 2-2-2" />
                        </svg>
                        <span>Integrations</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <details class="dropdown" {{ request()->routeIs('integrations.communication.*') ? 'open' : '' }}>
                                <summary class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg cursor-pointer p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                                    </svg>
                                    Communication APIs
                                </summary>

                                <ul class="ml-6 mt-1 space-y-1">
                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m7 4v6m0 0l3 3m-3-3l-3 3" />
                                            </svg>
                                            SMS
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12c0 4.418-3.582 8-8 8a8 8 0 01-8-8 8 8 0 018-8c1.657 0 3.18.448 4.5 1.214L21 3v9z" />
                                            </svg>
                                            WhatsApp
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8" />
                                            </svg>
                                            Email
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-2.21 0-4 .895-4 2s1.79 2 4 2 4-.895 4-2-1.79-2-4-2zM6 20h12v-2a4 4 0 00-4-4H10a4 4 0 00-4 4v2z" />
                                </svg>
                                Payment Gateways
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 3v18M3 7h18" />
                                </svg>
                                Accounting Systems
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 6h18M3 14h18" />
                                </svg>
                                Network / Device APIs
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M21 8v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8" />
                                </svg>
                                Webhooks
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                API Keys
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="dropdown" {{ request()->routeIs('admin.*') || request()->routeIs('administration.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('admin.*') || request()->routeIs('administration.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('admin.*') || request()->routeIs('administration.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M19.4 15a8 8 0 11-14.8 0" />
                        </svg>
                        <span>Administration</span>
                    </summary>

                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('users.*') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ request()->routeIs('users.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14c-4.418 0-8 1.79-8 4v1h16v-1c0-2.21-3.582-4-8-4z" />
                                </svg>
                                Users &amp; Roles
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('permissions.*') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ request()->routeIs('permissions.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Permissions
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18v10H3zM7 3h10v4H7z" />
                                </svg>
                                Organizations / Tenants
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v4M6 20h12M4 8h16" />
                                </svg>
                                Module Settings
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                Custom Fields
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('forms.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('forms.*') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 {{ request()->routeIs('forms.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                Forms &amp; Templates
                            </a>
                        </li>

                        <li>
                            <a href="#" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18M7 3v18M3 7h18" />
                                </svg>
                                Audit Logs
                            </a>
                        </li>
                    </ul>
                </details>
            </li>

            <!-- Divider -->
            <li class="pt-4 border-t border-gray-200"></li>

            <!-- Roles & Permissions -->
            @can('view_roles')
                <li>
                    <details class="dropdown"
                        {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('users.roles') ? 'open' : '' }}>
                        <summary
                            class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('users.roles') ? 'bg-primary text-white' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5 {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('users.roles') ? 'text-white' : 'text-black' }}"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Access Control</span>
                        </summary>
                        <ul class="ml-8 mt-2 space-y-1">
                            <li>
                                <a href="{{ route('roles.index') }}"
                                    class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('roles.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Roles
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('permissions.index') }}"
                                    class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('permissions.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                    </svg>
                                    Permissions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('users.roles') }}"
                                    class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('users.roles') ? 'bg-blue-100 font-semibold' : '' }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    User Access
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>
            @endcan


            <!-- Tools -->
            <li>
                <details class="dropdown" {{ request()->routeIs('tools.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('tools.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Tools</span>
                    </summary>
                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('forms.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('forms.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                All Forms
                            </a>
                        </li>
                    </ul>
                </details>
            </li>

            <!-- Divider -->
            <li class="border-t border-gray-200"></li>

            <!-- Settings -->
            @can('manage_settings')
                <li>
                    <a href="#" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Settings</span>
                    </a>
                </li>
            @endcan
        </ul>

        <!-- User Profile Section (Bottom) -->
        <div class="p-4 border-t border-gray-200 bg-base-100 flex-shrink-0">
            <div class="dropdown dropdown-top dropdown-end w-full">
                <div tabindex="0" role="button"
                    class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg cursor-pointer w-full">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-white rounded-full w-10">
                            <span
                                class="text-sm">{{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 2)) : 'GU' }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-black">
                            {{ auth()->check() ? auth()->user()->name : 'Guest' }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->check() ? auth()->user()->email : '' }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </div>
                <ul tabindex="0"
                    class="dropdown-content menu p-2 shadow-lg bg-base-100 rounded-lg w-52 border border-gray-200 mb-2">
                    <li>
                        <a href="{{ route('profile') }}" class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Account Settings
                        </a>
                    </li>
                    <li class="border-t border-gray-200 mt-2 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full text-left text-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </aside>
</div>
