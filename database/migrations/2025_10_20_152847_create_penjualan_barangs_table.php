<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penjualan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('kode_penjualan')->unique();
            $table->dateTime('tanggal_penjualan');
            $table->decimal('total_penjualan', 15, 2)->default(0);
            $table->decimal('total_margin', 15, 2)->default(0);
            $table->decimal('harga_jasa', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penjualan_barangs');
    }
};
    