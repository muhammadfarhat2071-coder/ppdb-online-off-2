<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPDB Online - SMK Bakti Nusantara 666')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1e3c72;
            --secondary-color: #2a5298;
            --accent-color: #667eea;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --danger-color: #dc3545;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 3px 10px;
            transition: all 0.3s ease;
            border: none;
            background: transparent;
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
        }

        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary-color);
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 60, 114, 0.4);
        }

        .stat-card {
            border-radius: 15px;
            color: white;
            padding: 20px;
            margin-bottom: 20px;
        }

        .stat-card.primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .stat-card.success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }

        .stat-card.info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-marker {
            position: absolute;
            left: -30px;
            top: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #dee2e6;
            border: 3px solid #fff;
        }

        .timeline-item.active .timeline-marker {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .timeline-content {
            padding: 15px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .progress-custom {
            height: 8px;
            border-radius: 10px;
            background-color: #e9ecef;
        }

        .progress-bar-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 10px;
        }
    </style>
</head>

<body>
    @if(Auth::check())
        <!-- LAYOUT UNTUK SEMUA USER YANG SUDAH LOGIN -->
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <!-- School Logo & Name -->
                        <div class="text-center mb-4 px-3">
                            <div class="user-avatar mx-auto mb-3" style="width: 60px; height: 60px; font-size: 24px;">
                                <i class="fas fa-school"></i>
                            </div>
                            <h6 class="mb-1 fw-bold">SMK BAKTI NUSANTARA 666</h6>
                            <small class="text-muted">PPDB Online System</small>
                            <div class="mt-2">
                                <span class="badge bg-light text-dark">
                                    <i class="fas fa-user me-1"></i>
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </div>
                        </div>

                        <ul class="nav flex-column">
                            <!-- Menu Utama -->
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>

                            <!-- Menu untuk Semua Role -->
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="fas fa-user me-2"></i> Profil Saya
                                </a>
                            </li>

                            <!-- Menu Khusus Berdasarkan Role -->
                            @if(Auth::user()->role == 'pendaftar')
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-edit me-2"></i> Formulir Pendaftaran
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-file-upload me-2"></i> Upload Berkas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-credit-card me-2"></i> Pembayaran
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-print me-2"></i> Cetak Kartu
                                    </a>
                                </li>
                            @endif

                            @if(in_array(Auth::user()->role, ['admin', 'verifikator_adm']))
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-users me-2"></i> Data Pendaftar
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-check-circle me-2"></i> Verifikasi Administrasi
                                    </a>
                                </li>
                            @endif

                            @if(Auth::user()->role == 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-book me-2"></i> Master Jurusan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-calendar me-2"></i> Master Gelombang
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-cog me-2"></i> Pengaturan Sistem
                                    </a>
                                </li>
                            @endif

                            @if(in_array(Auth::user()->role, ['keuangan', 'admin']))
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-money-bill-wave me-2"></i> Verifikasi Pembayaran
                                    </a>
                                </li>
                            @endif

                            @if(in_array(Auth::user()->role, ['kepsek', 'admin']))
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="fas fa-chart-bar me-2"></i> Laporan Eksekutif
                                    </a>
                                </li>
                            @endif

                            <!-- Logout -->
                            <li class="nav-item mt-4">
                                <hr>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="nav-link text-danger w-100 text-start bg-transparent border-0">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>

                        <!-- System Info -->
                        <div class="mt-5 px-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ Auth::user()->nama }}
                            </small>
                        </div>
                    </div>
                </nav>

                <!-- Main content -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                    <!-- Top Navigation -->
                    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarNav">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav ms-auto">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#"
                                            id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                            <div class="user-avatar me-2">
                                                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold small">{{ Auth::user()->nama }}</div>
                                                <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                                            </div>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i
                                                        class="fas fa-user me-2"></i>Profile</a></li>
                                            <li><a class="dropdown-item" href="#"><i
                                                        class="fas fa-cog me-2"></i>Settings</a></li>
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                            <li>
                                                <form action="{{ route('logout') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>

                    <!-- Main Content -->
                    <div class="container-fluid py-4">
                        @yield('content')
                    </div>
                </main>
            </div>
        </div>

    @else
        <!-- LAYOUT UNTUK GUEST (BELUM LOGIN) -->
        @yield('content')
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- SweetAlert2 untuk notifikasi yang lebih baik -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert untuk success messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // SweetAlert untuk error messages
        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                html: `@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach`,
                confirmButtonText: 'Mengerti'
            });
        @endif

        // Auto-dismiss alerts setelah 5 detik
        setTimeout(function () {
            $('.alert').fadeOut('slow');
        }, 5000);
    </script>

    @stack('scripts')
</body>

</html>