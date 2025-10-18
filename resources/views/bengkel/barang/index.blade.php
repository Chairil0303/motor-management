@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        {{-- Header + Tombol Tambah --}}
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Manajemen Barang Bengkel</h1>
            <a href="{{ route('bengkel.barang.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                + Tambah Barang
            </a>
        </div>

        {{-- Alert sukses --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-3">{{ session('success') }}</div>
        @endif

        {{-- Filter Kategori --}}
        <form method="GET" action="{{ route('bengkel.barang.index') }}" id="filterForm"
            class="mb-4 flex items-center gap-2">
            <input type="text" name="search" placeholder="Cari nama barang..." value="{{ request('search') }}"
                class="border p-2 rounded w-64 focus:ring focus:ring-blue-300">

            <select name="kategori_id" onchange="document.getElementById('filterForm').submit()"
                class="border py-2 pl-3 pr-8 rounded appearance-none bg-white pr-8 relative">
                <option value="">Semua Kategori</option>
                @foreach($kategoris as $k)
                    <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kategori }}
                    </option>
                @endforeach
            </select>

            @if(request('search') || request('kategori_id'))
                <a href="{{ route('bengkel.barang.index') }}" class="text-sm text-blue-600 hover:underline ml-2">Reset</a>
            @endif
        </form>

        {{-- Tabel Barang --}}
        <table class="w-full border text-left text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-1 border w-12 text-center">#</th>
                    <th class="p-1 border">Nama Barang</th>
                    <th class="p-1 border w-16 text-center">Stok</th>
                    <th class="p-1 border w-32 text-center">Kategori</th>
                    <th class="p-1 border w-32 text-right">Harga Beli</th>
                    <th class="p-1 border w-32 text-right">Harga Jual</th>
                    <th class="p-1 border text-center w-40">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $b)
                    <tr class="hover:bg-gray-50">
                        <td class="p-1 border text-center">
                            {{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}
                        </td>
                        <td class="p-1 border">{{ $b->nama_barang }}</td>
                        <td class="p-1 border text-center">{{ $b->stok }}</td>
                        <td class="p-1 border text-center">{{ $b->kategori->nama_kategori ?? '-' }}</td>
                        <td class="p-1 border text-right">Rp {{ number_format($b->harga_beli, 0, ',', '.') }}</td>
                        <td class="p-1 border text-right">Rp {{ number_format($b->harga_jual, 0, ',', '.') }}</td>
                        <td class="p-1 border text-center space-x-1">
                            <a href="{{ route('bengkel.barang.edit', $b->id) }}"
                                class=" text-yellow px-2 py-1 text-xs rounded hover:bg-yellow-600 transition">
                                Edit
                            </a>
                            <button type="button"
                                class="btn-hapus bg-red-500 text-red px-2 py-1 text-xs rounded hover:bg-red-600 transition"
                                data-id="{{ $b->id }}" data-nama="{{ $b->nama_barang }}">
                                Hapus
                            </button>
                            <form id="form-hapus-{{ $b->id }}" action="{{ route('bengkel.barang.destroy', $b->id) }}"
                                method="POST" class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center p-4 text-gray-500">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $barang->links('pagination::tailwind') }}
        </div>
    </div>

    {{-- Custom CSS buat ilangin icon dropdown --}}
    <style>
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
    </style>

    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-hapus').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');

                Swal.fire({
                    title: 'Yakin hapus barang?',
                    text: `Barang "${nama}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`form-hapus-${id}`).submit();
                    }
                });
            });
        });
    </script>
@endsection