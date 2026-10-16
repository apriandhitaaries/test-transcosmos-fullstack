# Setup & Installation Guide

Panduan lengkap instalasi dan konfigurasi lingkungan pengembangan lokal untuk proyek **Task Management System Fullstack**.

---

## 📋 Prasyarat Sistem (Prerequisites)

Pastikan lingkungan kerja Anda sudah terinstal tools berikut:
- **PHP** >= 8.2 (dengan extension: `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `fileinfo`)
- **Composer** >= 2.x
- **Node.js** >= 18.x / 20.x atau 22.x LTS
- **NPM** atau **PNPM**
- **Database Server**: MySQL 8.x / MariaDB 10.x atau SQLite

---

## ⚙️ Langkah 1: Setup Backend (Laravel)

1. **Masuk ke direktori backend:**
   ```bash
   cd backend
   ```

2. **Instal dependensi Composer:**
   ```bash
   composer install
   ```

3. **Salin environment file dan generate APP_KEY:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi Database di file `.env`:**
   Sesuaikan kredensial database Anda di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=task_management
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   *(Catatan: Anda juga dapat menggunakan SQLite dengan menyetel `DB_CONNECTION=sqlite`)*

5. **Jalankan Database Migration & Seeders:**
   ```bash
   php artisan migrate --seed
   ```
   Seeder otomatis akan membuat data user dummy dan task default untuk pengujian.

6. **Buat Symlink Storage untuk File Attachment:**
   ```bash
   php artisan storage:link
   ```
   *Langkah ini krusial agar file lampiran yang diupload dapat diakses/didownload secara publik.*

7. **Jalankan Server Backend:**
   ```bash
   php artisan serve
   ```
   Secara default backend akan aktif di `http://127.0.0.1:8000` (atau `http://backend.test` jika menggunakan Laravel Herd / Valet / vhost).

---

## 🎨 Langkah 2: Setup Frontend (Vue 3 + Vuetify)

1. **Buka terminal baru dan masuk ke direktori frontend:**
   ```bash
   cd frontend
   ```

2. **Instal dependensi NPM:**
   ```bash
   npm install
   ```

3. **Konfigurasi URL Endpoint Backend:**
   Periksa konfigurasi base URL API pada file [`src/stores/auth.js`](../frontend/src/stores/auth.js) dan [`src/views/Dashboard.vue`](../frontend/src/views/Dashboard.vue).
   Secara bawaan, frontend merujuk ke domain virtual `http://backend.test`. Jika Anda menggunakan `php artisan serve` standar (`http://127.0.0.1:8000`), sesuaikan URL target ke `http://127.0.0.1:8000`.

4. **Jalankan Development Server:**
   ```bash
   npm run dev
   ```
   Aplikasi frontend akan aktif di:
   `http://localhost:5173`

---

## 🧪 Langkah 3: Pengujian (Testing)

### Backend Tests (PHPUnit / Pest)
```bash
cd backend
php artisan test
```

### Frontend Unit & E2E Tests
```bash
cd frontend
# Unit Tests (Vitest)
npm run test:unit

# End-to-End Tests (Playwright)
npm run test:e2e
```

---

## 🔑 Akun Uji Coba Default (Demo Credentials)

Setelah menjalankan `php artisan migrate --seed`, Anda dapat memeriksa tabel `users` untuk user yang dibuat secara otomatis oleh seeder atau menggunakan akun pertama:
- **Email**: *(Ambil salah satu email dari database/seeder)*
- **Password**: `password` (default bawaan factory Laravel)
