@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Tambah Penjualan Barang</h1>

        <form action="{{ route('bengkel.penjualan.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block font-semibold mb-1">Tanggal Penjualan</label>
                <input type="date" name="tanggal_penjualan" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Pilih Barang</label>
                <select name="barang_id" class="w-full border p-2 rounded" required>
                    @foreach($barang as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_barang }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Jumlah</label>
                <input type="number" name="jumlah" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Harga Satuan</label>
                <input type="number" name="harga_satuan" class="w-full border p-2 rounded" required>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('bengkel.penjualan.index') }}"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection