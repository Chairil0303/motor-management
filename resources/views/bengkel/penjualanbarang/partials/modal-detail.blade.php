<div id="modal-{{ $penjualan->id }}" 
     class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
        <h2 class="text-xl font-bold mb-4">Detail Transaksi: {{ $penjualan->kode_penjualan }}</h2>

        <table class="w-full border mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Nama Barang</th>
                    <th class="p-2 border text-right">Qty</th>
                    <th class="p-2 border text-right">Harga Beli</th>
                    <th class="p-2 border text-right">Harga Jual</th>
                    <th class="p-2 border text-right">Margin/Item</th>
                    <th class="p-2 border text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->details as $detail)
                    <tr>
                        <td class="p-2 border">{{ $detail->barang->nama_barang }}</td>
                        <td class="p-2 border text-right">{{ $detail->kuantiti }}</td>
                        <td class="p-2 border text-right">Rp{{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                        <td class="p-2 border text-right">Rp{{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                        <td class="p-2 border text-right">
                            Rp{{ number_format(($detail->harga_jual - $detail->harga_beli), 0, ',', '.') }}
                        </td>
                        <td class="p-2 border text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right space-y-1">
            <p>Total Margin: <strong>Rp{{ number_format($penjualan->total_margin, 0, ',', '.') }}</strong></p>
            <p>Biaya Jasa: <strong>Rp{{ number_format($penjualan->harga_jasa, 0, ',', '.') }}</strong></p>
            <p>Total Transaksi: <strong class="text-green-600">Rp{{ number_format($penjualan->total_penjualan, 0, ',', '.') }}</strong></p>
        </div>

        <button data-modal-close 
                class="absolute top-2 right-3 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
    </div>
</div>
