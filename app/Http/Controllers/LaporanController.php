<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use App\Models\Jurusan;
use App\Models\Gelombang;
use App\Models\Wilayah;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LaporanController extends Controller
{
    public function index()
    {
        try {
            $data = [
                'pendaftar_hari_ini' => $this->getPendaftarHariIni(),
                'total_pendaftar' => $this->getTotalPendaftar(),
                'pendaftar_bulan_ini' => $this->getPendaftarBulanIni(),
                'statistik_jurusan' => $this->getStatistikJurusan(),
                'statistik_wilayah' => $this->getStatistikWilayah(),
                'statistik_status' => $this->getStatistikStatus(),
                'statistik_gelombang' => $this->getStatistikGelombang()
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Data laporan berhasil dimuat'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading laporan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data laporan: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function generateHarian(Request $request)
    {
        try {
            $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
            
            $data = [
                'tanggal' => $tanggal,
                'pendaftar_baru' => $this->getPendaftarByDate($tanggal),
                'total_pendaftar' => $this->getTotalPendaftar(),
                'statistik_jurusan' => $this->getStatistikJurusan($tanggal),
                'statistik_status' => $this->getStatistikStatus($tanggal)
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Laporan harian berhasil digenerate',
                'download_url' => route('laporan.download', ['type' => 'harian', 'date' => $tanggal])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate laporan harian',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateBulanan(Request $request)
    {
        try {
            $bulan = $request->get('bulan', Carbon::now()->format('Y-m'));
            
            $data = [
                'bulan' => $bulan,
                'pendaftar_bulan_ini' => $this->getPendaftarByMonth($bulan),
                'target_bulan' => 200,
                'statistik_jurusan' => $this->getStatistikJurusan(null, $bulan),
                'statistik_wilayah' => $this->getStatistikWilayah($bulan),
                'trend_harian' => $this->getTrendHarian($bulan)
            ];

            $data['persentase_target'] = round(($data['pendaftar_bulan_ini'] / $data['target_bulan']) * 100, 2);

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Laporan bulanan berhasil digenerate',
                'download_url' => route('laporan.download', ['type' => 'bulanan', 'month' => $bulan])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate laporan bulanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateKomprehensif()
    {
        try {
            $data = [
                'periode' => Carbon::now()->format('Y'),
                'total_pendaftar' => $this->getTotalPendaftar(),
                'statistik_jurusan' => $this->getStatistikJurusan(),
                'statistik_wilayah' => $this->getStatistikWilayah(),
                'statistik_status' => $this->getStatistikStatus(),
                'statistik_gelombang' => $this->getStatistikGelombang(),
                'trend_bulanan' => $this->getTrendBulanan(),
                'top_wilayah' => $this->getTopWilayah(),
                'analisis_kuota' => $this->getAnalisisKuota()
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Laporan komprehensif berhasil digenerate',
                'download_url' => route('laporan.download', ['type' => 'komprehensif'])
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal generate laporan komprehensif',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function download(Request $request)
    {
        try {
            $data = [
                'total_pendaftar' => $this->getTotalPendaftar(),
                'pendaftar_hari_ini' => $this->getPendaftarHariIni(),
                'pendaftar_bulan_ini' => $this->getPendaftarBulanIni(),
                'statistik_jurusan' => $this->getStatistikJurusan(),
                'statistik_wilayah' => $this->getStatistikWilayah(),
                'statistik_status' => $this->getStatistikStatus()
            ];
            
            $html = $this->generatePdfHtml($data);
            
            return response($html)
                ->header('Content-Type', 'text/html')
                ->header('Content-Disposition', 'inline');
                
        } catch (\Exception $e) {
            return '<h1>Error: ' . $e->getMessage() . '</h1>';
        }
    }

    private function getPendaftarHariIni()
    {
        try {
            return \DB::table('pengguna')
                ->where('role', 'pendaftar')
                ->whereDate('created_at', Carbon::today())
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTotalPendaftar()
    {
        try {
            $count = \DB::table('pengguna')->where('role', 'pendaftar')->count();
            return $count;
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getPendaftarBulanIni()
    {
        try {
            return \DB::table('pengguna')
                ->where('role', 'pendaftar')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getPendaftarByDate($tanggal)
    {
        return Pendaftar::whereDate('tanggal_daftar', $tanggal)->count();
    }

    private function getPendaftarByMonth($bulan)
    {
        $date = Carbon::createFromFormat('Y-m', $bulan);
        return Pendaftar::whereMonth('tanggal_daftar', $date->month)
                       ->whereYear('tanggal_daftar', $date->year)
                       ->count();
    }

    private function getStatistikJurusan($tanggal = null, $bulan = null)
    {
        try {
            $query = DB::table('jurusan')
                       ->leftJoin('pendaftar', 'jurusan.id', '=', 'pendaftar.jurusan_id')
                       ->select('jurusan.nama', DB::raw('count(pendaftar.id) as total'))
                       ->groupBy('jurusan.id', 'jurusan.nama');

            return $query->get()->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getStatistikWilayah($bulan = null)
    {
        try {
            $query = DB::table('wilayah')
                       ->leftJoin('pendaftar', 'wilayah.id', '=', 'pendaftar.wilayah_id')
                       ->select('wilayah.nama_wilayah as nama', DB::raw('count(pendaftar.id) as total'))
                       ->groupBy('wilayah.id', 'wilayah.nama_wilayah');

            return $query->get()->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getStatistikStatus($tanggal = null)
    {
        try {
            $pendaftarCount = DB::table('pendaftar')->count();
            if ($pendaftarCount == 0) {
                return [
                    (object)['status' => 'Belum Ada Data', 'total' => DB::table('pengguna')->where('role', 'pendaftar')->count()]
                ];
            }
            
            return DB::table('pendaftar')
                     ->select('status', DB::raw('count(*) as total'))
                     ->groupBy('status')
                     ->get()->toArray();
        } catch (\Exception $e) {
            return [
                (object)['status' => 'Menunggu', 'total' => 0],
                (object)['status' => 'Terverifikasi', 'total' => 0],
                (object)['status' => 'Ditolak', 'total' => 0]
            ];
        }
    }

    private function getStatistikGelombang()
    {
        try {
            return DB::table('gelombang')
                     ->leftJoin('pendaftar', 'gelombang.id', '=', 'pendaftar.gelombang_id')
                     ->select('gelombang.nama', DB::raw('count(pendaftar.id) as total'))
                     ->groupBy('gelombang.id', 'gelombang.nama')
                     ->get()->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getTrendHarian($bulan)
    {
        $date = Carbon::createFromFormat('Y-m', $bulan);
        $startDate = $date->startOfMonth();
        $endDate = $date->copy()->endOfMonth();

        return DB::table('pendaftar')
                 ->select(DB::raw('DATE(tanggal_daftar) as tanggal'), DB::raw('count(*) as total'))
                 ->whereBetween('tanggal_daftar', [$startDate, $endDate])
                 ->groupBy(DB::raw('DATE(tanggal_daftar)'))
                 ->orderBy('tanggal')
                 ->get()->toArray();
    }

    private function getTrendBulanan()
    {
        return DB::table('pendaftar')
                 ->select(DB::raw('YEAR(tanggal_daftar) as tahun'), 
                         DB::raw('MONTH(tanggal_daftar) as bulan'), 
                         DB::raw('count(*) as total'))
                 ->groupBy(DB::raw('YEAR(tanggal_daftar)'), DB::raw('MONTH(tanggal_daftar)'))
                 ->orderBy('tahun')
                 ->orderBy('bulan')
                 ->get()->toArray();
    }

    private function getTopWilayah()
    {
        try {
            return DB::table('wilayah')
                     ->leftJoin('pendaftar', 'wilayah.id', '=', 'pendaftar.wilayah_id')
                     ->select('wilayah.nama_wilayah as nama', DB::raw('count(pendaftar.id) as total'))
                     ->groupBy('wilayah.id', 'wilayah.nama_wilayah')
                     ->orderBy('total', 'desc')
                     ->limit(5)
                     ->get()->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getAnalisisKuota()
    {
        return DB::table('jurusan')
                 ->leftJoin('pendaftar', 'jurusan.id', '=', 'pendaftar.jurusan_id')
                 ->select('jurusan.nama', 'jurusan.kuota', 
                         DB::raw('count(pendaftar.id) as terisi'),
                         DB::raw('(jurusan.kuota - count(pendaftar.id)) as sisa'))
                 ->groupBy('jurusan.id', 'jurusan.nama', 'jurusan.kuota')
                 ->get()->toArray();
    }

    private function generatePdfHtml($data)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Laporan PPDB SMK Bakti Nusantara 666</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .section { margin-bottom: 20px; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; }
                .stats { display: flex; justify-content: space-around; margin: 20px 0; }
                .stat-box { text-align: center; padding: 10px; border: 1px solid #ddd; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>LAPORAN PPDB</h1>
                <h2>SMK BAKTI NUSANTARA 666</h2>
                <p>Periode: ' . Carbon::now()->format('Y') . '</p>
                <p>Tanggal Cetak: ' . Carbon::now()->format('d/m/Y H:i:s') . '</p>
            </div>
            
            <div class="section">
                <h3>RINGKASAN DATA</h3>
                <div class="stats">
                    <div class="stat-box">
                        <h4>' . ($data['total_pendaftar'] ?? 0) . '</h4>
                        <p>Total Pendaftar</p>
                    </div>
                    <div class="stat-box">
                        <h4>' . ($data['pendaftar_hari_ini'] ?? 0) . '</h4>
                        <p>Pendaftar Hari Ini</p>
                    </div>
                    <div class="stat-box">
                        <h4>' . ($data['pendaftar_bulan_ini'] ?? 0) . '</h4>
                        <p>Pendaftar Bulan Ini</p>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <h3>STATISTIK JURUSAN</h3>
                <table>
                    <tr><th>Nama Jurusan</th><th>Jumlah Pendaftar</th></tr>';
                    
        if (!empty($data['statistik_jurusan'])) {
            foreach ($data['statistik_jurusan'] as $jurusan) {
                $html .= '<tr><td>' . $jurusan->nama . '</td><td>' . $jurusan->total . '</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">Belum ada data</td></tr>';
        }
        
        $html .= '</table>
            </div>
            
            <div class="section">
                <h3>STATISTIK WILAYAH</h3>
                <table>
                    <tr><th>Nama Wilayah</th><th>Jumlah Pendaftar</th></tr>';
                    
        if (!empty($data['statistik_wilayah'])) {
            foreach ($data['statistik_wilayah'] as $wilayah) {
                $html .= '<tr><td>' . $wilayah->nama . '</td><td>' . $wilayah->total . '</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">Belum ada data</td></tr>';
        }
        
        $html .= '</table>
            </div>
            
            <div class="section">
                <h3>STATISTIK STATUS</h3>
                <table>
                    <tr><th>Status</th><th>Jumlah</th></tr>';
                    
        if (!empty($data['statistik_status'])) {
            foreach ($data['statistik_status'] as $status) {
                $html .= '<tr><td>' . $status->status . '</td><td>' . $status->total . '</td></tr>';
            }
        } else {
            $html .= '<tr><td colspan="2">Belum ada data</td></tr>';
        }
        
        $html .= '</table>
            </div>
            
            <div class="section">
                <p><strong>Catatan:</strong> Laporan ini digenerate secara otomatis oleh sistem PPDB SMK Bakti Nusantara 666</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}