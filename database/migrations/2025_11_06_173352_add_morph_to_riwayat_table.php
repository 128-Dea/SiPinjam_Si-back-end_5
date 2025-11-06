<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('riwayat', function (Blueprint $table) {
            if (!Schema::hasColumn('riwayat', 'riwayatable_type')) {
                $table->nullableMorphs('riwayatable'); 
                // ini menambah: riwayatable_type (string), riwayatable_id (unsignedBigInteger, nullable) + index
            }

            // index bantu
            if (!Schema::hasColumn('riwayat', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down(): void
    {
        Schema::table('riwayat', function (Blueprint $table) {
            if (Schema::hasColumn('riwayat', 'riwayatable_type')) {
                $table->dropMorphs('riwayatable');
            }
        });
    }
};
