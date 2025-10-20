<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanBarang extends Model
{
    use HasFactory;

    protected $table = 'penjualan_barangs';

    protected $fillable = [
        'kode_penjualan',
        'tanggal_penjualan',
        'total_penjualan',
        'total_margin',
        'harga_jasa',
    ];

    protected $casts = [
        'tanggal_penjualan' => 'datetime',
        'total_penjualan' => 'decimal:2',
        'total_margin' => 'decimal:2',
        'harga_jasa' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(PenjualanBarangDetail::class, 'penjualan_barang_id');
    }
}
