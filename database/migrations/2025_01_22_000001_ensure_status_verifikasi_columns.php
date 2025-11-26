<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Pastikan kolom status_verifikasi ada di tabel pendaftar_data_siswa
        if (!Schema::hasColumn('pendaftar_data_siswa', 'status_verifikasi')) {
            Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
                $table->tinyInteger('status_verifikasi')->default(0)->comment('0=menunggu, 1=terima, 2=tolak');
            });
        }

        // Pastikan kolom status_verifikasi ada di tabel pendaftar_data_ortu
        if (!Schema::hasColumn('pendaftar_data_ortu', 'status_verifikasi')) {
            Schema::table('pendaftar_data_ortu', function (Blueprint $table) {
                $table->tinyInteger('status_verifikasi')->default(0)->comment('0=menunggu, 1=terima, 2=tolak');
            });
        }

        // Pastikan kolom status_verifikasi ada di tabel pendaftar_asal_sekolah
        if (!Schema::hasColumn('pendaftar_asal_sekolah', 'status_verifikasi')) {
            Schema::table('pendaftar_asal_sekolah', function (Blueprint $table) {
                $table->tinyInteger('status_verifikasi')->default(0)->comment('0=menunggu, 1=terima, 2=tolak');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('pendaftar_data_siswa', 'status_verifikasi')) {
            Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
                $table->dropColumn('status_verifikasi');
            });
        }

        if (Schema::hasColumn('pendaftar_data_ortu', 'status_verifikasi')) {
            Schema::table('pendaftar_data_ortu', function (Blueprint $table) {
                $table->dropColumn('status_verifikasi');
            });
        }

        if (Schema::hasColumn('pendaftar_asal_sekolah', 'status_verifikasi')) {
            Schema::table('pendaftar_asal_sekolah', function (Blueprint $table) {
                $table->dropColumn('status_verifikasi');
            });
        }
    }
};