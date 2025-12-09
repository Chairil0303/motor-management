<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riwayat_belanjas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_belanja')->unique();
            $table->foreignId('barang_id')->constrained('barangs')->onDelete('cascade');
            $table->date('tanggal_belanja');
            $table->integer('kuantiti');
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('total_belanja', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_belanjas');
    }
};
