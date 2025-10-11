@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4">Edit Data Motor</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ Tambahkan id ke form --}}
        <form id="editForm" action="{{ route('motor.update', $motor->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Merek --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Merek</label>
                <select name="merek" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Merek --</option>
                    @foreach (['Honda', 'Yamaha', 'Suzuki', 'Kawasaki', 'Vespa', 'Lainnya'] as $merek)
                        <option value="{{ $merek }}" {{ $motor->merek == $merek ? 'selected' : '' }}>
                            {{ $merek }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tipe / Model --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Tipe / Model</label>
                <input type="text" name="tipe_model" class="w-full border rounded px-3 py-2"
                    value="{{ old('tipe_model', $motor->tipe_model) }}" required>
            </div>

            {{-- Tahun --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Tahun</label>
                <select name="tahun" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih Tahun --</option>
                    @for ($tahun = date('Y'); $tahun >= 1990; $tahun--)
                        <option value="{{ $tahun }}" {{ $motor->tahun == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Harga Beli --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Harga Beli</label>
                <input type="text" name="harga_beli" id="harga_beli" class="w-full border rounded px-3 py-2"
                    value="{{ number_format($motor->harga_beli, 0, ',', '.') }}" required>
            </div>

            {{-- Harga Jual --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Harga Jual</label>
                <input type="text" name="harga_jual" id="harga_jual" class="w-full border rounded px-3 py-2"
                    value="{{ $motor->harga_jual ? number_format($motor->harga_jual, 0, ',', '.') : '' }}">
            </div>

            {{-- Plat Nomor --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Plat Nomor</label>
                <input type="text" name="plat_nomor" class="w-full border rounded px-3 py-2"
                    value="{{ old('plat_nomor', $motor->plat_nomor) }}" required>
            </div>

            {{-- Nama Penjual --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Nama Penjual</label>
                <input type="text" name="nama_penjual" class="w-full border rounded px-3 py-2"
                    value="{{ old('nama_penjual', $motor->nama_penjual) }}" required>
            </div>

            {{-- No Telp Penjual --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">No. Telp Penjual</label>
                <input type="text" name="no_telp_penjual" class="w-full border rounded px-3 py-2"
                    value="{{ old('no_telp_penjual', $motor->no_telp_penjual) }}" required>
            </div>

            {{-- Alamat Penjual --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Alamat Penjual</label>
                <textarea name="alamat_penjual" class="w-full border rounded px-3 py-2" rows="3"
                    required>{{ old('alamat_penjual', $motor->alamat_penjual) }}</textarea>
            </div>

            {{-- Kondisi --}}
            <div class="mb-4">
                <label class="block font-semibold mb-1">Kondisi</label>
                <input type="text" name="kondisi" class="w-full border rounded px-3 py-2"
                    value="{{ old('kondisi', $motor->kondisi) }}">
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label class="block font-semibold mb-1">Status</label>
                <select name="status" class="w-full border rounded px-3 py-2">
                    @foreach (['baru masuk', 'siap jual', 'tersedia', 'terjual'] as $status)
                        <option value="{{ $status }}" {{ $motor->status == $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <a href="{{ route('motor.index') }}" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
                <button type="button" onclick="confirmUpdate()"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmUpdate() {
            Swal.fire({
                title: 'Simpan perubahan?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('editForm').submit();
                }
            });
        }

        // ✅ Format harga otomatis
        const hargaInputs = [document.getElementById('harga_beli'), document.getElementById('harga_jual')];
        hargaInputs.forEach(input => {
            input.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = new Intl.NumberFormat('id-ID').format(value);
            });
        });
    </script>
@endsection