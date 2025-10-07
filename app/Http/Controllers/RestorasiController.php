<?php

namespace App\Http\Controllers;

use App\Models\Restorasi;
use App\Models\Motor;
use Illuminate\Http\Request;

class RestorasiController extends Controller
{
    public function index()
    {
        $restorasi = Restorasi::with('motor')->latest()->get();
        return view('restorasi.index', compact('restorasi'));
    }

    public function create()
    {
        $motor = Motor::where('status', 'tersedia')->get();
        return view('restorasi.create', compact('motor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'deskripsi' => 'nullable|string',
            'biaya_restorasi' => 'required|numeric|min:0',
            'tanggal_restorasi' => 'required|date',
        ]);

        Restorasi::create($request->all());

        return redirect()->route('restorasi.index')->with('success', 'Data restorasi berhasil ditambahkan!');
    }

    public function edit(Restorasi $restorasi)
    {
        $motor = Motor::all();
        return view('restorasi.edit', compact('restorasi', 'motor'));
    }

    public function update(Request $request, Restorasi $restorasi)
    {
        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'deskripsi' => 'nullable|string',
            'biaya_restorasi' => 'required|numeric|min:0',
            'tanggal_restorasi' => 'required|date',
        ]);

        $restorasi->update($request->all());

        return redirect()->route('restorasi.index')->with('success', 'Data restorasi berhasil diperbarui!');
    }

    public function destroy(Restorasi $restorasi)
    {
        $restorasi->delete();
        return redirect()->route('restorasi.index')->with('success', 'Data restorasi berhasil dihapus!');
    }
}
