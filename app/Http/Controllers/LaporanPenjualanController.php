<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Penjualan::with(['motor', 'pelanggan']);

        // ✅ Filter bulan (format: YYYY-MM)
        if ($request->bulan) {
            $query->whereMonth('tanggal_jual', date('m', strtotime($request->bulan)))
                ->whereYear('tanggal_jual', date('Y', strtotime($request->bulan)));
        }

        // ✅ Search kode penjualan
        if ($request->search) {
            $query->where('kode_penjualan', 'like', '%' . $request->search . '%');
        }

        $penjualan = $query->latest()->paginate(10);

        return view('laporan_penjualan.index', compact('penjualan'));
    }
}
