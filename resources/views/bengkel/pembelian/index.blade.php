@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Pembelian Barang Bengkel</h1>
            <a href="{{ route('bengkel.pembelian.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Pembelian Baru</a>
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
                @forelse($pembelian as $p)
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $p->kode_pembelian }}</td>
                        <td class="p-2 border">{{ $p->tanggal_pembelian }}</td>
                        <td class="p-2 border font-semibold text-blue-600">Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-4 text-gray-500">Belum ada data pembelian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection