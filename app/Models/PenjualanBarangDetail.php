<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanBarangDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_barang_details';

    protected $fillable = [
        'penjualan_barang_id',
        'barang_id',
        'kuantiti',
        'harga_jual',
        'harga_beli',
        'subtotal',
        'margin',
    ];

    protected $casts = [
        'harga_jual' => 'decimal:2',
        'harga_beli' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'margin' => 'decimal:2',
    ];

    public function penjualan()
    {
        return $this->belongsTo(PenjualanBarang::class, 'penjualan_barang_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
