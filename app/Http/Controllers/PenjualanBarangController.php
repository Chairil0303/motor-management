<?php

namespace App\Http\Controllers;

use App\Models\PenjualanBarang;
use App\Models\PenjualanBarangDetail;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;


class PenjualanBarangController extends Controller
{
    public function index(Request $request)
{
    $query = PenjualanBarang::with('details.barang');

    if ($request->filled('bulan')) {
        try {
            [$y, $m] = explode('-', $request->bulan);
            $query->whereYear('tanggal_penjualan', $y)
                  ->whereMonth('tanggal_penjualan', $m);
        } catch (\Exception $e) {}
    }

    if ($request->filled('tanggal')) {
        $query->whereDate('tanggal_penjualan', $request->tanggal);
    }

    // pagination jadi 20
    $penjualans = $query->orderBy('tanggal_penjualan', 'desc')
                        ->paginate(20)
                        ->withQueryString();

    return view('bengkel.penjualanbarang.index', [
        'penjualanBarangs' => $penjualans,
    ]);
    }


    public function create()
    {
        // view nanti yg handle dynamic rows & search
        return view('bengkel.penjualanbarang.create');
    }

    // AJAX endpoint untuk autocomplete search barang
    public function searchBarang(Request $request)
    {
        $q = $request->q ?? '';
        $items = Barang::where('nama_barang', 'like', "%{$q}%")
            ->limit(10)
            ->get(['id','nama_barang','stok','harga_jual','harga_beli']);
        return response()->json($items);
    }

    public function edit($id)
    {
        // Ambil data penjualan beserta detail barang
        $penjualan = PenjualanBarang::with('details.barang')->findOrFail($id);

        return view('bengkel.penjualanbarang.edit', compact('penjualan'));
    }


