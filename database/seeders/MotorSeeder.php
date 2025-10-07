<?php

namespace Database\Seeders;

use App\Models\Motor;
use Illuminate\Database\Seeder;

class MotorSeeder extends Seeder
{
    public function run(): void
    {
        Motor::create([
            'merek' => 'Yamaha',
            'tipe_model' => 'NMAX 155',
            'tahun' => 2022,
            'harga_beli' => 21000000,
            'kondisi' => 'Bekas mulus',
            'status' => 'tersedia',
        ]);

        Motor::create([
            'merek' => 'Honda',
            'tipe_model' => 'CBR 150R',
            'tahun' => 2021,
            'harga_beli' => 25000000,
            'kondisi' => 'Bekas',
            'status' => 'tersedia',
        ]);
    }
}
