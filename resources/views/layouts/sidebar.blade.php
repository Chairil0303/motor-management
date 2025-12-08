<aside 
    :class="{
        'translate-x-0': $store.sidebar.isOpen,
        '-translate-x-full': !$store.sidebar.isOpen,
    }"
    class="fixed lg:relative inset-y-0 left-0 w-64 bg-white shadow-lg transform sidebar-transition h-screen flex flex-col z-50 
           -translate-x-full lg:translate-x-0"
    x-cloak>

    <!-- Header -->
    <div class="flex items-center justify-between p-4 border-b bg-white">
        <div class="text-xl font-bold text-gray-800">Ken Motor</div>
        
        <!-- Close button only mobile -->
        <button @click="$store.sidebar.close()" 
                x-show="$store.sidebar.isMobile"
                class="lg:hidden p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- NAVIGATION -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">

            <li>
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100"
                   @click="if ($store.sidebar.isMobile) $store.sidebar.close()">
                    <span class="mr-3 text-lg">🏠</span>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Bengkel -->
            <li class="mt-6 mb-2 px-4">
                <div class="text-xs font-semibold text-gray-500 uppercase">Bengkel</div>
            </li>

            @php
                $bengkelItems = [
                    ['route' => 'bengkel.barang.index', 'icon' => '📦', 'label' => 'Barang Bengkel'],
                    ['route' => 'bengkel.belanja.index', 'icon' => '🧾', 'label' => 'Belanja Barang'],
                    ['route' => 'bengkel.penjualanbarang.index', 'icon' => '💸', 'label' => 'Jual Barang'],
                    ['route' => 'bengkel.kategori.index', 'icon' => '🗂️', 'label' => 'Kategori Barang'],
                ];
            @endphp

            @foreach($bengkelItems as $item)
            <li>
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100"
                   @click="if ($store.sidebar.isMobile) $store.sidebar.close()">
                    <span class="mr-3 text-lg">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
            @endforeach

            <!-- Superadmin -->
            @if(auth()->user()->role === 'superadmin')
            <li class="mt-6 mb-2 px-4">
                <div class="text-xs font-semibold text-gray-500 uppercase">Motor</div>
            </li>

            @php
                $motorItems = [
                    ['route' => 'pembelian.index', 'icon' => '🧾', 'label' => 'Beli Motor'],
                    ['route' => 'motor.index', 'icon' => '🛵', 'label' => 'Motor'],
                    ['route' => 'pelanggan.index', 'icon' => '👤', 'label' => 'Pelanggan'],
                    ['route' => 'restorasi.index', 'icon' => '🛠️', 'label' => 'Restorasi'],
                    ['route' => 'penjualan.index', 'icon' => '💰', 'label' => 'Jual Motor'],
                    ['route' => 'laporan.penjualan', 'icon' => '📊', 'label' => 'Laporan Penjualan'],
                ];
            @endphp

            @foreach($motorItems as $item)
            <li>
                <a href="{{ route($item['route']) }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100"
                   @click="if ($store.sidebar.isMobile) $store.sidebar.close()">
                    <span class="mr-3 text-lg">{{ $item['icon'] }}</span>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
            @endforeach
            @endif

            <!-- Logout -->
            <li class="mt-auto border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full px-4 py-3 text-red-600 hover:bg-red-50"
                        @click="if ($store.sidebar.isMobile) $store.sidebar.close()">
                        <span class="mr-3 text-lg">🚪</span>
                        <span>Logout</span>
                    </button>
                </form>
            </li>

        </ul>
    </nav>
</aside>
