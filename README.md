# Juragan Batik — Surakarta Est. 1928

Website toko batik online yang menjual koleksi batik tulis dan cap autentik dari Solo dan Yogyakarta. Dibangun dengan Laravel 13 dan Tailwind CSS.

---

## Tentang Proyek

Juragan Batik adalah platform e-commerce yang fokus pada penjualan batik premium. Pembeli bisa melihat katalog produk, menyimpan favorit, dan melakukan pemesanan langsung via WhatsApp. Admin atau kurator bisa mengelola produk dan kategori langsung dari portal yang sudah disediakan.

---

## Fitur Utama

### Untuk Pengunjung / Pembeli
- **Beranda** — Hero slideshow otomatis dari foto produk katalog, berputar setiap 5 detik
- **Katalog Batik** — Daftar produk lengkap dengan fitur pencarian dan filter
  - Filter berdasarkan kategori (dropdown buka/tutup, ada search kategori)
  - Filter berdasarkan rentang harga (double range slider)
  - Sortir: Terbaru, Terlama, Harga Terendah, Harga Tertinggi, A-Z, Z-A
- **Detail Produk** — Foto produk besar, deskripsi, stok, sertifikasi, dan tombol beli via WhatsApp
- **Filosofi Motif** — Halaman cerita dan filosofi batik Juragan Batik

### Untuk Akun (Wajib Login)
- **Keranjang Belanja** — Tambah, ubah jumlah, hapus produk, lalu checkout via WhatsApp
- **Favorit** — Simpan produk yang disukai, lihat di halaman tersendiri
- **Profil** — Edit nama, email, nomor HP, alamat, dan ganti password

### Portal Kurator / Admin
- **Kelola Produk** — Tambah, edit, hapus produk; upload dan crop foto produk (rasio 3:4)
- **Kelola Kategori** — Tambah, hapus kategori; sortir dan pagination otomatis (10 per halaman)
- **Filter & Sortir** — Filter produk berdasarkan kategori dan sortir di halaman admin

---

## Teknologi yang Digunakan

| Bagian | Teknologi |
|---|---|
| Backend | PHP 8.3+, Laravel 13 |
| Frontend | Blade Template, Tailwind CSS v4 |
| Bundler | Vite 8 |
| Database | SQLite (default) / MySQL |
| Autentikasi | Laravel bawaan (session-based) |
| Storage | Laravel Storage (lokal) |
| Pemesanan | WhatsApp API (wa.me link) |

---

## Struktur Halaman

```
/                   → Beranda
/katalog            → Katalog produk (dengan filter & search)
/katalog/{id}       → Detail produk
/cerita-kami        → Halaman tentang & filosofi

/login              → Login
/register           → Daftar akun

/profil             → Halaman profil (login)
/keranjang          → Keranjang belanja (login)
/favorit            → Daftar favorit (login)

/admin/products     → Portal kurator — kelola produk (admin)
/admin/categories   → Portal kurator — kelola kategori (admin)
```

---

## Cara Menjalankan

### Kebutuhan Awal
- PHP 8.3 atau lebih baru
- Composer
- Node.js dan npm

### Langkah Install

```bash
# 1. Clone repository
git clone <url-repo> juragan-batik
cd juragan-batik

# 2. Install dependensi PHP
composer install

# 3. Salin file environment
cp .env.example .env

# 4. Generate app key
php artisan key:generate

# 5. Jalankan migrasi database
php artisan migrate

# 6. Install dependensi JS
npm install

# 7. Build aset frontend
npm run build
```

Atau bisa pakai satu perintah (dari `composer.json`):

```bash
composer run setup
```

### Menjalankan Server Lokal

```bash
# Jalankan PHP development server
php artisan serve

# Atau jalankan semua sekaligus (server + vite)
composer run dev
```

Buka di browser: `http://localhost:8000`

---

## Konfigurasi .env

Beberapa hal yang perlu disesuaikan di file `.env`:

```env
APP_NAME="Juragan Batik"
APP_URL=http://localhost:8000

# Database (default SQLite)
DB_CONNECTION=sqlite

# Jika pakai MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=juragan_batik
# DB_USERNAME=root
# DB_PASSWORD=

# Nomor WhatsApp untuk pemesanan
# (ganti di controller jika perlu)
```

---

## Akun Default

Setelah migrasi, buat akun admin lewat tinker atau seeder:

```bash
php artisan tinker
```

```php
// Buat akun admin
App\Models\User::create([
    'name'     => 'Admin',
    'email'    => 'admin@example.com',
    'password' => bcrypt('password'),
    'role'     => 'admin',
]);
```

---

## Struktur Folder Penting

```
app/
├── Http/Controllers/
│   ├── AuthController.php        # Login & register
│   ├── CartController.php        # Keranjang belanja
│   ├── CategoryController.php    # Kelola kategori (admin)
│   ├── FavoriteController.php    # Favorit produk
│   ├── PageController.php        # Halaman publik (beranda, katalog, dll)
│   ├── ProductController.php     # Kelola produk (admin)
│   └── ProfileController.php     # Profil pengguna
└── Models/
    ├── Cart.php
    ├── Category.php
    ├── Favorite.php
    ├── Product.php
    └── User.php

resources/views/
├── layouts/app.blade.php         # Layout utama (navbar, footer)
├── home.blade.php                # Beranda
├── catalog.blade.php             # Katalog produk
├── detail.blade.php              # Detail produk
├── about.blade.php               # Halaman cerita
├── auth/                         # Login & register
├── admin/                        # Portal kurator
└── profile/                      # Halaman profil

public/
└── images/logo.png               # Logo & favicon
```

---

## Lisensi

Proyek ini dibuat untuk keperluan toko Juragan Batik Surakarta. Hak cipta sepenuhnya milik pemilik usaha.
