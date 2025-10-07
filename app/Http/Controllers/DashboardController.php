<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Card Data
        $totalMotor = Motor::count();
        $totalPelanggan = Pelanggan::count();
        $totalPenjualan = Penjualan::count();
        $totalLaba = Penjualan::sum('laba');

        // Chart Data - penjualan per bulan
        $chartData = Penjualan::select(
            DB::raw('MONTH(tanggal_jual) as bulan'),
            DB::raw('SUM(harga_jual) as total')
        )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulan = $chartData->pluck('bulan');
        $total = $chartData->pluck('total');

        return view('dashboard.index', compact(
            'totalMotor',
            'totalPelanggan',
            'totalPenjualan',
            'totalLaba',
            'bulan',
            'total'
        ));
    }
}
