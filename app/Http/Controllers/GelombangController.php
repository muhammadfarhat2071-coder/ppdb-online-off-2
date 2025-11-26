<?php

namespace App\Http\Controllers;

use App\Models\Gelombang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class GelombangController extends Controller
{
    public function index()
    {
        try {
            $gelombang = Gelombang::orderBy('nama')->get();
            return response()->json([
                'success' => true,
                'data' => $gelombang,
                'message' => 'Data gelombang berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading gelombang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data gelombang',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'tahun' => 'required|integer|min:2020|max:2030',
                'tgl_mulai' => 'required|date',
                'tgl_selesai' => 'required|date|after:tgl_mulai',
                'biaya_daftar' => 'required|numeric|min:0',
                'kuota' => 'required|integer|min:1',
                'is_aktif' => 'nullable|boolean'
            ], [
                'nama.required' => 'Nama gelombang harus diisi',
                'nama.max' => 'Nama gelombang maksimal 100 karakter',
                'tahun.required' => 'Tahun harus diisi',
                'tahun.integer' => 'Tahun harus berupa angka',
                'tahun.min' => 'Tahun minimal 2020',
                'tahun.max' => 'Tahun maksimal 2030',
                'tgl_mulai.required' => 'Tanggal mulai harus diisi',
                'tgl_mulai.date' => 'Format tanggal mulai tidak valid',
                'tgl_selesai.required' => 'Tanggal selesai harus diisi',
                'tgl_selesai.date' => 'Format tanggal selesai tidak valid',
                'tgl_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai',
                'biaya_daftar.required' => 'Biaya pendaftaran harus diisi',
                'biaya_daftar.numeric' => 'Biaya pendaftaran harus berupa angka',
                'biaya_daftar.min' => 'Biaya pendaftaran minimal 0',
                'kuota.required' => 'Kuota harus diisi',
                'kuota.integer' => 'Kuota harus berupa angka',
                'kuota.min' => 'Kuota minimal 1'
            ]);

            // Cek duplikasi kombinasi nama dan tahun (case insensitive)
            $existingCombo = Gelombang::whereRaw('LOWER(nama) = ? AND tahun = ?', [
                strtolower($validated['nama']), 
                $validated['tahun']
            ])->first();
            
            if ($existingCombo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kombinasi nama gelombang dan tahun sudah ada',
                    'errors' => ['nama' => ['Kombinasi nama gelombang dan tahun sudah ada']]
                ], 422);
            }

            $gelombang = Gelombang::create($validated);
            
            $userName = auth()->check() ? auth()->user()->nama : 'Unknown';
            Log::info('Gelombang created: ' . $gelombang->nama . ' by user: ' . $userName);
            
            return response()->json([
                'success' => true, 
                'data' => $gelombang, 
                'message' => 'Data gelombang berhasil ditambahkan'
            ], 201);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating gelombang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data gelombang',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $gelombang = Gelombang::findOrFail($id);
            return response()->json([
                'success' => true,
                'data' => $gelombang,
                'message' => 'Data gelombang berhasil dimuat'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data gelombang tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error loading gelombang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data gelombang',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $gelombang = Gelombang::findOrFail($id);
            
            $validated = $request->validate([
                'nama' => 'required|string|max:100',
                'tahun' => 'required|integer|min:2020|max:2030',
                'tgl_mulai' => 'required|date',
                'tgl_selesai' => 'required|date|after:tgl_mulai',
                'biaya_daftar' => 'required|numeric|min:0',
                'kuota' => 'required|integer|min:1',
                'is_aktif' => 'nullable|boolean'
            ], [
                'nama.required' => 'Nama gelombang harus diisi',
                'nama.max' => 'Nama gelombang maksimal 100 karakter',
                'tahun.required' => 'Tahun harus diisi',
                'tahun.integer' => 'Tahun harus berupa angka',
                'tahun.min' => 'Tahun minimal 2020',
                'tahun.max' => 'Tahun maksimal 2030',
                'tgl_mulai.required' => 'Tanggal mulai harus diisi',
                'tgl_mulai.date' => 'Format tanggal mulai tidak valid',
                'tgl_selesai.required' => 'Tanggal selesai harus diisi',
                'tgl_selesai.date' => 'Format tanggal selesai tidak valid',
                'tgl_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai',
                'biaya_daftar.required' => 'Biaya pendaftaran harus diisi',
                'biaya_daftar.numeric' => 'Biaya pendaftaran harus berupa angka',
                'biaya_daftar.min' => 'Biaya pendaftaran minimal 0',
                'kuota.required' => 'Kuota harus diisi',
                'kuota.integer' => 'Kuota harus berupa angka',
                'kuota.min' => 'Kuota minimal 1'
            ]);

            // Cek duplikasi kombinasi nama dan tahun kecuali record yang sedang diupdate
            $existingCombo = Gelombang::whereRaw('LOWER(nama) = ? AND tahun = ?', [
                strtolower($validated['nama']), 
                $validated['tahun']
            ])->where('id', '!=', $id)->first();
            
            if ($existingCombo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kombinasi nama gelombang dan tahun sudah ada',
                    'errors' => ['nama' => ['Kombinasi nama gelombang dan tahun sudah ada']]
                ], 422);
            }

            $gelombang->update($validated);
            
            $userName = auth()->check() ? auth()->user()->nama : 'Unknown';
            Log::info('Gelombang updated: ' . $gelombang->nama . ' by user: ' . $userName);
            
            return response()->json([
                'success' => true, 
                'data' => $gelombang, 
                'message' => 'Data gelombang berhasil diperbarui'
            ]);
            
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating gelombang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data gelombang',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $gelombang = Gelombang::findOrFail($id);
            
            // Check if gelombang is being used by pendaftar
            if ($gelombang->pendaftar()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gelombang tidak dapat dihapus karena masih digunakan oleh pendaftar'
                ], 400);
            }
            
            $namaGelombang = $gelombang->nama;
            $gelombang->delete();
            
            $userName = auth()->check() ? auth()->user()->nama : 'Unknown';
            Log::info('Gelombang deleted: ' . $namaGelombang . ' by user: ' . $userName);
            
            return response()->json([
                'success' => true, 
                'message' => 'Data gelombang berhasil dihapus'
            ]);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data gelombang tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting gelombang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data gelombang',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
