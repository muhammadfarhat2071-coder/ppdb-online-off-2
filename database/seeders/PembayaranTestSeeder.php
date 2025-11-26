<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;
use App\Models\Pendaftar;

class PembayaranTestSeeder extends Seeder
{
    public function run()
    {
        // Ambil pendaftar yang ada
        $pendaftar = Pendaftar::first();
        
        if ($pendaftar) {
            // Buat data pembayaran test
            Pembayaran::create([
                'no_transaksi' => 'TRX2025000001',
                'pendaftar_id' => $pendaftar->id,
                'jumlah' => 4500000,
                'metode' => 'Transfer Bank',
                'tanggal_bayar' => now(),
                'status' => 'Menunggu Konfirmasi'
            ]);

            Pembayaran::create([
                'no_transaksi' => 'TRX2025000002',
                'pendaftar_id' => $pendaftar->id,
                'jumlah' => 4000000,
                'metode' => 'QRIS',
                'tanggal_bayar' => now()->subDays(1),
                'status' => 'Dikonfirmasi',
                'tanggal_konfirmasi' => now()->subHours(2)
            ]);
        }
    }
}