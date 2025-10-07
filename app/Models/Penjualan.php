<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $fillable = [
        'motor_id',
        'pelanggan_id',
        'tanggal_jual',
        'harga_jual',
        'total_biaya',
        'laba',
    ];

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }
}
