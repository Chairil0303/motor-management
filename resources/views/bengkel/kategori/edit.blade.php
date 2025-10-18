@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Kategori</h1>

        <form action="{{ route('bengkel.kategori.update', $kategori->id) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block font-semibold mb-1">Nama Kategori</label>
                <input type="text" name="nama_kategori" value="{{ $kategori->nama_kategori }}"
                    class="w-full border p-2 rounded" required>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('bengkel.kategori.index') }}"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
@endsection