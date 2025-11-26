<?php
// app/Models/PendaftarDataOrtu.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftarDataOrtu extends Model
{
    use HasFactory;

    protected $table = 'pendaftar_data_ortu';
    protected $primaryKey = 'pendaftar_id';
    public $incrementing = false;

    protected $fillable = [
        'pendaftar_id',
        'nama_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'hp_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'hp_ibu',
        'wali_nama',
        'wali_hp',
        'status_verifikasi'
    ];

    public function pendaftar()
    {
        return $this->belongsTo(Pendaftar::class, 'pendaftar_id');
    }
}