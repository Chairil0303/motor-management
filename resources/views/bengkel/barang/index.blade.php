@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Manajemen Barang Bengkel</h1>
            <a href="{{ route('bengkel.barang.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                + Tambah Barang
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif

        <table class="w-full border text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Nama Barang</th>
                    <th class="p-2 border">Stok</th>
                    <th class="p-2 border">Harga Beli</th>
                    <th class="p-2 border">Harga Jual</th>
                    <th class="p-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $b)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $b->nama_barang }}</td>
                        <td class="p-2 border">{{ $b->stok }}</td>
                        <td class="p-2 border">Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                        <td class="p-2 border">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                        <td class="p-2 border text-center">
                            <a href="{{ route('bengkel.barang.edit', $b->id) }}"
                                class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">Edit</a>
                            <form action="{{ route('bengkel.barang.destroy', $b->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus barang ini?')"
                                    class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center p-4 text-gray-500">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection