@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Penjualan</h1>
            <a href="{{ route('penjualan.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif

        <table class="w-full border text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Motor</th>
                    <th class="p-2 border">Pelanggan</th>
                    <th class="p-2 border">Harga Jual</th>
                    <th class="p-2 border">Total Biaya</th>
                    <th class="p-2 border">Laba</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan as $p)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $p->motor->merek }} - {{ $p->motor->tipe_model }}</td>
                        <td class="p-2 border">{{ $p->pelanggan->nama }}</td>
                        <td class="p-2 border">Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</td>
                        <td class="p-2 border">Rp {{ number_format($p->total_biaya, 0, ',', '.') }}</td>
                        <td class="p-2 border text-green-600 font-semibold">Rp {{ number_format($p->laba, 0, ',', '.') }}</td>
                        <td class="p-2 border">{{ $p->tanggal_jual }}</td>
                        <td class="p-2 border flex gap-2">
                            <a href="{{ route('penjualan.edit', $p->id) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('penjualan.destroy', $p->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection