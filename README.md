# 🌐 Life Connect - Customer Registration & Service Management System

Sistem manajemen terintegrasi untuk alur pendaftaran pelanggan internet LifeMedia, mulai dari pengisian form registrasi publik, penanganan survey teknis oleh tim OPJ, verifikasi data oleh tim Customer Care (C-Care), hingga approval dan monitoring oleh Admin Sales & Admin VAS.

---

## 📌 Fitur Utama & Peran Pengguna (Roles)

1. **Form Registrasi Pelanggan Publik**: Pengisian formulir pendaftaran pelanggan baru lengkap dengan pemilihan paket langganan dan upload berkas.
2. **Sales / Account Manager (AM)**: Pengajuan permohonan survey, tracking status prospek pelanggan, dan pemantauan pendaftaran.
3. **Tim OPJ (Operasi & Jaringan)**: Verifikasi teknis lapangan (ODP/FAT, redaman sinyal, kebutuhan kabel & tiang) serta rekomendasi kelayakan instalasi.
4. **Customer Care (C-Care)**: Verifikasi kelengkapan dokumen KTP/KK, validasi data pelanggan, dan konfirmasi penagihan awal.
5. **Admin Sales**: Approval pengajuan survey & pendaftaran, monitoring performa tim sales, serta rekap laporan.
6. **Admin VAS (Value Added Services)**: Monitoring aktivasi layanan tambahan, dashboard analitik pelanggan, dan log aktivitas.

---

## 📋 Prasyarat Sistem (Prerequisites)

Pastikan sistem/komputer Anda telah terinstall tools berikut:

- **PHP**: Versi `^8.3` atau lebih baru
  - *PHP Extensions*: `OpenSSL`, `PDO`, `pdo_sqlite` (atau `pdo_mysql`), `Mbstring`, `Tokenizer`, `XML`, `Ctype`, `JSON`, `BCMath`, `Fileinfo`, `GD` / `Imagick`
- **Composer**: Versi `2.x`
- **Node.js**: Versi `18.x` / `20.x` atau lebih baru
- **NPM**: Versi `9.x` atau lebih baru
- **Git**: Untuk clone repository
- **Database**: SQLite (default & praktis) atau MySQL / MariaDB

---

## 🚀 Panduan Instalasi Step-by-Step (Local Development)

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan project di komputer lokal:

### 1. Clone Repository

Buka terminal / Git Bash / Command Prompt, lalu jalankan:

```bash
git clone https://github.com/Dvamhmd/Life_Connect.git
cd Life_Connect
```

---

### 2. Install Dependensi PHP (Composer)

Jalankan composer untuk mengunduh semua library backend:

```bash
composer install
```

---

### 3. Install Dependensi JavaScript (NPM)

Jalankan npm untuk mengunduh dependensi frontend (Tailwind CSS, Vite, dll):

```bash
npm install
```

---

### 4. Konfigurasi File Environment (`.env`)

Salin file template `.env.example` menjadi `.env`:

**Linux / macOS / Git Bash:**
```bash
cp .env.example .env
```

**Windows PowerShell:**
```powershell
Copy-Item .env.example .env
```

**Windows Command Prompt (CMD):**
```cmd
copy .env.example .env
```

---

### 5. Generate Application Encryption Key

Generate key keamanan aplikasi Laravel:

```bash
php artisan key:generate
```

---

### 6. Konfigurasi & Migrasi Database

Project ini secara default siap menggunakan **SQLite** (tanpa perlu install database server tambahan), atau Anda dapat menggunakan **MySQL**.

#### Opsi A: Menggunakan SQLite (Rekomendasi Cepat & Praktis)

1. Pastikan konfigurasi di `.env` berisi:
   ```env
   DB_CONNECTION=sqlite
   ```
2. Buat file database SQLite jika belum ada:
   - **Linux / macOS / Git Bash**: `touch database/database.sqlite`
   - **Windows PowerShell**: `New-Item -ItemType File -Path database/database.sqlite -Force`
   - **Windows CMD**: `type nul > database\database.sqlite`

3. Jalankan migrasi dan seeder data awal:
   ```bash
   php artisan migrate:fresh --seed
   ```

#### Opsi B: Menggunakan MySQL / MariaDB

1. Buat database baru di MySQL (misal bernama `life_connect`).
2. Sesuaikan konfigurasi di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=life_connect
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate:fresh --seed
   ```

---

### 7. Buat Symbolic Link Storage (Untuk Upload Dokumen/Foto)

Buat link dari `public/storage` ke `storage/app/public` agar foto KTP, bukti survey, dan dokumen pelanggan dapat diakses:

```bash
php artisan storage:link
```

---

### 8. Jalankan Asset Bundling (Frontend)

Untuk meng-compile Tailwind CSS dan JavaScript:

- **Mode Development (Hot-Reloading):**
  ```bash
  npm run dev
  ```
- **Atau Mode Production Build:**
  ```bash
  npm run build
  ```

---

### 9. Jalankan Web Server Laravel

Buka tab terminal baru, lalu jalankan server lokal:

```bash
php artisan serve
```

Aplikasi sekarang dapat diakses melalui browser di:
👉 **[http://localhost:8000](http://localhost:8000)** atau **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Akun Demo / Akun Default (Hasil Seeder)

Setelah menjalankan `php artisan migrate:fresh --seed`, Anda dapat langsung login menggunakan akun-akun berikut:

> **Password untuk semua akun:** `password`

| Role / Divisi | Email Login | Password | Keterangan / Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin VAS** | `vas@lifemedia.id` | `password` | Akses penuh dashboard VAS, status, dan log |
| **Admin Sales** | `salesadmin@lifemedia.id` | `password` | Monitoring & approval sales seluruh area |
| **Tim OPJ** | `opj@lifemedia.id` | `password` | Verifikasi teknis, ODP, tiang, redaman sinyal |
| **Customer Care** | `ccare@lifemedia.id` | `password` | Verifikasi data pelanggan & penagihan |
| **Sales (AM-101)** | `sales01@lifemedia.id` | `password` | Budi Pratama (Input survey & prospek) |
| **Sales (AM-102)** | `sales02@lifemedia.id` | `password` | Dewi Lestari (Input survey & prospek) |

---

## 🧪 Menjalankan Testing

Untuk memastikan semua fitur dan logic berjalan normal:

```bash
php artisan test
```

---

## 🛠️ Tips & Solusi Masalah Umum (Troubleshooting)

1. **Gambar / File Upload Tidak Muncul (404 Not Found):**
   Pastikan sudah menjalankan `php artisan storage:link`. Jika di Windows link sempat rusak, hapus folder `public/storage` lalu jalankan ulang perintahnya.

2. **Perubahan Tampilan / CSS Tidak Muncul:**
   Pastikan `npm run dev` sedang aktif di terminal terpisah, atau jalankan `npm run build`.

3. **Cache Error / Route Not Found:**
   Bersihkan seluruh cache aplikasi dengan perintah:
   ```bash
   php artisan optimize:clear
   ```

4. **Izin Folder (Linux / macOS):**
   Pastikan folder `storage` dan `bootstrap/cache` dapat ditulis:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

---

## 📄 Lisensi

Project ini dikembangkan untuk kebutuhan internal LifeMedia.
