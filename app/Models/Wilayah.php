<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;

    protected $table = 'wilayah';
    protected $fillable = ['kode_wilayah', 'nama_wilayah', 'kecamatan', 'desa', 'latitude', 'longitude', 'keterangan'];

    public function pendaftar()
    {
        return $this->hasMany(Pendaftar::class, 'wilayah_id');
    }
}
