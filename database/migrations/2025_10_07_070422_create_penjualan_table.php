<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motor_id')->constrained('motor')->onDelete('cascade');
            $table->date('tanggal_jual');
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('total_biaya', 15, 2)->default(0);
            $table->decimal('laba', 15, 2)->default(0);

            // 🔥 Tambahan kolom pembeli
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
