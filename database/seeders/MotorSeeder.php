<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Motor;

class MotorSeeder extends Seeder
{
    public function run(): void
    {
        $dataMotor = [
            ['merek' => 'Honda', 'tipe_model' => 'Beat Street', 'tahun' => 2021, 'harga_beli' => 13000000, 'harga_jual' => 15000000],
            ['merek' => 'Yamaha', 'tipe_model' => 'NMAX 155', 'tahun' => 2020, 'harga_beli' => 21000000, 'harga_jual' => 24000000],
            ['merek' => 'Suzuki', 'tipe_model' => 'Satria FU 150', 'tahun' => 2019, 'harga_beli' => 14500000, 'harga_jual' => 17000000],
            ['merek' => 'Kawasaki', 'tipe_model' => 'W175', 'tahun' => 2022, 'harga_beli' => 28000000, 'harga_jual' => 31000000],
            ['merek' => 'Vespa', 'tipe_model' => 'Primavera 150', 'tahun' => 2021, 'harga_beli' => 42000000, 'harga_jual' => 47000000],
            ['merek' => 'Honda', 'tipe_model' => 'CBR 150R', 'tahun' => 2020, 'harga_beli' => 27000000, 'harga_jual' => 29500000],
            ['merek' => 'Yamaha', 'tipe_model' => 'Aerox 155', 'tahun' => 2021, 'harga_beli' => 24000000, 'harga_jual' => 27000000],
            ['merek' => 'Honda', 'tipe_model' => 'Vario 125', 'tahun' => 2022, 'harga_beli' => 19000000, 'harga_jual' => 21500000],
            ['merek' => 'Suzuki', 'tipe_model' => 'Nex II', 'tahun' => 2020, 'harga_beli' => 11000000, 'harga_jual' => 13000000],
            ['merek' => 'Yamaha', 'tipe_model' => 'Mio M3', 'tahun' => 2019, 'harga_beli' => 10000000, 'harga_jual' => 12000000],
            ['merek' => 'Honda', 'tipe_model' => 'PCX 160', 'tahun' => 2022, 'harga_beli' => 32000000, 'harga_jual' => 35000000],
            ['merek' => 'Kawasaki', 'tipe_model' => 'KLX 150', 'tahun' => 2021, 'harga_beli' => 27000000, 'harga_jual' => 29500000],
            ['merek' => 'Yamaha', 'tipe_model' => 'R15 V3', 'tahun' => 2021, 'harga_beli' => 31000000, 'harga_jual' => 34000000],
            ['merek' => 'Honda', 'tipe_model' => 'Scoopy', 'tahun' => 2020, 'harga_beli' => 17000000, 'harga_jual' => 19000000],
            ['merek' => 'Vespa', 'tipe_model' => 'Sprint 150', 'tahun' => 2022, 'harga_beli' => 46000000, 'harga_jual' => 49500000],
            ['merek' => 'Suzuki', 'tipe_model' => 'Address 110', 'tahun' => 2019, 'harga_beli' => 10000000, 'harga_jual' => 12000000],
            ['merek' => 'Honda', 'tipe_model' => 'Revo Fit', 'tahun' => 2021, 'harga_beli' => 12000000, 'harga_jual' => 13500000],
            ['merek' => 'Yamaha', 'tipe_model' => 'Fazzio Hybrid', 'tahun' => 2023, 'harga_beli' => 25000000, 'harga_jual' => 28000000],
            ['merek' => 'Honda', 'tipe_model' => 'Genio 110', 'tahun' => 2020, 'harga_beli' => 15000000, 'harga_jual' => 17000000],
            ['merek' => 'Kawasaki', 'tipe_model' => 'ZX-25R', 'tahun' => 2021, 'harga_beli' => 87000000, 'harga_jual' => 92000000],
            ['merek' => 'Yamaha', 'tipe_model' => 'XSR 155', 'tahun' => 2022, 'harga_beli' => 34000000, 'harga_jual' => 37000000],
            ['merek' => 'Honda', 'tipe_model' => 'CRF 150L', 'tahun' => 2021, 'harga_beli' => 29000000, 'harga_jual' => 31500000],
            ['merek' => 'Vespa', 'tipe_model' => 'GTS 250', 'tahun' => 2020, 'harga_beli' => 65000000, 'harga_jual' => 69000000],
            ['merek' => 'Suzuki', 'tipe_model' => 'Burgman 200', 'tahun' => 2019, 'harga_beli' => 36000000, 'harga_jual' => 39000000],
            ['merek' => 'Yamaha', 'tipe_model' => 'MT-15', 'tahun' => 2020, 'harga_beli' => 32000000, 'harga_jual' => 34500000],
        ];

        foreach ($dataMotor as $index => $item) {
            Motor::create([
                'merek' => $item['merek'],
                'tipe_model' => $item['tipe_model'],
                'tahun' => $item['tahun'],
                'harga_beli' => $item['harga_beli'],
                'harga_jual' => $item['harga_jual'],
                'plat_nomor' => strtoupper(Str::random(2)) . ' ' . rand(1000, 9999) . ' ' . strtoupper(Str::random(2)),
                'nama_penjual' => fake()->name(),
                'no_telp_penjual' => '08' . rand(1000000000, 9999999999),
                'alamat_penjual' => fake()->address(),
                'kondisi' => fake()->randomElement(['baik', 'sangat baik', 'perlu servis ringan']),
                'status' => $index < 20 ? 'tersedia' : 'terjual',
            ]);
        }
    }
}
