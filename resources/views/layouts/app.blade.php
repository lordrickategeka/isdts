<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="bccflow">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ISDTS Core') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="drawer lg:drawer-open">
        <input id="main-drawer" type="checkbox" class="drawer-toggle" />

        <!-- Main Content -->
        <div class="drawer-content min-h-screen bg-gray-50">
            <!-- Mobile Header with Hamburger Menu -->
            <div class="bg-base-100 border-b border-gray-200 lg:hidden">
                <div class="flex items-center gap-4 p-4">
                    <label for="main-drawer" class="btn btn-ghost btn-square drawer-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                    <div>
                        <h2 class="text-lg font-bold text-black">ISDTS Core</h2>
                    </div>
                </div>
            </div>

            @yield('content')
            {{ $slot ?? '' }}
        </div>

        <!-- Sidebar -->
        @livewire('sidebar.sidebar-component')
    </div>

    @livewireScripts
</body>
</html>
