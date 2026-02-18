<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-black text-white">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Carri Logistics') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased h-full" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen bg-black flex">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col md:pl-64 transition-all duration-300">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if (isset($header))
                    <header class="mb-6">
                        <h1 class="text-2xl font-bold text-white">{{ $header }}</h1>
                    </header>
                @endif

                {{ $slot }}
            </main>
        </div>

        <!-- Overlay for mobile sidebar -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 bg-gray-900 bg-opacity-80 z-40 md:hidden" style="display: none;"></div>
    </div>
</body>

</html>