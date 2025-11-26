<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_pendaftar_data_ortu_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendaftarDataOrtuTable extends Migration
{
    public function up()
    {
        Schema::create('pendaftar_data_ortu', function (Blueprint $table) {
            $table->unsignedBigInteger('pendaftar_id')->primary();
            $table->string('nama_ayah', 120);
            $table->string('pekerjaan_ayah', 100);
            $table->string('hp_ayah', 20);
            $table->string('nama_ibu', 120);
            $table->string('pekerjaan_ibu', 100);
            $table->string('hp_ibu', 20);
            $table->string('wali_nama', 120)->nullable();
            $table->string('wali_hp', 20)->nullable();
            $table->timestamps();

            $table->foreign('pendaftar_id')->references('id')->on('pendaftar');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pendaftar_data_ortu');
    }
}