@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Penjualan</h1>

            <!-- Form Search -->
            <form method="GET" action="{{ route('penjualan.index') }}" class="flex gap-2">
                <input autocomplete="off" type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari plat nomor..." class="border rounded p-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Cari</button>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif

        <table class="w-full border text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Motor</th>
                    <th class="p-2 border">Plat Nomor</th>
                    <th class="p-2 border">Harga Beli</th>
                    <th class="p-2 border">Restorasi</th>
                    <th class="p-2 border">Total</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($motor as $m)
                    @php
                        $totalRestorasi = $m->restorasis->sum('biaya_restorasi');
                        $totalModal = $m->harga_beli + $totalRestorasi;
                    @endphp
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration + ($motor->currentPage() - 1) * $motor->perPage() }}</td>
                        <td class="p-2 border">{{ $m->merek }} - {{ $m->tipe_model }}</td>
                        <td class="p-2 border">{{ $m->plat_nomor }}</td>
                        <td class="p-2 border">Rp {{ number_format($m->harga_beli, 0, ',', '.') }}</td>
                        <td class="p-2 border">Rp {{ number_format($totalRestorasi, 0, ',', '.') }}</td>
                        <td class="p-2 border font-semibold">Rp {{ number_format($totalModal, 0, ',', '.') }}</td>
                        <td class="p-2 border text-center">
                            <button onclick="openModal({{ $m->id }}, {{ $m->harga_beli }}, {{ $totalRestorasi }})"
                                class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 transition">
                                Jual
                            </button>
                        </td>
                    </tr>

                    {{-- MODAL JUAL --}}
                    <div id="modal-{{ $m->id }}"
                        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative animate-fadeIn">
                            <h2 class="text-xl font-bold mb-4">Jual Motor</h2>

                            <form id="formJual-{{ $m->id }}" method="POST" action="{{ route('penjualan.store') }}">
                                @csrf
                                <input type="hidden" name="motor_id" value="{{ $m->id }}">
                                <input type="hidden" name="status" value="terjual">

                                <div class="mb-3 space-y-1">
                                    <p><strong>Motor:</strong> {{ $m->merek }} - {{ $m->tipe_model }}</p>
                                    <p><strong>Harga Beli:</strong> Rp {{ number_format($m->harga_beli, 0, ',', '.') }}</p>
                                    <p><strong>Restorasi:</strong> Rp {{ number_format($totalRestorasi, 0, ',', '.') }}</p>
                                    <p><strong>Total Modal:</strong> <span class="text-blue-600 font-semibold">Rp
                                            {{ number_format($totalModal, 0, ',', '.') }}</span></p>
                                </div>

                                <div class="mb-3">
                                    <label class="block font-semibold mb-1">Harga Jual</label>
                                    <input autocomplete="off" type="text" name="harga_jual" oninput="formatInput(this); hitungLaba({{ $m->id }})"
                                        id="harga_jual_{{ $m->id }}" class="w-full border p-2 rounded" required>
                                </div>

                                <div class="mb-3">
                                    <label class="block font-semibold mb-1">Laba</label>
                                    <input type="text" id="laba_{{ $m->id }}" readonly
                                        class="w-full border p-2 rounded bg-gray-100 font-semibold text-green-600">
                                </div>

                                <div class="mb-3">
                                    <label class="block font-semibold mb-1">Tanggal Jual</label>
                                    <input type="date" name="tanggal_jual" class="w-full border p-2 rounded" required>
                                </div>

                                <div class="mt-4 flex justify-end gap-2">
                                    <button type="button" onclick="closeModal({{ $m->id }})"
                                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">
                                        Batal
                                    </button>
                                    <button type="button" onclick="confirmJual({{ $m->id }})"
                                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                                        Jual
                                    </button>
                                </div>
                            </form>

                            <button onclick="closeModal({{ $m->id }})"
                                class="absolute top-2 right-2 text-gray-500 hover:text-black">✕</button>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-gray-500">Tidak ada data motor tersedia.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $motor->links() }}
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // === Modal ===
        function openModal(id, hargaBeli, restorasi) {
            const modal = document.getElementById(`modal-${id}`);
            modal.dataset.hargaBeli = hargaBeli;
            modal.dataset.restorasi = restorasi;
            modal.classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(`modal-${id}`).classList.add('hidden');
        }

        // === Format input harga jual pakai koma ===
        function formatInput(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) {
                input.value = parseInt(value).toLocaleString('id-ID');
            } else {
                input.value = '';
            }
        }

        // === Hitung laba realtime ===
        function hitungLaba(id) {
            const modal = document.getElementById(`modal-${id}`);
            const hargaBeli = parseFloat(modal.dataset.hargaBeli);
            const restorasi = parseFloat(modal.dataset.restorasi);
            const input = document.getElementById(`harga_jual_${id}`);
            const hargaJual = parseFloat(input.value.replace(/\./g, '')) || 0;
            const laba = hargaJual - (hargaBeli + restorasi);
            document.getElementById(`laba_${id}`).value = `Rp ${laba.toLocaleString('id-ID')}`;
        }

        // === Konfirmasi jual pakai SweetAlert ===
        function confirmJual(id) {
            Swal.fire({
                title: 'Yakin jual motor ini?',
                text: 'Status motor akan berubah menjadi TERJUAL!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Jual Sekarang',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`formJual-${id}`).submit();
                }
            });
        }
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out;
        }
    </style>
@endsection