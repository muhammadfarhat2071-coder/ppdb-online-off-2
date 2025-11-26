<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Pendaftar;
use App\Models\Gelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index()
    {
        // Ambil data jurusan dengan jumlah pendaftar
        $jurusan = Jurusan::leftJoin('pendaftar', 'jurusan.id', '=', 'pendaftar.jurusan_id')
            ->select('jurusan.id', 'jurusan.kode', 'jurusan.nama', 'jurusan.kuota', DB::raw('COUNT(pendaftar.id) as jumlah_pendaftar'))
            ->groupBy('jurusan.id', 'jurusan.kode', 'jurusan.nama', 'jurusan.kuota')
            ->get();
        
        // Statistik untuk hero section
        $stats = [
            'total_jurusan' => Jurusan::count(),
            'total_pendaftar' => Pendaftar::count(),
            'total_gelombang' => Gelombang::count()
        ];
        
        return view('welcome', compact('jurusan', 'stats'));
    }
}
