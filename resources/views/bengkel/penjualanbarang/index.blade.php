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
    </form>
    <a href="{{ route('bengkel.penjualanbarang.cetak', request()->all()) }}" 
    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
    🖨️ Cetak Laporan
    </a>

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
</script>
@endsection
