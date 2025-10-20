<!-- Overlay untuk mobile -->
<div x-show="$store.sidebar.open" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="$store.sidebar.toggle()"
     class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"
     style="display: none;">
</div>

<aside id="sidebar" 
       x-data="{ isDesktop: window.innerWidth >= 1024 }"
       x-init="
           isDesktop = window.innerWidth >= 1024;
           window.addEventListener('resize', () => {
               isDesktop = window.innerWidth >= 1024;
           });
       "
       x-show="$store.sidebar.open || isDesktop"
       x-transition:enter="transition ease-in-out duration-300 transform"
       x-transition:enter-start="-translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition ease-in-out duration-300 transform"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="-translate-x-full"
       class="fixed lg:relative w-64 bg-white h-screen shadow-md z-50 lg:translate-x-0">
    <div class="flex items-center justify-between p-4 text-2xl font-bold border-b">
        <span>Ken Motor</span>
        <!-- Tombol Close untuk Mobile -->
        <button @click="$store.sidebar.toggle()" 
                class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    <nav class="mt-4">
        <ul>
            <!-- Main -->
            <li>
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-200">🏠 Dashboard</a>
            </li>

            <!-- Bengkel Section -->
            <li class="mt-4 px-4 text-gray-500 uppercase text-xs font-semibold">Bengkel</li>
            <li>
                <a href="{{ route('bengkel.barang.index') }}" class="block px-4 py-2 hover:bg-gray-200">📦 Barang
                    Bengkel</a>
            </li>
            <li>
                <a href="{{ route('bengkel.belanja.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                    🧾 Belanja Barang
                </a>
            </li>
            <li>
                <a href="{{ route('bengkel.pembelian.index') }}" class="block px-4 py-2 hover:bg-gray-200">🧾 Pembelian
                    Barang</a>
            </li>
            <li>
                <a href="{{ route('bengkel.penjualanbarang.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                    💸 Jual Barang
                </a>
            </li>
            <li>
                <a href="{{ route('bengkel.kategori.index') }}" class="block px-4 py-2 hover:bg-gray-200">
                    🗂️ Kategori Barang
                </a>
            </li>
            <!-- Motor Section -->
            <li class="mt-4 px-4 text-gray-500 uppercase text-xs font-semibold">Motor</li>
            <li>
                <a href="{{ route('pembelian.index') }}" class="block px-4 py-2 hover:bg-gray-200">🧾 Beli Motor</a>
            </li>
            <li>
                <a href="{{ route('motor.index') }}" class="block px-4 py-2 hover:bg-gray-200">🛵 Motor</a>
            </li>
            <li>
                <a href="{{ route('pelanggan.index') }}" class="block px-4 py-2 hover:bg-gray-200" @click="$store.sidebar.close()">👤 Pelanggan</a>
            </li>
            <li>
                <a href="{{ route('restorasi.index') }}" class="block px-4 py-2 hover:bg-gray-200" @click="$store.sidebar.close()">🛠️ Restorasi</a>
            </li>
            <li>
                <a href="{{ route('penjualan.index') }}" class="block px-4 py-2 hover:bg-gray-200" @click="$store.sidebar.close()">💰 Jual Motor</a>
            </li>
            <li>
                <a href="{{ route('laporan.penjualan') }}" class="block px-4 py-2 hover:bg-gray-200">📊 Laporan
                    Penjualan Motor</a>
            </li>
        </ul>
    </nav>
</aside>