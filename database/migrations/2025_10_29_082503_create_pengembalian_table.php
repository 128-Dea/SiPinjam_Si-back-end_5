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
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('peminjaman_id');
            $table->date('tanggal_pengembalian');
            $table->text('kondisi_barang')->nullable();
            $table->decimal('denda', 10, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('peminjaman_id')->references('id')->on('peminjaman')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};
