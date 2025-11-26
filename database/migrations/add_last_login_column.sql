-- Menambahkan kolom last_login ke tabel pengguna
ALTER TABLE `pengguna` 
ADD COLUMN `last_login` TIMESTAMP NULL DEFAULT NULL AFTER `aktif`;

-- Atau jika ingin menjalankan via Laravel migration, gunakan:
-- php artisan migrate
