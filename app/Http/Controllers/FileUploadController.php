<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Pendaftar;
use App\Models\PendaftarBerkas;
use App\Models\Gelombang;
use App\Models\Jurusan;

class FileUploadController extends Controller
{
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'jenis_berkas' => 'required|string|in:ktp,ijazah,rapor,foto,sehat',
                'file' => 'required|file|max:2048',
                'keterangan' => 'nullable|string|max:255'
            ]);

            $user = Auth::user();
            
            if ($user->role !== 'pendaftar') {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        }

        $file = $request->file('file');

        try {
            $gelombang = Gelombang::where('is_aktif', 1)->first();
            $jurusan = Jurusan::first();
            
            $pendaftar = Pendaftar::where('user_id', $user->id)->first();
            
            if (!$pendaftar) {
                // Generate nomor pendaftaran yang unik
                do {
                    $noPendaftaran = 'PPDB' . date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                } while (Pendaftar::where('no_pendaftaran', $noPendaftaran)->exists());
                
                $pendaftar = Pendaftar::create([
                    'user_id' => $user->id,
                    'tanggal_daftar' => now(),
                    'no_pendaftaran' => $noPendaftaran,
                    'gelombang_id' => $gelombang ? $gelombang->id : 1,
                    'jurusan_id' => $jurusan ? $jurusan->id : 1,
                    'status' => 'Menunggu'
                ]);
            }

            $fileName = time() . '_' . $user->id . '_' . $file->getClientOriginalName();
            $fileSize = $file->getSize();
            
            // Pastikan direktori berkas ada
            $destinationPath = storage_path('app/public/berkas');
            if (!is_dir($destinationPath)) {
                if (!mkdir($destinationPath, 0755, true)) {
                    throw new \Exception('Gagal membuat direktori berkas');
                }
            }
            
            // Cek apakah direktori dapat ditulis
            if (!is_writable($destinationPath)) {
                throw new \Exception('Direktori berkas tidak dapat ditulis');
            }
            
            // Upload file
            if (!$file->move($destinationPath, $fileName)) {
                throw new \Exception('Gagal memindahkan file ke direktori tujuan');
            }

            $berkas = new PendaftarBerkas();
            $berkas->pendaftar_id = $pendaftar->id;
            $berkas->jenis = strtoupper($request->jenis_berkas);
            $berkas->nama_file = $fileName;
            $berkas->url = 'berkas/' . $fileName;
            $berkas->ukuran_kb = round($fileSize / 1024);
            $berkas->catatan = $request->keterangan;
            $berkas->valid = 0;
            $berkas->save();

            return response()->json([
                'success' => true, 
                'message' => 'Berkas berhasil diupload dan menunggu verifikasi'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error uploading berkas:', [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload berkas: ' . $e->getMessage()
            ], 500);
        }
    }
}