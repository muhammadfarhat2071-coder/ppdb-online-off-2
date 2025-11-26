<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            // Pastikan kolom yang diperlukan ada
            if (!Schema::hasColumn('pembayaran', 'user_verifikasi')) {
                $table->unsignedBigInteger('user_verifikasi')->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('pembayaran', 'tanggal_konfirmasi')) {
                $table->timestamp('tanggal_konfirmasi')->nullable()->after('tanggal_bayar');
            }
        });

        // Update status pembayaran yang ada menjadi format yang benar
        DB::table('pembayaran')
            ->where('status', 'Menunggu')
            ->update(['status' => 'Menunggu Konfirmasi']);
    }

    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            if (Schema::hasColumn('pembayaran', 'user_verifikasi')) {
                $table->dropColumn('user_verifikasi');
            }
            if (Schema::hasColumn('pembayaran', 'tanggal_konfirmasi')) {
                $table->dropColumn('tanggal_konfirmasi');
            }
        });
    }
};