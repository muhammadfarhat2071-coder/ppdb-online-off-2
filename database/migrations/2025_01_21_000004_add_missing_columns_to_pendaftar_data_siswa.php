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
        Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftar_data_siswa', 'agama')) {
                $table->string('agama', 50)->nullable();
            }
            if (!Schema::hasColumn('pendaftar_data_siswa', 'nomor_hp')) {
                $table->string('nomor_hp', 20)->nullable();
            }
            if (!Schema::hasColumn('pendaftar_data_siswa', 'email')) {
                $table->string('email', 100)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar_data_siswa', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftar_data_siswa', 'agama')) {
                $table->dropColumn('agama');
            }
            if (Schema::hasColumn('pendaftar_data_siswa', 'nomor_hp')) {
                $table->dropColumn('nomor_hp');
            }
            if (Schema::hasColumn('pendaftar_data_siswa', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};