<aside class="w-64 bg-white h-screen shadow-md fixed lg:relative">
    <div class="p-4 text-2xl font-bold border-b">Ken Motor</div>
    <nav class="mt-4">
        <ul>
            <li>
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-200">🏠 Dashboard</a>
            </li>
            <li>
                <a href="{{ route('pembelian.index') }}" class="block px-4 py-2 hover:bg-gray-200">🧾 Pembelian</a>
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
        </ul>
    </nav>
</aside>