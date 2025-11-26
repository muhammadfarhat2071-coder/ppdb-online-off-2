<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\Pendaftar;
use App\Models\PendaftarBerkas;
use App\Models\Wilayah;
use App\Models\Gelombang;
use App\Models\Pengumuman;

class DashboardController extends Controller
{
    const ROLES = [
        'ADMIN' => 'admin',
        'KEPSEK' => 'kepsek', 
        'VERIFIKATOR' => 'verifikator_adm',
        'KEUANGAN' => 'keuangan',
        'PENDAFTAR' => 'pendaftar'
    ];

    public function admin()
    {
        $user = Auth::user();
        
        if ($user->role !== self::ROLES['ADMIN']) {
            abort(403, 'Unauthorized access.');
        }

        $stats = [
            'total_pendaftar' => Pendaftar::count(),
            'lulus_administrasi' => Pendaftar::where('status', 'Terverifikasi')->count(),
            'menunggu_verifikasi' => Pendaftar::where('status', 'Menunggu')->count(),
            'sudah_bayar' => 0,
            'total_jurusan' => \App\Models\Jurusan::count(),
            'total_gelombang' => Gelombang::count()
        ];

        return view('dashboard-admin', compact('user', 'stats'));
    }

    public function kepsek()
    {
        $user = Auth::user();
        
        if ($user->role !== self::ROLES['KEPSEK']) {
            abort(403, 'Unauthorized access.');
        }

        $stats = [
            'total_pendaftar' => 165,
            'laki_laki' => 85,
            'perempuan' => 80,
            'terverifikasi' => 132,
            'pending' => 25,
            'ditolak' => 8
        ];

        return view('dashboard-kepsek', compact('user', 'stats'));
    }

    public function verifikator()
    {
        $user = Auth::user();
        
        if ($user->role !== self::ROLES['VERIFIKATOR']) {
            abort(403, 'Unauthorized access.');
        }

        $menungguVerifikasi = Pendaftar::where('status', 'Menunggu')->count();
        $terverifikasi = Pendaftar::where('status', 'Terverifikasi')->count();
        $ditolak = Pendaftar::where('status', 'Ditolak')->count();
        
        try {
            $berkasMenunggu = PendaftarBerkas::with(['pendaftar.user', 'pendaftar.jurusan'])
                ->where(function($query) {
                    $query->where('valid', 0)->orWhereNull('valid');
                })
                ->orderBy('created_at', 'asc')
                ->get();
                
            Log::info('Berkas menunggu loaded:', [
                'count' => $berkasMenunggu->count(),
                'berkas_ids' => $berkasMenunggu->pluck('id')->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading berkas menunggu: ' . $e->getMessage());
            $berkasMenunggu = collect();
        }

        $stats = [
            'menunggu_verifikasi' => $menungguVerifikasi,
            'terverifikasi' => $terverifikasi,
            'ditolak' => $ditolak
        ];

        return view('dashboard-verifikator', compact('user', 'stats', 'berkasMenunggu'));
    }

    public function keuangan()
    {
        $user = Auth::user();
        
        if ($user->role !== self::ROLES['KEUANGAN']) {
            abort(403, 'Unauthorized access.');
        }

        $stats = [
            'total_pembayaran' => 120,
            'pending_verifikasi' => 15,
            'ditolak' => 5,
            'total_nominal' => '480.000.000'
        ];

        return view('dashboard-keuangan', compact('user', 'stats'));
    }

    public function pendaftar()
    {
        $user = Auth::user();
        
        if ($user->role !== self::ROLES['PENDAFTAR']) {
            abort(403, 'Unauthorized access.');
        }

        // Load pendaftar dengan semua relasi yang diperlukan
        $pendaftar = Pendaftar::with([
            'gelombang', 
            'jurusan', 
            'berkas', 
            'dataSiswa', 
            'dataOrtu', 
            'asalSekolah',
            'pembayaran' => function($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->where('user_id', $user->id)->first();

        $wilayah = Wilayah::all();
        $gelombang = Gelombang::where('is_aktif', 1)->first();
        $berkasCount = $pendaftar ? $pendaftar->berkas->count() : 0;
        $biayaPendaftaran = $gelombang ? $gelombang->biaya_daftar : 150000;

        // Debug information
        \Log::info('Dashboard Pendaftar Data:', [
            'user_id' => $user->id,
            'pendaftar_exists' => $pendaftar ? true : false,
            'berkas_count' => $berkasCount,
            'has_data_siswa' => $pendaftar && $pendaftar->dataSiswa ? true : false,
            'has_data_ortu' => $pendaftar && $pendaftar->dataOrtu ? true : false,
            'has_asal_sekolah' => $pendaftar && $pendaftar->asalSekolah ? true : false,
            'pembayaran_count' => $pendaftar ? $pendaftar->pembayaran->count() : 0
        ]);

        $pengumuman = collect();
        try {
            if (Schema::hasTable('pengumuman')) {
                $pengumuman = Pengumuman::where('is_aktif', true)
                    ->orderBy('prioritas', 'desc')
                    ->orderBy('tanggal_posting', 'desc')
                    ->get();
            }
        } catch (\Exception $e) {
            $pengumuman = collect();
        }

        return view('dashboard-pendaftar', compact(
            'user', 'pendaftar', 'wilayah', 'gelombang', 
            'berkasCount', 'biayaPendaftaran', 'pengumuman'
        ));
    }
}