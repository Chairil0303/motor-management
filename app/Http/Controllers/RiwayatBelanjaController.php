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
        $riwayatQuery =RiwayatBelanja::with('barang');

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
        $hargaBeli = (int) preg_replace('/\D/', '', $request->harga_beli);
        $hargaJual = (int) preg_replace('/\D/', '', $request->harga_jual ?? 0);

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

    public function edit($id)
{
    $belanja = RiwayatBelanja::with('barang')->findOrFail($id);
    $barangs = Barang::orderBy('nama_barang')->get();
    return view('bengkel.belanja.edit', compact('belanja', 'barangs'));
}

public function update(Request $request, $id)
{
    $belanja = RiwayatBelanja::findOrFail($id);

    $request->validate([
        'barang_id' => 'required|exists:barangs,id',
        'kuantiti' => 'required|integer|min:1',
        'harga_beli' => 'required',
        'harga_jual' => 'required',
    ]);

    // format angka
    $hargaBeli = (int) preg_replace('/\D/', '', $request->harga_beli);
    $hargaJual = (int) preg_replace('/\D/', '', $request->harga_jual);

    $barang = Barang::find($request->barang_id);

    // hitung selisih kuantiti
    $selisih = $request->kuantiti - $belanja->kuantiti;

    // update stok dan harga
    $barang->update([
        'stok' => $barang->stok + $selisih,
        'harga_beli' => $hargaBeli,
        'harga_jual' => $hargaJual,
    ]);

    // update riwayat belanja
    $belanja->update([
        'kuantiti' => $request->kuantiti,
        'harga_beli' => $hargaBeli,
        'total_belanja' => $hargaBeli * $request->kuantiti,
    ]);

    return redirect()->route('bengkel.belanja.index')->with('success', 'Riwayat belanja berhasil diperbarui!');
}

public function destroy($id)
{
    $belanja = RiwayatBelanja::findOrFail($id);
    $barang = Barang::find($belanja->barang_id);

    // kembalikan stok ke semula
    $barang->stok -= $belanja->kuantiti;
    $barang->save();

    $belanja->delete();

    return redirect()->route('bengkel.belanja.index')->with('success', 'Riwayat belanja berhasil dihapus!');
}   

}
