@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Riwayat Belanja Barang</h1>
            <a href="{{ route('bengkel.belanja.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                + Tambah Belanja Barang
            </a>
        </div>

        {{-- Filter Bulan --}}
        <form id="filter-bulan-form" method="GET" action="{{ route('bengkel.belanja.index') }}"
            class="mb-4 flex items-center gap-3">
            <label class="font-semibold">Filter Bulan:</label>
            <input id="filter-bulan-input" type="month" name="bulan" value="{{ request('bulan') }}"
                class="border p-2 rounded focus:ring focus:ring-blue-300">
            @if(request('bulan'))
                <a href="{{ route('bengkel.belanja.index') }}" class="text-sm text-blue-600 hover:underline">Reset</a>
            @endif
        </form>

        {{-- Total Belanja --}}
        @if(request('bulan'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4 font-semibold">
                Total Belanja Bulan Ini: Rp {{ number_format($totalBelanja, 0, ',', '.') }}
            </div>
        @endif

        {{-- Alert sukses --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif

        {{-- Tabel --}}
        <table class="w-full border text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border w-12 text-center">#</th>
                    <th class="p-2 border">Kode Belanja</th>
                    <th class="p-2 border">Nama Barang</th>
                    <th class="p-2 border text-center">Tanggal Belanja</th>
                    <th class="p-2 border text-center">Kuantiti</th>
                    <th class="p-2 border text-right">Total Belanja (Rp)</th>
                    <th class="p-2 border text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($belanjas as $b)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border text-center">
                            {{ ($belanjas->currentPage() - 1) * $belanjas->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-2 border">{{ $b->kode_belanja }}</td>
                        <td class="p-2 border">{{ $b->barang->nama_barang ?? '-' }}</td>
                        <td class="p-2 border text-center">{{ \Carbon\Carbon::parse($b->tanggal_belanja)->format('d M Y') }}</td>
                        <td class="p-2 border text-center">{{ $b->kuantiti }}</td>
                        <td class="p-2 border text-right">Rp {{ number_format($b->total_belanja, 0, ',', '.') }}</td>
                        <td class="p-2 border text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('bengkel.belanja.edit', $b->id) }}"
                                    class="bg-yellow-400 text-blue px-2 py-1 rounded hover:bg-yellow-500 text-xs">
                                    Edit
                                </a>
                                <form class="inline delete-form" action="{{ route('bengkel.belanja.destroy', $b->id) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-xs btn-delete">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-gray-500">
                            @if(request('bulan'))
                                Tidak ada data belanja untuk bulan
                                {{ \Carbon\Carbon::parse(request('bulan') . '-01')->translatedFormat('F Y') }}.
                            @else
                                Belum ada data belanja.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $belanjas->links('pagination::tailwind') }}
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bulanInput = document.getElementById('filter-bulan-input');
            const form = document.getElementById('filter-bulan-form');

            if (bulanInput) {
                bulanInput.addEventListener('change', function () {
                    form.submit();
                });
            }

            // konfirmasi hapus
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Hapus Riwayat Belanja?',
                        text: 'Data ini akan dihapus permanen!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // notif sukses
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 1500
                });
            @endif
        });
    </script>
@endsection
