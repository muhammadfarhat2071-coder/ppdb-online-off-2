<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
    
    protected $fillable = [
        'judul',
        'isi',
        'tanggal_posting',
        'is_aktif',
        'prioritas'
    ];

    protected $casts = [
        'tanggal_posting' => 'datetime',
        'is_aktif' => 'boolean'
    ];
}