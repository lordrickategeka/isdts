<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="bccflow">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ISDTS Core') }} - Client Portal</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Client Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">BCC Flow</h1>
                        <p class="text-xs text-gray-500">Client Portal</p>
                    </div>
                </div>

                <!-- Navigation - Only show if client is authenticated -->
                @auth
                    @if(auth()->user()->hasRole('client'))
                        <nav class="hidden md:flex items-center gap-6">
                            <a href="{{ route('client.dashboard') }}" class="text-sm text-gray-700 hover:text-primary {{ request()->routeIs('client.dashboard') ? 'font-semibold text-primary' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('client.agreements') }}" class="text-sm text-gray-700 hover:text-primary {{ request()->routeIs('client.agreements') ? 'font-semibold text-primary' : '' }}">
                                My Agreements
                            </a>
                            <a href="{{ route('client.services') }}" class="text-sm text-gray-700 hover:text-primary {{ request()->routeIs('client.services') ? 'font-semibold text-primary' : '' }}">
                                Services
                            </a>
                        </nav>

                        <!-- User Dropdown -->
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                                <div class="w-10 rounded-full bg-primary text-white flex items-center justify-center">
                                    <span class="text-sm font-semibold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                                </div>
                            </div>
                            <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                                <li class="menu-title">
                                    <span class="text-xs">{{ auth()->user()->email }}</span>
                                </li>
                                <li><a href="{{ route('client.profile') }}">Profile</a></li>
                                <li><a href="{{ route('client.settings') }}">Settings</a></li>
                                <li class="border-t mt-2 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="text-error w-full text-left">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                @endauth
            </div>

            <!-- Mobile Navigation -->
            @auth
                @if(auth()->user()->hasRole('client'))
                    <nav class="md:hidden pb-3 flex gap-4 overflow-x-auto">
                        <a href="{{ route('client.dashboard') }}" class="text-sm whitespace-nowrap text-gray-700 hover:text-primary {{ request()->routeIs('client.dashboard') ? 'font-semibold text-primary border-b-2 border-primary' : '' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('client.agreements') }}" class="text-sm whitespace-nowrap text-gray-700 hover:text-primary {{ request()->routeIs('client.agreements') ? 'font-semibold text-primary border-b-2 border-primary' : '' }}">
                            My Agreements
                        </a>
                        <a href="{{ route('client.services') }}" class="text-sm whitespace-nowrap text-gray-700 hover:text-primary {{ request()->routeIs('client.services') ? 'font-semibold text-primary border-b-2 border-primary' : '' }}">
                            Services
                        </a>
                    </nav>
                @endif
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="min-h-[calc(100vh-80px)]">
        @yield('content')
        {{ $slot ?? '' }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-600">
                    © {{ date('Y') }} BCC Flow. All rights reserved.
                </p>
                <div class="flex gap-6">
                    <a href="#" class="text-sm text-gray-600 hover:text-primary">Privacy Policy</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-primary">Terms of Service</a>
                    <a href="#" class="text-sm text-gray-600 hover:text-primary">Contact Support</a>
                </div>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
