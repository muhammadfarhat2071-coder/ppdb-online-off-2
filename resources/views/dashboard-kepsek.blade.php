<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Sekolah - SMK Bakti Nusantara 666</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        #map {
            height: 400px;
            border-radius: 10px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 2rem;
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
                        <i class="fas fa-user-tie me-2"></i>
                        KEPALA SEKOLAH
                    </h5>
                    <p class="text-center small mb-4">SMK Bakti Nusantara 666</p>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" data-target="dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-target="statistik">
                                <i class="fas fa-chart-bar me-2"></i>Statistik
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
                        <span class="navbar-brand mb-0 h6">Dashboard Kepala Sekolah</span>
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
                                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard Kepala Sekolah
                                                </h2>
                                                <p class="text-muted mb-0">
                                                    Selamat datang, <strong>{{ $user->nama }}</strong>!
                                                    <span class="badge bg-primary ms-2">Kepala Sekolah</span>
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
                                            <small><i class="fas fa-users me-1"></i>Calon siswa</small>
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
                                            <h5 class="card-title mb-1">Terverifikasi</h5>
                                            <h2 class="mb-0" id="terverifikasi">0</h2>
                                            <small><i class="fas fa-check-circle me-1"></i>Sudah diverifikasi</small>
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
                                            <i class="fas fa-hourglass-half fa-2x opacity-50"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="stat-card info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="card-title mb-1">Pendapatan</h5>
                                            <h2 class="mb-0" id="totalPendapatan">Rp 0</h2>
                                            <small><i class="fas fa-money-bill-wave me-1"></i>Total
                                                terkonfirmasi</small>
                                        </div>
                                        <div class="stat-icon">
                                            <i class="fas fa-money-bill-wave fa-2x opacity-50"></i>
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
                                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Distribusi
                                            Pendaftar per Jurusan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="jurusanChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-custom">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i class="fas fa-chart-line me-2 text-success"></i>Tren
                                            Pendaftaran</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-container">
                                            <canvas id="trendChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Summary Tables -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-custom">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i
                                                class="fas fa-graduation-cap me-2 text-primary"></i>Ringkasan per
                                            Jurusan</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Jurusan</th>
                                                        <th>Kuota</th>
                                                        <th>Pendaftar</th>
                                                        <th>Persentase</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="jurusanTable">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-custom">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i class="fas fa-money-check-alt me-2 text-success"></i>Status
                                            Pembayaran</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-success mb-1" id="lunas">0</h4>
                                                    <small class="text-muted">Lunas</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-warning mb-1" id="menungguKonfirmasi">0</h4>
                                                    <small class="text-muted">Menunggu</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border rounded p-3">
                                                    <h4 class="text-danger mb-1" id="belumBayar">0</h4>
                                                    <small class="text-muted">Belum Bayar</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <canvas id="pembayaranChart" height="150"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other sections placeholder -->
                    <div id="statistik" class="section">
                        <!-- Statistik Asal Sekolah -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card card-custom">
                                    <div class="card-header bg-white border-0">
                                        <h5 class="mb-0"><i class="fas fa-school me-2 text-primary"></i>Statistik Asal Sekolah Pendaftar</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Summary Cards -->
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <div class="stat-card info">
                                                    <div class="text-center">
                                                        <h3 class="mb-1" id="totalSekolahAsal">0</h3>
                                                        <small>Total Sekolah Asal</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-card success">
                                                    <div class="text-center">
                                                        <h3 class="mb-1" id="sekolahNegeri">0</h3>
                                                        <small>Sekolah Negeri</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-card warning">
                                                    <div class="text-center">
                                                        <h3 class="mb-1" id="sekolahSwasta">0</h3>
                                                        <small>Sekolah Swasta</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="stat-card primary">
                                                    <div class="text-center">
                                                        <h3 class="mb-1" id="rataRataNilai">0</h3>
                                                        <small>Rata-rata Nilai</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Charts Row -->
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">Distribusi per Kabupaten</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="chart-container">
                                                            <canvas id="kabupatenChart"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h6 class="mb-0">Status Sekolah</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="chart-container">
                                                            <canvas id="statusSekolahChart"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Detail Table -->
                                        <div class="card">
                                            <div class="card-header">
                                                <h6 class="mb-0">Detail Asal Sekolah</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Sekolah</th>
                                                                <th>NPSN</th>
                                                                <th>Status</th>
                                                                <th>Kabupaten</th>
                                                                <th>Jumlah Pendaftar</th>
                                                                <th>Rata-rata Nilai</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="asalSekolahTable">
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


                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Global variables
        let charts = {};
        let map;

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

            loadSectionData(sectionId);
        }

        // Load data for specific section
        function loadSectionData(sectionId) {
            switch (sectionId) {
                case 'dashboard':
                    loadDashboardData();
                    break;
                case 'statistik':
                    loadStatistikData();
                    break;
            }
        }

        // Load dashboard data
        async function loadDashboardData() {
            try {
                const response = await fetch('/kepsek/dashboard-stats');
                const result = await response.json();

                if (result.success) {
                    const data = result.data;

                    document.getElementById('totalPendaftar').textContent = data.total_pendaftar || 0;
                    document.getElementById('terverifikasi').textContent = data.terverifikasi || 0;
                    document.getElementById('menungguVerifikasi').textContent = data.menunggu_verifikasi || 0;
                    document.getElementById('totalPendapatan').textContent = formatCurrency(data.total_pendapatan || 0);

                    document.getElementById('lunas').textContent = data.lunas || 0;
                    document.getElementById('menungguKonfirmasi').textContent = data.menunggu_konfirmasi || 0;
                    document.getElementById('belumBayar').textContent = data.belum_bayar || 0;

                    loadJurusanTable(data.jurusan || []);
                    createCharts(data);
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        function loadJurusanTable(jurusanData) {
            const table = document.getElementById('jurusanTable');
            table.innerHTML = '';

            jurusanData.forEach(jurusan => {
                const persentase = jurusan.kuota > 0 ? Math.round((jurusan.pendaftar / jurusan.kuota) * 100) : 0;
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${jurusan.nama}</td>
                    <td>${jurusan.kuota}</td>
                    <td>${jurusan.pendaftar}</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar" style="width: ${persentase}%">${persentase}%</div>
                        </div>
                    </td>
                `;
                table.appendChild(row);
            });
        }

        function createCharts(data) {
            createJurusanChart(data.jurusan || []);
            createTrendChart(data.trend || []);
            createPembayaranChart(data);
        }

        function createJurusanChart(jurusanData) {
            const ctx = document.getElementById('jurusanChart').getContext('2d');
            if (charts.jurusan) charts.jurusan.destroy();

            charts.jurusan = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: jurusanData.map(j => j.nama),
                    datasets: [{
                        data: jurusanData.map(j => j.pendaftar),
                        backgroundColor: ['#1e3c72', '#2a5298', '#28a745', '#ffc107']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function createTrendChart(trendData) {
            const ctx = document.getElementById('trendChart').getContext('2d');
            if (charts.trend) charts.trend.destroy();

            charts.trend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: trendData.map(t => t.tanggal),
                    datasets: [{
                        label: 'Pendaftar',
                        data: trendData.map(t => t.jumlah),
                        borderColor: '#1e3c72',
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        function createPembayaranChart(data) {
            const ctx = document.getElementById('pembayaranChart').getContext('2d');
            if (charts.pembayaran) charts.pembayaran.destroy();

            charts.pembayaran = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Lunas', 'Menunggu', 'Belum Bayar'],
                    datasets: [{
                        data: [data.lunas || 0, data.menunggu_konfirmasi || 0, data.belum_bayar || 0],
                        backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        }

        // Load statistik data
        async function loadStatistikData() {
            try {
                const response = await fetch('/kepsek/statistik-asal-sekolah');
                const result = await response.json();

                if (result.success) {
                    const data = result.data;

                    // Update summary cards
                    document.getElementById('totalSekolahAsal').textContent = data.total_sekolah || 0;
                    document.getElementById('sekolahNegeri').textContent = data.sekolah_negeri || 0;
                    document.getElementById('sekolahSwasta').textContent = data.sekolah_swasta || 0;
                    document.getElementById('rataRataNilai').textContent = (data.rata_rata_nilai || 0).toFixed(2);

                    // Load table
                    loadAsalSekolahTable(data.detail_sekolah || []);
                    
                    // Create charts
                    createAsalSekolahCharts(data);
                }
            } catch (error) {
                console.error('Error loading statistik data:', error);
            }
        }

        function loadAsalSekolahTable(sekolahData) {
            const table = document.getElementById('asalSekolahTable');
            table.innerHTML = '';

            if (!sekolahData || sekolahData.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td colspan="6" class="text-center text-muted">
                        <i class="fas fa-info-circle me-2"></i>Belum ada data asal sekolah
                    </td>
                `;
                table.appendChild(row);
                return;
            }

            sekolahData.forEach(sekolah => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${sekolah.nama_sekolah || '-'}</td>
                    <td>${sekolah.npsn || '-'}</td>
                    <td>
                        <span class="badge ${sekolah.status_sekolah === 'Negeri' ? 'bg-success' : 'bg-warning'}">
                            ${sekolah.status_sekolah || 'Negeri'}
                        </span>
                    </td>
                    <td>${sekolah.kabupaten || '-'}</td>
                    <td>
                        <span class="badge bg-primary">${sekolah.jumlah_pendaftar || 0}</span>
                    </td>
                    <td>${sekolah.rata_rata_nilai ? parseFloat(sekolah.rata_rata_nilai).toFixed(2) : '-'}</td>
                `;
                table.appendChild(row);
            });
        }

        function createAsalSekolahCharts(data) {
            createKabupatenChart(data.per_kabupaten || []);
            createStatusSekolahChart(data);
        }

        function createKabupatenChart(kabupatenData) {
            const ctx = document.getElementById('kabupatenChart').getContext('2d');
            if (charts.kabupaten) charts.kabupaten.destroy();

            if (!kabupatenData || kabupatenData.length === 0) {
                // Show empty chart message
                ctx.font = '16px Arial';
                ctx.fillStyle = '#6c757d';
                ctx.textAlign = 'center';
                ctx.fillText('Belum ada data', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }

            charts.kabupaten = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: kabupatenData.map(k => k.kabupaten || 'Tidak Diketahui'),
                    datasets: [{
                        label: 'Jumlah Pendaftar',
                        data: kabupatenData.map(k => k.jumlah || 0),
                        backgroundColor: '#1e3c72'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.parsed.y} pendaftar`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        function createStatusSekolahChart(data) {
            const ctx = document.getElementById('statusSekolahChart').getContext('2d');
            if (charts.statusSekolah) charts.statusSekolah.destroy();

            const negeri = data.sekolah_negeri || 0;
            const swasta = data.sekolah_swasta || 0;

            if (negeri === 0 && swasta === 0) {
                // Show empty chart message
                ctx.font = '16px Arial';
                ctx.fillStyle = '#6c757d';
                ctx.textAlign = 'center';
                ctx.fillText('Belum ada data', ctx.canvas.width / 2, ctx.canvas.height / 2);
                return;
            }

            charts.statusSekolah = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Negeri', 'Swasta'],
                    datasets: [{
                        data: [negeri, swasta],
                        backgroundColor: ['#28a745', '#ffc107']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = negeri + swasta;
                                    const percentage = ((context.parsed / total) * 100).toFixed(1);
                                    return `${context.label}: ${context.parsed} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }



        // Initialize
        document.addEventListener('DOMContentLoaded', function () {
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
                document.getElementById('currentDate').textContent = now.toLocaleDateString('id-ID');
                document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID');
            }
            setInterval(updateDateTime, 1000);
            updateDateTime();

            // Load initial data
            loadDashboardData();
        });
    </script>
</body>

</html>