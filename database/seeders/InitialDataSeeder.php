<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use App\Models\Jurusan;
use App\Models\Gelombang;
use App\Models\Wilayah;

class InitialDataSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        Pengguna::create([
            'nama' => 'Admin System',
            'email' => 'admin@smkbn666.sch.id',
            'hp' => '08123456789',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'aktif' => 1
        ]);

        // Create sample jurusan
        Jurusan::create([
            'kode' => 'PPLG',
            'nama' => 'Pengembangan Perangkat Lunak dan Gim',
            'kuota' => 40
        ]);

        Jurusan::create([
            'kode' => 'TKJ',
            'nama' => 'Teknik Komputer dan Jaringan',
            'kuota' => 36
        ]);

        // Create sample gelombang
        Gelombang::create([
            'nama' => 'Gelombang 1 - Early Bird',
            'tahun' => 2025,
            'tgl_mulai' => '2025-01-01',
            'tgl_selesai' => '2025-03-31',
            'biaya_daftar' => 4000000,
            'kuota' => 50,
            'is_aktif' => 1
        ]);

        // Create sample wilayah
        Wilayah::create([
            'kode_wilayah' => 'BDG',
            'nama_wilayah' => 'Kota Bandung',
            'latitude' => -6.9175,
            'longitude' => 107.6191,
            'keterangan' => 'Wilayah Kota Bandung'
        ]);

        Wilayah::create([
            'kode_wilayah' => 'JKT',
            'nama_wilayah' => 'Jakarta',
            'latitude' => -6.2088,
            'longitude' => 106.8456,
            'keterangan' => 'Wilayah DKI Jakarta'
        ]);
    }
}