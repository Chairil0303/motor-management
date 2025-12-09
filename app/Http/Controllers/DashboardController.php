<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Motor;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanBarang;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard dengan data statistik.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $now = Carbon::now();
        $year = $now->year;
        $month = $now->month;

        // --- Data Statistik Showroom (Penjualan Motor) ---
        $showroomStats = [
            'totalMotor'     => Motor::count(),
            'totalPelanggan' => Pelanggan::count(),
            'totalPenjualan' => Penjualan::count(),
            'totalLaba'      => Penjualan::sum('laba'),
            'motorTersedia'  => Motor::where('status', 'tersedia')->count(),
        ];

        // --- Data Statistik Bengkel (Penjualan Barang/Jasa) ---
        $bengkelStats = [
            'totalBarang'            => Barang::count(),
            'totalTransaksiBulanIni' => PenjualanBarang::whereYear('tanggal_penjualan', $year)
                ->whereMonth('tanggal_penjualan', $month)
                ->count(),
            'totalOmzetBulanIni'     => PenjualanBarang::whereYear('tanggal_penjualan', $year)
                ->whereMonth('tanggal_penjualan', $month)
                ->sum(DB::raw('total_penjualan')),
        ];

        // --- Data Chart Penjualan Bengkel (per hari di bulan ini) ---
        $chartData = PenjualanBarang::select(
                DB::raw('DAY(tanggal_penjualan) as hari'),
                DB::raw('SUM(total_penjualan) as total')
            )
            ->whereYear('tanggal_penjualan', $year)
            ->whereMonth('tanggal_penjualan', $month)
            ->groupBy('hari')
            ->orderBy('hari')
            ->get();

        $chartBengkel = [
            'hari'   => $chartData->pluck('hari'),
            'total'  => $chartData->pluck('total'),
        ];

        // Gabungkan semua data
        $data = array_merge(
            $showroomStats,
            $bengkelStats,
            $chartBengkel
        );

        return view('dashboard.index', $data);
    }
}
