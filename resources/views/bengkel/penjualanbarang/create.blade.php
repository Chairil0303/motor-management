@extends('layouts.app')

@section('content')

<div class="p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">🛒 Transaksi Penjualan Barang & Jasa Bengkel</h1>

    {{-- Notifikasi --}}
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 p-4 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('bengkel.penjualanbarang.store') }}" method="POST" id="form-penjualan">
        @csrf

        {{-- BAGIAN DETAIL BARANG/LAYANAN --}}
        <div class="mb-6 border rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200" id="table-barang">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/3">Nama Barang</th>
                        <th class="p-3 border text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Stok</th>
                        <th class="p-3 border text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Qty</th>
                        <th class="p-3 border text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Harga Jual</th>
                        <th class="p-3 border text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Subtotal</th>
                        <th class="p-3 border text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-1/12">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    {{-- Baris transaksi akan ditambahkan di sini oleh JS --}}
                </tbody>
            </table>
            <div class="p-4 bg-white">
                <button type="button" id="add-row" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition duration-150 ease-in-out shadow-md">
                    + Tambah Barang/Jasa
                </button>
            </div>
        </div>

        {{-- BAGIAN TOTAL DAN AKSI --}}
        <div class="flex justify-end items-end gap-10 mt-6">
            {{-- Total Pembayaran --}}
            <div class="space-y-4 w-96">
                <div class="flex justify-between items-center pb-2 border-b">
                    <label class="text-lg font-semibold text-gray-700">Total Barang:</label>
                    <span class="text-lg font-bold text-gray-800">Rp <span id="total">0</span></span>
                </div>

                <div class="flex justify-between items-center gap-4">
                    <label for="harga_jasa" class="text-lg font-semibold text-gray-700 whitespace-nowrap">Harga Jasa:</label>
                    <input type="text" name="harga_jasa" id="harga_jasa" 
                           class="border border-gray-300 rounded-lg p-2 w-full text-right font-medium focus:ring-blue-500 focus:border-blue-500" 
                           placeholder="0" value="0">
                </div>

                <div class="flex justify-between items-center pt-2 border-t-2 border-gray-700">
                    <label class="text-xl font-bold text-gray-800">Grand Total:</label>
                    <span class="text-2xl font-extrabold text-green-600">Rp <span id="grand-total">0</span></span>
                </div>
            </div>
        </div>
        <div class="flex justify-end items-end gap-10 mt-6">
            {{-- Tombol Aksi --}}
            <div class="flex flex-col items-center">
                    <button type="submit" id="btn-simpan" class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition duration-150 ease-in-out transform hover:scale-105">
                        ✅ Simpan Transaksi
                    </button>
                    <a href="{{ route('bengkel.penjualanbarang.index') }}" class="mt-3 text-sm text-gray-600 hover:text-red-500 transition duration-150">Batal Transaksi</a>
            </div>
        </div>
    </form>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- Konstanta Elemen DOM ---
    const tbody = document.querySelector('#table-barang tbody');
    const totalEl = document.querySelector('#total');
    const hargaJasaEl = document.querySelector('#harga_jasa');
    const grandTotalEl = document.querySelector('#grand-total');
    const btnSimpan = document.getElementById('btn-simpan');

    // --- Utility Functions ---
    /** Format angka menjadi format Rupiah (tanpa symbol Rp). */
    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    /** Hapus format Rupiah dari string (misal: "1.000.000" -> 1000000). */
    function unformatRupiah(str) {
        // Hapus semua titik/koma dan konversi ke float
        return parseFloat(String(str).replace(/\./g, '')) || 0;
    }

    // --- Calculation Logic ---
    function hitungTotal() {
        let totalBarang = 0;
        tbody.querySelectorAll('.subtotal-input').forEach(el => {
            totalBarang += unformatRupiah(el.value || '0');
        });

        const hargaJasa = unformatRupiah(hargaJasaEl.value || '0');
        const grandTotal = totalBarang + hargaJasa;

        totalEl.textContent = formatRupiah(totalBarang);
        grandTotalEl.textContent = formatRupiah(grandTotal);
        
        // Disable tombol simpan jika grand total 0
        btnSimpan.disabled = grandTotal === 0;
        btnSimpan.classList.toggle('opacity-50', grandTotal === 0);
    }

    // --- Row Management and Event Handlers ---
    function setupRowHandlers(tr) {
        const searchInput = tr.querySelector('.search-barang');
        const dropdown = tr.querySelector('.dropdown');
        const qtyInput = tr.querySelector('.qty-input');
        const stokInput = tr.querySelector('.stok-input');

        // 1. Pencarian Barang
        searchInput.addEventListener('input', async () => {
            const query = searchInput.value;
            // Clear barang ID saat mulai mengetik
            tr.querySelector('.barang-id').value = ''; 
            
            if (query.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }

            // Route untuk pencarian, pastikan URL-nya benar
            const searchRoute = `{{ route('bengkel.penjualanbarang.search') }}?q=${query}`;
            const res = await fetch(searchRoute);
            const data = await res.json();
            
            dropdown.innerHTML = '';
            dropdown.classList.remove('hidden');

            if (data.length === 0) {
                 dropdown.innerHTML = '<div class="p-2 text-gray-500">Barang tidak ditemukan.</div>';
                 return;
            }

            data.forEach(item => {
                const div = document.createElement('div');
                div.textContent = `${item.nama_barang} (stok: ${item.stok})`;
                div.classList.add('p-2', 'hover:bg-blue-100', 'cursor-pointer');
                
                div.addEventListener('click', () => {
                    // Set data barang yang dipilih
                    tr.querySelector('.barang-id').value = item.id;
                    stokInput.value = item.stok;
                    tr.querySelector('.harga-input').value = formatRupiah(item.harga_jual);
                    searchInput.value = item.nama_barang;
                    dropdown.classList.add('hidden');
                    
                    // Reset Qty dan hitung ulang
                    qtyInput.value = 1;
                    qtyInput.dispatchEvent(new Event('input')); // Trigger input event
                    
                    qtyInput.focus();
                });
                dropdown.appendChild(div);
            });
        });

        // 2. Perhitungan Qty dan Subtotal
        const calculateSubtotal = () => {
            const harga = unformatRupiah(tr.querySelector('.harga-input').value || '0');
            let qty = parseFloat(qtyInput.value || 0);
            const stok = parseFloat(stokInput.value || Infinity);
            
            // Validasi: Qty tidak boleh lebih dari Stok
            if (qty > stok) {
                alert(`Kuantitas melebihi stok yang tersedia (${stok}).`);
                qty = stok; // Set Qty ke stok maksimal
                qtyInput.value = qty;
            }
            
            // Validasi: Qty tidak boleh kurang dari 0
            if (qty < 0) {
                qty = 0;
                qtyInput.value = 0;
            }

            tr.querySelector('.subtotal-input').value = formatRupiah(harga * qty);
            hitungTotal();
        };

        qtyInput.addEventListener('input', calculateSubtotal);
        qtyInput.addEventListener('change', calculateSubtotal); // Untuk memastikan saat unfokus

        // 3. Hapus Baris
        tr.querySelector('.hapus').addEventListener('click', () => {
            tr.remove();
            hitungTotal();
        });
    }

    function addRow() {
        const tr = document.createElement('tr');
        tr.classList.add('hover:bg-gray-50');
        tr.innerHTML = `
            <td class="border p-2 relative">
                <input type="hidden" name="barang_id[]" class="barang-id">
                <input type="text" class="search-barang border border-gray-300 rounded w-full p-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik nama barang...">
                <div class="dropdown bg-white border border-gray-300 rounded shadow-lg hidden absolute z-20 w-full mt-1"></div>
            </td>
            <td class="border p-2 text-center">
                <input type="text" class="stok-input border-none bg-transparent w-full p-1 text-center font-medium text-gray-600" readonly value="0">
            </td>
            <td class="border p-2 text-center">
                <input type="number" name="kuantiti[]" class="qty-input border border-gray-300 rounded w-full p-2 text-center text-sm focus:ring-blue-500 focus:border-blue-500" min="0" value="0">
            </td>
            <td class="border p-2 text-right">
                <input type="text" class="harga-input border-none bg-transparent w-full p-1 text-right font-semibold text-gray-800" readonly value="0">
            </td>
            <td class="border p-2 text-right">
                <input type="text" name="subtotal[]" class="subtotal-input border-none bg-transparent w-full p-1 text-right font-bold text-gray-800" readonly value="0">
            </td>
            <td class="border p-2 text-center">
                <button type="button" class="hapus bg-red-500 hover:bg-red-600 text-white font-bold p-2 rounded-full leading-none text-xs transition duration-150 ease-in-out">X</button>
            </td>
        `;
        tbody.appendChild(tr);

        setupRowHandlers(tr);
        
        // Fokus langsung ke input cari barang setelah baris ditambahkan
        setTimeout(() => tr.querySelector('.search-barang').focus(), 100);
    }

    // --- Main Event Listeners ---
    
    // Auto-format dan hitung ulang harga jasa
    hargaJasaEl.addEventListener('input', (e) => {
        let val = e.target.value.replace(/\D/g, ''); // Hapus non-digit
        // Jika ada nilai, format, jika tidak, kosongkan
        e.target.value = val ? formatRupiah(val) : '';
        hitungTotal();
    });

    document.getElementById('add-row').addEventListener('click', addRow);

    // Inisialisasi: Tambahkan baris pertama dan hitung total awal
    addRow(); 
    hitungTotal(); 

    // Opsi: Tambahkan konfirmasi sebelum submit (alert bawaan dari kode Anda)
    // const form = document.getElementById('form-penjualan');
    // form.addEventListener('submit', (e) => {
    //     // Anda mungkin ingin menambahkan validasi di sini sebelum submit
    //     // misalnya memastikan minimal ada 1 barang atau jasa
    //     if (tbody.children.length === 0 && unformatRupiah(hargaJasaEl.value) === 0) {
    //         e.preventDefault();
    //         alert('Mohon masukkan minimal 1 barang atau harga jasa.');
    //     }
    //     alert('✅ Transaksi sedang diproses...');
    // });
});
</script>

@endsection