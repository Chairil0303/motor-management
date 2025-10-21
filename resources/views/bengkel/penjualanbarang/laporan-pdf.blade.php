<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #eee; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan Barang</h2>

    @if($filter)
        <p>Filter: 
            @if($filter['bulan'] ?? false) Bulan: {{ $filter['bulan'] }} @endif
            @if($filter['tanggal'] ?? false) | Tanggal: {{ $filter['tanggal'] }} @endif
        </p>
    @endif

    <table>
        <thead>
            <tr>
                <th>Kode Penjualan</th>
                <th>Tanggal</th>
                <th>Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualanBarangs as $pj)
            <tr>
                <td>{{ $pj->kode_penjualan }}</td>
                <td>{{ \Carbon\Carbon::parse($pj->tanggal_penjualan)->format('d M Y') }}</td>
                <td class="text-right">Rp{{ number_format($pj->total_penjualan,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
