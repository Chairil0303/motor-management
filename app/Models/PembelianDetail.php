<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'pembelian_barang_id',
        'barang_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function pembelian()
    {
        return $this->belongsTo(PembelianBarang::class, 'pembelian_barang_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
