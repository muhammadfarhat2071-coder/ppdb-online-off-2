<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verifikasi OTP - SMK Bakti Nusantara 666</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .otp-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .otp-header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .otp-input {
            width: 60px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            margin: 0 5px;
        }

        .otp-input:focus {
            border-color: #1e3c72;
            box-shadow: 0 0 0 0.2rem rgba(30, 60, 114, 0.25);
        }

        .btn-verify {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="otp-container">
                    <div class="otp-header">
                        <h3><i class="fas fa-shield-alt me-2"></i>Verifikasi OTP</h3>
                        <p class="mb-0">Masukkan kode verifikasi</p>
                    </div>

                    <div class="p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-envelope-open-text fa-3x text-primary mb-3"></i>
                            <p>Kode OTP telah dikirim ke:</p>
                            <strong>{{ $email }}</strong>
                            <p class="text-muted small mt-2">Kode berlaku selama 5 menit</p>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif
                        
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        <div class="alert alert-warning">
                            <small><i class="fas fa-exclamation-triangle"></i> <strong>Periksa folder Spam/Junk</strong> jika email tidak masuk ke Inbox</small>
                        </div>

                        <form method="POST" action="/verify-otp" id="otpForm">
                            @csrf
                            <input type="hidden" name="email" value="{{ $email }}">
                            
                            <div class="text-center mb-4">
                                <div class="d-flex justify-content-center">
                                    <input type="text" class="otp-input" maxlength="1" id="otp1">
                                    <input type="text" class="otp-input" maxlength="1" id="otp2">
                                    <input type="text" class="otp-input" maxlength="1" id="otp3">
                                    <input type="text" class="otp-input" maxlength="1" id="otp4">
                                    <input type="text" class="otp-input" maxlength="1" id="otp5">
                                    <input type="text" class="otp-input" maxlength="1" id="otp6">
                                </div>
                                <input type="hidden" name="otp" id="otpValue">
                            </div>

                            <button type="submit" class="btn btn-verify text-white w-100 py-2 mb-3" id="verifyBtn">
                                <i class="fas fa-check me-2"></i>Verifikasi
                            </button>
                        </form>

                        <div class="text-center">
                            <p class="mb-2">Tidak menerima kode?</p>
                            <form method="POST" action="/resend-otp" class="d-inline">
                                @csrf
                                <input type="hidden" name="email" value="{{ $email }}">
                                <button type="submit" class="btn btn-link p-0 text-decoration-none">Kirim ulang OTP</button>
                            </form>
                            <br>
                            <a href="/register" class="text-decoration-none small">Kembali ke registrasi</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set CSRF token for AJAX requests
        document.querySelector('meta[name="csrf-token"]').setAttribute('content', '{{ csrf_token() }}');
        // OTP Input handling
        const otpInputs = document.querySelectorAll('.otp-input');
        const otpValue = document.getElementById('otpValue');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function() {
                if (this.value.length === 1) {
                    if (index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                }
                updateOtpValue();
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                }
            });
        });

        function updateOtpValue() {
            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });
            otpValue.value = otp;
        }

        // Handle form submission
        document.getElementById('otpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('verifyBtn');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memverifikasi...';
            submitBtn.disabled = true;
            
            // Get fresh CSRF token
            fetch('/verify-otp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: document.querySelector('input[name="email"]').value,
                    otp: otpValue.value
                })
            })
            .then(response => {
                if (response.ok) {
                    // Success - redirect to dashboard
                    window.location.href = '/pendaftar/dashboard';
                } else {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Verifikasi gagal');
                    });
                }
            })
            .catch(error => {
                // Reset button state
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                // Show error
                alert('Error: ' + error.message);
                
                // Clear OTP inputs
                otpInputs.forEach(input => input.value = '');
                otpInputs[0].focus();
                updateOtpValue();
            });
        });
        
        // Auto submit when all inputs filled
        document.getElementById('otpForm').addEventListener('input', function() {
            if (otpValue.value.length === 6) {
                setTimeout(() => {
                    document.getElementById('otpForm').dispatchEvent(new Event('submit'));
                }, 500);
            }
        });
    </script>
</body>

</html>