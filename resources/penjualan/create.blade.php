@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6 max-w-xl">
        <h1 class="text-2xl font-bold mb-4">Tambah Penjualan</h1>
        <form method="POST" action="{{ route('penjualan.store') }}" class="space-y-4">
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
                <label class="block mb-1">Pelanggan</label>
                <select name="pelanggan_id" class="w-full border p-2 rounded" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach($pelanggan as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Harga Jual</label>
                <input type="number" step="0.01" name="harga_jual" class="w-full border p-2 rounded" required>
            </div>

            <div>
                <label class="block mb-1">Tanggal Penjualan</label>
                <input type="date" name="tanggal_penjualan" class="w-full border p-2 rounded" required>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
@endsection