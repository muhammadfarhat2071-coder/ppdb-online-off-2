<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LaporanTestController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'pendaftar_hari_ini' => 0,
                'total_pendaftar' => 0,
                'pendaftar_bulan_ini' => 0,
                'statistik_jurusan' => [],
                'statistik_wilayah' => [],
                'statistik_status' => [
                    (object)['status' => 'Menunggu', 'total' => 0],
                    (object)['status' => 'Terverifikasi', 'total' => 0],
                    (object)['status' => 'Ditolak', 'total' => 0]
                ],
                'statistik_gelombang' => []
            ],
            'message' => 'Data laporan berhasil dimuat (test mode)'
        ]);
    }
}