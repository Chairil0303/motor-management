<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Restorasi;
use App\Models\Penjualan;

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
        'plat_nomor',
        'nama_penjual',
        'no_telp_penjual',
        'alamat_penjual',
        'harga_jual',
    ];

    public function pembelian()
    {
        return $this->hasOne(Pembelian::class);
    }

    public function restorasis()
    {
        return $this->hasMany(Restorasi::class, 'motor_id');
    }
    // alias: jika ada kode lain yang manggil "restorasi" (tanpa s)
    public function restorasi()
    {
        return $this->hasMany(Restorasi::class, 'motor_id');
    }

    public function penjualan()
    {
        return $this->hasOne(Penjualan::class);
    }
}
