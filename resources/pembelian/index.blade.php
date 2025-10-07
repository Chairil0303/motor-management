@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Pembelian</h1>
            <a href="{{ route('pembelian.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif

        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Motor</th>
                    <th class="p-2 border">Tanggal Beli</th>
                    <th class="p-2 border">Biaya Beli</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelian as $beli)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $beli->motor->merek }} - {{ $beli->motor->tipe_model }}</td>
                        <td class="p-2 border">{{ $beli->tanggal_beli }}</td>
                        <td class="p-2 border">Rp {{ number_format($beli->biaya_beli, 0, ',', '.') }}</td>
                        <td class="p-2 border flex gap-2">
                            <a href="{{ route('pembelian.edit', $beli->id) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('pembelian.destroy', $beli->id) }}" method="POST"
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