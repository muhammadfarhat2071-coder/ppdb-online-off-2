<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeuanganController extends Controller
{
    public function getPembayaranMenunggu()
    {
        $pembayaran = Pembayaran::with(['pendaftar.user', 'pendaftar.jurusan'])
            ->where('status', 'Menunggu Konfirmasi')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pembayaran
        ]);
    }

    public function verifikasiPembayaran(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Dikonfirmasi,Ditolak',
            'keterangan' => 'nullable|string|max:500'
        ]);

        try {
            $pembayaran = Pembayaran::findOrFail($id);
            
            $pembayaran->update([
                'status' => $request->status,
                'keterangan' => $request->keterangan,
                'user_verifikasi' => Auth::id(),
                'tanggal_konfirmasi' => now()
            ]);

            // Update status pendaftar jika pembayaran dikonfirmasi
            if ($request->status === 'Dikonfirmasi') {
                $pembayaran->pendaftar->update([
                    'status' => 'Terbayar',
                    'user_verifikasi_payment' => Auth::id(),
                    'tgl_verifikasi_payment' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil ' . strtolower($request->status)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDetailPembayaran($id)
    {
        try {
            $pembayaran = Pembayaran::with(['pendaftar.user', 'pendaftar.jurusan'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pembayaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan'
            ], 404);
        }
    }
}