<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('denda', function (Blueprint $table) {
            $table->id();

            // relasi ke tabel peminjaman
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');

            // kolom utama
            $table->decimal('jumlah_denda', 10, 2);
            $table->text('alasan')->nullable();
            $table->enum('status', ['belum_dibayar', 'dibayar'])->default('belum_dibayar');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('denda');
    }
};
