# UTP TIS — Ecommerce Backend API
### Laravel + JSON Storage (No Database)

**Nama:** Naila Keisha  
**NIM:** 245150707111054  
**Mata Kuliah:** Teknologi Informasi Sistem  

---

## 📌 Deskripsi Project

Project ini merupakan implementasi backend API sederhana berbasis **ecommerce** menggunakan framework **Laravel** dengan penyimpanan data menggunakan **mock data JSON** (tanpa database). API ini mendukung operasi CRUD lengkap untuk manajemen barang/item.

---

## 🔧 Teknologi yang Digunakan

- **PHP** 8.2
- **Laravel** 11.x
- **JSON File** sebagai penyimpanan data (non-database)
- **Swagger UI** (l5-swagger) untuk dokumentasi API interaktif

---

## 📁 Struktur Project

```
app/
├── Http/
│   └── Controllers/
│       ├── ItemController.php   ← Controller utama CRUD
│       └── SwaggerInfo.php      ← Konfigurasi Swagger
├── Services/
│   └── ItemService.php          ← Logic baca/tulis JSON
routes/
└── api.php                      ← Definisi semua route API
storage/
└── app/
    └── data/
        └── items.json           ← "Database" JSON
```

---

## ⚙️ Cara Instalasi & Menjalankan

### 1. Clone Repository
```bash
git clone https://github.com/username/245150707111054-NailaKeisha-utptis.git
cd 245150707111054-NailaKeisha-utptis
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Buat File JSON Storage
```bash
mkdir -p storage/app/data
echo [] > storage/app/data/items.json
```

### 5. Aktifkan API Routes
```bash
php artisan install:api
```

### 6. Generate Swagger Docs
```bash
php artisan l5-swagger:generate
```

### 7. Jalankan Server
```bash
php artisan serve
```

Server berjalan di: `http://127.0.0.1:8000`

---

## 📖 Dokumentasi API (Swagger)

Akses Swagger UI di:
```
http://127.0.0.1:8000/api/documentation#/
```

---

## 📋 Daftar Endpoint

| Method | Endpoint | Deskripsi |
|--------|----------|-----------|
| GET | `/api/items` | Tampilkan semua barang |
| POST | `/api/items` | Tambah barang baru |
| GET | `/api/items/{id}` | Tampilkan barang by ID |
| PUT | `/api/items/{id}` | Update seluruh data barang |
| PATCH | `/api/items/{id}` | Update sebagian data barang |
| DELETE | `/api/items/{id}` | Hapus barang |

---

## 📝 Contoh Request & Response

### POST `/api/items` — Tambah Barang
**Request:**
```json
{
  "nama_barang": "Laptop Asus",
  "harga": 8500000,
  "stok": 10,
  "deskripsi": "Laptop gaming terjangkau"
}
```
**Response 201:**
```json
{
  "success": true,
  "message": "Barang berhasil ditambahkan",
  "data": {
    "id": 1,
    "nama_barang": "Laptop Asus",
    "harga": 8500000,
    "stok": 10,
    "deskripsi": "Laptop gaming terjangkau",
    "created_at": "2026-04-19 10:00:00",
    "updated_at": "2026-04-19 10:00:00"
  }
}
```

### GET `/api/items` — Semua Barang
**Response 200:**
```json
{
  "success": true,
  "message": "Berhasil mengambil semua data barang",
  "data": [ ... ],
  "total": 1
}
```

### GET `/api/items/99` — ID Tidak Ada
**Response 404:**
```json
{
  "success": false,
  "message": "Item dengan ID 99 tidak Ditemukan"
}
```

### PUT `/api/items/{id}` — Update Semua Field
**Request (semua field wajib):**
```json
{
  "nama_barang": "Laptop Asus Pro",
  "harga": 9000000,
  "stok": 5,
  "deskripsi": "Versi terbaru RAM 16GB"
}
```

### PATCH `/api/items/{id}` — Update Sebagian
**Request (minimal 1 field):**
```json
{
  "harga": 7500000
}
```

### DELETE `/api/items/{id}`
**Response 200:**
```json
{
  "success": true,
  "message": "Barang dengan ID 1 berhasil dihapus"
}
```

---

## ✅ Validasi & Error Handling

| Kode | Keterangan |
|------|------------|
| 200 | Berhasil |
| 201 | Data berhasil dibuat |
| 404 | Data tidak ditemukan |
| 422 | Validasi gagal |

Contoh response validasi gagal:
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "nama_barang": ["Nama barang wajib diisi"],
    "harga": ["Harga wajib diisi"]
  }
}
```

---

