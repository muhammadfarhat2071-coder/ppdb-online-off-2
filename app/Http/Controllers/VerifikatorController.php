<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\PendaftarDataSiswa;
use App\Models\PendaftarDataOrtu;
use App\Models\PendaftarAsalSekolah;
use App\Models\PendaftarBerkas;

class VerifikatorController extends Controller
{
    public function getDataMenunggu()
    {
        try {
            $dataSiswa = PendaftarDataSiswa::with(['pendaftar.user', 'pendaftar.jurusan'])
                ->whereIn('status_verifikasi', [0, null])
                ->orderBy('created_at', 'asc')
                ->get();
            
            $dataOrtu = PendaftarDataOrtu::with(['pendaftar.user', 'pendaftar.jurusan'])
                ->whereIn('status_verifikasi', [0, null])
                ->orderBy('created_at', 'asc')
                ->get();
            
            $asalSekolah = PendaftarAsalSekolah::with(['pendaftar.user', 'pendaftar.jurusan'])
                ->whereIn('status_verifikasi', [0, null])
                ->orderBy('created_at', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'data_siswa' => $dataSiswa,
                    'data_ortu' => $dataOrtu,
                    'asal_sekolah' => $asalSekolah
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading data menunggu: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data'
            ], 500);
        }
    }

    public function getDetailDataSiswa($id)
    {
        try {
            $data = PendaftarDataSiswa::with(['pendaftar.user', 'pendaftar.jurusan'])->find($id);
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading student detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail data siswa'
            ], 500);
        }
    }

    public function getDetailDataOrtu($id)
    {
        try {
            $data = PendaftarDataOrtu::with(['pendaftar.user', 'pendaftar.jurusan'])->find($id);
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data orang tua tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading parent detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail data orang tua'
            ], 500);
        }
    }

    public function getDetailAsalSekolah($id)
    {
        try {
            $data = PendaftarAsalSekolah::with(['pendaftar.user', 'pendaftar.jurusan'])->find($id);
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data asal sekolah tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading school detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail asal sekolah'
            ], 500);
        }
    }

    public function verifikasiDataSiswa(Request $request, $id)
    {
        try {
            $request->validate(['status' => 'required|in:terima,tolak']);
            
            $data = PendaftarDataSiswa::find($id);
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }
            
            $data->status_verifikasi = $request->status === 'terima' ? 1 : 2;
            $data->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diverifikasi'
            ]);
        } catch (\Exception $e) {
            Log::error('Error verifying student data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi data siswa'
            ], 500);
        }
    }

    public function verifikasiDataOrtu(Request $request, $id)
    {
        try {
            $request->validate(['status' => 'required|in:terima,tolak']);
            
            $data = PendaftarDataOrtu::find($id);
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data orang tua tidak ditemukan'
                ], 404);
            }
            
            $data->status_verifikasi = $request->status === 'terima' ? 1 : 2;
            $data->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Data orang tua berhasil diverifikasi'
            ]);
        } catch (\Exception $e) {
            Log::error('Error verifying parent data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi data orang tua'
            ], 500);
        }
    }

    public function verifikasiAsalSekolah(Request $request, $id)
    {
        try {
            $request->validate(['status' => 'required|in:terima,tolak']);
            
            $data = PendaftarAsalSekolah::find($id);
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data asal sekolah tidak ditemukan'
                ], 404);
            }
            
            $data->status_verifikasi = $request->status === 'terima' ? 1 : 2;
            $data->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Data asal sekolah berhasil diverifikasi'
            ]);
        } catch (\Exception $e) {
            Log::error('Error verifying school data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi data asal sekolah'
            ], 500);
        }
    }
}