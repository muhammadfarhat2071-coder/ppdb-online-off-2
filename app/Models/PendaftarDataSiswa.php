<?php
// app/Models/PendaftarDataSiswa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarDataSiswa extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_data_siswa';
    protected $primaryKey = 'pendaftar_id';
    public $incrementing = false;

    protected $fillable = [
        'pendaftar_id',
        'nik',
        'nish',
        'nama',
        'jk',
        'tmp_lahir',
        'tgl_lahir',
        'agama',
        'alamat',
        'wilayah_id',
        'nomor_hp',
        'email',
        'lat',
        'lng',
        'status_verifikasi'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class);
    }
}