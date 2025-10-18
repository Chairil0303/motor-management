@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Tambah Barang</h1>

        <form action="{{ route('bengkel.barang.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block font-semibold mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Stok</label>
                <input type="number" name="stok" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Harga Beli</label>
                <input type="number" name="harga_beli" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block font-semibold mb-1">Harga Jual</label>
                <input type="number" name="harga_jual" class="w-full border p-2 rounded" required>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('bengkel.barang.index') }}"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection