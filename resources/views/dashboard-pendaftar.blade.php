<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Siswa - SMK Bakti Nusantara 666</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1e3c72;
            --secondary: #2a5298;
            --success: #28a745;
            --warning: #ffc107;
            --info: #17a2b8;
            --siswa: #1e3c72;
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
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
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
            border-left: 4px solid var(--siswa);
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

        .stat-card.danger {
            border-left-color: #dc3545;
        }

        .stat-card.secondary {
            border-left-color: #6c757d;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-siswa {
            background: var(--siswa);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
        }

        .btn-siswa:hover {
            background: var(--secondary);
            color: white;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        .navbar-siswa {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .siswa-badge {
            background: var(--siswa);
            color: white;
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
                        <i class="fas fa-user-graduate me-2"></i>
                        SISWA
                    </h5>
                    <p class="text-center small mb-4">SMK Bakti Nusantara 666</p>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" onclick="showSection('dashboard')">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showSection('profil')">
                                <i class="fas fa-user me-2"></i>Profil Saya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showSection('formulir')">
                                <i class="fas fa-edit me-2"></i>Formulir Pendaftaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showSection('berkas')">
                                <i class="fas fa-file-upload me-2"></i>Upload Berkas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showSection('pembayaran')">
                                <i class="fas fa-credit-card me-2"></i>Pembayaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showSection('status')">
                                <i class="fas fa-clipboard-check me-2"></i>Status Pendaftaran
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showSection('pengumuman')">
                                <i class="fas fa-bullhorn me-2"></i>Pengumuman
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 content-area">
                <!-- Header -->
                <nav class="navbar navbar-siswa navbar-light border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-brand mb-0 h6 text-white">
                            <i class="fas fa-user-graduate me-2"></i>Dashboard Siswa
                        </span>
                        <div class="d-flex align-items-center">
                            <span class="me-3 text-white" id="userName">{{ e($user->nama) }}</span>
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

                <!-- Content Sections -->
                <div class="container-fluid mt-4">
                    <!-- Dashboard Section -->
                    <div id="dashboard" class="section active">
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom border-0">
                                    <div class="card-body p-4">
                                        <h2 class="fw-bold mb-2" style="color: var(--siswa);">
                                            <i class="fas fa-user-graduate me-2"></i>Dashboard Siswa
                                        </h2>
                                        <p class="text-muted mb-0">
                                            Selamat datang, <strong>{{ e($user->nama) }}</strong>!
                                            <span class="badge siswa-badge ms-2">Calon Siswa</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Stat Cards -->
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div
                                    class="stat-card {{ $pendaftar ? ($pendaftar->status == 'Terverifikasi' ? 'success' : ($pendaftar->status == 'Ditolak' ? 'danger' : 'warning')) : 'secondary' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Status Pendaftaran</h5>
                                            <h2 class="mb-0" id="statusPendaftaran">
                                                {{ $pendaftar ? ($pendaftar->status ?: 'Menunggu') : 'Belum Daftar' }}
                                            </h2>
                                            <small><i class="fas fa-info-circle me-1"></i>Status terbaru</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-clipboard-check fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Data Formulir</h5>
                                            <h2 class="mb-0" id="dataFormulir">
                                                @php
                                                    $dataCount = 0;
                                                    if ($pendaftar && $pendaftar->dataSiswa)
                                                        $dataCount++;
                                                    if ($pendaftar && $pendaftar->dataOrtu)
                                                        $dataCount++;
                                                    if ($pendaftar && $pendaftar->asalSekolah)
                                                        $dataCount++;
                                                @endphp
                                                {{ $dataCount }}/3
                                            </h2>
                                            <small><i class="fas fa-form me-1"></i>Data terisi</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-edit fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card warning">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Berkas Terupload</h5>
                                            <h2 class="mb-0" id="berkasCount">
                                                {{ $pendaftar ? $pendaftar->berkas->where('valid', 1)->count() : 0 }}/5
                                            </h2>
                                            <small><i class="fas fa-file me-1"></i>Dokumen lengkap</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-file-upload fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div
                                    class="stat-card {{ $pendaftar && $pendaftar->tgl_verifikasi_payment ? 'success' : 'danger' }}">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Pembayaran</h5>
                                            <h2 class="mb-0" id="statusPembayaran">
                                                {{ $pendaftar && $pendaftar->tgl_verifikasi_payment ? 'Lunas' : 'Menunggu' }}
                                            </h2>
                                            <small><i class="fas fa-credit-card me-1"></i>Status terbaru</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Info Verifikasi -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom border-0">
                                    <div class="card-body p-4">
                                        <h5 class="fw-bold mb-3" style="color: var(--siswa);">
                                            <i class="fas fa-info-circle me-2"></i>Status Verifikasi Data
                                        </h5>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    @php
                                                        $statusSiswa = null;
                                                        if ($pendaftar && $pendaftar->dataSiswa) {
                                                            $statusSiswa = $pendaftar->dataSiswa->status_verifikasi;
                                                        }
                                                    @endphp
                                                    <span class="badge 
                                                        @if($statusSiswa === null) bg-secondary
                                                        @elseif($statusSiswa === 0) bg-warning
                                                        @elseif($statusSiswa === 1) bg-success
                                                        @else bg-danger
                                                        @endif me-2" id="statusDataSiswa">
                                                        @if($statusSiswa === null) Belum diisi
                                                        @elseif($statusSiswa === 0) Menunggu verifikasi
                                                        @elseif($statusSiswa === 1) Terverifikasi
                                                        @else Ditolak
                                                        @endif
                                                    </span>
                                                    <span>Data Siswa</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    @php
                                                        $statusOrtu = null;
                                                        if ($pendaftar && $pendaftar->dataOrtu) {
                                                            $statusOrtu = $pendaftar->dataOrtu->status_verifikasi;
                                                        }
                                                    @endphp
                                                    <span class="badge 
                                                        @if($statusOrtu === null) bg-secondary
                                                        @elseif($statusOrtu === 0) bg-warning
                                                        @elseif($statusOrtu === 1) bg-success
                                                        @else bg-danger
                                                        @endif me-2" id="statusDataOrtu">
                                                        @if($statusOrtu === null) Belum diisi
                                                        @elseif($statusOrtu === 0) Menunggu verifikasi
                                                        @elseif($statusOrtu === 1) Terverifikasi
                                                        @else Ditolak
                                                        @endif
                                                    </span>
                                                    <span>Data Orang Tua</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center mb-2">
                                                    @php
                                                        $statusSekolah = null;
                                                        if ($pendaftar && $pendaftar->asalSekolah) {
                                                            $statusSekolah = $pendaftar->asalSekolah->status_verifikasi;
                                                        }
                                                    @endphp
                                                    <span class="badge 
                                                        @if($statusSekolah === null) bg-secondary
                                                        @elseif($statusSekolah === 0) bg-warning
                                                        @elseif($statusSekolah === 1) bg-success
                                                        @else bg-danger
                                                        @endif me-2" id="statusAsalSekolah">
                                                        @if($statusSekolah === null) Belum diisi
                                                        @elseif($statusSekolah === 0) Menunggu verifikasi
                                                        @elseif($statusSekolah === 1) Terverifikasi
                                                        @else Ditolak
                                                        @endif
                                                    </span>
                                                    <span>Data Asal Sekolah</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="alert alert-info mt-3 mb-0">
                                            <i class="fas fa-exclamation-circle me-2"></i>
                                            <strong>Penting:</strong> Semua data formulir akan diverifikasi oleh
                                            verifikator sebelum pendaftaran dapat diproses lebih lanjut.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profil Section -->
                    <div id="profil" class="section">
                        <div class="card card-custom">
                            <div
                                class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-user me-2 text-primary"></i>Profil Saya</h5>
                                <button class="btn btn-siswa btn-sm" onclick="editProfil()">
                                    <i class="fas fa-edit me-2"></i>Edit Profil
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>No. Pendaftaran</strong></label>
                                                    <p>PPDB2024001</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Nama Lengkap</strong></label>
                                                    <p>{{ $user->nama }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Email</strong></label>
                                                    <p>{{ $user->email }}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Nomor HP</strong></label>
                                                    <p>{{ $user->hp }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Wilayah</strong></label>
                                                    <p>{{ $user->wilayah_id ?? 'Belum diisi' }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><strong>Asal Sekolah</strong></label>
                                                    <p>{{ $user->asal_sekolah ?? 'Belum diisi' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulir Pendaftaran Section -->
                    <div id="formulir" class="section">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card-custom mb-4">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i class="fas fa-edit me-2 text-primary"></i>Formulir
                                            Pendaftaran</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            Lengkapi semua data di bawah ini untuk melanjutkan proses pendaftaran.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Calon Siswa -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0"><i class="fas fa-user-graduate me-2"></i>Data Calon Siswa</h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="formDataSiswa">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">NIK <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="nik"
                                                            placeholder="Masukkan NIK (16 digit)" maxlength="16">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">NISN <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="nisn"
                                                            placeholder="Masukkan NISN">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Lengkap <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="namaLengkap"
                                                            value="{{ $user->nama }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Jenis Kelamin <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" id="jenisKelamin">
                                                            <option value="">Pilih Jenis Kelamin</option>
                                                            <option value="L">Laki-laki</option>
                                                            <option value="P">Perempuan</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tempat Lahir <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="tempatLahir"
                                                            placeholder="Masukkan tempat lahir">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tanggal Lahir <span
                                                                class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="tanggalLahir">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Agama <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" id="agama">
                                                            <option value="">Pilih Agama</option>
                                                            <option value="Islam">Islam</option>
                                                            <option value="Kristen">Kristen</option>
                                                            <option value="Katolik">Katolik</option>
                                                            <option value="Hindu">Hindu</option>
                                                            <option value="Buddha">Buddha</option>
                                                            <option value="Konghucu">Konghucu</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Wilayah <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" id="wilayahId">
                                                            <option value="">Pilih Wilayah</option>
                                                            @foreach($wilayah as $w)
                                                                <option value="{{ $w->id }}" {{ $user->wilayah_id == $w->id ? 'selected' : '' }}>{{ $w->nama_wilayah }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Alamat Lengkap <span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="alamat" rows="3"
                                                            placeholder="Masukkan alamat lengkap"></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nomor HP <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="nomorHP"
                                                            value="{{ $user->hp }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email <span
                                                                class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="email"
                                                            value="{{ $user->email }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Jurusan Pilihan <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" id="jurusanPilihan">
                                                            <option value="">Pilih Jurusan</option>
                                                            @php
                                                                $jurusanList = \App\Models\Jurusan::all();
                                                            @endphp
                                                            @foreach($jurusanList as $j)
                                                                <option value="{{ $j->id }}" {{ $pendaftar && $pendaftar->jurusan_id == $j->id ? 'selected' : '' }}>
                                                                    {{ $j->nama }} (Kuota: {{ $j->kuota }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-text">Pilih jurusan yang diminati sesuai dengan
                                                            minat dan bakat Anda</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-siswa" onclick="simpanDataSiswa()">
                                                    <i class="fas fa-save me-2"></i>Simpan Data Siswa
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Orang Tua -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="mb-0"><i class="fas fa-users me-2"></i>Data Orang Tua/Wali</h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="formDataOrtu">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-primary mb-3">Data Ayah</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Ayah <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="namaAyah"
                                                            placeholder="Masukkan nama ayah">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Pekerjaan Ayah</label>
                                                        <input type="text" class="form-control" id="pekerjaanAyah"
                                                            placeholder="Masukkan pekerjaan ayah">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Penghasilan Ayah</label>
                                                        <select class="form-select" id="penghasilanAyah">
                                                            <option value="">Pilih Penghasilan</option>
                                                            <option value="< 1 juta">
                                                                < Rp 1.000.000</option>
                                                            <option value="1-3 juta">Rp 1.000.000 - 3.000.000</option>
                                                            <option value="3-5 juta">Rp 3.000.000 - 5.000.000</option>
                                                            <option value="> 5 juta">> Rp 5.000.000</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">No. HP Ayah</label>
                                                        <input type="text" class="form-control" id="hpAyah"
                                                            placeholder="Masukkan nomor HP ayah">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="text-primary mb-3">Data Ibu</h6>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Ibu <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="namaIbu"
                                                            placeholder="Masukkan nama ibu">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Pekerjaan Ibu</label>
                                                        <input type="text" class="form-control" id="pekerjaanIbu"
                                                            placeholder="Masukkan pekerjaan ibu">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Penghasilan Ibu</label>
                                                        <select class="form-select" id="penghasilanIbu">
                                                            <option value="">Pilih Penghasilan</option>
                                                            <option value="< 1 juta">
                                                                < Rp 1.000.000</option>
                                                            <option value="1-3 juta">Rp 1.000.000 - 3.000.000</option>
                                                            <option value="3-5 juta">Rp 3.000.000 - 5.000.000</option>
                                                            <option value="> 5 juta">> Rp 5.000.000</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">No. HP Ibu</label>
                                                        <input type="text" class="form-control" id="hpIbu"
                                                            placeholder="Masukkan nomor HP ibu">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-success"
                                                    onclick="simpanDataOrtu()">
                                                    <i class="fas fa-save me-2"></i>Simpan Data Orang Tua
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Asal Sekolah -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0"><i class="fas fa-school me-2"></i>Data Asal Sekolah</h6>
                                    </div>
                                    <div class="card-body">
                                        <form id="formAsalSekolah">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Sekolah <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="namaSekolah"
                                                            value="{{ $user->asal_sekolah ?? '' }}"
                                                            placeholder="Masukkan nama sekolah">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">NPSN Sekolah</label>
                                                        <input type="text" class="form-control" id="npsnSekolah"
                                                            placeholder="Masukkan NPSN sekolah">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Status Sekolah</label>
                                                        <select class="form-select" id="statusSekolah">
                                                            <option value="">Pilih Status</option>
                                                            <option value="Negeri">Negeri</option>
                                                            <option value="Swasta">Swasta</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Kabupaten <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="kabupatenSekolah"
                                                            placeholder="Masukkan kabupaten sekolah">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Alamat Sekolah <span
                                                                class="text-danger">*</span></label>
                                                        <textarea class="form-control" id="alamatSekolah" rows="3"
                                                            placeholder="Masukkan alamat sekolah"></textarea>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tahun Lulus <span
                                                                class="text-danger">*</span></label>
                                                        <select class="form-select" id="tahunLulus">
                                                            <option value="">Pilih Tahun Lulus</option>
                                                            <option value="2024">2024</option>
                                                            <option value="2023">2023</option>
                                                            <option value="2022">2022</option>
                                                            <option value="2021">2021</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Nilai Rata-rata <span
                                                                class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="nilaiRata"
                                                            step="0.01" min="0" max="100"
                                                            placeholder="Masukkan nilai rata-rata">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-warning"
                                                    onclick="simpanAsalSekolah()">
                                                    <i class="fas fa-save me-2"></i>Simpan Data Sekolah
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Berkas Section -->
                    <div id="berkas" class="section">
                        <div class="card card-custom">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0"><i class="fas fa-file-upload me-2 text-primary"></i>Upload Berkas
                                    Persyaratan</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Pastikan semua berkas diupload dalam format PDF/JPG/PNG dengan ukuran maksimal 2MB
                                    per file.
                                </div>

                                <div class="document-list">
                                    <div class="document-item border rounded p-3 mb-3" data-jenis="ktp">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-1">Kartu Tanda Penduduk (KTP)</h6>
                                                <p class="mb-0 text-muted">Upload scan KTP yang masih berlaku</p>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-danger">Belum</span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <button class="btn btn-siswa btn-sm" onclick="uploadDocument('ktp')">
                                                    <i class="fas fa-upload me-1"></i>Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="document-item border rounded p-3 mb-3" data-jenis="ijazah">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-1">Ijazah/Surat Keterangan Lulus</h6>
                                                <p class="mb-0 text-muted">Ijazah SMP/sederajat atau surat keterangan
                                                    lulus</p>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-danger">Belum</span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <button class="btn btn-siswa btn-sm" onclick="uploadDocument('ijazah')">
                                                    <i class="fas fa-upload me-1"></i>Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="document-item border rounded p-3 mb-3" data-jenis="rapor">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-1">Rapor Semester 1-5</h6>
                                                <p class="mb-0 text-muted">Scan rapor SMP/sederajat semester 1 sampai 5
                                                </p>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-danger">Belum</span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <button class="btn btn-siswa btn-sm" onclick="uploadDocument('rapor')">
                                                    <i class="fas fa-upload me-1"></i>Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="document-item border rounded p-3 mb-3" data-jenis="foto">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-1">Foto 3x4</h6>
                                                <p class="mb-0 text-muted">Foto terbaru ukuran 3x4 dengan background
                                                    merah</p>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-danger">Belum</span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <button class="btn btn-siswa btn-sm" onclick="uploadDocument('foto')">
                                                    <i class="fas fa-upload me-1"></i>Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="document-item border rounded p-3 mb-3" data-jenis="sehat">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-1">Surat Keterangan Sehat</h6>
                                                <p class="mb-0 text-muted">Surat keterangan sehat dari dokter</p>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge bg-danger">Belum</span>
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <button class="btn btn-siswa btn-sm" onclick="uploadDocument('sehat')">
                                                    <i class="fas fa-upload me-1"></i>Upload
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pembayaran Section -->
                    <div id="pembayaran" class="section">
                        <div class="card card-custom">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0"><i class="fas fa-credit-card me-2 text-primary"></i>Informasi
                                    Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">Detail Tagihan</h6>
                                            </div>
                                            <div class="card-body">
                                                <table class="table table-sm">
                                                    <tr>
                                                        <td>Biaya Pendaftaran
                                                            {{ $gelombang ? $gelombang->nama : 'Gelombang 1' }}:
                                                        </td>
                                                        <td class="text-end">Rp
                                                            {{ number_format($biayaPendaftaran, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    <tr class="table-success">
                                                        <td><strong>Total Tagihan:</strong></td>
                                                        <td class="text-end"><strong>Rp
                                                                {{ number_format($biayaPendaftaran, 0, ',', '.') }}</strong>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0">Status Pembayaran</h6>
                                            </div>
                                            <div class="card-body text-center">
                                                <div class="mb-3" id="statusPembayaranCard">
                                                    @if($pendaftar && $pendaftar->tgl_verifikasi_payment)
                                                        <span class="badge bg-success fs-6">Pembayaran Lunas</span>
                                                    @else
                                                        <span class="badge bg-warning fs-6">Menunggu Pembayaran</span>
                                                    @endif
                                                </div>
                                                <p class="text-muted" id="keteranganPembayaran">
                                                    @if($pendaftar && $pendaftar->tgl_verifikasi_payment)
                                                        Pembayaran Anda telah diverifikasi dan diterima pada
                                                        {{ \Carbon\Carbon::parse($pendaftar->tgl_verifikasi_payment)->format('d M Y') }}
                                                    @else
                                                        Silakan lakukan pembayaran sebelum <strong>20 Jan 2024</strong>
                                                    @endif
                                                </p>
                                                <div id="buttonPembayaran">
                                                    @if($pendaftar && $pendaftar->tgl_verifikasi_payment)
                                                        <button class="btn btn-success" disabled>
                                                            <i class="fas fa-check me-2"></i>Pembayaran Selesai
                                                        </button>
                                                    @else
                                                        <button class="btn btn-siswa" onclick="showPembayaranModal()">
                                                            <i class="fas fa-credit-card me-2"></i>Bayar Sekarang
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Rekening Pembayaran</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 text-center">
                                                <div class="border rounded p-3">
                                                    <h5>BCA</h5>
                                                    <p class="mb-1"><strong>1234567890</strong></p>
                                                    <p class="mb-0">SMK Bakti Nusantara 666</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="border rounded p-3">
                                                    <h5>Mandiri</h5>
                                                    <p class="mb-1"><strong>0987654321</strong></p>
                                                    <p class="mb-0">SMK Bakti Nusantara 666</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="border rounded p-3">
                                                    <h5>BNI</h5>
                                                    <p class="mb-1"><strong>1122334455</strong></p>
                                                    <p class="mb-0">SMK Bakti Nusantara 666</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Section -->
                    <div id="status" class="section">
                        <div class="card card-custom">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2 text-primary"></i>Status
                                    Pendaftaran</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">Ringkasan Status</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3">
                                                            <h4
                                                                class="{{ $pendaftar ? 'text-success' : 'text-secondary' }}">
                                                                {{ $pendaftar ? '✓' : '○' }}
                                                            </h4>
                                                            <small>Pendaftaran</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3">
                                                            @php
                                                                $hasDataSiswa = $pendaftar && $pendaftar->dataSiswa;
                                                            @endphp
                                                            <h4
                                                                class="{{ $hasDataSiswa ? 'text-success' : 'text-warning' }}">
                                                                {{ $hasDataSiswa ? '✓' : '⏳' }}
                                                            </h4>
                                                            <small>Data Diri</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3">
                                                            @php
                                                                $berkasVerified = $pendaftar ? $pendaftar->berkas->where('valid', 1)->count() : 0;
                                                                $totalBerkas = $pendaftar ? $pendaftar->berkas->count() : 0;
                                                            @endphp
                                                            <h4
                                                                class="{{ $berkasVerified >= 3 ? 'text-success' : ($totalBerkas > 0 ? 'text-warning' : 'text-danger') }}">
                                                                {{ $berkasVerified >= 3 ? '✓' : ($totalBerkas > 0 ? '⏳' : '✗') }}
                                                            </h4>
                                                            <small>Berkas ({{ $berkasVerified }}/5)</small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="border rounded p-3">
                                                            <h4
                                                                class="{{ $pendaftar && $pendaftar->tgl_verifikasi_payment ? 'text-success' : 'text-danger' }}">
                                                                {{ $pendaftar && $pendaftar->tgl_verifikasi_payment ? '✓' : '✗' }}
                                                            </h4>
                                                            <small>Pembayaran</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header bg-success text-white">
                                                <h6 class="mb-0">Informasi Penting</h6>
                                            </div>
                                            <div class="card-body">
                                                <p><strong>No.
                                                        Pendaftaran:</strong><br>{{ $pendaftar ? $pendaftar->no_pendaftaran : 'Belum ada' }}
                                                </p>
                                                <p><strong>Jurusan:</strong><br>{{ $pendaftar && $pendaftar->jurusan ? $pendaftar->jurusan->nama : 'Belum dipilih' }}
                                                </p>
                                                <p><strong>Gelombang:</strong><br>{{ $gelombang ? $gelombang->nama : 'Belum ada' }}
                                                </p>
                                                <p><strong>Tanggal
                                                        Daftar:</strong><br>{{ $pendaftar && $pendaftar->tanggal_daftar ? \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('d M Y') : 'Belum ada' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Detail Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Tahapan</th>
                                                        <th>Status</th>
                                                        <th>Tanggal</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Pendaftaran Akun</td>
                                                        <td><span class="badge bg-success">Selesai</span></td>
                                                        <td>{{ $user->created_at->format('d M Y') }}</td>
                                                        <td>Akun berhasil dibuat</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pengisian Data Diri</td>
                                                        @php
                                                            $hasDataSiswa = $pendaftar && $pendaftar->dataSiswa;
                                                        @endphp
                                                        <td><span
                                                                class="badge {{ $hasDataSiswa ? 'bg-success' : 'bg-warning' }}">{{ $hasDataSiswa ? 'Selesai' : 'Proses' }}</span>
                                                        </td>
                                                        <td>{{ $hasDataSiswa ? $pendaftar->dataSiswa->updated_at->format('d M Y') : '-' }}
                                                        </td>
                                                        <td>{{ $hasDataSiswa ? 'Formulir data diri lengkap' : 'Belum mengisi data diri' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Upload Berkas</td>
                                                        @php
                                                            $berkasVerified = $pendaftar ? $pendaftar->berkas->where('valid', 1)->count() : 0;
                                                            $totalBerkas = $pendaftar ? $pendaftar->berkas->count() : 0;
                                                        @endphp
                                                        <td><span
                                                                class="badge {{ $berkasVerified >= 3 ? 'bg-success' : ($totalBerkas > 0 ? 'bg-warning' : 'bg-danger') }}">
                                                                {{ $berkasVerified >= 3 ? 'Selesai' : ($totalBerkas > 0 ? 'Proses' : 'Menunggu') }}
                                                            </span></td>
                                                        <td>{{ $totalBerkas > 0 ? $pendaftar->berkas->first()->created_at->format('d M Y') : '-' }}
                                                        </td>
                                                        <td>{{ $berkasVerified }} dari 5 berkas terverifikasi</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pembayaran</td>
                                                        <td><span
                                                                class="badge {{ $pendaftar && $pendaftar->tgl_verifikasi_payment ? 'bg-success' : 'bg-danger' }}">
                                                                {{ $pendaftar && $pendaftar->tgl_verifikasi_payment ? 'Lunas' : 'Menunggu' }}
                                                            </span></td>
                                                        <td>{{ $pendaftar && $pendaftar->tgl_verifikasi_payment ? \Carbon\Carbon::parse($pendaftar->tgl_verifikasi_payment)->format('d M Y') : '-' }}
                                                        </td>
                                                        <td>{{ $pendaftar && $pendaftar->tgl_verifikasi_payment ? 'Pembayaran telah diverifikasi' : 'Belum melakukan pembayaran' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Verifikasi Admin</td>
                                                        <td><span
                                                                class="badge {{ $pendaftar && $pendaftar->status == 'Terverifikasi' ? 'bg-success' : ($pendaftar && $pendaftar->status == 'Ditolak' ? 'bg-danger' : 'bg-secondary') }}">
                                                                {{ $pendaftar ? $pendaftar->status : 'Menunggu' }}
                                                            </span></td>
                                                        <td>{{ $pendaftar && $pendaftar->tgl_verifikasi_adm ? \Carbon\Carbon::parse($pendaftar->tgl_verifikasi_adm)->format('d M Y') : '-' }}
                                                        </td>
                                                        <td>{{ $pendaftar && $pendaftar->status == 'Terverifikasi' ? 'Pendaftaran telah diverifikasi' : 'Menunggu kelengkapan berkas dan pembayaran' }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pengumuman Section -->
                    <div id="pengumuman" class="section">
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom">
                                    <div class="card-header bg-primary text-white">
                                        <h5 class="mb-0"><i class="fas fa-id-card me-2"></i>Cetak Kartu Pendaftaran</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    Kartu pendaftaran dapat dicetak setelah Anda melengkapi data diri
                                                    dan mengupload foto 3x4.
                                                </div>
                                                <div id="kartuStatus">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <span class="badge bg-secondary me-2" id="statusFoto">Belum
                                                            Upload Foto</span>
                                                        <span>Foto 3x4</span>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-3">
                                                        <span class="badge bg-secondary me-2" id="statusData">Belum
                                                            Lengkap</span>
                                                        <span>Data Diri</span>
                                                    </div>
                                                </div>
                                                <button class="btn btn-siswa" id="btnCetakKartu" onclick="cetakKartu()"
                                                    disabled>
                                                    <i class="fas fa-print me-2"></i>Cetak Kartu Pendaftaran
                                                </button>
                                            </div>
                                            <div class="col-md-4 text-center">
                                                <div class="border rounded p-3" style="background: #f8f9fa;">
                                                    <i class="fas fa-id-card fa-4x text-muted mb-3"></i>
                                                    <p class="text-muted">Preview kartu akan muncul di sini</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card card-custom">
                            <div class="card-header bg-white border-0">
                                <h5 class="mb-0"><i class="fas fa-bullhorn me-2 text-primary"></i>Pengumuman</h5>
                            </div>
                            <div class="card-body">
                                <div class="row mb-4">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header bg-warning text-dark">
                                                <h6 class="mb-0">Pengumuman Penting</h6>
                                            </div>
                                            <div class="card-body">
                                                @if($pengumuman->where('prioritas', '>', 0)->count() > 0)
                                                    @foreach($pengumuman->where('prioritas', '>', 0)->take(2) as $item)
                                                        <div
                                                            class="alert alert-{{ $item->prioritas >= 3 ? 'danger' : ($item->prioritas >= 2 ? 'warning' : 'info') }}">
                                                            <h5><i
                                                                    class="fas fa-{{ $item->prioritas >= 3 ? 'exclamation-triangle' : ($item->prioritas >= 2 ? 'info-circle' : 'bullhorn') }} me-2"></i>{{ $item->judul }}
                                                            </h5>
                                                            <p class="mb-2">{{ nl2br(e($item->isi)) }}</p>
                                                            <small class="text-muted">Diposting:
                                                                {{ $item->tanggal_posting->format('d M Y') }}</small>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="alert alert-info">
                                                        <h5><i class="fas fa-info-circle me-2"></i>Belum Ada Pengumuman</h5>
                                                        <p class="mb-0">Belum ada pengumuman penting saat ini.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">Kalender Akademik</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <small class="text-muted">Gelombang 1</small>
                                                    <p class="mb-1"><strong>1 Jan - 31 Mar 2024</strong></p>
                                                </div>
                                                <div class="mb-3">
                                                    <small class="text-muted">Tes Masuk</small>
                                                    <p class="mb-1"><strong>25 Feb 2024</strong></p>
                                                </div>
                                                <div class="mb-3">
                                                    <small class="text-muted">Pengumuman Hasil</small>
                                                    <p class="mb-1"><strong>15 Mar 2024</strong></p>
                                                </div>
                                                <div class="mb-3">
                                                    <small class="text-muted">Daftar Ulang</small>
                                                    <p class="mb-1"><strong>1 - 30 Apr 2024</strong></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Semua Pengumuman</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="list-group">
                                            @forelse($pengumuman->take(10) as $item)
                                                <div class="list-group-item list-group-item-action">
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h6 class="mb-1">{{ $item->judul }}</h6>
                                                        <small>{{ $item->tanggal_posting->format('d M Y') }}</small>
                                                    </div>
                                                    <p class="mb-1">{{ Str::limit(strip_tags($item->isi), 100) }}</p>
                                                    @if($item->prioritas > 0)
                                                        <span
                                                            class="badge bg-{{ $item->prioritas >= 3 ? 'danger' : ($item->prioritas >= 2 ? 'warning' : 'info') }} badge-sm">Penting</span>
                                                    @endif
                                                </div>
                                            @empty
                                                <div class="list-group-item">
                                                    <p class="mb-0 text-muted text-center">Belum ada pengumuman</p>
                                                </div>
                                            @endforelse
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

    <!-- Modal Edit Profil -->
    <div class="modal fade" id="editProfilModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfilForm">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="editNama" value="{{ $user->nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" value="{{ $user->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nomor HP</label>
                            <input type="text" class="form-control" id="editHp" value="{{ $user->hp }}" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-siswa" onclick="simpanProfil()">Simpan Perubahan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload Berkas -->
    <div class="modal fade" id="uploadModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalTitle">Upload Berkas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadForm">
                        <div class="mb-3">
                            <label class="form-label">Jenis Berkas</label>
                            <input type="text" class="form-control" id="jenisBerkas" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Upload File</label>
                            <input type="file" class="form-control" id="fileUpload" accept=".pdf,.jpg,.jpeg,.png">
                            <div class="form-text">Format: PDF, JPG, PNG (Maks. 2MB)</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="keteranganUpload" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-siswa" onclick="simpanUpload()">Upload</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Cetak Kartu -->
    <div class="modal fade" id="kartuModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-id-card me-2"></i>Kartu Pendaftaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="kartuContent" class="p-4"
                        style="background: white; border: 2px solid #1e3c72; border-radius: 10px;">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="text-center mb-3">
                                    <h4 class="fw-bold" style="color: #1e3c72;">SMK BAKTI NUSANTARA 666</h4>
                                    <p class="mb-1">Jl. Pendidikan No. 123, Bandung</p>
                                    <p class="mb-0">Telp: (022) 1234567 | Email: info@smkbn666.sch.id</p>
                                </div>
                                <hr style="border-color: #1e3c72;">
                                <h5 class="text-center mb-3" style="color: #1e3c72;">KARTU PENDAFTARAN PPDB</h5>
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>No. Pendaftaran:</strong><br><span id="kartuNoPendaftaran">-</span>
                                        </p>
                                        <p><strong>Nama Lengkap:</strong><br><span id="kartuNama">-</span></p>
                                        <p><strong>Tempat, Tgl Lahir:</strong><br><span id="kartuTtl">-</span></p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>Jenis Kelamin:</strong><br><span id="kartuJk">-</span></p>
                                        <p><strong>Asal Sekolah:</strong><br><span id="kartuAsalSekolah">-</span></p>
                                        <p><strong>Jurusan Pilihan:</strong><br><span id="kartuJurusan">-</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="border"
                                    style="width: 120px; height: 160px; margin: 0 auto; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                    <img id="kartuFoto" src="" alt="Foto 3x4"
                                        style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                    <span id="kartuFotoPlaceholder" class="text-muted">Foto 3x4</span>
                                </div>
                                <div class="mt-3">
                                    <p class="small mb-1">Tanda Tangan</p>
                                    <div style="border-bottom: 1px solid #000; width: 100px; margin: 0 auto;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="text-center">
                                    <p class="small mb-1">Kartu ini wajib dibawa saat tes masuk dan daftar ulang</p>
                                    <p class="small mb-0">Dicetak pada: <span id="kartuTanggalCetak"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-siswa" onclick="printKartu()">
                        <i class="fas fa-print me-1"></i>Print Kartu
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    <div class="modal fade" id="qrisModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-credit-card me-2"></i>Pembayaran Pendaftaran</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Pilih metode pembayaran dan upload bukti transfer
                            </div>
                            <div class="mb-3">
                                <h4 class="text-primary">Total Pembayaran</h4>
                                <h2 class="fw-bold" id="totalPembayaran">Rp {{ number_format($biayaPendaftaran, 0, ',', '.') }}</h2>
                            </div>
                            
                            <form id="formPembayaran">
                                <div class="mb-3">
                                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select class="form-select" id="metodePembayaran" required>
                                        <option value="">Pilih Metode</option>
                                        <option value="Transfer Bank">Transfer Bank</option>
                                        <option value="Tunai">Tunai</option>
                                        <option value="Virtual Account">Virtual Account</option>
                                        <option value="QRIS">QRIS</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Pembayaran <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="jumlahPembayaran" 
                                           value="{{ $biayaPendaftaran }}" readonly>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Upload Bukti Transfer <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control" id="buktiTransfer" 
                                           accept=".jpg,.jpeg,.png,.pdf" required>
                                    <div class="form-text">Format: JPG, PNG, PDF (Maks. 2MB)</div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0">Informasi Rekening</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3 text-center">
                                        <h5>BCA</h5>
                                        <p class="mb-1"><strong>1234567890</strong></p>
                                        <p class="mb-0">SMK Bakti Nusantara 666</p>
                                    </div>
                                    <hr>
                                    <div class="mb-3 text-center">
                                        <h5>Mandiri</h5>
                                        <p class="mb-1"><strong>0987654321</strong></p>
                                        <p class="mb-0">SMK Bakti Nusantara 666</p>
                                    </div>
                                    <hr>
                                    <div class="text-center">
                                        <h5>BNI</h5>
                                        <p class="mb-1"><strong>1122334455</strong></p>
                                        <p class="mb-0">SMK Bakti Nusantara 666</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning mt-3">
                                <small><i class="fas fa-exclamation-triangle me-1"></i>
                                Pastikan jumlah transfer sesuai dengan tagihan dan upload bukti transfer yang jelas</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" onclick="submitPembayaran()">
                        <i class="fas fa-paper-plane me-1"></i>Submit Pembayaran
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.querySelectorAll('.section').forEach(section => {
                section.classList.remove('active');
            });

            // Show target section
            document.getElementById(sectionId).classList.add('active');

            // Update sidebar active state
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.classList.remove('active');
            });

            // Add active class to clicked link
            event.target.classList.add('active');

            // Load data when switching to formulir section
            if (sectionId === 'formulir') {
                loadDataSiswa();
                loadDataOrtu();
                loadDataAsalSekolah();
            }

            // Update payment status when switching to pembayaran section
            if (sectionId === 'pembayaran') {
                checkPembayaranStatus();
            }

            // Update kartu status when switching to pengumuman section
            if (sectionId === 'pengumuman') {
                checkKartuStatus();
            }
        }

        function editProfil() {
            const modal = new bootstrap.Modal(document.getElementById('editProfilModal'));
            modal.show();
        }

        function simpanProfil() {
            const nama = document.getElementById('editNama').value;
            const email = document.getElementById('editEmail').value;
            const hp = document.getElementById('editHp').value;

            if (!nama || !email || !hp) {
                alert('Semua field harus diisi!');
                return;
            }

            fetch('/pendaftar/update-profil', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    nama: nama,
                    email: email,
                    hp: hp
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Profil berhasil diperbarui!');
                        location.reload();
                    } else {
                        alert('Gagal memperbarui profil: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memperbarui profil');
                });
        }

        let currentUploadType = '';

        function uploadDocument(type) {
            currentUploadType = type;
            const modalTitle = document.getElementById('uploadModalTitle');
            const jenisBerkas = document.getElementById('jenisBerkas');

            let title = '';
            let jenis = '';

            switch (type) {
                case 'ktp':
                    title = 'Upload Kartu Tanda Penduduk (KTP)';
                    jenis = 'ktp';
                    break;
                case 'ijazah':
                    title = 'Upload Ijazah/Surat Keterangan Lulus';
                    jenis = 'ijazah';
                    break;
                case 'rapor':
                    title = 'Upload Rapor Semester 1-5';
                    jenis = 'rapor';
                    break;
                case 'foto':
                    title = 'Upload Foto 3x4';
                    jenis = 'foto';
                    break;
                case 'sehat':
                    title = 'Upload Surat Keterangan Sehat';
                    jenis = 'sehat';
                    break;
            }

            modalTitle.textContent = title;
            jenisBerkas.value = jenis;

            // Reset form
            document.getElementById('fileUpload').value = '';
            document.getElementById('keteranganUpload').value = '';

            const modal = new bootstrap.Modal(document.getElementById('uploadModal'));
            modal.show();
        }

        function simpanUpload() {
            const fileInput = document.getElementById('fileUpload');
            const keterangan = document.getElementById('keteranganUpload').value;

            if (!fileInput.files[0]) {
                alert('Pilih file terlebih dahulu!');
                return;
            }

            const formData = new FormData();
            formData.append('jenis_berkas', currentUploadType);
            formData.append('file', fileInput.files[0]);
            formData.append('keterangan', keterangan);
            formData.append('_token', '{{ csrf_token() }}');

            fetch('/pendaftar/upload-berkas', {
                method: 'POST',
                body: formData
            })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        return response.text().then(text => {
                            console.error('Non-JSON response:', text);
                            throw new Error('Server mengembalikan HTML bukan JSON. Cek log server.');
                        });
                    }
                })
                .then(data => {
                    if (data.success) {
                        alert('Berkas berhasil diupload!');
                        bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
                        loadBerkasStatus();
                        loadStatusPendaftaran();
                    } else {
                        alert('Gagal mengupload berkas: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Upload error:', error);
                    alert('Terjadi kesalahan saat mengupload berkas: ' + error.message);
                });
        }

        function loadBerkasStatus() {
            fetch('/pendaftar/berkas-status')
                .then(response => response.json())
                .then(data => {
                    data.forEach(berkas => {
                        updateBerkasStatus(berkas.jenis.toLowerCase(), berkas.valid);
                    });

                    // Update berkas count di dashboard (hanya yang terverifikasi)
                    const berkasElement = document.getElementById('berkasCount');
                    if (berkasElement) {
                        const verifiedBerkas = data.filter(berkas => berkas.valid === 1).length;
                        console.log('Updating berkas count:', verifiedBerkas);
                        berkasElement.textContent = verifiedBerkas + '/5';
                    }
                })
                .catch(error => {
                    console.error('Error loading berkas status:', error);
                });
        }

        function updateBerkasStatus(jenis, valid) {
            const documentItems = document.querySelectorAll('.document-item');
            documentItems.forEach(item => {
                const button = item.querySelector('button[onclick*="' + jenis + '"]');
                if (button) {
                    const badge = item.querySelector('.badge');
                    const actionButton = item.querySelector('.col-md-2:last-child button');

                    if (valid === 1) {
                        badge.className = 'badge bg-success';
                        badge.textContent = 'Terverifikasi';
                        actionButton.className = 'btn btn-success btn-sm';
                        actionButton.innerHTML = '<i class="fas fa-check me-1"></i>Selesai';
                        actionButton.onclick = null;
                    } else if (valid === 0) {
                        badge.className = 'badge bg-warning';
                        badge.textContent = 'Menunggu';
                        actionButton.className = 'btn btn-warning btn-sm';
                        actionButton.innerHTML = '<i class="fas fa-clock me-1"></i>Diproses';
                        actionButton.onclick = null;
                    }
                }
            });

            // Update berkas count in dashboard
            loadStatusPendaftaran();
            checkKartuStatus();
        }

        document.addEventListener('DOMContentLoaded', function () {
            console.log('Dashboard loaded, initializing...');

            // Load all data immediately
            loadBerkasStatus();
            loadDataSiswa();
            loadDataOrtu();
            loadDataAsalSekolah();

            // Load status with delay to ensure DOM is ready
            setTimeout(function () {
                loadStatusPendaftaran();
                checkPembayaranStatus();
                checkKartuStatus();
            }, 500);

            // Additional load after 2 seconds to ensure all data is loaded
            setTimeout(function () {
                loadStatusPendaftaran();
                updateDashboardStats();
                checkPembayaranStatus();
                checkKartuStatus();
            }, 2000);

            // Refresh status every 30 seconds
            setInterval(function () {
                loadStatusPendaftaran();
                loadBerkasStatus();
                updateDashboardStats();
                checkPembayaranStatus();
                checkKartuStatus();
            }, 30000);
        });

        // Fungsi untuk mengecek status pembayaran dan update UI
        function checkPembayaranStatus() {
            fetch('/pendaftar/cek-status-pembayaran')
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        const data = result.data;

                        console.log('Payment status check:', data);

                        // Cek apakah pembayaran sudah lunas
                        if (data.is_lunas) {
                            updatePembayaranStatus('Dikonfirmasi', true);
                        } else if (data.pembayaran_status === 'Menunggu Konfirmasi') {
                            updatePembayaranStatus('Menunggu Konfirmasi');
                        } else if (data.pembayaran_status === 'Ditolak') {
                            updatePembayaranStatus('Ditolak');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                });
        }

        // Fungsi untuk memuat data siswa yang sudah tersimpan
        function loadDataSiswa() {
            console.log('Loading student data...');

            fetch('/pendaftar/data-siswa')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('Student data received:', result);

                    if (result.success && result.data) {
                        const data = result.data;

                        // Safely set form values
                        const setFieldValue = (id, value) => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.value = value || '';
                            }
                        };

                        setFieldValue('nik', data.nik);
                        setFieldValue('nisn', data.nish);
                        setFieldValue('namaLengkap', data.nama);
                        setFieldValue('jenisKelamin', data.jk);
                        setFieldValue('tempatLahir', data.tmp_lahir);
                        setFieldValue('tanggalLahir', data.tgl_lahir);
                        setFieldValue('agama', data.agama);
                        setFieldValue('wilayahId', data.wilayah_id);
                        setFieldValue('alamat', data.alamat);
                        setFieldValue('nomorHP', data.nomor_hp);
                        setFieldValue('email', data.email);

                        // Load jurusan dari data pendaftar
                        if (data.jurusan_id) {
                            setFieldValue('jurusanPilihan', data.jurusan_id);
                        }

                        console.log('Student data loaded successfully');
                    } else {
                        console.log('No student data found');
                    }
                })
                .catch(error => {
                    console.error('Error loading student data:', error);
                });
        }

        // Fungsi untuk memuat data orang tua yang sudah tersimpan
        function loadDataOrtu() {
            console.log('Loading parent data...');

            fetch('/pendaftar/data-ortu')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('Parent data received:', result);

                    if (result.success && result.data) {
                        const data = result.data;

                        // Safely set form values
                        const setFieldValue = (id, value) => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.value = value || '';
                            }
                        };

                        setFieldValue('namaAyah', data.nama_ayah);
                        setFieldValue('pekerjaanAyah', data.pekerjaan_ayah);
                        setFieldValue('penghasilanAyah', data.penghasilan_ayah);
                        setFieldValue('hpAyah', data.hp_ayah);
                        setFieldValue('namaIbu', data.nama_ibu);
                        setFieldValue('pekerjaanIbu', data.pekerjaan_ibu);
                        setFieldValue('penghasilanIbu', data.penghasilan_ibu);
                        setFieldValue('hpIbu', data.hp_ibu);

                        console.log('Parent data loaded successfully');
                    } else {
                        console.log('No parent data found');
                    }
                })
                .catch(error => {
                    console.error('Error loading parent data:', error);
                });
        }

        // Fungsi untuk memuat data asal sekolah yang sudah tersimpan
        function loadDataAsalSekolah() {
            console.log('Loading school data...');

            fetch('/pendaftar/data-asal-sekolah')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('School data received:', result);

                    if (result.success && result.data) {
                        const data = result.data;

                        // Safely set form values
                        const setFieldValue = (id, value) => {
                            const element = document.getElementById(id);
                            if (element) {
                                element.value = value || '';
                            }
                        };

                        setFieldValue('namaSekolah', data.nama_sekolah);
                        setFieldValue('npsnSekolah', data.npsn);
                        setFieldValue('statusSekolah', data.status_sekolah);
                        setFieldValue('alamatSekolah', data.alamat_sekolah);
                        setFieldValue('tahunLulus', data.tahun_lulus);
                        setFieldValue('kabupatenSekolah', data.kabupaten);
                        setFieldValue('nilaiRata', data.nilai_rata);

                        console.log('School data loaded successfully');
                    } else {
                        console.log('No school data found');
                    }
                })
                .catch(error => {
                    console.error('Error loading school data:', error);
                });
        }

        function showPembayaranModal() {
            // Cek status pembayaran terlebih dahulu
            fetch('/pendaftar/cek-status-pembayaran')
                .then(response => response.json())
                .then(result => {
                    if (result.success && result.data) {
                        const data = result.data;

                        // Jika sudah lunas, jangan tampilkan modal
                        if (data.is_lunas) {
                            alert('Pembayaran Anda sudah lunas!');
                            updatePembayaranStatus('Dikonfirmasi', true);
                            return;
                        }

                        // Jika masih menunggu konfirmasi, jangan bisa bayar lagi
                        if (data.pembayaran_status === 'Menunggu Konfirmasi') {
                            alert('Pembayaran Anda sedang diverifikasi. Mohon tunggu konfirmasi dari bagian keuangan.');
                            updatePembayaranStatus('Menunggu Konfirmasi');
                            return;
                        }
                    }

                    // Reset form untuk pembayaran baru/ulang
                    document.getElementById('metodePembayaran').value = '';
                    document.getElementById('buktiTransfer').value = '';

                    // Tampilkan modal untuk pembayaran baru atau bayar ulang
                    const modal = new bootstrap.Modal(document.getElementById('qrisModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error checking payment status:', error);
                    // Tetap tampilkan modal jika ada error
                    const modal = new bootstrap.Modal(document.getElementById('qrisModal'));
                    modal.show();
                });
        }

        function submitPembayaran() {
            const metode = document.getElementById('metodePembayaran').value;
            const jumlah = document.getElementById('jumlahPembayaran').value;
            const buktiFile = document.getElementById('buktiTransfer').files[0];

            // Validasi form
            if (!metode) {
                alert('Pilih metode pembayaran terlebih dahulu!');
                return;
            }

            if (!buktiFile) {
                alert('Upload bukti transfer terlebih dahulu!');
                return;
            }

            // Validasi ukuran file (2MB)
            if (buktiFile.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB!');
                return;
            }

            // Buat FormData untuk upload file
            const formData = new FormData();
            formData.append('jumlah', jumlah);
            formData.append('metode', metode);
            formData.append('bukti_transfer', buktiFile);
            formData.append('_token', '{{ csrf_token() }}');

            // Submit pembayaran ke server
            fetch('/pendaftar/submit-pembayaran', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI untuk menunjukkan status menunggu konfirmasi
                        updatePembayaranStatus('Menunggu Konfirmasi');
                        alert('Pembayaran berhasil disubmit! Menunggu verifikasi dari bagian keuangan.');
                        bootstrap.Modal.getInstance(document.getElementById('qrisModal')).hide();
                        loadStatusPendaftaran();
                    } else {
                        alert('Gagal submit pembayaran: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat submit pembayaran');
                });
        }
    

        function updatePembayaranStatus(status, isLunas = false) {
            const statusCard = document.getElementById('statusPembayaranCard');
            const keterangan = document.getElementById('keteranganPembayaran');
            const buttonArea = document.getElementById('buttonPembayaran');

            if (isLunas || status === 'Dikonfirmasi') {
                statusCard.innerHTML = '<span class="badge bg-success fs-6">Pembayaran Lunas</span>';
                keterangan.innerHTML = 'Pembayaran Anda telah diverifikasi dan diterima';
                buttonArea.innerHTML = '<button class="btn btn-success" disabled><i class="fas fa-check me-2"></i>Pembayaran Selesai</button>';
            } else if (status === 'Menunggu Konfirmasi') {
                statusCard.innerHTML = '<span class="badge bg-warning fs-6">Menunggu Verifikasi Keuangan</span>';
                keterangan.innerHTML = 'Pembayaran Anda sedang diverifikasi oleh bagian keuangan';
                buttonArea.innerHTML = '<button class="btn btn-secondary" disabled><i class="fas fa-clock me-2"></i>Menunggu Verifikasi</button>';
            } else if (status === 'Ditolak') {
                statusCard.innerHTML = '<span class="badge bg-danger fs-6">Pembayaran Ditolak</span>';
                keterangan.innerHTML = 'Pembayaran Anda ditolak. Silakan lakukan pembayaran ulang dengan bukti transfer yang benar';
                buttonArea.innerHTML = '<button class="btn btn-warning" onclick="showPembayaranModal()"><i class="fas fa-redo me-2"></i>Bayar Ulang</button>';
            }
        }

        // Fungsi untuk menyimpan data siswa
        function simpanDataSiswa() {
            const formData = {
                nik: document.getElementById('nik').value,
                nisn: document.getElementById('nisn').value,
                nama_lengkap: document.getElementById('namaLengkap').value,
                jenis_kelamin: document.getElementById('jenisKelamin').value,
                tempat_lahir: document.getElementById('tempatLahir').value,
                tanggal_lahir: document.getElementById('tanggalLahir').value,
                agama: document.getElementById('agama').value,
                wilayah_id: document.getElementById('wilayahId').value,
                alamat: document.getElementById('alamat').value,
                nomor_hp: document.getElementById('nomorHP').value,
                email: document.getElementById('email').value,
                jurusan_id: document.getElementById('jurusanPilihan').value
            };

            // Validasi data wajib
            if (!formData.nik || !formData.nisn || !formData.nama_lengkap || !formData.jenis_kelamin ||
                !formData.tempat_lahir || !formData.tanggal_lahir || !formData.agama ||
                !formData.wilayah_id || !formData.alamat || !formData.nomor_hp || !formData.jurusan_id) {
                alert('Harap lengkapi semua field yang wajib diisi, termasuk jurusan pilihan!');
                return;
            }

            // Validasi NIK (16 digit)
            if (formData.nik.length !== 16 || !/^\d+$/.test(formData.nik)) {
                alert('NIK harus berupa 16 digit angka!');
                return;
            }

            console.log('Saving student data:', formData);

            // Kirim data ke server
            fetch('/pendaftar/simpan-data-siswa', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Student data save response:', data);
                    if (data.success) {
                        alert(data.message);
                        // Reload status after saving
                        setTimeout(function () {
                            loadStatusPendaftaran();
                            updateDashboardStats();
                        }, 1000);
                    } else {
                        alert('Gagal menyimpan data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data');
                });
        }

        // Fungsi untuk menyimpan data orang tua
        function simpanDataOrtu() {
            const formData = {
                nama_ayah: document.getElementById('namaAyah').value,
                pekerjaan_ayah: document.getElementById('pekerjaanAyah').value,
                penghasilan_ayah: document.getElementById('penghasilanAyah').value,
                hp_ayah: document.getElementById('hpAyah').value,
                nama_ibu: document.getElementById('namaIbu').value,
                pekerjaan_ibu: document.getElementById('pekerjaanIbu').value,
                penghasilan_ibu: document.getElementById('penghasilanIbu').value,
                hp_ibu: document.getElementById('hpIbu').value
            };

            // Validasi data wajib
            if (!formData.nama_ayah || !formData.nama_ibu) {
                alert('Nama ayah dan ibu wajib diisi!');
                return;
            }

            console.log('Saving parent data:', formData);

            // Kirim data ke server
            fetch('/pendaftar/simpan-data-ortu', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Parent data save response:', data);
                    if (data.success) {
                        alert(data.message);
                        // Reload status after saving
                        setTimeout(function () {
                            loadStatusPendaftaran();
                            updateDashboardStats();
                        }, 1000);
                    } else {
                        alert('Gagal menyimpan data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data');
                });
        }

        // Fungsi untuk memuat status pendaftaran real-time
        function loadStatusPendaftaran() {
            console.log('Loading status pendaftaran...');

            fetch('/pendaftar/status-pendaftaran')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(result => {
                    console.log('Status data received:', result);

                    if (result.success && result.data) {
                        const data = result.data;

                        // Update status pendaftaran
                        const statusElement = document.getElementById('statusPendaftaran');
                        if (statusElement) {
                            statusElement.textContent = data.status || 'Menunggu Verifikasi';
                            console.log('Status updated:', data.status);
                        }

                        // Update status pembayaran
                        const pembayaranElement = document.getElementById('statusPembayaran');
                        if (pembayaranElement) {
                            if (data.pembayaran_status === 'Dikonfirmasi' || data.tgl_verifikasi_payment) {
                                pembayaranElement.textContent = 'Lunas';
                            } else if (data.pembayaran_status === 'Menunggu Konfirmasi') {
                                pembayaranElement.textContent = 'Menunggu Verifikasi';
                            } else {
                                pembayaranElement.textContent = 'Menunggu';
                            }
                        }

                        // Update berkas count (hanya yang terverifikasi)
                        const berkasElement = document.getElementById('berkasCount');
                        if (berkasElement) {
                            const berkasVerified = (data.berkas_verified || 0);
                            berkasElement.textContent = berkasVerified + '/5';
                            console.log('Berkas count updated:', berkasVerified);
                        }

                        // Update data formulir count
                        const dataFormulirElement = document.getElementById('dataFormulir');
                        if (dataFormulirElement) {
                            let verifiedCount = 0;
                            let totalCount = 0;

                            // Hitung data yang sudah diisi
                            if (data.data_siswa_verified !== null) {
                                totalCount++;
                                if (data.data_siswa_verified === 1) verifiedCount++;
                            }
                            if (data.data_ortu_verified !== null) {
                                totalCount++;
                                if (data.data_ortu_verified === 1) verifiedCount++;
                            }
                            if (data.asal_sekolah_verified !== null) {
                                totalCount++;
                                if (data.asal_sekolah_verified === 1) verifiedCount++;
                            }

                            dataFormulirElement.textContent = totalCount + '/3';
                            console.log('Data formulir updated:', totalCount + '/3');
                        }

                        // Update status badges
                        updateStatusBadge('statusDataSiswa', data.data_siswa_verified);
                        updateStatusBadge('statusDataOrtu', data.data_ortu_verified);
                        updateStatusBadge('statusAsalSekolah', data.asal_sekolah_verified);

                        // Update status pembayaran di section pembayaran
                        if (data.tgl_verifikasi_payment || data.pembayaran_status === 'Dikonfirmasi') {
                            updatePembayaranStatus('Dikonfirmasi', true);
                        } else if (data.pembayaran_status) {
                            updatePembayaranStatus(data.pembayaran_status);
                        }
                    } else {
                        console.log('No data received or unsuccessful response');
                    }
                })
                .catch(error => {
                    console.error('Error loading status:', error);
                });
        }

        function updateStatusBadge(elementId, status) {
            const element = document.getElementById(elementId);
            if (!element) {
                console.log('Element not found:', elementId);
                return;
            }

            console.log('Updating badge', elementId, 'with status:', status);

            if (status === null || status === undefined) {
                element.className = 'badge bg-secondary me-2';
                element.textContent = 'Belum diisi';
            } else if (status === 0) {
                element.className = 'badge bg-warning me-2';
                element.textContent = 'Menunggu verifikasi';
            } else if (status === 1) {
                element.className = 'badge bg-success me-2';
                element.textContent = 'Terverifikasi';
            } else if (status === 2) {
                element.className = 'badge bg-danger me-2';
                element.textContent = 'Ditolak';
            }
        }

        // Fungsi untuk update statistik dashboard
        function updateDashboardStats() {
            console.log('Updating dashboard stats...');

            // Force update semua elemen statistik
            loadStatusPendaftaran();

            // Update berkas status
            loadBerkasStatus();
        }

        // Fungsi untuk cek status kartu pendaftaran
        function checkKartuStatus() {
            Promise.all([
                fetch('/pendaftar/berkas-status'),
                fetch('/pendaftar/data-siswa')
            ])
                .then(responses => Promise.all(responses.map(r => r.json())))
                .then(([berkasResult, dataSiswaResult]) => {
                    let hasFoto = false;
                    let hasDataSiswa = false;

                    // Cek apakah ada foto 3x4 yang terverifikasi
                    if (berkasResult && Array.isArray(berkasResult)) {
                        hasFoto = berkasResult.some(berkas =>
                            berkas.jenis && berkas.jenis.toLowerCase() === 'foto' && berkas.valid === 1
                        );
                    }

                    // Cek apakah data siswa sudah lengkap
                    if (dataSiswaResult && dataSiswaResult.success && dataSiswaResult.data) {
                        const data = dataSiswaResult.data;
                        hasDataSiswa = data.nama && data.tmp_lahir && data.tgl_lahir && data.jk;
                    }

                    // Update status badges
                    const statusFoto = document.getElementById('statusFoto');
                    const statusData = document.getElementById('statusData');
                    const btnCetak = document.getElementById('btnCetakKartu');

                    if (statusFoto) {
                        if (hasFoto) {
                            statusFoto.className = 'badge bg-success me-2';
                            statusFoto.textContent = 'Foto Tersedia';
                        } else {
                            statusFoto.className = 'badge bg-danger me-2';
                            statusFoto.textContent = 'Belum Upload Foto';
                        }
                    }

                    if (statusData) {
                        if (hasDataSiswa) {
                            statusData.className = 'badge bg-success me-2';
                            statusData.textContent = 'Data Lengkap';
                        } else {
                            statusData.className = 'badge bg-danger me-2';
                            statusData.textContent = 'Data Belum Lengkap';
                        }
                    }

                    // Enable/disable tombol cetak
                    if (btnCetak) {
                        if (hasFoto && hasDataSiswa) {
                            btnCetak.disabled = false;
                            btnCetak.className = 'btn btn-siswa';
                        } else {
                            btnCetak.disabled = true;
                            btnCetak.className = 'btn btn-secondary';
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking kartu status:', error);
                });
        }

        // Fungsi untuk cetak kartu
        function cetakKartu() {
            // Load data untuk kartu
            Promise.all([
                fetch('/pendaftar/data-siswa'),
                fetch('/pendaftar/berkas-status'),
                fetch('/pendaftar/status-pendaftaran')
            ])
                .then(responses => Promise.all(responses.map(r => r.json())))
                .then(([dataSiswaResult, berkasResult, statusResult]) => {
                    let fotoUrl = null;

                    // Cari foto 3x4
                    if (berkasResult && Array.isArray(berkasResult)) {
                        const fotoData = berkasResult.find(berkas =>
                            berkas.jenis && berkas.jenis.toLowerCase() === 'foto' && berkas.valid === 1
                        );
                        if (fotoData && fotoData.nama_file) {
                            fotoUrl = '/berkas/' + fotoData.nama_file;
                        }
                    }

                    // Populate kartu data
                    if (dataSiswaResult.success && dataSiswaResult.data) {
                        const data = dataSiswaResult.data;
                        const statusData = statusResult.data || {};

                        document.getElementById('kartuNoPendaftaran').textContent = statusData.no_pendaftaran || 'PPDB2025001';
                        document.getElementById('kartuNama').textContent = data.nama || '{{ $user->nama }}';
                        document.getElementById('kartuTtl').textContent = (data.tmp_lahir || '') + ', ' + (data.tgl_lahir || '');
                        document.getElementById('kartuJk').textContent = data.jk === 'L' ? 'Laki-laki' : (data.jk === 'P' ? 'Perempuan' : '-');
                        document.getElementById('kartuAsalSekolah').textContent = '{{ $user->asal_sekolah }}' || '-';

                        // Ambil nama jurusan dari status data
                        let namaJurusan = 'Belum Dipilih';
                        if (statusData.jurusan_nama) {
                            namaJurusan = statusData.jurusan_nama;
                        }
                        document.getElementById('kartuJurusan').textContent = namaJurusan;
                        document.getElementById('kartuTanggalCetak').textContent = new Date().toLocaleDateString('id-ID');

                        // Set foto
                        const kartuFoto = document.getElementById('kartuFoto');
                        const kartuFotoPlaceholder = document.getElementById('kartuFotoPlaceholder');

                        if (fotoUrl) {
                            kartuFoto.src = fotoUrl;
                            kartuFoto.style.display = 'block';
                            kartuFotoPlaceholder.style.display = 'none';
                        } else {
                            kartuFoto.style.display = 'none';
                            kartuFotoPlaceholder.style.display = 'block';
                        }

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('kartuModal'));
                        modal.show();
                    } else {
                        alert('Data siswa belum lengkap. Silakan lengkapi data diri terlebih dahulu.');
                    }
                })
                .catch(error => {
                    console.error('Error loading kartu data:', error);
                    alert('Gagal memuat data kartu. Silakan coba lagi.');
                });
        }

        // Fungsi untuk print kartu
        function printKartu() {
            const printContent = document.getElementById('kartuContent').innerHTML;
            const originalContent = document.body.innerHTML;

            document.body.innerHTML = `
                <html>
                <head>
                    <title>Kartu Pendaftaran</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .row { display: flex; }
                        .col-md-8 { flex: 0 0 66.666667%; }
                        .col-md-4 { flex: 0 0 33.333333%; }
                        .col-6 { flex: 0 0 50%; }
                        .text-center { text-align: center; }
                        .fw-bold { font-weight: bold; }
                        .mb-1 { margin-bottom: 0.25rem; }
                        .mb-3 { margin-bottom: 1rem; }
                        .mt-3 { margin-top: 1rem; }
                        .border { border: 1px solid #000; }
                        .small { font-size: 0.875em; }
                        hr { border: 1px solid #1e3c72; }
                        p { margin: 0.5rem 0; }
                    </style>
                </head>
                <body>
                    <div style="background: white; border: 2px solid #1e3c72; border-radius: 10px; padding: 20px;">
                        ${printContent}
                    </div>
                </body>
                </html>
            `;

            window.print();
            document.body.innerHTML = originalContent;
            location.reload();
        }

        // Fungsi untuk menyimpan data asal sekolah
        function simpanAsalSekolah() {
            const formData = {
                nama_sekolah: document.getElementById('namaSekolah').value,
                npsn_sekolah: document.getElementById('npsnSekolah').value,
                status_sekolah: document.getElementById('statusSekolah').value,
                alamat_sekolah: document.getElementById('alamatSekolah').value,
                tahun_lulus: parseInt(document.getElementById('tahunLulus').value),
                kabupaten: document.getElementById('kabupatenSekolah').value,
                nilai_rata: parseFloat(document.getElementById('nilaiRata').value)
            };

            // Validasi data wajib
            if (!formData.nama_sekolah || !formData.alamat_sekolah || !formData.tahun_lulus || !formData.kabupaten || !formData.nilai_rata) {
                alert('Nama sekolah, alamat sekolah, tahun lulus, kabupaten, dan nilai rata-rata wajib diisi!');
                return;
            }

            console.log('Saving school data:', formData);

            // Kirim data ke server
            fetch('/pendaftar/simpan-asal-sekolah', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    console.log('School data save response:', data);
                    if (data.success) {
                        alert(data.message);
                        // Reload status after saving
                        setTimeout(function () {
                            loadStatusPendaftaran();
                            updateDashboardStats();
                        }, 1000);
                    } else {
                        alert('Gagal menyimpan data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan data');
                });
        }
    </script>
</body>

</html>