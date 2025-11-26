<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB Online - SMK Bakti Nusantara 666</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1e3c72;
            --secondary: #2a5298;
            --accent: #667eea;
            --gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --gradient-hover: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 20px 50px rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            overflow-x: hidden;
            padding-top: 80px;
            /* Untuk navbar fixed */
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: rgba(30, 60, 114, 0.95);
            backdrop-filter: blur(20px);
            padding: 1rem 0;
            transition: all 0.3s ease;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-scrolled {
            background: rgba(30, 60, 114, 0.98);
            padding: 0.7rem 0;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            margin: 0 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
        }

        .btn-nav-login {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-nav-login:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-2px);
        }

        /* ===== LOGO NAVBAR ===== */
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .navbar-logo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            margin-right: 12px;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }

        .navbar-brand-text {
            line-height: 1.2;
        }

        /* ===== HERO SECTION ===== */
        .hero-section {
            background: var(--gradient);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,1000 1000,0 1000,1000"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            left: 80%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 80%;
            left: 20%;
            animation-delay: 4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        /* ===== SEJARAH SECTION ===== */
        .sejarah-section {
            background: #f8f9fa;
        }

        .timeline {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
        }

        .timeline::after {
            content: '';
            position: absolute;
            width: 6px;
            background: var(--primary);
            top: 0;
            bottom: 0;
            left: 50%;
            margin-left: -3px;
        }

        .timeline-item {
            padding: 10px 40px;
            position: relative;
            width: 50%;
            box-sizing: border-box;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: white;
            border: 4px solid var(--primary);
            border-radius: 50%;
            top: 15px;
            z-index: 1;
        }

        .left {
            left: 0;
        }

        .right {
            left: 50%;
        }

        .left::after {
            right: -10px;
        }

        .right::after {
            left: -10px;
        }

        .timeline-content {
            padding: 20px 30px;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
        }

        /* ===== GLASS MORPHISM CARDS ===== */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.6s;
        }

        .glass-card:hover::before {
            left: 100%;
        }

        .glass-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-hover);
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* ===== GELOMBANG SECTION ===== */
        .gelombang-timeline {
            position: relative;
            padding: 2rem 0;
        }

        .timeline-item-gelombang {
            position: relative;
            padding: 2rem;
            margin: 2rem 0;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            border-left: 5px solid var(--primary);
        }

        .timeline-item-gelombang:hover {
            transform: translateX(10px);
            box-shadow: var(--shadow-hover);
        }

        .timeline-item-gelombang.active {
            border-left-color: #28a745;
            background: linear-gradient(135deg, #fff 0%, #f8fff9 100%);
        }

        .timeline-item-gelombang.soon {
            border-left-color: #ffc107;
            background: linear-gradient(135deg, #fff 0%, #fffbf0 100%);
        }

        .status-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        /* ===== PRESTASI SHOWCASE ===== */
        .prestasi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .prestasi-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .prestasi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: var(--gradient);
            transition: width 0.3s ease;
        }

        .prestasi-card:hover::before {
            width: 10px;
        }

        .prestasi-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-hover);
        }

        .prestasi-icon {
            width: 60px;
            height: 60px;
            background: var(--gradient);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            color: white;
            font-size: 1.5rem;
        }

        /* ===== JURUSAN SHOWCASE ===== */
        .jurusan-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: var(--shadow);
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        .jurusan-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--gradient);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .jurusan-card:hover::after {
            transform: scaleX(1);
        }

        .jurusan-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--shadow-hover);
        }

        .jurusan-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            background: var(--gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
            transition: all 0.3s ease;
        }

        .jurusan-card:hover .jurusan-icon {
            transform: scale(1.1) rotate(5deg);
        }

        /* ===== ANIMATED COUNTERS ===== */
        .counter-item {
            text-align: center;
            padding: 2rem;
        }

        .counter-number {
            font-size: 3rem;
            font-weight: 700;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
        }



        /* ===== ALUR PENDAFTARAN ===== */
        .alur-pendaftaran {
            background: var(--gradient);
            color: white;
        }

        .step-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .step-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: white;
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 1rem;
        }

        /* ===== SECTION SPACING ===== */
        .section {
            padding: 5rem 0;
        }

        .section-alt {
            background: #f8f9fa;
        }

        /* ===== TYPOGRAPHY ===== */
        .display-3 {
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .lead-lg {
            font-size: 1.3rem;
            font-weight: 300;
        }

        /* Counter Number Styling */
        .counter-number {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 0.5rem;
            display: block;
        }

        .counter-item small {
            font-size: 0.9rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8) !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .timeline::after {
                left: 31px;
            }

            .timeline-item {
                width: 100%;
                padding-left: 70px;
                padding-right: 25px;
            }

            .timeline-item::after {
                left: 21px;
            }

            .right {
                left: 0;
            }

            body {
                padding-top: 70px;
            }
        }
    </style>
