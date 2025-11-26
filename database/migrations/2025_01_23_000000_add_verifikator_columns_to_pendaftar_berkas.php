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
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            if (!Schema::hasColumn('pendaftar_berkas', 'verifikator_id')) {
                $table->unsignedBigInteger('verifikator_id')->nullable()->after('valid');
            }
            if (!Schema::hasColumn('pendaftar_berkas', 'tgl_verifikasi')) {
                $table->timestamp('tgl_verifikasi')->nullable()->after('verifikator_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            if (Schema::hasColumn('pendaftar_berkas', 'verifikator_id')) {
                $table->dropColumn('verifikator_id');
            }
            if (Schema::hasColumn('pendaftar_berkas', 'tgl_verifikasi')) {
                $table->dropColumn('tgl_verifikasi');
            }
        });
    }
};