@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Edit Riwayat Belanja</h1>

    <form method="POST" action="{{ route('bengkel.belanja.update', $belanja->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Barang (readonly) --}}
        <div>
            <label class="block font-semibold mb-1">Barang</label>
            <input type="text" value="{{ $belanja->barang->nama_barang }}" class="border rounded p-2 w-full bg-gray-100" readonly>
            <input type="hidden" name="barang_id" value="{{ $belanja->barang_id }}">
        </div>

        <div>
            <label class="block font-semibold mb-1">Kuantiti</label>
            <input type="number" name="kuantiti" value="{{ old('kuantiti', $belanja->kuantiti) }}" class="border rounded p-2 w-full">
        </div>

        <div>
            <label class="block font-semibold mb-1">Harga Beli</label>
            <input type="text" name="harga_beli" id="harga_beli" 
                   value="{{ number_format($belanja->harga_beli, 0, ',', '.') }}" 
                   class="border rounded p-2 w-full">
        </div>

        <div>
            <label class="block font-semibold mb-1">Harga Jual</label>
            <input type="text" name="harga_jual" id="harga_jual" 
                   value="{{ number_format($belanja->barang->harga_jual, 0, ',', '.') }}" 
                   class="border rounded p-2 w-full">
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('bengkel.belanja.index') }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>

{{-- Auto-format angka pakai titik ribuan --}}
<script>
    function formatRupiah(angka) {
        return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function setupAutoFormat(id) {
        const input = document.getElementById(id);
        input.addEventListener("input", function(e) {
            let value = e.target.value.replace(/\D/g, "");
            e.target.value = formatRupiah(value);
        });
    }

    setupAutoFormat("harga_beli");
    setupAutoFormat("harga_jual");
</script>
@endsection
