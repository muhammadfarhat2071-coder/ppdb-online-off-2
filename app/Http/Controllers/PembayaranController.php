<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pendaftar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function index()
    {
        $pembayaran = Pembayaran::with(['pendaftar.user', 'pendaftar.jurusan', 'verifikator'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pembayaran
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pendaftar_id' => 'required|exists:pendaftar,id',
            'jumlah' => 'required|numeric|min:0',
            'metode' => 'required|in:Transfer Bank,Tunai,Virtual Account,QRIS',
            'bukti_transfer' => 'nullable|image|max:2048'
        ]);

        $noTransaksi = 'TRX' . date('Y') . str_pad(Pembayaran::count() + 1, 6, '0', STR_PAD_LEFT);
        
        $buktiPath = null;
        if ($request->hasFile('bukti_transfer')) {
            $buktiPath = $request->file('bukti_transfer')->store('bukti_transfer', 'public');
        }

        $pembayaran = Pembayaran::create([
            'no_transaksi' => $noTransaksi,
            'pendaftar_id' => $request->pendaftar_id,
            'jumlah' => $request->jumlah,
            'metode' => $request->metode,
            'bukti_transfer' => $buktiPath,
            'tanggal_bayar' => now(),
            'status' => 'Menunggu Konfirmasi'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil disubmit',
            'data' => $pembayaran->load(['pendaftar.user', 'pendaftar.jurusan'])
        ]);
    }

    public function konfirmasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Dikonfirmasi,Ditolak',
            'keterangan' => 'nullable|string|max:500'
        ]);

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
            'message' => 'Pembayaran berhasil ' . strtolower($request->status),
            'data' => $pembayaran->load(['pendaftar.user', 'pendaftar.jurusan', 'verifikator'])
        ]);
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['pendaftar.user', 'pendaftar.jurusan', 'verifikator'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $pembayaran
        ]);
    }

    public function menungguKonfirmasi()
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

    public function statistik()
    {
        $pendapatanBulanIni = Pembayaran::where('status', 'Dikonfirmasi')
            ->whereMonth('tanggal_konfirmasi', now()->month)
            ->whereYear('tanggal_konfirmasi', now()->year)
            ->sum('jumlah');

        $menungguKonfirmasi = Pembayaran::where('status', 'Menunggu Konfirmasi')->count();
        
        $totalTerbayar = Pembayaran::where('status', 'Dikonfirmasi')->sum('jumlah');
        
        $rataRata = Pembayaran::where('status', 'Dikonfirmasi')->avg('jumlah') ?? 0;

        return response()->json([
            'success' => true,
            'data' => [
                'pendapatan_bulan_ini' => $pendapatanBulanIni,
                'menunggu_konfirmasi' => $menungguKonfirmasi,
                'total_terbayar' => $totalTerbayar,
                'rata_rata' => $rataRata
            ]
        ]);
    }
}