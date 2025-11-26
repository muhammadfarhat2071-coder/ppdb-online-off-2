<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_gelombang_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGelombangTable extends Migration
{
    public function up()
    {
        Schema::create('gelombang', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->integer('tahun');
            $table->date('tgl_mulai');
            $table->date('tgl_selesai');
            $table->decimal('biaya_daftar', 12, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gelombang');
    }
}