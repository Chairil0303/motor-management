@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Restorasi</h1>
            <a href="{{ route('restorasi.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif

        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Motor</th>
                    <th class="p-2 border">Deskripsi</th>
                    <th class="p-2 border">Tanggal Restorasi</th>
                    <th class="p-2 border">Biaya Restorasi</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($restorasi as $r)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $r->motor->merek }} - {{ $r->motor->tipe_model }}</td>
                        <td class="p-2 border">{{ $r->deskripsi ?? '-' }}</td>
                        <td class="p-2 border">{{ $r->tanggal_restorasi }}</td>
                        <td class="p-2 border">Rp {{ number_format($r->biaya_restorasi, 0, ',', '.') }}</td>
                        <td class="p-2 border flex gap-2">
                            <a href="{{ route('restorasi.edit', $r->id) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('restorasi.destroy', $r->id) }}" method="POST"
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