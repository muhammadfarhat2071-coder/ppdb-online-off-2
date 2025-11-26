<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Carbon\Carbon;

// Halaman Welcome
Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

// ==================== LOGIN ROUTES ====================
Route::get('/login', function () {
    // Jika sudah login, redirect ke dashboard sesuai role
    if (Auth::check()) {
        $user = Auth::user();
        return redirectToDashboard($user->role);
    }
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    // Validasi input menggunakan validate() method
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ], [
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'password.required' => 'Password harus diisi'
    ]);

    try {
        // Cari user berdasarkan email
        $user = Pengguna::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            // Cek apakah user aktif
            if (!$user->aktif) {
                return back()->withErrors([
                    'email' => 'Akun Anda tidak aktif. Hubungi administrator.',
                ])->withInput();
            }

            // Login user
            Auth::login($user);

            // Update last login
            $user->update(['last_login' => now()]);

            // Regenerate session untuk keamanan
            $request->session()->regenerate();

            // Redirect berdasarkan role
            return redirectToDashboard($user->role)->with('success', 'Login berhasil! Selamat datang ' . $user->nama);
        } else {
            // Jika user tidak ditemukan atau password salah
            return back()->withErrors([
                'email' => 'Email atau password tidak valid.',
            ])->withInput();
        }
    } catch (\Exception $e) {
        // Handle error database
        return back()->withErrors([
            'email' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
        ])->withInput();
    }
});

// ==================== REGISTER ROUTES ====================
Route::get('/register', function () {
    // Jika sudah login, redirect ke dashboard
    if (Auth::check()) {
        $user = Auth::user();
        return redirectToDashboard($user->role);
    }
    
    // Load wilayah data
    $wilayah = \App\Models\Wilayah::all();
    return view('auth.register', compact('wilayah'));
})->name('register');

Route::post('/register', function (Request $request) {
    // Validasi input menggunakan validate() method
    $validated = $request->validate([
        'nama' => 'required|string|max:100',
        'email' => 'required|email|unique:pengguna,email',
        'hp' => 'required|string|max:20',
        'wilayah_id' => 'required|integer',
        'asal_sekolah' => 'required|string|max:255',
        'password' => 'required|min:6|confirmed'
    ], [
        'nama.required' => 'Nama lengkap harus diisi',
        'nama.max' => 'Nama maksimal 100 karakter',
        'email.required' => 'Email harus diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain.',
        'hp.required' => 'Nomor HP harus diisi',
        'wilayah_id.required' => 'Wilayah harus dipilih',
        'asal_sekolah.required' => 'Asal sekolah harus diisi',
        'password.required' => 'Password harus diisi',
        'password.min' => 'Password minimal 6 karakter',
        'password.confirmed' => 'Konfirmasi password tidak sesuai'
    ]);

    try {
        // Generate OTP
        $otp = rand(100000, 999999);
        
        // Simpan data registrasi di session
        $request->session()->put('register_data', $validated);
        
        // Simpan OTP ke database
        \App\Models\OtpVerification::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
            'is_used' => false
        ]);
        
        // Kirim OTP via email
        try {
            \Mail::to($request->email)->send(new \App\Mail\OtpMail($otp, $request->nama));
            \Log::info('OTP sent to email: ' . $request->email . ' - Code: ' . $otp);
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            \Log::info('OTP untuk ' . $request->email . ': ' . $otp);
        }
        
        return view('auth.verify-otp', ['email' => $request->email]);

    } catch (\Exception $e) {
        // Handle error database
        return back()->withErrors([
            'email' => 'Terjadi kesalahan sistem. Silakan coba lagi.',
        ])->withInput();
    }
});

// Route untuk verifikasi OTP
Route::post('/verify-otp', function (Request $request) {
    try {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6'
        ]);
        
        $otpRecord = \App\Models\OtpVerification::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$otpRecord) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'OTP tidak valid atau sudah kadaluarsa'], 400);
            }
            return back()->withErrors(['otp' => 'OTP tidak valid atau sudah kadaluarsa']);
        }
        
        // Ambil data registrasi dari session
        $registerData = session('register_data');
        if (!$registerData) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Session expired. Silakan registrasi ulang.'], 400);
            }
            return redirect('/register')->withErrors(['email' => 'Session expired. Silakan registrasi ulang.']);
        }
        
        // Buat user baru
        $user = new Pengguna();
        $user->nama = $registerData['nama'];
        $user->email = $registerData['email'];
        $user->hp = $registerData['hp'];
        $user->password = Hash::make($registerData['password']);
        $user->wilayah_id = $registerData['wilayah_id'];
        $user->asal_sekolah = $registerData['asal_sekolah'];
        $user->role = 'pendaftar';
        $user->aktif = 1;
        $user->save();
        
        // Mark OTP as used
        $otpRecord->update(['is_used' => true]);
        
        // Clear session data
        $request->session()->forget('register_data');
        
        // Login user dan regenerate session
        Auth::login($user);
        $request->session()->regenerate();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'Registrasi berhasil! Selamat datang ' . $user->nama,
                'redirect' => '/pendaftar/dashboard'
            ]);
        }
        
        // Redirect dengan success message
        return redirect()->intended('/pendaftar/dashboard')->with('success', 'Registrasi berhasil! Selamat datang ' . $user->nama);
        
    } catch (\Exception $e) {
        \Log::error('OTP verification error: ' . $e->getMessage());
        
        if ($request->expectsJson()) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat verifikasi. Silakan coba lagi.'], 500);
        }
        
        return back()->withErrors(['otp' => 'Terjadi kesalahan saat verifikasi. Silakan coba lagi.']);
    }
});

// Route untuk resend OTP
Route::post('/resend-otp', function (Request $request) {
    $request->validate(['email' => 'required|email']);
    
    // Generate OTP baru
    $otp = rand(100000, 999999);
    
    // Update atau create OTP record
    \App\Models\OtpVerification::updateOrCreate(
        ['email' => $request->email],
        [
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
            'is_used' => false
        ]
    );
    
    // Kirim OTP baru via email
    try {
        \Mail::to($request->email)->send(new \App\Mail\OtpMail($otp));
        \Log::info('New OTP sent to email: ' . $request->email . ' - Code: ' . $otp);
    } catch (\Exception $e) {
        \Log::error('Failed to send OTP email: ' . $e->getMessage());
        \Log::info('OTP baru untuk ' . $request->email . ': ' . $otp);
    }
    
    return back()->with('success', 'Kode OTP baru telah dikirim');
});

// ==================== FILE SERVING ROUTES ====================
Route::get('/berkas/{filename}', function ($filename) {
    $path = storage_path('app/public/berkas/' . $filename);
    
    if (!file_exists($path)) {
        abort(404, 'File tidak ditemukan di server');
    }
    
    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=3600'
    ]);
})->name('berkas.show');

// Route untuk serving bukti transfer seperti berkas
Route::get('/bukti/{filename}', function ($filename) {
    $path = storage_path('app/public/bukti_transfer/' . $filename);
    
    if (!file_exists($path)) {
        abort(404, 'File bukti transfer tidak ditemukan');
    }
    
    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=3600'
    ]);
})->name('bukti.show');

