<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();

            // 🔢 Kode penjualan otomatis, contoh: KEN2501
            $table->string('kode_penjualan')->unique()->nullable();

            // 🔗 Relasi ke motor
            $table->foreignId('motor_id')->constrained('motor')->onDelete('cascade');

            // 📅 Data penjualan
            $table->date('tanggal_jual');
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->decimal('laba', 15, 2)->default(0);

            // 👤 Data pembeli
            $table->string('nama_pembeli')->nullable();
            $table->string('no_telp_pembeli')->nullable();
            $table->text('alamat_pembeli')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};
