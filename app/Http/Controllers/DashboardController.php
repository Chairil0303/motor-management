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
            'motorTersedia'  => Motor::where('status', 'tersedia')->count(), // motor yang masih tersedia
        ];

        // --- Data Statistik Bengkel (Penjualan Barang/Jasa) ---
        $bengkelStats = [
            'totalBarang'               => Barang::count(), // jumlah item unik di bengkel
            'totalTransaksiBulanIni'    => PenjualanBarang::whereYear('tanggal_penjualan', $year)
                ->whereMonth('tanggal_penjualan', $month)
                ->count(),
            'totalOmzetBulanIni'        => PenjualanBarang::whereYear('tanggal_penjualan', $year)
                ->whereMonth('tanggal_penjualan', $month)
                ->sum(DB::raw('total_penjualan')), // termasuk jasa
        ];

        // --- Data Chart Penjualan Showroom ---
        $chartData = Penjualan::select(
            DB::raw('MONTH(tanggal_jual) as bulan'),
            DB::raw('SUM(harga_jual) as total')
        )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $chartShowroom = [
            'bulan' => $chartData->pluck('bulan'),
            'total' => $chartData->pluck('total'),
        ];

        // Gabungkan semua data menjadi satu array untuk dikirim ke view
        $data = array_merge(
            $showroomStats,
            $bengkelStats,
            $chartShowroom
        );

        return view('dashboard.index', $data);
        /* * Alternatif: menggunakan compact() jika tidak ingin menggunakan array_merge. 
         * return view('dashboard.index', compact(
         * 'showroomStats',
         * 'bengkelStats',
         * 'chartShowroom'
         * )); 
         * namun view Anda harus menyesuaikan.
         */
    }
}