<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Motor;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    public function index()
    {
        // ambil motor yang belum terjual
        $motor = \App\Models\Motor::whereDoesntHave('penjualan')->with('restorasis')->get();

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
            'motor_id' => 'required',
            'pelanggan_id' => 'required',
            'harga_jual' => 'required|numeric',
            'tanggal_jual' => 'required|date',
        ]);

        $motor = Motor::findOrFail($request->motor_id);

        // total biaya = harga pembelian + total biaya restorasi
        $totalBiaya = $motor->harga_beli + $motor->restorasi->sum('biaya_restorasi');
        $laba = $request->harga_jual - $totalBiaya;

        // Cek apakah motor sudah pernah terjual
        $cekPenjualan = \App\Models\Penjualan::where('motor_id', $request->motor_id)->exists();
        if ($cekPenjualan) {
            return back()->withErrors(['motor_id' => 'Motor ini sudah pernah terjual dan tidak bisa dijual lagi.'])->withInput();
        }

        Penjualan::create([
            'motor_id' => $request->motor_id,
            'pelanggan_id' => $request->pelanggan_id,
            'harga_jual' => $request->harga_jual,
            'total_biaya' => $totalBiaya,
            'laba' => $laba,
            'tanggal_jual' => $request->tanggal_jual,
        ]);

        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil ditambahkan');
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
