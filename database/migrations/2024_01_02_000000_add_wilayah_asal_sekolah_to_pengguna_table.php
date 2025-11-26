<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->unsignedBigInteger('wilayah_id')->nullable()->after('aktif');
            $table->string('asal_sekolah')->nullable()->after('wilayah_id');
        });
    }

    public function down()
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropColumn(['wilayah_id', 'asal_sekolah']);
        });
    }
};