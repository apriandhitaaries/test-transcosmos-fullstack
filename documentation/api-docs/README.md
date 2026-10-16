# REST API Documentation

Spesifikasi lengkap REST API untuk **Task Management System**.

- **Base URL**: `http://localhost:8000/api` (atau `http://backend.test/api`)
- **Format Pertukaran Data**: JSON (`application/json`)
- **Autentikasi**: Bearer Token (HTTP Authorization Header)

```http
Authorization: Bearer <your_access_token>
```

---

## 📑 Ringkasan Endpoint

| Method | Endpoint | Deskripsi | Auth Diperlukan |
| :--- | :--- | :--- | :--- |
| `POST` | `/auth/login` | Login user & dapatkan token | Tidak |
| `POST` | `/auth/logout` | Revoke token aktif | Ya |
| `GET` | `/auth/me` | Dapatkan info user yang sedang login | Ya |
| `GET` | `/users` | Ambil daftar semua user untuk assignment | Ya |
| `GET` | `/tasks` | Ambil list task (dengan pagination & filter) | Ya |
| `POST` | `/tasks` | Buat task baru | Ya |
| `GET` | `/tasks/{task}` | Ambil detail 1 task | Ya |
| `PUT/PATCH`| `/tasks/{task}` | Perbarui data task | Ya |
| `DELETE` | `/tasks/{task}` | Hapus task | Ya |
| `POST` | `/tasks/{task}/attachments` | Upload lampiran file ke task | Ya |
| `DELETE` | `/attachments/{attachment}` | Hapus berkas lampiran | Ya |
| `GET` | `/attachments/{attachment}/download` | Download berkas lampiran fisik | Tidak |

---

## 1. Authentication Endpoints

### 1.1 Login User
Mendapatkan access token dengan email dan password.

- **URL**: `POST /auth/login`
- **Headers**: `Content-Type: application/json`
- **Body**:
  ```json
  {
    "email": "user@example.com",
    "password": "password"
  }
  ```
- **Response `200 OK`**:
  ```json
  {
    "access_token": "1|qW8e7r...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com"
    }
  }
  ```
- **Response `401 Unauthorized`**:
  ```json
  {
    "message": "Kredensial tidak valid"
  }
  ```

### 1.2 Get Current User
- **URL**: `GET /auth/me`
- **Headers**: `Authorization: Bearer <token>`
- **Response `200 OK`**:
  ```json
  {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com"
  }
  ```

### 1.3 Logout
- **URL**: `POST /auth/logout`
- **Headers**: `Authorization: Bearer <token>`
- **Response `200 OK`**:
  ```json
  {
    "message": "Berhasil logout"
  }
  ```

---

## 2. Tasks Endpoints

### 2.1 List Tasks
Mengambil daftar task yang dipaginasi (10 item/halaman) beserta relasi user dan lampiran.

- **URL**: `GET /tasks`
- **Query Parameters**:
  - `status` *(string, optional)*: `pending`, `in_progress`, atau `completed`
  - `priority` *(string, optional)*: `low`, `medium`, atau `high`
  - `sort_by` *(string, optional, default: `created_at`)*: Nama kolom sorting
  - `sort_dir` *(string, optional, default: `desc`)*: `asc` atau `desc`
  - `page` *(integer, optional)*: Nomor halaman paginasi
- **Headers**: `Authorization: Bearer <token>`
- **Response `200 OK`**:
  ```json
  {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Membuat Dokumentasi Proyek",
        "description": "Menyusun file README dan panduan arsitektur.",
        "status": "in_progress",
        "priority": "high",
        "due_date": "2026-10-20",
        "created_by": 1,
        "assigned_user_id": 2,
        "created_at": "2026-10-16T12:00:00.000000Z",
        "updated_at": "2026-10-16T12:00:00.000000Z",
        "assigned_user": {
          "id": 2,
          "name": "Jane Smith"
        },
        "creator": {
          "id": 1,
          "name": "John Doe"
        },
        "attachments": []
      }
    ],
    "first_page_url": "...",
    "from": 1,
    "last_page": 2,
    "per_page": 10,
    "total": 15
  }
  ```

### 2.2 Create Task
Membuat task baru. Pembuat otomatis diset ke akun pengguna yang sedang login.

- **URL**: `POST /tasks`
- **Headers**: `Authorization: Bearer <token>`, `Content-Type: application/json`
- **Body**:
  ```json
  {
    "title": "Implementasi Fitur Notifikasi",
    "description": "Tambahkan notifikasi email saat task ditugaskan.",
    "status": "pending",
    "priority": "medium",
    "due_date": "2026-10-25",
    "assigned_user_id": 2
  }
  ```
- **Response `201 Created`**:
  ```json
  {
    "id": 16,
    "title": "Implementasi Fitur Notifikasi",
    "description": "Tambahkan notifikasi email saat task ditugaskan.",
    "status": "pending",
    "priority": "medium",
    "due_date": "2026-10-25",
    "assigned_user_id": 2,
    "created_by": 1,
    "created_at": "...",
    "updated_at": "..."
  }
  ```

### 2.3 Update Task
- **URL**: `PUT /tasks/{id}`
- **Headers**: `Authorization: Bearer <token>`, `Content-Type: application/json`
- **Body**:
  ```json
  {
    "status": "completed"
  }
  ```
- **Response `200 OK`**:
  Mengembalikan objek task yang diperbarui.

### 2.4 Delete Task
- **URL**: `DELETE /tasks/{id}`
- **Headers**: `Authorization: Bearer <token>`
- **Response `200 OK`**:
  ```json
  {
    "message": "Task berhasil dihapus"
  }
  ```

---

## 3. Attachments Endpoints

### 3.1 Upload File ke Task
- **URL**: `POST /tasks/{task}/attachments`
- **Headers**: `Authorization: Bearer <token>`, `Content-Type: multipart/form-data`
- **Form Data**:
  - `file`: *File binary (maksimum 50MB)*
- **Response `201 Created`**:
  ```json
  {
    "id": 5,
    "task_id": 1,
    "file_name": "document.pdf",
    "file_path": "attachments/abc123xyz.pdf",
    "file_size": 204800,
    "mime_type": "application/pdf",
    "created_at": "..."
  }
  ```

### 3.2 Download File
Mengunduh berkas fisik secara langsung.

- **URL**: `GET /attachments/{attachment}/download`
- **Response**: Binary Stream dengan `Content-Disposition: attachment; filename="document.pdf"`

### 3.3 Hapus Attachment
- **URL**: `DELETE /attachments/{attachment}`
- **Headers**: `Authorization: Bearer <token>`
- **Response `200 OK`**:
  ```json
  {
    "message": "Attachment berhasil dihapus"
  }
  ```

---

## 4. Users Endpoint

### 4.1 Get All Users
Mengambil daftar semua user untuk opsi penugasan (*assignee*).

- **URL**: `GET /users`
- **Headers**: `Authorization: Bearer <token>`
- **Response `200 OK`**:
  ```json
  [
    {
      "id": 1,
      "name": "Admin User",
      "email": "admin@example.com"
    },
    {
      "id": 2,
      "name": "Jane Developer",
      "email": "jane@example.com"
    }
  ]
  ```
