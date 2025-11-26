# Integrasi Database Jurusan - PPDB Online

## Perubahan yang Telah Dilakukan

### 1. Controller Baru
**File: `app/Http/Controllers/WelcomeController.php`**
- Mengambil data jurusan dari database
- Mengirim data ke view welcome

### 2. Route Update
**File: `routes/web.php`**
- Welcome route menggunakan WelcomeController
- Dashboard admin mengambil statistik dari database

### 3. View Welcome
**File: `resources/views/welcome.blade.php`**
- Badge jurusan dinamis dari database
- Menggunakan loop Blade untuk menampilkan data

### 4. Dashboard Admin
**File: `resources/views/dashboard-admin.blade.php`**
- Load jurusan via AJAX dari API
- CRUD jurusan tersimpan ke database MySQL
- Statistik real-time dari database

## Cara Menggunakan

### Setup Awal
```bash
# 1. Buat database
CREATE DATABASE ppdb_online;

# 2. Jalankan migration
php artisan migrate

# 3. Isi data awal
php artisan db:seed --class=JurusanSeeder

# 4. Jalankan server
php artisan serve
```

### Akses Aplikasi
- Welcome: http://localhost:8000
- Dashboard Admin: http://localhost:8000/admin/dashboard

## Fitur Terintegrasi

✅ Halaman welcome menampilkan jurusan dari database
✅ Dashboard admin CRUD jurusan ke database
✅ API endpoints untuk operasi jurusan
✅ Statistik dashboard dari database real-time
