<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // ubah ke datetime
            $table->dateTime('tanggal_pinjam')->change();
            $table->dateTime('tanggal_kembali')->change();
            $table->dateTime('tanggal_dikembalikan')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->date('tanggal_pinjam')->change();
            $table->date('tanggal_kembali')->change();
            $table->date('tanggal_dikembalikan')->nullable()->change();
        });
    }
};
