<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Fix pendaftar_data_siswa table
        if (Schema::hasTable('pendaftar_data_siswa')) {
            Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
                if (!Schema::hasColumn('pendaftar_data_siswa', 'id')) {
                    $table->id()->first();
                    $table->dropPrimary();
                    $table->unique('pendaftar_id');
                }
            });
        }

        // Fix pendaftar_data_ortu table
        if (Schema::hasTable('pendaftar_data_ortu')) {
            Schema::table('pendaftar_data_ortu', function (Blueprint $table) {
                if (!Schema::hasColumn('pendaftar_data_ortu', 'id')) {
                    $table->id()->first();
                    $table->dropPrimary();
                    $table->unique('pendaftar_id');
                }
            });
        }

        // Fix pendaftar_asal_sekolah table
        if (Schema::hasTable('pendaftar_asal_sekolah')) {
            Schema::table('pendaftar_asal_sekolah', function (Blueprint $table) {
                if (!Schema::hasColumn('pendaftar_asal_sekolah', 'id')) {
                    $table->id()->first();
                    $table->dropPrimary();
                    $table->unique('pendaftar_id');
                }
            });
        }
    }

    public function down()
    {
        // Revert changes if needed
    }
};