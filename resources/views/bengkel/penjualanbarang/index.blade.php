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

    <form method="GET" class="mb-4 flex gap-2">
        <input type="month" name="bulan" value="{{ request('bulan') }}" 
               class="border rounded p-2">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}" 
               class="border rounded p-2">
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
            Filter
        </button>
        <a href="{{ route('bengkel.penjualanbarang.index') }}" 
           class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
            Reset
        </a>
    </form>

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full border">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="p-2 border">Kode Penjualan</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Detail Barang</th>
                    <th class="p-2 border">Total Penjualan</th>
                    <th class="p-2 border">Total Margin</th>
                    <th class="p-2 border">Harga Jasa</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualanBarangs as $pj)
                    <tr>
                        <td class="p-2 border">{{ $pj->kode_penjualan }}</td>
                        <td class="p-2 border">{{ \Carbon\Carbon::parse($pj->tanggal_penjualan)->format('d M Y H:i') }}</td>
                        <td class="p-2 border">
                            <ul class="list-disc ml-4">
                                @foreach ($pj->details as $detail)
                                    <li>{{ $detail->barang->nama_barang }} (x{{ $detail->kuantiti }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td class="p-2 border text-right">Rp{{ number_format($pj->total_penjualan, 0, ',', '.') }}</td>
                        <td class="p-2 border text-right">Rp{{ number_format($pj->total_margin, 0, ',', '.') }}</td>
                        <td class="p-2 border text-right">Rp{{ number_format($pj->harga_jasa, 0, ',', '.') }}</td>
                        <td class="p-2 border">
                            <div class="flex gap-2">
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
</div>
@endsection
