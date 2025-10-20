@extends('layouts.app')

@section('content')

<div class="container mx-auto px-6 py-4">
    <h1 class="text-3xl font-bold mb-4 text-gray-800">
        Edit Transaksi Penjualan
    </h1>
    <p class="text-gray-600 mb-6">
        Kode: <span class="font-medium text-gray-800">{{ $penjualan->kode_penjualan }}</span> | 
        Tanggal: {{ $penjualan->tanggal_penjualan->format('d M Y H:i') }}
    </p>

    <form id="editForm" method="POST" action="{{ route('bengkel.penjualanbarang.update', $penjualan->id) }}" class="bg-white p-6 rounded-lg shadow-xl">
        @csrf
        @method('PUT')

        {{-- Input Harga Jasa --}}
        <div class="mb-6 border-b pb-4">
            <label for="harga_jasa" class="block font-semibold text-lg mb-2 text-gray-700">
                Harga Jasa
            </label>
            <input type="text" name="harga_jasa" id="harga_jasa" 
                   value="{{ number_format($penjualan->harga_jasa, 0, ',', '.') }}"
                   class="border border-gray-300 rounded-lg p-3 w-full sm:w-64 text-right text-lg font-medium focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition duration-150">
        </div>

        {{-- Tabel Detail Barang --}}
        <h2 class="text-xl font-semibold mb-3 text-gray-700">Daftar Barang</h2>
        <div class="overflow-x-auto shadow-md rounded-lg mb-6">
            <table class="w-full border-collapse" id="barangTable">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="p-3 text-left border w-4/12">Nama Barang</th>
                        <th class="p-3 text-center border w-1/12">Qty</th>
                        <th class="p-3 text-center border w-1/12">Stok</th>
                        <th class="p-3 text-center border w-2/12">Harga Jual</th>
                        <th class="p-3 text-center border w-3/12">Subtotal</th>
                        <th class="p-3 text-center border w-1/12">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->details as $detail)
                    <tr class="barang-row border-b hover:bg-gray-50 transition duration-100">
                        <td class="p-2 border relative">
                            <input type="hidden" name="barang_id[]" class="barang-id" value="{{ $detail->barang_id }}">
                            <input type="text" value="{{ $detail->barang->nama_barang }}" 
                                   class="search-barang border border-gray-300 rounded w-full p-2 bg-gray-50 text-sm" placeholder="Cari barang...">
                            <div class="dropdown bg-white border border-gray-300 rounded shadow-lg hidden absolute z-10 w-full mt-1"></div>
                        </td>
                        <td class="p-2 border text-center">
                            <input type="number" name="kuantiti[]" class="qty border border-gray-300 rounded w-full p-2 text-center text-sm" 
                                   value="{{ $detail->kuantiti }}" min="1">
                        </td>
                        <td class="p-2 border text-center">
                            <input type="text" class="stok border border-gray-300 rounded w-full p-2 text-center bg-gray-100 text-sm" 
                                   readonly value="{{ $detail->barang->stok ?? 0 }}">
                        </td>
                        <td class="p-2 border text-center">
                            <input type="text" class="harga border border-gray-300 rounded w-full p-2 text-center bg-gray-100 text-sm" 
                                   readonly value="{{ number_format($detail->harga_jual, 0, ',', '.') }}">
                        </td>
                        <td class="p-2 border text-center">
                            <input type="text" class="subtotal border border-gray-300 rounded w-full p-2 text-center bg-gray-100 font-semibold text-sm" 
                                   readonly value="{{ number_format($detail->subtotal, 0, ',', '.') }}">
                        </td>
                        <td class="p-2 border text-center">
                            <button type="button" class="hapus bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition text-sm">X</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" id="addRow" class="mt-4 bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                + Tambah Barang
            </button>
        </div>

        {{-- Footer Total --}}
        <div class="flex justify-end mt-6">
            <div class="text-right p-3 border-t-2 border-blue-500 border-dashed w-full sm:w-auto">
                <p class="font-bold text-xl text-gray-700">
                    Total Penjualan: <span id="grandTotal" class="text-green-600 ml-2 font-extrabold">
                        Rp{{ number_format($penjualan->total_penjualan, 0, ',', '.') }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Tombol Simpan/Batal --}}
        <div class="mt-8 pt-4 border-t border-gray-300">
            <button type="submit" class="bg-yellow-500 text-white px-6 py-2 rounded-lg hover:bg-yellow-600 transition duration-150 font-semibold shadow-md">
                Simpan Perubahan
            </button>
            <a href="{{ route('bengkel.penjualanbarang.index') }}" 
               class="ml-3 text-gray-600 hover:text-gray-800 font-medium transition duration-150">
                Batal
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.querySelector('#barangTable tbody');
    const hargaJasaEl = document.querySelector('#harga_jasa');
    const grandTotalEl = document.querySelector('#grandTotal');

    // --- UTILITY FUNCTIONS ---
    function formatRupiah(num) {
        // Menggunakan Intl.NumberFormat untuk format yang lebih baik (misal: 1.000.000)
        return new Intl.NumberFormat('id-ID').format(num);
    }
    function unformatRupiah(str) {
        // Menghapus titik dan mengganti koma (jika ada)
        return parseFloat(String(str).replace(/\./g, '').replace(/,/g, '')) || 0;
    }

    // --- CORE LOGIC ---
    function hitungSubtotal(row) {
        const harga = unformatRupiah(row.querySelector('.harga').value || '0');
        // Pastikan kuantitas minimal 1
        let qty = Math.max(1, parseFloat(row.querySelector('.qty').value || 0));
        row.querySelector('.qty').value = qty; 

        const subtotal = harga * qty;
        row.querySelector('.subtotal').value = formatRupiah(subtotal);
        hitungTotal();
    }

    function hitungTotal() {
        let totalBarang = 0;
        tbody.querySelectorAll('.subtotal').forEach(el => {
            totalBarang += unformatRupiah(el.value || '0');
        });
        
        const jasa = unformatRupiah(hargaJasaEl.value || '0');
        const grand = totalBarang + jasa;
        
        grandTotalEl.textContent = `Rp${formatRupiah(grand)}`;
    }

    // --- EVENT INITIALIZATION FOR EACH ROW ---
    function initRowEvents(tr) {
        const searchInput = tr.querySelector('.search-barang');
        const dropdown = tr.querySelector('.dropdown');
        const qtyInput = tr.querySelector('.qty');
        const hapusButton = tr.querySelector('.hapus');
        
        // Perhitungan subtotal saat QTY berubah
        qtyInput.addEventListener('input', () => hitungSubtotal(tr));
        
        // Hapus Baris
        hapusButton.addEventListener('click', () => {
            Swal.fire({
                title: 'Hapus Item?',
                text: "Anda yakin ingin menghapus barang ini dari daftar?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    tr.remove();
                    hitungTotal();
                }
            })
        });

        // Pencarian Barang
        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            
            const query = searchInput.value;
            if (query.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }
            
            searchTimeout = setTimeout(async () => {
                try {
                    const res = await fetch(`{{ route('bengkel.penjualanbarang.search') }}?q=${query}`);
                    if (!res.ok) throw new Error('Gagal mencari barang');
                    
                    const data = await res.json();
                    dropdown.innerHTML = '';

                    if (data.length === 0) {
                        dropdown.classList.add('hidden');
                        return;
                    }

                    data.forEach(item => {
                        const div = document.createElement('div');
                        div.innerHTML = `${item.nama_barang} <span class="text-xs text-gray-500">(Stok: ${item.stok})</span>`;
                        div.classList.add('p-2', 'hover:bg-blue-100', 'cursor-pointer', 'text-sm');
                        
                        // Event saat barang dipilih
                        div.addEventListener('mousedown', (e) => {
                            e.preventDefault(); // cegah input blur duluan
                            tr.querySelector('.barang-id').value = item.id;
                            tr.querySelector('.stok').value = item.stok;
                            tr.querySelector('.harga').value = formatRupiah(item.harga_jual);
                            tr.querySelector('.subtotal').value = formatRupiah(item.harga_jual * (tr.querySelector('.qty').value || 0));
                            searchInput.value = item.nama_barang;
                            dropdown.classList.add('hidden');
                            hitungTotal();
                        });
                        dropdown.appendChild(div);
                    });
                    dropdown.classList.remove('hidden');
                } catch (e) {
                    console.error(e);
                    dropdown.classList.add('hidden');
                }
            }, 300); // Debounce 300ms
        });
        
        // Sembunyikan dropdown saat klik di luar
        searchInput.addEventListener('blur', () => {
            setTimeout(() => dropdown.classList.add('hidden'), 200);
        });
        searchInput.addEventListener('focus', () => {
            if (dropdown.children.length > 0) dropdown.classList.remove('hidden');
        });
    }

    // --- ADD NEW ROW ---
    function addRow() {
        const tr = document.createElement('tr');
        tr.classList.add('barang-row', 'border-b', 'hover:bg-gray-50', 'transition', 'duration-100');
        tr.innerHTML = `
            <td class="p-2 border relative">
                <input type="hidden" name="barang_id[]" class="barang-id">
                <input type="text" class="search-barang border border-gray-300 rounded w-full p-2 text-sm" placeholder="Cari barang...">
                <div class="dropdown bg-white border border-gray-300 rounded shadow-lg hidden absolute z-10 w-full mt-1"></div>
            </td>
            <td class="p-2 border text-center">
                <input type="number" name="kuantiti[]" class="qty border border-gray-300 rounded w-full p-2 text-center text-sm" value="1" min="1">
            </td>
            <td class="p-2 border text-center">
                <input type="text" class="stok border border-gray-300 rounded w-full p-2 text-center bg-gray-100 text-sm" readonly>
            </td>
            <td class="p-2 border text-center">
                <input type="text" class="harga border border-gray-300 rounded w-full p-2 text-center bg-gray-100 text-sm" readonly>
            </td>
            <td class="p-2 border text-center">
                <input type="text" class="subtotal border border-gray-300 rounded w-full p-2 text-center bg-gray-100 font-semibold text-sm" readonly>
            </td>
            <td class="p-2 border text-center">
                <button type="button" class="hapus bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition text-sm">X</button>
            </td>
        `;
        tbody.appendChild(tr);
        initRowEvents(tr);
        hitungTotal();
    }

    // --- INITIAL SETUP ---

    // 1. Inisialisasi event untuk BARIS YANG SUDAH ADA
    tbody.querySelectorAll('.barang-row').forEach(initRowEvents);

    // 2. Listener untuk tombol Tambah Baris
    document.querySelector('#addRow').addEventListener('click', addRow);

    // 3. Listener untuk Harga Jasa (auto format dan hitung total)
    hargaJasaEl.addEventListener('input', e => {
        let val = unformatRupiah(e.target.value);
        e.target.value = val ? formatRupiah(val) : '';
        hitungTotal();
    });

    // 4. Submit Form dengan SweetAlert
    document.getElementById('editForm').addEventListener('submit', (e) => {
        e.preventDefault();
        
        if (document.querySelectorAll('.barang-row').length === 0) {
            Swal.fire('Gagal!', 'Transaksi harus memiliki minimal 1 barang.', 'error');
            return;
        }

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: 'Anda akan menyimpan perubahan untuk transaksi ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then(res => {
            if (res.isConfirmed) {
                e.target.submit();
            }
        });
    });

    // 5. Panggil hitungTotal() di awal untuk memastikan total awal benar
    hitungTotal();
});
</script>

@endsection