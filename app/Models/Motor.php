<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    protected $table = 'motor';

    protected $fillable = [
        'merek',
        'tipe_model',
        'tahun',
        'harga_beli',
        'kondisi',
        'status',
    ];

    public function pembelian()
    {
        return $this->hasOne(Pembelian::class);
    }

    public function restorasi()
    {
        return $this->hasMany(Restorasi::class);
    }

    public function penjualan()
    {
        return $this->hasOne(Penjualan::class);
    }
}
