@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Motor</h1>
            <a href="{{ route('motor.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Motor</a>
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
                    <th class="p-2 border">Merek</th>
                    <th class="p-2 border">Tipe</th>
                    <th class="p-2 border">Tahun</th>
                    <th class="p-2 border">Harga Beli</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($motor as $m)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $m->merek }}</td>
                        <td class="p-2 border">{{ $m->tipe_model }}</td>
                        <td class="p-2 border">{{ $m->tahun }}</td>
                        <td class="p-2 border">Rp {{ number_format($m->harga_beli, 0, ',', '.') }}</td>
                        <td class="p-2 border">{{ ucfirst($m->status) }}</td>
                        <td class="p-2 border flex gap-2">
                            <a href="{{ route('motor.edit', $m->id) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('motor.destroy', $m->id) }}" method="POST"
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