<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restorasis', function (Blueprint $table) {
            $table->id();

            // Relasi ke motor
            $table->foreignId('motor_id')
                ->constrained('motor')
                ->onDelete('cascade');

            // Deskripsi pekerjaan restorasi
            $table->text('deskripsi')->nullable();

            // Tanggal restorasi dilakukan
            $table->date('tanggal_restorasi')->nullable();

            // Biaya restorasi (wajib)
            $table->decimal('biaya_restorasi', 15, 2)->default(0);

            // Kalau suatu saat ada status restorasi (opsional)
            $table->enum('status', ['proses', 'selesai'])->default('selesai');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restorasis');
    }
};
