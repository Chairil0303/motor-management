<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Motor;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index(Request $request)
    {
        $query = Motor::where('status', 'tersedia')->with('restorasis');

        if ($request->filled('search')) {
            $query->where('plat_nomor', 'like', '%' . $request->search . '%');
        }

        $motor = $query->paginate(20);

        return view('penjualan.index', compact('motor'));
    }

    public function create()
    {
        $motor = Motor::all();
        $pelanggan = Pelanggan::all();
        return view('penjualan.create', compact('motor', 'pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'harga_jual' => 'required',
            'tanggal_jual' => 'required|date',
        ]);

        $motor = Motor::findOrFail($request->motor_id);

        // ✅ Hapus semua titik supaya numeric valid
        $hargaJual = floatval(str_replace('.', '', $request->harga_jual));

        // Hitung total biaya
        $totalBiaya = $motor->harga_beli + $motor->restorasis->sum('biaya_restorasi');
        $laba = $hargaJual - $totalBiaya;

        // Simpan ke tabel penjualan
        Penjualan::create([
            'motor_id' => $motor->id,
            'harga_jual' => $hargaJual,
            'total_biaya' => $totalBiaya,
            'laba' => $laba,
            'tanggal_jual' => $request->tanggal_jual,
        ]);

        // ✅ Update status motor jadi terjual
        $motor->update(['status' => 'terjual']);

        return redirect()->route('penjualan.index')->with('success', 'Motor berhasil dijual dan status diperbarui!');
    }


    public function edit(Penjualan $penjualan)
    {
        $motor = Motor::all();
        $pelanggan = Pelanggan::all();
        return view('penjualan.edit', compact('penjualan', 'motor', 'pelanggan'));
    }

    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'motor_id' => 'required',
            'pelanggan_id' => 'required',
            'harga_jual' => 'required|numeric',
            'tanggal_jual' => 'required|date',
        ]);

        $motor = Motor::findOrFail($request->motor_id);

        $totalBiaya = $motor->harga_beli + $motor->restorasi->sum('biaya_restorasi');
        $laba = $request->harga_jual - $totalBiaya;

        $penjualan->update([
            'motor_id' => $request->motor_id,
            'pelanggan_id' => $request->pelanggan_id,
            'harga_jual' => $request->harga_jual,
            'total_biaya' => $totalBiaya,
            'laba' => $laba,
            'tanggal_jual' => $request->tanggal_jual,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil diperbarui');
    }

    public function destroy(Penjualan $penjualan)
    {
        $penjualan->delete();
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus');
    }
}
