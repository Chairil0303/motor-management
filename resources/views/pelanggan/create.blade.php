@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-lg">
        <h1 class="text-2xl font-bold mb-4">Tambah Pelanggan</h1>
        <form method="POST" action="{{ route('pelanggan.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1">Nama</label>
                <input type="text" name="nama" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block mb-1">No HP</label>
                <input type="text" name="no_hp" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block mb-1">Alamat</label>
                <textarea name="alamat" class="w-full border p-2 rounded" required></textarea>
            </div>
            <div>
                <label class="block mb-1">Email (Opsional)</label>
                <input type="email" name="email" class="w-full border p-2 rounded">
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
@endsection