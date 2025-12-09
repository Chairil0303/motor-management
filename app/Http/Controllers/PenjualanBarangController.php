<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PenjualanBarang;
use App\Models\PenjualanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenjualanBarangController extends Controller
{
    public function index()
    {
        $penjualan = PenjualanBarang::with('details.barang')->orderByDesc('tanggal_penjualan')->paginate(15);
        return view('bengkel.penjualan.index', compact('penjualan'));
    }

    public function create()
    {
        $barang = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        return view('bengkel.penjualan.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_penjualan' => 'required|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barang,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:1',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $year = now()->format('y');
            $last = PenjualanBarang::whereYear('created_at', now()->year)->count() + 1;
            $kode = 'KENJ' . $year . str_pad($last, 4, '0', STR_PAD_LEFT);

            $total = 0;
            foreach ($request->barang_id as $i => $barang_id) {
                $total += $request->jumlah[$i] * $request->harga_satuan[$i];
            }

            $penjualan = PenjualanBarang::create([
                'kode_penjualan' => $kode,
                'tanggal_penjualan' => $request->tanggal_penjualan,
                'total_harga' => $total,
            ]);

            foreach ($request->barang_id as $i => $barang_id) {
                $jumlah = $request->jumlah[$i];
                $harga = $request->harga_satuan[$i];
                $subtotal = $jumlah * $harga;

                PenjualanDetail::create([
                    'penjualan_barang_id' => $penjualan->id,
                    'barang_id' => $barang_id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'subtotal' => $subtotal,
                ]);

                Barang::where('id', $barang_id)->decrement('stok', $jumlah);
            }
        });

        return redirect()->route('penjualan.index')->with('success', 'Penjualan barang berhasil ditambahkan!');
    }

    public function destroy(PenjualanBarang $penjualan)
    {
        $penjualan->delete();
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus!');
    }
}
