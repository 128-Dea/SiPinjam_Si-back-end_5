<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('serah_terima', function (Blueprint $table) {
            // kolom baru untuk 2 pihak (lama & baru)
            if (!Schema::hasColumn('serah_terima', 'pengguna_lama_id')) {
                $table->foreignId('pengguna_lama_id')->nullable()->constrained('pengguna')->nullOnDelete()->after('peminjaman_id');
            }
            if (!Schema::hasColumn('serah_terima', 'pengguna_baru_id')) {
                $table->foreignId('pengguna_baru_id')->nullable()->constrained('pengguna')->nullOnDelete()->after('pengguna_lama_id');
            }

            // pastikan tanggal_serah_terima bertipe datetime (kalau masih date, ubah sendiri via DBAL jika perlu)
            // $table->dateTime('tanggal_serah_terima')->change(); // butuh doctrine/dbal bila mau change

            // path file QR
            if (!Schema::hasColumn('serah_terima', 'qr_path')) {
                $table->string('qr_path')->nullable()->after('catatan');
            }

            // index bantu
            $table->index(['pengguna_lama_id','pengguna_baru_id']);
        });
    }

    public function down(): void
    {
        Schema::table('serah_terima', function (Blueprint $table) {
            if (Schema::hasColumn('serah_terima', 'qr_path')) $table->dropColumn('qr_path');
            if (Schema::hasColumn('serah_terima', 'pengguna_baru_id')) $table->dropConstrainedForeignId('pengguna_baru_id');
            if (Schema::hasColumn('serah_terima', 'pengguna_lama_id')) $table->dropConstrainedForeignId('pengguna_lama_id');
        });
    }
};
