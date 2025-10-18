<?php

namespace App\Http\Controllers;

use App\Models\RiwayatBelanja;
use App\Models\Barang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Kategori;

class RiwayatBelanjaController extends Controller
{
    public function index(Request $request)
    {
        $riwayatQuery = \App\Models\RiwayatBelanja::with('barang');

        // Filter bulan (format: YYYY-MM dari <input type="month">)
        if ($request->filled('bulan')) {
            try {
                [$tahun, $bulan] = explode('-', $request->bulan);
                $riwayatQuery->whereYear('tanggal_belanja', $tahun)
                    ->whereMonth('tanggal_belanja', $bulan);
            } catch (\Exception $e) {
                // Lewatin kalau format bulan salah
            }
        }

        // Hitung total belanja dari query yang sama (clone supaya paginate tidak terpengaruh)
        $totalBelanja = (clone $riwayatQuery)->sum('total_belanja') ?: 0;

        // Ambil data dengan paginate. withQueryString() biar filter bulan tetap ada saat pindah halaman
        $belanjas = $riwayatQuery->orderBy('tanggal_belanja', 'desc')->paginate(10)->withQueryString();

        return view('bengkel.belanja.index', compact('belanjas', 'totalBelanja'));
    }


    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('bengkel.belanja.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barangs,id',
            'kuantiti' => 'required|integer|min:1',
            'harga_beli' => 'required',
            'harga_jual' => 'nullable',
        ]);

        // 🔧 Hilangkan semua titik (.) sebelum convert ke angka
        $hargaBeli = str_replace('.', '', $request->harga_beli);
        $hargaJual = str_replace('.', '', $request->harga_jual ?? 0);

        $barang = Barang::find($request->barang_id);

        // Update stok dan harga barang
        $barang->update([
            'stok' => $barang->stok + $request->kuantiti,
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual ?: $barang->harga_jual,
        ]);

        // Generate kode belanja otomatis
        $latest = RiwayatBelanja::latest('id')->first();
        $increment = $latest ? str_pad($latest->id + 1, 4, '0', STR_PAD_LEFT) : '0001';
        $kode = 'KENB' . date('y') . $increment;

        // Simpan riwayat belanja
        RiwayatBelanja::create([
            'kode_belanja' => $kode,
            'barang_id' => $barang->id,
            'tanggal_belanja' => now(),
            'kuantiti' => $request->kuantiti,
            'harga_beli' => $hargaBeli,
            'total_belanja' => $request->kuantiti * $hargaBeli,
        ]);

        return redirect()->route('bengkel.belanja.index')
            ->with('success', 'Belanja barang berhasil disimpan!');
    }

}
