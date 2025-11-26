# Setup Database PPDB Online

## Langkah-langkah Setup Database

### 1. Buat Database MySQL
```sql
CREATE DATABASE ppdb_online;
```

### 2. Konfigurasi File .env
Pastikan file `.env` sudah dikonfigurasi dengan benar:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ppdb_online
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Jalankan Migration
```bash
php artisan migrate
```

### 4. Jalankan Seeder untuk Data Awal
```bash
php artisan db:seed --class=JurusanSeeder
```

### 5. Jalankan Server
```bash
php artisan serve
```

## Fitur yang Sudah Terhubung dengan Database

### 1. Halaman Welcome (/)
- Menampilkan badge jurusan dari database MySQL
- Data diambil melalui WelcomeController

### 2. Dashboard Admin (/admin/dashboard)
- Statistik total jurusan dari database
- Manajemen CRUD jurusan:
  - Tambah jurusan baru
  - Edit jurusan
  - Hapus jurusan
  - Lihat daftar jurusan

### 3. API Endpoints
- GET `/api/jurusan` - Mengambil semua data jurusan
- POST `/api/jurusan` - Menambah jurusan baru
- GET `/api/jurusan/{id}` - Mengambil detail jurusan
- PUT `/api/jurusan/{id}` - Update jurusan
- DELETE `/api/jurusan/{id}` - Hapus jurusan

## Struktur Tabel Jurusan

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | BIGINT | Primary Key |
| kode | VARCHAR(10) | Kode jurusan (UNIQUE) |
| nama | VARCHAR(100) | Nama jurusan |
| kuota | INTEGER | Kuota siswa |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu diupdate |

## Data Jurusan Default

Setelah menjalankan seeder, akan ada 5 jurusan:
1. PPLG - Pengembangan Perangkat Lunak dan Gim (Kuota: 40)
2. AK - Akuntansi (Kuota: 35)
3. DKV - Desain Komunikasi Visual (Kuota: 30)
4. PM - Pemasaran (Kuota: 35)
5. ANM - Animasi (Kuota: 25)

## Testing

### Test di Halaman Welcome
1. Buka browser: `http://localhost:8000`
2. Scroll ke bagian "Gelombang Pendaftaran"
3. Lihat badge jurusan yang muncul dari database

### Test di Dashboard Admin
1. Login sebagai admin
2. Buka menu "Jurusan"
3. Coba tambah, edit, atau hapus jurusan
4. Data akan tersimpan di database MySQL

## Troubleshooting

### Error: SQLSTATE[HY000] [1049] Unknown database
- Pastikan database `ppdb_online` sudah dibuat
- Jalankan: `CREATE DATABASE ppdb_online;`

### Error: SQLSTATE[42S02]: Base table or view not found
- Jalankan migration: `php artisan migrate`

### Data jurusan tidak muncul
- Pastikan seeder sudah dijalankan: `php artisan db:seed --class=JurusanSeeder`
- Cek data di database: `SELECT * FROM jurusan;`
