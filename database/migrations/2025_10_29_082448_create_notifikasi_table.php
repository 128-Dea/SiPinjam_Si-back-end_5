<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel notifikasi.
     */
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengguna_id'); // relasi ke tabel pengguna
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['info', 'peringatan', 'error'])->default('info');
            $table->boolean('dibaca')->default(false);
            $table->timestamps();

            // relasi foreign key
            $table->foreign('pengguna_id')
                  ->references('id')
                  ->on('pengguna')
                  ->onDelete('cascade');
        });
    }

    /**
     * Balikkan migration (hapus tabel notifikasi).
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
