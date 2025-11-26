# Manajemen Tagihan - Role Keuangan

## Fitur yang Ditambahkan

### 1. Section Manajemen Tagihan
**Lokasi:** Dashboard Keuangan > Manajemen Tagihan

**Fitur:**
- Menampilkan semua pendaftar dengan status tagihan
- Ringkasan tagihan (Total, Tertagih, Tunggakan, Persentase)
- Tabel data pendaftar dengan informasi:
  - No. Pendaftaran
  - Nama Siswa
  - Jurusan
  - Jumlah Tagihan
  - Tanggal Daftar
  - Status Pembayaran
  - Aksi (Detail)

### 2. Tabel Riwayat Transaksi
**Lokasi:** Di bawah tabel manajemen tagihan

**Fitur:**
- Statistik transaksi (Total, Dikonfirmasi, Menunggu, Total Nominal)
- Riwayat semua transaksi pembayaran dengan informasi:
  - No. Transaksi
  - Nama Pendaftar
  - Jurusan
  - Jumlah
  - Metode Pembayaran
  - Tanggal Bayar
  - Status
  - Tanggal Konfirmasi

## Route API yang Ditambahkan

### 1. `/keuangan/manajemen-tagihan`
**Method:** GET
**Fungsi:** Mengambil data semua pendaftar dengan informasi tagihan
**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "no_pendaftaran": "PPDB2024001",
      "nama": "Muhammad Farhat",
      "jurusan": "PPLG",
      "gelombang": "Gelombang 1",
      "tanggal_daftar": "2024-01-15",
      "biaya_pendaftaran": 150000,
      "status_pembayaran": "Dikonfirmasi",
      "jumlah_bayar": 150000,
      "tanggal_bayar": "2024-01-16",
      "metode": "Transfer Bank",
      "tgl_verifikasi_payment": "2024-01-16"
    }
  ]
}
```

### 2. `/keuangan/riwayat-tagihan`
**Method:** GET
**Fungsi:** Mengambil riwayat semua transaksi pembayaran
**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "no_transaksi": "TRX20240001",
      "no_pendaftaran": "PPDB2024001",
      "nama": "Muhammad Farhat",
      "jurusan": "PPLG",
      "jumlah": 150000,
      "metode": "Transfer Bank",
      "status": "Dikonfirmasi",
      "tanggal_bayar": "2024-01-16",
      "tanggal_konfirmasi": "2024-01-16",
      "created_at": "2024-01-16"
    }
  ],
  "stats": {
    "total_transaksi": 10,
    "dikonfirmasi": 8,
    "menunggu": 1,
    "ditolak": 1,
    "total_nominal": 1200000
  }
}
```

## Alur Kerja

### 1. Manajemen Tagihan
1. **Keuangan** masuk ke section "Manajemen Tagihan"
2. Sistem menampilkan semua pendaftar dengan status tagihan
3. **Keuangan** dapat melihat:
   - Siapa yang sudah bayar (Status: Lunas)
   - Siapa yang menunggu verifikasi (Status: Menunggu Verifikasi)
   - Siapa yang belum bayar (Status: Belum Bayar)
   - Siapa yang ditolak (Status: Ditolak)

### 2. Riwayat Transaksi
1. Di bawah tabel tagihan, terdapat riwayat semua transaksi
2. Menampilkan statistik lengkap transaksi
3. **Keuangan** dapat melihat histori semua pembayaran yang pernah terjadi

### 3. Integrasi dengan Section Pembayaran
- Section "Manajemen Tagihan" bersifat **read-only** (hanya melihat)
- Untuk verifikasi pembayaran tetap menggunakan section "Pembayaran"
- Tidak ada button verifikasi di section tagihan karena sudah ada di section pembayaran

## Status Pembayaran

### Badge Status:
- **Lunas** (Hijau): Pembayaran sudah dikonfirmasi keuangan
- **Menunggu Verifikasi** (Kuning): Pembayaran disubmit, menunggu konfirmasi keuangan
- **Belum Bayar** (Abu-abu): Pendaftar belum melakukan pembayaran
- **Ditolak** (Merah): Pembayaran ditolak oleh keuangan

## File yang Dimodifikasi
1. `routes/web.php` - Menambahkan 2 route baru untuk keuangan
2. `resources/views/dashboard-keuangan.blade.php` - Menambahkan logika JavaScript untuk manajemen tagihan

## Keunggulan Fitur
1. **Comprehensive View**: Keuangan dapat melihat semua pendaftar dan status tagihan dalam satu tempat
2. **Real-time Data**: Data diambil langsung dari database
3. **Historical Tracking**: Riwayat lengkap semua transaksi
4. **Statistical Summary**: Ringkasan statistik untuk monitoring
5. **Clean Separation**: Pemisahan antara viewing (tagihan) dan action (pembayaran)