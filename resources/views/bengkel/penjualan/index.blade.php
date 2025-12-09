@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Penjualan Barang Bengkel</h1>
            <a href="{{ route('bengkel.penjualan.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Penjualan Baru</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif

        <table class="w-full border text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Kode</th>
                    <th class="p-2 border">Tanggal</th>
                    <th class="p-2 border">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penjualan as $j)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $j->kode_penjualan }}</td>
                        <td class="p-2 border">{{ $j->tanggal_penjualan }}</td>
                        <td class="p-2 border font-semibold text-blue-600">Rp {{ number_format($j->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-500">Belum ada data penjualan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection