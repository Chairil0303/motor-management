@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">📊 Laporan Penjualan Motor</h1>

        <table class="w-full border text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Tanggal Jual</th>
                    <th class="p-2 border">Motor</th>
                    <th class="p-2 border">Laba</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualan as $p)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration + ($penjualan->currentPage() - 1) * $penjualan->perPage() }}
                        </td>
                        <td class="p-2 border">{{ \Carbon\Carbon::parse($p->tanggal_jual)->format('d M Y') }}</td>
                        <td class="p-2 border">
                            {{ $p->motor->merek ?? '-' }} - {{ $p->motor->tipe_model ?? '-' }}
                        </td>
                        <td class="p-2 border text-green-600 font-semibold">
                            Rp {{ number_format($p->laba, 0, ',', '.') }}
                        </td>
                        <td class="p-2 border text-center">
                            <button onclick="openModal({{ $p->id }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">
                                Detail
                            </button>
                        </td>
                    </tr>

                    {{-- Modal Detail --}}
                    <div id="modal-{{ $p->id }}"
                        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div
                            class="bg-white rounded-lg shadow-lg w-full max-w-3xl p-6 relative animate-fadeIn overflow-y-auto max-h-[85vh]">
                            <h2 class="text-xl font-bold mb-4 text-center">Detail Penjualan</h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Info Motor --}}
                                <div>
                                    <h3 class="text-lg font-semibold mb-2 border-b pb-1">Motor</h3>
                                    <p><strong>Merek:</strong> {{ $p->motor->merek ?? '-' }}</p>
                                    <p><strong>Tipe:</strong> {{ $p->motor->tipe_model ?? '-' }}</p>
                                    <p><strong>Plat Nomor:</strong> {{ $p->motor->plat_nomor ?? '-' }}</p>
                                    <p><strong>Tahun:</strong> {{ $p->motor->tahun ?? '-' }}</p>
                                    <p><strong>Harga Beli:</strong> Rp
                                        {{ number_format($p->motor->harga_beli ?? 0, 0, ',', '.') }}
                                    </p>
                                    <p><strong>Harga Jual:</strong> Rp {{ number_format($p->harga_jual, 0, ',', '.') }}</p>
                                    <p><strong>Total Biaya:</strong> Rp {{ number_format($p->total_biaya, 0, ',', '.') }}</p>
                                    <p><strong>Laba:</strong> <span class="text-green-600 font-semibold">Rp
                                            {{ number_format($p->laba, 0, ',', '.') }}</span></p>
                                </div>

                                {{-- Info Pembeli --}}
                                <div>
                                    <h3 class="text-lg font-semibold mb-2 border-b pb-1">Pembeli</h3>
                                    <p><strong>Nama:</strong> {{ $p->nama_pembeli ?? '-' }}</p>
                                    <p><strong>No. Telp:</strong> {{ $p->no_telp_pembeli ?? '-' }}</p>
                                    <p><strong>Alamat:</strong> {{ $p->alamat_pembeli ?? '-' }}</p>
                                    <p><strong>Tanggal Jual:</strong>
                                        {{ \Carbon\Carbon::parse($p->tanggal_jual)->format('d M Y') }}</p>
                                    <p><strong>Dibuat:</strong> {{ $p->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end">
                                <button onclick="closeModal({{ $p->id }})"
                                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition">Tutup</button>
                            </div>

                            <button onclick="closeModal({{ $p->id }})"
                                class="absolute top-3 right-4 text-gray-500 hover:text-black text-2xl">✕</button>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="text-center p-4 text-gray-500">Belum ada penjualan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $penjualan->links() }}
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            modal.classList.add('hidden');
            document.body.style.overflow = '';
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