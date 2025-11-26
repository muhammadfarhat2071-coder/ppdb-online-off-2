<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingColumnsToPendaftarAsalSekolah extends Migration
{
    public function up()
    {
        Schema::table('pendaftar_asal_sekolah', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftar_asal_sekolah', 'status_sekolah')) {
                $table->enum('status_sekolah', ['Negeri', 'Swasta'])->default('Negeri')->after('nama_sekolah');
            }
            if (!Schema::hasColumn('pendaftar_asal_sekolah', 'alamat_sekolah')) {
                $table->text('alamat_sekolah')->nullable()->after('status_sekolah');
            }
            if (!Schema::hasColumn('pendaftar_asal_sekolah', 'tahun_lulus')) {
                $table->year('tahun_lulus')->nullable()->after('kabupaten');
            }
            if (!Schema::hasColumn('pendaftar_asal_sekolah', 'status_verifikasi')) {
                $table->tinyInteger('status_verifikasi')->default(0)->after('nilai_rata');
            }
        });
    }

    public function down()
    {
        Schema::table('pendaftar_asal_sekolah', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftar_asal_sekolah', 'status_sekolah')) {
                $table->dropColumn('status_sekolah');
            }
            if (Schema::hasColumn('pendaftar_asal_sekolah', 'alamat_sekolah')) {
                $table->dropColumn('alamat_sekolah');
            }
            if (Schema::hasColumn('pendaftar_asal_sekolah', 'tahun_lulus')) {
                $table->dropColumn('tahun_lulus');
            }
            if (Schema::hasColumn('pendaftar_asal_sekolah', 'status_verifikasi')) {
                $table->dropColumn('status_verifikasi');
            }
        });
    }
}