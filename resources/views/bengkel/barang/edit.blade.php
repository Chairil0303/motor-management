@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Barang</h1>

        <form id="formEditBarang" action="{{ route('bengkel.barang.update', $barang->id) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')

            <div>
                <label class="block font-semibold mb-1">Nama Barang</label>
                <input type="text" name="nama_barang" class="w-full border p-2 rounded"
                    value="{{ $barang->nama_barang }}" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Stok</label>
                <input type="number" name="stok" class="w-full border p-2 rounded"
                    value="{{ $barang->stok }}" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Kategori</label>
                <select name="kategori_id" class="w-full border p-2 rounded" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoris as $k)
                        <option value="{{ $k->id }}" {{ $barang->kategori_id == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Harga Beli</label>
                <input type="text" name="harga_beli" id="harga_beli" class="harga-input w-full border p-2 rounded"
                    value="{{ number_format($barang->harga_beli, 0, ',', '.') }}" required>
            </div>

            <div>
                <label class="block font-semibold mb-1">Harga Jual</label>
                <input type="text" name="harga_jual" id="harga_jual" class="harga-input w-full border p-2 rounded"
                    value="{{ number_format($barang->harga_jual, 0, ',', '.') }}" required>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <a href="{{ route('bengkel.barang.index') }}"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Batal</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update
                </button>
            </div>
        </form>
    </div>

    {{-- SweetAlert2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('formEditBarang');

            // format angka ribuan saat input
            document.querySelectorAll('.harga-input').forEach(input => {
                input.addEventListener('input', function () {
                    let val = this.value.replace(/\D/g, '');
                    this.value = val === '' ? '' : val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                });
            });

            // hapus titik sebelum submit
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Update Barang?',
                    text: "Pastikan data sudah benar!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Update'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // bersihkan angka
                        const hb = document.getElementById('harga_beli');
                        const hj = document.getElementById('harga_jual');
                        hb.value = hb.value.replace(/\D/g, '');
                        hj.value = hj.value.replace(/\D/g, '');
                        form.submit();
                    }
                });
            });

            // kalau ada pesan sukses dari Laravel
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif
        });
    </script>
@endsection
