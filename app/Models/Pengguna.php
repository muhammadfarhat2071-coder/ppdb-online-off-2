<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';
    public $timestamps = true;

    protected $fillable = [
        'nama',
        'email',
        'hp',
        'password',
        'wilayah_id',
        'asal_sekolah'
    ];

    protected $guarded = [
        'role',
        'aktif',
        'last_login'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'last_login' => 'datetime',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function pendaftar()
    {
        return $this->hasOne(Pendaftar::class, 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKepsek()
    {
        return $this->role === 'kepsek';
    }

    public function isVerifikator()
    {
        return $this->role === 'verifikator_adm';
    }

    public function isKeuangan()
    {
        return $this->role === 'keuangan';
    }

    public function isPendaftar()
    {
        return $this->role === 'pendaftar';
    }
}