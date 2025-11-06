<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('denda', function (Blueprint $table) {
            // pastikan FK peminjaman_id
            if (!Schema::hasColumn('denda', 'peminjaman_id')) {
                $table->foreignId('peminjaman_id')->constrained('peminjaman')->cascadeOnDelete();
            }

            // rename jumlah_denda -> total_denda (kalau kolom lama ada)
            if (Schema::hasColumn('denda', 'jumlah_denda') && !Schema::hasColumn('denda', 'total_denda')) {
                $table->renameColumn('jumlah_denda', 'total_denda');
            }
            // kalau tidak ada keduanya, buat baru
            if (!Schema::hasColumn('denda', 'total_denda')) {
                $table->decimal('total_denda', 12, 2)->default(0);
            }

            // jenis_denda: terlambat | hilang
            if (!Schema::hasColumn('denda', 'jenis_denda')) {
                $table->enum('jenis_denda', ['terlambat', 'hilang'])->default('terlambat')->after('peminjaman_id');
            }

            // status_pembayaran: belum_dibayar | dibayar
            if (Schema::hasColumn('denda', 'status') && !Schema::hasColumn('denda', 'status_pembayaran')) {
                $table->renameColumn('status', 'status_pembayaran');
            }
            if (!Schema::hasColumn('denda', 'status_pembayaran')) {
                $table->enum('status_pembayaran', ['belum_dibayar', 'dibayar'])->default('belum_dibayar');
            }

            // keterangan (ganti "alasan" lama kalau ada)
            if (Schema::hasColumn('denda', 'alasan') && !Schema::hasColumn('denda', 'keterangan')) {
                $table->renameColumn('alasan', 'keterangan');
            }
            if (!Schema::hasColumn('denda', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }

            // index
            $table->index(['peminjaman_id', 'jenis_denda']);
            $table->index(['status_pembayaran']);
        });
    }

    public function down(): void
    {
        Schema::table('denda', function (Blueprint $table) {
            // rollback minimal
            if (Schema::hasColumn('denda', 'keterangan')) $table->dropColumn('keterangan');
            if (Schema::hasColumn('denda', 'status_pembayaran')) $table->dropColumn('status_pembayaran');
            if (Schema::hasColumn('denda', 'jenis_denda')) $table->dropColumn('jenis_denda');
            if (Schema::hasColumn('denda', 'total_denda')) $table->dropColumn('total_denda');
        });
    }
};
