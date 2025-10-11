@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Pembelian</h1>
            <a href="{{ route('motor.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Beli Motor
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border border-gray-300 text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Motor</th>
                    <th class="p-2 border">Tanggal Beli</th>
                    <th class="p-2 border">Biaya Beli</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembelian as $beli)
                    @php
                        $motor = $beli->motor;
                        $formattedDate = \Carbon\Carbon::parse($beli->tanggal_beli)->format('d/m/Y');
                    @endphp

                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $motor->merek }} - {{ $motor->tipe_model }}</td>
                        <td class="p-2 border">{{ $formattedDate }}</td>
                        <td class="p-2 border">Rp {{ number_format($beli->biaya_beli, 0, ',', '.') }}</td>
                        <td class="p-2 border text-center">
                            <button onclick="openModal({{ $beli->id }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Detail
                            </button>
                        </td>
                    </tr>

                    {{-- MODAL DETAIL MOTOR --}}
                    <div id="modal-{{ $beli->id }}"
                        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
                        <div
                            class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative transform scale-95 transition-all duration-300">
                            <h2 class="text-xl font-bold mb-4">Detail Motor</h2>
                            <div class="space-y-2">
                                <p><strong>Merek:</strong> {{ $motor->merek }}</p>
                                <p><strong>Model:</strong> {{ $motor->tipe_model }}</p>
                                <p><strong>Tahun:</strong> {{ $motor->tahun }}</p>
                                <p><strong>Plat Nomor:</strong> {{ $motor->plat_nomor }}</p>
                                <p><strong>Harga Beli:</strong> Rp {{ number_format($motor->harga_beli, 0, ',', '.') }}</p>
                                <p><strong>Harga Jual:</strong>
                                    {{ $motor->harga_jual ? 'Rp ' . number_format($motor->harga_jual, 0, ',', '.') : '-' }}
                                </p>
                                <p><strong>Kondisi:</strong> {{ $motor->kondisi ?? '-' }}</p>
                                <hr>
                                <p><strong>Nama Penjual:</strong> {{ $motor->nama_penjual }}</p>
                                <p><strong>No. Telp Penjual:</strong> {{ $motor->no_telp_penjual }}</p>
                                <p><strong>Alamat Penjual:</strong> {{ $motor->alamat_penjual }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($motor->status) }}</p>
                                <p><strong>Tanggal Beli:</strong> {{ $formattedDate }}</p>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <button onclick="closeModal({{ $beli->id }})"
                                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                                    Tutup
                                </button>
                            </div>

                            <button onclick="closeModal({{ $beli->id }})"
                                class="absolute top-2 right-2 text-gray-500 hover:text-black">
                                ✕
                            </button>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                modal.querySelector('div').classList.remove('scale-95');
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
    </script>
@endsection