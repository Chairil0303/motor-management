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

    // Pembelian Barang Bengkel
    Route::resource('pembelian', PembelianBarangController::class)
        ->names('bengkel.pembelian');

    // Penjualan Barang Bengkel
    Route::resource('penjualan', PenjualanBarangController::class)
        ->names('bengkel.penjualan');

    Route::resource('kategori', KategoriController::class)->except(['show'])
        ->names('bengkel.kategori');

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
