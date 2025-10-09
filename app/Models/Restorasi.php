<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restorasi extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function motor()
    {
        return $this->belongsTo(Motor::class, 'motor_id');
    }
}
