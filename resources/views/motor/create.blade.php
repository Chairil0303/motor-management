@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Tambah Motor</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('motor.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-semibold mb-1">Merek</label>
                <input type="text" name="merek" class="w-full border rounded px-3 py-2" placeholder="Contoh: Honda"
                    required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Tipe / Model</label>
                <input type="text" name="tipe_model" class="w-full border rounded px-3 py-2"
                    placeholder="Contoh: Beat Street" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Tahun</label>
                <input type="number" name="tahun" class="w-full border rounded px-3 py-2" placeholder="Contoh: 2020"
                    min="1990" max="{{ date('Y') }}" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Harga Beli</label>
                <input type="number" name="harga_beli" class="w-full border rounded px-3 py-2"
                    placeholder="Contoh: 12500000" required>
            </div>

            <div class="mb-4">
                <label class="block font-semibold mb-1">Kondisi</label>
                <input type="text" name="kondisi" class="w-full border rounded px-3 py-2"
                    placeholder="Contoh: Mulus, Bekas, Butuh servis">
            </div>

            <div class="mb-6">
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    <option value="tersedia" selected>Tersedia</option>
                    <option value="baru masuk">Baru Masuk</option>
                    <option value="siap jual">Siap Jual</option>
                    <option value="terjual">Terjual</option>
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('motor.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection