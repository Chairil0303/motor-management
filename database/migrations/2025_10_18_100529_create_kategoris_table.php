<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kategoris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori')->unique();
            $table->timestamps();
        });

        // ubah tabel barangs, ganti kolom kategori string -> relasi kategori_id
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropColumn('kategori'); // hapus kolom lama
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
            $table->string('kategori')->nullable(); // rollback ke versi lama
        });

        Schema::dropIfExists('kategoris');
    }
};
