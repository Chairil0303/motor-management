<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Ken Motor')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Initialize Alpine Store for Sidebar - SIMPLIFIED VERSION -->
    <script>
        document.addEventListener('alpine:init', () => {
            // Simple state management
            const isMobile = window.innerWidth < 1024;
            
            Alpine.store('sidebar', {
                // Mobile: false, Desktop: true
                isOpen: !isMobile,
                isMobile: isMobile,
                
                toggle() {
                    if (this.isMobile) {
                        this.isOpen = !this.isOpen;
                    }
                },
                
                close() {
                    if (this.isMobile) {
                        this.isOpen = false;
                    }
                }
            });
            
            // Update on resize
            window.addEventListener('resize', () => {
                const store = Alpine.store('sidebar');
                store.isMobile = window.innerWidth < 1024;
                
                // Auto behavior
                if (store.isMobile) {
                    store.isOpen = false;
                } else {
                    store.isOpen = true;
                }
            });
            
            // Close sidebar when clicking outside (mobile only)
            document.addEventListener('click', (e) => {
                const store = Alpine.store('sidebar');
                const sidebar = document.querySelector('aside');
                const hamburger = document.querySelector('[x-on\\:click*="sidebar.toggle"]');
                
                if (store.isMobile && store.isOpen && sidebar && 
                    !sidebar.contains(e.target) && 
                    !hamburger.contains(e.target)) {
                    store.close();
                }
            });
        });
        
        // Force close on mobile on page load
        document.addEventListener('DOMContentLoaded', () => {
            if (window.innerWidth < 1024) {
                setTimeout(() => {
                    Alpine.store('sidebar').isOpen = false;
                }, 100);
            }
        });
    </script>

    <style>
        /* Essential styles */
        [x-cloak] { display: none !important; }
        
        /* Sidebar animation */
        .sidebar-transition {
            transition: transform 0.3s ease-in-out;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">
    <div x-data x-init="
        Alpine.store('sidebar', {
            isMobile: window.innerWidth < 1024,
            isOpen: window.innerWidth >= 1024,

            toggle() {
                if (this.isMobile) this.isOpen = !this.isOpen
            },
            close() {
                if (this.isMobile) this.isOpen = false
            }
        });

        window.addEventListener('resize', () => {
            const s = Alpine.store('sidebar');
            s.isMobile = window.innerWidth < 1024;
            s.isOpen = !s.isMobile;
        });
    " class="flex min-h-screen">

        <!-- Overlay mobile -->
        <div 
            x-show="$store.sidebar.isOpen && $store.sidebar.isMobile"
            @click="$store.sidebar.close()" 
            class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
            x-cloak>
        </div>

        <!-- SIDEBAR -->
        @include('layouts.sidebar')

        <!-- MAIN CONTENT -->
        <div class="flex-1 flex flex-col w-full min-w-0">

            <!-- Mobile Header -->
            <div class="lg:hidden bg-white border-b px-4 py-3 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <button @click="$store.sidebar.toggle()" 
                        class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                    <!-- Hamburger -->
                    <svg x-show="!$store.sidebar.isOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>

                    <!-- Close -->
                    <svg x-show="$store.sidebar.isOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="text-lg font-semibold text-gray-800 truncate ml-2">
                    @yield('page-title', 'Ken Motor')
                </div>

                <div class="w-8"></div>
            </div>

            <!-- Desktop Nav -->
            <div class="hidden lg:block">
                @include('layouts.navigation')
            </div>

            <!-- PAGE CONTENT -->
            <main class="flex-1 p-4 sm:p-6 overflow-x-auto">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>


</html>