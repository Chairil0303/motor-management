@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Pelanggan</h1>
            <a href="{{ route('pelanggan.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah
                Pelanggan</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Nama</th>
                    <th class="p-2 border">No HP</th>
                    <th class="p-2 border">Alamat</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pelanggan as $p)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $p->nama }}</td>
                        <td class="p-2 border">{{ $p->no_hp }}</td>
                        <td class="p-2 border">{{ $p->alamat }}</td>
                        <td class="p-2 border">{{ $p->email ?? '-' }}</td>
                        <td class="p-2 border flex gap-2">
                            <a href="{{ route('pelanggan.edit', $p->id) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('pelanggan.destroy', $p->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection