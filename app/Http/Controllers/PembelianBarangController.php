<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\PembelianBarang;
use App\Models\PembelianDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianBarangController extends Controller
{
    public function index()
    {
        $pembelian = PembelianBarang::with('details.barang')->orderByDesc('tanggal_pembelian')->paginate(15);
        return view('bengkel.pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('bengkel.pembelian.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_pembelian' => 'required|date',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barang,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'numeric|min:1',
            'harga_satuan' => 'required|array',
            'harga_satuan.*' => 'numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $year = now()->format('y');
            $last = PembelianBarang::whereYear('created_at', now()->year)->count() + 1;
            $kode = 'KENB' . $year . str_pad($last, 4, '0', STR_PAD_LEFT);

            $total = 0;
            foreach ($request->barang_id as $i => $barang_id) {
                $total += $request->jumlah[$i] * $request->harga_satuan[$i];
            }

            $pembelian = PembelianBarang::create([
                'kode_pembelian' => $kode,
                'tanggal_pembelian' => $request->tanggal_pembelian,
                'total_harga' => $total,
            ]);

            foreach ($request->barang_id as $i => $barang_id) {
                $jumlah = $request->jumlah[$i];
                $harga = $request->harga_satuan[$i];
                $subtotal = $jumlah * $harga;

                PembelianDetail::create([
                    'pembelian_barang_id' => $pembelian->id,
                    'barang_id' => $barang_id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $harga,
                    'subtotal' => $subtotal,
                ]);

                Barang::where('id', $barang_id)->increment('stok', $jumlah);
            }
        });

        return redirect()->route('bengkel.pembelian.index')->with('success', 'Pembelian barang berhasil ditambahkan!');
    }

    public function destroy(PembelianBarang $pembelian)
    {
        $pembelian->delete();
        return redirect()->route('bengkel.pembelian.index')->with('success', 'Data pembelian berhasil dihapus!');
    }
}
