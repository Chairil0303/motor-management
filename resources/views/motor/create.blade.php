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

            {{-- Merek --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Merek</label>
                <select name="merek" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Merek --</option>
                    <option value="Honda">Honda</option>
                    <option value="Yamaha">Yamaha</option>
                    <option value="Suzuki">Suzuki</option>
                    <option value="Kawasaki">Kawasaki</option>
                    <option value="Vespa">Vespa</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>

            {{-- Tipe / Model --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Tipe / Model</label>
                <input type="text" name="tipe_model" class="w-full border rounded px-3 py-2"
                    placeholder="Contoh: Beat Street" required>
            </div>

            {{-- Tahun --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Tahun</label>
                <select name="tahun" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Tahun --</option>
                    @for ($tahun = date('Y'); $tahun >= 1990; $tahun--)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                    @endfor
                </select>
            </div>

            {{-- Harga Beli --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Harga Beli</label>
                <input type="text" name="harga_beli" id="harga_beli" class="w-full border rounded px-3 py-2"
                    placeholder="Contoh: 12.500.000" required>
            </div>

            {{-- Plat Nomor --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Plat Nomor</label>
                <input type="text" name="plat_nomor" class="w-full border rounded px-3 py-2"
                    placeholder="Contoh: B 1234 ABC" required>
            </div>

            {{-- Nama Penjual --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama Penjual</label>
                <input type="text" name="nama_penjual" class="w-full border rounded px-3 py-2" required>
            </div>

            {{-- No Telp Penjual --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">No. Telp Penjual</label>
                <input type="text" name="no_telp_penjual" class="w-full border rounded px-3 py-2" placeholder="08xxxxxxx"
                    required>
            </div>

            {{-- Alamat Penjual --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Alamat Penjual</label>
                <textarea name="alamat_penjual" class="w-full border rounded px-3 py-2" rows="3" required></textarea>
            </div>

            {{-- Kondisi --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Kondisi</label>
                <input type="text" name="kondisi" class="w-full border rounded px-3 py-2"
                    placeholder="Contoh: Mulus, Bekas, Perlu servis">
            </div>


            <div class="flex justify-end gap-2">
                <a href="{{ route('motor.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>

    {{-- Format harga otomatis --}}
    <script>
        const hargaInput = document.getElementById('harga_beli');
        hargaInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = new Intl.NumberFormat('id-ID').format(value);
            e.target.value = value;
        });
    </script>
@endsection