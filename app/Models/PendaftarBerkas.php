<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftarBerkas extends Model
{
    protected $table = 'pendaftar_berkas';
    
    protected $fillable = [
        'pendaftar_id',
        'jenis',
        'nama_file',
        'url',
        'ukuran_kb',
        'catatan'
    ];

    protected $guarded = [
        'valid'
    ];
    


    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }
}