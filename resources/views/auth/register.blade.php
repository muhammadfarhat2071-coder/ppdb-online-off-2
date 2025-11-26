<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - SMK Bakti Nusantara 666</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .register-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .register-body {
            padding: 2rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #1e3c72;
            box-shadow: 0 0 0 0.2rem rgba(30, 60, 114, 0.25);
        }

        .btn-register {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }

        .input-group-text {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-right: none;
        }

        .progress-bar {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="register-container">
                    <!-- Header -->
                    <div class="register-header">
                        <h3><i class="fas fa-school me-2"></i>SMK BAKTI NUSANTARA 666</h3>
                        <p class="mb-0">Pendaftaran Peserta Didik Baru</p>
                    </div>

                    <!-- Body -->
                    <div class="register-body">
                        <h4 class="text-center mb-4">Buat Akun Baru</h4>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <small>Langkah 1 dari 4</small>
                                <small>25%</small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" style="width: 25%"></div>
                            </div>
                            <small class="text-muted">Registrasi Akun → Data Diri → Berkas → Pembayaran</small>
                        </div>

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- Register Form -->
                        <form method="POST" action="/register" id="registerForm">
                            @csrf
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nama" name="nama"
                                        value="{{ old('nama') }}" required placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email') }}" required placeholder="email@example.com">
                                </div>
                                <div class="form-text">Pastikan email aktif untuk verifikasi</div>
                            </div>
                            <div class="mb-3">
                                <label for="hp" class="form-label">Nomor HP/WhatsApp <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="form-control" id="hp" name="hp" value="{{ old('hp') }}"
                                        required placeholder="081234567890">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="wilayah_id" class="form-label">Wilayah <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <select class="form-control" id="wilayah_id" name="wilayah_id" required>
                                        <option value="">Pilih Wilayah</option>
                                        @if(isset($wilayah) && $wilayah->count() > 0)
                                            @foreach($wilayah as $w)
                                                <option value="{{ $w->id }}" {{ old('wilayah_id') == $w->id ? 'selected' : '' }}>{{ $w->nama_wilayah }}</option>
                                            @endforeach
                                        @else
                                            <option value="1">Jakarta</option>
                                            <option value="2">Bandung</option>
                                            <option value="3">Surabaya</option>
                                            <option value="4">Medan</option>
                                            <option value="5">Semarang</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="asal_sekolah" class="form-label">Asal Sekolah <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-school"></i></span>
                                    <input type="text" class="form-control" id="asal_sekolah" name="asal_sekolah"
                                        value="{{ old('asal_sekolah') }}" required placeholder="Nama sekolah asal">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required
                                        placeholder="Minimal 6 karakter">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required placeholder="Ulangi password">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-register text-white w-100 py-2 mb-3">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="mb-0">Sudah punya akun?
                                <a href="/login" class="text-decoration-none fw-bold">Login di sini</a>
                            </p>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded">
                            <h6><i class="fas fa-shield-alt me-2"></i>Keamanan Data</h6>
                            <small class="text-muted">Data Anda dilindungi dan tidak akan dibagikan kepada pihak
                                lain.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength check
        document.getElementById('password').addEventListener('input', function () {
            const password = this.value;
            const strengthText = document.createElement('small');
            strengthText.className = 'form-text';

            if (password.length < 6) {
                strengthText.innerHTML = '<i class="fas fa-times text-danger"></i> Password terlalu pendek';
            } else if (password.length < 8) {
                strengthText.innerHTML = '<i class="fas fa-check text-warning"></i> Password cukup';
            } else {
                strengthText.innerHTML = '<i class="fas fa-check-double text-success"></i> Password kuat';
            }

            const existingText = this.parentNode.parentNode.querySelector('.form-text');
            if (existingText && existingText.textContent.includes('Password')) {
                existingText.remove();
            }
            this.parentNode.parentNode.appendChild(strengthText);
        });
    </script>
</body>

</html>