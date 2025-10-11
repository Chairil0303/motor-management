<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        $penjualan = Penjualan::with(['motor', 'pelanggan'])->latest()->paginate(10);
        return view('laporan_penjualan.index', compact('penjualan'));
    }
}
