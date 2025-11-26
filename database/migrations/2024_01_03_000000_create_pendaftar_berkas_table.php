<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pendaftar_berkas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('jenis_berkas');
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('status')->default('menunggu'); // menunggu, terverifikasi, ditolak
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('pengguna')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftar_berkas');
    }
};