    // update/edit transaksi
    public function update(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'barang_id'   => 'required|array|min:1',
            'barang_id.*' => 'exists:barangs,id',
            'kuantiti'    => 'required|array',
            'kuantiti.*'  => 'integer|min:1',
            'harga_jasa'  => 'nullable',
        ]);

        DB::beginTransaction();
        try {
            $penjualan = PenjualanBarang::with('details')->findOrFail($id);

            // 2. Balik Stok Barang Lama
            // Iterasi detail lama untuk mengembalikan stok ke Barang
            foreach ($penjualan->details as $detail) {
                if ($barang = Barang::find($detail->barang_id)) {
                    $barang->stok += $detail->kuantiti;
                    $barang->save();
                }
            }

            // 3. Hapus Detail Lama
            $penjualan->details()->delete();

            $totalPenjualan = 0;
            $totalMargin    = 0;

            // 4. Proses dan Buat Detail Baru
            foreach ($request->barang_id as $i => $barangId) {
                $qty = (int) $request->kuantiti[$i];

                // Lock the row to prevent race conditions during stock update
                $barang = Barang::lockForUpdate()->findOrFail($barangId);

                if ($barang->stok < $qty) {
                    DB::rollBack();
                    return back()->with('error', "Stok tidak cukup untuk {$barang->nama_barang}. Stok saat ini: {$barang->stok}");
                }

                // Kurangi stok barang
                $barang->stok -= $qty;
                $barang->save();

                $subtotal = $barang->harga_jual * $qty;
                $margin   = ($barang->harga_jual - $barang->harga_beli) * $qty;

                // Buat detail penjualan baru
                PenjualanBarangDetail::create([
                    'penjualan_barang_id' => $penjualan->id,
                    'barang_id'           => $barang->id,
                    'kuantiti'            => $qty,
                    'harga_jual'          => $barang->harga_jual,
                    'harga_beli'          => $barang->harga_beli,
                    'subtotal'            => $subtotal,
                    'margin'              => $margin,
                ]);

                $totalPenjualan += $subtotal;
                $totalMargin    += $margin;
            }

            // 5. Update Transaksi Utama
            // Bersihkan format Rupiah (e.g., "1.000.000" menjadi 1000000)
            $hargaJasa = (float) preg_replace('/[^\d]/', '', $request->harga_jasa ?? 0);

            $penjualan->update([
                'harga_jasa'      => $hargaJasa,
                'total_penjualan' => $totalPenjualan + $hargaJasa,
                'total_margin'    => $totalMargin,
            ]);

            DB::commit();

            return redirect()->route('bengkel.penjualanbarang.index')->with('success', 'Transaksi berhasil diperbarui!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Catat error untuk debugging
            Log::error('Penjualan update error: ' . $e->getMessage());
            
            return back()->with('error', 'Gagal update transaksi. Silakan coba lagi.');
        }
    }

    public function store(Request $request)
    {
        // ekspektasi: barang_id[] dan kuantiti[] di request
        $request->validate([
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barangs,id',
            'kuantiti' => 'required|array',
            'kuantiti.*' => 'required|integer|min:1',
            'harga_jasa' => 'nullable',
        ]);

        $barangIds = $request->input('barang_id');
        $kuantitis = $request->input('kuantiti');
        $hargaJasa = (float) preg_replace('/\D/', '', $request->input('harga_jasa', 0)) / 100; // if formatted with decimals; or simply cast
        // NOTE: if harga_jasa sent as plain number without separators, adjust accordingly
        // For safety, let's sanitize simpler:
        $rawHargaJasa = $request->input('harga_jasa', 0);
        $hargaJasaSanitized = (float) (preg_replace('/\D/', '', $rawHargaJasa) === '' ? 0 : preg_replace('/\D/', '', $rawHargaJasa));
        // assuming stored with two decimals in DB; but to keep consistency, we treat as integer (no cents). We'll store as decimal: divide if needed.
        // Simpler: treat hargaJasa as integer value in units (e.g., 100000)
        $hargaJasa = (float) $hargaJasaSanitized;

        DB::beginTransaction();

        try {
            // create header first (kode)
            $last = PenjualanBarang::latest('id')->first();
            $increment = $last ? str_pad($last->id + 1, 4, '0', STR_PAD_LEFT) : '0001';
            $kode = 'PNJB' . date('y') . $increment;

            $penjualan = PenjualanBarang::create([
                'kode_penjualan' => $kode,
                'tanggal_penjualan' => now(),
                'harga_jasa' => $hargaJasa,
                'total_penjualan' => 0,
                'total_margin' => 0,
            ]);

            $totalItems = 0;
            $totalMargin = 0;

            foreach ($barangIds as $idx => $barangId) {
                $qty = intval($kuantitis[$idx] ?? 0);
                if ($qty <= 0) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', 'Kuantiti harus lebih dari 0');
                }

                // ambil barang dengan lock for update supaya aman concurrent
                $barang = Barang::lockForUpdate()->findOrFail($barangId);

                if ($barang->stok < $qty) {
                    DB::rollBack();
                    return redirect()->back()->withInput()->with('error', "Stok tidak cukup untuk barang: {$barang->nama_barang}");
                }

                $hargaJual = (float) $barang->harga_jual;
                $hargaBeli = (float) $barang->harga_beli;

                $subtotal = $hargaJual * $qty;
                $margin = ($hargaJual - $hargaBeli) * $qty;

                // create detail
                PenjualanBarangDetail::create([
                    'penjualan_barang_id' => $penjualan->id,
                    'barang_id' => $barang->id,
                    'kuantiti' => $qty,
                    'harga_jual' => $hargaJual,
                    'harga_beli' => $hargaBeli,
                    'subtotal' => $subtotal,
                    'margin' => $margin,
                ]);

                // kurangi stok
                $barang->stok -= $qty;
                $barang->save();

                $totalItems += $subtotal;
                $totalMargin += $margin;
            }

            // update header totals
            $penjualan->update([
                'total_penjualan' => $totalItems + $hargaJasa,
                'total_margin' => $totalMargin,
            ]);

            DB::commit();

            return redirect()->route('bengkel.penjualanbarang.index')
                ->with('success', 'Transaksi penjualan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            // log error jika perlu
            \Log::error('Penjualan store error: '.$e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan transaksi: '.$e->getMessage());
        }
    }

    public function show($id)
    {
        $penjualan = PenjualanBarang::with('details.barang')->findOrFail($id);
        return view('bengkel.penjualanbarang.show', compact('penjualan'));
    }

    public function destroy($id)
    {
        $penjualan = PenjualanBarang::with('details')->findOrFail($id);

        DB::beginTransaction();
        try {
            // restore stok
            foreach ($penjualan->details as $detail) {
                $barang = Barang::find($detail->barang_id);
                if ($barang) {
                    $barang->stok += $detail->kuantiti;
                    $barang->save();
                }
            }

            $penjualan->delete();

            DB::commit();
            return redirect()->route('bengkel.penjualanbarang.index')->with('success', 'Transaksi berhasil dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Penjualan destroy error: '.$e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus transaksi: '.$e->getMessage());
        }
    }

    // cetak laporan penjualan 

        // laporan bulanan
        public function cetakLaporanBulan(Request $request)
        {
            $penjualanBarangs = PenjualanBarang::with('details.barang')
                ->whereMonth('tanggal_penjualan', \Carbon\Carbon::parse($request->bulan)->month)
                ->whereYear('tanggal_penjualan', \Carbon\Carbon::parse($request->bulan)->year)
                ->get();

            $pdf = Pdf::loadView('bengkel.penjualanbarang.laporan-pdf-detail', [
                'penjualanBarangs' => $penjualanBarangs,
                'filter' => ['bulan' => $request->bulan]
            ]);

            return $pdf->download('laporan-penjualan-bulan.pdf');
        }

        // laporan tanggal
        public function cetakLaporanTanggal(Request $request)
        {
            $penjualanBarangs = PenjualanBarang::with('details.barang')
                ->whereDate('tanggal_penjualan', $request->tanggal)
                ->get();

            $pdf = Pdf::loadView('bengkel.penjualanbarang.laporan-pdf-detail', [
                'penjualanBarangs' => $penjualanBarangs,
                'filter' => ['tanggal' => $request->tanggal]
            ]);

            return $pdf->download('laporan-penjualan-tanggal.pdf');
        }
}
