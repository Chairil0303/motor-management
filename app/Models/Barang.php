<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori_id',
        'stok',
        'harga_beli',
        'harga_jual',
        'satuan',
    ];

    // Barang bisa muncul di banyak detail pembelian & penjualan
    public function pembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

}
