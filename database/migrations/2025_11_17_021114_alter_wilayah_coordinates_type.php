<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('wilayah', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->change();
            $table->decimal('longitude', 11, 7)->change();
        });
    }

    public function down()
    {
        Schema::table('wilayah', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->change();
            $table->decimal('longitude', 10, 8)->change();
        });
    }
};
