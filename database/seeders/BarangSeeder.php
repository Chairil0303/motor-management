<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\PembelianBarang;
use App\Models\PembelianDetail;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // 1️⃣ Buat beberapa kategori
        $kategoriList = ['Oli', 'Sparepart', 'Aksesoris', 'Ban', 'Lampu'];
        $kategoris = [];

        foreach ($kategoriList as $nama) {
            $kategoris[] = Kategori::create([
                'nama_kategori' => $nama
            ]);
        }

        // 2️⃣ Buat beberapa barang
        $barangs = [];
        foreach (range(1, 10) as $i) {
            $kategori = $faker->randomElement($kategoris);
            $stok = $faker->numberBetween(10, 100);
            $hargaBeli = $faker->numberBetween(50000, 500000);
            $hargaJual = $hargaBeli + $faker->numberBetween(10000, 100000);

            $barangs[] = Barang::create([
                'kode_barang' => 'BRG' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nama_barang' => $faker->word(),
                'kategori_id' => $kategori->id,
                'stok' => $stok,
                'harga_beli' => $hargaBeli,
                'harga_jual' => $hargaJual,
                'satuan' => 'pcs',
            ]);
        }

        // 3️⃣ Buat pembelian barang
        foreach (range(1, 5) as $i) {
            $pembelian = PembelianBarang::create([
                'kode_pembelian' => 'KENB' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal_pembelian' => $faker->date(),
                'total_harga' => 0, // nanti dihitung
            ]);

            $totalHarga = 0;

            // 4️⃣ Buat beberapa detail pembelian
            $detailCount = $faker->numberBetween(1, 5);
            for ($j = 0; $j < $detailCount; $j++) {
                $barang = $faker->randomElement($barangs);
                $jumlah = $faker->numberBetween(1, 10);
                $subtotal = $jumlah * $barang->harga_beli;

                PembelianDetail::create([
                    'pembelian_barang_id' => $pembelian->id,
                    'barang_id' => $barang->id,
                    'jumlah' => $jumlah,
                    'harga_satuan' => $barang->harga_beli,
                    'subtotal' => $subtotal,
                ]);

                $totalHarga += $subtotal;
            }

            // update total harga pembelian
            $pembelian->update(['total_harga' => $totalHarga]);
        }
    }
}
