<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MotorController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\RestorasiController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanPenjualanController;

// kasir bengkel
use App\Http\Controllers\BarangController;
use App\Http\Controllers\PembelianBarangController;
use App\Http\Controllers\PenjualanBarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\RiwayatBelanjaController;


use App\Models\Barang;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/restorasi/detail/{motorId}', [RestorasiController::class, 'detail'])
    ->name('restorasi.detail');
Route::put('restorasi/update/{id}', [RestorasiController::class, 'updateInline'])->name('restorasi.updateInline');
Route::delete('restorasi/delete/{id}', [RestorasiController::class, 'deleteInline'])->name('restorasi.deleteInline');



// bengkel motor
// bengkel motor
Route::middleware(['auth'])->prefix('bengkel')->group(function () {

    // Manajemen Stok Barang
    Route::resource('barang', BarangController::class)
        ->names('bengkel.barang');

    // Belanja Barang Bengkel (ganti Pembelian)
    Route::resource('riwayatbelanja', RiwayatBelanjaController::class)
        ->names('bengkel.belanja')
        ->except(['show']);

    // Pembelian Barang Bengkel
    Route::resource('pembelian', PembelianBarangController::class)
        ->names('bengkel.pembelian');

    // Penjualan Barang Bengkel
    Route::resource('penjualan', PenjualanBarangController::class)
        ->names('bengkel.penjualan');

    Route::resource('kategori', KategoriController::class)->except(['show'])
        ->names('bengkel.kategori');

    Route::get('/belanja/search-barang', function (Request $request) {
        $query = $request->q;
        return Barang::where('nama_barang', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'nama_barang', 'stok', 'harga_beli', 'harga_jual']);
    })->middleware('auth')->name('bengkel.belanja.search-barang');

    // tambah barang ke pembelian via AJAX
    Route::post('/barang/store-ajax', function (Request $request) {
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategoris,id',
            'stok' => 'required|integer|min:0',
            'harga_beli' => 'required',
            'harga_jual' => 'nullable'
        ]);

        // 🔧 Generate kode barang
        $latestBarang = \App\Models\Barang::latest('id')->first();
        $increment = $latestBarang ? str_pad($latestBarang->id + 1, 4, '0', STR_PAD_LEFT) : '0001';
        $kodeBarang = 'BRG' . date('y') . $increment;
        $validated['kode_barang'] = $kodeBarang;

        // 💰 Bersihkan format angka
        $validated['harga_beli'] = (int) str_replace('.', '', $validated['harga_beli']);
        $validated['harga_jual'] = (int) str_replace('.', '', $validated['harga_jual'] ?? 0);

        // 🧾 Simpan barang baru
        $barang = \App\Models\Barang::create($validated);

        // ⚙️ Generate kode belanja otomatis
        $latestBelanja = \App\Models\RiwayatBelanja::latest('id')->first();
        $incrementBelanja = $latestBelanja ? str_pad($latestBelanja->id + 1, 4, '0', STR_PAD_LEFT) : '0001';
        $kodeBelanja = 'KENB' . date('y') . $incrementBelanja;

        // 🧮 Hitung total
        $totalBelanja = $barang->stok * $validated['harga_beli'];

        // 💾 Simpan ke riwayat belanja juga
        \App\Models\RiwayatBelanja::create([
            'kode_belanja' => $kodeBelanja,
            'barang_id' => $barang->id,
            'tanggal_belanja' => now(),
            'kuantiti' => $barang->stok,
            'harga_beli' => $validated['harga_beli'],
            'total_belanja' => $totalBelanja,
        ]);

        return response()->json([
            'success' => true,
            'barang' => $barang
        ]);
    })->name('bengkel.barang.storeAjax');


});




Route::resource('motor', MotorController::class)->middleware(['auth']);
Route::resource('pelanggan', PelangganController::class)->middleware(['auth']);
Route::resource('pembelian', PembelianController::class)->middleware(['auth']);
Route::resource('restorasi', RestorasiController::class)->middleware(['auth']);
Route::resource('penjualan', PenjualanController::class)->middleware(['auth']);
Route::get('/laporan-penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




require __DIR__ . '/auth.php';
