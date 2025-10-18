@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Manajemen Kategori Barang</h1>
            <a href="{{ route('bengkel.kategori.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Kategori
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border text-left text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border w-12 text-center">#</th>
                    <th class="p-2 border">Nama Kategori</th>
                    <th class="p-2 border w-32 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris as $k)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border text-center">
                            {{ ($kategoris->currentPage() - 1) * $kategoris->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-2 border">{{ $k->nama_kategori }}</td>
                        <td class="p-2 border text-center">
                            <a href="{{ route('bengkel.kategori.edit', $k->id) }}"
                                class="bg-yellow-500 text-white px-2 py-1 text-xs rounded hover:bg-yellow-600">
                                Edit
                            </a>
                            <form action="{{ route('bengkel.kategori.destroy', $k->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus kategori ini?')"
                                    class="bg-red-500 text-white px-2 py-1 text-xs rounded hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center p-4 text-gray-500">Belum ada data kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $kategoris->links('pagination::tailwind') }}
        </div>
    </div>
@endsection