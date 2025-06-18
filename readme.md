# DATA: Dashboard Analitika dan Telaah Anggaran Web Application

## Persyaratan Sistem
- PHP 8.0 atau lebih tinggi
- MySQL
- Composer

## Instalasi
1.  **Clone repository** ini ke direktori web server Anda.
2.  Buka terminal dan masuk ke **direktori proyek**.
3.  Jalankan perintah berikut untuk menginstal semua *dependency*, termasuk *library* untuk manajemen *environment* (`phpdotenv`):
    ```bash
    composer install
    ```
4.  **Salin file `.env.example`** menjadi `.env`. File ini akan digunakan untuk menyimpan semua konfigurasi sensitif Anda.
    ```bash
    cp .env.example .env
    ```

---

## Konfigurasi Database
1.  Buka file `.env` yang baru saja Anda buat.
2.  Sesuaikan konfigurasi database dengan pengaturan environment Anda. Pastikan Anda telah membuat database yang diperlukan di MySQL.

    Contoh isi file `.env`:
    ```ini
    # Konfigurasi Database Utama
    DB_HOST=localhost
	DB_USERNAME=root
	DB_PASSWORD=
	DB_PORT=3306
	DB_DRIVER=mysqli
	
	BASE_URL=http://localhost/data
    	
	RAGFLOW_URL=YOUR_RAGFLOW_URL
	RAGFLOW_API_KEY=YOUR_API_KEY
	RAGFLOW_AGENT_ID=YOUR_AGENT_ID
    ```
    * **Penting:** Pastikan file `application/config/database.php` sudah diatur untuk mengambil nilai-nilai ini dari file `.env` menggunakan `$_ENV['NAMA_VARIABEL']` atau `getenv('NAMA_VARIABEL')`.

---

## Akun Default
Gunakan akun berikut untuk login pertama kali:
-   **Username:** `89234`
-   **Password:** `123`

---

## Framework
Aplikasi ini dibangun menggunakan **CodeIgniter 3**.

---

## Catatan
-   Pastikan **PHP dan MySQL** sudah terinstal dan berjalan dengan baik.
-   Pastikan semua **ekstensi PHP** yang diperlukan (misalnya `mysqli`, `mbstring`) sudah aktif.
-   Sesuaikan **izin akses (permission) folder** jika diperlukan, terutama untuk direktori `application/cache` dan `application/logs`.
