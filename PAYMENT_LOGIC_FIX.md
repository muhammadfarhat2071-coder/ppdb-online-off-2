# Perbaikan Logika Pembayaran - Status "Lunas" Prematur

## Masalah yang Ditemukan
Muhammad Farhat menunjukkan status pembayaran "Lunas" padahal:
1. Data belum diverifikasi oleh verifikator
2. Pembayaran belum masuk ke role keuangan untuk dikonfirmasi
3. Tidak ada proses verifikasi yang proper

## Akar Masalah
Logika pembayaran di route `/pendaftar/cek-status-pembayaran` (baris 1018-1020):

```php
$isLunas = $pendaftar->tgl_verifikasi_payment || 
          ($pembayaranTerakhir && $pembayaranTerakhir->status === 'Dikonfirmasi');
```

**Masalah:** Status "lunas" bisa muncul dari:
1. Field `tgl_verifikasi_payment` di tabel pendaftar (yang bisa diset otomatis)
2. Status pembayaran 'Dikonfirmasi' (tanpa verifikasi keuangan proper)

## Perbaikan yang Dilakukan

### 1. Perbaikan Logika Status Pembayaran
**File:** `routes/web.php` - Route `/pendaftar/cek-status-pembayaran`

**Sebelum:**
```php
$isLunas = $pendaftar->tgl_verifikasi_payment || 
          ($pembayaranTerakhir && $pembayaranTerakhir->status === 'Dikonfirmasi');
```

**Sesudah:**
```php
// Hanya anggap lunas jika pembayaran benar-benar dikonfirmasi oleh keuangan
$isLunas = $pembayaranTerakhir && $pembayaranTerakhir->status === 'Dikonfirmasi';
```

### 2. Menghapus Auto-Update Status Pendaftar
**File:** `routes/web.php` - Route `/pendaftar/status-pendaftaran`

**Sebelum:**
```php
if ($pembayaranTerakhir && $pembayaranTerakhir->status === 'Dikonfirmasi') {
    $pendaftar->update(['status' => 'Terbayar', 'tgl_verifikasi_payment' => $pembayaranTerakhir->tanggal_konfirmasi]);
    $statusPendaftar = 'Terbayar';
}
```

**Sesudah:**
```php
// Jangan otomatis update status, biarkan keuangan yang mengkonfirmasi
if ($pembayaranTerakhir && $pembayaranTerakhir->status === 'Dikonfirmasi' && !$pendaftar->tgl_verifikasi_payment) {
    $pendaftar->update(['tgl_verifikasi_payment' => $pembayaranTerakhir->tanggal_konfirmasi]);
}
```

### 3. Perbaikan Verifikasi Keuangan
**File:** `routes/web.php` - Route `/keuangan/verifikasi-pembayaran/{id}`

**Ditambahkan:**
```php
if ($request->status === 'Dikonfirmasi') {
    $pembayaran->tanggal_konfirmasi = now();
}
```

## Alur Pembayaran yang Benar Sekarang

1. **Pendaftar** submit pembayaran → Status: "Menunggu Konfirmasi"
2. **Keuangan** melihat pembayaran di dashboard keuangan
3. **Keuangan** verifikasi dan konfirmasi pembayaran → Status: "Dikonfirmasi"
4. **Pendaftar** melihat status "Lunas" setelah dikonfirmasi keuangan

## Testing
1. Login sebagai Muhammad Farhat
2. Cek status pembayaran - seharusnya tidak "Lunas" jika belum dikonfirmasi keuangan
3. Login sebagai keuangan - lihat pembayaran yang perlu dikonfirmasi
4. Konfirmasi pembayaran dari keuangan
5. Login kembali sebagai Muhammad Farhat - status seharusnya "Lunas"

## File yang Dimodifikasi
- `routes/web.php` (3 route diperbaiki)
- `app/Models/Pembayaran.php` (sudah memiliki kolom yang diperlukan)

## Catatan Penting
- Sekarang status "Lunas" hanya muncul setelah keuangan benar-benar mengkonfirmasi
- Data pembayaran akan masuk ke dashboard keuangan untuk diverifikasi
- Tidak ada lagi bypass verifikasi keuangan