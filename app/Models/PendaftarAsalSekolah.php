<?php
// app/Models/PendaftarAsalSekolah.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarAsalSekolah extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_asal_sekolah';
    protected $primaryKey = 'pendaftar_id';
    public $incrementing = false;

    protected $fillable = [
        'pendaftar_id',
        'npsn',
        'nama_sekolah',
        'status_sekolah',
        'alamat_sekolah',
        'kabupaten',
        'tahun_lulus',
        'nilai_rata',
        'status_verifikasi'
    ];

    protected $casts = [
        'nilai_rata' => 'decimal:2'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }
}