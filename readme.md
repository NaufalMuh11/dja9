# DATA: Dashboard Analitika dan Telaah Anggaran Web Application

Dokumen ini akan memandu Anda melalui proses instalasi lengkap, mulai dari penyiapan aplikasi utama, database, hingga integrasi dengan Ragflow.

---

## Alur Instalasi
Untuk menjalankan aplikasi secara penuh, urutan instalasi yang disarankan adalah sebagai berikut:
1.  **Instalasi Aplikasi & Database**: Menyiapkan file aplikasi dan database MySQL.
2.  **Instalasi & Konfigurasi Ragflow**: Menyiapkan environment Ragflow sebagai layanan pendukung.
3.  **Konfigurasi Environment**: Menghubungkan aplikasi utama dengan database dan Ragflow melalui file `.env`.

---

## Langkah 1: Instalasi Aplikasi & Database

### Persyaratan Sistem Aplikasi
- PHP 7.4 atau lebih tinggi
- MySQL
- Composer

### Instalasi Aplikasi
1.  **Clone repository** ini ke direktori web server Anda.
2.  Buka terminal dan masuk ke **direktori proyek**.
3.  Jalankan perintah berikut untuk menginstal semua *dependency*:
    ```bash
    composer install
    ```
4.  **Salin file `.env.example`** menjadi `.env`.
    ```bash
    cp .env.example .env
    ```

### Setup Database
Aplikasi ini memerlukan beberapa database untuk dapat berfungsi.
1.  **Buat Database**: Buat tiga database baru di MySQL dengan nama: `dbsatu_`, `dbref2025_`, dan `dbref2026_`.
2.  **Impor Database**: Impor file `.sql` yang sesuai ke dalam masing-masing database. Karena ukuran file mungkin besar, **sangat disarankan** menggunakan *database management tool* seperti **HeidiSQL, DBeaver, atau MySQL Workbench** untuk menghindari limitasi upload yang sering terjadi pada phpMyAdmin.

---

## Langkah 2: Instalasi & Konfigurasi Ragflow
Layanan Ragflow diperlukan untuk menjalankan fitur-fitur berbasis AI. Anda dapat melihat dokumentasi resmi [Ragflow](https://ragflow.io/docs/dev/) untuk detail lebih lanjut.

### Persyaratan Ragflow
* CPU ≥ 4 cores (x86)
* RAM ≥ 16 GB
* Disk ≥ 50 GB
* Docker ≥ 24.0.0 & Docker Compose ≥ v2.26.1

### Langkah-langkah Instalasi Ragflow
1.  **Clone repositori Ragflow dan masuk ke folder docker:**
    ```bash
    git clone https://github.com/infiniflow/ragflow.git
    cd ragflow/docker
    ```
2.  **Pindah ke versi yang stabil (Disarankan):**
    ```bash
    git checkout -f v0.19.0
    ```
3.  **Konfigurasi Environment Docker:**
    Buka file `.env` di dalam folder `ragflow/docker/` dan atur port. Disarankan untuk mengubah port agar tidak bentrok dengan layanan lain.
    ```
    RAGFLOW_IMAGE=infiniflow/ragflow:v0.19.0-slim
    SVR_HTTP_PORT=8080 # Disarankan merubah port ke 8080 atau port lain
    ```
4.  **Jalankan server Ragflow:**
    ```bash
    # Menggunakan CPU
    docker-compose -f docker-compose.yml up -d
    # Menggunakan GPU untuk akselerasi embedding
    # docker compose -f docker-compose-gpu.yml up -d
    ```
5.  **Akses Dasbor Ragflow:**
    Buka web browser Anda, masukkan alamat IP server beserta port yang telah Anda atur (contoh: `http://localhost:8080`), lalu masuk atau buat akun baru.

### Konfigurasi LLM, Knowledge Base, dan Agen
1.  **Konfigurasi LLM dan Knowledge Base:** Ikuti panduan pada [dokumentasi resmi RAGFlow](https://ragflow.io/docs/dev/#configure-llms) untuk menghubungkan model bahasa (LLM) dan membuat basis pengetahuan.
2.  **Impor Konfigurasi Agen:** Untuk mempercepat penyiapan, impor konfigurasi agen yang sudah ada.
    * Masuk ke dasbor Ragflow.
    * Arahkan ke menu **AI Agents** > Klik **Import Agent**.
    * Pilih file `SAPA.json` yang tersedia di repositori ini.

---

## Langkah 3: Konfigurasi Environment Aplikasi
Setelah aplikasi dan Ragflow siap, hubungkan keduanya.

1.  Buka file `.env` yang berada di direktori utama proyek aplikasi **DATA**.
2.  Sesuaikan konfigurasi untuk database dan Ragflow.

    Contoh isi file `.env`:
    ```ini
    # Konfigurasi Koneksi Database
    DB_HOST=localhost
    DB_USERNAME=root
    DB_PASSWORD=
    DB_PORT=3306
    DB_DRIVER=mysqli
    
    BASE_URL=http://localhost/data
        
    # Konfigurasi Ragflow
    RAGFLOW_URL=http://localhost:8080 # Sesuaikan dengan URL dan port Ragflow Anda
    RAGFLOW_API_KEY=YOUR_API_KEY # Kunci API dari Ragflow Anda
    RAGFLOW_AGENT_ID=YOUR_AGENT_ID # ID dari agen yang telah Anda buat atau impor
    ```
    * **Penting:** Pastikan file `application/config/database.php` dan `application/controllers/Api.php` sudah diatur untuk mengambil nilai-nilai ini dari file `.env` menggunakan `getenv('NAMA_VARIABEL')`.

---

## Akun Default
Gunakan akun berikut untuk login pertama kali ke aplikasi **DATA**:
-   **Username:** `89234`
-   **Password:** `123`

---

## Informasi Tambahan

### Cara Mengekspor Database
Untuk tujuan backup atau migrasi, disarankan menggunakan *database management tool* seperti **HeidiSQL** atau **MySQL Workbench**.
1.  Hubungkan ke server MySQL Anda.
2.  Klik kanan pada database yang ingin di-ekspor.
3.  Pilih opsi "Export" atau "Dump".
4.  Simpan sebagai file `.sql`.

### Framework
Aplikasi ini dibangun menggunakan **CodeIgniter 3**.

### Catatan
-   Pastikan **PHP dan MySQL** sudah terinstal dan berjalan dengan baik.
-   Pastikan semua **ekstensi PHP** yang diperlukan (misalnya `mysqli`, `mbstring`) sudah aktif.
-   Sesuaikan **izin akses (permission) folder** jika diperlukan, terutama untuk direktori `application/cache` dan `application/logs`.

