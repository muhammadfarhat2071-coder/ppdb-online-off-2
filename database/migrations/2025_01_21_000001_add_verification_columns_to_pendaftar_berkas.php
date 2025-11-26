<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            $table->unsignedBigInteger('verified_by')->nullable()->after('valid');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            
            $table->foreign('verified_by')->references('id')->on('pengguna')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pendaftar_berkas', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn(['verified_by', 'verified_at']);
        });
    }
};