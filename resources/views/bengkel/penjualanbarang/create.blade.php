@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">🛒 Transaksi Penjualan Barang</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('bengkel.penjualanbarang.store') }}" method="POST" id="form-penjualan">
        @csrf

        <div class="mb-4">
            <table class="w-full border border-gray-300" id="table-barang">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 border">Nama Barang</th>
                        <th class="p-2 border">Qty</th>
                        <th class="p-2 border">Stok</th>
                        <th class="p-2 border">Harga Jual</th>
                        <th class="p-2 border">Subtotal</th>
                        <th class="p-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            <button type="button" id="add-row" class="mt-2 bg-blue-600 text-white px-3 py-1 rounded">+ Tambah Barang</button>
        </div>

        <div class="flex justify-end text-lg font-bold mb-2">
            Total Barang: Rp <span id="total">0</span>
        </div>

        <div class="flex justify-end items-center mb-4 gap-2">
            <label for="harga_jasa" class="font-semibold">Harga Jasa:</label>
            <input type="text" name="harga_jasa" id="harga_jasa" class="border rounded p-1 w-40 text-right" placeholder="0">
        </div>

        <div class="flex justify-end text-xl font-bold mb-6">
            Grand Total: Rp <span id="grand-total">0</span>
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan Transaksi</button>
        <a href="{{ route('bengkel.penjualanbarang.index') }}" class="ml-2 text-gray-600">Batal</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const tbody = document.querySelector('#table-barang tbody');
    const totalEl = document.querySelector('#total');
    const hargaJasaEl = document.querySelector('#harga_jasa');
    const grandTotalEl = document.querySelector('#grand-total');

    // Format angka ke format Rupiah pakai titik
    function formatRupiah(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    // Hapus titik biar bisa dihitung lagi
    function unformatRupiah(str) {
        return parseFloat(str.replace(/\./g, '').replace(/,/g, '')) || 0;
    }

    // Hitung total semua subtotal + harga jasa
    function hitungTotal() {
        let totalBarang = 0;
        tbody.querySelectorAll('.subtotal').forEach(el => {
            totalBarang += unformatRupiah(el.value || '0');
        });

        const hargaJasa = unformatRupiah(hargaJasaEl.value || '0');
        const grandTotal = totalBarang + hargaJasa;

        totalEl.textContent = formatRupiah(totalBarang);
        grandTotalEl.textContent = formatRupiah(grandTotal);
    }

    // Tambah baris barang baru
    function addRow() {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="border p-1 relative">
                <input type="hidden" name="barang_id[]" class="barang-id">
                <input type="text" class="search-barang border rounded w-full p-1" placeholder="Cari barang...">
                <div class="dropdown bg-white border hidden absolute z-10"></div>
            </td>
            <td class="border p-1 text-center">
                <input type="number" name="kuantiti[]" class="qty border rounded w-full p-1 text-center" min="0" value="0">
            </td>
            <td class="border p-1 text-center">
                <input type="text" class="stok border rounded w-full p-1 text-center" readonly>
            </td>
            <td class="border p-1 text-center">
                <input type="text" class="harga border rounded w-full p-1 text-center" readonly>
            </td>
            <td class="border p-1 text-center">
                <input type="text" class="subtotal border rounded w-full p-1 text-center" readonly>
            </td>
            <td class="border p-1 text-center">
                <button type="button" class="hapus bg-red-500 text-white px-2 py-1 rounded">X</button>
            </td>
        `;
        tbody.appendChild(tr);

        const searchInput = tr.querySelector('.search-barang');
        const dropdown = tr.querySelector('.dropdown');

        // autocomplete barang
        searchInput.addEventListener('input', async () => {
            const query = searchInput.value;
            if (query.length < 2) {
                dropdown.classList.add('hidden');
                return;
            }

            const res = await fetch(`{{ route('bengkel.penjualanbarang.search') }}?q=${query}`);
            const data = await res.json();
            dropdown.innerHTML = '';

            data.forEach(item => {
                const div = document.createElement('div');
                div.textContent = `${item.nama_barang} (stok: ${item.stok})`;
                div.classList.add('p-1', 'hover:bg-gray-100', 'cursor-pointer');
                div.addEventListener('click', () => {
                    tr.querySelector('.barang-id').value = item.id;
                    tr.querySelector('.stok').value = item.stok;
                    tr.querySelector('.harga').value = formatRupiah(item.harga_jual);
                    tr.querySelector('.subtotal').value = formatRupiah(item.harga_jual * tr.querySelector('.qty').value);
                    searchInput.value = item.nama_barang;
                    dropdown.classList.add('hidden');
                    hitungTotal();
                });
                dropdown.appendChild(div);
            });
            dropdown.classList.remove('hidden');
        });

        // ubah qty => update subtotal
        tr.querySelector('.qty').addEventListener('input', () => {
            const harga = unformatRupiah(tr.querySelector('.harga').value || '0');
            const qty = parseFloat(tr.querySelector('.qty').value || 0);
            tr.querySelector('.subtotal').value = formatRupiah(harga * qty);
            hitungTotal();
        });

        // hapus baris
        tr.querySelector('.hapus').addEventListener('click', () => {
            tr.remove();
            hitungTotal();
        });
    }

    // Input harga jasa realtime update total
    hargaJasaEl.addEventListener('input', e => {
        let val = e.target.value.replace(/\D/g, '');
        e.target.value = val ? formatRupiah(val) : '';
        hitungTotal();
    });

    document.getElementById('add-row').addEventListener('click', addRow);
});
</script>
@endsection
