@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-lg">
        <h1 class="text-2xl font-bold mb-4">Tambah Restorasi</h1>
        <form method="POST" action="{{ route('restorasi.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block mb-1">Motor</label>
                <select name="motor_id" class="w-full border p-2 rounded" required>
                    <option value="">-- Pilih Motor --</option>
                    @foreach($motor as $m)
                        <option value="{{ $m->id }}">{{ $m->merek }} - {{ $m->tipe_model }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border p-2 rounded"
                    placeholder="Contoh: Ganti cat, servis mesin kecil"></textarea>
            </div>
            <div>
                <label class="block mb-1">Tanggal Restorasi</label>
                <input type="date" name="tanggal_restorasi" class="w-full border p-2 rounded" required>
            </div>
            <div>
                <label class="block mb-1">Biaya Restorasi</label>
                <input type="number" step="0.01" name="biaya_restorasi" class="w-full border p-2 rounded" required>
            </div>
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
@endsection