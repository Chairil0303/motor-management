<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Harian Ken Motor</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1, h2, h3 { margin-bottom: 0; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background-color: #eee; }
        .text-right { text-align: right; }
        .mb-20 { margin-bottom: 20px; }
        .summary { margin-top: 30px; width: 100%; border-collapse: collapse; }
        .summary td { padding: 6px; border: none; text-align: right; } /* semua rata kanan */
    </style>
</head>
<body>
    <h1>Ken Motor</h1>
    <h2>Laporan Penjualan Harian</h2>
    <p style="text-align:center;">
        <strong>Tanggal:</strong> {{ $tanggal }}
    </p>

    @foreach($penjualanBarangs as $pj)
        <div class="mb-20">
            <h3>Transaksi: {{ $pj->kode_penjualan }}</h3>
            <p>
                <strong>Total Penjualan:</strong> Rp{{ number_format($pj->total_penjualan,0,',','.') }} |
                <strong>Total Margin:</strong> Rp{{ number_format($pj->total_margin,0,',','.') }} |
                <strong>Harga Jasa:</strong> Rp{{ number_format($pj->harga_jasa,0,',','.') }}
            </p>

            <table>
                <thead>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th class="text-right">Kuantiti</th>
                        <th class="text-right">Harga Jual</th>
                        <th class="text-right">Harga Beli</th>
                        <th class="text-right">Subtotal</th>
                        <th class="text-right">Margin</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pj->details as $detail)
                        <tr>
                            <td>{{ $detail->barang->kode_barang }}</td>
                            <td>{{ $detail->barang->nama_barang }}</td>
                            <td class="text-right">{{ $detail->kuantiti }}</td>
                            <td class="text-right">Rp{{ number_format($detail->harga_jual,0,',','.') }}</td>
                            <td class="text-right">Rp{{ number_format($detail->harga_beli,0,',','.') }}</td>
                            <td class="text-right">Rp{{ number_format($detail->subtotal,0,',','.') }}</td>
                            <td class="text-right">Rp{{ number_format($detail->margin,0,',','.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    {{-- ✅ Summary Harian --}}
    <table class="summary">
        <tr>
            <td><strong>Total Modal:</strong> Rp{{ number_format($total['modal'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Penjualan:</strong> Rp{{ number_format($total['penjualan'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Margin:</strong> Rp{{ number_format($total['margin'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Jasa:</strong> Rp{{ number_format($total['jasa'], 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
