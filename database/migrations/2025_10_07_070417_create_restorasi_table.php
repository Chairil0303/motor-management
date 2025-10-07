<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('restorasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motor_id')->constrained('motor')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->decimal('biaya_restorasi', 15, 2)->default(0);
            $table->date('tanggal_restorasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restorasi');
    }
};