</head>

<body>
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <img src="{{ asset('img/logo-bn.jpg') }}" alt="Logo SMK" class="navbar-logo">
                <span class="navbar-brand-text">SMK BAKTI NUSANTARA 666</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#sejarah">Sejarah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gelombang">Gelombang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#prestasi">Prestasi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#jurusan">Jurusan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#alur">Alur Pendaftaran</a>
                    </li>
                    <li class="nav-item ms-2">
                        <button class="btn btn-nav-login" onclick="window.location.href='/login'">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="hero-content">
                        <h1 class="display-3 fw-bold mb-4">
                            Portal Penerimaan
                            Peserta Didik Baru <span class="text-warning">(PPDB)</span>
                        </h1>
                        <p class="lead-lg mb-4">
                            "Menciptakan Generasi Unggul Berkarakter, Siap Menghadapi Era Digital"
                        </p>
                        <p class="mb-5">
                            Bergabunglah dengan keluarga besar SMK Bakti Nusantara 666 melalui sistem
                            PPDB Online yang modern, cepat, dan terpercaya.
                        </p>

                        <!-- Quick Stats -->
                        <div class="row mt-5 pt-4">
                            <div class="col-4 text-center">
                                <div class="counter-item">
                                    <div class="counter-number" data-count="{{ $stats['total_jurusan'] }}">{{ $stats['total_jurusan'] }}</div>
                                    <small class="text-white-50">Program Jurusan</small>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="counter-item">
                                    <div class="counter-number" data-count="{{ $stats['total_pendaftar'] }}">{{ $stats['total_pendaftar'] }}</div>
                                    <small class="text-white-50">Siswa Terdaftar</small>
                                </div>
                            </div>
                            <div class="col-4 text-center">
                                <div class="counter-item">
                                    <div class="counter-number" data-count="{{ $stats['total_gelombang'] }}">{{ $stats['total_gelombang'] }}</div>
                                    <small class="text-white-50">Gelombang</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
                    <div class="glass-card text-center">
                        <div class="hero-graphic mb-4">
                            <i class="fas fa-graduation-cap" style="font-size: 8rem; opacity: 0.8;"></i>
                        </div>
                        <h4 class="mb-3">PPDB Online 2025/2026</h4>
                        <p class="mb-4">Proses pendaftaran digital yang mudah, cepat, dan transparan</p>

                        <div class="row text-start">
                            <div class="col-6 mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small class="text-white-50">Daftar Online</small>
                            </div>
                            <div class="col-6 mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small class="text-white-50">Upload Digital</small>
                            </div>
                            <div class="col-6 mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small class="text-white-50">Bayar Mudah</small>
                            </div>
                            <div class="col-6 mb-3">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <small class="text-white-50">Pantau Real-time</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sejarah Section -->
    <section id="sejarah" class="section sejarah-section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-3">Sejarah Singkat</h2>
                    <p class="lead text-muted">Perjalanan SMK Bakti Nusantara 666 dalam Mencerdaskan Bangsa</p>
                </div>
            </div>

            <div class="timeline">
                <!-- Tahun 2005 -->
                <div class="timeline-item left" data-aos="fade-right">
                    <div class="timeline-content">
                        <h4 class="text-primary">2007 - Pendirian</h4>
                        <p>SMK Bakti Nusantara 666 didirikan pada tahun 2007 sebagai Sekolah Menengah Kejuruan berbasis
                            Industri kreatif di Kawasan Bandung Timur. Sekolah ini bertujuan untuk menghasilkan lulusan
                            yang terampil dan siap kerja di bidang-bidang kreatif sesuai dengan kebutuhan industri. </p>
                    </div>
                </div>

                <!-- Tahun 2010 -->
                <div class="timeline-item right" data-aos="fade-left">
                    <div class="timeline-content">
                        <h4 class="text-primary">2010 - Pengembangan</h4>
                        <p>Penambahan program keahlian baru dan pembangunan gedung praktik yang modern. Sekolah mulai
                            menerapkan sistem teaching factory untuk memberikan pengalaman industri langsung kepada
                            siswa.</p>
                    </div>
                </div>

                <!-- Tahun 2015 -->
                <div class="timeline-item left" data-aos="fade-right">
                    <div class="timeline-content">
                        <h4 class="text-primary">2015 - Akreditasi A</h4>
                        <p>Meraih akreditasi A dari Badan Akreditasi Nasional untuk semua program keahlian. Prestasi
                            siswa mulai mencuat di tingkat regional dan nasional dalam berbagai kompetensi.</p>
                    </div>
                </div>

                <!-- Tahun 2020 -->
                <div class="timeline-item right" data-aos="fade-left">
                    <div class="timeline-content">
                        <h4 class="text-primary">2020 - Transformasi Digital</h4>
                        <p>Implementasi sistem pembelajaran digital dan penguatan link and match dengan industri.
                            Kerjasama dengan lebih dari 50 perusahaan mitra untuk program magang dan penyerapan lulusan.
                        </p>
                    </div>
                </div>

                <!-- Tahun 2024 -->
                <div class="timeline-item left" data-aos="fade-right">
                    <div class="timeline-content">
                        <h4 class="text-primary">2025 - Sekarang</h4>
                        <p>Menjadi SMK rujukan dengan 5 program keahlian unggulan, fasilitas lengkap, dan sistem PPDB
                            online yang modern. Terus berkomitmen menghasilkan lulusan yang kompeten dan berkarakter.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gelombang Pendaftaran Section -->
    <section id="gelombang" class="section section-alt">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-3">Gelombang Pendaftaran</h2>
                    <p class="lead text-muted">Pilih waktu yang tepat untuk bergabung dengan kami</p>
                </div>
            </div>

            <!-- Gelombang Aktif -->
            <div id="gelombangAktif" class="mb-5" style="display: none;">
                <div class="timeline-item-gelombang active" data-aos="fade-right">
                    <span class="status-badge bg-success">Sedang Berlangsung</span>
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h4 class="text-primary" id="gelombangNama">Loading...</h4>
                            <p class="text-muted mb-3" id="gelombangDeskripsi">Memuat informasi gelombang...</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><i class="fas fa-calendar me-2 text-primary"></i><strong>Periode:</strong> <span id="gelombangPeriode">-</span></p>
                                    <p><i class="fas fa-money-bill-wave me-2 text-success"></i><strong>Biaya:</strong> <span id="gelombangBiaya">-</span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><i class="fas fa-users me-2 text-info"></i><strong>Kuota:</strong> <span id="gelombangKuota">-</span></p>
                                    <p><i class="fas fa-clock me-2 text-warning"></i><strong>Status:</strong> <span class="badge bg-success">Aktif</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-center">
                            <div class="bg-white rounded-3 p-4 shadow">
                                <h6 class="text-primary mb-3">Pendaftaran Terbuka</h6>
                                <p class="text-muted mb-3">Segera daftarkan diri Anda sebelum kuota penuh!</p>
                                <button class="btn btn-primary w-100" onclick="window.location.href='/register'">
                                    <i class="fas fa-rocket me-2"></i>Daftar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Semua Gelombang -->
            <div class="gelombang-timeline" id="semuaGelombang">
                <div class="text-center mb-4">
                    <p class="text-muted">Memuat data gelombang...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Prestasi Section -->
    <section id="prestasi" class="section">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-3">Prestasi Membanggakan</h2>
                    <p class="lead text-muted">Bukti kualitas pendidikan yang konsisten</p>
                </div>
            </div>

            <div class="prestasi-grid">
                <!-- Prestasi 1 -->
                <div class="prestasi-card" data-aos="zoom-in" data-aos-delay="100">
                    <div class="prestasi-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Juara 1 LKS Tingkat Nasional 2023</h5>
                    <p class="text-muted mb-3">Web Technologies - Muhammad Farhat</p>
                    <div class="achievement-meta">
                        <span class="badge bg-primary">IT & Programming</span>
                        <span class="badge bg-success">Tingkat Nasional</span>
                    </div>
                </div>

                <!-- Prestasi 2 -->
                <div class="prestasi-card" data-aos="zoom-in" data-aos-delay="200">
                    <div class="prestasi-icon">
                        <i class="fas fa-medal"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Juara 2 Olimpiade Matematika</h5>
                    <p class="text-muted mb-3">Olimpiade Sains Nasional - Nadhifa Aufa Zahra</p>
                    <div class="achievement-meta">
                        <span class="badge bg-warning">Matematika</span>
                        <span class="badge bg-info">Tingkat Provinsi</span>
                    </div>
                </div>

                <!-- Prestasi 3 -->
                <div class="prestasi-card" data-aos="zoom-in" data-aos-delay="300">
                    <div class="prestasi-icon">
                        <i class="fas fa-award"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Sekolah Adiwiyata Nasional</h5>
                    <p class="text-muted mb-3">Penghargaan Lingkungan Hidup 2023</p>
                    <div class="achievement-meta">
                        <span class="badge bg-success">Lingkungan</span>
                        <span class="badge bg-primary">Tingkat Nasional</span>
                    </div>
                </div>

                <!-- Prestasi 4 -->
                <div class="prestasi-card" data-aos="zoom-in" data-aos-delay="400">
                    <div class="prestasi-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Inovasi Teknologi Terbaik</h5>
                    <p class="text-muted mb-3">Kompetensi Inovasi Digital 2023</p>
                    <div class="achievement-meta">
                        <span class="badge bg-info">Inovasi</span>
                        <span class="badge bg-danger">Tingkat Regional</span>
                    </div>
                </div>
            </div>

            <!-- Achievement Stats -->
            <div class="row mt-5 text-center">
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="counter-item">
                        <div class="counter-number" data-count="25">0</div>
                        <small>Prestasi Nasional</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="counter-item">
                        <div class="counter-number" data-count="48">0</div>
                        <small>Prestasi Regional</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="counter-item">
                        <div class="counter-number" data-count="15">0</div>
                        <small>Penghargaan</small>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="counter-item">
                        <div class="counter-number" data-count="92">0</div>
                        <small>Siswa Berprestasi</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Jurusan Section -->
    <section id="jurusan" class="section section-alt">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-3">Program Jurusan Unggulan</h2>
                    <p class="lead text-muted">Pilih jurusan yang sesuai dengan passion dan bakat Anda</p>
                </div>
            </div>

            <div class="row">
                @forelse($jurusan as $index => $j)
                <div class="col-lg-4 col-md-6 mb-4" data-aos="flip-left" data-aos-delay="{{ ($index + 1) * 100 }}">
                    <div class="jurusan-card">
                        <div class="jurusan-icon">
                            @if($j->kode == 'PPLG')
                                <i class="fas fa-code"></i>
                            @elseif($j->kode == 'AKT')
                                <i class="fas fa-calculator"></i>
                            @elseif($j->kode == 'DKV')
                                <i class="fas fa-palette"></i>
                            @elseif($j->kode == 'PM')
                                <i class="fas fa-chart-line"></i>
                            @elseif($j->kode == 'ANM')
                                <i class="fas fa-film"></i>
                            @else
                                <i class="fas fa-graduation-cap"></i>
                            @endif
                        </div>
                        <h5 class="fw-bold mb-3">{{ $j->kode }}</h5>
                        <h6 class="text-primary mb-3">{{ $j->nama }}</h6>
                        <p class="text-muted mb-4">
                            @if($j->kode == 'PPLG')
                                Belajar pemrograman, pengembangan software, mobile apps, dan game development
                            @elseif($j->kode == 'AKT')
                                Mengelola keuangan, pembukuan, auditing, dan sistem akuntansi modern
                            @elseif($j->kode == 'DKV')
                                Desain grafis, ilustrasi digital, fotografi, dan komunikasi visual kreatif
                            @elseif($j->kode == 'PM')
                                Strategi pemasaran, digital marketing, e-commerce, dan kewirausahaan
                            @elseif($j->kode == 'ANM')
                                Pembuatan animasi 2D/3D, visual effect, motion graphic, dan produksi film
                            @else
                                Program keahlian unggulan dengan kurikulum terkini
                            @endif
                        </p>
                        <div class="jurusan-meta" id="jurusan-meta-{{ $j->id }}">
                            <span class="badge bg-primary">Kuota: {{ $j->kuota ?? 0 }}</span>
                            <span class="badge bg-success">Terisi: 0</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Belum ada data jurusan tersedia</p>
                </div>
                @endforelse

                <!-- Info Pendaftaran -->
                <div class="col-lg-4 col-md-6 mb-4" data-aos="flip-left" data-aos-delay="{{ ($jurusan->count() + 1) * 100 }}">
                    <div class="jurusan-card bg-primary text-white">
                        <div class="jurusan-icon bg-white text-primary">
                            <i class="fas fa-info"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Info Pendaftaran</h5>
                        <p class="mb-4">Segera daftar sebelum kuota terpenuhi. Pilihan jurusan dapat ditentukan saat
                            pengisian formulir.</p>
                        <button class="btn btn-light w-100" onclick="window.location.href='/register'">
                            <i class="fas fa-rocket me-2"></i>Daftar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Alur Pendaftaran Section -->
    <section id="alur" class="section alur-pendaftaran">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12" data-aos="fade-up">
                    <h2 class="display-5 fw-bold mb-3 text-white">Alur Pendaftaran PPDB</h2>
                    <p class="lead text-white-50">Proses pendaftaran yang mudah dan transparan</p>
                </div>
            </div>

            <div class="row">
                <!-- Step 1 -->
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-number">1</div>
                        <h5 class="fw-bold mb-3">Registrasi Akun</h5>
                        <p class="small">Buat akun dengan email dan data pribadi yang valid</p>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-number">2</div>
                        <h5 class="fw-bold mb-3">Isi Formulir</h5>
                        <p class="small">Lengkapi data diri dan pilih program keahlian</p>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-number">3</div>
                        <h5 class="fw-bold mb-3">Upload Dokumen</h5>
                        <p class="small">Upload scan dokumen yang diperlukan secara digital</p>
                    </div>
                </div>

                <!-- Step 4 -->
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-card">
                        <div class="step-number">4</div>
                        <h5 class="fw-bold mb-3">Pembayaran</h5>
                        <p class="small">Lakukan pembayaran melalui metode yang tersedia</p>
                    </div>
                </div>

                <!-- Step 5 -->
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="step-card">
                        <div class="step-number">5</div>
                        <h5 class="fw-bold mb-3">Verifikasi</h5>
                        <p class="small">Tim admin memverifikasi kelengkapan data</p>
                    </div>
                </div>

                <!-- Step 6 -->
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="step-card">
                        <div class="step-number">6</div>
                        <h5 class="fw-bold mb-3">Pengumuman</h5>
                        <p class="small">Hasil seleksi diumumkan melalui website dan email</p>
                    </div>
                </div>

                <!-- Step 7 -->
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="700">
                    <div class="step-card">
                        <div class="step-number">7</div>
                        <h5 class="fw-bold mb-3">Daftar Ulang</h5>
                        <p class="small">Lakukan daftar ulang bagi yang diterima</p>
                    </div>
                </div>

                <!-- Step 8 -->
                <div class="col-md-3 col-6 mb-4" data-aos="fade-up" data-aos-delay="800">
                    <div class="step-card">
                        <div class="step-number">8</div>
                        <h5 class="fw-bold mb-3">Welcome</h5>
                        <p class="small">Selamat bergabung di SMK Bakti Nusantara 666</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-school me-2"></i>SMK BAKTI NUSANTARA 666
                    </h5>
                    <p class="mb-3">"Mencerdaskan Kehidupan Bangsa, Dengan prinsip SAJUTA Santun-Jujur- Taat"</p>
                    <p class="text-muted mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>Jl. Raya Percobaan No.65, Cileunyi Kulon, Kec.
                        Cileunyi, Kabupaten Bandung, Jawa Barat 40622<br>
                        <i class="fas fa-phone me-2"></i>(021) 1234-5678<br>
                        <i class="fas fa-envelope me-2"></i>info@smkbn666.sch.id
                    </p>
                </div>
                <div class="col-lg-6 text-lg-end">
                    <h6 class="fw-bold mb-3">PPDB Online System</h6>
                    <p class="text-muted mb-0">&copy; 2025 SMK Bakti Nusantara 666. All rights reserved.</p>
                    <p class="text-muted">Version 1.0 - Futuristic Design</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function () {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 100) {
                navbar.classList.add('navbar-scrolled');
            } else {
                navbar.classList.remove('navbar-scrolled');
            }
        });

        // Smooth scrolling for navbar links
        document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetSection = document.querySelector(targetId);

                if (targetSection) {
                    // Close mobile navbar if open
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarCollapse.classList.contains('show')) {
                        navbarCollapse.classList.remove('show');
                    }

                    // Smooth scroll to section
                    targetSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });

                    // Update active nav link
                    document.querySelectorAll('.navbar-nav .nav-link').forEach(navLink => {
                        navLink.classList.remove('active');
                    });
                    this.classList.add('active');
                }
            });
        });

        // Counter animation
        function animateCounter() {
            const counters = document.querySelectorAll('.counter-number');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                let current = 0;
                const increment = target / 50;

                const updateCounter = () => {
                    if (current < target) {
                        current += increment;
                        counter.textContent = Math.ceil(current);
                        setTimeout(updateCounter, 20);
                    } else {
                        counter.textContent = target;
                    }
                };
                updateCounter();
            });
        }

        // Trigger counter when hero section is in view
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter();
                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        const heroSection = document.querySelector('.hero-section');
        if (heroSection) {
            counterObserver.observe(heroSection);
        }

        // Update active nav link on scroll
        window.addEventListener('scroll', function () {
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.clientHeight;
                if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Simple redirect functions
        window.goToRegister = function () {
            window.location.href = '/register';
        };

        window.goToLogin = function () {
            window.location.href = '/login';
        };

        // Load gelombang data
        function loadGelombangData() {
            fetch('/api/gelombang')
                .then(response => response.json())
                .then(result => {
                    console.log('Gelombang API Response:', result);
                    if (result.success && result.data) {
                        const gelombangList = result.data;
                        
                        // Find active gelombang (is_aktif = 1)
                        const activeGelombang = gelombangList.find(g => g.is_aktif == 1);
                        console.log('Active Gelombang Found:', activeGelombang);

                        if (activeGelombang) {
                            displayActiveGelombang(activeGelombang);
                        } else {
                            console.log('No active gelombang found');
                            document.getElementById('gelombangAktif').style.display = 'none';
                        }

                        displayAllGelombang(gelombangList);
                    } else {
                        console.log('No gelombang data received');
                        document.getElementById('semuaGelombang').innerHTML = 
                            '<div class="text-center"><p class="text-muted">Belum ada data gelombang</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error loading gelombang:', error);
                    document.getElementById('semuaGelombang').innerHTML = 
                        '<div class="text-center"><p class="text-muted">Gagal memuat data gelombang</p></div>';
                });
        }

        function displayActiveGelombang(gelombang) {
            console.log('Displaying active gelombang:', gelombang);
            
            document.getElementById('gelombangNama').textContent = gelombang.nama;
            document.getElementById('gelombangDeskripsi').textContent = 
                `Periode pendaftaran ${gelombang.nama.toLowerCase()} dengan berbagai keuntungan`;
            
            const startDate = new Date(gelombang.tgl_mulai).toLocaleDateString('id-ID');
            const endDate = new Date(gelombang.tgl_selesai).toLocaleDateString('id-ID');
            document.getElementById('gelombangPeriode').textContent = `${startDate} - ${endDate}`;
            
            document.getElementById('gelombangBiaya').textContent = 
                `Rp ${gelombang.biaya_daftar.toLocaleString('id-ID')}`;
            
            document.getElementById('gelombangKuota').textContent = `${gelombang.kuota} Siswa`;
            
            // Show the active gelombang section
            const gelombangAktifElement = document.getElementById('gelombangAktif');
            gelombangAktifElement.style.display = 'block';
            console.log('Active gelombang section displayed');
        }

        function displayAllGelombang(gelombangList) {
            const container = document.getElementById('semuaGelombang');
            container.innerHTML = '';

            if (gelombangList.length === 0) {
                container.innerHTML = '<div class="text-center"><p class="text-muted">Belum ada gelombang tersedia</p></div>';
                return;
            }

            gelombangList.forEach((gelombang, index) => {
                const now = new Date();
                const startDate = new Date(gelombang.tgl_mulai);
                const endDate = new Date(gelombang.tgl_selesai);
                
                let status = 'Akan Datang';
                let statusClass = 'bg-info';
                let itemClass = '';
                
                if (gelombang.is_aktif == 1) {
                    if (now >= startDate && now <= endDate) {
                        status = 'Sedang Berlangsung';
                        statusClass = 'bg-success';
                        itemClass = 'active';
                    } else if (now > endDate) {
                        status = 'Berakhir';
                        statusClass = 'bg-secondary';
                    } else {
                        status = 'Akan Datang';
                        statusClass = 'bg-warning';
                    }
                } else {
                    status = 'Nonaktif';
                    statusClass = 'bg-danger';
                }

                const gelombangHtml = `
                    <div class="timeline-item-gelombang ${itemClass}" data-aos="fade-right" data-aos-delay="${(index + 1) * 100}">
                        <span class="status-badge ${statusClass}">${status}</span>
                        <h4 class="text-primary">${gelombang.nama}</h4>
                        <p class="text-muted mb-3">Tahun ${gelombang.tahun}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <p><i class="fas fa-calendar me-2 text-primary"></i><strong>Periode:</strong> ${startDate.toLocaleDateString('id-ID')} - ${endDate.toLocaleDateString('id-ID')}</p>
                                <p><i class="fas fa-money-bill-wave me-2 text-success"></i><strong>Biaya:</strong> Rp ${gelombang.biaya_daftar.toLocaleString('id-ID')}</p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="fas fa-users me-2 text-info"></i><strong>Kuota:</strong> ${gelombang.kuota} Siswa</p>
                                <p><i class="fas fa-toggle-${gelombang.is_aktif == 1 ? 'on' : 'off'} me-2 text-${gelombang.is_aktif == 1 ? 'success' : 'danger'}"></i><strong>Status:</strong> ${gelombang.is_aktif == 1 ? 'Aktif' : 'Nonaktif'}</p>
                            </div>
                        </div>
                    </div>
                `;
                
                container.innerHTML += gelombangHtml;
            });
        }



        // Load jurusan data from API
        function loadJurusanData() {
            fetch('/api/jurusan')
                .then(response => response.json())
                .then(result => {
                    console.log('Jurusan API Response:', result);
                    if (result.success && result.data) {
                        result.data.forEach(jurusan => {
                            const metaElement = document.getElementById(`jurusan-meta-${jurusan.id}`);
                            if (metaElement) {
                                metaElement.innerHTML = `
                                    <span class="badge bg-primary">Kuota: ${jurusan.kuota}</span>
                                    <span class="badge bg-success">Terisi: ${jurusan.jumlah_pendaftar || 0}</span>
                                `;
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading jurusan:', error);
                });
        }

        // Load data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadGelombangData();
            loadJurusanData();
        });

        console.log('SMK Bakti Nusantara 666 - PPDB Online System loaded successfully');
    </script>
</body>

</html>