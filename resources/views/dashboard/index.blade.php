@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>   
    <h1 class="text-3xl font-bold">Dashboard Ken Motor</h1>

   <div class="p-4 lg:p-6">
    <!-- Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        <!-- Barang Bengkel -->
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-gray-500 text-sm">Total Barang Bengkel</h2>
            <p class="text-2xl font-bold">{{ $totalBarang }}</p>
        </div>

        <!-- Transaksi Bengkel Bulan Ini -->
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-gray-500 text-sm">Transaksi Bengkel (Bulan Ini)</h2>
            <p class="text-2xl font-bold">{{ $totalTransaksiBulanIni }}</p>
        </div>

        <!-- Motor Tersedia -->
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-gray-500 text-sm">Motor Tersedia</h2>
            <p class="text-2xl font-bold text-green-600">{{ $motorTersedia }}</p>
        </div>

        <!-- Total Omzet Bengkel Bulan Ini -->
        <div class="bg-white rounded-xl shadow p-4">
            <h2 class="text-gray-500 text-sm">Total Omzet Bengkel (Bulan Ini)</h2>
            <p class="text-2xl font-bold text-blue-600">
                Rp {{ number_format($totalOmzetBulanIni, 0, ',', '.') }}
            </p>
        </div>
    </div>

    
  <!-- Chart -->
    <div class="bg-white p-4 rounded shadow mt-6">
        <h2 class="text-xl font-semibold mb-4">Grafik Penjualan Bengkel (Per Hari)</h2>

        <!-- Lebih kecil: h-24 = 6rem (≈96px) -->
        <div class="relative h-32">
            <canvas id="chartPenjualan"></canvas>
        </div>
    </div>

   <script>
    const ctx = document.getElementById('chartPenjualan').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($hari) !!},
            datasets: [{
                label: 'Total Penjualan Harian',
                data: {!! json_encode($total) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 2, // titik data lebih kecil
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // wajib biar CSS tinggi berlaku
            plugins: {
                legend: { display: false } // sembunyikan legend biar makin compact
            },
            scales: {
                x: {
                    ticks: { font: { size: 10 } },
                    title: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { font: { size: 10 } },
                    title: { display: false },
                    grid: { display: false }
                }
            },
            layout: {
                padding: 5 // sedikit jarak biar gak terlalu padat
            }
        }
    });
</script>
    
@endsection
