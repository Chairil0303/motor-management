@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Motor</h1>
            <!-- <a href="{{ route('motor.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Tambah Motor</a> -->
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        <table class="w-full border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Motor</th>
                    <th class="p-2 border">Plat Nomor</th>
                    <th class="p-2 border">Harga Beli</th>
                    <th class="p-2 border">Restorasi</th>
                    <th class="p-2 border">Harga Jual</th>
                    <th class="p-2 border">Detail</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($motor as $m)
                    @php
                        $totalRestorasi = $m->restorasis->sum('biaya_restorasi');
                    @endphp
                    <tr>
                        <td class="p-2 border">{{ $loop->iteration }}</td>
                        <td class="p-2 border">{{ $m->tipe_model }}</td>
                        <td class="p-2 border">{{ $m->plat_nomor }}</td>
                        <td class="p-2 border">Rp {{ number_format($m->harga_beli, 0, ',', '.') }}</td>
                        <td class="p-2 border">Rp {{ number_format($totalRestorasi, 0, ',', '.') }}</td>
                        <td class="p-2 border">
                            {{ $m->harga_jual ? 'Rp ' . number_format($m->harga_jual, 0, ',', '.') : '-' }}
                        </td>
                        <td class="p-2 border text-center">
                            <button onclick="openModal({{ $m->id }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                                Detail
                            </button>
                        </td>
                        <td class="p-2 border flex gap-2">
                            <a href="{{ route('motor.edit', $m->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('motor.destroy', $m->id) }}" method="POST"
                                onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    {{-- MODAL DETAIL MOTOR --}}
                    <div id="modal-{{ $m->id }}"
                        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
                        <div
                            class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative transform scale-95 transition-all duration-300">
                            <h2 class="text-xl font-bold mb-4">Detail Motor</h2>
                            <div class="space-y-2">
                                <p><strong>Motor:</strong> {{ $m->tipe_model }}</p>
                                <p><strong>Plat Nomor:</strong> {{ $m->plat_nomor }}</p>
                                <p><strong>Harga Beli:</strong> Rp {{ number_format($m->harga_beli, 0, ',', '.') }}</p>
                                <p><strong>Total Restorasi:</strong> Rp {{ number_format($totalRestorasi, 0, ',', '.') }}</p>
                                <p><strong>Harga Jual:</strong>
                                    {{ $m->harga_jual ? 'Rp ' . number_format($m->harga_jual, 0, ',', '.') : '-' }}</p>
                                <p><strong>Kondisi:</strong> {{ $m->kondisi ?? '-' }}</p>
                                <hr>
                                <p><strong>Nama Penjual:</strong> {{ $m->nama_penjual }}</p>
                                <p><strong>No. Telp Penjual:</strong> {{ $m->no_telp_penjual }}</p>
                                <p><strong>Alamat Penjual:</strong> {{ $m->alamat_penjual }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($m->status) }}</p>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <button onclick="closeModal({{ $m->id }})"
                                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                                    Kembali
                                </button>
                            </div>

                            <button onclick="closeModal({{ $m->id }})"
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