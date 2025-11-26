<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePendaftarBerkasJenisEnum extends Migration
{
    public function up()
    {
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
        
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            $table->enum('jenis', ['KTP', 'IJAZAH', 'RAPOR', 'FOTO', 'SEHAT'])->after('pendaftar_id');
        });
    }

    public function down()
    {
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
        
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            $table->enum('jenis', ['IJAZAH', 'RAPOR', 'KIP', 'KKS', 'AKTA', 'KK', 'LAINNYA'])->after('pendaftar_id');
        });
    }
}