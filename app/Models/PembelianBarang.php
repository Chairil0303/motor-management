<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_pembelian',
        'tanggal_pembelian',
        'total_harga',
    ];

    public function details()
    {
        return $this->hasMany(PembelianDetail::class);
    }
}
