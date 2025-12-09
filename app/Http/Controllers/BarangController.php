<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Kategori;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori');

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $barang = $query->orderBy('nama_barang')->paginate(20);
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('bengkel.barang.index', compact('barang', 'kategoris'));
    }


    public function create()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('bengkel.barang.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        // generate kode
        $latest = Barang::latest('id')->first();
        $increment = $latest ? str_pad($latest->id + 1, 4, '0', STR_PAD_LEFT) : '0001';
        $kode = 'BRG' . date('y') . $increment;

        // simpan (pastikan kolom kategori_id ada di migration/barangs)
        $barang = Barang::create([
            'kode_barang' => $kode,
            'nama_barang' => $validated['nama_barang'],
            'kategori_id' => $validated['kategori_id'] ?? null,
            'stok' => $validated['stok'],
            'harga_beli' => $validated['harga_beli'],
            'harga_jual' => $validated['harga_jual'],
        ]);

        // Jika AJAX / expects JSON -> kembalikan JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil dibuat',
                'data' => $barang,
            ], 201);
        }

        return redirect()->route('bengkel.barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }



    public function edit(Barang $barang)
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        return view('bengkel.barang.edit', compact('barang', 'kategoris'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $barang->update($request->only(['nama_barang', 'kategori_id', 'stok', 'harga_beli', 'harga_jual']));

        return redirect()->route('bengkel.barang.index')
            ->with('success', 'Barang berhasil diperbarui!');
    }
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('bengkel.barang.index')
            ->with('success', 'Barang berhasil dihapus!');
    }
}
