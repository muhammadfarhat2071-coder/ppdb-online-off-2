<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class JurusanController extends Controller
{
    public function index()
    {
        try {
            $jurusan = Jurusan::orderBy('nama')->get();
            return response()->json([
                'success' => true,
                'data' => $jurusan,
                'message' => 'Data jurusan berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading jurusan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jurusan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi input dengan pengecekan duplikasi yang lebih ketat
            $validated = $request->validate([
                'kode' => 'required|string|max:10',
                'nama' => 'required|string|max:100',
                'kuota' => 'required|integer|min:1|max:1000'
            ], [
                'kode.required' => 'Kode jurusan harus diisi',
                'kode.max' => 'Kode jurusan maksimal 10 karakter',
                'nama.required' => 'Nama jurusan harus diisi',
                'nama.max' => 'Nama jurusan maksimal 100 karakter',
                'kuota.required' => 'Kuota harus diisi',
                'kuota.integer' => 'Kuota harus berupa angka',
                'kuota.min' => 'Kuota minimal 1',
                'kuota.max' => 'Kuota maksimal 1000'
            ]);

            // Cek duplikasi kode (case insensitive)
            $existingKode = Jurusan::whereRaw('LOWER(kode) = ?', [strtolower($validated['kode'])])->first();
            if ($existingKode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode jurusan sudah digunakan',
                    'errors' => ['kode' => ['Kode jurusan sudah digunakan']]
                ], 422);
            }

            // Cek duplikasi nama (case insensitive)
            $existingNama = Jurusan::whereRaw('LOWER(nama) = ?', [strtolower($validated['nama'])])->first();
            if ($existingNama) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama jurusan sudah digunakan',
                    'errors' => ['nama' => ['Nama jurusan sudah digunakan']]
                ], 422);
            }

            $jurusan = Jurusan::create($validated);
            
            $userName = auth()->check() ? auth()->user()->nama : 'Unknown';
            Log::info('Jurusan created: ' . $jurusan->nama . ' by user: ' . $userName);
            
            return response()->json([
                'success' => true, 
                'data' => $jurusan, 
                'message' => 'Data jurusan berhasil ditambahkan'
            ], 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating jurusan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data jurusan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $jurusan = Jurusan::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $jurusan,
                'message' => 'Data jurusan berhasil dimuat'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data jurusan tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error loading jurusan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data jurusan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $jurusan = Jurusan::findOrFail($id);
            
            $validated = $request->validate([
                'kode' => 'required|string|max:10',
                'nama' => 'required|string|max:100',
                'kuota' => 'required|integer|min:1|max:1000'
            ], [
                'kode.required' => 'Kode jurusan harus diisi',
                'kode.max' => 'Kode jurusan maksimal 10 karakter',
                'nama.required' => 'Nama jurusan harus diisi',
                'nama.max' => 'Nama jurusan maksimal 100 karakter',
                'kuota.required' => 'Kuota harus diisi',
                'kuota.integer' => 'Kuota harus berupa angka',
                'kuota.min' => 'Kuota minimal 1',
                'kuota.max' => 'Kuota maksimal 1000'
            ]);

            // Cek duplikasi kode (case insensitive) kecuali untuk record yang sedang diupdate
            $existingKode = Jurusan::whereRaw('LOWER(kode) = ?', [strtolower($validated['kode'])])
                ->where('id', '!=', $id)
                ->first();
            if ($existingKode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode jurusan sudah digunakan',
                    'errors' => ['kode' => ['Kode jurusan sudah digunakan']]
                ], 422);
            }

            // Cek duplikasi nama (case insensitive) kecuali untuk record yang sedang diupdate
            $existingNama = Jurusan::whereRaw('LOWER(nama) = ?', [strtolower($validated['nama'])])
                ->where('id', '!=', $id)
                ->first();
            if ($existingNama) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama jurusan sudah digunakan',
                    'errors' => ['nama' => ['Nama jurusan sudah digunakan']]
                ], 422);
            }

            $jurusan->update($validated);
            
            $userName = auth()->check() ? auth()->user()->nama : 'Unknown';
            Log::info('Jurusan updated: ' . $jurusan->nama . ' by user: ' . $userName);
            
            return response()->json([
                'success' => true, 
                'data' => $jurusan, 
                'message' => 'Data jurusan berhasil diperbarui'
            ]);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating jurusan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data jurusan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $jurusan = Jurusan::findOrFail($id);
            
            // Check if jurusan is being used by pendaftar
            if ($jurusan->pendaftar()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jurusan tidak dapat dihapus karena masih digunakan oleh pendaftar'
                ], 400);
            }
            
            $namaJurusan = $jurusan->nama;
            $jurusan->delete();
            
            $userName = auth()->check() ? auth()->user()->nama : 'Unknown';
            Log::info('Jurusan deleted: ' . $namaJurusan . ' by user: ' . $userName);
            
            return response()->json([
                'success' => true, 
                'message' => 'Data jurusan berhasil dihapus'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data jurusan tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting jurusan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data jurusan',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
