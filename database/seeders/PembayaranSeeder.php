<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pembayaran;
use App\Models\Pendaftar;

class PembayaranSeeder extends Seeder
{
    public function run()
    {
        $pendaftar = Pendaftar::first();
        
        if ($pendaftar) {
            Pembayaran::create([
                'no_transaksi' => 'TRX2025000001',
                'pendaftar_id' => $pendaftar->id,
                'jumlah' => 4500000,
                'metode' => 'Transfer Bank',
                'status' => 'Menunggu Konfirmasi',
                'tanggal_bayar' => now(),
            ]);
        }
    }
}