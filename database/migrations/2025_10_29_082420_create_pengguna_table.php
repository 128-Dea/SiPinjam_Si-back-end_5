<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePenggunaTable extends Migration
{
    public function up(): void
    {
       
        if (!Schema::hasTable('pengguna')) {
            Schema::create('pengguna', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('role')->default('user');
                $table->timestamps(); 
            });
        } else {
            Schema::table('pengguna', function (Blueprint $table) {
                if (Schema::hasColumn('pengguna', 'kata_sandi')) {
                    $table->renameColumn('kata_sandi', 'password');
                }
                if (Schema::hasColumn('pengguna', 'dibuat_pada')) {
                    $table->renameColumn('dibuat_pada', 'created_at');
                }
                if (Schema::hasColumn('pengguna', 'diperbarui_pada')) {
                    $table->renameColumn('diperbarui_pada', 'updated_at');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
}
