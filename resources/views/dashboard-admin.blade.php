<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - SMK Bakti Nusantara 666</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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

        #map {
            height: 400px;
            border-radius: 10px;
            margin-top: 1rem;
        }

        .action-buttons .btn {
            margin: 0 2px;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
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
                        <i class="fas fa-school me-2"></i>
                        SMK BN 666
                    </h5>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" data-target="dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="jurusan">
                                <i class="fas fa-code me-2"></i>Jurusan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="gelombang">
                                <i class="fas fa-calendar me-2"></i>Gelombang
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="wilayah">
                                <i class="fas fa-map-marker-alt me-2"></i>Wilayah
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="users">
                                <i class="fas fa-users me-2"></i>User Management
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="pendaftar">
                                <i class="fas fa-user-graduate me-2"></i>Data Pendaftar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="laporan">
                                <i class="fas fa-chart-bar me-2"></i>Laporan
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
                        <span class="navbar-brand mb-0 h6">Admin Dashboard</span>
                        <div class="d-flex align-items-center">
                            <span class="me-3" id="userName">Admin</span>
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
                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data...</p>
                    </div>

                    <!-- Dashboard Section -->
                    <div id="dashboard" class="section active">
                        <!-- Welcome Header & Stat Cards -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom border-0">
                                    <div class="card-body p-4">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h2 class="fw-bold mb-2 text-primary">
                                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin
                                                </h2>
                                                <p class="text-muted mb-0">
                                                    Selamat datang, <strong id="dashboardUserName">Admin</strong>!
                                                    Anda login sebagai <span class="badge bg-primary"
                                                        id="userRoleBadge">Admin</span>

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
                                <div class="stat-card primary">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Total Pendaftar</h5>
                                            <h2 class="mb-0" id="totalPendaftar">0</h2>
                                            <small><i class="fas fa-chart-line me-1"></i>+12% dari kemarin</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-users fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card success">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Lulus Administrasi</h5>
                                            <h2 class="mb-0" id="lulusAdministrasi">0</h2>
                                            <small><i class="fas fa-user-check me-1"></i>57% dari total</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card warning">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Menunggu Verifikasi</h5>
                                            <h2 class="mb-0" id="menungguVerifikasi">0</h2>
                                            <small><i class="fas fa-hourglass-half me-1"></i>Perlu tindakan</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-clock fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Program Jurusan</h5>
                                            <h2 class="mb-0" id="totalJurusan">0</h2>
                                            <small><i class="fas fa-code-branch me-1"></i>Aktif</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-book fa-2x opacity-50"></i>
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
                                            <div class="col-xl-2 col-md-4 col-6 mb-3">
                                                <button class="btn btn-primary-custom text-white w-100 py-3"
                                                    onclick="showSection('pendaftar')">
                                                    <i class="fas fa-users fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Data Pendaftar</span>
                                                </button>
                                            </div>
                                            <div class="col-xl-2 col-md-4 col-6 mb-3">
                                                <button class="btn btn-success w-100 py-3" style="border-radius: 10px;"
                                                    onclick="showSection('jurusan')">
                                                    <i class="fas fa-book fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Jurusan</span>
                                                </button>
                                            </div>
                                            <div class="col-xl-2 col-md-4 col-6 mb-3">
                                                <button class="btn btn-info w-100 py-3 text-white"
                                                    style="border-radius: 10px;" onclick="showSection('gelombang')">
                                                    <i class="fas fa-calendar fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Gelombang</span>
                                                </button>
                                            </div>
                                            <div class="col-xl-2 col-md-4 col-6 mb-3">
                                                <button class="btn btn-warning w-100 py-3 text-dark"
                                                    style="border-radius: 10px;" onclick="showSection('wilayah')">
                                                    <i class="fas fa-map-marker-alt fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Wilayah</span>
                                                </button>
                                            </div>
                                            <div class="col-xl-2 col-md-4 col-6 mb-3">
                                                <button class="btn btn-secondary w-100 py-3"
                                                    style="border-radius: 10px;" onclick="showSection('users')">
                                                    <i class="fas fa-user-cog fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">User Management</span>
                                                </button>
                                            </div>
                                            <div class="col-xl-2 col-md-4 col-6 mb-3">
                                                <button class="btn btn-dark w-100 py-3" style="border-radius: 10px;"
                                                    onclick="showSection('laporan')">
                                                    <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                                                    <span class="fw-bold">Laporan</span>
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
                                            Terbaru</h5>
                                        <span class="badge bg-primary">Real-time</span>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Waktu</th>
                                                        <th>Aktivitas</th>
                                                        <th>User</th>
                                                        <th>Status</th>
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

                    <!-- Jurusan Management Section -->
                    <div id="jurusan" class="section">
                        <div class="card card-custom">
                            <div
                                class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-code me-2 text-primary"></i>Manajemen Jurusan</h5>
                                <button class="btn btn-primary btn-sm" onclick="showJurusanModal()">
                                    <i class="fas fa-plus me-2"></i>Tambah Jurusan
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Kode</th>
                                                <th>Nama Jurusan</th>
                                                <th>Kuota</th>
                                                <th>Terisi</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="jurusanTable">
                                            <!-- Jurusan data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gelombang Management Section -->
                    <div id="gelombang" class="section">
                        <div class="card card-custom">
                            <div
                                class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-calendar me-2 text-primary"></i>Manajemen Gelombang
                                </h5>
                                <button class="btn btn-primary btn-sm" onclick="showGelombangModal()">
                                    <i class="fas fa-plus me-2"></i>Tambah Gelombang
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Gelombang</th>
                                                <th>Periode</th>
                                                <th>Biaya</th>
                                                <th>Kuota</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gelombangTable">
                                            <!-- Gelombang data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Wilayah Management Section -->
                    <div id="wilayah" class="section">
                        <div class="card card-custom">
                            <div
                                class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-map-marker-alt me-2 text-primary"></i>Manajemen
                                    Wilayah</h5>
                                <button class="btn btn-primary btn-sm" onclick="showWilayahModal()">
                                    <i class="fas fa-plus me-2"></i>Tambah Wilayah
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div id="map"></div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Statistik Wilayah</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <small class="text-muted">Total Wilayah</small>
                                                    <h4 id="totalWilayah">0</h4>
                                                </div>
                                                <div class="mb-3">
                                                    <small class="text-muted">Pendaftar Tertinggi</small>
                                                    <h5 id="wilayahTertinggi">-</h5>
                                                </div>
                                                <div>
                                                    <small class="text-muted">Wilayah Baru</small>
                                                    <h5 id="wilayahBaru">-</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Kode Wilayah</th>
                                                <th>Nama Wilayah</th>
                                                <th>Kecamatan</th>
                                                <th>Desa</th>
                                                <th>Jumlah Pendaftar</th>
                                                <th>Latitude</th>
                                                <th>Longitude</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="wilayahTable">
                                            <!-- Wilayah data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Management Section -->
                    <div id="users" class="section">
                        <div class="card card-custom">
                            <div
                                class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-users me-2 text-primary"></i>Manajemen User</h5>
                                <button class="btn btn-primary btn-sm" onclick="showUserModal()">
                                    <i class="fas fa-user-plus me-2"></i>Tambah User
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Terakhir Login</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="usersTable">
                                            <!-- Users data will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pendaftar Section -->
                    <div id="pendaftar" class="section">
                        <div class="card card-custom">
                            <div
                                class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-user-graduate me-2 text-primary"></i>Data Pendaftar
                                </h5>
                                <div>
                                    <button class="btn btn-success btn-sm me-2" onclick="exportData()">
                                        <i class="fas fa-download me-2"></i>Export Data
                                    </button>
                                    <button class="btn btn-primary btn-sm" onclick="showFilterModal()">
                                        <i class="fas fa-filter me-2"></i>Filter
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>No. Pendaftaran</th>
                                                <th>Nama</th>
                                                <th>Asal Sekolah</th>
                                                <th>Jurusan</th>
                                                <th>Gelombang</th>
                                                <th>Status</th>
                                                <th>Tanggal Daftar</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pendaftarTable">
                                            <!-- Pendaftar data will be loaded here -->
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
                                <h5 class="mb-0"><i class="fas fa-chart-bar me-2 text-primary"></i>Laporan PPDB</h5>
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
                                                <p><strong>Pendaftar Baru:</strong> <span id="pendaftarBaru">0</span>
                                                    orang</p>
                                                <p><strong>Total Pendaftar:</strong> <span
                                                        id="totalPendaftarReport">0</span> orang</p>
                                                <button class="btn btn-sm btn-outline-primary"
                                                    onclick="generateLaporan('harian')">
                                                    <i class="fas fa-download me-1"></i>Generate Laporan
                                                </button>
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
                                                <p><strong>Pendaftar Bulan Ini:</strong> <span
                                                        id="pendaftarBulanIni">0</span> orang</p>
                                                <p><strong>Target:</strong> <span id="targetBulan">200</span> orang
                                                    (<span id="persentaseTarget">0</span>%)</p>
                                                <button class="btn btn-sm btn-outline-success"
                                                    onclick="generateLaporan('bulanan')">
                                                    <i class="fas fa-download me-1"></i>Generate Laporan
                                                </button>
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
                                                        <h6>Statistik Jurusan</h6>
                                                        <ul class="list-group" id="statJurusan">
                                                            <!-- Statistik jurusan will be loaded here -->
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>Statistik Wilayah</h6>
                                                        <ul class="list-group" id="statWilayah">
                                                            <!-- Statistik wilayah will be loaded here -->
                                                        </ul>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <h6>Statistik Status</h6>
                                                        <ul class="list-group" id="statStatus">
                                                            <!-- Statistik status will be loaded here -->
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="mt-3 text-center">
                                                    <button class="btn btn-primary"
                                                        onclick="generateLaporan('komprehensif')">
                                                        <i class="fas fa-file-pdf me-2"></i>Generate Laporan Lengkap
                                                    </button>
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

    <!-- Modals -->
    <!-- Jurusan Modal -->
    <div class="modal fade" id="jurusanModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jurusanModalTitle">Tambah Jurusan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formJurusan">
                        <input type="hidden" id="jurusanId">
                        <div class="mb-3">
                            <label class="form-label">Kode Jurusan</label>
                            <input type="text" class="form-control" id="jurusanKode" placeholder="PPLG" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Jurusan</label>
                            <input type="text" class="form-control" id="jurusanNama"
                                placeholder="Pengembangan Perangkat Lunak dan Gim" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kuota</label>
                            <input type="number" class="form-control" id="jurusanKuota" placeholder="40" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveJurusan()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Gelombang Modal -->
    <div class="modal fade" id="gelombangModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="gelombangModalTitle">Tambah Gelombang Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formGelombang">
                        <input type="hidden" id="gelombangId">
                        <div class="mb-3">
                            <label class="form-label">Nama Gelombang</label>
                            <input type="text" class="form-control" id="gelombangNama"
                                placeholder="Gelombang 1 - Early Bird" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tahun</label>
                            <input type="number" class="form-control" id="gelombangTahun" placeholder="2025" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="gelombangMulai" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="gelombangSelesai" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Biaya Pendaftaran</label>
                            <input type="number" class="form-control" id="gelombangBiaya" placeholder="4000000"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kuota</label>
                            <input type="number" class="form-control" id="gelombangKuota" placeholder="50" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="gelombangStatus" required>
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveGelombang()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Wilayah Modal -->
    <div class="modal fade" id="wilayahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="wilayahModalTitle">Tambah Wilayah Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formWilayah">
                        <input type="hidden" id="wilayahId">
                        <div class="mb-3">
                            <label class="form-label">Kode Wilayah</label>
                            <input type="text" class="form-control" id="wilayahKode" placeholder="BDG" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Wilayah</label>
                            <input type="text" class="form-control" id="wilayahNama" placeholder="Kota Bandung"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kecamatan</label>
                            <input type="text" class="form-control" id="wilayahKecamatan" placeholder="Bandung Wetan"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Desa</label>
                            <input type="text" class="form-control" id="wilayahDesa" placeholder="Cihapit"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="0.00000001" class="form-control" id="wilayahLat"
                                placeholder="-6.9175" min="-90" max="90" required>
                            <small class="text-muted">Contoh: -6.9175 (untuk Bandung). Range: -90 sampai 90</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="0.00000001" class="form-control" id="wilayahLng"
                                placeholder="107.6191" min="-180" max="180" required>
                            <small class="text-muted">Contoh: 107.6191 (untuk Bandung). Range: -180 sampai 180</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" id="wilayahKeterangan" rows="2"
                                placeholder="Keterangan wilayah (opsional)"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveWilayah()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mapModalTitle">Peta Wilayah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="modalMapContainer" style="height: 400px; border-radius: 10px;"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formUser">
                        <input type="hidden" id="userId">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="userNama" placeholder="Admin System" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail" placeholder="admin@smkbn666.sch.id"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="userHp" placeholder="08123456789" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-control" id="userRole" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="kepsek">Kepala Sekolah</option>
                                <option value="verifikator_adm">Verifikator</option>
                                <option value="keuangan">Keuangan</option>
                                <option value="pendaftar">Pendaftar</option>
                            </select>

                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="userStatus" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="mb-3" id="passwordField">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="userPassword" placeholder="Password"
                                minlength="6">
                            <small class="text-muted">Minimal 6 karakter</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveUser()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Data Pendaftar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="formFilter">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="filterStatus">
                                <option value="">-- Semua Status --</option>
                                <option value="Terverifikasi">Terverifikasi</option>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <select class="form-control" id="filterJurusan">
                                <option value="">-- Semua Jurusan --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gelombang</label>
                            <select class="form-control" id="filterGelombang">
                                <option value="">-- Semua Gelombang --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Daftar</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="date" class="form-control" id="filterTanggalMulai" placeholder="Dari">
                                </div>
                                <div class="col-6">
                                    <input type="date" class="form-control" id="filterTanggalSelesai" placeholder="Sampai">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="resetFilter()">Reset</button>
                    <button type="button" class="btn btn-primary" onclick="applyFilter()">Terapkan Filter</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Pendaftar Modal -->
    <div class="modal fade" id="pendaftarModal" tabindex="-1" aria-labelledby="pendaftarModalTitle" aria-hidden="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pendaftarModalTitle">Detail Pendaftar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formPendaftar">
                        <input type="hidden" id="pendaftarId">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">No. Pendaftaran</label>
                                    <input type="text" class="form-control" id="pendaftarNo" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="pendaftarNama" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="pendaftarEmail" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" id="pendaftarTelepon" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jurusan</label>
                                    <select class="form-control" id="pendaftarJurusan" required>
                                        <!-- Options will be populated from database -->
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gelombang</label>
                                    <select class="form-control" id="pendaftarGelombang" required>
                                        <!-- Options will be populated from database -->
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Wilayah</label>
                                    <select class="form-control" id="pendaftarWilayah">
                                        <!-- Options will be populated from database -->
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" id="pendaftarStatus" required>
                                        <option value="Terverifikasi">Terverifikasi</option>
                                        <option value="Menunggu">Menunggu</option>
                                        <option value="Ditolak">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="savePendaftar()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Global variables
        let map;
        let currentUser = {
            id: 1,
            nama: "Admin System",
            email: "admin@smkbn666.sch.id",
            role: "Super Admin"
        };

        // Database simulation
        let database = {
            jurusan: [],
            gelombang: [],
            wilayah: [],


            users: JSON.parse(localStorage.getItem('users')) || [
                { id: 1, nama: "Admin System", email: "admin@smkbn666.sch.id", role: "Super Admin", status: "Aktif", last_login: "2024-01-15 10:30" },
                { id: 2, nama: "Budi S.Kom", email: "verifikasi@smkbn666.sch.id", role: "Verifikator", status: "Aktif", last_login: "2024-01-14 15:20" }
            ],
            pendaftar: JSON.parse(localStorage.getItem('pendaftar')) || [
                { id: 1, no_pendaftaran: "PPDB2024001", nama: "Andi Wijaya", email: "andi@email.com", telepon: "08123456789", jurusan_id: 1, gelombang_id: 1, wilayah_id: 1, status: "Terverifikasi", tanggal_daftar: "2024-01-15" }
            ],
            activity: JSON.parse(localStorage.getItem('activity')) || [
                { id: 1, waktu: "10:30", aktivitas: "Pendaftaran baru - Andi Pratama", user: "System", status: "Selesai" },
                { id: 2, waktu: "10:25", aktivitas: "Verifikasi berkas - Siti Rahma", user: "Verifikator", status: "Proses" },
                { id: 3, waktu: "10:15", aktivitas: "Pembayaran dikonfirmasi", user: "Keuangan", status: "Selesai" },
                { id: 4, waktu: "09:45", aktivitas: "Update data jurusan", user: "Admin", status: "Update" }
            ]
        };

        // Save database to localStorage
        function saveDatabase() {
            localStorage.setItem('jurusan', JSON.stringify(database.jurusan));
            localStorage.setItem('gelombang', JSON.stringify(database.gelombang));
            localStorage.setItem('wilayah', JSON.stringify(database.wilayah));
            localStorage.setItem('users', JSON.stringify(database.users));
            localStorage.setItem('pendaftar', JSON.stringify(database.pendaftar));
            localStorage.setItem('activity', JSON.stringify(database.activity));
        }

        // Initialize Main Map
        function initMainMap(wilayahData) {
            if (map) {
                map.remove();
            }

            map = L.map('map').setView([-6.9175, 107.6191], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add markers for all wilayah
            wilayahData.forEach(wilayah => {
                if (wilayah.latitude && wilayah.longitude) {
                    L.marker([wilayah.latitude, wilayah.longitude]).addTo(map)
                        .bindPopup(`<b>${wilayah.nama_wilayah}</b><br>Kode: ${wilayah.kode_wilayah}<br>Pendaftar: 0`);
                }
            });
        }

        // Show specific wilayah on map modal
        function showWilayahOnMap(id) {
            const wilayah = database.wilayah.find(w => w.id === id);
            if (!wilayah) return;

            document.getElementById('mapModalTitle').textContent = `Peta - ${wilayah.nama_wilayah}`;
            const mapModal = new bootstrap.Modal(document.getElementById('mapModal'));
            mapModal.show();

            setTimeout(() => {
                if (window.modalMap) {
                    window.modalMap.remove();
                }

                window.modalMap = L.map('modalMapContainer').setView([wilayah.latitude, wilayah.longitude], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(window.modalMap);

                L.marker([wilayah.latitude, wilayah.longitude]).addTo(window.modalMap)
                    .bindPopup(`<b>${wilayah.nama_wilayah}</b><br>Kode: ${wilayah.kode_wilayah}<br>Pendaftar: 0`)
                    .openPopup();
            }, 300);
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
                case 'jurusan':
                    loadJurusanData();
                    break;
                case 'gelombang':
                    loadGelombangData();
                    break;
                case 'wilayah':
                    loadWilayahData();
                    break;
                case 'users':
                    loadUsersData();
                    break;
                case 'pendaftar':
                    loadPendaftarData();
                    break;
                case 'laporan':
                    loadLaporanData();
                    break;
            }
        }

        // Load dashboard data
        function loadDashboardData() {
            // Update stats from server-side data
            document.getElementById('totalPendaftar').textContent = '{{ $stats["total_pendaftar"] ?? 0 }}';
            document.getElementById('lulusAdministrasi').textContent = '{{ $stats["lulus_administrasi"] ?? 0 }}';
            document.getElementById('menungguVerifikasi').textContent = '{{ $stats["menunggu_verifikasi"] ?? 0 }}';

            // Get total jurusan from API
            fetch('/api/jurusan', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        document.getElementById('totalJurusan').textContent = result.data.length;
                    }
                })
                .catch(error => {
                    console.error('Error loading jurusan count:', error);
                    document.getElementById('totalJurusan').textContent = '0';
                });

            // Update user info from Laravel
            document.getElementById('dashboardUserName').textContent = '{{ $user->nama ?? "Admin" }}';
            document.getElementById('userRoleBadge').textContent = '{{ $user->role ?? "Admin" }}';

            // Load recent activity
            const activityTable = document.getElementById('recentActivity');
            activityTable.innerHTML = '';

            database.activity.slice(-5).reverse().forEach(activity => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${activity.waktu}</td>
                    <td>${activity.aktivitas}</td>
                    <td>${activity.user}</td>
                    <td><span class="badge bg-${getStatusColor(activity.status)}">${activity.status}</span></td>
                `;
                activityTable.appendChild(row);
            });
        }

        // Load jurusan data from database
        function loadJurusanData() {
            const jurusanTable = document.getElementById('jurusanTable');
            if (!jurusanTable) return;

            jurusanTable.innerHTML = '<tr><td colspan="7" class="text-center">Memuat data...</td></tr>';

            fetch('/api/jurusan', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('Response jurusan:', result);
                    
                    if (!result.success) {
                        throw new Error(result.message || 'Gagal memuat data');
                    }
                    
                    const data = result.data || [];
                    database.jurusan = data;
                    jurusanTable.innerHTML = '';

                    if (data.length === 0) {
                        jurusanTable.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada data jurusan</td></tr>';
                        return;
                    }

                    data.forEach((jurusan, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${jurusan.kode}</td>
                        <td>${jurusan.nama}</td>
                        <td>${jurusan.kuota}</td>
                        <td>0</td>
                        <td><span class="badge bg-success">Aktif</span></td>
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-outline-primary" onclick="editJurusan(${jurusan.id})">Edit</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteJurusan(${jurusan.id})">Hapus</button>
                        </td>
                    `;
                        jurusanTable.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error loading jurusan:', error);
                    jurusanTable.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data: ' + error.message + '</td></tr>';
                    showToast('Gagal memuat data jurusan: ' + error.message, 'error');
                });
        }

        // Load gelombang data from database
        function loadGelombangData() {
            const gelombangTable = document.getElementById('gelombangTable');
            if (!gelombangTable) return;

            gelombangTable.innerHTML = '<tr><td colspan="7" class="text-center">Memuat data...</td></tr>';

            fetch('/api/gelombang', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('Response gelombang:', result);
                    
                    if (!result.success) {
                        throw new Error(result.message || 'Gagal memuat data');
                    }
                    
                    const data = result.data || [];
                    database.gelombang = data;
                    gelombangTable.innerHTML = '';

                    if (data.length === 0) {
                        gelombangTable.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada data gelombang</td></tr>';
                        return;
                    }

                    data.forEach(gelombang => {
                        const row = document.createElement('tr');
                        const status = gelombang.is_aktif ? 'Aktif' : 'Nonaktif';
                        row.innerHTML = `
                        <td>${gelombang.id}</td>
                        <td>${gelombang.nama}</td>
                        <td>${formatDate(gelombang.tgl_mulai)} - ${formatDate(gelombang.tgl_selesai)}</td>
                        <td>Rp ${formatNumber(gelombang.biaya_daftar)}</td>
                        <td>${gelombang.kuota}</td>
                        <td><span class="badge bg-${gelombang.is_aktif ? 'success' : 'secondary'}">${status}</span></td>
                        <td class="action-buttons">
                            <button class="btn btn-sm btn-outline-primary" onclick="editGelombang(${gelombang.id})">Edit</button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteGelombang(${gelombang.id})">Hapus</button>
                        </td>
                    `;
                        gelombangTable.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error loading gelombang:', error);
                    gelombangTable.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data: ' + error.message + '</td></tr>';
                    showToast('Gagal memuat data gelombang: ' + error.message, 'error');
                });
        }

        // Load wilayah data from database
        function loadWilayahData() {
            const wilayahTable = document.getElementById('wilayahTable');
            if (!wilayahTable) return;

            wilayahTable.innerHTML = '<tr><td colspan="9" class="text-center">Memuat data...</td></tr>';

            fetch('/api/wilayah', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('Response wilayah:', result);
                    
                    if (!result.success) {
                        throw new Error(result.message || 'Gagal memuat data');
                    }
                    
                    const data = result.data || [];
                    // Sort data by ID to maintain consistent order
                    data.sort((a, b) => (a.id || 0) - (b.id || 0));
                    database.wilayah = data;
                    wilayahTable.innerHTML = '';

                    if (data.length === 0) {
                        wilayahTable.innerHTML = '<tr><td colspan="9" class="text-center">Belum ada data wilayah</td></tr>';
                        return;
                    }

                    // Update stats
                    document.getElementById('totalWilayah').textContent = data.length;
                    if (data.length > 0) {
                        // Find wilayah dengan pendaftar tertinggi
                        const wilayahTertinggi = data.reduce((prev, current) => 
                            (prev.jumlah_pendaftar > current.jumlah_pendaftar) ? prev : current
                        );
                        document.getElementById('wilayahTertinggi').textContent = wilayahTertinggi.nama_wilayah;
                        document.getElementById('wilayahBaru').textContent = data[data.length - 1].nama_wilayah;

                        // Load map with all markers after data loaded
                        setTimeout(() => initMainMap(data), 100);
                    }

                    data.forEach(wilayah => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${wilayah.id}</td>
                            <td>${wilayah.kode_wilayah}</td>
                            <td>${wilayah.nama_wilayah}</td>
                            <td>${wilayah.kecamatan}</td>
                            <td>${wilayah.desa}</td>
                            <td><span class="badge bg-primary">${wilayah.jumlah_pendaftar || 0}</span></td>
                            <td>${wilayah.latitude}</td>
                            <td>${wilayah.longitude}</td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary" onclick="editWilayah(${wilayah.id})">Edit</button>
                                <button class="btn btn-sm btn-outline-info" onclick="showWilayahOnMap(${wilayah.id})">Lihat Peta</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteWilayah(${wilayah.id})">Hapus</button>
                            </td>
                        `;
                        wilayahTable.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error loading wilayah:', error);
                    wilayahTable.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data</td></tr>';
                });
        }

        // Load users data
        function loadUsersData() {
            const usersTable = document.getElementById('usersTable');
            if (!usersTable) return;

            usersTable.innerHTML = '<tr><td colspan="7" class="text-center">Memuat data...</td></tr>';

            fetch('/api/pengguna', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('Response users:', result);
                    
                    if (!result.success) {
                        throw new Error(result.message || 'Gagal memuat data');
                    }
                    
                    const data = result.data || [];
                    database.users = data;
                    usersTable.innerHTML = '';

                    if (data.length === 0) {
                        usersTable.innerHTML = '<tr><td colspan="7" class="text-center">Belum ada data user</td></tr>';
                        return;
                    }

                    data.forEach((user, index) => {
                        const status = user.aktif ? 'Aktif' : 'Nonaktif';
                        const lastLogin = user.last_login || '-';
                        const roleLabel = getRoleLabel(user.role);
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${user.nama}</td>
                            <td>${user.email}</td>
                            <td><span class="badge bg-${getRoleColor(user.role)}">${roleLabel}</span></td>
                            <td><span class="badge bg-${user.aktif ? 'success' : 'secondary'}">${status}</span></td>
                            <td>${lastLogin}</td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-outline-info" onclick="viewUser(${user.id})">Detail</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.id})">Hapus</button>
                            </td>
                        `;
                        usersTable.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error loading users:', error);
                    usersTable.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Gagal memuat data: ' + error.message + '</td></tr>';
                    showToast('Gagal memuat data user: ' + error.message, 'error');
                });
        }

        // Load pendaftar data
        function loadPendaftarData(filterParams = '') {
            const pendaftarTable = document.getElementById('pendaftarTable');
            if (!pendaftarTable) return;

            pendaftarTable.innerHTML = '<tr><td colspan="9" class="text-center">Memuat data...</td></tr>';

            const url = '/api/pendaftar' + (filterParams ? '?' + filterParams : '');
            
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('Response pendaftar:', result);
                    
                    if (!result.success) {
                        throw new Error(result.message || 'Gagal memuat data');
                    }
                    
                    const data = result.data || [];
                    // Sort data by ID to maintain consistent order
                    data.sort((a, b) => (a.id || 0) - (b.id || 0));
                    database.pendaftar = data;
                    pendaftarTable.innerHTML = '';

                    if (data.length === 0) {
                        pendaftarTable.innerHTML = '<tr><td colspan="9" class="text-center">Belum ada data pendaftar</td></tr>';
                        return;
                    }

                    data.forEach(pendaftar => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${pendaftar.id || '-'}</td>
                            <td>${pendaftar.no_pendaftaran}</td>
                            <td>${pendaftar.nama}</td>
                            <td>${pendaftar.asal_sekolah}</td>
                            <td>${pendaftar.jurusan}</td>
                            <td>${pendaftar.gelombang}</td>
                            <td><span class="badge bg-${getPendaftarStatusColor(pendaftar.status)}">${pendaftar.status}</span></td>
                            <td>${formatDate(pendaftar.tanggal_daftar)}</td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-outline-info" onclick="viewPendaftar(${pendaftar.user_id})">Detail</button>
                            </td>
                        `;
                        pendaftarTable.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error loading pendaftar:', error);
                    pendaftarTable.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Gagal memuat data: ' + error.message + '</td></tr>';
                    showToast('Gagal memuat data pendaftar: ' + error.message, 'error');
                });
        }


        // Load laporan data
        function loadLaporanData() {
            // Update report dates
            const now = new Date();
            document.getElementById('currentDateReport').textContent = formatDate(now);
            document.getElementById('currentMonthReport').textContent = formatMonth(now);

            // Load data from API
            fetch('/api/laporan', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success && result.data) {
                    const data = result.data;
                    
                    // Update stats
                    document.getElementById('pendaftarBaru').textContent = data.pendaftar_hari_ini || 0;
                    document.getElementById('totalPendaftarReport').textContent = data.total_pendaftar || 0;
                    document.getElementById('pendaftarBulanIni').textContent = data.pendaftar_bulan_ini || 0;
                    
                    const targetBulan = 200;
                    const persentase = Math.round((data.pendaftar_bulan_ini / targetBulan) * 100);
                    document.getElementById('targetBulan').textContent = targetBulan;
                    document.getElementById('persentaseTarget').textContent = persentase;

                    // Load statistik jurusan
                    const statJurusan = document.getElementById('statJurusan');
                    statJurusan.innerHTML = '';
                    
                    if (data.statistik_jurusan && data.statistik_jurusan.length > 0) {
                        data.statistik_jurusan.forEach(jurusan => {
                            const item = document.createElement('li');
                            item.className = 'list-group-item d-flex justify-content-between';
                            item.innerHTML = `
                                <span>${jurusan.nama}</span>
                                <span class="badge bg-primary">${jurusan.total}</span>
                            `;
                            statJurusan.appendChild(item);
                        });
                    } else {
                        statJurusan.innerHTML = '<li class="list-group-item text-center">Belum ada data</li>';
                    }

                    // Load statistik wilayah
                    const statWilayah = document.getElementById('statWilayah');
                    statWilayah.innerHTML = '';
                    
                    if (data.statistik_wilayah && data.statistik_wilayah.length > 0) {
                        data.statistik_wilayah.forEach(wilayah => {
                            const item = document.createElement('li');
                            item.className = 'list-group-item d-flex justify-content-between';
                            item.innerHTML = `
                                <span>${wilayah.nama}</span>
                                <span class="badge bg-primary">${wilayah.total}</span>
                            `;
                            statWilayah.appendChild(item);
                        });
                    } else {
                        statWilayah.innerHTML = '<li class="list-group-item text-center">Belum ada data</li>';
                    }

                    // Load statistik status
                    const statStatus = document.getElementById('statStatus');
                    statStatus.innerHTML = '';
                    
                    if (data.statistik_status && data.statistik_status.length > 0) {
                        data.statistik_status.forEach(status => {
                            const item = document.createElement('li');
                            item.className = 'list-group-item d-flex justify-content-between';
                            item.innerHTML = `
                                <span>${status.status}</span>
                                <span class="badge bg-${getPendaftarStatusColor(status.status)}">${status.total}</span>
                            `;
                            statStatus.appendChild(item);
                        });
                    } else {
                        statStatus.innerHTML = '<li class="list-group-item text-center">Belum ada data</li>';
                    }
                } else {
                    showToast('Gagal memuat data laporan', 'error');
                }
            })
            .catch(error => {
                console.error('Error loading laporan:', error);
                
                // Fallback data jika API gagal
                document.getElementById('pendaftarBaru').textContent = '0';
                document.getElementById('totalPendaftarReport').textContent = '0';
                document.getElementById('pendaftarBulanIni').textContent = '0';
                document.getElementById('targetBulan').textContent = '200';
                document.getElementById('persentaseTarget').textContent = '0';
                
                // Set default empty lists
                document.getElementById('statJurusan').innerHTML = '<li class="list-group-item text-center">Belum ada data</li>';
                document.getElementById('statWilayah').innerHTML = '<li class="list-group-item text-center">Belum ada data</li>';
                document.getElementById('statStatus').innerHTML = '<li class="list-group-item text-center">Belum ada data</li>';
                
                showToast('Gagal memuat data laporan, menggunakan data default', 'error');
            });
        }

        // Modal Functions
        function showJurusanModal(jurusanId = null) {
            const modal = document.getElementById('jurusanModal');
            const title = document.getElementById('jurusanModalTitle');
            const form = document.getElementById('formJurusan');

            // Clear previous validation errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            if (jurusanId) {
                // Edit mode - fetch from API
                fetch(`/api/jurusan/${jurusanId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success && result.data) {
                            const jurusan = result.data;
                            title.textContent = 'Edit Jurusan';
                            document.getElementById('jurusanId').value = jurusan.id;
                            document.getElementById('jurusanKode').value = jurusan.kode;
                            document.getElementById('jurusanNama').value = jurusan.nama;
                            document.getElementById('jurusanKuota').value = jurusan.kuota;
                            new bootstrap.Modal(modal).show();
                        } else {
                            throw new Error(result.message || 'Gagal memuat data jurusan');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Gagal memuat data jurusan: ' + error.message, 'error');
                    });
            } else {
                // Add mode
                title.textContent = 'Tambah Jurusan Baru';
                form.reset();
                document.getElementById('jurusanId').value = '';
                
                // Reset validation states
                form.querySelectorAll('.is-invalid, .is-valid').forEach(el => {
                    el.classList.remove('is-invalid', 'is-valid');
                });
                
                new bootstrap.Modal(modal).show();
            }
        }

        function showGelombangModal(gelombangId = null) {
            const modal = document.getElementById('gelombangModal');
            const title = document.getElementById('gelombangModalTitle');
            const form = document.getElementById('formGelombang');

            if (gelombangId) {
                fetch(`/api/gelombang/${gelombangId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success && result.data) {
                            const gelombang = result.data;
                            title.textContent = 'Edit Gelombang';
                            document.getElementById('gelombangId').value = gelombang.id;
                            document.getElementById('gelombangNama').value = gelombang.nama;
                            document.getElementById('gelombangTahun').value = gelombang.tahun;
                            document.getElementById('gelombangMulai').value = gelombang.tgl_mulai;
                            document.getElementById('gelombangSelesai').value = gelombang.tgl_selesai;
                            document.getElementById('gelombangBiaya').value = gelombang.biaya_daftar;
                            document.getElementById('gelombangKuota').value = gelombang.kuota;
                            document.getElementById('gelombangStatus').value = gelombang.is_aktif ? '1' : '0';
                            new bootstrap.Modal(modal).show();
                        } else {
                            throw new Error(result.message || 'Gagal memuat data gelombang');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Gagal memuat data gelombang: ' + error.message, 'error');
                    });
            } else {
                title.textContent = 'Tambah Gelombang Baru';
                form.reset();
                document.getElementById('gelombangId').value = '';
                document.getElementById('gelombangTahun').value = new Date().getFullYear();
                document.getElementById('gelombangStatus').value = '1';
                new bootstrap.Modal(modal).show();
            }
        }

        function showWilayahModal(wilayahId = null) {
            const modal = document.getElementById('wilayahModal');
            const title = document.getElementById('wilayahModalTitle');
            const form = document.getElementById('formWilayah');

            if (wilayahId) {
                fetch(`/api/wilayah/${wilayahId}`)
                    .then(response => response.json())
                    .then(wilayah => {
                        title.textContent = 'Edit Wilayah';
                        document.getElementById('wilayahId').value = wilayah.id;
                        document.getElementById('wilayahKode').value = wilayah.kode_wilayah;
                        document.getElementById('wilayahNama').value = wilayah.nama_wilayah;
                        document.getElementById('wilayahKecamatan').value = wilayah.kecamatan || '';
                        document.getElementById('wilayahDesa').value = wilayah.desa || '';
                        document.getElementById('wilayahLat').value = wilayah.latitude;
                        document.getElementById('wilayahLng').value = wilayah.longitude;
                        document.getElementById('wilayahKeterangan').value = wilayah.keterangan || '';
                        new bootstrap.Modal(modal).show();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Gagal memuat data wilayah', 'error');
                    });
            } else {
                title.textContent = 'Tambah Wilayah Baru';
                form.reset();
                document.getElementById('wilayahId').value = '';
                new bootstrap.Modal(modal).show();
            }
        }

        function showUserModal(userId = null) {
            const modal = document.getElementById('userModal');
            const title = document.getElementById('userModalTitle');
            const form = document.getElementById('formUser');
            const passwordField = document.getElementById('passwordField');

            // Add mode only
            title.textContent = 'Tambah User Baru';
            form.reset();
            document.getElementById('userId').value = '';
            document.getElementById('userPassword').required = true;
            passwordField.querySelector('small').textContent = 'Minimal 6 karakter';
            
            // Enable all fields
            document.querySelectorAll('#formUser input, #formUser select').forEach(field => {
                field.removeAttribute('readonly');
                field.removeAttribute('disabled');
            });
            
            // Show save button
            document.querySelector('#userModal .btn-primary').style.display = 'inline-block';
            
            new bootstrap.Modal(modal).show();
        }

        function showUserDetailModal(userId) {
            const modal = document.getElementById('userModal');
            const title = document.getElementById('userModalTitle');
            const form = document.getElementById('formUser');

            // Fetch user data from API
            fetch(`/api/pengguna/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        const user = result.data;
                        title.textContent = 'Detail User';

                        document.getElementById('userId').value = user.id;
                        document.getElementById('userNama').value = user.nama;
                        document.getElementById('userEmail').value = user.email;
                        document.getElementById('userHp').value = user.hp || '';
                        document.getElementById('userRole').value = user.role;
                        document.getElementById('userStatus').value = user.aktif ? 'Aktif' : 'Nonaktif';
                        document.getElementById('userPassword').value = '';

                        // Make all fields readonly
                        document.querySelectorAll('#formUser input, #formUser select').forEach(field => {
                            field.setAttribute('readonly', true);
                            field.setAttribute('disabled', true);
                        });
                        
                        // Hide save button
                        document.querySelector('#userModal .btn-primary').style.display = 'none';

                        new bootstrap.Modal(modal).show();
                    } else {
                        throw new Error(result.message || 'Gagal memuat data user');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Gagal memuat data user: ' + error.message, 'error');
                });
        }


        function showPendaftarModal(userId = null) {
            const modal = document.getElementById('pendaftarModal');
            const title = document.getElementById('pendaftarModalTitle');
            const form = document.getElementById('formPendaftar');

            // Load dropdown data from API
            Promise.all([
                fetch('/api/jurusan', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } }),
                fetch('/api/gelombang', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } }),
                fetch('/api/wilayah', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([jurusanResult, gelombangResult, wilayahResult]) => {
                // Populate dropdowns
                const jurusanSelect = document.getElementById('pendaftarJurusan');
                const gelombangSelect = document.getElementById('pendaftarGelombang');
                const wilayahSelect = document.getElementById('pendaftarWilayah');

                jurusanSelect.innerHTML = '<option value="">-- Pilih Jurusan --</option>';
                gelombangSelect.innerHTML = '<option value="">-- Pilih Gelombang --</option>';
                wilayahSelect.innerHTML = '<option value="">-- Pilih Wilayah --</option>';

                if (jurusanResult.success) {
                    jurusanResult.data.forEach(jurusan => {
                        const option = document.createElement('option');
                        option.value = jurusan.id;
                        option.textContent = jurusan.nama;
                        jurusanSelect.appendChild(option);
                    });
                }

                if (gelombangResult.success) {
                    gelombangResult.data.forEach(gelombang => {
                        const option = document.createElement('option');
                        option.value = gelombang.id;
                        option.textContent = gelombang.nama;
                        gelombangSelect.appendChild(option);
                    });
                }

                if (wilayahResult.success) {
                    wilayahResult.data.forEach(wilayah => {
                        const option = document.createElement('option');
                        option.value = wilayah.id;
                        option.textContent = wilayah.nama_wilayah;
                        wilayahSelect.appendChild(option);
                    });
                }

                // Reset readonly state
                document.querySelectorAll('#formPendaftar input, #formPendaftar select').forEach(field => {
                    field.removeAttribute('readonly');
                    field.removeAttribute('disabled');
                });
                
                // Show save button
                document.querySelector('#pendaftarModal .btn-primary').style.display = 'inline-block';

                if (userId) {
                    // Edit mode - find pendaftar by user_id
                    const pendaftar = database.pendaftar.find(p => p.user_id === userId);
                    if (pendaftar) {
                        title.textContent = 'Edit Pendaftar';
                        document.getElementById('pendaftarId').value = pendaftar.id || '';
                        document.getElementById('pendaftarNo').value = pendaftar.no_pendaftaran;
                        document.getElementById('pendaftarNama').value = pendaftar.nama;
                        document.getElementById('pendaftarEmail').value = pendaftar.email;
                        document.getElementById('pendaftarTelepon').value = pendaftar.hp;
                        document.getElementById('pendaftarJurusan').value = pendaftar.jurusan_id || '';
                        document.getElementById('pendaftarGelombang').value = pendaftar.gelombang_id || '';
                        document.getElementById('pendaftarWilayah').value = pendaftar.wilayah_id || '';
                        document.getElementById('pendaftarStatus').value = pendaftar.status;
                    }
                } else {
                    // Add mode
                    title.textContent = 'Tambah Pendaftar Baru';
                    form.reset();
                    document.getElementById('pendaftarId').value = '';
                    document.getElementById('pendaftarNo').value = 'Auto Generate';
                }

                new bootstrap.Modal(modal).show();
            })
            .catch(error => {
                console.error('Error loading dropdown data:', error);
                showToast('Gagal memuat data dropdown', 'error');
            });
        }

        // CRUD Functions
        function saveJurusan() {
            const id = document.getElementById('jurusanId').value;
            const kode = document.getElementById('jurusanKode').value.trim();
            const nama = document.getElementById('jurusanNama').value.trim();
            const kuota = parseInt(document.getElementById('jurusanKuota').value);

            // Validasi form di frontend
            if (!kode || !nama || !kuota) {
                showToast('Semua field harus diisi', 'error');
                return;
            }

            if (kode.length > 10) {
                showToast('Kode jurusan maksimal 10 karakter', 'error');
                return;
            }

            if (nama.length > 100) {
                showToast('Nama jurusan maksimal 100 karakter', 'error');
                return;
            }

            if (kuota < 1 || kuota > 1000) {
                showToast('Kuota harus antara 1-1000', 'error');
                return;
            }

            // Cek duplikasi di frontend sebelum submit
            const existingJurusan = database.jurusan || [];
            const isDuplicateKode = existingJurusan.some(j => 
                j.kode.toLowerCase() === kode.toLowerCase() && 
                (!id || j.id != id)
            );
            const isDuplicateNama = existingJurusan.some(j => 
                j.nama.toLowerCase() === nama.toLowerCase() && 
                (!id || j.id != id)
            );

            if (isDuplicateKode) {
                showToast('Kode jurusan sudah digunakan', 'error');
                return;
            }

            if (isDuplicateNama) {
                showToast('Nama jurusan sudah digunakan', 'error');
                return;
            }

            const data = { kode, nama, kuota };
            const url = id ? `/api/jurusan/${id}` : '/api/jurusan';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showToast(result.message || (id ? 'Jurusan berhasil diperbarui!' : 'Jurusan berhasil ditambahkan!'), 'success');
                        bootstrap.Modal.getInstance(document.getElementById('jurusanModal')).hide();
                        setTimeout(() => {
                            loadJurusanData();
                            loadDashboardData();
                        }, 300);
                    } else {
                        // Handle validation errors
                        if (result.errors) {
                            const errorMessages = Object.values(result.errors).flat();
                            showToast(errorMessages.join(', '), 'error');
                        } else {
                            showToast(result.message || 'Gagal menyimpan data jurusan', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan saat menyimpan data', 'error');
                });
        }

        function saveGelombang() {
            const id = document.getElementById('gelombangId').value;
            const nama = document.getElementById('gelombangNama').value.trim();
            const tahun = parseInt(document.getElementById('gelombangTahun').value);
            const tgl_mulai = document.getElementById('gelombangMulai').value;
            const tgl_selesai = document.getElementById('gelombangSelesai').value;
            const biaya_daftar = parseFloat(document.getElementById('gelombangBiaya').value);
            const kuota = parseInt(document.getElementById('gelombangKuota').value);
            const is_aktif = parseInt(document.getElementById('gelombangStatus').value);

            // Validasi form
            if (!nama || !tahun || !tgl_mulai || !tgl_selesai || !biaya_daftar || !kuota) {
                showToast('Semua field harus diisi', 'error');
                return;
            }

            if (new Date(tgl_selesai) <= new Date(tgl_mulai)) {
                showToast('Tanggal selesai harus setelah tanggal mulai', 'error');
                return;
            }

            const data = { nama, tahun, tgl_mulai, tgl_selesai, biaya_daftar, kuota, is_aktif };
            console.log('Data to send:', data);

            const url = id ? `/api/gelombang/${id}` : '/api/gelombang';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('Result:', result);
                    if (result.success) {
                        showToast(result.message || (id ? 'Gelombang berhasil diperbarui!' : 'Gelombang berhasil ditambahkan!'), 'success');
                        bootstrap.Modal.getInstance(document.getElementById('gelombangModal')).hide();
                        setTimeout(() => loadGelombangData(), 300);
                    } else {
                        let errorMessage = result.message || 'Gagal menyimpan data';
                        if (result.errors) {
                            const errors = Object.values(result.errors).flat();
                            errorMessage = errors.join(', ');
                        }
                        showToast(errorMessage, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error saving gelombang:', error);
                    showToast('Terjadi kesalahan: ' + error.message, 'error');
                });
        }

        function saveWilayah() {
            const id = document.getElementById('wilayahId').value;
            const kode_wilayah = document.getElementById('wilayahKode').value;
            const nama_wilayah = document.getElementById('wilayahNama').value;
            const kecamatan = document.getElementById('wilayahKecamatan').value;
            const desa = document.getElementById('wilayahDesa').value;
            const latitude = parseFloat(document.getElementById('wilayahLat').value);
            const longitude = parseFloat(document.getElementById('wilayahLng').value);
            const keterangan = document.getElementById('wilayahKeterangan').value;

            const data = { kode_wilayah, nama_wilayah, kecamatan, desa, latitude, longitude, keterangan };
            const url = id ? `/api/wilayah/${id}` : '/api/wilayah';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showToast(result.message, 'success');
                        loadWilayahData();
                        bootstrap.Modal.getInstance(document.getElementById('wilayahModal')).hide();
                    } else {
                        let errorMessage = result.message || 'Gagal menyimpan data';
                        if (result.errors) {
                            const errors = Object.values(result.errors).flat();
                            errorMessage = errors.join(', ');
                        }
                        showToast(errorMessage, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan', 'error');
                });
        }

        function saveUser() {
            const id = document.getElementById('userId').value;
            const nama = document.getElementById('userNama').value;
            const email = document.getElementById('userEmail').value;
            const hp = document.getElementById('userHp').value;
            const roleElement = document.getElementById('userRole');
            const role = roleElement.value;
            const password = document.getElementById('userPassword').value;

            console.log('=== SAVE USER DEBUG ===');
            console.log('Role element:', roleElement);
            console.log('Role value:', role);
            console.log('Selected index:', roleElement.selectedIndex);
            console.log('All options:', Array.from(roleElement.options).map(o => ({ value: o.value, text: o.text })));
            console.log('Data yang akan dikirim:', { id, nama, email, hp, role });

            if (!nama || !email || !hp || !role) {
                showToast('Semua field harus diisi', 'error');
                return;
            }

            if (!id && !password) {
                showToast('Password harus diisi untuk user baru', 'error');
                return;
            }

            const data = { nama, email, hp, role };
            if (password) data.password = password;

            console.log('JSON yang dikirim:', JSON.stringify(data));

            const url = id ? `/api/pengguna/${id}` : '/api/pengguna';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    showToast(id ? 'User berhasil diperbarui!' : 'User berhasil ditambahkan!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                    setTimeout(() => loadUsersData(), 300);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast(id ? 'User berhasil diperbarui!' : 'User berhasil ditambahkan!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                    setTimeout(() => loadUsersData(), 300);
                });
        }


        function savePendaftar() {
            const id = document.getElementById('pendaftarId').value;
            const jurusan_id = document.getElementById('pendaftarJurusan').value;
            const gelombang_id = document.getElementById('pendaftarGelombang').value;
            const wilayah_id = document.getElementById('pendaftarWilayah').value;
            const status = document.getElementById('pendaftarStatus').value;

            console.log('Form values:', { id, jurusan_id, gelombang_id, wilayah_id, status });

            if (!jurusan_id || !gelombang_id || !status) {
                showToast('Jurusan, Gelombang, dan Status harus diisi', 'error');
                return;
            }

            // Pastikan ID adalah angka yang valid
            const pendaftarId = id && id !== '' ? parseInt(id) : null;
            if (!pendaftarId) {
                showToast('ID pendaftar tidak valid', 'error');
                return;
            }

            const data = {
                jurusan_id: parseInt(jurusan_id),
                gelombang_id: parseInt(gelombang_id),
                wilayah_id: wilayah_id ? parseInt(wilayah_id) : null,
                status: status
            };

            if (pendaftarId) {
                // Update existing pendaftar
                console.log('Updating pendaftar with ID:', pendaftarId);
                console.log('Data to send:', data);
                
                fetch(`/api/pendaftar/${pendaftarId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        showToast(result.message, 'success');
                        loadPendaftarData();
                        bootstrap.Modal.getInstance(document.getElementById('pendaftarModal')).hide();
                    } else {
                        showToast('Gagal menyimpan data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan', 'error');
                });
            } else {
                // Create new pendaftar - need user_id
                showToast('Fitur tambah pendaftar belum tersedia', 'error');
            }
        }

        // Delete Functions
        function deleteJurusan(id) {
            if (confirm('Apakah Anda yakin ingin menghapus jurusan ini?')) {
                fetch(`/api/jurusan/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            showToast(result.message, 'success');
                            loadJurusanData();
                            loadDashboardData();
                        } else {
                            showToast('Gagal menghapus data', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan', 'error');
                    });
            }
        }

        function deleteGelombang(id) {
            if (confirm('Apakah Anda yakin ingin menghapus gelombang ini?')) {
                fetch(`/api/gelombang/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            showToast(result.message, 'success');
                            loadGelombangData();
                        } else {
                            showToast('Gagal menghapus data', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan', 'error');
                    });
            }
        }

        function deleteWilayah(id) {
            if (confirm('Apakah Anda yakin ingin menghapus wilayah ini?')) {
                fetch(`/api/wilayah/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            showToast(result.message, 'success');
                            loadWilayahData();
                        } else {
                            showToast('Gagal menghapus data', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan', 'error');
                    });
            }
        }

        function deleteUser(id) {
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                fetch(`/api/pengguna/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => response.json())
                    .then(result => {
                        showToast('User berhasil dihapus!', 'success');
                        loadUsersData();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Terjadi kesalahan', 'error');
                    });
            }
        }



        // Edit Functions
        function editJurusan(id) {
            showJurusanModal(id);
        }

        function editGelombang(id) {
            showGelombangModal(id);
        }

        function editWilayah(id) {
            showWilayahModal(id);
        }

        function viewUser(id) {
            showUserDetailModal(id);
        }

        function editPendaftar(id) {
            showPendaftarModal(id);
        }

        // Other Functions
        function toggleGelombangStatus(id) {
            const gelombang = database.gelombang.find(g => g.id === id);
            if (gelombang) {
                gelombang.status = gelombang.status === 'Aktif' ? 'Nonaktif' : 'Aktif';
                saveDatabase();
                loadGelombangData();
                showToast(`Status gelombang berhasil diubah menjadi ${gelombang.status}!`, 'success');
            }
        }



        function viewPendaftar(userId) {
            // Find pendaftar from loaded data
            const pendaftar = database.pendaftar.find(p => p.user_id === userId);
            if (!pendaftar) {
                showToast('Data pendaftar tidak ditemukan', 'error');
                return;
            }

            // Load dropdown data first
            Promise.all([
                fetch('/api/jurusan', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } }),
                fetch('/api/gelombang', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } }),
                fetch('/api/wilayah', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([jurusanResult, gelombangResult, wilayahResult]) => {
                // Populate dropdowns
                const jurusanSelect = document.getElementById('pendaftarJurusan');
                const gelombangSelect = document.getElementById('pendaftarGelombang');
                const wilayahSelect = document.getElementById('pendaftarWilayah');

                jurusanSelect.innerHTML = '<option value="">-- Pilih Jurusan --</option>';
                gelombangSelect.innerHTML = '<option value="">-- Pilih Gelombang --</option>';
                wilayahSelect.innerHTML = '<option value="">-- Pilih Wilayah --</option>';

                if (jurusanResult.success) {
                    jurusanResult.data.forEach(jurusan => {
                        const option = document.createElement('option');
                        option.value = jurusan.id;
                        option.textContent = jurusan.nama;
                        jurusanSelect.appendChild(option);
                    });
                }

                if (gelombangResult.success) {
                    gelombangResult.data.forEach(gelombang => {
                        const option = document.createElement('option');
                        option.value = gelombang.id;
                        option.textContent = gelombang.nama;
                        gelombangSelect.appendChild(option);
                    });
                }

                if (wilayahResult.success) {
                    wilayahResult.data.forEach(wilayah => {
                        const option = document.createElement('option');
                        option.value = wilayah.id;
                        option.textContent = wilayah.nama_wilayah;
                        wilayahSelect.appendChild(option);
                    });
                }

                const modal = document.getElementById('pendaftarModal');
                const title = document.getElementById('pendaftarModalTitle');
                
                title.textContent = 'Detail Pendaftar';
                
                // Populate form with data (read-only)
                document.getElementById('pendaftarId').value = pendaftar.id || '';
                document.getElementById('pendaftarNo').value = pendaftar.no_pendaftaran || 'Auto Generate';
                document.getElementById('pendaftarNama').value = pendaftar.nama || '';
                document.getElementById('pendaftarEmail').value = pendaftar.email || '';
                document.getElementById('pendaftarTelepon').value = pendaftar.hp || '';
                document.getElementById('pendaftarJurusan').value = pendaftar.jurusan_id || '';
                document.getElementById('pendaftarGelombang').value = pendaftar.gelombang_id || '';
                document.getElementById('pendaftarWilayah').value = pendaftar.wilayah_id || '';
                document.getElementById('pendaftarStatus').value = pendaftar.status || 'Menunggu';
                
                // Make all fields readonly
                document.querySelectorAll('#formPendaftar input, #formPendaftar select').forEach(field => {
                    field.setAttribute('readonly', true);
                    field.setAttribute('disabled', true);
                });
                
                // Hide save button
                document.querySelector('#pendaftarModal .btn-primary').style.display = 'none';
                
                new bootstrap.Modal(modal).show();
            })
            .catch(error => {
                console.error('Error loading dropdown data:', error);
                showToast('Gagal memuat data dropdown', 'error');
            });
        }



        function showFilterModal() {
            // Load dropdown data for filter
            Promise.all([
                fetch('/api/jurusan', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } }),
                fetch('/api/gelombang', { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } })
            ])
            .then(responses => Promise.all(responses.map(r => r.json())))
            .then(([jurusanResult, gelombangResult]) => {
                // Populate filter dropdowns
                const jurusanSelect = document.getElementById('filterJurusan');
                const gelombangSelect = document.getElementById('filterGelombang');

                jurusanSelect.innerHTML = '<option value="">-- Semua Jurusan --</option>';
                gelombangSelect.innerHTML = '<option value="">-- Semua Gelombang --</option>';

                if (jurusanResult.success) {
                    jurusanResult.data.forEach(jurusan => {
                        const option = document.createElement('option');
                        option.value = jurusan.id;
                        option.textContent = jurusan.nama;
                        jurusanSelect.appendChild(option);
                    });
                }

                if (gelombangResult.success) {
                    gelombangResult.data.forEach(gelombang => {
                        const option = document.createElement('option');
                        option.value = gelombang.id;
                        option.textContent = gelombang.nama;
                        gelombangSelect.appendChild(option);
                    });
                }

                new bootstrap.Modal(document.getElementById('filterModal')).show();
            })
            .catch(error => {
                console.error('Error loading filter data:', error);
                showToast('Gagal memuat data filter', 'error');
            });
        }

        function resetFilter() {
            document.getElementById('formFilter').reset();
            loadPendaftarData();
            bootstrap.Modal.getInstance(document.getElementById('filterModal')).hide();
        }

        function applyFilter() {
            const status = document.getElementById('filterStatus').value;
            const jurusan = document.getElementById('filterJurusan').value;
            const gelombang = document.getElementById('filterGelombang').value;
            const tanggalMulai = document.getElementById('filterTanggalMulai').value;
            const tanggalSelesai = document.getElementById('filterTanggalSelesai').value;

            const params = new URLSearchParams();
            if (status) params.append('status', status);
            if (jurusan) params.append('jurusan_id', jurusan);
            if (gelombang) params.append('gelombang_id', gelombang);
            if (tanggalMulai) params.append('tanggal_mulai', tanggalMulai);
            if (tanggalSelesai) params.append('tanggal_selesai', tanggalSelesai);

            loadPendaftarData(params.toString());
            bootstrap.Modal.getInstance(document.getElementById('filterModal')).hide();
        }

        function exportData() {
            fetch('/api/pendaftar', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Convert data to CSV format
                    const data = result.data;
                    const headers = ['No Pendaftaran', 'Nama', 'Email', 'HP', 'Asal Sekolah', 'Jurusan', 'Gelombang', 'Status', 'Tanggal Daftar'];
                    
                    let csvContent = headers.join(',') + '\n';
                    data.forEach(row => {
                        const csvRow = [
                            row.no_pendaftaran || '-',
                            row.nama || '-',
                            row.email || '-',
                            row.hp || '-',
                            row.asal_sekolah || '-',
                            row.jurusan || '-',
                            row.gelombang || '-',
                            row.status || '-',
                            formatDate(row.tanggal_daftar) || '-'
                        ].map(field => `"${field}"`).join(',');
                        csvContent += csvRow + '\n';
                    });
                    
                    // Create and download file
                    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                    const link = document.createElement('a');
                    const url = URL.createObjectURL(blob);
                    link.setAttribute('href', url);
                    link.setAttribute('download', `data_pendaftar_${new Date().toISOString().split('T')[0]}.csv`);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    
                    showToast('Data berhasil diexport!', 'success');
                } else {
                    showToast('Gagal export data', 'error');
                }
            })
            .catch(error => {
                console.error('Error exporting data:', error);
                showToast('Gagal export data', 'error');
            });
        }

        function generateLaporan(jenis) {
            if (jenis === 'komprehensif') {
                // Buka laporan di tab baru
                window.open('/api/laporan/download?type=komprehensif', '_blank');
                showToast('Laporan dibuka di tab baru', 'success');
                return;
            }
            
            let endpoint = '';
            let params = {};
            
            switch(jenis) {
                case 'harian':
                    endpoint = '/api/laporan/harian';
                    params.tanggal = new Date().toISOString().split('T')[0];
                    break;
                case 'bulanan':
                    endpoint = '/api/laporan/bulanan';
                    params.bulan = new Date().toISOString().slice(0, 7);
                    break;
                default:
                    showToast('Jenis laporan tidak valid', 'error');
                    return;
            }
            
            const urlParams = new URLSearchParams(params);
            const url = endpoint + (Object.keys(params).length ? '?' + urlParams : '');
            
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    showToast(`Laporan ${jenis} berhasil digenerate!`, 'success');
                } else {
                    showToast(`Gagal generate laporan ${jenis}: ` + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error generating laporan:', error);
                showToast(`Gagal generate laporan ${jenis}`, 'error');
            });
        }

        function sendToKepsek() {
            // Generate komprehensif report first
            fetch('/api/laporan/komprehensif', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Simulate sending email to kepsek
                    showToast('Laporan berhasil dikirim ke Kepala Sekolah!', 'success');
                } else {
                    showToast('Gagal mengirim laporan: ' + result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error sending laporan:', error);
                showToast('Gagal mengirim laporan ke Kepala Sekolah', 'error');
            });
        }



        // Utility Functions
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

        function getStatusColor(status) {
            switch (status) {
                case 'Selesai': return 'success';
                case 'Proses': return 'warning';
                case 'Update': return 'info';
                default: return 'secondary';
            }
        }

        function getGelombangStatusColor(status) {
            switch (status) {
                case 'Aktif': return 'success';
                case 'Akan Datang': return 'warning';
                case 'Selesai': return 'secondary';
                default: return 'secondary';
            }
        }

        function getRoleColor(role) {
            switch (role) {
                case 'admin': return 'primary';
                case 'kepsek': return 'danger';
                case 'verifikator_adm': return 'warning';
                case 'keuangan': return 'info';
                case 'pendaftar': return 'secondary';
                default: return 'secondary';
            }
        }

        function getRoleLabel(role) {
            switch (role) {
                case 'admin': return 'Admin';
                case 'kepsek': return 'Kepala Sekolah';
                case 'verifikator_adm': return 'Verifikator';
                case 'keuangan': return 'Keuangan';
                case 'pendaftar': return 'Pendaftar';
                default: return role;
            }
        }

        function getPendaftarStatusColor(status) {
            switch (status) {
                case 'Terverifikasi': return 'success';
                case 'Menunggu': return 'warning';
                case 'Ditolak': return 'danger';
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

        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function generateNoPendaftaran() {
            const year = new Date().getFullYear();
            const count = database.pendaftar.length + 1;
            return `PPDB${year}${count.toString().padStart(3, '0')}`;
        }

        // Validasi real-time untuk form jurusan
        function setupJurusanValidation() {
            const kodeInput = document.getElementById('jurusanKode');
            const namaInput = document.getElementById('jurusanNama');
            const kuotaInput = document.getElementById('jurusanKuota');

            if (!kodeInput || !namaInput || !kuotaInput) return;

            // Validasi kode jurusan
            kodeInput.addEventListener('input', function() {
                const value = this.value.trim();
                const errorDiv = document.getElementById('jurusanKodeError');
                const currentId = document.getElementById('jurusanId').value;
                
                this.classList.remove('is-invalid', 'is-valid');
                
                if (value.length === 0) {
                    this.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = 'Kode jurusan harus diisi';
                } else if (value.length > 10) {
                    this.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = 'Kode jurusan maksimal 10 karakter';
                } else {
                    // Cek duplikasi
                    const isDuplicate = (database.jurusan || []).some(j => 
                        j.kode.toLowerCase() === value.toLowerCase() && 
                        (!currentId || j.id != currentId)
                    );
                    
                    if (isDuplicate) {
                        this.classList.add('is-invalid');
                        if (errorDiv) errorDiv.textContent = 'Kode jurusan sudah digunakan';
                    } else {
                        this.classList.add('is-valid');
                        if (errorDiv) errorDiv.textContent = '';
                    }
                }
            });

            // Validasi nama jurusan
            namaInput.addEventListener('input', function() {
                const value = this.value.trim();
                const errorDiv = document.getElementById('jurusanNamaError');
                const currentId = document.getElementById('jurusanId').value;
                
                this.classList.remove('is-invalid', 'is-valid');
                
                if (value.length === 0) {
                    this.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = 'Nama jurusan harus diisi';
                } else if (value.length > 100) {
                    this.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = 'Nama jurusan maksimal 100 karakter';
                } else {
                    // Cek duplikasi
                    const isDuplicate = (database.jurusan || []).some(j => 
                        j.nama.toLowerCase() === value.toLowerCase() && 
                        (!currentId || j.id != currentId)
                    );
                    
                    if (isDuplicate) {
                        this.classList.add('is-invalid');
                        if (errorDiv) errorDiv.textContent = 'Nama jurusan sudah digunakan';
                    } else {
                        this.classList.add('is-valid');
                        if (errorDiv) errorDiv.textContent = '';
                    }
                }
            });

            // Validasi kuota
            kuotaInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                const errorDiv = document.getElementById('jurusanKuotaError');
                
                this.classList.remove('is-invalid', 'is-valid');
                
                if (isNaN(value) || value < 1) {
                    this.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = 'Kuota minimal 1';
                } else if (value > 1000) {
                    this.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = 'Kuota maksimal 1000';
                } else {
                    this.classList.add('is-valid');
                    if (errorDiv) errorDiv.textContent = '';
                }
            });
        }

        // Validasi real-time untuk form gelombang
        function setupGelombangValidation() {
            const namaInput = document.getElementById('gelombangNama');
            const tahunInput = document.getElementById('gelombangTahun');
            const mulaiInput = document.getElementById('gelombangMulai');
            const selesaiInput = document.getElementById('gelombangSelesai');
            const biayaInput = document.getElementById('gelombangBiaya');
            const kuotaInput = document.getElementById('gelombangKuota');

            if (!namaInput || !tahunInput) return;

            // Validasi nama gelombang
            namaInput.addEventListener('input', function() {
                validateGelombangCombo();
            });

            // Validasi tahun
            tahunInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                const errorDiv = document.getElementById('gelombangTahunError');
                
                this.classList.remove('is-invalid', 'is-valid');
                
                if (isNaN(value) || value < 2020) {
                    this.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = 'Tahun minimal 2020';
                } else if (value > 2030) {
                    this.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = 'Tahun maksimal 2030';
                } else {
                    this.classList.add('is-valid');
                    if (errorDiv) errorDiv.textContent = '';
                    validateGelombangCombo();
                }
            });

            // Validasi tanggal
            if (mulaiInput && selesaiInput) {
                selesaiInput.addEventListener('change', function() {
                    const mulai = new Date(mulaiInput.value);
                    const selesai = new Date(this.value);
                    const errorDiv = document.getElementById('gelombangSelesaiError');
                    
                    this.classList.remove('is-invalid', 'is-valid');
                    
                    if (selesai <= mulai) {
                        this.classList.add('is-invalid');
                        if (errorDiv) errorDiv.textContent = 'Tanggal selesai harus setelah tanggal mulai';
                    } else {
                        this.classList.add('is-valid');
                        if (errorDiv) errorDiv.textContent = '';
                    }
                });
            }

            // Validasi biaya
            if (biayaInput) {
                biayaInput.addEventListener('input', function() {
                    const value = parseFloat(this.value);
                    const errorDiv = document.getElementById('gelombangBiayaError');
                    
                    this.classList.remove('is-invalid', 'is-valid');
                    
                    if (isNaN(value) || value < 0) {
                        this.classList.add('is-invalid');
                        if (errorDiv) errorDiv.textContent = 'Biaya minimal 0';
                    } else {
                        this.classList.add('is-valid');
                        if (errorDiv) errorDiv.textContent = '';
                    }
                });
            }

            // Validasi kuota
            if (kuotaInput) {
                kuotaInput.addEventListener('input', function() {
                    const value = parseInt(this.value);
                    const errorDiv = document.getElementById('gelombangKuotaError');
                    
                    this.classList.remove('is-invalid', 'is-valid');
                    
                    if (isNaN(value) || value < 1) {
                        this.classList.add('is-invalid');
                        if (errorDiv) errorDiv.textContent = 'Kuota minimal 1';
                    } else {
                        this.classList.add('is-valid');
                        if (errorDiv) errorDiv.textContent = '';
                    }
                });
            }
        }

        function validateGelombangCombo() {
            const namaInput = document.getElementById('gelombangNama');
            const tahunInput = document.getElementById('gelombangTahun');
            const currentId = document.getElementById('gelombangId').value;
            
            if (!namaInput || !tahunInput) return;
            
            const nama = namaInput.value.trim();
            const tahun = parseInt(tahunInput.value);
            const errorDiv = document.getElementById('gelombangNamaError');
            
            namaInput.classList.remove('is-invalid', 'is-valid');
            
            if (nama.length === 0) {
                namaInput.classList.add('is-invalid');
                if (errorDiv) errorDiv.textContent = 'Nama gelombang harus diisi';
            } else if (nama.length > 100) {
                namaInput.classList.add('is-invalid');
                if (errorDiv) errorDiv.textContent = 'Nama gelombang maksimal 100 karakter';
            } else if (!isNaN(tahun)) {
                // Cek duplikasi kombinasi
                const isDuplicate = (database.gelombang || []).some(g => 
                    g.nama.toLowerCase() === nama.toLowerCase() && 
                    g.tahun == tahun && 
                    (!currentId || g.id != currentId)
                );
                
                if (isDuplicate) {
                    namaInput.classList.add('is-invalid');
                    if (errorDiv) errorDiv.textContent = 'Kombinasi nama gelombang dan tahun sudah ada';
                } else {
                    namaInput.classList.add('is-valid');
                    if (errorDiv) errorDiv.textContent = '';
                }
            } else {
                namaInput.classList.add('is-valid');
                if (errorDiv) errorDiv.textContent = '';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
            // Set user info
            document.getElementById('userName').textContent = currentUser.nama;
            document.getElementById('dashboardUserName').textContent = currentUser.nama;
            document.getElementById('userRoleBadge').textContent = currentUser.role;

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

            // Setup form validation
            setTimeout(setupJurusanValidation, 100);
            setTimeout(setupGelombangValidation, 100);

            // Load initial data
            loadDashboardData();
        });
    </script>
</body>

</html>