@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Transaksi Penjualan Barang</h1>
        <a href="{{ route('bengkel.penjualanbarang.create') }}" 
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Jual Barang
        </a>
    </div>

    {{-- Filter --}}
    
    <form method="GET" class="mb-4 flex gap-2" id="filterForm">
        <input type="month" name="bulan" value="{{ request('bulan') }}" 
               class="border rounded p-2 auto-submit">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
               class="border rounded p-2 auto-submit">
        <a href="{{ route('bengkel.penjualanbarang.index') }}" 
           class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
            Reset
        </a>
        <!-- Tombol Cetak Laporan -->
        <button data-open-cetak type="button"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                id="btnCetakLaporan">
            🖨️ Cetak Laporan
        </button>
    </form>
    

    <style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px) scale(0.98); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.25s ease-out;
    }

    #modalCetak {
    background-color: rgba(0, 0, 0, 0.6); /* layar belakang gelap solid */
    backdrop-filter: none; /* pastiin gak blur */
    transition: opacity 0.3s ease;
    }
    #modalCetak.hidden {
        opacity: 0;
        pointer-events: none;
    }
    #modalCetak:not(.hidden) {
        opacity: 1;
    }
    </style>

    <!-- Modal Pilih Filter -->
    <div id="modalCetak" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-xl w-[24rem] p-6 border border-gray-200 relative animate-fadeIn">
            <!-- Tombol Close -->
            <button type="button" data-modal-close
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition">
                ✖
            </button>

            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center border-b pb-2">🖨️ Cetak Laporan</h2>

            <div class="space-y-6">
                <!-- Cetak Berdasarkan Bulan -->
                <form action="{{ route('bengkel.penjualanbarang.cetak-bulan') }}" method="GET" target="_blank"
                    class="flex flex-col gap-2">
                    <label class="font-medium text-sm text-gray-700">Pilih Bulan</label>
                    <input type="month" name="bulan"
                        class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        required>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                        Cetak Berdasarkan Bulan
                    </button>
                </form>

                <!-- Cetak Berdasarkan Tanggal -->
                <form action="{{ route('bengkel.penjualanbarang.cetak-tanggal') }}" method="GET" target="_blank"
                    class="flex flex-col gap-2">
                    <label class="font-medium text-sm text-gray-700">Pilih Tanggal</label>
                    <input type="date" name="tanggal"
                        class="border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                        required>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                        Cetak Berdasarkan Tanggal
                    </button>
                </form>
            </div>

            <button type="button" data-modal-close
                    class="mt-6 w-full bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium px-4 py-2 rounded-lg transition">
                Batal
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full border border-collapse">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="p-2 border">Kode Penjualan</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border text-center">Detail Transaksi</th>
                    <th class="p-2 border text-right">Total Penjualan</th>
                    <th class="p-2 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualanBarangs as $pj)
                    <tr>
                        <td class="p-2 border">{{ $pj->kode_penjualan }}</td>
                        <td class="p-2 border">{{ \Carbon\Carbon::parse($pj->tanggal_penjualan)->format('d M Y') }}</td>
                        <td class="p-2 border text-center">
                            <button 
                                type="button"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                                data-modal-target="modal-{{ $pj->id }}">
                                Detail
                            </button>
                            {{-- Include Modal --}}
                            @include('bengkel.penjualanbarang.partials.modal-detail', ['penjualan' => $pj])
                        </td>
                        <td class="p-2 border text-right text-green">Rp{{ number_format($pj->total_penjualan, 0, ',', '.') }}</td>
                        <td class="p-2 border text-center  whitespace-nowrap">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('bengkel.penjualanbarang.edit', $pj->id) }}" 
                                   class="bg-yellow-400 px-2 py-1 rounded text-black hover:bg-yellow-500">Edit</a>
                                <form method="POST" action="{{ route('bengkel.penjualanbarang.destroy', $pj->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Yakin mau hapus transaksi ini?')" 
                                            class="bg-red-500 px-2 py-1 rounded text-white hover:bg-red-600">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">Belum ada transaksi penjualan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $penjualanBarangs->links() }}
    </div>
</div>

{{-- JS Handler --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Modal
    document.querySelectorAll('[data-modal-target]').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.modalTarget;
            const modal = document.getElementById(id);
            if (modal) modal.classList.remove('hidden');
        });
    });

    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal');
            modal.classList.add('hidden');
        });
    });

    // Auto filter (submit otomatis)
    document.querySelectorAll('.auto-submit').forEach(input => {
        input.addEventListener('change', () => {
            document.getElementById('filterForm').submit();
        });
    });
});

        document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('modalCetak');

    document.querySelector('[data-open-cetak]').addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    document.querySelectorAll('[data-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    });
});
</script>
@endsection
