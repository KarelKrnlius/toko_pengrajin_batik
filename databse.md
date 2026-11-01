DATABASE SCHEMA DEFINITION FOR KIRO
Project: Juragan Batik (PostgreSQL)

1. Tabel categories
Menyimpan data kategori jenis produk batik.

Nama Tabel: categories

Kolom:

id: Primary Key, Auto Increment (Integer / BigIncrements)

name: Varchar (255), Not Null (Contoh: "Batik Tulis", "Batik Cap", "Kemeja Pria")

timestamps: created_at & updated_at (Timestamp)

2. Tabel products
Menyimpan rincian data seluruh barang/kain batik yang dijual.

Nama Tabel: products

Kolom:

id: Primary Key, Auto Increment (Integer / BigIncrements)

category_id: Foreign Key, Not Null (Integer, merujuk ke categories.id dengan relasi onDelete('cascade'))

name: Varchar (255), Not Null (Nama produk batik)

price: Integer, Not Null (Harga produk dalam nilai angka Rupiah)

stock: Integer, Not Null (Jumlah persediaan barang di gudang)

description: Text, Not Null (Penjelasan detail bahan, ukuran, dan narasi produk)

image: Varchar (255), Nullable (Path direktori penyimpanan file gambar produk)

timestamps: created_at & updated_at (Timestamp)

3. Tabel users
Menyimpan data akun administrator untuk mengakses panel manajemen CRUD.

Nama Tabel: users

Kolom:

id: Primary Key, Auto Increment (Integer / BigIncrements)

name: Varchar (255), Not Null (Nama lengkap admin)

email: Varchar (255), Unique, Not Null (Email login admin)

password: Varchar (255), Not Null (Password terenkripsi / Hash)

remember_token: Varchar (100), Nullable

timestamps: created_at & updated_at (Timestamp)