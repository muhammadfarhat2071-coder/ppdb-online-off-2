<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengumuman;

class PengumumanSeeder extends Seeder
{
    public function run()
    {
        Pengumuman::create([
            'judul' => 'Jadwal Tes Masuk PPDB 2025',
            'isi' => "Tes masuk akan dilaksanakan pada:\n\nTanggal: 25 Februari 2025\nWaktu: 08.00 - 12.00 WIB\nTempat: Kampus SMK Bakti Nusantara 666\n\nHarap datang 30 menit sebelum tes dimulai.",
            'tanggal_posting' => now()->subDays(5),
            'is_aktif' => true,
            'prioritas' => 3
        ]);

        Pengumuman::create([
            'judul' => 'Batas Upload Berkas Gelombang 1',
            'isi' => "Batas akhir upload berkas untuk Gelombang 1:\n\nTanggal: 31 Januari 2025\n\nPastikan semua berkas sudah diupload sebelum batas waktu.",
            'tanggal_posting' => now()->subDays(10),
            'is_aktif' => true,
            'prioritas' => 2
        ]);

        Pengumuman::create([
            'judul' => 'Panduan Upload Berkas',
            'isi' => 'Panduan lengkap untuk upload berkas persyaratan pendaftaran dapat diunduh di website resmi sekolah.',
            'tanggal_posting' => now()->subDays(15),
            'is_aktif' => true,
            'prioritas' => 1
        ]);

        Pengumuman::create([
            'judul' => 'Informasi Biaya Pendidikan 2025/2026',
            'isi' => 'Detail biaya pendidikan untuk tahun ajaran 2025/2026 telah dipublikasikan. Silakan cek di bagian informasi biaya.',
            'tanggal_posting' => now()->subDays(20),
            'is_aktif' => true,
            'prioritas' => 0
        ]);

        Pengumuman::create([
            'judul' => 'Pembukaan PPDB 2025',
            'isi' => 'Penerimaan Peserta Didik Baru Tahun Ajaran 2025/2026 telah dibuka. Daftar sekarang!',
            'tanggal_posting' => now()->subDays(30),
            'is_aktif' => true,
            'prioritas' => 1
        ]);
    }
}