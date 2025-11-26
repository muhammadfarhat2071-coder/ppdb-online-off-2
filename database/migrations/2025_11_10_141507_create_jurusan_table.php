<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_jurusan_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJurusanTable extends Migration
{
    public function up()
    {
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama', 100);
            $table->integer('kuota');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jurusan');
    }
}