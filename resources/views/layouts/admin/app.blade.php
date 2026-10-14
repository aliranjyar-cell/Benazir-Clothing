<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Dashboard') · {{ config('app.name', 'Laravel') }} Admin</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('fontawesome-free-7.2.0-web/css/all.min.css') }}">
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }
        </style>

        @stack('styles')
    </head>
    <body class="h-full bg-ivory font-sans antialiased text-ink">
        <div
            x-data="{ sidebarOpen: false }"
            @keydown.escape.window="sidebarOpen = false"
            class="min-h-full"
        >
            <a
                href="#main-content"
                class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:rounded-xl focus:bg-aubergine focus:px-4 focus:py-2.5 focus:text-sm focus:font-medium focus:text-white focus:outline-none focus:ring-2 focus:ring-lilac"
            >
                Skip to content
            </a>

            <x-admin.sidebar />

            <div class="flex min-h-screen flex-col lg:ps-64">
                <x-admin.header />

                <main id="main-content" class="flex-1">
                    <div class="mx-auto w-full max-w-7xl px-4 pb-10 pt-6 sm:px-6 sm:pt-8 lg:px-8">
                        <x-admin.flash-messages />

                        @yield('content')
                    </div>
                </main>

                <footer class="border-t border-black/5 bg-white/60 px-4 py-5 sm:px-6 lg:px-8">
                    <p class="text-xs text-muted">
                        © {{ date('Y') }} AURELLE — Fashion Operations · admin layout preview
                    </p>
                </footer>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
