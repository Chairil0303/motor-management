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
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar -->
        <aside class="w-64 bg-white h-screen shadow-md fixed">
            <div class="p-4 text-2xl font-bold border-b">🏍️ Dealer</div>
            <nav class="mt-4">
                <ul>
                    <li>
                        <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-200">🏠 Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ route('motor.index') }}" class="block px-4 py-2 hover:bg-gray-200">🛵 Motor</a>
                    </li>
                    <li>
                        <a href="{{ route('pelanggan.index') }}" class="block px-4 py-2 hover:bg-gray-200">👤
                            Pelanggan</a>
                    </li>
                    <li>
                        <a href="{{ route('pembelian.index') }}" class="block px-4 py-2 hover:bg-gray-200">🧾
                            Pembelian</a>
                    </li>
                    <li>
                        <a href="{{ route('restorasi.index') }}" class="block px-4 py-2 hover:bg-gray-200">🛠️
                            Restorasi</a>
                    </li>
                    <li>
                        <a href="{{ route('penjualan.index') }}" class="block px-4 py-2 hover:bg-gray-200">💰
                            Penjualan</a>
                    </li>
                </ul>
            </nav>
        </aside>
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            <main class="ml-64 p-6 w-full">
                @yield('content')
            </main>
        </main>
    </div>
</body>

</html>