// Route untuk serving bukti transfer
Route::get('/bukti-transfer/{filename}', function ($filename) {
    $path = storage_path('app/public/bukti_transfer/' . $filename);
    
    if (!file_exists($path)) {
        abort(404, 'File bukti transfer tidak ditemukan');
    }
    
    $mimeType = mime_content_type($path);
    return response()->file($path, [
        'Content-Type' => $mimeType,
        'Cache-Control' => 'public, max-age=3600'
    ]);
})->name('bukti-transfer.show');

// Route untuk membuat storage link (hanya untuk development)
Route::get('/create-storage-link', function () {
    if (app()->environment('local')) {
        $target = storage_path('app/public');
        $link = public_path('storage');
        
        if (!file_exists($link)) {
            symlink($target, $link);
            return 'Storage link created successfully!';
        }
        return 'Storage link already exists!';
    }
    abort(403, 'Not allowed in production');
});

// Route untuk diagnosa file berkas (hanya untuk development)
Route::get('/diagnose-berkas', function () {
    if (!app()->environment('local')) {
        abort(403, 'Not allowed in production');
    }
    
    $berkasPath = storage_path('app/public/berkas');
    $publicPath = public_path('berkas');
    $storageLinkPath = public_path('storage');
    
    $info = [
        'berkas_storage_path' => $berkasPath,
        'berkas_storage_exists' => is_dir($berkasPath),
        'berkas_storage_files' => is_dir($berkasPath) ? scandir($berkasPath) : [],
        'public_berkas_path' => $publicPath,
        'public_berkas_exists' => is_dir($publicPath),
        'public_berkas_files' => is_dir($publicPath) ? scandir($publicPath) : [],
        'storage_link_exists' => is_link($storageLinkPath),
        'storage_link_target' => is_link($storageLinkPath) ? readlink($storageLinkPath) : null,
        'sample_berkas' => \App\Models\PendaftarBerkas::take(5)->get(['id', 'nama_file', 'jenis', 'created_at'])
    ];
    
    return response()->json($info, 200, [], JSON_PRETTY_PRINT);
});

// Route untuk diagnosa bukti transfer
Route::get('/diagnose-bukti-transfer', function () {
    if (!app()->environment('local')) {
        abort(403, 'Not allowed in production');
    }
    
    $buktiTransferPath = storage_path('app/public/bukti_transfer');
    $storageLinkPath = public_path('storage');
    
    $info = [
        'bukti_transfer_storage_path' => $buktiTransferPath,
        'bukti_transfer_storage_exists' => is_dir($buktiTransferPath),
        'bukti_transfer_files' => is_dir($buktiTransferPath) ? scandir($buktiTransferPath) : [],
        'storage_link_exists' => is_link($storageLinkPath),
        'storage_link_target' => is_link($storageLinkPath) ? readlink($storageLinkPath) : null,
        'sample_pembayaran' => \App\Models\Pembayaran::whereNotNull('bukti_transfer')->take(5)->get(['id', 'no_transaksi', 'bukti_transfer', 'created_at']),
        'pembayaran_count' => \App\Models\Pembayaran::whereNotNull('bukti_transfer')->count()
    ];
    
    return response()->json($info, 200, [], JSON_PRETTY_PRINT);
});

// ==================== API ROUTES (Public for AJAX) ====================
Route::prefix('api')->group(function () {
    // Jurusan
    Route::get('/jurusan', [App\Http\Controllers\JurusanController::class, 'index']);
    Route::post('/jurusan', [App\Http\Controllers\JurusanController::class, 'store']);
    Route::get('/jurusan/{id}', [App\Http\Controllers\JurusanController::class, 'show']);
    Route::put('/jurusan/{id}', [App\Http\Controllers\JurusanController::class, 'update']);
    Route::delete('/jurusan/{id}', [App\Http\Controllers\JurusanController::class, 'destroy']);

    // Gelombang
    Route::get('/gelombang', [App\Http\Controllers\GelombangController::class, 'index']);
    Route::post('/gelombang', [App\Http\Controllers\GelombangController::class, 'store']);
    Route::get('/gelombang/{id}', [App\Http\Controllers\GelombangController::class, 'show']);
    Route::put('/gelombang/{id}', [App\Http\Controllers\GelombangController::class, 'update']);
    Route::delete('/gelombang/{id}', [App\Http\Controllers\GelombangController::class, 'destroy']);

    // Wilayah
    Route::get('/wilayah', [App\Http\Controllers\WilayahController::class, 'index']);
    Route::post('/wilayah', [App\Http\Controllers\WilayahController::class, 'store']);
    Route::put('/wilayah/{id}', [App\Http\Controllers\WilayahController::class, 'update']);
    Route::delete('/wilayah/{id}', [App\Http\Controllers\WilayahController::class, 'destroy']);

    // Pendaftar
    Route::get('/pendaftar', [App\Http\Controllers\PendaftarController::class, 'index']);
    Route::get('/pendaftar/{id}', [App\Http\Controllers\PendaftarController::class, 'show']);
    Route::put('/pendaftar/{id}', [App\Http\Controllers\PendaftarController::class, 'update']);
    Route::delete('/pendaftar/{id}', [App\Http\Controllers\PendaftarController::class, 'destroy']);

    // Pengguna
    Route::get('/pengguna', [App\Http\Controllers\PenggunaController::class, 'index']);
    Route::post('/pengguna', [App\Http\Controllers\PenggunaController::class, 'store']);
    Route::get('/pengguna/{id}', [App\Http\Controllers\PenggunaController::class, 'show']);
    Route::put('/pengguna/{id}', [App\Http\Controllers\PenggunaController::class, 'update']);
    Route::delete('/pengguna/{id}', [App\Http\Controllers\PenggunaController::class, 'destroy']);
    Route::post('/pengguna/{id}/toggle-status', [App\Http\Controllers\PenggunaController::class, 'toggleStatus']);

    // Laporan
    Route::prefix('laporan')->group(function () {
        Route::get('/', [App\Http\Controllers\LaporanController::class, 'index']);
        Route::get('/harian', [App\Http\Controllers\LaporanController::class, 'generateHarian']);
        Route::get('/bulanan', [App\Http\Controllers\LaporanController::class, 'generateBulanan']);
        Route::get('/komprehensif', [App\Http\Controllers\LaporanController::class, 'generateKomprehensif']);
        Route::get('/download', [App\Http\Controllers\LaporanController::class, 'download'])->name('laporan.download');
    });

    // Pembayaran
    Route::prefix('pembayaran')->group(function () {
        Route::get('/', [App\Http\Controllers\PembayaranController::class, 'index']);
        Route::post('/', [App\Http\Controllers\PembayaranController::class, 'store']);
        Route::get('/{id}', [App\Http\Controllers\PembayaranController::class, 'show']);
        Route::post('/{id}/konfirmasi', [App\Http\Controllers\PembayaranController::class, 'konfirmasi']);
        Route::get('/status/menunggu', [App\Http\Controllers\PembayaranController::class, 'menungguKonfirmasi']);
        Route::get('/statistik/dashboard', [App\Http\Controllers\PembayaranController::class, 'statistik']);
    });
});

