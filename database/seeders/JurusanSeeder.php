<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanSeeder extends Seeder
{
    public function run()
    {
        DB::table('jurusan')->insert([
            [
                'kode' => 'PPLG',
                'nama' => 'Pengembangan Perangkat Lunak dan Gim',
                'kuota' => 40,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'AK',
                'nama' => 'Akuntansi',
                'kuota' => 35,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'DKV',
                'nama' => 'Desain Komunikasi Visual',
                'kuota' => 30,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'PM',
                'nama' => 'Pemasaran',
                'kuota' => 35,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kode' => 'ANM',
                'nama' => 'Animasi',
                'kuota' => 25,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}