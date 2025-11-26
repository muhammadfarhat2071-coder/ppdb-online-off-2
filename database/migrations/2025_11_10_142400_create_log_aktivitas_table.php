<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_log_aktivitas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAktivitasTable extends Migration
{
    public function up()
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('aksi', 100);
            $table->string('objek', 100);
            $table->json('objek_data')->nullable();
            $table->datetime('waktu');
            $table->string('ip', 45);
            $table->timestamps();

            $table->index(['user_id', 'waktu']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('log_aktivitas');
    }
}