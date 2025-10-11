@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Data Motor</h1>
        </div>

        {{-- ✅ Filter dan Search --}}
        <form method="GET" action="{{ route('motor.index') }}" class="flex flex-wrap gap-2 mb-4">
            <input type="text" name="search" value="{{ request('search') }}" autocomplete="off" placeholder="Cari plat nomor..."
                class="border rounded px-3 py-2 w-64">

            <select name="status" class="border rounded px-3 py-2">
                <option value="">-- Semua Status --</option>
                @foreach ([ 'tersedia', 'terjual'] as $status)
                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                        {{ ucfirst($status) }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
        </form>

        {{-- Alert sukses & error --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-800 p-3 rounded mb-3">{{ session('error') }}</div>
        @endif

        {{-- Tabel --}}
        <table class="w-full border border-gray-300 text-left">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">#</th>
                    <th class="p-2 border">Motor</th>
                    <th class="p-2 border">Plat Nomor</th>
                    <th class="p-2 border">Harga Beli</th>
                    <th class="p-2 border">Restorasi</th>
                    <th class="p-2 border">Harga Jual</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Detail</th>
                    <th class="p-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($motor as $m)
                    @php $totalRestorasi = $m->restorasis->sum('biaya_restorasi'); @endphp
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
                            @if ($m->status === 'tersedia')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Tersedia</span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">{{ ucfirst($m->status) }}</span>
                            @endif
                        </td>
                        <td class="p-2 border text-center">
                            <button onclick="openModal({{ $m->id }})"
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition">Detail</button>
                        </td>
                        <td class="p-2 border flex gap-2">
                            <a href="{{ route('motor.edit', $m->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('motor.destroy', $m->id) }}" method="POST" class="deleteForm">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="confirmDelete(this)" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>

                    {{-- MODAL DETAIL MOTOR --}}
                    <div id="modal-{{ $m->id }}"
                        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300">
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative transform scale-95 transition-all duration-300">
                            <h2 class="text-xl font-bold mb-4">Detail Motor</h2>
                            <div class="space-y-2">
                                <p><strong>Motor:</strong> {{ $m->tipe_model }}</p>
                                <p><strong>Plat Nomor:</strong> {{ $m->plat_nomor }}</p>
                                <p><strong>Harga Beli:</strong> Rp {{ number_format($m->harga_beli, 0, ',', '.') }}</p>
                                <p><strong>Total Restorasi:</strong> Rp {{ number_format($totalRestorasi, 0, ',', '.') }}</p>
                                <p><strong>Total Modal:</strong> <span class="font-semibold text-blue-600">
                                    Rp {{ number_format($m->harga_beli + $totalRestorasi, 0, ',', '.') }}</span></p>
                                <p><strong>Harga Jual:</strong> {{ $m->harga_jual ? 'Rp ' . number_format($m->harga_jual, 0, ',', '.') : '-' }}</p>
                                <p><strong>Kondisi:</strong> {{ $m->kondisi ?? '-' }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($m->status) }}</p>
                                <hr>
                                <p><strong>Nama Penjual:</strong> {{ $m->nama_penjual }}</p>
                                <p><strong>No. Telp Penjual:</strong> {{ $m->no_telp_penjual }}</p>
                                <p><strong>Alamat Penjual:</strong> {{ $m->alamat_penjual }}</p>
                            </div>

                            <div class="mt-4 flex justify-end">
                                <button onclick="closeModal({{ $m->id }})"
                                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">Kembali</button>
                            </div>

                            <button onclick="closeModal({{ $m->id }})"
                                class="absolute top-2 right-2 text-gray-500 hover:text-black">✕</button>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="9" class="text-center p-4 text-gray-500">Data tidak ditemukan</td>
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
        function confirmDelete(button) {
            Swal.fire({
                title: 'Yakin hapus data ini?',
                text: 'Data yang dihapus tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }

        function openModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(`modal-${id}`);
            modal.querySelector('div').classList.remove('scale-100');
            setTimeout(() => modal.classList.add('hidden'), 200);
        }
    </script>
@endsection
