<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; padding: 30px; text-align: center; }
        .content { padding: 30px; }
        .otp-box { background: #f8f9fa; border: 2px dashed #1e3c72; border-radius: 10px; padding: 20px; text-align: center; margin: 20px 0; }
        .otp-code { font-size: 32px; font-weight: bold; color: #1e3c72; letter-spacing: 5px; }
        .footer { background: #f8f9fa; padding: 20px; text-align: center; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏫 SMK Bakti Nusantara 666</h1>
            <p>Kode Verifikasi OTP</p>
        </div>
        
        <div class="content">
            <h2>Halo{{ $nama ? ', ' . $nama : '' }}!</h2>
            <p>Terima kasih telah mendaftar di SMK Bakti Nusantara 666. Untuk melanjutkan proses registrasi, silakan masukkan kode OTP berikut:</p>
            
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
                <p style="margin: 10px 0 0 0; color: #666;">Kode berlaku selama 5 menit</p>
            </div>
            
            <p><strong>Penting:</strong></p>
            <ul>
                <li>Jangan bagikan kode ini kepada siapapun</li>
                <li>Kode akan kadaluarsa dalam 5 menit</li>
                <li>Jika Anda tidak melakukan registrasi, abaikan email ini</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim otomatis, mohon tidak membalas.</p>
            <p>&copy; {{ date('Y') }} SMK Bakti Nusantara 666. All rights reserved.</p>
        </div>
    </div>
</body>
</html>