<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanBarang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_penjualan',
        'tanggal_penjualan',
        'total_harga',
    ];

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}
