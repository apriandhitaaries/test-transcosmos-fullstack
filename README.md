# Task Management System - Fullstack Application

Aplikasi sistem manajemen tugas (Task Management) full-stack yang dibangun menggunakan **Laravel 12 (API Backend)** dan **Vue 3 + Vuetify (Frontend SPA)**.

---

## 📌 Daftar Isi
- [Struktur Direktori](#-struktur-direktori)
- [Teknologi yang Digunakan](#-teknologi-yang-digunakan)
- [Fitur Utama](#-fitur-utama)
- [Panduan Instalasi & Menjalankan](#-panduan-instalasi--menjalankan)
- [Dokumentasi Lengkap](#-dokumentasi-lengkap)

---

## 📂 Struktur Direktori

```text
test-transcosmos-fullstack/
├── backend/                  # RESTful API Backend (Laravel 12)
│   ├── app/
│   │   ├── Http/Controllers/# Auth, Task, Attachment Controller
│   │   └── Models/          # User, Task, TaskAttachment, TaskComment
│   ├── database/
│   │   ├── migrations/      # Skema tabel database
│   │   └── seeders/         # Dummy seeders
│   ├── routes/              # api.php (Endpoints API)
│   └── tests/               # PHPUnit / Pest Test suites
├── frontend/                 # Client SPA (Vue 3 + Vite)
│   ├── src/
│   │   ├── stores/          # Pinia State Management (auth.js)
│   │   ├── views/           # Halaman Dashboard.vue & Login.vue
│   │   └── router/          # Vue Router configuration
│   └── tests/               # Vitest & Playwright E2E tests
├── documentation/            # Dokumentasi Proyek
│   ├── api-docs/            # Spesifikasi OpenAPI / REST API Docs
│   ├── architecture.md      # Arsitektur sistem & diagram alur
│   └── setup-guide.md       # Panduan instalasi dan deployment detail
└── README.md
```

---

## 🛠 Teknologi yang Digunakan

### Backend
- **Framework**: Laravel 12 (PHP 8.2+)
- **Autentikasi**: Laravel Sanctum (Bearer Token)
- **Database**: MySQL / MariaDB / SQLite
- **File Storage**: Laravel Public Storage Disk

### Frontend
- **Framework**: Vue 3 (Composition API / `<script setup>`)
- **Build Tool**: Vite
- **UI Library**: Vuetify 3 + Material Design Icons (`@mdi/font`)
- **State Management**: Pinia
- **HTTP Client**: Axios
- **Routing**: Vue Router

---

## ✨ Fitur Utama
1. **Autentikasi Aman**: Login berbasis Token (Sanctum), Session Persistence di LocalStorage, proteksi route.
2. **Manajemen Task (CRUD)**:
   - Buat task baru dengan status (*pending, in_progress, completed*) dan prioritas (*low, medium, high*).
   - Edit, perbarui status, dan hapus task.
   - Penugasan tugas ke pengguna tertentu (*Assigned User*).
3. **Filter & Sorting**:
   - Filter task berdasarkan status dan tingkat prioritas.
   - Sorting otomatis berdasarkan tanggal pembuatan.
4. **Lampiran Berkas (File Attachments)**:
   - Unggah lampiran dokumen/gambar ke task terkait.
   - Download langsung file lampiran dari dashboard.
   - Hapus berkas lampiran otomatis dari disk storage saat dihapus.

---

## 🚀 Panduan Instalasi & Menjalankan

### 1. Backend (Laravel)
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate

# Konfigurasi database di .env, kemudian migrasi & seeder
php artisan migrate --seed
php artisan storage:link

# Jalankan server API
php artisan serve
```

### 2. Frontend (Vue 3)
```bash
cd frontend
npm install
npm run dev
```

Kunjungi frontend di browser: `http://localhost:5173`

---

## 📚 Dokumentasi Lengkap
Dokumentasi teknis lebih detail dapat ditemukan pada folder `documentation/`:
- 📖 [Panduan Instalasi Lengkap (Setup Guide)](documentation/setup-guide.md)
- 🏗️ [Arsitektur Sistem (Architecture)](documentation/architecture.md)
- 🔌 [Dokumentasi API Endpoints](documentation/api-docs/README.md)
