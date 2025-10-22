<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Bulanan - Ken Motor</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        h1, h2 { text-align: center; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; vertical-align: top; }
        th { background: #f2f2f2; }
        .text-right { text-align: right; }
        .summary { margin-top: 20px; width: 100%; border-collapse: collapse; }
        .summary td { padding: 6px; border: none; text-align: right; } /* semua rata kanan */
    </style>
</head>
<body>
    <h1>Ken Motor</h1>
    <h2>Laporan Penjualan Bulanan</h2>

    @if($filter)
        <p><strong>Bulan:</strong> {{ \Carbon\Carbon::parse($filter['bulan'])->translatedFormat('F Y') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th> <!-- ✅ Tambah kolom nomor -->
                <th>ID Transaksi</th>
                <th>Item Barang (Qty)</th>
                <th class="text-right">Harga Beli</th>
                <th class="text-right">Harga Jual</th>
                <th class="text-right">Margin</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Harga Jasa</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grouped = collect($details)->groupBy('kode_penjualan');
                $no = 1;
            @endphp

            @foreach($grouped as $kode => $rows)
                @php $rowspan = count($rows); @endphp
                @foreach($rows as $i => $d)
                    <tr>
                        {{-- Kolom nomor dan ID transaksi ditampilkan hanya sekali per transaksi --}}
                        @if($i === 0)
                            <td rowspan="{{ $rowspan }}" class="text-center">{{ $no++ }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $kode }}</td>
                        @endif

                        <td>{{ $d['nama_barang'] }}</td>
                        <td class="text-right">Rp{{ number_format($d['harga_beli'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($d['harga_jual'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($d['margin'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($d['subtotal'], 0, ',', '.') }}</td>

                        @if($i === 0)
                            <td rowspan="{{ $rowspan }}" class="text-right">
                                Rp{{ number_format($d['harga_jasa'], 0, ',', '.') }}
                            </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <table class="summary">
        <tr>
            <td><strong>Total Modal:</strong> Rp{{ number_format($total['modal'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Margin:</strong> Rp{{ number_format($total['margin'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Jasa:</strong> Rp{{ number_format($total['jasa'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total Penjualan:</strong> Rp{{ number_format($total['penjualan'], 0, ',', '.') }}</td>
        </tr>
    </table>
</body>
</html>
