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
        // Add status_verifikasi to pendaftar_data_siswa table
        if (Schema::hasTable('pendaftar_data_siswa')) {
            Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
                if (!Schema::hasColumn('pendaftar_data_siswa', 'status_verifikasi')) {
                    $table->tinyInteger('status_verifikasi')->default(0)->comment('0=menunggu verifikasi, 1=terverifikasi, 2=ditolak');
                }
            });
        }

        // Add status_verifikasi to pendaftar_data_ortu table
        if (Schema::hasTable('pendaftar_data_ortu')) {
            Schema::table('pendaftar_data_ortu', function (Blueprint $table) {
                if (!Schema::hasColumn('pendaftar_data_ortu', 'status_verifikasi')) {
                    $table->tinyInteger('status_verifikasi')->default(0)->comment('0=menunggu verifikasi, 1=terverifikasi, 2=ditolak');
                }
            });
        }

        // Add status_verifikasi to pendaftar_asal_sekolah table
        if (Schema::hasTable('pendaftar_asal_sekolah')) {
            Schema::table('pendaftar_asal_sekolah', function (Blueprint $table) {
                if (!Schema::hasColumn('pendaftar_asal_sekolah', 'status_verifikasi')) {
                    $table->tinyInteger('status_verifikasi')->default(0)->comment('0=menunggu verifikasi, 1=terverifikasi, 2=ditolak');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove status_verifikasi from pendaftar_data_siswa table
        if (Schema::hasTable('pendaftar_data_siswa')) {
            Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
                if (Schema::hasColumn('pendaftar_data_siswa', 'status_verifikasi')) {
                    $table->dropColumn('status_verifikasi');
                }
            });
        }

        // Remove status_verifikasi from pendaftar_data_ortu table
        if (Schema::hasTable('pendaftar_data_ortu')) {
            Schema::table('pendaftar_data_ortu', function (Blueprint $table) {
                if (Schema::hasColumn('pendaftar_data_ortu', 'status_verifikasi')) {
                    $table->dropColumn('status_verifikasi');
                }
            });
        }

        // Remove status_verifikasi from pendaftar_asal_sekolah table
        if (Schema::hasTable('pendaftar_asal_sekolah')) {
            Schema::table('pendaftar_asal_sekolah', function (Blueprint $table) {
                if (Schema::hasColumn('pendaftar_asal_sekolah', 'status_verifikasi')) {
                    $table->dropColumn('status_verifikasi');
                }
            });
        }
    }
};