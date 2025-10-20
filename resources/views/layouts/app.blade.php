<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script>
        // Initialize Alpine.js store for sidebar state
        document.addEventListener('alpine:init', () => {
            Alpine.store('sidebar', {
                open: false, // Closed by default on mobile
                
                toggle() {
                    // Only toggle on mobile (< 1024px)
                    if (window.innerWidth < 1024) {
                        this.open = !this.open;
                    }
                },
                
                close() {
                    // Only close on mobile
                    if (window.innerWidth < 1024) {
                        this.open = false;
                    }
                },
                
                init() {
                    // Set initial state based on screen size
                    this.open = window.innerWidth >= 1024;
                    
                    // Update on window resize
                    let resizeTimer;
                    window.addEventListener('resize', () => {
                        clearTimeout(resizeTimer);
                        resizeTimer = setTimeout(() => {
                            if (window.innerWidth >= 1024) {
                                this.open = true;
                            } else {
                                this.open = false;
                            }
                        }, 100);
                    });
                }
            });
        });
    </script>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('layouts.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>