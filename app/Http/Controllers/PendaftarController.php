<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PendaftarController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Build query with filters
            $query = \App\Models\Pengguna::where('role', 'pendaftar');
            
            // Apply date filters if provided
            if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
                $query->whereDate('created_at', '>=', $request->tanggal_mulai);
            }
            if ($request->has('tanggal_selesai') && $request->tanggal_selesai) {
                $query->whereDate('created_at', '<=', $request->tanggal_selesai);
            }
            
            $usersPendaftar = $query->orderBy('created_at', 'desc')->get();
            
            // Apply additional filters on pendaftar data
            $statusFilter = $request->get('status');
            $jurusanFilter = $request->get('jurusan_id');
            $gelombangFilter = $request->get('gelombang_id');

            $pendaftarData = $usersPendaftar->map(function ($user) use ($statusFilter, $jurusanFilter, $gelombangFilter) {
                try {
                    // Cek apakah user sudah memiliki data pendaftar
                    $pendaftar = Pendaftar::where('user_id', $user->id)->first();
                    
                    if ($pendaftar) {
                        // Apply filters
                        if ($statusFilter && $pendaftar->status !== $statusFilter) {
                            return null;
                        }
                        if ($jurusanFilter && $pendaftar->jurusan_id != $jurusanFilter) {
                            return null;
                        }
                        if ($gelombangFilter && $pendaftar->gelombang_id != $gelombangFilter) {
                            return null;
                        }
                        
                        // Jika sudah ada data pendaftar, ambil dari tabel pendaftar
                        $pendaftar->load(['jurusan', 'gelombang', 'asalSekolah']);
                        return [
                            'id' => $pendaftar->id,
                            'user_id' => $user->id,
                            'no_pendaftaran' => $pendaftar->no_pendaftaran ?? '-',
                            'nama' => $user->nama,
                            'email' => $user->email,
                            'hp' => $user->hp,
                            'asal_sekolah' => optional($pendaftar->asalSekolah)->nama_sekolah ?? '-',
                            'jurusan' => optional($pendaftar->jurusan)->nama ?? '-',
                            'jurusan_id' => $pendaftar->jurusan_id,
                            'gelombang' => optional($pendaftar->gelombang)->nama ?? '-',
                            'gelombang_id' => $pendaftar->gelombang_id,
                            'wilayah_id' => $pendaftar->wilayah_id,
                            'status' => $pendaftar->status ?? 'Menunggu',
                            'tanggal_daftar' => $pendaftar->tanggal_daftar ?? $user->created_at
                        ];
                    } else {
                        // Skip if filters are applied and no pendaftar data
                        if ($statusFilter || $jurusanFilter || $gelombangFilter) {
                            return null;
                        }
                        
                        // Jika belum ada data pendaftar, tampilkan data user saja
                        return [
                            'id' => null,
                            'user_id' => $user->id,
                            'no_pendaftaran' => '-',
                            'nama' => $user->nama,
                            'email' => $user->email,
                            'hp' => $user->hp,
                            'asal_sekolah' => '-',
                            'jurusan' => '-',
                            'jurusan_id' => null,
                            'gelombang' => '-',
                            'gelombang_id' => null,
                            'wilayah_id' => null,
                            'status' => 'Belum Mendaftar',
                            'tanggal_daftar' => $user->created_at
                        ];
                    }
                } catch (\Exception $e) {
                    Log::error('Error processing user ' . $user->id . ': ' . $e->getMessage());
                    return [
                        'id' => null,
                        'user_id' => $user->id,
                        'no_pendaftaran' => '-',
                        'nama' => $user->nama,
                        'email' => $user->email,
                        'asal_sekolah' => '-',
                        'jurusan' => '-',
                        'gelombang' => '-',
                        'status' => 'Error',
                        'tanggal_daftar' => $user->created_at
                    ];
                }
            })->filter(); // Remove null values from filtering

            return response()->json([
                'success' => true,
                'data' => $pendaftarData->values(), // Re-index array after filtering
                'message' => 'Data pendaftar berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading pendaftar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data pendaftar',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:pengguna,id',
                'gelombang_id' => 'required|exists:gelombang,id',
                'jurusan_id' => 'required|exists:jurusan,id',
                'status' => 'in:Menunggu,Terverifikasi,Ditolak,Lulus,Tidak Lulus'
            ]);
            
            // Cek apakah user sudah pernah mendaftar
            $existingPendaftar = Pendaftar::where('user_id', $validated['user_id'])->first();
            if ($existingPendaftar) {
                return response()->json([
                    'success' => false,
                    'message' => 'User sudah pernah mendaftar dengan nomor: ' . $existingPendaftar->no_pendaftaran
                ], 422);
            }

            // Generate nomor pendaftaran yang unik
            do {
                $noPendaftaran = 'PPDB' . date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
            } while (Pendaftar::where('no_pendaftaran', $noPendaftaran)->exists());
            
            $validated['tanggal_daftar'] = now();
            $validated['no_pendaftaran'] = $noPendaftaran;
            $validated['status'] = $validated['status'] ?? 'Menunggu';

            $pendaftar = Pendaftar::create($validated);
            
            return response()->json([
                'success' => true,
                'data' => $pendaftar,
                'message' => 'Pendaftar berhasil ditambahkan'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating pendaftar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pendaftar'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $pendaftar = Pendaftar::with(['user', 'jurusan', 'gelombang', 'dataSiswa', 'dataOrtu', 'asalSekolah', 'berkas'])
                ->findOrFail($id);
                
            return response()->json([
                'success' => true,
                'data' => $pendaftar,
                'message' => 'Data pendaftar berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pendaftar tidak ditemukan'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($id);

            $validated = $request->validate([
                'status' => 'required|in:Menunggu,Terverifikasi,Ditolak,Lulus,Tidak Lulus',
                'gelombang_id' => 'required|exists:gelombang,id',
                'jurusan_id' => 'required|exists:jurusan,id',
                'wilayah_id' => 'nullable|exists:wilayah,id'
            ]);
            
            $pendaftar->update($validated);
            
            return response()->json([
                'success' => true,
                'data' => $pendaftar->fresh(),
                'message' => 'Data pendaftar berhasil diperbarui'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating pendaftar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data pendaftar'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pendaftar = Pendaftar::findOrFail($id);
            $pendaftar->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Pendaftar berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting pendaftar: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pendaftar'
            ], 500);
        }
    }
}
