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
        // Fix pendaftar_data_siswa table
        if (Schema::hasTable('pendaftar_data_siswa')) {
            if (!Schema::hasColumn('pendaftar_data_siswa', 'id')) {
                Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
                    $table->id()->first();
                });
            }
        }

        // Fix pendaftar_data_ortu table
        if (Schema::hasTable('pendaftar_data_ortu')) {
            if (!Schema::hasColumn('pendaftar_data_ortu', 'id')) {
                Schema::table('pendaftar_data_ortu', function (Blueprint $table) {
                    $table->id()->first();
                });
            }
        }

        // Fix pendaftar_asal_sekolah table
        if (Schema::hasTable('pendaftar_asal_sekolah')) {
            if (!Schema::hasColumn('pendaftar_asal_sekolah', 'id')) {
                Schema::table('pendaftar_asal_sekolah', function (Blueprint $table) {
                    $table->id()->first();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove id columns if they were added
        if (Schema::hasTable('pendaftar_data_siswa')) {
            if (Schema::hasColumn('pendaftar_data_siswa', 'id')) {
                Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
                    $table->dropColumn('id');
                });
            }
        }

        if (Schema::hasTable('pendaftar_data_ortu')) {
            if (Schema::hasColumn('pendaftar_data_ortu', 'id')) {
                Schema::table('pendaftar_data_ortu', function (Blueprint $table) {
                    $table->dropColumn('id');
                });
            }
        }

        if (Schema::hasTable('pendaftar_asal_sekolah')) {
            if (Schema::hasColumn('pendaftar_asal_sekolah', 'id')) {
                Schema::table('pendaftar_asal_sekolah', function (Blueprint $table) {
                    $table->dropColumn('id');
                });
            }
        }
    }
};