# SBM Web Application

## Persyaratan Sistem
- PHP 8.0 atau lebih tinggi
- MySQL
- Composer

## Instalasi
1. Clone repository ini ke direktori web server Anda
2. Buka terminal, masuk ke direktori proyek
3. Jalankan perintah:
   ```bash
   composer install
   ```

## Konfigurasi Database
1. Buka file `application/config/database.php`
2. Sesuaikan konfigurasi database dengan pengaturan berikut:
   - Database yang digunakan:
     * dbref2025
     * dbsatu_
     * dbref2026
     * dbsatu
   - Tabel utama: t_sbu_hierarchy
   - Sesuaikan pengaturan lainnya (host, username, password) sesuai dengan environment Anda

## Akun Default
Gunakan akun berikut untuk login pertama kali:
- Username: 89234
- Password: 123

## Framework
Aplikasi ini dibangun menggunakan CodeIgniter 3

## Catatan
- Pastikan PHP dan MySQL sudah terinstall dan berjalan dengan baik
- Pastikan ekstensi PHP yang diperlukan sudah diaktifkan
- Sesuaikan permission folder jika diperlukan
