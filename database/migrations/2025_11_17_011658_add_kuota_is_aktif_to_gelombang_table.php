<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gelombang', function (Blueprint $table) {
            $table->integer('kuota')->default(0)->after('biaya_daftar');
            $table->boolean('is_aktif')->default(1)->after('kuota');
        });
    }

    public function down()
    {
        Schema::table('gelombang', function (Blueprint $table) {
            $table->dropColumn(['kuota', 'is_aktif']);
        });
    }
};
