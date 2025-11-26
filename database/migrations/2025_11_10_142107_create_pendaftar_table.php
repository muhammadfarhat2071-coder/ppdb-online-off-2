<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_pendaftar_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftarTable extends Migration
{
    public function up()
    {
        Schema::create('pendaftar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->datetime('tanggal_daftar');
            $table->string('no_pendaftaran', 20)->unique();
            $table->unsignedBigInteger('gelombang_id');
            $table->unsignedBigInteger('jurusan_id');
            $table->enum('status', ['SUBMIT', 'ADM_PASS', 'ADM_REJECT', 'PAID', 'LULUS', 'TIDAK_LULUS', 'CADANGAN']);
            $table->string('user_verifikasi_adm', 100)->nullable();
            $table->datetime('tgl_verifikasi_adm')->nullable();
            $table->string('user_verifikasi_payment', 100)->nullable();
            $table->datetime('tgl_verifikasi_payment')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('pengguna');
            $table->foreign('gelombang_id')->references('id')->on('gelombang');
            $table->foreign('jurusan_id')->references('id')->on('jurusan');
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftar');
    }
}