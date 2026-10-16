# Backend API - Task Management System

Dokumentasi spesifik untuk modul backend (Laravel 12 REST API).

---

## 🛠️ Tech Stack & Konfigurasi
- **Framework**: Laravel 12
- **PHP**: 8.2+
- **Auth Provider**: Laravel Sanctum (Token-based authentication)
- **Database Driver**: MySQL / MariaDB / SQLite
- **File Disk**: `storage/app/public`

---

## 🚀 Panduan Setup Singkat

1. **Install Dependencies**:
   ```bash
   composer install
   ```
2. **Environment File**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. **Database Migration & Seeding**:
   ```bash
   php artisan migrate --seed
   ```
4. **Storage Link** (Wajib untuk attachment download):
   ```bash
   php artisan storage:link
   ```
5. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```

---

## 📁 Struktur Modul Backend

- [`app/Http/Controllers/AuthController.php`](file:///d:/Development/Work/test-transcosmos-fullstack/backend/app/Http/Controllers/AuthController.php): Endpoint login, logout, dan verifikasi user session.
- [`app/Http/Controllers/TaskController.php`](file:///d:/Development/Work/test-transcosmos-fullstack/backend/app/Http/Controllers/TaskController.php): Operasi CRUD task, pagination, dan filter status/prioritas.
- [`app/Http/Controllers/AttachmentController.php`](file:///d:/Development/Work/test-transcosmos-fullstack/backend/app/Http/Controllers/AttachmentController.php): Upload berkas pendukung, download stream, dan hapus attachment.
- [`app/Models/`](file:///d:/Development/Work/test-transcosmos-fullstack/backend/app/Models): Model Eloquent (`Task`, `TaskAttachment`, `TaskComment`, `User`).
- [`routes/api.php`](file:///d:/Development/Work/test-transcosmos-fullstack/backend/routes/api.php): Definisi rute REST API dan middleware `auth:sanctum`.
- [`database/migrations/`](file:///d:/Development/Work/test-transcosmos-fullstack/backend/database/migrations): Skema tabel migrasi.

---

## 🧪 Testing

Jalankan test suite menggunakan PHPUnit / Pest:
```bash
php artisan test
```
