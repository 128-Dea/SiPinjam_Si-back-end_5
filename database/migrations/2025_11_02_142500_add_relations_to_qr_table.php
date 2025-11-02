<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr', function (Blueprint $table) {
            $table->unsignedBigInteger('peminjaman_id')->nullable()->after('barang_id');
            $table->unsignedBigInteger('serah_terima_id')->nullable()->after('peminjaman_id');
            $table->string('tipe')->default('barang')->after('serah_terima_id');
        });
    }

    public function down(): void
    {
        Schema::table('qr', function (Blueprint $table) {
            $table->dropColumn(['peminjaman_id', 'serah_terima_id', 'tipe']);
        });
    }
};