// ==================== PROTECTED ROUTES ====================
Route::middleware(['auth'])->group(function () {

    // ==================== CRUD ROUTES ====================
    Route::prefix('admin')->group(function () {
        Route::resource('jurusan', App\Http\Controllers\JurusanController::class);
        Route::resource('wilayah', App\Http\Controllers\WilayahController::class);
    });



    // ==================== DASHBOARD ROUTES BERDASARKAN ROLE ====================

    // Dashboard Routes
    Route::get('/admin/dashboard', [App\Http\Controllers\DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/kepsek/dashboard', [App\Http\Controllers\DashboardController::class, 'kepsek'])->name('kepsek.dashboard');
    Route::get('/verifikator/dashboard', [App\Http\Controllers\DashboardController::class, 'verifikator'])->name('verifikator.dashboard');
    Route::get('/keuangan/dashboard', [App\Http\Controllers\DashboardController::class, 'keuangan'])->name('keuangan.dashboard');
    Route::get('/pendaftar/dashboard', [App\Http\Controllers\DashboardController::class, 'pendaftar'])->name('pendaftar.dashboard');

    // Update Profil Pendaftar
    Route::post('/pendaftar/update-profil', function (Request $request) {
        $user = Auth::user();

        if ($user->role !== 'pendaftar') {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|unique:pengguna,email,' . $user->id . '|max:255',
            'hp' => 'required|string|max:20|regex:/^[0-9+\-\s]+$/'
        ]);

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->hp = $validated['hp'];
        $user->save();

        return response()->json(['success' => true, 'message' => 'Profil berhasil diupdate']);
    })->name('pendaftar.update-profil');

    // Route untuk verifikator
    Route::prefix('verifikator')->middleware(['auth'])->group(function () {
        // Get berkas yang perlu diverifikasi
        Route::get('/berkas-menunggu', function () {
            $berkas = \App\Models\PendaftarBerkas::with(['pendaftar.user', 'pendaftar.jurusan'])
                ->where('valid', 0)
                ->orderBy('created_at', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $berkas
            ]);
        });
        
        // Verifikasi berkas
        Route::post('/verifikasi-berkas/{id}', function (Request $request, $id) {
            try {
                $request->validate([
                    'status' => 'required|in:terima,tolak',
                    'catatan' => 'nullable|string|max:500'
                ]);
                
                $berkas = \App\Models\PendaftarBerkas::find($id);
                
                if (!$berkas) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Berkas tidak ditemukan'
                    ], 404);
                }
                
                $berkas->valid = $request->status === 'terima' ? 1 : 2;
                $berkas->catatan = $request->catatan;
                $berkas->tgl_verifikasi = now();
                $berkas->verifikator_id = Auth::id();
                $berkas->save();
                
                \Log::info('Berkas verified:', [
                    'berkas_id' => $id,
                    'status' => $request->status,
                    'verifikator' => Auth::user()->nama
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Berkas berhasil diverifikasi'
                ]);
            } catch (\Exception $e) {
                \Log::error('Error verifying berkas: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memverifikasi berkas: ' . $e->getMessage()
                ], 500);
            }
        });
        
        // Get detail berkas
        Route::get('/berkas/{id}', function ($id) {
            try {
                $berkas = \App\Models\PendaftarBerkas::with(['pendaftar.user', 'pendaftar.jurusan'])
                    ->find($id);
                
                if (!$berkas) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Berkas tidak ditemukan'
                    ], 404);
                }
                
                // Check if file exists in storage
                $filePath = storage_path('app/public/berkas/' . $berkas->nama_file);
                $berkas->file_exists = file_exists($filePath);
                $berkas->file_url = $berkas->file_exists ? '/berkas/' . $berkas->nama_file : null;
                
                return response()->json([
                    'success' => true,
                    'data' => $berkas
                ]);
            } catch (\Exception $e) {
                \Log::error('Error loading berkas detail: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat detail berkas: ' . $e->getMessage()
                ], 500);
            }
        });
        
        // Dashboard stats
        Route::get('/dashboard-stats', function () {
            $menungguVerifikasi = \App\Models\PendaftarBerkas::where('valid', 0)->count();
            $terverifikasiHariIni = \App\Models\PendaftarBerkas::where('valid', 1)
                ->whereDate('tgl_verifikasi', today())->count();
            $totalTerverifikasi = \App\Models\PendaftarBerkas::where('valid', 1)->count();
            
            return response()->json([
                'success' => true,
                'stats' => [
                    'menunggu_verifikasi' => $menungguVerifikasi,
                    'terverifikasi_hari_ini' => $terverifikasiHariIni,
                    'total_terverifikasi' => $totalTerverifikasi,
                    'rata_waktu' => 0
                ]
            ]);
        });
        
        // Recent activity
        Route::get('/recent-activity', function () {
            $activities = \App\Models\PendaftarBerkas::with(['pendaftar.user'])
                ->where('valid', '!=', 0)
                ->orderBy('tgl_verifikasi', 'desc')
                ->limit(10)
                ->get()
                ->map(function($berkas) {
                    return [
                        'created_at' => $berkas->tgl_verifikasi,
                        'no_pendaftaran' => $berkas->pendaftar->no_pendaftaran ?? '-',
                        'nama' => $berkas->pendaftar->user->nama ?? '-',
                        'status' => $berkas->valid == 1 ? 'Terverifikasi' : 'Ditolak',
                        'verifikator' => 'Verifikator'
                    ];
                });
                
            return response()->json([
                'success' => true,
                'activities' => $activities
            ]);
        });
        
        // Riwayat verifikasi
        Route::get('/riwayat-verifikasi', function () {
            $riwayat = \App\Models\PendaftarBerkas::with(['pendaftar.user'])
                ->where('valid', '!=', 0)
                ->orderBy('tgl_verifikasi', 'desc')
                ->get()
                ->map(function($berkas) {
                    return [
                        'tgl_verifikasi' => $berkas->tgl_verifikasi,
                        'no_pendaftaran' => $berkas->pendaftar->no_pendaftaran ?? '-',
                        'nama' => $berkas->pendaftar->user->nama ?? '-',
                        'status' => $berkas->valid == 1 ? 'Terverifikasi' : 'Ditolak',
                        'catatan' => $berkas->catatan,
                        'verifikator' => 'Verifikator'
                    ];
                });
                
            $stats = [
                'total' => $riwayat->count(),
                'terverifikasi' => $riwayat->where('status', 'Terverifikasi')->count(),
                'ditolak' => $riwayat->where('status', 'Ditolak')->count()
            ];
                
            return response()->json([
                'success' => true,
                'data' => $riwayat,
                'stats' => $stats
            ]);
        });
        
        // Routes untuk verifikasi data menggunakan controller
        Route::get('/data-menunggu', [App\Http\Controllers\VerifikatorController::class, 'getDataMenunggu']);
        Route::get('/detail-data-siswa/{id}', [App\Http\Controllers\VerifikatorController::class, 'getDetailDataSiswa']);
        Route::get('/detail-data-ortu/{id}', [App\Http\Controllers\VerifikatorController::class, 'getDetailDataOrtu']);
        Route::get('/detail-asal-sekolah/{id}', [App\Http\Controllers\VerifikatorController::class, 'getDetailAsalSekolah']);
        Route::post('/verifikasi-data-siswa/{id}', [App\Http\Controllers\VerifikatorController::class, 'verifikasiDataSiswa']);
        Route::post('/verifikasi-data-ortu/{id}', [App\Http\Controllers\VerifikatorController::class, 'verifikasiDataOrtu']);
        Route::post('/verifikasi-asal-sekolah/{id}', [App\Http\Controllers\VerifikatorController::class, 'verifikasiAsalSekolah']);
    });

    // Route untuk kepala sekolah
    Route::prefix('kepsek')->middleware(['auth'])->group(function () {
        Route::get('/statistik-asal-sekolah', function () {
            try {
                // Cek apakah kolom status_sekolah ada
                $hasStatusSekolah = \Schema::hasColumn('pendaftar_asal_sekolah', 'status_sekolah');
                
                // Query dasar
                $baseQuery = \App\Models\PendaftarAsalSekolah::query();
                
                // Data asal sekolah dengan detail
                $selectFields = [
                    'nama_sekolah',
                    'npsn', 
                    'kabupaten',
                    \DB::raw('COUNT(*) as jumlah_pendaftar'),
                    \DB::raw('AVG(COALESCE(nilai_rata, 0)) as rata_rata_nilai')
                ];
                
                $groupByFields = ['nama_sekolah', 'npsn', 'kabupaten'];
                
                if ($hasStatusSekolah) {
                    $selectFields[] = 'status_sekolah';
                    $groupByFields[] = 'status_sekolah';
                }
                
                $asalSekolahData = $baseQuery->select($selectFields)
                    ->groupBy($groupByFields)
                    ->orderBy('jumlah_pendaftar', 'desc')
                    ->get();
                
                // Tambahkan status default jika kolom tidak ada
                if (!$hasStatusSekolah) {
                    $asalSekolahData = $asalSekolahData->map(function($item) {
                        $item->status_sekolah = 'Negeri'; // Default
                        return $item;
                    });
                }

                // Statistik per kabupaten
                $perKabupaten = \App\Models\PendaftarAsalSekolah::select(
                    'kabupaten',
                    \DB::raw('COUNT(*) as jumlah')
                )
                ->groupBy('kabupaten')
                ->orderBy('jumlah', 'desc')
                ->limit(10)
                ->get();

                // Hitung statistik umum
                $totalSekolah = $asalSekolahData->count();
                $sekolahNegeri = $asalSekolahData->where('status_sekolah', 'Negeri')->count();
                $sekolahSwasta = $asalSekolahData->where('status_sekolah', 'Swasta')->count();
                $rataRataNilai = \App\Models\PendaftarAsalSekolah::avg('nilai_rata') ?? 0;

                return response()->json([
                    'success' => true,
                    'data' => [
                        'total_sekolah' => $totalSekolah,
                        'sekolah_negeri' => $sekolahNegeri,
                        'sekolah_swasta' => $sekolahSwasta,
                        'rata_rata_nilai' => round($rataRataNilai, 2),
                        'detail_sekolah' => $asalSekolahData,
                        'per_kabupaten' => $perKabupaten
                    ]
                ]);
            } catch (\Exception $e) {
                \Log::error('Error loading statistik asal sekolah: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat statistik asal sekolah: ' . $e->getMessage()
                ], 500);
            }
        });
        
        Route::get('/dashboard-stats', function () {
            try {
                // Total pendaftar
                $totalPendaftar = \App\Models\Pendaftar::count();
                
                // Terverifikasi (yang sudah bayar dan dikonfirmasi)
                $terverifikasi = \App\Models\Pendaftar::whereNotNull('tgl_verifikasi_payment')->count();
                
                // Menunggu verifikasi
                $menungguVerifikasi = $totalPendaftar - $terverifikasi;
                
                // Total pendapatan
                $totalPendapatan = \App\Models\Pembayaran::where('status', 'Dikonfirmasi')->sum('jumlah');
                
                // Status pembayaran
                $lunas = \App\Models\Pendaftar::whereNotNull('tgl_verifikasi_payment')->count();
                $menungguKonfirmasi = \App\Models\Pembayaran::where('status', 'Menunggu Konfirmasi')->count();
                $belumBayar = $totalPendaftar - $lunas - $menungguKonfirmasi;
                
                // Data per jurusan
                $jurusan = \App\Models\Jurusan::leftJoin('pendaftar', 'jurusan.id', '=', 'pendaftar.jurusan_id')
                    ->select('jurusan.nama', 'jurusan.kuota', \DB::raw('COUNT(pendaftar.id) as pendaftar'))
                    ->groupBy('jurusan.id', 'jurusan.nama', 'jurusan.kuota')
                    ->get();
                    
                // Tren pendaftaran (7 hari terakhir)
                $trend = [];
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $count = \App\Models\Pendaftar::whereDate('tanggal_daftar', $date->toDateString())->count();
                    $trend[] = [
                        'tanggal' => $date->format('d/m'),
                        'jumlah' => $count
                    ];
                }
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'total_pendaftar' => $totalPendaftar,
                        'terverifikasi' => $terverifikasi,
                        'menunggu_verifikasi' => $menungguVerifikasi,
                        'total_pendapatan' => $totalPendapatan,
                        'lunas' => $lunas,
                        'menunggu_konfirmasi' => $menungguKonfirmasi,
                        'belum_bayar' => $belumBayar,
                        'jurusan' => $jurusan,
                        'trend' => $trend
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat statistik dashboard: ' . $e->getMessage()
                ], 500);
            }
        });
    });

    // Route untuk keuangan
    Route::prefix('keuangan')->middleware(['auth'])->group(function () {
        Route::get('/dashboard-stats', function () {
            $pendapatanBulanIni = \App\Models\Pembayaran::where('status', 'Dikonfirmasi')
                ->whereMonth('tanggal_konfirmasi', now()->month)
                ->sum('jumlah');
                
            $menungguKonfirmasi = \App\Models\Pembayaran::where('status', 'Menunggu Konfirmasi')->count();
            
            $totalTerbayar = \App\Models\Pembayaran::where('status', 'Dikonfirmasi')->sum('jumlah');
            
            // Perbaikan: Hitung rata-rata hanya dari pembayaran yang dikonfirmasi dan bukan 0
            $pembayaranTerkonfirmasi = \App\Models\Pembayaran::where('status', 'Dikonfirmasi')
                ->where('jumlah', '>', 0)
                ->get();
            
            $rataRata = $pembayaranTerkonfirmasi->count() > 0 ? 
                $pembayaranTerkonfirmasi->avg('jumlah') : 0;
            
            return response()->json([
                'success' => true,
                'data' => [
                    'pendapatan_bulan_ini' => $pendapatanBulanIni,
                    'menunggu_konfirmasi' => $menungguKonfirmasi,
                    'total_terbayar' => $totalTerbayar,
                    'rata_rata' => round($rataRata, 0) // Bulatkan ke angka bulat
                ]
            ]);
        });
        
        // Manajemen Tagihan
        Route::get('/manajemen-tagihan', function () {
            $pendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'gelombang', 'pembayaran' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($p) {
                $pembayaranTerakhir = $p->pembayaran->first();
                return [
                    'id' => $p->id,
                    'no_pendaftaran' => $p->no_pendaftaran,
                    'nama' => $p->user->nama ?? '-',
                    'jurusan' => $p->jurusan->nama ?? '-',
                    'gelombang' => $p->gelombang->nama ?? '-',
                    'tanggal_daftar' => $p->tanggal_daftar,
                    'biaya_pendaftaran' => $p->gelombang->biaya_daftar ?? 150000,
                    'status_pembayaran' => $pembayaranTerakhir ? $pembayaranTerakhir->status : 'Belum Bayar',
                    'jumlah_bayar' => $pembayaranTerakhir ? $pembayaranTerakhir->jumlah : 0,
                    'tanggal_bayar' => $pembayaranTerakhir ? $pembayaranTerakhir->tanggal_bayar : null,
                    'metode' => $pembayaranTerakhir ? $pembayaranTerakhir->metode : null,
                    'tgl_verifikasi_payment' => $p->tgl_verifikasi_payment
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $pendaftar
            ]);
        });
        
        // Riwayat Tagihan
        Route::get('/riwayat-tagihan', function () {
            $riwayat = \App\Models\Pembayaran::with(['pendaftar.user', 'pendaftar.jurusan'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($p) {
                    return [
                        'id' => $p->id,
                        'no_transaksi' => $p->no_transaksi,
                        'no_pendaftaran' => $p->pendaftar->no_pendaftaran ?? '-',
                        'nama' => $p->pendaftar->user->nama ?? '-',
                        'jurusan' => $p->pendaftar->jurusan->nama ?? '-',
                        'jumlah' => $p->jumlah,
                        'metode' => $p->metode,
                        'status' => $p->status,
                        'tanggal_bayar' => $p->tanggal_bayar,
                        'tanggal_konfirmasi' => $p->tanggal_konfirmasi,
                        'created_at' => $p->created_at
                    ];
                });
                
            $stats = [
                'total_transaksi' => $riwayat->count(),
                'dikonfirmasi' => $riwayat->where('status', 'Dikonfirmasi')->count(),
                'menunggu' => $riwayat->where('status', 'Menunggu Konfirmasi')->count(),
                'ditolak' => $riwayat->where('status', 'Ditolak')->count(),
                'total_nominal' => $riwayat->where('status', 'Dikonfirmasi')->sum('jumlah')
            ];
            
            return response()->json([
                'success' => true,
                'data' => $riwayat,
                'stats' => $stats
            ]);
        });
        
        Route::get('/recent-transactions', function () {
            $transactions = \App\Models\Pembayaran::with(['pendaftar.user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($p) {
                    return [
                        'tanggal_bayar' => $p->tanggal_bayar,
                        'nama_pendaftar' => $p->pendaftar->user->nama ?? '-',
                        'jumlah' => $p->jumlah,
                        'status' => $p->status
                    ];
                });
                
            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        });
        
        Route::get('/pembayaran-menunggu', function () {
            $pembayaran = \App\Models\Pembayaran::with(['pendaftar.user', 'pendaftar.jurusan'])
                ->where('status', 'Menunggu Konfirmasi')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($p) {
                    return [
                        'id' => $p->id,
                        'no_transaksi' => $p->no_transaksi,
                        'nama_pendaftar' => $p->pendaftar->user->nama ?? '-',
                        'jurusan' => $p->pendaftar->jurusan->nama ?? '-',
                        'jumlah' => $p->jumlah,
                        'metode' => $p->metode,
                        'tanggal_bayar' => $p->tanggal_bayar,
                        'status' => $p->status,
                        'bukti_transfer' => $p->bukti_transfer
                    ];
                });
                
            return response()->json([
                'success' => true,
                'data' => $pembayaran
            ]);
        });
        
        Route::get('/detail-pembayaran/{id}', function ($id) {
            $pembayaran = \App\Models\Pembayaran::with(['pendaftar.user', 'pendaftar.jurusan'])
                ->find($id);
                
            if (!$pembayaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pembayaran tidak ditemukan'
                ], 404);
            }
            
            // Debug info untuk bukti transfer
            $buktiInfo = null;
            if ($pembayaran->bukti_transfer) {
                $fullPath = storage_path('app/public/' . $pembayaran->bukti_transfer);
                $buktiInfo = [
                    'raw_path' => $pembayaran->bukti_transfer,
                    'full_storage_path' => $fullPath,
                    'file_exists' => file_exists($fullPath),
                    'file_size' => file_exists($fullPath) ? filesize($fullPath) : 0,
                    'public_url' => '/storage/' . $pembayaran->bukti_transfer
                ];
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $pembayaran->id,
                    'no_transaksi' => $pembayaran->no_transaksi,
                    'nama_pendaftar' => $pembayaran->pendaftar->user->nama ?? '-',
                    'jurusan' => $pembayaran->pendaftar->jurusan->nama ?? '-',
                    'jumlah' => $pembayaran->jumlah,
                    'metode' => $pembayaran->metode,
                    'tanggal_bayar' => $pembayaran->tanggal_bayar,
                    'status' => $pembayaran->status,
                    'bukti_transfer' => $pembayaran->bukti_transfer,
                    'bukti_info' => $buktiInfo
                ]
            ]);
        });
        
        Route::post('/verifikasi-pembayaran/{id}', function (Request $request, $id) {
            try {
                $request->validate([
                    'status' => 'required|in:Dikonfirmasi,Ditolak',
                    'keterangan' => 'nullable|string|max:500'
                ]);
                
                $pembayaran = \App\Models\Pembayaran::find($id);
                
                if (!$pembayaran) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pembayaran tidak ditemukan'
                    ], 404);
                }
                
                $pembayaran->status = $request->status;
                if ($request->status === 'Dikonfirmasi') {
                    $pembayaran->tanggal_konfirmasi = now();
                }
                $pembayaran->save();
                
                // Update tgl_verifikasi_payment di pendaftar hanya jika dikonfirmasi
                if ($request->status === 'Dikonfirmasi') {
                    $pembayaran->pendaftar->update([
                        'tgl_verifikasi_payment' => now()
                    ]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil ' . ($request->status === 'Dikonfirmasi' ? 'dikonfirmasi' : 'ditolak')
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memverifikasi pembayaran: ' . $e->getMessage()
                ], 500);
            }
        });
        
        Route::get('/detail-tagihan/{noPendaftaran}', function ($noPendaftaran) {
            try {
                $pendaftar = \App\Models\Pendaftar::with(['user', 'jurusan', 'gelombang', 'pembayaran' => function($query) {
                    $query->orderBy('created_at', 'desc');
                }])
                ->where('no_pendaftaran', $noPendaftaran)
                ->first();
                
                if (!$pendaftar) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pendaftar tidak ditemukan'
                    ], 404);
                }
                
                $pembayaranTerakhir = $pendaftar->pembayaran->first();
                
                $data = [
                    'no_pendaftaran' => $pendaftar->no_pendaftaran,
                    'nama' => $pendaftar->user->nama ?? '-',
                    'jurusan' => $pendaftar->jurusan->nama ?? '-',
                    'gelombang' => $pendaftar->gelombang->nama ?? '-',
                    'tanggal_daftar' => $pendaftar->tanggal_daftar,
                    'biaya_pendaftaran' => $pendaftar->gelombang->biaya_daftar ?? 150000,
                    'tgl_verifikasi_payment' => $pendaftar->tgl_verifikasi_payment,
                    'pembayaran' => $pembayaranTerakhir ? [
                        'no_transaksi' => $pembayaranTerakhir->no_transaksi,
                        'jumlah' => $pembayaranTerakhir->jumlah,
                        'metode' => $pembayaranTerakhir->metode,
                        'tanggal_bayar' => $pembayaranTerakhir->tanggal_bayar,
                        'status' => $pembayaranTerakhir->status,
                        'bukti_transfer' => $pembayaranTerakhir->bukti_transfer,
                        'tanggal_konfirmasi' => $pembayaranTerakhir->tanggal_konfirmasi,
                        'tgl_verifikasi_payment' => $pembayaranTerakhir->tanggal_konfirmasi
                    ] : null
                ];
                
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat detail tagihan: ' . $e->getMessage()
                ], 500);
            }
        });
        
        Route::get('/laporan-stats', function () {
            try {
                $today = now()->toDateString();
                $currentMonth = now()->month;
                $currentYear = now()->year;
                
                // Pendapatan harian
                $pendapatanHarian = \App\Models\Pembayaran::where('status', 'Dikonfirmasi')
                    ->whereDate('tanggal_konfirmasi', $today)
                    ->sum('jumlah');
                    
                // Transaksi harian
                $transaksiHarian = \App\Models\Pembayaran::where('status', 'Dikonfirmasi')
                    ->whereDate('tanggal_konfirmasi', $today)
                    ->count();
                    
                // Pendapatan bulanan
                $pendapatanBulanan = \App\Models\Pembayaran::where('status', 'Dikonfirmasi')
                    ->whereMonth('tanggal_konfirmasi', $currentMonth)
                    ->whereYear('tanggal_konfirmasi', $currentYear)
                    ->sum('jumlah');
                    
                // Pendapatan per jurusan
                $pendapatanPerJurusan = \App\Models\Jurusan::leftJoin('pendaftar', 'jurusan.id', '=', 'pendaftar.jurusan_id')
                    ->leftJoin('pembayaran', function($join) {
                        $join->on('pendaftar.id', '=', 'pembayaran.pendaftar_id')
                             ->where('pembayaran.status', '=', 'Dikonfirmasi');
                    })
                    ->select('jurusan.nama', \DB::raw('COALESCE(SUM(pembayaran.jumlah), 0) as total_pendapatan'))
                    ->groupBy('jurusan.id', 'jurusan.nama')
                    ->get();
                    
                // Metode pembayaran
                $metodePembayaran = \App\Models\Pembayaran::where('status', 'Dikonfirmasi')
                    ->select('metode', \DB::raw('COUNT(*) as jumlah'))
                    ->groupBy('metode')
                    ->get();
                    
                // Status tagihan
                $totalPendaftar = \App\Models\Pendaftar::count();
                $lunas = \App\Models\Pendaftar::whereNotNull('tgl_verifikasi_payment')->count();
                $belumLunas = $totalPendaftar - $lunas;
                
                $statusTagihan = [
                    'Lunas' => $lunas,
                    'Belum Lunas' => $belumLunas
                ];
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'pendapatan_harian' => $pendapatanHarian,
                        'transaksi_harian' => $transaksiHarian,
                        'pendapatan_bulanan' => $pendapatanBulanan,
                        'pendapatan_per_jurusan' => $pendapatanPerJurusan,
                        'metode_pembayaran' => $metodePembayaran,
                        'status_tagihan' => $statusTagihan
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat statistik laporan: ' . $e->getMessage()
                ], 500);
            }
        });
    });

    // Route untuk fitur sidebar pendaftar
    Route::prefix('pendaftar')->middleware(['auth'])->group(function () {
        // Data Siswa
        Route::get('/data-siswa', function () {
            try {
                $user = Auth::user();
                $pendaftar = \App\Models\Pendaftar::where('user_id', $user->id)->first();
                $dataSiswa = $pendaftar ? $pendaftar->dataSiswa : null;
                
                // Jika ada data siswa, tambahkan jurusan_id dari pendaftar
                if ($dataSiswa && $pendaftar) {
                    $dataSiswa->jurusan_id = $pendaftar->jurusan_id;
                }
                
                \Log::info('Data siswa request:', [
                    'user_id' => $user->id,
                    'pendaftar_exists' => $pendaftar ? true : false,
                    'data_siswa_exists' => $dataSiswa ? true : false,
                    'jurusan_id' => $pendaftar ? $pendaftar->jurusan_id : null
                ]);
                
                return response()->json(['success' => true, 'data' => $dataSiswa]);
            } catch (\Exception $e) {
                \Log::error('Error loading data siswa:', ['error' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        });

        Route::post('/simpan-data-siswa', function (Request $request) {
            try {
                \Log::info('Saving student data request:', $request->all());
                
                $user = Auth::user();
                $gelombang = \App\Models\Gelombang::where('is_aktif', 1)->first();
                
                // Validasi jurusan_id
                $request->validate([
                    'jurusan_id' => 'required|exists:jurusan,id'
                ]);
                
                // Create or get pendaftar
                $pendaftar = \App\Models\Pendaftar::where('user_id', $user->id)->first();
                
                if (!$pendaftar) {
                    // Generate nomor pendaftaran yang unik
                    do {
                        $noPendaftaran = 'PPDB' . date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    } while (\App\Models\Pendaftar::where('no_pendaftaran', $noPendaftaran)->exists());
                    
                    $pendaftar = \App\Models\Pendaftar::create([
                        'user_id' => $user->id,
                        'tanggal_daftar' => now(),
                        'no_pendaftaran' => $noPendaftaran,
                        'gelombang_id' => $gelombang ? $gelombang->id : 1,
                        'jurusan_id' => $request->jurusan_id,
                        'status' => 'Menunggu'
                    ]);
                }
                
                // Update jurusan jika pendaftar sudah ada
                if (!$pendaftar->wasRecentlyCreated) {
                    $pendaftar->update(['jurusan_id' => $request->jurusan_id]);
                }

                \Log::info('Pendaftar created/found:', ['pendaftar_id' => $pendaftar->id]);

                $data = [
                    'nik' => $request->nik,
                    'nish' => $request->nisn,
                    'nama' => $request->nama_lengkap,
                    'jk' => $request->jenis_kelamin,
                    'tmp_lahir' => $request->tempat_lahir,
                    'tgl_lahir' => $request->tanggal_lahir,
                    'agama' => $request->agama,
                    'alamat' => $request->alamat,
                    'wilayah_id' => $request->wilayah_id,
                    'nomor_hp' => $request->nomor_hp,
                    'email' => $request->email,
                    'status_verifikasi' => 0
                ];

                $dataSiswa = \App\Models\PendaftarDataSiswa::updateOrCreate(
                    ['pendaftar_id' => $pendaftar->id],
                    $data
                );

                \Log::info('Student data saved:', ['data_siswa_id' => $dataSiswa->pendaftar_id]);

                return response()->json([
                    'success' => true, 
                    'message' => 'Data siswa berhasil disimpan dan menunggu verifikasi',
                    'data' => $dataSiswa
                ]);
            } catch (\Exception $e) {
                \Log::error('Error saving student data:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        });

        // Data Orang Tua
        Route::get('/data-ortu', function () {
            try {
                $user = Auth::user();
                $pendaftar = \App\Models\Pendaftar::where('user_id', $user->id)->first();
                $dataOrtu = $pendaftar ? $pendaftar->dataOrtu : null;
                
                \Log::info('Data ortu request:', [
                    'user_id' => $user->id,
                    'pendaftar_exists' => $pendaftar ? true : false,
                    'data_ortu_exists' => $dataOrtu ? true : false
                ]);
                
                return response()->json(['success' => true, 'data' => $dataOrtu]);
            } catch (\Exception $e) {
                \Log::error('Error loading data ortu:', ['error' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        });

        Route::post('/simpan-data-ortu', function (Request $request) {
            try {
                \Log::info('Saving parent data request:', $request->all());
                
                $user = Auth::user();
                $gelombang = \App\Models\Gelombang::where('is_aktif', 1)->first();
                $jurusan = \App\Models\Jurusan::first();
                
                $pendaftar = \App\Models\Pendaftar::where('user_id', $user->id)->first();
                
                if (!$pendaftar) {
                    // Generate nomor pendaftaran yang unik
                    do {
                        $noPendaftaran = 'PPDB' . date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    } while (\App\Models\Pendaftar::where('no_pendaftaran', $noPendaftaran)->exists());
                    
                    $pendaftar = \App\Models\Pendaftar::create([
                        'user_id' => $user->id,
                        'tanggal_daftar' => now(),
                        'no_pendaftaran' => $noPendaftaran,
                        'gelombang_id' => $gelombang ? $gelombang->id : 1,
                        'jurusan_id' => $jurusan ? $jurusan->id : 1,
                        'status' => 'Menunggu'
                    ]);
                }

                \Log::info('Pendaftar created/found for parent data:', ['pendaftar_id' => $pendaftar->id]);

                $dataOrtu = \App\Models\PendaftarDataOrtu::updateOrCreate(
                    ['pendaftar_id' => $pendaftar->id],
                    [
                        'nama_ayah' => $request->nama_ayah,
                        'pekerjaan_ayah' => $request->pekerjaan_ayah,
                        'penghasilan_ayah' => $request->penghasilan_ayah,
                        'hp_ayah' => $request->hp_ayah,
                        'nama_ibu' => $request->nama_ibu,
                        'pekerjaan_ibu' => $request->pekerjaan_ibu,
                        'penghasilan_ibu' => $request->penghasilan_ibu,
                        'hp_ibu' => $request->hp_ibu,
                        'status_verifikasi' => 0
                    ]
                );

                \Log::info('Parent data saved:', ['data_ortu_id' => $dataOrtu->pendaftar_id]);

                return response()->json([
                    'success' => true, 
                    'message' => 'Data orang tua berhasil disimpan dan menunggu verifikasi',
                    'data' => $dataOrtu
                ]);
            } catch (\Exception $e) {
                \Log::error('Error saving parent data:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        });

        // Data Asal Sekolah
        Route::get('/data-asal-sekolah', function () {
            try {
                $user = Auth::user();
                $pendaftar = \App\Models\Pendaftar::where('user_id', $user->id)->first();
                $asalSekolah = $pendaftar ? $pendaftar->asalSekolah : null;
                
                \Log::info('Data asal sekolah request:', [
                    'user_id' => $user->id,
                    'pendaftar_exists' => $pendaftar ? true : false,
                    'asal_sekolah_exists' => $asalSekolah ? true : false
                ]);
                
                return response()->json(['success' => true, 'data' => $asalSekolah]);
            } catch (\Exception $e) {
                \Log::error('Error loading data asal sekolah:', ['error' => $e->getMessage()]);
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
        });

        Route::post('/simpan-asal-sekolah', function (Request $request) {
            try {
                \Log::info('Saving school data request:', $request->all());
                
                $user = Auth::user();
                $gelombang = \App\Models\Gelombang::where('is_aktif', 1)->first();
                $jurusan = \App\Models\Jurusan::first();
                
                $pendaftar = \App\Models\Pendaftar::where('user_id', $user->id)->first();
                
                if (!$pendaftar) {
                    // Generate nomor pendaftaran yang unik
                    do {
                        $noPendaftaran = 'PPDB' . date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    } while (\App\Models\Pendaftar::where('no_pendaftaran', $noPendaftaran)->exists());
                    
                    $pendaftar = \App\Models\Pendaftar::create([
                        'user_id' => $user->id,
                        'tanggal_daftar' => now(),
                        'no_pendaftaran' => $noPendaftaran,
                        'gelombang_id' => $gelombang ? $gelombang->id : 1,
                        'jurusan_id' => $jurusan ? $jurusan->id : 1,
                        'status' => 'Menunggu'
                    ]);
                }

                \Log::info('Pendaftar created/found for school data:', ['pendaftar_id' => $pendaftar->id]);

                $data = [
                    'npsn' => $request->npsn_sekolah,
                    'nama_sekolah' => $request->nama_sekolah,
                    'status_sekolah' => $request->status_sekolah,
                    'alamat_sekolah' => $request->alamat_sekolah,
                    'kabupaten' => $request->kabupaten,
                    'tahun_lulus' => $request->tahun_lulus,
                    'nilai_rata' => $request->nilai_rata,
                    'status_verifikasi' => 0
                ];

                $asalSekolah = \App\Models\PendaftarAsalSekolah::updateOrCreate(
                    ['pendaftar_id' => $pendaftar->id],
                    $data
                );

                \Log::info('School data saved:', ['asal_sekolah_id' => $asalSekolah->pendaftar_id]);

                return response()->json([
                    'success' => true, 
                    'message' => 'Data asal sekolah berhasil disimpan dan menunggu verifikasi',
                    'data' => $asalSekolah
                ]);
            } catch (\Exception $e) {
                \Log::error('Error saving school data:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal menyimpan data: ' . $e->getMessage()
                ], 500);
            }
        });

        // Upload Berkas
        Route::post('/upload-berkas', [App\Http\Controllers\FileUploadController::class, 'upload'])->name('pendaftar.upload-berkas');

        // Status Berkas
        Route::get('/berkas-status', function () {
            $user = Auth::user();
            $pendaftar = \App\Models\Pendaftar::where('user_id', $user->id)->first();
            
            if (!$pendaftar) {
                return response()->json([]);
            }

            $berkas = \App\Models\PendaftarBerkas::where('pendaftar_id', $pendaftar->id)->get();
            return response()->json($berkas);
        });
        
        // Pengumuman Real-time
        Route::get('/pengumuman', function () {
            $pengumuman = collect();
            try {
                if (\Schema::hasTable('pengumuman')) {
                    $pengumuman = \App\Models\Pengumuman::where('is_aktif', true)
                        ->orderBy('prioritas', 'desc')
                        ->orderBy('tanggal_posting', 'desc')
                        ->get();
                }
            } catch (\Exception $e) {
                $pengumuman = collect();
            }
            
            return response()->json([
                'success' => true,
                'data' => $pengumuman
            ]);
        });
        
        // Status Pendaftaran Real-time
        Route::get('/status-pendaftaran', function () {
            try {
                $user = Auth::user();
                \Log::info('Status pendaftaran request for user:', ['user_id' => $user->id]);
                
                $pendaftar = \App\Models\Pendaftar::with(['berkas', 'dataSiswa', 'dataOrtu', 'asalSekolah', 'pembayaran', 'jurusan'])
                    ->where('user_id', $user->id)
                    ->first();
                
                if (!$pendaftar) {
                    \Log::info('No pendaftar found for user:', ['user_id' => $user->id]);
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'status' => 'Belum Daftar',
                            'tgl_verifikasi_payment' => null,
                            'berkas_verified' => 0,
                            'berkas_total' => 0,
                            'has_data_siswa' => false,
                            'pembayaran_status' => null,
                            'data_siswa_verified' => null,
                            'data_ortu_verified' => null,
                            'asal_sekolah_verified' => null
                        ]
                    ]);
                }
                
                $berkasVerified = $pendaftar->berkas->where('valid', 1)->count();
                $berkasTotal = $pendaftar->berkas->count();
                $pembayaranTerakhir = $pendaftar->pembayaran->sortByDesc('created_at')->first();
                
                // Update status pendaftar berdasarkan pembayaran terverifikasi
                $statusPendaftar = $pendaftar->status ?: 'Menunggu Verifikasi';
                // PERBAIKAN: Jangan otomatis update status, biarkan keuangan yang mengkonfirmasi
                if ($pembayaranTerakhir && $pembayaranTerakhir->status === 'Dikonfirmasi' && !$pendaftar->tgl_verifikasi_payment) {
                    $pendaftar->update(['tgl_verifikasi_payment' => $pembayaranTerakhir->tanggal_konfirmasi]);
                }
                
                // Get verification status for each data type
                $dataSiswaStatus = $pendaftar->dataSiswa ? $pendaftar->dataSiswa->status_verifikasi : null;
                $dataOrtuStatus = $pendaftar->dataOrtu ? $pendaftar->dataOrtu->status_verifikasi : null;
                $asalSekolahStatus = $pendaftar->asalSekolah ? $pendaftar->asalSekolah->status_verifikasi : null;
                
                $responseData = [
                    'status' => $statusPendaftar,
                    'tgl_verifikasi_payment' => $pendaftar->tgl_verifikasi_payment,
                    'berkas_verified' => $berkasVerified,
                    'berkas_total' => $berkasTotal,
                    'has_data_siswa' => $pendaftar->dataSiswa ? true : false,
                    'no_pendaftaran' => $pendaftar->no_pendaftaran,
                    'tanggal_daftar' => $pendaftar->tanggal_daftar,
                    'data_siswa_verified' => $dataSiswaStatus,
                    'data_ortu_verified' => $dataOrtuStatus,
                    'asal_sekolah_verified' => $asalSekolahStatus,
                    'pembayaran_status' => $pembayaranTerakhir ? $pembayaranTerakhir->status : null,
                    'pembayaran_jumlah' => $pembayaranTerakhir ? $pembayaranTerakhir->jumlah : null,
                    'jurusan_nama' => $pendaftar->jurusan ? $pendaftar->jurusan->nama : null
                ];
                
                \Log::info('Status pendaftaran response:', $responseData);
                
                return response()->json([
                    'success' => true,
                    'data' => $responseData
                ]);
            } catch (\Exception $e) {
                \Log::error('Error in status-pendaftaran:', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Error loading status: ' . $e->getMessage()
                ], 500);
            }
        });
        
        // Cek status pembayaran
        Route::get('/cek-status-pembayaran', function () {
            try {
                $user = Auth::user();
                $pendaftar = \App\Models\Pendaftar::where('user_id', $user->id)->first();
                
                if (!$pendaftar) {
                    return response()->json([
                        'success' => true,
                        'data' => [
                            'status' => 'belum_bayar',
                            'is_lunas' => false,
                            'pembayaran_status' => null
                        ]
                    ]);
                }
                
                $pembayaranTerakhir = $pendaftar->pembayaran->sortByDesc('created_at')->first();
                
                // PERBAIKAN: Hanya anggap lunas jika pembayaran benar-benar dikonfirmasi oleh keuangan
                $isLunas = $pembayaranTerakhir && $pembayaranTerakhir->status === 'Dikonfirmasi';
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'status' => $isLunas ? 'lunas' : ($pembayaranTerakhir ? $pembayaranTerakhir->status : 'belum_bayar'),
                        'is_lunas' => $isLunas,
                        'pembayaran_status' => $pembayaranTerakhir ? $pembayaranTerakhir->status : null,
                        'tgl_verifikasi' => $pembayaranTerakhir && $pembayaranTerakhir->status === 'Dikonfirmasi' ? $pembayaranTerakhir->tanggal_konfirmasi : null,
                        'jumlah' => $pembayaranTerakhir ? $pembayaranTerakhir->jumlah : null
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ]);
            }
        });
        
        // Submit pembayaran
        Route::post('/submit-pembayaran', function (Request $request) {
            try {
                $user = Auth::user();
                $pendaftar = \App\Models\Pendaftar::where('user_id', $user->id)->first();
                
                if (!$pendaftar) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pendaftar tidak ditemukan'
                    ]);
                }
                
                // Cek apakah sudah lunas
                $pembayaranTerakhir = $pendaftar->pembayaran->sortByDesc('created_at')->first();
                if ($pendaftar->tgl_verifikasi_payment || 
                    ($pembayaranTerakhir && $pembayaranTerakhir->status === 'Dikonfirmasi')) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pembayaran sudah lunas'
                    ]);
                }
                
                // Cek apakah masih ada pembayaran yang menunggu konfirmasi
                if ($pembayaranTerakhir && $pembayaranTerakhir->status === 'Menunggu Konfirmasi') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Masih ada pembayaran yang menunggu konfirmasi'
                    ]);
                }
                
                // Jika pembayaran sebelumnya ditolak, izinkan bayar ulang
                if ($pembayaranTerakhir && $pembayaranTerakhir->status === 'Ditolak') {
                    \Log::info('Pembayaran ulang untuk pendaftar: ' . $pendaftar->id . ' (pembayaran sebelumnya ditolak)');
                }
                
                $request->validate([
                    'jumlah' => 'required|numeric|min:0',
                    'metode' => 'required|in:Transfer Bank,Tunai,Virtual Account,QRIS',
                    'bukti_transfer' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
                ]);
                
                $noTransaksi = 'TRX' . date('Y') . str_pad(\App\Models\Pembayaran::count() + 1, 6, '0', STR_PAD_LEFT);
                
                // Upload bukti transfer
                $buktiPath = null;
                if ($request->hasFile('bukti_transfer')) {
                    $file = $request->file('bukti_transfer');
                    $fileName = time() . '_' . $pendaftar->id . '_' . $file->getClientOriginalName();
                    $buktiPath = $file->storeAs('bukti_transfer', $fileName, 'public');
                }
                
                $pembayaran = \App\Models\Pembayaran::create([
                    'no_transaksi' => $noTransaksi,
                    'pendaftar_id' => $pendaftar->id,
                    'jumlah' => $request->jumlah,
                    'metode' => $request->metode,
                    'bukti_transfer' => $buktiPath,
                    'tanggal_bayar' => now(),
                    'status' => 'Menunggu Konfirmasi'
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Pembayaran berhasil disubmit dan menunggu konfirmasi dari bagian keuangan',
                    'data' => $pembayaran
                ]);
            } catch (\Exception $e) {
                \Log::error('Error submit pembayaran: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal submit pembayaran: ' . $e->getMessage()
                ]);
            }
        });
    });

    // ==================== LEGACY DASHBOARD ROUTE (Redirect berdasarkan role) ====================
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return redirectToDashboard($user->role);
    })->name('dashboard');

    // ==================== LOGOUT ROUTE ====================
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil!');
    })->name('logout');

});

// Include routes keuangan
require __DIR__.'/keuangan.php';

// ==================== HELPER FUNCTION ====================
function redirectToDashboard($role)
{
    switch ($role) {
        case 'admin':
            return redirect('/admin/dashboard');
        case 'kepsek':
            return redirect('/kepsek/dashboard');
        case 'verifikator_adm':
            return redirect('/verifikator/dashboard');
        case 'keuangan':
            return redirect('/keuangan/dashboard');
        case 'pendaftar':
            return redirect('/pendaftar/dashboard');
        default:
            return redirect('/')->withErrors(['message' => 'Role tidak dikenali.']);
    }
}

// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});