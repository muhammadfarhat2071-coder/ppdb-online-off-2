# Integrasi User Management ke Dashboard Admin

## Perubahan yang Dilakukan

### 1. Database Migration
- **File**: `database/migrations/2025_01_15_000001_add_last_login_to_pengguna_table.php`
- **Tujuan**: Menambahkan kolom `last_login` ke tabel `pengguna`
- **Cara Menjalankan**:
  ```bash
  php artisan migrate
  ```
  
  Atau secara manual via SQL:
  ```sql
  ALTER TABLE `pengguna` ADD COLUMN `last_login` TIMESTAMP NULL DEFAULT NULL AFTER `aktif`;
  ```

### 2. Model Update
- **File**: `app/Models/Pengguna.php`
- **Perubahan**: Menambahkan `last_login` ke array `$fillable`

### 3. Controller Update
- **File**: `app/Http/Controllers/PenggunaController.php`
- **Perubahan**:
  - Menambahkan method `show($id)` untuk mendapatkan detail user
  - Update response JSON untuk konsistensi (menambahkan `success` dan `message`)

### 4. Routes Update
- **File**: `routes/web.php`
- **Perubahan**: Menambahkan route `GET /api/pengguna/{id}`

### 5. View Update
- **File**: `resources/views/dashboard-admin.blade.php`
- **Perubahan**:
  - Fungsi `loadUsersData()`: Mengambil data dari API `/api/pengguna`
  - Fungsi `showUserModal()`: Menambahkan field `hp` (nomor HP)
  - Fungsi `saveUser()`: Integrasi dengan API untuk create/update user
  - Fungsi `deleteUser()`: Integrasi dengan API untuk delete user
  - Fungsi `getRoleColor()`: Menyesuaikan dengan role di database
  - Modal User: Menambahkan field No. HP dan menyesuaikan role options

## Struktur Tabel Users (pengguna)

```sql
CREATE TABLE `pengguna` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(120) NOT NULL UNIQUE,
  `hp` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('pendaftar','admin','verifikator_adm','keuangan','kepsek') NOT NULL,
  `aktif` tinyint(4) NOT NULL DEFAULT 1,
  `last_login` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pengguna_role_index` (`role`)
);
```

## Mapping Kolom

| Kolom Database | Field di Form | Keterangan |
|----------------|---------------|------------|
| id | - | Auto increment |
| nama | Nama Lengkap | Required |
| email | Email | Required, unique |
| hp | No. HP | Required |
| password | Password | Required saat create, optional saat update |
| role | Role | Enum: admin, kepsek, verifikator_adm, keuangan, pendaftar |
| aktif | Status | 1 = Aktif, 0 = Nonaktif |
| last_login | Terakhir Login | Timestamp, nullable |
| remember_token | - | Laravel auth token |
| created_at | - | Auto timestamp |
| updated_at | - | Auto timestamp |

## API Endpoints

### GET /api/pengguna
Mengambil semua data user
- **Response**: Array of users

### GET /api/pengguna/{id}
Mengambil detail user by ID
- **Response**: User object

### POST /api/pengguna
Membuat user baru
- **Request Body**:
  ```json
  {
    "nama": "string",
    "email": "string",
    "hp": "string",
    "password": "string (min 6)",
    "role": "admin|kepsek|verifikator_adm|keuangan|pendaftar"
  }
  ```
- **Response**: User object dengan status 201

### PUT /api/pengguna/{id}
Update user
- **Request Body**:
  ```json
  {
    "nama": "string",
    "email": "string",
    "hp": "string",
    "password": "string (optional)",
    "role": "admin|kepsek|verifikator_adm|keuangan|pendaftar"
  }
  ```
- **Response**: User object

### DELETE /api/pengguna/{id}
Hapus user
- **Response**: Success message

### POST /api/pengguna/{id}/toggle-status
Toggle status aktif/nonaktif user
- **Response**: User object dengan status terbaru

## Fitur User Management

1. **List Users**: Menampilkan semua user dengan informasi lengkap
2. **Add User**: Menambahkan user baru dengan validasi
3. **Edit User**: Mengubah data user (password optional)
4. **Delete User**: Menghapus user dari sistem
5. **Status Badge**: Menampilkan role dan status dengan warna berbeda
6. **Last Login**: Menampilkan waktu login terakhir

## Role Colors

- **admin**: Primary (Biru)
- **kepsek**: Danger (Merah)
- **verifikator_adm**: Warning (Kuning)
- **keuangan**: Info (Cyan)
- **pendaftar**: Secondary (Abu-abu)

## Testing

1. Pastikan migration sudah dijalankan
2. Buka dashboard admin: `/admin/dashboard`
3. Klik menu "User Management" di sidebar
4. Test fitur:
   - Tambah user baru
   - Edit user existing
   - Hapus user
   - Lihat perubahan status dan role

## Troubleshooting

### Error: Column 'last_login' not found
**Solusi**: Jalankan migration
```bash
php artisan migrate
```

### Error: CSRF token mismatch
**Solusi**: Pastikan `{{ csrf_token() }}` sudah ada di view

### Error: 404 Not Found pada API
**Solusi**: Clear route cache
```bash
php artisan route:clear
php artisan config:clear
```
