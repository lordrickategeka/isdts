<div class="drawer-side z-40">
    <label for="main-drawer" class="drawer-overlay"></label>

    <!-- Sidebar Content -->
    <aside class="bg-base-100 w-64 min-h-screen max-h-screen border-r border-gray-200 flex flex-col">
        <!-- Logo/Brand Section -->
        <div class="p-6 border-b border-gray-200 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-black">ISDTS Core</h2>
                    <p class="text-xs text-gray-500">Management System</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="menu p-4 space-y-2 flex-1 overflow-y-auto overflow-x-visible">
            <!-- Dashboard -->
            @can('access_dashboard')
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary text-white' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>
            @endcan

            <!-- Clients -->
            @can('view_clients')
            <li>
                <details class="dropdown" {{ request()->routeIs('clients.*') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('clients.*') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('clients.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span>Clients</span>
                    </summary>
                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('clients.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('clients.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                                All Clients
                            </a>
                        </li>
                        @can('create_clients')
                        <li>
                            <a href="{{ route('clients.enroll') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('clients.enroll') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Add Client
                            </a>
                        </li>
                        @endcan
                    </ul>
                </details>
            </li>
            @endcan

            <!-- Users -->
            @can('view_users')
            <li>
                <a href="#" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span>Users</span>
                </a>
            </li>
            @endcan

            <!-- Projects -->
            <li>
                <a href="#" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    <span>Projects</span>
                </a>
            </li>

            <!-- Reports -->
            @can('generate_reports')
            <li>
                <a href="#" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span>Reports</span>
                </a>
            </li>
            @endcan

            <!-- Service Types -->
            @can('view_services')
            <li>
                <a href="{{ route('service-types.index') }}" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg {{ request()->routeIs('service-types.*') ? 'bg-primary text-white' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('service-types.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span>Service Types</span>
                </a>
            </li>
            @endcan

            <!-- Products -->
            @can('view_products')
            <li>
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg {{ request()->routeIs('products.*') ? 'bg-primary text-white' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('products.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <span>Products</span>
                </a>
            </li>
            @endcan

            <!-- Vendors -->
            @can('view_vendors')
            <li>
                <a href="{{ route('vendors.index') }}" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg {{ request()->routeIs('vendors.*') ? 'bg-primary text-white' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('vendors.*') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Vendors</span>
                </a>
            </li>
            @endcan

            <!-- Divider -->
            <li class="pt-4 border-t border-gray-200"></li>

            <!-- Roles & Permissions -->
            @can('view_roles')
            <li>
                <details class="dropdown" {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('users.roles') ? 'open' : '' }}>
                    <summary class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg cursor-pointer {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('users.roles') ? 'bg-primary text-white' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ request()->routeIs('roles.*') || request()->routeIs('permissions.*') || request()->routeIs('users.roles') ? 'text-white' : 'text-black' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span>Access Control</span>
                    </summary>
                    <ul class="ml-8 mt-2 space-y-1">
                        <li>
                            <a href="{{ route('roles.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('roles.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Roles
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('permissions.index') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('permissions.index') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Permissions
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('users.roles') }}" class="flex items-center gap-2 text-sm text-black hover:bg-gray-100 rounded-lg p-2 {{ request()->routeIs('users.roles') ? 'bg-blue-100 font-semibold' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                User Access
                            </a>
                        </li>
                    </ul>
                </details>
            </li>
            @endcan

            <!-- Divider -->
            <li class="border-t border-gray-200"></li>

            <!-- Settings -->
            @can('manage_settings')
            <li>
                <a href="#" class="flex items-center gap-3 text-black hover:bg-gray-100 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Settings</span>
                </a>
            </li>
            @endcan
        </ul>

        <!-- User Profile Section (Bottom) -->
        <div class="p-4 border-t border-gray-200 bg-base-100 flex-shrink-0">
            <div class="dropdown dropdown-top dropdown-end w-full">
                <div tabindex="0" role="button" class="flex items-center gap-3 p-2 hover:bg-gray-100 rounded-lg cursor-pointer w-full">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-white rounded-full w-10">
                            <span class="text-sm">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-black">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>
                </div>
                <ul tabindex="0" class="dropdown-content menu p-2 shadow-lg bg-base-100 rounded-lg w-52 border border-gray-200 mb-2">
                    <li>
                        <a href="{{ route('profile') }}" class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Profile
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Account Settings
                        </a>
                    </li>
                    <li class="border-t border-gray-200 mt-2 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full text-left text-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
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
