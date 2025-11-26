<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendaftar extends Model
{
    use HasFactory;

    protected $table = 'pendaftar';

    protected $fillable = [
        'user_id',
        'tanggal_daftar',
        'no_pendaftaran',
        'gelombang_id',
        'jurusan_id',
        'wilayah_id',
        'status',
        'user_verifikasi_adm',
        'tgl_verifikasi_adm',
        'user_verifikasi_payment',
        'tgl_verifikasi_payment',
        'nama',
        'nisn',
        'email',
        'no_hp',
        'alamat'
    ];

    public function user()
    {
        return $this->belongsTo(Pengguna::class, 'user_id');
    }

    public function gelombang()
    {
        return $this->belongsTo(Gelombang::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function dataSiswa()
    {
        return $this->hasOne(PendaftarDataSiswa::class, 'pendaftar_id');
    }

    public function dataOrtu()
    {
        return $this->hasOne(PendaftarDataOrtu::class, 'pendaftar_id');
    }

    public function asalSekolah()
    {
        return $this->hasOne(PendaftarAsalSekolah::class, 'pendaftar_id');
    }

    public function berkas()
    {
        return $this->hasMany(PendaftarBerkas::class, 'pendaftar_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'pendaftar_id');
    }
}
