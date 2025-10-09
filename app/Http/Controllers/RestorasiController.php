<?php

namespace App\Http\Controllers;

use App\Models\Restorasi;
use App\Models\Motor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RestorasiController extends Controller
{
    public function index()
    {
        // Ambil semua motor dengan relasi restorasi
        $motor = \App\Models\Motor::with('restorasis')->get();
        return view('restorasi.index', compact('motor'));
    }

    // Tambahin method detail
    public function detail($motorId)
    {
        $motor = \App\Models\Motor::with('restorasis')->findOrFail($motorId);
        return view('restorasi.partials.detail', compact('motor'));
    }
    public function create()
    {
        $motor = Motor::where('status', 'tersedia')->get();
        return view('restorasi.create', compact('motor'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // bersihkan format (1.000.000 -> 1000000)
        if (isset($data['biaya_restorasi'])) {
            $data['biaya_restorasi'] = (float) str_replace(['.', ','], ['', ''], $data['biaya_restorasi']);
        }

        $validated = Validator::make($data, [
            'motor_id' => 'required|exists:motor,id',
            'deskripsi' => 'nullable|string',
            'biaya_restorasi' => 'required|numeric|min:0',
            'tanggal_restorasi' => 'required|date',
        ])->validate();

        Restorasi::create($validated);

        return redirect()->route('restorasi.index')->with('success', 'Data restorasi berhasil ditambahkan!');
    }

    public function updateInline(Request $request, $id)
    {
        $r = \App\Models\Restorasi::findOrFail($id);

        $data = $request->validate([
            'deskripsi' => 'nullable|string',
            'tanggal_restorasi' => 'required|date',
            'biaya_restorasi' => 'required',
        ]);

        // Hilangkan format titik dari biaya
        $data['biaya_restorasi'] = str_replace('.', '', $data['biaya_restorasi']);

        $r->update($data);

        return back()->with('success', 'Data restorasi berhasil diperbarui!');
    }

    public function deleteInline($id)
    {
        $r = \App\Models\Restorasi::findOrFail($id);
        $r->delete();

        return response()->json(['success' => true]);
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
