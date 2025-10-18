@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Tambah Belanja Barang</h1>

        <form id="formBelanja" action="{{ route('bengkel.belanja.store') }}" method="POST" class="space-y-4">
            @csrf

            {{-- 🔍 Pencarian Barang --}}
            <div class="relative">
                <label class="block font-semibold mb-1">Cari Barang</label>
                <div class="flex">
                    <input type="text" id="search-barang" placeholder="Ketik nama barang..."
                        class="w-full border p-2 rounded-l" autocomplete="off">
                    <button type="button" id="clear-btn"
                        class="bg-gray-300 px-3 rounded-r hover:bg-gray-400 transition hidden">✕</button>
                </div>
                <input type="hidden" name="barang_id" id="barang_id">

                {{-- Dropdown hasil pencarian --}}
                <ul id="search-results"
                    class="border rounded bg-white mt-1 hidden max-h-48 overflow-y-auto shadow z-10 absolute w-full"></ul>
            </div>

            {{-- Informasi Barang --}}
            <div id="barang-info" class="hidden space-y-2 border p-3 rounded bg-gray-50 mt-2">
                <div>
                    <label class="block font-semibold mb-1">Stok Saat Ini</label>
                    <input type="text" id="stok_saat_ini" class="w-full border p-2 rounded bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Harga Beli (Rp)</label>
                    <input type="text" name="harga_beli" id="harga_beli" class="w-full border p-2 rounded harga-input"
                        required>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Harga Jual (Rp)</label>
                    <input type="text" name="harga_jual" id="harga_jual" class="w-full border p-2 rounded harga-input">
                </div>
            </div>

            {{-- Kuantiti --}}
            <div>
                <label class="block font-semibold mb-1">Kuantiti Belanja</label>
                <input type="number" name="kuantiti" class="w-full border p-2 rounded" required min="1">
            </div>

            {{-- Tombol --}}
            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('bengkel.belanja.index') }}"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" id="btnSimpan"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Simpan</button>
            </div>
        </form>

        <!-- modal -->
        <!-- MODAL TAMBAH BARANG BARU -->
        <div id="modal-tambah-barang" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-bold mb-4">Tambah Barang Baru</h2>

                <form id="form-tambah-barang">
                    @csrf
                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Nama Barang</label>
                        <input type="text" name="nama_barang" id="nama_barang_baru" class="w-full border p-2 rounded"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Kategori</label>
                        <select name="kategori_id" id="kategori_id" class="w-full border p-2 rounded">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Stok Awal</label>
                        <input type="number" name="stok" id="stok_baru" class="w-full border p-2 rounded" value="0"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Harga Beli (Rp)</label>
                        <input type="text" name="harga_beli" id="harga_beli_baru" class="w-full border p-2 rounded"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Harga Jual (Rp)</label>
                        <input type="text" name="harga_jual" id="harga_jual_baru" class="w-full border p-2 rounded"
                            required>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" id="close-modal"
                            class="bg-gray-300 px-3 py-2 rounded hover:bg-gray-400">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- SWEET ALERT & SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-barang');
            const resultsBox = document.getElementById('search-results');
            const barangInfo = document.getElementById('barang-info');
            const clearBtn = document.getElementById('clear-btn');
            const form = document.getElementById('formBelanja');
            let timeout = null;

            // 🔍 Search Barang
            searchInput.addEventListener('keyup', function () {
                clearTimeout(timeout);
                const query = this.value.trim();
                if (query.length < 2) {
                    resultsBox.classList.add('hidden');
                    return;
                }

                timeout = setTimeout(() => {
                    fetch(`{{ route('bengkel.belanja.search-barang') }}?q=${query}`)
                        .then(res => res.json())
                        .then(data => {
                            resultsBox.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const li = document.createElement('li');
                                    li.textContent = `${item.nama_barang}`;
                                    li.classList.add('px-3', 'py-2', 'hover:bg-blue-100', 'cursor-pointer');
                                    li.addEventListener('click', () => selectBarang(item));
                                    resultsBox.appendChild(li);
                                });
                            } else {
                                resultsBox.innerHTML = '<li class="px-3 py-2 text-gray-500">Barang tidak ditemukan</li>';
                            }
                            resultsBox.classList.remove('hidden');
                        })
                        .catch(() => {
                            resultsBox.innerHTML = '<li class="px-3 py-2 text-red-500">Terjadi error koneksi</li>';
                            resultsBox.classList.remove('hidden');
                        });
                }, 300);
            });

            // 🧹 Clear barang terpilih
            clearBtn.addEventListener('click', function () {
                searchInput.value = '';
                document.getElementById('barang_id').value = '';
                document.getElementById('stok_saat_ini').value = '';
                document.getElementById('harga_beli').value = '';
                document.getElementById('harga_jual').value = '';
                barangInfo.classList.add('hidden');
                this.classList.add('hidden');
            });

            // ✅ Select Barang
            function selectBarang(item) {
                document.getElementById('barang_id').value = item.id;
                document.getElementById('stok_saat_ini').value = item.stok;
                document.getElementById('harga_beli').value = formatRupiah(item.harga_beli.toString());
                document.getElementById('harga_jual').value = formatRupiah(item.harga_jual.toString());
                barangInfo.classList.remove('hidden');
                resultsBox.classList.add('hidden');
                searchInput.value = item.nama_barang;
                clearBtn.classList.remove('hidden');
            }

            // 💰 Format angka jadi rupiah-like (1.000.000)
            document.querySelectorAll('.harga-input').forEach(input => {
                input.addEventListener('input', function (e) {
                    let value = this.value.replace(/\D/g, '');
                    this.value = formatRupiah(value);
                });
            });

            function formatRupiah(angka) {
                if (!angka) return '';
                return angka.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // 🚀 SweetAlert on Submit Success
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Simpan Belanja?',
                    text: "Pastikan data sudah benar!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Simpan'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection