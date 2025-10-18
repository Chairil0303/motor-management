<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBelanja extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_belanja',
        'barang_id',
        'tanggal_belanja',
        'kuantiti',
        'harga_beli',
        'total_belanja',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
