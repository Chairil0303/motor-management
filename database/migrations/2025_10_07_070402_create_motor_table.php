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
            $table->string('plat_nomor')->unique(); // ✅ plat nomor
            $table->string('nama_penjual');         // ✅ nama penjual
            $table->string('no_telp_penjual');      // ✅ no telp penjual
            $table->text('alamat_penjual');         // ✅ alamat penjual
            $table->string('kondisi')->nullable();
            $table->string('status')->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('motor');
    }
};
