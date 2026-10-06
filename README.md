# Sistem Manajemen Master & Kategori Item

Aplikasi web berbasis **Laravel 9** untuk mengelola data **Master Items** (barang) dan **Kategori Items** (kategori barang) pada lingkungan medis/rumah sakit. Sistem mendukung pencarian & filter data, unggah foto barang, relasi banyak-ke-banyak antar barang dan kategori, ekspor ke Excel, serta cetak PDF daftar barang per kategori.

---

## Daftar Isi

- [Fitur](#fitur)
- [Teknologi yang Digunakan](#teknologi-yang-digunakan)
- [Struktur Direktori](#struktur-direktori)
- [Prasyarat](#prasyarat)
- [Cara Instalasi](#cara-instalasi)
- [Akun Default](#akun-default)
- [Cara Menjalankan](#cara-menjalankan)
- [Dokumentasi Penggunaan](#dokumentasi-penggunaan)
- [Menjalankan Seeder](#menjalankan-seeder)
- [Testing](#testing)
- [Perubahan & Perbaikan yang Telah Diterapkan](#perubahan--perbaikan-yang-telah-diterapkan)
- [Catatan Keamanan](#catatan-keamanan)

---

## Fitur

- **Autentikasi user** (login, register, logout) — seluruh halaman CRUD dilindungi login.
- **Master Items**
  - Tambah, lihat detail, ubah, dan hapus barang.
  - Unggah foto barang (jpeg, png, jpg, gif, svg; maks 2 MB).
  - Kolom: kode, nama, harga beli, laba (%), supplier, jenis, kategori, foto.
  - Harga jual dihitung otomatis: `harga_beli + (harga_beli x laba / 100)`.
- **Kategori Items**
  - Tambah, lihat detail, ubah, hapus kategori.
  - Cetak PDF berisi daftar barang pada sebuah kategori.
- **Pencarian & filter** (kode, nama, rentang harga) dengan **pagination server-side** (DataTables).
- **Relasi banyak-ke-banyak** antara Master Items dan Kategori Items.
- **Ekspor Excel** seluruh master items.
- **Soft deletes** — data yang dihapus masih tersimpan di database (ditandai `deleted_at`).

---

## Teknologi yang Digunakan

| Komponen | Teknologi |
|---|---|
| Backend | PHP 8.2, Laravel Framework 9.52 |
| Frontend | Blade, Bootstrap 5, jQuery, DataTables |
| Build tool | Vite + Sass |
| Database | MySQL |
| Library pendukung | Laravel Sanctum, Maatwebsite Excel, Barryvdh DomPDF, laravel/ui |

---

## Struktur Direktori (Bagian Penting)

```
.
├── app/
│   ├── Console/Commands/RecreateDatabase.php
│   ├── Exports/MasterItemsExport.php        # Export Excel
│   ├── Http/Controllers/
│   │   ├── MasterItemsController.php        # CRUD master items
│   │   ├── KategoriItemsController.php      # CRUD kategori items
│   │   └── Auth/                             # Login/Register dari laravel/ui
│   ├── Http/Requests/                        # Validasi FormRequest
│   └── Models/                               # MasterItem, KategoriItem, User, Pasien
├── config/
├── database/
│   ├── migrations/                           # Struktur tabel + relasi
│   └── seeders/                              # User seeder (akun default)
├── public/
├── resources/
│   └── views/
│       ├── layouts/app.blade.php             # Layout utama
│       ├── master_items/                     # View modul master items
│       └── kategori_items/                   # View modul kategori items
├── routes/
│   └── web.php                               # Seluruh rute web (dibungkus middleware auth)
└── tests/                                    # Unit & feature test
```

---

## Prasyarat

Pastikan perangkat Anda sudah menginstal:

- **PHP** >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `fileinfo`, `gd` atau `imagick`, `xml`)
- **Composer** (dependency manager PHP)
- **Node.js** + **npm**
- **MySQL** (atau MariaDB) yang berjalan
- **Git**

Cek versi masing-masing:

```bash
php -v
composer --version
node -v
npm -v
mysql --version
```

---

## Cara Instalasi

1. **Clone / salin proyek**, lalu masuk ke direktori proyek:

   ```bash
   git clone <url-repositori> medify-test
   cd medify-test
   ```

2. **Install dependency PHP (Composer):**

   ```bash
   composer install
   ```

3. **Install dependency frontend (npm):**

   ```bash
   npm install
   ```

4. **Siapkan file environment:**

   ```bash
   cp .env.example .env
   ```

   Kemudian buka `.env` dan sesuaikan koneksi database:

   ```env
   APP_NAME="Sistem Item Medify"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=modify-test
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   > Pastikan database dengan nama `modify-test` sudah dibuat di MySQL:
   > ```sql
   > CREATE DATABASE `modify-test` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   > ```

5. **Buat application key:**

   ```bash
   php artisan key:generate
   ```

6. **Jalankan migrasi + seeder:**

   ```bash
   php artisan migrate:fresh --seed
   ```

7. **Buat symbolic link storage** (agar foto barang bisa diakses dari `public/storage`):

   ```bash
   php artisan storage:link
   ```

8. **Build asset frontend:**

   - Untuk pengembangan (hot reload):
     ```bash
     npm run dev
     ```
   - Untuk produksi:
     ```bash
     npm run build
     ```

---

## Akun Default

Seeder membuat 1 user default (dari `Database\Seeders\UserSeeder`):

| Email | Password |
|---|---|
| `123@123` | `123` |

> Sebaiknya ganti password default pada lingkungan produksi.

---

## Cara Menjalankan

**Opsi 1 — Development (terminal 1):**

```bash
npm run dev
```

**Terminal 2:**

```bash
php artisan serve
```

Buka http://localhost:8000 pada browser.

**Opsi 2 — Tanpa Vite dev server** (harus sudah build):

```bash
npm run build
php artisan serve
```

> Jika muncul error *"Vite manifest not found"*, berarti asset belum di-build — jalankan `npm run build` (atau `npm run dev`).

---

## Dokumentasi Penggunaan

### Halaman Utama

Setelah login (`/` → `/master-items`), menu di navbar:

- **Master Items** → pengelolaan barang.
- **Kategori Items** → pengelolaan kategori barang.

### Master Items

| Aksi | Cara |
|---|---|
| Lihat daftar | Buka menu *Master Items* |
| Tambah barang | Klik tombol `+ Master Items Baru` |
| Filter | Isi kolom Kode / Nama / Harga Min / Harga Max, klik `Filter` |
| Lihat detail | Klik tombol `View` pada baris data |
| Ubah | Pada halaman detail klik `Edit` |
| Hapus | Pada halaman detail klik `Delete` (dengan konfirmasi) |
| Export Excel | Klik tombol `Download Excel` |

Pada form barang:

- **Kategori** dipilih dengan **centang kotak** (bisa lebih dari satu, tanpa menahan Ctrl).
- **Foto** opsional, dapat di-preview sebelum disimpan; file lama otomatis dihapus saat diganti.
- **Harga Jual** dihitung otomatis dari harga beli dan laba.

### Kategori Items

| Aksi | Cara |
|---|---|
| Lihat daftar | Buka menu *Kategori Items* |
| Tambah kategori | Klik tombol `+ Kategori Baru` |
| Lihat detail | Klik tombol `View` — menampilkan semua barang dalam kategori tersebut |
| Cetak PDF | Pada halaman detail klik `Download PDF` |
| Ubah / Hapus | Pada halaman detail klik `Edit` / `Delete` |

> Menghapus kategori akan memutus relasi dengan barang (baris pada tabel pivot dihapus), bukan menghapus barangnya.

---

## Menjalankan Seeder

Seeder dipanggil otomatis saat `php artisan migrate:fresh --seed`. Saat ini **hanya `UserSeeder`** yang tersedia — seeder ini membuat user default untuk login:

```php
$this->call([
    UserSeeder::class,
]);
```

Menjalankan seeder:

```bash
php artisan db:seed   # semua seeder (saat ini hanya UserSeeder)
```

Tidak ada seeder data dummy (kategori & master items) — data diisi melalui antarmuka aplikasi setelah login.

---

## Testing

Menjalankan seluruh test otomatis:

```bash
php artisan test
```

Test saat ini mencakup:

- Guest diarahkan ke halaman login saat membuka `/`.
- User yang sudah login dapat mengakses halaman `/master-items`.

---

## Perubahan & Perbaikan yang Telah Diterapkan

Dokumen analisis dan solusi selengkapnya tersedia di:

- **`ANALISIS-MASALAH.md`** — daftar bug & pelanggaran best practice beserta solusinya.
- **`SOLUSI-MASALAH.md`** — penjelasan perbaikan yang sudah dieksekusi.
- **`PERUBAHAN-MINOR.md`** — perubahan kecil (UI kategori, seeder, dokumentasi).

Rangkuman perbaikan utama:

- Semua rute CRUD dilindungi middleware `auth`.
- Rute berbahaya `update-random-data` dikomentar (tidak aktif).
- Delete memakai method `DELETE` + CSRF (bukan GET).
- Perbaikan fatal error saat menghapus data yang tidak ada.
- Perbaikan tag HTML yang salah di form.
- Antisipasi XSS pada tabel (escaping di sisi server).
- Validasi server-side memakai FormRequest.
- Seeding user dipindah dari migrasi ke seeder.
- Duplikasi rute `/` dirapikan.
- DataTable diubah menjadi server-side (pagination).
- Query data memakai Eloquent + eager loading (tanpa N+1).
- Seeder hanya berisi `UserSeeder` (data dummy kategori/master items dihapus).
- Pemilihan kategori pada form memakai checkbox (lebih mudah daripada Ctrl+klik).

---

## Catatan Keamanan

- Jangan pernah setel `APP_DEBUG=true` pada lingkungan produksi.
- Ganti `APP_KEY`, kredensial database, dan password dari `.env` sesuai lingkungan.
- File `.env` tidak boleh ikut ter-commit ke repositori (sudah ada di `.gitignore`).

---

## Lisensi

Proyek ini dibuat untuk keperluan pengembangan/tes. Lisensi default mengikuti framework Laravel (MIT) kecuali ditentukan lain.