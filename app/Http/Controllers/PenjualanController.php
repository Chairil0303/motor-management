<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Motor;
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

    public function store(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'harga_jual' => 'required',
            'tanggal_jual' => 'required|date',
            'nama_pembeli' => 'required|string|max:255',
            'no_telp_pembeli' => 'required|string|max:20',
            'alamat_pembeli' => 'required|string',
        ]);

        $motor = Motor::findOrFail($request->motor_id);

        $hargaJual = floatval(str_replace('.', '', $request->harga_jual));
        $totalBiaya = $motor->harga_beli + $motor->restorasis->sum('biaya_restorasi');
        $laba = $hargaJual - $totalBiaya;

        Penjualan::create([
            'motor_id' => $motor->id,
            'harga_jual' => $hargaJual,
            'total_biaya' => $totalBiaya,
            'laba' => $laba,
            'tanggal_jual' => $request->tanggal_jual,
            'nama_pembeli' => $request->nama_pembeli,
            'no_telp_pembeli' => $request->no_telp_pembeli,
            'alamat_pembeli' => $request->alamat_pembeli,
        ]);

        $motor->update(['status' => 'terjual']);

        return redirect()->route('penjualan.index')->with('success', 'Motor berhasil dijual dan status diperbarui!');
    }

    public function destroy(Penjualan $penjualan)
    {
        $penjualan->delete();
        return redirect()->route('penjualan.index')->with('success', 'Data penjualan berhasil dihapus');
    }
}
