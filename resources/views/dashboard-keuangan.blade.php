<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Keuangan - SMK Bakti Nusantara 666</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #1e3c72;
            --secondary: #2a5298;
            --success: #28a745;
            --warning: #ffc107;
            --info: #17a2b8;
            --keuangan: #1e3c72;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            min-height: 100vh;
            transition: all 0.3s;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: white;
            padding: 0.8rem 1rem;
            border-radius: 5px;
            margin: 0.2rem 0;
            cursor: pointer;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
        }

        .content-area {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--keuangan);
            transition: transform 0.3s ease;
            overflow: hidden;
            position: relative;
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.1;
            z-index: 1;
        }

        .stat-card .d-flex {
            position: relative;
            z-index: 2;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.primary {
            border-left-color: var(--primary);
        }

        .stat-card.success {
            border-left-color: var(--success);
        }

        .stat-card.warning {
            border-left-color: var(--warning);
        }

        .stat-card.info {
            border-left-color: var(--info);
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-keuangan {
            background: var(--keuangan);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
        }

        .btn-keuangan:hover {
            background: #219653;
            color: white;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        .action-buttons .btn {
            margin: 0 2px;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }

        .navbar-keuangan {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .keuangan-badge {
            background: var(--keuangan);
            color: white;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 2rem;
        }

        .money {
            font-family: 'Courier New', monospace;
            font-weight: bold;
        }

        .income {
            color: #27ae60;
        }

        .expense {
            color: #e74c3c;
        }

        .pending {
            color: #f39c12;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3">
                    <h5 class="text-center mb-4">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        KEUANGAN
                    </h5>
                    <p class="text-center small mb-4">SMK Bakti Nusantara 666</p>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" data-target="dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="pembayaran">
                                <i class="fas fa-credit-card me-2"></i>Pembayaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="tagihan">
                                <i class="fas fa-file-invoice me-2"></i>Manajemen Tagihan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="laporan">
                                <i class="fas fa-chart-bar me-2"></i>Laporan Keuangan
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content-area">
                <!-- Header -->
                <nav class="navbar navbar-keuangan navbar-light border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h6 text-white">
                            <i class="fas fa-money-bill-wave me-2"></i>Dashboard Keuangan
                        </span>
                        <div class="d-flex align-items-center">
                            <span class="me-3 text-white" id="userName">{{ $user->nama }}</span>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin logout?')">
                                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>

                <!-- Toast Notifications -->
                <div class="toast-container">
                    <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body" id="successMessage"></div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                    <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body" id="errorMessage"></div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                                data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>

                <!-- Content Sections -->
                <div class="container-fluid mt-4">
                    <!-- Dashboard Section -->
                    <div id="dashboard" class="section active">
                        <!-- Welcome Header -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom border-0">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h2 class="fw-bold mb-2" style="color: var(--keuangan);">
                                                    <i class="fas fa-money-bill-wave me-2"></i>Dashboard Keuangan
                                                </h2>
                                                <p class="text-muted mb-0">
                                                    Selamat datang, <strong id="dashboardUserName">Staff
                                                        Keuangan</strong>!
                                                    <span class="badge keuangan-badge ms-2">Keuangan</span>
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        <span id="currentDate"></span>
                                                    </span>
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <span id="currentTime"></span>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stat Cards -->
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card success">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Pendapatan Bulan Ini</h5>
                                            <h2 class="mb-0 money income" id="pendapatanBulanIni">Rp 0</h2>
                                            <small><i class="fas fa-arrow-up me-1"></i><span
                                                    id="persentasePendapatan">0%</span> dari bulan lalu</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card warning">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Menunggu Pembayaran</h5>
                                            <h2 class="mb-0" id="menungguPembayaran">0</h2>
                                            <small><i class="fas fa-clock me-1"></i>Perlu konfirmasi</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-hourglass-half fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Total Terbayar</h5>
                                            <h2 class="mb-0 money income" id="totalTerbayar">Rp 0</h2>
                                            <small><i class="fas fa-check-circle me-1"></i>Terkonfirmasi</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-check fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card primary">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Rata-rata Pembayaran</h5>
                                            <h2 class="mb-0 money" id="rataPembayaran">Rp 0</h2>
                                            <small><i class="fas fa-calculator me-1"></i>Per siswa</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-calculator fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Row -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card card-custom">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-primary"></i>Tren
                                            Pendapatan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="pendapatanChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-custom">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-success"></i>Distribusi
                                            Pembayaran</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="distribusiChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions & Recent Activity -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-custom">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i class="fas fa-rocket me-2 text-warning"></i>Quick Actions
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6 mb-3">
                                                <button class="btn btn-keuangan text-white w-100 py-3"
                                                    onclick="showSection('pembayaran')">
                                                    <i class="fas fa-credit-card fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Konfirmasi</span>
                                                </button>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <button class="btn btn-warning w-100 py-3 text-dark"
                                                    style="border-radius: 10px;" onclick="showSection('tagihan')">
                                                    <i class="fas fa-file-invoice fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Tagihan</span>
                                                </button>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <button class="btn btn-info w-100 py-3 text-white"
                                                    style="border-radius: 10px;" onclick="showSection('laporan')">
                                                    <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Laporan</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-custom">
                                    <div
                                        class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Transaksi
                                            Terbaru</h5>
                                        <span class="badge bg-primary">Real-time</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Tanggal</th>
                                                        <th>Siswa</th>
                                                        <th>Jumlah</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="recentTransactions">
                                                    <!-- Recent transactions will be loaded here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran Section -->
                    <div id="pembayaran" class="section">
                        <div class="card card-custom">
                            <div
                                class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-credit-card me-2 text-primary"></i>Konfirmasi
                                    Pembayaran</h5>
                                <button class="btn btn-keuangan btn-sm" onclick="showFilterModal()">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No. Transaksi</th>
                                                <th>Nama Siswa</th>
                                                <th>Jurusan</th>
                                                <th>Jumlah</th>
                                                <th>Metode</th>
                                                <th>Tanggal</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pembayaranTable">
                                            <!-- Pembayaran data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tagihan Section -->
                    <div id="tagihan" class="section">
                        <div class="card card-custom">
                            <div
                                class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-file-invoice me-2 text-primary"></i>Manajemen Tagihan
                                </h5>
                                <button class="btn btn-keuangan btn-sm" onclick="showTagihanModal()">
                                    <i class="fas fa-plus me-2"></i>Tambah Tagihan
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div class="chart-container">
                                            <canvas id="tagihanChart"></canvas>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">Ringkasan Tagihan</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <small class="text-muted">Total Tagihan</small>
                                                    <h4 class="money" id="totalTagihan">Rp 0</h4>
                                                </div>
                                                <div class="mb-3">
                                                    <small class="text-muted">Tertagih</small>
                                                    <h5 class="money income" id="tertagih">Rp 0</h5>
                                                </div>
                                                <div class="mb-3">
                                                    <small class="text-muted">Tunggakan</small>
                                                    <h5 class="money expense" id="tunggakan">Rp 0</h5>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Persentase Tertagih</small>
                                                    <h5 id="persentaseTertagih">0%</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No. Tagihan</th>
                                                <th>Siswa</th>
                                                <th>Jurusan</th>
                                                <th>Jumlah</th>
                                                <th>Jatuh Tempo</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tagihanTable">
                                            <!-- Tagihan data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Laporan Section -->
                    <div id="laporan" class="section">
                        <div class="card card-custom">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i>Laporan Keuangan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">Laporan Harian</h6>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Tanggal:</strong> <span id="currentDateReport"></span></p>
                                                <p><strong>Pendapatan:</strong> <span class="money income"
                                                        id="pendapatanHarian">Rp 0</span></p>
                                                <p><strong>Transaksi:</strong> <span id="transaksiHarian">0</span>
                                                    transaksi</p>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0">Laporan Bulanan</h6>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>Bulan:</strong> <span id="currentMonthReport"></span></p>
                                                <p><strong>Pendapatan:</strong> <span class="money income"
                                                        id="pendapatanBulanan">Rp 0</span></p>
                                                <p><strong>Target:</strong> <span class="money" id="targetBulanan">Rp
                                                        0</span> (<span id="persentaseTarget">0</span>%)</p>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-info text-white">
                                                <h6 class="mb-0">Laporan Komprehensif</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <h6>Pendapatan per Jurusan</h6>
                                                        <ul class="list-group" id="statJurusan">
                                                            <!-- Statistik jurusan will be loaded here -->
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>Metode Pembayaran</h6>
                                                        <ul class="list-group" id="statMetode">
                                                            <!-- Statistik metode will be loaded here -->
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>Status Tagihan</h6>
                                                        <ul class="list-group" id="statTagihan">
                                                            <!-- Statistik tagihan will be loaded here -->
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembayaran -->
    <div class="modal fade" id="konfirmasiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pembayaran - <span id="modalNoTransaksi"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Data Pembayaran</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Siswa:</strong></td>
                                    <td id="modalSiswa"></td>
                                </tr>
                                <tr>
                                    <td><strong>Jurusan:</strong></td>
                                    <td id="modalJurusan"></td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah:</strong></td>
                                    <td class="money" id="modalJumlah"></td>
                                </tr>
                                <tr>
                                    <td><strong>Metode:</strong></td>
                                    <td id="modalMetode"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Bukti Transfer</h6>
                            <div id="buktiTransfer" class="text-center border rounded p-3" style="min-height: 200px;">
                                <img id="buktiTransferImg" src="" alt="Bukti Transfer" class="img-fluid rounded" style="max-height: 300px; display: none;">
                                <p id="buktiTransferText" class="text-muted">Tidak ada bukti transfer</p>
                            </div>
                            <div class="mt-2 text-center">
                                <button id="btnLihatBukti" class="btn btn-info btn-sm" onclick="lihatBuktiFull()" style="display: none;">
                                    <i class="fas fa-expand me-1"></i>Lihat Ukuran Penuh
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Konfirmasi</h6>
                        <form id="formKonfirmasi">
                            <input type="hidden" id="transaksiId">
                            <div class="mb-3">
                                <label class="form-label">Status Konfirmasi</label>
                                <select class="form-control" id="konfirmasiStatus" required>
                                    <option value="">Pilih Status</option>
                                    <option value="Dikonfirmasi">Dikonfirmasi</option>
                                    <option value="Ditolak">Ditolak</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea class="form-control" id="konfirmasiKeterangan" rows="3"
                                    placeholder="Masukkan keterangan konfirmasi..."></textarea>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="simpanKonfirmasi()">Simpan
                        Konfirmasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Tagihan -->
    <div class="modal fade" id="detailTagihanModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Transaksi Tagihan - <span id="detailNoPendaftaran"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Data Pendaftar</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>No. Pendaftaran:</strong></td>
                                    <td><span id="detailNoPendaftaran2"></span></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Siswa:</strong></td>
                                    <td id="detailNamaSiswa"></td>
                                </tr>
                                <tr>
                                    <td><strong>Jurusan:</strong></td>
                                    <td id="detailJurusan"></td>
                                </tr>
                                <tr>
                                    <td><strong>Biaya Pendaftaran:</strong></td>
                                    <td class="money" id="detailBiayaPendaftaran"></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Daftar:</strong></td>
                                    <td id="detailTanggalDaftar"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Data Pembayaran</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>No. Transaksi:</strong></td>
                                    <td id="detailNoTransaksi"></td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah Bayar:</strong></td>
                                    <td class="money" id="detailJumlahBayar"></td>
                                </tr>
                                <tr>
                                    <td><strong>Metode:</strong></td>
                                    <td id="detailMetodeBayar"></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Bayar:</strong></td>
                                    <td id="detailTanggalBayar"></td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td id="detailStatusBayar"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Bukti Transfer</h6>
                            <div id="detailBuktiTransfer" class="text-center border rounded p-3">
                                <!-- Bukti transfer akan dimuat di sini -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="printDetailTagihan()">
                        <i class="fas fa-print me-1"></i>Cetak Detail
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let currentUser = {
            id: 3,
            nama: "Staff Keuangan",
            email: "keuangan@smkbn666.sch.id",
            role: "Keuangan"
        };

        // Charts
        let pendapatanChart, distribusiChart, tagihanChart;

        // Data pembayaran dari database
        let pembayaranData = [];
        let statistikData = {
            pendapatan_bulan_ini: 0,
            menunggu_konfirmasi: 0,
            total_terbayar: 0,
            rata_rata: 0
        };
        
        // Load data from API
        async function loadPembayaranFromAPI() {
            try {
                const response = await fetch('/api/pembayaran');
                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        pembayaranData = result.data || [];
                    }
                } else {
                    console.warn('API pembayaran not available, using fallback data');
                    pembayaranData = [];
                }
            } catch (error) {
                console.warn('Error loading pembayaran, using fallback:', error);
                pembayaranData = [];
            }
        }
        
        async function loadStatistikFromAPI() {
            try {
                const response = await fetch('/api/pembayaran/statistik/dashboard');
                if (response.ok) {
                    const result = await response.json();
                    if (result.success) {
                        statistikData = result.data || statistikData;
                    }
                } else {
                    console.warn('API statistik not available, using fallback data');
                }
            } catch (error) {
                console.warn('Error loading statistik, using fallback:', error);
            }
        }



        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Section Navigation
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');

            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.classList.remove('active');
            });

            const targetLink = document.querySelector(`[data-target="${sectionId}"]`);
            if (targetLink) {
                targetLink.classList.add('active');
            }

            // Load data when section is shown
            loadSectionData(sectionId);
        }

        // Load data for specific section
        function loadSectionData(sectionId) {
            switch (sectionId) {
                case 'dashboard':
                    loadDashboardData();
                    break;
                case 'pembayaran':
                    loadPembayaranData();
                    break;
                case 'tagihan':
                    loadTagihanData();
                    break;
                case 'laporan':
                    loadLaporanData();
                    break;
            }
        }

        // Load dashboard data
        async function loadDashboardData() {
            try {
                // Load stats
                const statsResponse = await fetch('/keuangan/dashboard-stats');
                const statsResult = await statsResponse.json();
                
                if (statsResult.success) {
                    const stats = statsResult.data;
                    document.getElementById('pendapatanBulanIni').textContent = formatCurrency(stats.pendapatan_bulan_ini || 0);
                    document.getElementById('menungguPembayaran').textContent = stats.menunggu_konfirmasi || 0;
                    document.getElementById('totalTerbayar').textContent = formatCurrency(stats.total_terbayar || 0);
                    document.getElementById('rataPembayaran').textContent = formatCurrency(stats.rata_rata || 0);
                }
                
                // Load recent transactions
                const transResponse = await fetch('/keuangan/recent-transactions');
                const transResult = await transResponse.json();
                
                const recentTable = document.getElementById('recentTransactions');
                recentTable.innerHTML = '';
                
                if (transResult.success && transResult.data.length > 0) {
                    transResult.data.forEach(pembayaran => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${new Date(pembayaran.tanggal_bayar).toLocaleDateString('id-ID')}</td>
                            <td>${pembayaran.nama_pendaftar || '-'}</td>
                            <td class="money">${formatCurrency(pembayaran.jumlah)}</td>
                            <td><span class="badge bg-${pembayaran.status === 'Dikonfirmasi' ? 'success' : 'warning'}">${pembayaran.status}</span></td>
                        `;
                        recentTable.appendChild(row);
                    });
                } else {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td colspan="4" class="text-center text-muted">
                            <i class="fas fa-clock me-1"></i>Belum ada transaksi
                        </td>
                    `;
                    recentTable.appendChild(row);
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }

            // Create charts
            createPendapatanChart();
            createDistribusiChart();
        }

        // Load pembayaran data
        async function loadPembayaranData() {
            try {
                const response = await fetch('/keuangan/pembayaran-menunggu');
                const result = await response.json();
                
                if (result.success) {
                    pembayaranData = result.data || [];
                } else {
                    pembayaranData = [];
                }
            } catch (error) {
                console.warn('Error loading pembayaran:', error);
                pembayaranData = [];
            }
            
            const pembayaranTable = document.getElementById('pembayaranTable');
            pembayaranTable.innerHTML = '';

            if (pembayaranData.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i><br>
                        Tidak ada pembayaran yang perlu diverifikasi
                    </td>
                `;
                pembayaranTable.appendChild(row);
                return;
            }

            pembayaranData.forEach(pembayaran => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${pembayaran.no_transaksi}</td>
                    <td>${pembayaran.nama_pendaftar || '-'}</td>
                    <td>${pembayaran.jurusan || '-'}</td>
                    <td class="money">${formatCurrency(pembayaran.jumlah)}</td>
                    <td>${pembayaran.metode}</td>
                    <td>${new Date(pembayaran.tanggal_bayar).toLocaleDateString('id-ID')}</td>
                    <td><span class="badge bg-${getStatusColor(pembayaran.status)}">${pembayaran.status}</span></td>
                    <td class="action-buttons">
                        <button class="btn btn-sm btn-info me-1" onclick="lihatDetailPembayaran(${pembayaran.id})">
                            <i class="fas fa-eye me-1"></i>Detail
                        </button>
                        <button class="btn btn-sm btn-success me-1" onclick="verifikasiPembayaran(${pembayaran.id}, 'Dikonfirmasi')">
                            <i class="fas fa-check me-1"></i>Terima
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="verifikasiPembayaran(${pembayaran.id}, 'Ditolak')">
                            <i class="fas fa-times me-1"></i>Tolak
                        </button>
                    </td>
                `;
                pembayaranTable.appendChild(row);
            });
        }

        // Load tagihan data
        async function loadTagihanData() {
            try {
                const response = await fetch('/keuangan/manajemen-tagihan');
                const result = await response.json();
                
                if (result.success) {
                    const rawData = result.data || [];
                    const pendaftarData = validateTagihanData(rawData);
                    
                    console.log('Data validation:', {
                        rawCount: rawData.length,
                        validCount: pendaftarData.length,
                        sample: pendaftarData[0]
                    });
                    
                    const tagihanTable = document.getElementById('tagihanTable');
                    tagihanTable.innerHTML = '';
                    
                    // Jika tidak ada data, tampilkan pesan dan reset ringkasan
                    if (pendaftarData.length === 0) {
                        const row = document.createElement('tr');
                        row.innerHTML = '<td colspan="7" class="text-center text-muted py-4">Tidak ada data pendaftar</td>';
                        tagihanTable.appendChild(row);
                        
                        // Reset ringkasan ke 0
                        document.getElementById('totalTagihan').textContent = formatCurrency(0);
                        document.getElementById('tertagih').textContent = formatCurrency(0);
                        document.getElementById('tunggakan').textContent = formatCurrency(0);
                        document.getElementById('persentaseTertagih').textContent = '0%';
                        return;
                    }
                    
                    // Calculate summary berdasarkan data aktual
                    let totalTagihan = 0;
                    let tertagih = 0;
                    let menungguKonfirmasi = 0;
                    let belumBayar = 0;
                    let ditolak = 0;
                    
                    // Simpan data untuk grafik
                    window.tagihanChartData = {
                        lunas: 0,
                        menunggu: 0,
                        belum: 0,
                        tolak: 0
                    };
                    
                    pendaftarData.forEach(pendaftar => {
                        totalTagihan += pendaftar.biaya_pendaftaran;
                        
                        // Logika status berdasarkan data aktual - perbaikan
                        const isLunas = pendaftar.tgl_verifikasi_payment || pendaftar.status_pembayaran === 'Dikonfirmasi';
                        const isMenunggu = pendaftar.status_pembayaran === 'Menunggu Konfirmasi';
                        const isDitolak = pendaftar.status_pembayaran === 'Ditolak';
                        const isBelumBayar = !pendaftar.status_pembayaran || pendaftar.status_pembayaran === 'Belum Bayar';
                        
                        if (isLunas) {
                            // Sudah lunas - gunakan jumlah yang dibayar atau biaya pendaftaran
                            const jumlahTerbayar = pendaftar.jumlah_bayar || pendaftar.biaya_pendaftaran;
                            tertagih += jumlahTerbayar;
                            window.tagihanChartData.lunas++;
                        } else if (isMenunggu) {
                            // Menunggu konfirmasi - tidak dihitung sebagai tertagih
                            window.tagihanChartData.menunggu++;
                        } else if (isDitolak) {
                            // Ditolak - masuk tunggakan
                            ditolak += pendaftar.biaya_pendaftaran;
                            window.tagihanChartData.tolak++;
                        } else {
                            // Belum bayar - masuk tunggakan
                            belumBayar += pendaftar.biaya_pendaftaran;
                            window.tagihanChartData.belum++;
                        }
                        
                        // Add row to table
                        const row = document.createElement('tr');
                        const statusBadge = getPaymentStatusBadge(pendaftar.status_pembayaran, pendaftar.tgl_verifikasi_payment);
                        
                        row.innerHTML = `
                            <td>${pendaftar.no_pendaftaran}</td>
                            <td>${pendaftar.nama}</td>
                            <td>${pendaftar.jurusan}</td>
                            <td class="money">${formatCurrency(pendaftar.biaya_pendaftaran)}</td>
                            <td>${new Date(pendaftar.tanggal_daftar).toLocaleDateString('id-ID')}</td>
                            <td>${statusBadge}</td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-info" onclick="viewTagihanDetail('${pendaftar.no_pendaftaran}')">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </button>
                            </td>
                        `;
                        tagihanTable.appendChild(row);
                    });
                    
                    // Update summary dengan perhitungan yang benar
                    const tunggakan = belumBayar + ditolak;
                    const persentaseTertagih = totalTagihan > 0 ? Math.round((tertagih / totalTagihan) * 100) : 0;
                    
                    // Debug log untuk memastikan perhitungan benar
                    console.log('Ringkasan Tagihan:', {
                        totalPendaftar: pendaftarData.length,
                        totalTagihan: totalTagihan,
                        tertagih: tertagih,
                        tunggakan: tunggakan,
                        belumBayar: belumBayar,
                        ditolak: ditolak,
                        menungguKonfirmasi: menungguKonfirmasi,
                        persentase: persentaseTertagih
                    });
                    
                    document.getElementById('totalTagihan').textContent = formatCurrency(totalTagihan);
                    document.getElementById('tertagih').textContent = formatCurrency(tertagih);
                    document.getElementById('tunggakan').textContent = formatCurrency(tunggakan);
                    document.getElementById('persentaseTertagih').textContent = `${persentaseTertagih}%`;
                    
                    // Load riwayat tagihan
                    loadRiwayatTagihan();
                    
                    // Update grafik pendapatan berdasarkan data aktual
                    updatePendapatanChart(pendaftarData);
                    
                } else {
                    console.error('Failed to load tagihan data');
                }
            } catch (error) {
                console.error('Error loading tagihan data:', error);
            }
            
            createTagihanChart();
            
            // Update distribusi chart dengan data aktual
            updateDistribusiChart();
        }
        
        // Load riwayat tagihan
        async function loadRiwayatTagihan() {
            try {
                const response = await fetch('/keuangan/riwayat-tagihan');
                const result = await response.json();
                
                if (result.success) {
                    const riwayatData = result.data || [];
                    
                    // Simpan data untuk grafik
                    window.riwayatData = riwayatData;
                    
                    // Create riwayat table if not exists
                    let riwayatSection = document.getElementById('riwayatTagihanSection');
                    if (!riwayatSection) {
                        const tagihanSection = document.getElementById('tagihan');
                        const riwayatHTML = `
                            <div class="mt-4" id="riwayatTagihanSection">
                                <div class="card card-custom">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i class="fas fa-history me-2 text-info"></i>Riwayat Transaksi Tagihan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <h5 id="totalTransaksi">0</h5>
                                                        <small class="text-muted">Total Transaksi</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <h5 id="transaksiDikonfirmasi" class="text-success">0</h5>
                                                        <small class="text-muted">Dikonfirmasi</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <h5 id="transaksiMenunggu" class="text-warning">0</h5>
                                                        <small class="text-muted">Menunggu</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card text-center">
                                                    <div class="card-body">
                                                        <h5 id="totalNominalRiwayat" class="text-primary">Rp 0</h5>
                                                        <small class="text-muted">Total Nominal</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>No. Transaksi</th>
                                                        <th>Pendaftar</th>
                                                        <th>Jurusan</th>
                                                        <th>Jumlah</th>
                                                        <th>Metode</th>
                                                        <th>Tanggal Bayar</th>
                                                        <th>Status</th>
                                                        <th>Tanggal Konfirmasi</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="riwayatTagihanTable">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        tagihanSection.querySelector('.card-custom').insertAdjacentHTML('afterend', riwayatHTML);
                    }
                    
                    // Update stats
                    const stats = result.stats || {};
                    document.getElementById('totalTransaksi').textContent = stats.total_transaksi || 0;
                    document.getElementById('transaksiDikonfirmasi').textContent = stats.dikonfirmasi || 0;
                    document.getElementById('transaksiMenunggu').textContent = stats.menunggu || 0;
                    document.getElementById('totalNominalRiwayat').textContent = formatCurrency(stats.total_nominal || 0);
                    
                    // Populate table
                    const riwayatTable = document.getElementById('riwayatTagihanTable');
                    riwayatTable.innerHTML = '';
                    
                    if (riwayatData.length === 0) {
                        const row = document.createElement('tr');
                        row.innerHTML = '<td colspan="8" class="text-center text-muted">Belum ada riwayat transaksi</td>';
                        riwayatTable.appendChild(row);
                        return;
                    }
                    
                    riwayatData.forEach(transaksi => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${transaksi.no_transaksi}</td>
                            <td>${transaksi.nama}</td>
                            <td>${transaksi.jurusan}</td>
                            <td class="money">${formatCurrency(transaksi.jumlah)}</td>
                            <td>${transaksi.metode}</td>
                            <td>${new Date(transaksi.tanggal_bayar).toLocaleDateString('id-ID')}</td>
                            <td><span class="badge bg-${getStatusColor(transaksi.status)}">${transaksi.status}</span></td>
                            <td>${transaksi.tanggal_konfirmasi ? new Date(transaksi.tanggal_konfirmasi).toLocaleDateString('id-ID') : '-'}</td>
                        `;
                        riwayatTable.appendChild(row);
                    });
                    
                    // Update distribusi chart setelah data dimuat
                    setTimeout(() => updateDistribusiChart(), 100);
                }
            } catch (error) {
                console.error('Error loading riwayat tagihan:', error);
            }
        }
        
        function getPaymentStatusBadge(status, tglVerifikasi) {
            // Perbaikan logika badge status
            if (tglVerifikasi || status === 'Dikonfirmasi') {
                return '<span class="badge bg-success">Lunas</span>';
            } else if (status === 'Menunggu Konfirmasi') {
                return '<span class="badge bg-warning">Menunggu Verifikasi</span>';
            } else if (status === 'Ditolak') {
                return '<span class="badge bg-danger">Ditolak</span>';
            } else {
                return '<span class="badge bg-secondary">Belum Bayar</span>';
            }
        }
        
        // Fungsi untuk memvalidasi dan membersihkan data
        function validateTagihanData(pendaftarData) {
            return pendaftarData.filter(pendaftar => {
                // Pastikan data memiliki field yang diperlukan
                return pendaftar.biaya_pendaftaran && 
                       pendaftar.biaya_pendaftaran > 0 &&
                       pendaftar.no_pendaftaran &&
                       pendaftar.nama;
            }).map(pendaftar => {
                // Normalisasi data
                return {
                    ...pendaftar,
                    biaya_pendaftaran: parseInt(pendaftar.biaya_pendaftaran) || 0,
                    jumlah_bayar: parseInt(pendaftar.jumlah_bayar) || 0,
                    status_pembayaran: pendaftar.status_pembayaran || null,
                    tgl_verifikasi_payment: pendaftar.tgl_verifikasi_payment || null
                };
            });
        }
        
        async function viewTagihanDetail(noPendaftaran) {
            try {
                const response = await fetch(`/keuangan/detail-tagihan/${noPendaftaran}`);
                const result = await response.json();
                
                if (result.success) {
                    const data = result.data;
                    
                    // Populate modal dengan data transaksi
                    document.getElementById('detailNoPendaftaran').textContent = data.no_pendaftaran;
                    document.getElementById('detailNoPendaftaran2').textContent = data.no_pendaftaran;
                    document.getElementById('detailNamaSiswa').textContent = data.nama;
                    document.getElementById('detailJurusan').textContent = data.jurusan;
                    document.getElementById('detailBiayaPendaftaran').textContent = formatCurrency(data.biaya_pendaftaran);
                    document.getElementById('detailTanggalDaftar').textContent = new Date(data.tanggal_daftar).toLocaleDateString('id-ID');
                    
                    // Data pembayaran
                    if (data.pembayaran) {
                        document.getElementById('detailNoTransaksi').textContent = data.pembayaran.no_transaksi || '-';
                        document.getElementById('detailJumlahBayar').textContent = formatCurrency(data.pembayaran.jumlah || 0);
                        document.getElementById('detailMetodeBayar').textContent = data.pembayaran.metode || '-';
                        document.getElementById('detailTanggalBayar').textContent = data.pembayaran.tanggal_bayar ? new Date(data.pembayaran.tanggal_bayar).toLocaleDateString('id-ID') : '-';
                        document.getElementById('detailStatusBayar').innerHTML = getPaymentStatusBadge(data.pembayaran.status, data.pembayaran.tgl_verifikasi_payment);
                        
                        // Bukti transfer
                        const buktiDiv = document.getElementById('detailBuktiTransfer');
                        if (data.pembayaran.bukti_transfer) {
                            buktiDiv.innerHTML = `<img src="/storage/${data.pembayaran.bukti_transfer}" alt="Bukti Transfer" class="img-fluid rounded" style="max-height: 200px;">`;
                        } else {
                            buktiDiv.innerHTML = '<p class="text-muted">Tidak ada bukti transfer</p>';
                        }
                    } else {
                        // Jika belum ada pembayaran
                        document.getElementById('detailNoTransaksi').textContent = '-';
                        document.getElementById('detailJumlahBayar').textContent = formatCurrency(0);
                        document.getElementById('detailMetodeBayar').textContent = '-';
                        document.getElementById('detailTanggalBayar').textContent = '-';
                        document.getElementById('detailStatusBayar').innerHTML = '<span class="badge bg-secondary">Belum Bayar</span>';
                        document.getElementById('detailBuktiTransfer').innerHTML = '<p class="text-muted">Belum ada pembayaran</p>';
                    }
                    
                    // Tampilkan modal
                    const modal = new bootstrap.Modal(document.getElementById('detailTagihanModal'));
                    modal.show();
                } else {
                    showToast('Gagal memuat detail tagihan: ' + result.message, 'error');
                }
            } catch (error) {
                console.error('Error loading detail tagihan:', error);
                showToast('Gagal memuat detail tagihan', 'error');
            }
        }
        
        // Update grafik pendapatan berdasarkan data aktual
        function updatePendapatanChart(pendaftarData) {
            // Hitung pendapatan per bulan dari data aktual
            const monthlyData = {};
            const currentDate = new Date();
            
            // Inisialisasi 6 bulan terakhir
            for (let i = 5; i >= 0; i--) {
                const date = new Date(currentDate.getFullYear(), currentDate.getMonth() - i, 1);
                const monthKey = date.toLocaleDateString('id-ID', { month: 'short' });
                monthlyData[monthKey] = 0;
            }
            
            // Hitung pendapatan dari data pendaftar yang sudah lunas
            pendaftarData.forEach(pendaftar => {
                if (pendaftar.tgl_verifikasi_payment || pendaftar.status_pembayaran === 'Dikonfirmasi') {
                    const paymentDate = new Date(pendaftar.tgl_verifikasi_payment || pendaftar.tanggal_bayar);
                    const monthKey = paymentDate.toLocaleDateString('id-ID', { month: 'short' });
                    if (monthlyData.hasOwnProperty(monthKey)) {
                        monthlyData[monthKey] += pendaftar.jumlah_bayar || pendaftar.biaya_pendaftaran;
                    }
                }
            });
            
            // Update chart data
            if (pendapatanChart) {
                pendapatanChart.data.labels = Object.keys(monthlyData);
                pendapatanChart.data.datasets[0].data = Object.values(monthlyData);
                pendapatanChart.update();
            }
        }
        
        // Update distribusi chart berdasarkan data riwayat
        function updateDistribusiChart() {
            if (window.riwayatData && distribusiChart) {
                const metodeCount = {};
                
                window.riwayatData.forEach(transaksi => {
                    if (transaksi.status === 'Dikonfirmasi') {
                        metodeCount[transaksi.metode] = (metodeCount[transaksi.metode] || 0) + 1;
                    }
                });
                
                distribusiChart.data.labels = Object.keys(metodeCount);
                distribusiChart.data.datasets[0].data = Object.values(metodeCount);
                distribusiChart.update();
            }
        }

        // Load laporan data
        async function loadLaporanData() {
            try {
                // Update report dates
                const now = new Date();
                document.getElementById('currentDateReport').textContent = formatDate(now);
                document.getElementById('currentMonthReport').textContent = formatMonth(now);

                // Load laporan stats from API
                const response = await fetch('/keuangan/laporan-stats');
                const result = await response.json();
                
                if (result.success) {
                    const stats = result.data;
                    
                    document.getElementById('pendapatanHarian').textContent = formatCurrency(stats.pendapatan_harian || 0);
                    document.getElementById('transaksiHarian').textContent = stats.transaksi_harian || 0;
                    document.getElementById('pendapatanBulanan').textContent = formatCurrency(stats.pendapatan_bulanan || 0);
                    
                    const targetBulanan = 50000000;
                    const persentaseTarget = stats.pendapatan_bulanan > 0 ? Math.round((stats.pendapatan_bulanan / targetBulanan) * 100) : 0;
                    
                    document.getElementById('targetBulanan').textContent = formatCurrency(targetBulanan);
                    document.getElementById('persentaseTarget').textContent = persentaseTarget;
                    
                    // Load statistics with real data
                    loadLaporanStats(stats);
                } else {
                    console.error('Failed to load laporan stats');
                }
            } catch (error) {
                console.error('Error loading laporan data:', error);
            }
        }



        // Chart Creation Functions
        function createPendapatanChart() {
            const ctx = document.getElementById('pendapatanChart').getContext('2d');

            if (pendapatanChart) {
                pendapatanChart.destroy();
            }

            // Sample data for last 6 months
            const months = ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const data = [4500000, 5200000, 4800000, 6100000, 5500000, 6800000];

            pendapatanChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Pendapatan',
                        data: data,
                        backgroundColor: 'rgba(39, 174, 96, 0.2)',
                        borderColor: 'rgba(39, 174, 96, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Tren Pendapatan 6 Bulan Terakhir'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                }
                            }
                        }
                    }
                }
            });
        }

        function createDistribusiChart() {
            const ctx = document.getElementById('distribusiChart').getContext('2d');

            if (distribusiChart) {
                distribusiChart.destroy();
            }

            const labels = ['Transfer Bank', 'Tunai', 'Virtual Account', 'QRIS'];
            const data = [65, 15, 15, 5];

            distribusiChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#1e3c72',
                            '#2a5298',
                            '#28a745',
                            '#ffc107'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Metode Pembayaran'
                        }
                    }
                }
            });
        }

        function createTagihanChart() {
            const ctx = document.getElementById('tagihanChart').getContext('2d');

            if (tagihanChart) {
                tagihanChart.destroy();
            }

            // Gunakan data aktual dari API
            const chartData = window.tagihanChartData || {
                lunas: 0,
                menunggu: 0,
                belum: 0,
                tolak: 0
            };

            tagihanChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Lunas', 'Menunggu Verifikasi', 'Belum Bayar', 'Ditolak'],
                    datasets: [{
                        data: [chartData.lunas, chartData.menunggu, chartData.belum, chartData.tolak],
                        backgroundColor: ['#27ae60', '#f39c12', '#95a5a6', '#e74c3c'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        title: {
                            display: true,
                            text: 'Distribusi Status Tagihan Pendaftar',
                            font: {
                                size: 16,
                                weight: 'bold'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                    return `${label}: ${value} siswa (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }



        // Laporan Statistics
        async function loadLaporanStats(stats = null) {
            try {
                if (!stats) {
                    const response = await fetch('/keuangan/laporan-stats');
                    const result = await response.json();
                    if (result.success) {
                        stats = result.data;
                    } else {
                        return;
                    }
                }
                
                // Pendapatan per Jurusan
                const statJurusan = document.getElementById('statJurusan');
                statJurusan.innerHTML = '';
                
                if (stats.pendapatan_per_jurusan) {
                    stats.pendapatan_per_jurusan.forEach(jurusan => {
                        const item = document.createElement('li');
                        item.className = 'list-group-item d-flex justify-content-between';
                        item.innerHTML = `
                            <span>${jurusan.nama}</span>
                            <span class="money">${formatCurrency(jurusan.total_pendapatan || 0)}</span>
                        `;
                        statJurusan.appendChild(item);
                    });
                }

                // Metode Pembayaran
                const statMetode = document.getElementById('statMetode');
                statMetode.innerHTML = '';
                
                if (stats.metode_pembayaran) {
                    stats.metode_pembayaran.forEach(metode => {
                        const item = document.createElement('li');
                        item.className = 'list-group-item d-flex justify-content-between';
                        item.innerHTML = `
                            <span>${metode.metode}</span>
                            <span class="badge bg-primary">${metode.jumlah}</span>
                        `;
                        statMetode.appendChild(item);
                    });
                }

                // Status Tagihan
                const statTagihan = document.getElementById('statTagihan');
                statTagihan.innerHTML = '';
                
                if (stats.status_tagihan) {
                    Object.entries(stats.status_tagihan).forEach(([status, count]) => {
                        const item = document.createElement('li');
                        item.className = 'list-group-item d-flex justify-content-between';
                        item.innerHTML = `
                            <span>${status}</span>
                            <span class="badge bg-${status === 'Lunas' ? 'success' : 'warning'}">${count}</span>
                        `;
                        statTagihan.appendChild(item);
                    });
                }
            } catch (error) {
                console.error('Error loading laporan stats:', error);
            }
        }

        // Action Functions
        async function lihatDetailPembayaran(pembayaranId) {
            try {
                const response = await fetch(`/keuangan/detail-pembayaran/${pembayaranId}`);
                const result = await response.json();
                
                if (result.success) {
                    const pembayaran = result.data;
                    
                    console.log('Detail pembayaran received:', pembayaran);
                    console.log('Bukti transfer info:', pembayaran.bukti_info);
                    
                    document.getElementById('transaksiId').value = pembayaranId;
                    document.getElementById('modalNoTransaksi').textContent = pembayaran.no_transaksi;
                    document.getElementById('modalSiswa').textContent = pembayaran.nama_pendaftar || '-';
                    document.getElementById('modalJurusan').textContent = pembayaran.jurusan || '-';
                    document.getElementById('modalJumlah').textContent = formatCurrency(pembayaran.jumlah);
                    document.getElementById('modalMetode').textContent = pembayaran.metode;
                    
                    // Show bukti transfer if exists
                    const buktiImg = document.getElementById('buktiTransferImg');
                    const buktiText = document.getElementById('buktiTransferText');
                    const btnLihat = document.getElementById('btnLihatBukti');
                    
                    if (pembayaran.bukti_transfer) {
                        // Extract filename from path like berkas system
                        const filename = pembayaran.bukti_transfer.split('/').pop();
                        const buktiUrl = `/bukti/${filename}`;
                        
                        console.log('Bukti transfer path:', pembayaran.bukti_transfer);
                        console.log('Bukti transfer URL:', buktiUrl);
                        
                        buktiImg.src = buktiUrl;
                        buktiImg.style.display = 'block';
                        buktiText.style.display = 'none';
                        btnLihat.style.display = 'inline-block';
                        
                        // Add error handler for image loading
                        buktiImg.onerror = function() {
                            console.error('Failed to load image:', buktiUrl);
                            buktiImg.style.display = 'none';
                            buktiText.style.display = 'block';
                            buktiText.innerHTML = `<span class="text-danger">Gagal memuat bukti transfer</span><br><small>${pembayaran.bukti_transfer}</small>`;
                            btnLihat.style.display = 'none';
                        };
                        
                        // Store bukti path for full view
                        window.currentBuktiPath = buktiUrl;
                    } else {
                        buktiImg.style.display = 'none';
                        buktiText.style.display = 'block';
                        buktiText.textContent = 'Tidak ada bukti transfer';
                        btnLihat.style.display = 'none';
                        window.currentBuktiPath = null;
                    }
                    
                    // Reset form
                    document.getElementById('konfirmasiStatus').value = '';
                    document.getElementById('konfirmasiKeterangan').value = '';
                    
                    const modal = new bootstrap.Modal(document.getElementById('konfirmasiModal'));
                    modal.show();
                } else {
                    showToast('Gagal memuat detail pembayaran', 'error');
                }
            } catch (error) {
                console.error('Error loading detail:', error);
                showToast('Gagal memuat detail pembayaran', 'error');
            }
        }
        
        async function verifikasiPembayaran(pembayaranId, status) {
            if (!confirm(`Apakah Anda yakin ingin ${status === 'Dikonfirmasi' ? 'menerima' : 'menolak'} pembayaran ini?`)) {
                return;
            }
            
            const keterangan = prompt(`Masukkan keterangan untuk ${status.toLowerCase()} pembayaran:`) || '';
            
            try {
                const response = await fetch(`/keuangan/verifikasi-pembayaran/${pembayaranId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        status: status,
                        keterangan: keterangan
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    loadPembayaranData();
                    loadDashboardData();
                } else {
                    showToast(result.message || 'Gagal memverifikasi pembayaran', 'error');
                }
            } catch (error) {
                console.error('Error verifying pembayaran:', error);
                showToast('Gagal memverifikasi pembayaran', 'error');
            }
        }

        async function konfirmasiPembayaran(pembayaranId) {
            try {
                const response = await fetch(`/api/pembayaran/${pembayaranId}`);
                const result = await response.json();
                
                if (!result.success) {
                    showToast('Gagal memuat data pembayaran', 'error');
                    return;
                }
                
                const pembayaran = result.data;
                
                document.getElementById('transaksiId').value = pembayaranId;
                document.getElementById('modalNoTransaksi').textContent = pembayaran.no_transaksi;
                document.getElementById('modalSiswa').textContent = pembayaran.pendaftar?.user?.nama || '-';
                document.getElementById('modalJurusan').textContent = pembayaran.pendaftar?.jurusan?.nama || '-';
                document.getElementById('modalJumlah').textContent = formatCurrency(pembayaran.jumlah);
                document.getElementById('modalMetode').textContent = pembayaran.metode;

                // Reset form
                document.getElementById('konfirmasiStatus').value = '';
                document.getElementById('konfirmasiKeterangan').value = '';

                const modal = new bootstrap.Modal(document.getElementById('konfirmasiModal'));
                modal.show();
            } catch (error) {
                console.error('Error loading pembayaran detail:', error);
                showToast('Gagal memuat detail pembayaran', 'error');
            }
        }
        
        async function simpanKonfirmasi() {
            const pembayaranId = parseInt(document.getElementById('transaksiId').value);
            const status = document.getElementById('konfirmasiStatus').value;
            const keterangan = document.getElementById('konfirmasiKeterangan').value;

            if (!status) {
                showToast('Pilih status konfirmasi terlebih dahulu!', 'error');
                return;
            }

            try {
                const response = await fetch(`/keuangan/verifikasi-pembayaran/${pembayaranId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        status: status,
                        keterangan: keterangan
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('konfirmasiModal')).hide();
                    
                    // Reload data
                    loadSectionData('dashboard');
                    loadSectionData('pembayaran');
                } else {
                    showToast(result.message || 'Gagal menyimpan konfirmasi', 'error');
                }
            } catch (error) {
                console.error('Error saving konfirmasi:', error);
                showToast('Gagal menyimpan konfirmasi', 'error');
            }
        }

        // Utility Functions
        function getStatusColor(status) {
            switch (status) {
                case 'Dikonfirmasi': return 'success';
                case 'Menunggu Konfirmasi': return 'warning';
                case 'Ditolak': return 'danger';
                case 'Terbayar': return 'success';
                case 'Belum Bayar': return 'warning';
                case 'Dibayar': return 'success';
                default: return 'secondary';
            }
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        function formatMonth(date) {
            return date.toLocaleDateString('id-ID', {
                month: 'long',
                year: 'numeric'
            });
        }

        function showFilterModal() {
            showToast('Filter modal akan ditampilkan', 'success');
        }

        function showTagihanModal() {
            showToast('Modal tambah tagihan akan ditampilkan', 'success');
        }





        function printLaporan() {
            window.print();
        }
        
        function printDetailTagihan() {
            const modalContent = document.getElementById('detailTagihanModal').querySelector('.modal-body').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Detail Transaksi Tagihan</title>
                        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .money { font-family: 'Courier New', monospace; font-weight: bold; }
                            @media print { .no-print { display: none; } }
                        </style>
                    </head>
                    <body>
                        <div class="container mt-4">
                            <h3 class="text-center mb-4">Detail Transaksi Tagihan</h3>
                            ${modalContent}
                        </div>
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }



        function lihatBuktiFull() {
            if (window.currentBuktiPath) {
                // Buat modal untuk menampilkan bukti transfer ukuran penuh
                const modalHtml = `
                    <div class="modal fade" id="buktiFullModal" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Bukti Transfer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="${window.currentBuktiPath}" alt="Bukti Transfer" class="img-fluid" style="max-width: 100%; height: auto;">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    <a href="${window.currentBuktiPath}" target="_blank" class="btn btn-primary">
                                        <i class="fas fa-external-link-alt me-1"></i>Buka di Tab Baru
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Remove existing modal if any
                const existingModal = document.getElementById('buktiFullModal');
                if (existingModal) {
                    existingModal.remove();
                }
                
                // Add modal to body
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('buktiFullModal'));
                modal.show();
                
                // Remove modal from DOM when hidden
                document.getElementById('buktiFullModal').addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
            }
        }

        function showToast(message, type) {
            const toastElement = type === 'success' ?
                document.getElementById('successToast') :
                document.getElementById('errorToast');

            const messageElement = type === 'success' ?
                document.getElementById('successMessage') :
                document.getElementById('errorMessage');

            messageElement.textContent = message;

            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            // Set user info
            document.getElementById('userName').textContent = currentUser.nama;
            document.getElementById('dashboardUserName').textContent = currentUser.nama;

            // Sidebar navigation
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const targetSection = this.getAttribute('data-target');
                    showSection(targetSection);
                });
            });

            // Date and time
            function updateDateTime() {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID', options);
                document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID');
            }
            setInterval(updateDateTime, 1000);
            updateDateTime();

            // Add CSRF token to meta
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const csrfMeta = document.createElement('meta');
                csrfMeta.name = 'csrf-token';
                csrfMeta.content = '{{ csrf_token() }}';
                document.head.appendChild(csrfMeta);
            }
            
            // Auto refresh pembayaran data every 30 seconds
            setInterval(function() {
                if (document.getElementById('pembayaran').classList.contains('active')) {
                    loadPembayaranData();
                }
            }, 30000);
            
            // Load initial data
            loadDashboardData();
        });
    </script>
</body>

</html>