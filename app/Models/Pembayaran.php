<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';
    
    protected $fillable = [
        'no_transaksi',
        'pendaftar_id',
        'jumlah',
        'metode',
        'bukti_transfer',
        'status',
        'keterangan',
        'user_verifikasi',
        'tanggal_bayar',
        'tanggal_konfirmasi'
    ];

    protected $casts = [
        'tanggal_bayar' => 'datetime',
        'tanggal_konfirmasi' => 'datetime',
        'jumlah' => 'decimal:2'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(Pengguna::class, 'user_verifikasi');
    }
}