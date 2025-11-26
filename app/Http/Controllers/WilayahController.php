<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        try {
            $wilayah = Wilayah::orderBy('nama_wilayah')
            ->get()
            ->map(function ($item) {
                // Set jumlah pendaftar ke 0 untuk sementara
                $jumlahPendaftar = 0;
                
                return [
                    'id' => $item->id,
                    'kode_wilayah' => $item->kode_wilayah ?? '-',
                    'nama_wilayah' => $item->nama_wilayah ?? '-',
                    'kecamatan' => '-',
                    'desa' => '-',
                    'latitude' => $item->latitude ?? 0,
                    'longitude' => $item->longitude ?? 0,
                    'keterangan' => $item->keterangan ?? '-',
                    'jumlah_pendaftar' => $jumlahPendaftar
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $wilayah,
                'message' => 'Data wilayah berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading wilayah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data wilayah',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode_wilayah' => 'required|string|max:10|unique:wilayah',
                'nama_wilayah' => 'required|string|max:255|unique:wilayah',
                'kecamatan' => 'nullable|string|max:255',
                'desa' => 'nullable|string|max:255',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'keterangan' => 'nullable|string'
            ], [
                'kode_wilayah.required' => 'Kode wilayah harus diisi',
                'kode_wilayah.unique' => 'Kode wilayah sudah digunakan',
                'nama_wilayah.required' => 'Nama wilayah harus diisi',
                'nama_wilayah.unique' => 'Nama wilayah sudah digunakan',
                'latitude.required' => 'Latitude harus diisi',
                'latitude.between' => 'Latitude harus antara -90 sampai 90',
                'longitude.required' => 'Longitude harus diisi',
                'longitude.between' => 'Longitude harus antara -180 sampai 180'
            ]);

            $wilayah = Wilayah::create($validated);
            return response()->json(['success' => true, 'data' => $wilayah, 'message' => 'Wilayah berhasil ditambahkan']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $wilayah = Wilayah::findOrFail($id);
            return response()->json($wilayah);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $wilayah = Wilayah::findOrFail($id);
            
            $validated = $request->validate([
                'kode_wilayah' => 'required|string|max:10|unique:wilayah,kode_wilayah,' . $id,
                'nama_wilayah' => 'required|string|max:255|unique:wilayah,nama_wilayah,' . $id,
                'kecamatan' => 'nullable|string|max:255',
                'desa' => 'nullable|string|max:255',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'keterangan' => 'nullable|string'
            ], [
                'kode_wilayah.required' => 'Kode wilayah harus diisi',
                'kode_wilayah.unique' => 'Kode wilayah sudah digunakan',
                'nama_wilayah.required' => 'Nama wilayah harus diisi',
                'nama_wilayah.unique' => 'Nama wilayah sudah digunakan',
                'latitude.required' => 'Latitude harus diisi',
                'latitude.between' => 'Latitude harus antara -90 sampai 90',
                'longitude.required' => 'Longitude harus diisi',
                'longitude.between' => 'Longitude harus antara -180 sampai 180'
            ]);

            $wilayah->update($validated);
            return response()->json(['success' => true, 'data' => $wilayah, 'message' => 'Wilayah berhasil diperbarui']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $wilayah = Wilayah::findOrFail($id);
            $wilayah->delete();
            return response()->json(['success' => true, 'message' => 'Wilayah berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
