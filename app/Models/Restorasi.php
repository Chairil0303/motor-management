<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restorasi extends Model
{
    protected $table = 'restorasi';
    protected $fillable = ['motor_id', 'deskripsi', 'biaya_restorasi', 'tanggal_restorasi'];

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }
}
