# Fitur Cetak Kartu Pendaftaran - Dashboard Pendaftar

## Deskripsi Fitur
Fitur cetak kartu pendaftaran memungkinkan siswa mencetak kartu identitas pendaftaran mereka dengan mengambil foto dari berkas yang sudah diupload.

## Lokasi Fitur
- **Section**: Pengumuman (sidebar dashboard pendaftar)
- **Posisi**: Di bagian atas section pengumuman

## Persyaratan Cetak Kartu
1. **Data Diri Lengkap**: Nama, tempat lahir, tanggal lahir, jenis kelamin
2. **Foto 3x4**: Berkas foto 3x4 sudah diupload dan terverifikasi

## Alur Logika
1. **Cek Status**: Sistem mengecek kelengkapan data dan foto
2. **Validasi**: Tombol cetak hanya aktif jika semua persyaratan terpenuhi
3. **Generate Kartu**: Mengambil data dari database dan foto dari berkas
4. **Preview**: Menampilkan preview kartu di modal
5. **Print**: Fungsi print browser untuk mencetak kartu

## Komponen Kartu
- Header sekolah (nama, alamat, kontak)
- No. pendaftaran
- Data siswa (nama, TTL, JK, asal sekolah, jurusan)
- Foto 3x4 dari berkas yang diupload
- Area tanda tangan
- Tanggal cetak
- Catatan penggunaan kartu

## Teknologi
- **Frontend**: Bootstrap modal, JavaScript
- **Backend**: Laravel routes untuk data dan berkas
- **Print**: Browser print API dengan CSS khusus

## Keamanan
- Hanya siswa yang login dapat mengakses
- Foto diambil dari berkas terverifikasi
- Data real-time dari database