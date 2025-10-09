<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    public function index()
    {
        $motor = Motor::all();
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
            'status' => 'required|string',
        ]);

        // Bersihkan format harga (hilangkan titik)
        $validated['harga_beli'] = str_replace('.', '', $validated['harga_beli']);

        \App\Models\Motor::create($validated);

        return redirect()->route('motor.index')->with('success', 'Data motor berhasil ditambahkan!');
    }


    public function edit($id)
    {
        $motor = \App\Models\Motor::findOrFail($id);
        return view('motor.edit', compact('motor'));
    }

    public function update(Request $request, $id)
    {
        $motor = \App\Models\Motor::findOrFail($id);

        $validated = $request->validate([
            'merek' => 'required|string',
            'tipe_model' => 'required|string',
            'tahun' => 'required|digits:4|integer',
            'harga_beli' => 'required',
            'plat_nomor' => 'required|unique:motor,plat_nomor,' . $motor->id,
            'nama_penjual' => 'required|string',
            'no_telp_penjual' => 'required|string',
            'alamat_penjual' => 'required|string',
            'kondisi' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $validated['harga_beli'] = str_replace('.', '', $validated['harga_beli']);

        $motor->update($validated);

        return redirect()->route('motor.index')->with('success', 'Data motor berhasil diperbarui!');
    }
    public function destroy(Motor $motor)
    {
        $motor->delete();
        return redirect()->route('motor.index')->with('success', 'Data motor berhasil dihapus!');
    }
}
