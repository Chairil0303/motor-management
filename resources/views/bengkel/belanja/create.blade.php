@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Tambah Belanja Barang</h1>

        <form action="{{ route('bengkel.belanja.store') }}" method="POST" class="space-y-4" id="belanjaForm">
            @csrf

            {{-- Cari Barang --}}
            <div>
                <label class="block font-semibold mb-1">Cari Barang</label>
                <input type="text" id="search-barang" placeholder="Ketik nama barang..." class="w-full border p-2 rounded">
                <ul id="barang-list" class="border rounded mt-1 hidden bg-white shadow"></ul>
            </div>

            {{-- Input hidden --}}
            <input type="hidden" name="barang_id" id="barang_id">

            {{-- Info barang yang dipilih --}}
            <div id="barang-info" class="hidden border p-3 rounded bg-gray-50">
                <p><strong>Nama Barang:</strong> <span id="nama_barang"></span></p>
                <p><strong>Stok Sekarang:</strong> <span id="stok_barang"></span></p>
                <p><strong>Harga Jual Saat Ini:</strong> Rp <span id="harga_jual"></span></p>
            </div>

            {{-- Kuantiti dan Harga --}}
            <div>
                <label class="block font-semibold mb-1">Kuantiti</label>
                <input type="number" name="kuantiti" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Harga Beli per Unit</label>
                <input type="number" name="harga_beli" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Harga Jual (opsional)</label>
                <input type="number" name="harga_jual" class="w-full border p-2 rounded">
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('bengkel.belanja.index') }}"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>

    {{-- AJAX Search Script --}}
    <script>
        document.getElementById('search-barang').addEventListener('input', function () {
            let keyword = this.value;
            if (keyword.length < 2) {
                document.getElementById('barang-list').classList.add('hidden');
                return;
            }

            fetch(`/api/search-barang?keyword=${keyword}`)
                .then(res => res.json())
                .then(data => {
                    let list = document.getElementById('barang-list');
                    list.innerHTML = '';
                    data.forEach(item => {
                        let li = document.createElement('li');
                        li.className = 'p-2 hover:bg-gray-100 cursor-pointer';
                        li.textContent = item.nama_barang;
                        li.onclick = () => pilihBarang(item);
                        list.appendChild(li);
                    });
                    list.classList.remove('hidden');
                });
        });

        function pilihBarang(item) {
            document.getElementById('barang_id').value = item.id;
            document.getElementById('nama_barang').textContent = item.nama_barang;
            document.getElementById('stok_barang').textContent = item.stok;
            document.getElementById('harga_jual').textContent = item.harga_jual;
            document.getElementById('barang-info').classList.remove('hidden');
            document.getElementById('barang-list').classList.add('hidden');
            document.getElementById('search-barang').value = item.nama_barang;
        }
    </script>
@endsection