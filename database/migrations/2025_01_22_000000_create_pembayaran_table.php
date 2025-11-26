<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi')->unique();
            $table->foreignId('pendaftar_id')->constrained('pendaftar')->onDelete('cascade');
            $table->decimal('jumlah', 12, 2);
            $table->enum('metode', ['Transfer Bank', 'Tunai', 'Virtual Account', 'QRIS']);
            $table->string('bukti_transfer')->nullable();
            $table->enum('status', ['Menunggu Konfirmasi', 'Dikonfirmasi', 'Ditolak'])->default('Menunggu Konfirmasi');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_verifikasi')->nullable()->constrained('pengguna')->onDelete('set null');
            $table->timestamp('tanggal_bayar');
            $table->timestamp('tanggal_konfirmasi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
};