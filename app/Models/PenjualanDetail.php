<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'penjualan_barang_id',
        'barang_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
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
