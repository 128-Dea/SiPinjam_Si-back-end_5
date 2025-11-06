<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('keluhan', function (Blueprint $table) {
            // jika kolom belum ada
            if (!Schema::hasColumn('keluhan', 'peminjaman_id')) {
                $table->unsignedBigInteger('peminjaman_id')->after('pengguna_id')->nullable();

                // asumsi nama tabel peminjaman = 'peminjaman'
                $table->foreign('peminjaman_id')
                    ->references('id')->on('peminjaman')
                    ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('keluhan', function (Blueprint $table) {
            if (Schema::hasColumn('keluhan', 'peminjaman_id')) {
                $table->dropForeign(['peminjaman_id']);
                $table->dropColumn('peminjaman_id');
            }
        });
    }
};
