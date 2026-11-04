# SPESIFIKASI FITUR: Autentikasi User, Keranjang, & Produk Favorit

## 1. Perubahan Identitas Portal
* **Perubahan Nama Portal:** Mengganti nama portal/label header yang sebelumnya "Kuartor" menjadi **"Login"** (atau halaman autentikasi user).

---

## 2. Sistem Autentikasi (Role: User Biasa)
* **Status Admin:** Role admin sudah tersedia dan terimplementasi.
* **Fitur User Baru:** 
  * Halaman Register/Registrasi akun bagi pelanggan baru.
  * Halaman Login khusus user biasa dengan validasi sesi (*session* / *JWT*).
  * Tombol akses cepat ke halaman Login/Register di bagian navigasi website.

---

## 3. Alur Fungsionalitas Utama (User Flow)
* **Validasi Sesi Sebelum Aksi:**
  * Pengunjung (tamu yang belum login) **tidak dapat** langsung mengklik tombol "Beli via WhatsApp" maupun tombol "Tambah ke Favorit".
  * Jika user belum login dan mencoba melakukan aksi tersebut, sistem akan **secara otomatis mengarahkan (redirect)** pengguna ke halaman **Login**.
  * Setelah berhasil login, pengguna akan dikembalikan ke halaman produk sebelumnya atau dapat melanjutkan transaksi/favorit.

* **Fitur Keranjang Belanja:**
  * User dapat menambahkan beberapa produk pilihan ke dalam keranjang belanja.
  * Halaman/Modul keranjang untuk melihat daftar produk yang akan dibeli sebelum checkout ke WhatsApp.

* **Fitur Produk Favorit (Wishlist):**
  * User dapat menandai (ikon hati/bookmark) produk tertentu sebagai produk favorit mereka agar mudah ditemukan kembali di kemudian hari.

---

## 4. Perencanaan Basis Database (Menunggu Konfirmasi)
> **Catatan Penting:** Sesuai instruksi, **struktur tabel database di bawah ini belum dieksekusi** dan memerlukan konfirmasi/persetujuan Anda sebelum diterapkan ke dalam database (PostgreSQL/MySQL).

### Usulan Struktur Tabel Tambahan:

#### A. Tabel `users` (Jika belum ada untuk role user)
* `id` (Primary Key)
* `name` (VARCHAR)
* `email` (VARCHAR, Unique)
* `password` (VARCHAR, Hashed)
* `role` (ENUM: 'admin', 'user') - *default: 'user'*
* `created_at`, `updated_at`

#### B. Tabel `carts` (Keranjang Belanja)
* `id` (Primary Key)
* `user_id` (Foreign Key ke tabel `users`)
* `product_id` (Foreign Key ke tabel produk)
* `quantity` (INT)
* `created_at`, `updated_at`

#### C. Tabel `favorites` (Produk Favorit / Wishlist)
* `id` (Primary Key)
* `user_id` (Foreign Key ke tabel `users`)
* `product_id` (Foreign Key ke tabel produk)
* `created_at`