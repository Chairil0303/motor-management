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
            <div class="mt-5">
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

        @include('bengkel.belanja.partials.modal-tambah-barang')


    </div>

    @include('bengkel.belanja.partials.script-create')
@endsection