<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->unsignedBigInteger('barang_id')->nullable()->after('pengguna_id');
            $table->enum('role_target', ['mahasiswa', 'petugas'])->default('mahasiswa')->after('tipe');

            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
        });

        // Change enum values from 'peringatan' to 'warning'
        DB::statement("ALTER TABLE notifikasi MODIFY COLUMN tipe ENUM('info', 'warning', 'error') DEFAULT 'info'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->dropColumn(['barang_id', 'role_target']);
        });

        // Revert enum back to original (assuming 'peringatan')
        DB::statement("ALTER TABLE notifikasi MODIFY COLUMN tipe ENUM('info', 'peringatan', 'error') DEFAULT 'info'");
    }
};
