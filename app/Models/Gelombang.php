<?php
// app/Models/Gelombang.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gelombang extends Model
{
    use HasFactory;

    protected $table = 'gelombang';

    protected $fillable = [
        'nama',
        'tahun',
        'tgl_mulai',
        'tgl_selesai',
        'biaya_daftar',
        'kuota',
        'is_aktif'
    ];

    public function pendaftar(): HasMany
    {
        return $this->hasMany(Pendaftar::class);
    }
}