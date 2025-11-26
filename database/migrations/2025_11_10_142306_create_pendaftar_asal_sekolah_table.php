<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_pendaftar_asal_sekolah_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftarAsalSekolahTable extends Migration
{
    public function up()
    {
        Schema::create('pendaftar_asal_sekolah', function (Blueprint $table) {
            $table->unsignedBigInteger('pendaftar_id')->primary();
            $table->string('npsn', 20);
            $table->string('nama_sekolah', 150);
            $table->enum('status_sekolah', ['Negeri', 'Swasta'])->default('Negeri');
            $table->text('alamat_sekolah')->nullable();
            $table->string('kabupaten', 100);
            $table->year('tahun_lulus')->nullable();
            $table->decimal('nilai_rata', 5, 2)->nullable();
            $table->tinyInteger('status_verifikasi')->default(0);
            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftar');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftar_asal_sekolah');
    }
}