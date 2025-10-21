<aside class="w-64 bg-white h-screen shadow-md fixed lg:relative">
    <div class="p-4 text-2xl font-bold border-b">Ken Motor</div>
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
            @if(auth()->user()->role === 'superadmin')
                <li class="mt-4 px-4 text-gray-500 uppercase text-xs font-semibold">Motor</li>
                <li>
                    <a href="{{ route('pembelian.index') }}" class="block px-4 py-2 hover:bg-gray-200">🧾 Beli Motor</a>
                </li>
                <li>
                    <a href="{{ route('motor.index') }}" class="block px-4 py-2 hover:bg-gray-200">🛵 Motor</a>
                </li>
                <li>
                    <a href="{{ route('pelanggan.index') }}" class="block px-4 py-2 hover:bg-gray-200">👤 Pelanggan</a>
                </li>
                <li>
                    <a href="{{ route('restorasi.index') }}" class="block px-4 py-2 hover:bg-gray-200">🛠️ Restorasi</a>
                </li>
                <li>
                    <a href="{{ route('penjualan.index') }}" class="block px-4 py-2 hover:bg-gray-200">💰 Jual Motor</a>
                </li>
                <li>
                    <a href="{{ route('laporan.penjualan') }}" class="block px-4 py-2 hover:bg-gray-200">📊 Laporan Penjualan Motor</a>
                </li>
                @endif

        </ul>
    </nav>
</aside>