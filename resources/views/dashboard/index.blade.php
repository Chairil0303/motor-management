@extends('layouts.app')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Dashboard Ken Motor</h1>

    <div class="p-4 lg:p-6">
        <!-- Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 ">
            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="text-gray-500 text-sm">Total Motor</h2>
                <p class="text-2xl font-bold">{{ $totalMotor }}</p>
            </div>

            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="text-gray-500 text-sm">Total Pelanggan</h2>
                <p class="text-2xl font-bold">{{ $totalPelanggan }}</p>
            </div>

            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="text-gray-500 text-sm">Total Penjualan</h2>
                <p class="text-2xl font-bold">{{ $totalPenjualan }}</p>
            </div>

            <div class="bg-white rounded-xl shadow p-4">
                <h2 class="text-gray-500 text-sm">Total Laba</h2>
                <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalLaba, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>


    <!-- Chart -->
    <div class="bg-white p-4 rounded shadow">
        <h2 class="text-xl font-semibold mb-4">Grafik Penjualan per Bulan</h2>
        <canvas id="chartPenjualan"></canvas>
    </div>

    <script>
        const ctx = document.getElementById('chartPenjualan').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($bulan) !!},
                datasets: [{
                    label: 'Total Penjualan',
                    data: {!! json_encode($total) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
@endsection