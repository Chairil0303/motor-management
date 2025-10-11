<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('motor', function (Blueprint $table) {
            $table->id();
            $table->string('merek');
            $table->string('tipe_model');
            $table->year('tahun');
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->string('plat_nomor')->unique();
            $table->string('nama_penjual');
            $table->string('no_telp_penjual');
            $table->text('alamat_penjual');
            $table->string('kondisi')->nullable();

            // 🔥 ENUM untuk status: hanya bisa 'tersedia' atau 'terjual'
            $table->enum('status', ['tersedia', 'terjual'])->default('tersedia');

            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('motor');
    }
};
