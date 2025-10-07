<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Motor;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function index()
    {
        $pembelian = Pembelian::with('motor')->latest()->get();
        return view('pembelian.index', compact('pembelian'));
    }

    public function create()
    {
        $motor = Motor::where('status', 'tersedia')->get();
        return view('pembelian.create', compact('motor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'tanggal_beli' => 'required|date',
            'biaya_beli' => 'required|numeric|min:0',
        ]);

        Pembelian::create($request->all());

        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil ditambahkan!');
    }

    public function edit(Pembelian $pembelian)
    {
        $motor = Motor::all();
        return view('pembelian.edit', compact('pembelian', 'motor'));
    }

    public function update(Request $request, Pembelian $pembelian)
    {
        $request->validate([
            'motor_id' => 'required|exists:motor,id',
            'tanggal_beli' => 'required|date',
            'biaya_beli' => 'required|numeric|min:0',
        ]);

        $pembelian->update($request->all());

        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil diperbarui!');
    }

    public function destroy(Pembelian $pembelian)
    {
        $pembelian->delete();
        return redirect()->route('pembelian.index')->with('success', 'Data pembelian berhasil dihapus!');
    }
}
