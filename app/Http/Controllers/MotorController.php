<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    public function index(Request $request)
    {
        $query = Motor::query();

        // Search by plat nomor
        if ($request->filled('search')) {
            $query->where('plat_nomor', 'like', '%' . $request->search . '%');
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $motor = $query->paginate(20); // pagination 20 per halaman

        return view('motor.index', compact('motor'));
    }



    public function create()
    {
        return view('motor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'merek' => 'required|string',
            'tipe_model' => 'required|string',
            'tahun' => 'required|digits:4|integer',
            'harga_beli' => 'required',
            'plat_nomor' => 'required|unique:motor,plat_nomor',
            'nama_penjual' => 'required|string',
            'no_telp_penjual' => 'required|string',
            'alamat_penjual' => 'required|string',
            'kondisi' => 'nullable|string',
        ]);

        // Bersihkan format harga (hilangkan titik)
        $validated['harga_beli'] = str_replace('.', '', $validated['harga_beli']);

        // ✅ Simpan motor hanya sekali
        $motor = \App\Models\Motor::create($validated);

        // ✅ Auto insert ke tabel pembelian
        \App\Models\Pembelian::create([
            'motor_id' => $motor->id,
            'tanggal_beli' => now()->toDateString(),
            'biaya_beli' => $validated['harga_beli'],
        ]);

        return redirect()->route('motor.index')->with('success', 'Data motor berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $motor = \App\Models\Motor::findOrFail($id);
        return view('motor.edit', compact('motor'));
    }

    public function update(Request $request, $id)
    {
        $motor = Motor::findOrFail($id);

        $request->validate([
            'merek' => 'required',
            'tipe_model' => 'required',
            'tahun' => 'required',
            'harga_beli' => 'required',
            'plat_nomor' => 'required',
        ]);

        $hargaBeli = str_replace('.', '', $request->harga_beli);
        $hargaJual = $request->harga_jual ? str_replace('.', '', $request->harga_jual) : null;

        $motor->update([
            'merek' => $request->merek,
            'tipe_model' => $request->tipe_model,
            'tahun' => $request->tahun,
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
            'plat_nomor' => $request->plat_nomor,
            'nama_penjual' => $request->nama_penjual,
            'no_telp_penjual' => $request->no_telp_penjual,
            'alamat_penjual' => $request->alamat_penjual,
            'kondisi' => $request->kondisi,
            // ✅ kalau status nggak dikirim dari form, tetap pakai status lama
            'status' => $request->status ?? $motor->status,
        ]);

        return redirect()->route('motor.index')->with('success', 'Data motor berhasil diperbarui');
    }


    public function destroy(Motor $motor)
    {
        $motor->delete();
        return redirect()->route('motor.index')->with('success', 'Data motor berhasil dihapus!');
    }
}
