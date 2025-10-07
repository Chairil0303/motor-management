<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembelian extends Model
{
    protected $table = 'pembelian';
    protected $fillable = ['motor_id', 'tanggal_beli', 'biaya_beli'];

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }
}
