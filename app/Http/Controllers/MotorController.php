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
            'merek' => 'required|string|max:255',
            'tipe_model' => 'required|string|max:255',
            'tahun' => 'required|digits:4|integer',
            'harga_beli' => 'required|numeric|min:0',
            'kondisi' => 'nullable|string|max:255',
            'status' => 'required|string',
        ]);

        \App\Models\Motor::create($validated);

        return redirect()->route('motor.index')->with('success', 'Data motor berhasil ditambahkan!');
    }


    public function edit(Motor $motor)
    {
        return view('motor.edit', compact('motor'));
    }

    public function update(Request $request, Motor $motor)
    {
        $request->validate([
            'merek' => 'required|string|max:255',
            'tipe_model' => 'required|string|max:255',
            'tahun' => 'required|numeric',
            'harga_beli' => 'required|numeric',
            'kondisi' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $motor->update($request->all());
        return redirect()->route('motor.index')->with('success', 'Data motor berhasil diperbarui!');
    }

    public function destroy(Motor $motor)
    {
        $motor->delete();
        return redirect()->route('motor.index')->with('success', 'Data motor berhasil dihapus!');
    }
}
