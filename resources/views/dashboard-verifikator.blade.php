<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Verifikator - SMK Bakti Nusantara 666</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1e3c72;
            --secondary: #2a5298;
            --success: #28a745;
            --warning: #ffc107;
            --info: #17a2b8;
        }

        .sidebar {
            background: var(--primary);
            color: white;
            min-height: 100vh;
            transition: all 0.3s;
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
            border-left: 4px solid var(--primary);
            transition: transform 0.3s ease;
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

        .btn-primary-custom {
            background: var(--primary);
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
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



        .status-badge {
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
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
                        <i class="fas fa-user-check me-2"></i>
                        VERIFIKATOR
                    </h5>
                    <p class="text-center small mb-4">SMK Bakti Nusantara 666</p>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" data-target="dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="verifikasi">
                                <i class="fas fa-clipboard-check me-2"></i>Verifikasi Berkas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="verifikasi-data">
                                <i class="fas fa-user-check me-2"></i>Verifikasi Data
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="menunggu">
                                <i class="fas fa-clock me-2"></i>Menunggu Verifikasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="riwayat">
                                <i class="fas fa-history me-2"></i>Riwayat Verifikasi
                            </a>
                        </li>

                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content-area">
                <!-- Header -->
                <nav class="navbar navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h6">Dashboard Verifikator</span>
                        <div class="d-flex align-items-center">
                            <span class="me-3" id="userName">{{ $user->nama }}</span>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin logout?')">
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
                                                <h2 class="fw-bold mb-2 text-primary">
                                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard Verifikator
                                                </h2>
                                                <p class="text-muted mb-0">
                                                    Selamat datang, <strong id="dashboardUserName">Verifikator</strong>!
                                                    <span class="badge bg-primary ms-2">Verifikator</span>
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
                                <div class="stat-card warning">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Menunggu Verifikasi</h5>
                                            <h2 class="mb-0" id="menungguVerifikasi">0</h2>
                                            <small><i class="fas fa-hourglass-half me-1"></i>Perlu tindakan
                                                segera</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-clock fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card success">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Terverifikasi Hari Ini</h5>
                                            <h2 class="mb-0" id="terverifikasiHariIni">0</h2>
                                            <small><i class="fas fa-check-circle me-1"></i>Pekerjaan selesai</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-check fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Total Terverifikasi</h5>
                                            <h2 class="mb-0" id="totalTerverifikasi">0</h2>
                                            <small><i class="fas fa-chart-line me-1"></i>Akumulasi</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-list-check fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <!-- Quick Actions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i class="fas fa-rocket me-2 text-warning"></i>Quick Actions
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-xl-3 col-md-4 col-6 mb-3">
                                                <button class="btn btn-primary-custom text-white w-100 py-3"
                                                    onclick="showSection('verifikasi')">
                                                    <i class="fas fa-clipboard-check fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Verifikasi Berkas</span>
                                                </button>
                                            </div>
                                            <div class="col-xl-3 col-md-4 col-6 mb-3">
                                                <button class="btn btn-success w-100 py-3"
                                                    style="border-radius: 10px;" onclick="showSection('verifikasi-data')">
                                                    <i class="fas fa-user-check fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Verifikasi Data</span>
                                                </button>
                                            </div>
                                            <div class="col-xl-3 col-md-4 col-6 mb-3">
                                                <button class="btn btn-warning w-100 py-3 text-dark"
                                                    style="border-radius: 10px;" onclick="showSection('menunggu')">
                                                    <i class="fas fa-clock fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Menunggu</span>
                                                </button>
                                            </div>
                                            <div class="col-xl-3 col-md-4 col-6 mb-3">
                                                <button class="btn btn-info w-100 py-3 text-white"
                                                    style="border-radius: 10px;" onclick="showSection('riwayat')">
                                                    <i class="fas fa-history fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Riwayat</span>
                                                </button>
                                            </div>
                                            <div class="col-xl-3 col-md-4 col-6 mb-3">
                                                <button class="btn btn-secondary w-100 py-3 text-white"
                                                    style="border-radius: 10px;" onclick="testFileAccess()">
                                                    <i class="fas fa-tools fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Test File</span>
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-custom">
                                    <div
                                        class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Aktivitas
                                            Verifikasi Terbaru</h5>
                                        <span class="badge bg-primary">Real-time</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Waktu</th>
                                                        <th>No. Pendaftaran</th>
                                                        <th>Nama</th>
                                                        <th>Status</th>
                                                        <th>Verifikator</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="recentActivity">
                                                    <!-- Recent activity will be loaded here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verifikasi Berkas Section -->
                    <div id="verifikasi" class="section">
                        <div class="card card-custom">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2 text-primary"></i>Verifikasi Berkas Pendaftar</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0">Berkas yang Perlu Diverifikasi ({{ $berkasMenunggu->count() }} berkas)</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>No. Pendaftaran</th>
                                                                <th>Nama</th>
                                                                <th>Jurusan</th>
                                                                <th>Jenis Berkas</th>
                                                                <th>Tanggal Upload</th>
                                                                <th>Ukuran</th>
                                                                <th>Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($berkasMenunggu as $berkas)
                                                                <tr>
                                                                    <td>{{ $berkas->pendaftar->no_pendaftaran ?? '-' }}</td>
                                                                    <td>{{ $berkas->pendaftar->user->nama ?? '-' }}</td>
                                                                    <td>{{ $berkas->pendaftar->jurusan->nama ?? '-' }}</td>
                                                                    <td>
                                                                        <span class="badge bg-info">{{ strtoupper($berkas->jenis) }}</span>
                                                                    </td>
                                                                    <td>{{ $berkas->created_at->format('d M Y') }}</td>
                                                                    <td>{{ $berkas->ukuran_kb ?? 0 }} KB</td>
                                                                    <td>
                                                                        @php
                                                                            $filePath = storage_path('app/public/berkas/' . $berkas->nama_file);
                                                                            $fileExists = file_exists($filePath);
                                                                        @endphp
                                                                        @if($fileExists)
                                                                            <button class="btn btn-sm btn-info me-1" onclick="lihatBerkasFile('{{ $berkas->nama_file }}', '{{ $berkas->jenis }}')">
                                                                                <i class="fas fa-eye me-1"></i>Lihat
                                                                            </button>
                                                                        @else
                                                                            <button class="btn btn-sm btn-secondary me-1" disabled title="File tidak ditemukan">
                                                                                <i class="fas fa-exclamation-triangle me-1"></i>File Hilang
                                                                            </button>
                                                                        @endif
                                                                        <button class="btn btn-sm btn-primary" onclick="verifikasiBerkas({{ $berkas->id }})">
                                                                            <i class="fas fa-clipboard-check me-1"></i>Verifikasi
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center text-muted">Tidak ada berkas yang perlu diverifikasi</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Verifikasi Data Section -->
                    <div id="verifikasi-data" class="section">
                        <div class="card card-custom">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0"><i class="fas fa-user-check me-2 text-primary"></i>Verifikasi Data Formulir</h5>
                            </div>
                            <div class="card-body">
                                <!-- Tab Navigation -->
                                <ul class="nav nav-tabs" id="dataTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="data-siswa-tab" data-bs-toggle="tab" data-bs-target="#data-siswa" type="button" role="tab">
                                            <i class="fas fa-user me-2"></i>Data Siswa
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="data-ortu-tab" data-bs-toggle="tab" data-bs-target="#data-ortu" type="button" role="tab">
                                            <i class="fas fa-users me-2"></i>Data Orang Tua
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="asal-sekolah-tab" data-bs-toggle="tab" data-bs-target="#asal-sekolah" type="button" role="tab">
                                            <i class="fas fa-school me-2"></i>Asal Sekolah
                                        </button>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content" id="dataTabContent">
                                    <!-- Data Siswa Tab -->
                                    <div class="tab-pane fade show active" id="data-siswa" role="tabpanel">
                                        <div class="mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No. Pendaftaran</th>
                                                            <th>Nama</th>
                                                            <th>Jurusan</th>
                                                            <th>NIK</th>
                                                            <th>NISN</th>
                                                            <th>Tanggal Lahir</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="dataSiswaTable">
                                                        <!-- Data siswa will be loaded here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Orang Tua Tab -->
                                    <div class="tab-pane fade" id="data-ortu" role="tabpanel">
                                        <div class="mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No. Pendaftaran</th>
                                                            <th>Nama Siswa</th>
                                                            <th>Jurusan</th>
                                                            <th>Nama Ayah</th>
                                                            <th>Nama Ibu</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="dataOrtuTable">
                                                        <!-- Data orang tua will be loaded here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Asal Sekolah Tab -->
                                    <div class="tab-pane fade" id="asal-sekolah" role="tabpanel">
                                        <div class="mt-3">
                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            <th>No. Pendaftaran</th>
                                                            <th>Nama Siswa</th>
                                                            <th>Jurusan</th>
                                                            <th>Nama Sekolah</th>
                                                            <th>Tahun Lulus</th>
                                                            <th>Nilai Rata-rata</th>
                                                            <th>Status</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="asalSekolahTable">
                                                        <!-- Data asal sekolah will be loaded here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menunggu Verifikasi Section -->
                    <div id="menunggu" class="section">
                        <div class="card card-custom">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0"><i class="fas fa-clock me-2 text-warning"></i>Data Menunggu Verifikasi
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No. Pendaftaran</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Jurusan</th>
                                                <th>Tanggal Daftar</th>
                                                <th>Lama Menunggu</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="menungguTable">
                                            <!-- Data menunggu will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Verifikasi Section -->
                    <div id="riwayat" class="section">
                        <div class="card card-custom">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Riwayat Verifikasi</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0">Statistik Verifikasi</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small class="text-muted">Total Verifikasi</small>
                                                        <h4 id="totalVerifikasiStat">0</h4>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">Terverifikasi</small>
                                                        <h5 id="terverifikasiStat">0</h5>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">Ditolak</small>
                                                        <h5 id="ditolakStat">0</h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Tanggal</th>
                                                <th>No. Pendaftaran</th>
                                                <th>Nama</th>
                                                <th>Status</th>
                                                <th>Keterangan</th>

                                                <th>Verifikator</th>
                                            </tr>
                                        </thead>
                                        <tbody id="riwayatTable">
                                            <!-- Riwayat data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Modal Verifikasi -->
    <div class="modal fade" id="verifikasiModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Berkas - <span id="modalJenisBerkas"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Data Pendaftar</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>No. Pendaftaran:</strong></td>
                                    <td id="modalNoPendaftaran"></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td id="modalNama"></td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td id="modalEmail"></td>
                                </tr>
                                <tr>
                                    <td><strong>Jurusan:</strong></td>
                                    <td id="modalJurusan"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Detail Berkas</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Jenis:</strong></td>
                                    <td id="modalJenis"></td>
                                </tr>
                                <tr>
                                    <td><strong>Nama File:</strong></td>
                                    <td id="modalNamaFile"></td>
                                </tr>
                                <tr>
                                    <td><strong>Ukuran:</strong></td>
                                    <td id="modalUkuran"></td>
                                </tr>
                                <tr>
                                    <td><strong>Upload:</strong></td>
                                    <td id="modalTanggalUpload"></td>
                                </tr>
                            </table>
                            <div class="mt-3">
                                <button class="btn btn-info btn-sm" onclick="lihatBerkas()" id="btnLihatBerkas">
                                    <i class="fas fa-eye me-1"></i>Lihat Berkas
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Hasil Verifikasi</h6>
                        <form id="formVerifikasi">
                            <input type="hidden" id="berkasId">
                            <div class="mb-3">
                                <label class="form-label">Status Verifikasi</label>
                                <select class="form-control" id="statusVerifikasi" required>
                                    <option value="">Pilih Status</option>
                                    <option value="terima">Terima</option>
                                    <option value="tolak">Tolak</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea class="form-control" id="catatanVerifikasi" rows="3" placeholder="Masukkan catatan verifikasi..."></textarea>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="simpanVerifikasi()">Simpan Verifikasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Preview Berkas -->
    <div class="modal fade" id="previewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Berkas - <span id="previewJenis"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="previewContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a id="downloadLink" class="btn btn-primary" target="_blank">
                        <i class="fas fa-download me-1"></i>Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Data -->
    <div class="modal fade" id="detailDataModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalTitle">Detail Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <!-- Detail content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" id="btnTerimaDetail" onclick="verifikasiFromDetail('terima')">Terima</button>
                    <button type="button" class="btn btn-danger" id="btnTolakDetail" onclick="verifikasiFromDetail('tolak')">Tolak</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let currentUser = {
            id: 2,
            nama: "Budi S.Kom",
            email: "verifikasi@smkbn666.sch.id",
            role: "Verifikator"
        };

        // Database simulation (shared with admin dashboard)
        let database = JSON.parse(localStorage.getItem('ppdb_database')) || {
            jurusan: [
                { id: 1, kode: "PPLG", nama: "Pengembangan Perangkat Lunak dan Gim", kuota: 40, terisi: 32, status: "Aktif" },
                { id: 2, kode: "AKT", nama: "Akuntansi", kuota: 35, terisi: 28, status: "Aktif" }
            ],
            pendaftar: [
                {
                    id: 1,
                    no_pendaftaran: "PPDB2024001",
                    nama: "Andi Wijaya",
                    email: "andi@email.com",
                    telepon: "08123456789",
                    jurusan_id: 1,
                    gelombang_id: 1,
                    wilayah_id: 1,
                    status: "Menunggu",
                    tanggal_daftar: "2024-01-15",
                    verifikasi: null
                },
                {
                    id: 2,
                    no_pendaftaran: "PPDB2024002",
                    nama: "Siti Rahma",
                    email: "siti@email.com",
                    telepon: "08123456788",
                    jurusan_id: 2,
                    gelombang_id: 1,
                    wilayah_id: 1,
                    status: "Menunggu",
                    tanggal_daftar: "2024-01-14",
                    verifikasi: null
                },
                {
                    id: 3,
                    no_pendaftaran: "PPDB2024003",
                    nama: "Budi Santoso",
                    email: "budi@email.com",
                    telepon: "08123456787",
                    jurusan_id: 1,
                    gelombang_id: 1,
                    wilayah_id: 2,
                    status: "Terverifikasi",
                    tanggal_daftar: "2024-01-13",
                    verifikasi: {
                        tanggal: "2024-01-13 14:30",
                        status: "Terverifikasi",
                        keterangan: "Berkas lengkap dan valid",
                        verifikator: "Budi S.Kom",
                        waktu_proses: "15"
                    }
                }
            ],
            riwayat_verifikasi: [
                {
                    id: 1,
                    pendaftar_id: 3,
                    tanggal: "2024-01-13 14:30",
                    status: "Terverifikasi",
                    keterangan: "Berkas lengkap dan valid",
                    waktu_proses: "15",
                    verifikator: "Budi S.Kom"
                }
            ]
        };

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
                case 'verifikasi':
                    loadVerifikasiData();
                    break;
                case 'verifikasi-data':
                    loadVerifikasiDataFormulir();
                    break;
                case 'menunggu':
                    loadMenungguData();
                    break;
                case 'riwayat':
                    loadRiwayatData();
                    break;

            }
        }

        // Load dashboard data
        function loadDashboardData() {
            // Load real-time stats
            fetch('/verifikator/dashboard-stats')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('menungguVerifikasi').textContent = data.stats.menunggu_verifikasi;
                    document.getElementById('terverifikasiHariIni').textContent = data.stats.terverifikasi_hari_ini;
                    document.getElementById('totalTerverifikasi').textContent = data.stats.total_terverifikasi;
                    document.getElementById('rataWaktu').textContent = data.stats.rata_waktu + 'm';
                }
            })
            .catch(error => console.error('Error loading stats:', error));



            // Load recent activity
            loadRecentActivity();
        }

        // Load verifikasi data
        function loadVerifikasiData() {
            // Data sudah dimuat dari server-side, tidak perlu reload
            // Jika perlu refresh, reload halaman
        }

        // Load menunggu data
        function loadMenungguData() {
            fetch('/verifikator/data-menunggu')
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const table = document.getElementById('menungguTable');
                    table.innerHTML = '';
                    
                    const allData = [...(result.data.data_siswa || []), ...(result.data.data_ortu || []), ...(result.data.asal_sekolah || [])];
                    
                    if (allData.length === 0) {
                        table.innerHTML = '<tr><td colspan="8" class="text-center text-muted">Tidak ada data yang menunggu verifikasi</td></tr>';
                        return;
                    }
                    
                    allData.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.pendaftar?.no_pendaftaran || '-'}</td>
                            <td>${item.pendaftar?.user?.nama || item.nama || '-'}</td>
                            <td>${item.pendaftar?.user?.email || '-'}</td>
                            <td>${item.pendaftar?.jurusan?.nama || '-'}</td>
                            <td>${item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-'}</td>
                            <td>${calculateWaitingTime(item.created_at)}</td>
                            <td>
                                <span class="badge bg-warning">Menunggu Verifikasi</span>
                            </td>
                        `;
                        table.appendChild(row);
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('menungguTable').innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error memuat data</td></tr>';
            });
        }

        // Load riwayat data
        function loadRiwayatData() {
            fetch('/verifikator/riwayat-verifikasi')
            .then(response => response.json())
            .then(result => {
                const table = document.getElementById('riwayatTable');
                table.innerHTML = '';
                
                if (result.success && result.data.length > 0) {
                    result.data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${new Date(item.tgl_verifikasi).toLocaleDateString('id-ID')}</td>
                            <td>${item.no_pendaftaran || '-'}</td>
                            <td>${item.nama || '-'}</td>
                            <td><span class="badge bg-${item.status === 'Terverifikasi' ? 'success' : 'danger'}">${item.status}</span></td>
                            <td>${item.catatan || '-'}</td>

                            <td>${item.verifikator || 'Verifikator'}</td>
                        `;
                        table.appendChild(row);
                    });
                    updateRiwayatStats(result.stats);
                } else {
                    table.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Belum ada riwayat verifikasi</td></tr>';
                    updateRiwayatStats({total: 0, terverifikasi: 0, ditolak: 0});
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('riwayatTable').innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error memuat riwayat</td></tr>';
            });
        }



        // Verifikasi Functions
        function verifikasiBerkas(berkasId) {
            console.log('Loading berkas detail for ID:', berkasId);
            
            fetch(`/verifikator/berkas/${berkasId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                console.log('Berkas detail loaded:', result);
                
                if (result.success) {
                    const berkas = result.data;
                    
                    document.getElementById('berkasId').value = berkasId;
                    document.getElementById('modalJenisBerkas').textContent = berkas.jenis.toUpperCase();
                    document.getElementById('modalNoPendaftaran').textContent = berkas.pendaftar.no_pendaftaran || '-';
                    document.getElementById('modalNama').textContent = berkas.pendaftar.user.nama || '-';
                    document.getElementById('modalEmail').textContent = berkas.pendaftar.user.email || '-';
                    document.getElementById('modalJurusan').textContent = berkas.pendaftar.jurusan ? berkas.pendaftar.jurusan.nama : '-';
                    
                    document.getElementById('modalJenis').textContent = berkas.jenis.toUpperCase();
                    document.getElementById('modalNamaFile').textContent = berkas.nama_file;
                    document.getElementById('modalUkuran').textContent = (berkas.ukuran_kb || 0) + ' KB';
                    document.getElementById('modalTanggalUpload').textContent = new Date(berkas.created_at).toLocaleDateString('id-ID');
                    
                    // Update tombol lihat berkas berdasarkan keberadaan file
                    const btnLihatBerkas = document.getElementById('btnLihatBerkas');
                    if (berkas.file_exists && berkas.file_url) {
                        btnLihatBerkas.className = 'btn btn-info btn-sm';
                        btnLihatBerkas.innerHTML = '<i class="fas fa-eye me-1"></i>Lihat Berkas';
                        btnLihatBerkas.onclick = function() {
                            lihatBerkasFile(berkas.nama_file, berkas.jenis);
                        };
                    } else {
                        btnLihatBerkas.className = 'btn btn-secondary btn-sm';
                        btnLihatBerkas.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>File Tidak Ditemukan';
                        btnLihatBerkas.onclick = function() {
                            showToast('File tidak ditemukan di server. Mungkin file telah dihapus atau dipindahkan.', 'error');
                        };
                    }
                    
                    // Reset form
                    document.getElementById('statusVerifikasi').value = '';
                    document.getElementById('catatanVerifikasi').value = '';
                    
                    const modal = new bootstrap.Modal(document.getElementById('verifikasiModal'));
                    modal.show();
                } else {
                    showToast('Gagal memuat data berkas: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memuat data berkas', 'error');
            });
        }

        function simpanVerifikasi() {
            const berkasId = document.getElementById('berkasId').value;
            const status = document.getElementById('statusVerifikasi').value;
            const catatan = document.getElementById('catatanVerifikasi').value;

            if (!status) {
                showToast('Pilih status verifikasi terlebih dahulu!', 'error');
                return;
            }

            if (!confirm(`Apakah Anda yakin ingin ${status === 'terima' ? 'menerima' : 'menolak'} berkas ini?`)) {
                return;
            }

            console.log('Saving verification:', { berkasId, status, catatan });

            fetch(`/verifikator/verifikasi-berkas/${berkasId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: status,
                    catatan: catatan
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                console.log('Verification result:', result);
                
                if (result.success) {
                    const statusText = status === 'terima' ? 'diterima' : 'ditolak';
                    showToast(`Berkas berhasil ${statusText}!`, 'success');
                    bootstrap.Modal.getInstance(document.getElementById('verifikasiModal')).hide();
                    
                    // Reload section instead of entire page
                    setTimeout(() => {
                        loadVerifikasiData();
                    }, 1000);
                } else {
                    showToast('Gagal memverifikasi berkas: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memverifikasi berkas', 'error');
            });
        }
        
        // Load verifikasi data formulir
        function loadVerifikasiDataFormulir() {
            fetch('/verifikator/data-menunggu')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    loadDataSiswaTable(result.data.data_siswa || []);
                    loadDataOrtuTable(result.data.data_ortu || []);
                    loadAsalSekolahTable(result.data.asal_sekolah || []);
                } else {
                    console.error('Failed to load data:', result.message);
                    showToast('Gagal memuat data verifikasi: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memuat data', 'error');
            });
        }
        
        function loadDataSiswaTable(data) {
            const table = document.getElementById('dataSiswaTable');
            table.innerHTML = '';
            
            if (!data || data.length === 0) {
                table.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Tidak ada data siswa yang perlu diverifikasi</td></tr>';
                return;
            }
            
            data.forEach(item => {
                const itemId = item.pendaftar_id;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.pendaftar?.no_pendaftaran || '-'}</td>
                    <td>${item.nama || '-'}</td>
                    <td>${item.pendaftar?.jurusan?.nama || '-'}</td>
                    <td>${item.nik || '-'}</td>
                    <td>${item.nish || '-'}</td>
                    <td>${item.tgl_lahir || '-'}</td>
                    <td><span class="badge bg-warning">Menunggu</span></td>
                    <td>
                        <button class="btn btn-sm btn-info me-1" onclick="lihatDetailData('siswa', ${itemId})">
                            <i class="fas fa-eye me-1"></i>Lihat
                        </button>
                        <button class="btn btn-sm btn-success me-1" onclick="verifikasiData('siswa', ${itemId}, 'terima')">
                            <i class="fas fa-check me-1"></i>Terima
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="verifikasiData('siswa', ${itemId}, 'tolak')">
                            <i class="fas fa-times me-1"></i>Tolak
                        </button>
                    </td>
                `;
                table.appendChild(row);
            });
        }
        
        function loadDataOrtuTable(data) {
            const table = document.getElementById('dataOrtuTable');
            table.innerHTML = '';
            
            if (!data || data.length === 0) {
                table.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Tidak ada data orang tua yang perlu diverifikasi</td></tr>';
                return;
            }
            
            data.forEach(item => {
                const itemId = item.pendaftar_id;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.pendaftar?.no_pendaftaran || '-'}</td>
                    <td>${item.pendaftar?.user?.nama || '-'}</td>
                    <td>${item.pendaftar?.jurusan?.nama || '-'}</td>
                    <td>${item.nama_ayah || '-'}</td>
                    <td>${item.nama_ibu || '-'}</td>
                    <td><span class="badge bg-warning">Menunggu</span></td>
                    <td>
                        <button class="btn btn-sm btn-info me-1" onclick="lihatDetailData('ortu', ${itemId})">
                            <i class="fas fa-eye me-1"></i>Lihat
                        </button>
                        <button class="btn btn-sm btn-success me-1" onclick="verifikasiData('ortu', ${itemId}, 'terima')">
                            <i class="fas fa-check me-1"></i>Terima
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="verifikasiData('ortu', ${itemId}, 'tolak')">
                            <i class="fas fa-times me-1"></i>Tolak
                        </button>
                    </td>
                `;
                table.appendChild(row);
            });
        }
        
        function loadAsalSekolahTable(data) {
            const table = document.getElementById('asalSekolahTable');
            table.innerHTML = '';
            
            if (!data || data.length === 0) {
                table.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Tidak ada data asal sekolah yang perlu diverifikasi</td></tr>';
                return;
            }
            
            data.forEach(item => {
                const itemId = item.pendaftar_id;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.pendaftar?.no_pendaftaran || '-'}</td>
                    <td>${item.pendaftar?.user?.nama || '-'}</td>
                    <td>${item.pendaftar?.jurusan?.nama || '-'}</td>
                    <td>${item.nama_sekolah || '-'}</td>
                    <td>${item.tahun_lulus || '-'}</td>
                    <td>${item.nilai_rata || '-'}</td>
                    <td><span class="badge bg-warning">Menunggu</span></td>
                    <td>
                        <button class="btn btn-sm btn-info me-1" onclick="lihatDetailData('sekolah', ${itemId})">
                            <i class="fas fa-eye me-1"></i>Lihat
                        </button>
                        <button class="btn btn-sm btn-success me-1" onclick="verifikasiData('sekolah', ${itemId}, 'terima')">
                            <i class="fas fa-check me-1"></i>Terima
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="verifikasiData('sekolah', ${itemId}, 'tolak')">
                            <i class="fas fa-times me-1"></i>Tolak
                        </button>
                    </td>
                `;
                table.appendChild(row);
            });
        }
        
        function verifikasiData(jenis, id, status) {
            // Validasi parameter
            if (!id || id === 0 || id === 'undefined' || id === 'unknown') {
                showToast('ID data tidak valid', 'error');
                return;
            }
            
            if (!confirm(`Apakah Anda yakin ingin ${status === 'terima' ? 'menerima' : 'menolak'} data ini?`)) {
                return;
            }
            
            let endpoint = '';
            switch(jenis) {
                case 'siswa':
                    endpoint = `/verifikator/verifikasi-data-siswa/${id}`;
                    break;
                case 'ortu':
                    endpoint = `/verifikator/verifikasi-data-ortu/${id}`;
                    break;
                case 'sekolah':
                    endpoint = `/verifikator/verifikasi-asal-sekolah/${id}`;
                    break;
            }
            
            if (!endpoint) {
                showToast('Endpoint tidak valid', 'error');
                return;
            }
            
            fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    status: status
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    showToast(result.message, 'success');
                    loadVerifikasiDataFormulir(); // Reload data
                } else {
                    showToast('Gagal memverifikasi data: ' + (result.message || 'Unknown error'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memverifikasi data: ' + error.message, 'error');
            });
        }
        
        // Global variables for detail modal
        let currentDetailType = '';
        let currentDetailId = 0;
        
        function lihatDetailData(jenis, id) {
            // Pastikan id tidak undefined
            if (!id || id === 0 || id === 'undefined') {
                showToast('ID data tidak valid', 'error');
                return;
            }
            
            currentDetailType = jenis;
            currentDetailId = id;
            
            let endpoint = '';
            let title = '';
            
            switch(jenis) {
                case 'siswa':
                    endpoint = `/verifikator/detail-data-siswa/${id}`;
                    title = 'Detail Data Siswa';
                    break;
                case 'ortu':
                    endpoint = `/verifikator/detail-data-ortu/${id}`;
                    title = 'Detail Data Orang Tua';
                    break;
                case 'sekolah':
                    endpoint = `/verifikator/detail-asal-sekolah/${id}`;
                    title = 'Detail Data Asal Sekolah';
                    break;
            }
            
            document.getElementById('detailModalTitle').textContent = title;
            document.getElementById('detailModalBody').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</div>';
            
            fetch(endpoint)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.success) {
                    displayDetailData(jenis, result.data);
                    const modal = new bootstrap.Modal(document.getElementById('detailDataModal'));
                    modal.show();
                } else {
                    showToast('Gagal memuat detail data: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Terjadi kesalahan saat memuat detail data', 'error');
            });
        }
        
        function displayDetailData(jenis, data) {
            let content = '';
            
            if (jenis === 'siswa') {
                content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Pendaftar</h6>
                            <table class="table table-sm">
                                <tr><td><strong>No. Pendaftaran:</strong></td><td>${data.pendaftar.no_pendaftaran || '-'}</td></tr>
                                <tr><td><strong>Nama User:</strong></td><td>${data.pendaftar.user.nama || '-'}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${data.pendaftar.user.email || '-'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Data Siswa</h6>
                            <table class="table table-sm">
                                <tr><td><strong>NIK:</strong></td><td>${data.nik || '-'}</td></tr>
                                <tr><td><strong>NISN:</strong></td><td>${data.nish || '-'}</td></tr>
                                <tr><td><strong>Nama Lengkap:</strong></td><td>${data.nama || '-'}</td></tr>
                                <tr><td><strong>Jenis Kelamin:</strong></td><td>${data.jk || '-'}</td></tr>
                                <tr><td><strong>Tempat Lahir:</strong></td><td>${data.tmp_lahir || '-'}</td></tr>
                                <tr><td><strong>Tanggal Lahir:</strong></td><td>${data.tgl_lahir || '-'}</td></tr>
                                <tr><td><strong>Agama:</strong></td><td>${data.agama || '-'}</td></tr>
                                <tr><td><strong>Alamat:</strong></td><td>${data.alamat || '-'}</td></tr>
                                <tr><td><strong>Nomor HP:</strong></td><td>${data.nomor_hp || '-'}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${data.email || '-'}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
            } else if (jenis === 'ortu') {
                content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Pendaftar</h6>
                            <table class="table table-sm">
                                <tr><td><strong>No. Pendaftaran:</strong></td><td>${data.pendaftar.no_pendaftaran || '-'}</td></tr>
                                <tr><td><strong>Nama Siswa:</strong></td><td>${data.pendaftar.user.nama || '-'}</td></tr>
                            </table>
                            <h6>Data Ayah</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Nama Ayah:</strong></td><td>${data.nama_ayah || '-'}</td></tr>
                                <tr><td><strong>Pekerjaan:</strong></td><td>${data.pekerjaan_ayah || '-'}</td></tr>
                                <tr><td><strong>Penghasilan:</strong></td><td>${data.penghasilan_ayah || '-'}</td></tr>
                                <tr><td><strong>No. HP:</strong></td><td>${data.hp_ayah || '-'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Data Ibu</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Nama Ibu:</strong></td><td>${data.nama_ibu || '-'}</td></tr>
                                <tr><td><strong>Pekerjaan:</strong></td><td>${data.pekerjaan_ibu || '-'}</td></tr>
                                <tr><td><strong>Penghasilan:</strong></td><td>${data.penghasilan_ibu || '-'}</td></tr>
                                <tr><td><strong>No. HP:</strong></td><td>${data.hp_ibu || '-'}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
            } else if (jenis === 'sekolah') {
                content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Pendaftar</h6>
                            <table class="table table-sm">
                                <tr><td><strong>No. Pendaftaran:</strong></td><td>${data.pendaftar.no_pendaftaran || '-'}</td></tr>
                                <tr><td><strong>Nama Siswa:</strong></td><td>${data.pendaftar.user.nama || '-'}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Data Asal Sekolah</h6>
                            <table class="table table-sm">
                                <tr><td><strong>NPSN:</strong></td><td>${data.npsn || '-'}</td></tr>
                                <tr><td><strong>Nama Sekolah:</strong></td><td>${data.nama_sekolah || '-'}</td></tr>
                                <tr><td><strong>Status Sekolah:</strong></td><td>${data.status_sekolah || '-'}</td></tr>
                                <tr><td><strong>Alamat Sekolah:</strong></td><td>${data.alamat_sekolah || '-'}</td></tr>
                                <tr><td><strong>Kabupaten:</strong></td><td>${data.kabupaten || '-'}</td></tr>
                                <tr><td><strong>Tahun Lulus:</strong></td><td>${data.tahun_lulus || '-'}</td></tr>
                                <tr><td><strong>Nilai Rata-rata:</strong></td><td>${data.nilai_rata || '-'}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
            }
            
            document.getElementById('detailModalBody').innerHTML = content;
        }
        
        function verifikasiFromDetail(status) {
            if (!currentDetailId || currentDetailId === 0) {
                showToast('ID data tidak valid', 'error');
                return;
            }
            
            if (!confirm(`Apakah Anda yakin ingin ${status === 'terima' ? 'menerima' : 'menolak'} data ini?`)) {
                return;
            }
            
            // Tutup modal detail terlebih dahulu
            const detailModal = bootstrap.Modal.getInstance(document.getElementById('detailDataModal'));
            if (detailModal) {
                detailModal.hide();
            }
            
            // Lakukan verifikasi
            verifikasiData(currentDetailType, currentDetailId, status);
        }

        // Utility Functions
        function lihatBerkasFile(namaFile, jenis) {
            const fileUrl = `/berkas/${namaFile}`;
            const fileExt = namaFile.split('.').pop().toLowerCase();
            
            document.getElementById('previewJenis').textContent = jenis.toUpperCase();
            document.getElementById('downloadLink').href = fileUrl;
            
            const previewContent = document.getElementById('previewContent');
            previewContent.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Memuat preview...</div>';
            
            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                const img = new Image();
                img.onload = function() {
                    previewContent.innerHTML = `<img src="${fileUrl}" class="img-fluid" style="max-height: 500px; border: 1px solid #ddd;">`;
                };
                img.onerror = function() {
                    console.error('Failed to load image:', fileUrl);
                    previewContent.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i><br>
                            <strong>File tidak dapat dimuat</strong><br>
                            <small>File: ${namaFile}</small><br>
                            <small>Path: ${fileUrl}</small><br>
                            <small class="text-muted">File mungkin tidak ditemukan, rusak, atau ada masalah dengan server</small>
                        </div>
                    `;
                };
                img.src = fileUrl;
            } else if (fileExt === 'pdf') {
                previewContent.innerHTML = `
                    <embed src="${fileUrl}" type="application/pdf" width="100%" height="500px">
                    <div class="alert alert-warning mt-2">
                        <small>Jika PDF tidak tampil, klik download untuk membuka file</small>
                    </div>
                `;
            } else {
                previewContent.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-file fa-3x mb-3"></i><br>
                        <strong>File: ${jenis.toUpperCase()}</strong><br>
                        <small>Klik download untuk melihat file</small>
                    </div>
                `;
            }
            
            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        }
        
        function lihatBerkas() {
            const berkasId = document.getElementById('berkasId').value;
            const namaFile = document.getElementById('modalNamaFile').textContent;
            window.open(`/berkas/${namaFile}`, '_blank');
        }



        function updateRiwayatStats(stats) {
            document.getElementById('totalVerifikasiStat').textContent = stats.total;
            document.getElementById('terverifikasiStat').textContent = stats.terverifikasi;
            document.getElementById('ditolakStat').textContent = stats.ditolak;
        }



        function calculateWaitingTime(tanggalDaftar) {
            const daftarDate = new Date(tanggalDaftar);
            const now = new Date();
            const diffTime = Math.abs(now - daftarDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays === 1) return '1 hari';
            return `${diffDays} hari`;
        }

        function loadRecentActivity() {
            fetch('/verifikator/recent-activity')
            .then(response => response.json())
            .then(data => {
                const table = document.getElementById('recentActivity');
                if (data.success && data.activities.length > 0) {
                    table.innerHTML = '';
                    data.activities.forEach(activity => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${new Date(activity.created_at).toLocaleString('id-ID')}</td>
                            <td>${activity.no_pendaftaran || '-'}</td>
                            <td>${activity.nama || '-'}</td>
                            <td><span class="badge bg-${activity.status === 'Terverifikasi' ? 'success' : 'danger'}">${activity.status}</span></td>
                            <td>${activity.verifikator || '-'}</td>
                        `;
                        table.appendChild(row);
                    });
                } else {
                    table.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Belum ada aktivitas verifikasi</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error loading recent activity:', error);
                document.getElementById('recentActivity').innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error memuat aktivitas</td></tr>';
            });
        }
        




        function testFileAccess() {
            // Test dengan file yang ada di storage
            fetch('/diagnose-berkas')
            .then(response => response.json())
            .then(data => {
                console.log('File system diagnosis:', data);
                
                let message = 'Diagnosis File System:\n';
                message += `Storage Path Exists: ${data.berkas_storage_exists}\n`;
                message += `Storage Files Count: ${data.berkas_storage_files.length - 2}\n`; // -2 for . and ..
                message += `Public Path Exists: ${data.public_berkas_exists}\n`;
                message += `Storage Link Exists: ${data.storage_link_exists}\n`;
                message += `Sample Berkas Count: ${data.sample_berkas.length}\n`;
                
                if (data.sample_berkas.length > 0) {
                    const sampleFile = data.sample_berkas[0];
                    message += `\nTesting file: ${sampleFile.nama_file}`;
                    
                    // Test akses file
                    const testUrl = `/berkas/${sampleFile.nama_file}`;
                    fetch(testUrl, { method: 'HEAD' })
                    .then(response => {
                        if (response.ok) {
                            showToast('File access test: SUCCESS - File dapat diakses', 'success');
                        } else {
                            showToast(`File access test: FAILED - HTTP ${response.status}`, 'error');
                        }
                    })
                    .catch(error => {
                        showToast('File access test: ERROR - ' + error.message, 'error');
                    });
                } else {
                    showToast('No sample files found to test', 'error');
                }
                
                alert(message);
            })
            .catch(error => {
                console.error('Diagnosis error:', error);
                showToast('Failed to run diagnosis: ' + error.message, 'error');
            });
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

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric'
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            // Set user info
            document.getElementById('dashboardUserName').textContent = '{{ $user->nama }}';

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

            // Load initial data
            loadDashboardData();
            
            // Auto refresh dashboard every 30 seconds
            setInterval(loadDashboardData, 30000);
        });
    </script>
</body>

</html>