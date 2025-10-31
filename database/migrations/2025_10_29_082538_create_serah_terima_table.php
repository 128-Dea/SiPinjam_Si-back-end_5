<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('serah_terima', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjaman_id');
            $table->unsignedBigInteger('pengguna_id');
            $table->date('tanggal_serah_terima');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('peminjaman_id')->references('id')->on('peminjaman')->onDelete('cascade');
            $table->foreign('pengguna_id')->references('id')->on('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serah_terima');
    }
};
