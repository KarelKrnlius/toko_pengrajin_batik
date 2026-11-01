⚙️ 1. Logika Backend & Controller (Laravel)
Bagian ini mengatur bagaimana sistem memproses data dari database PostgreSQL dan merespons permintaan pengguna.

A. PageController (Menangani Halaman Publik)
home():

Fitur: Menampilkan halaman beranda utama.

Logika: Mengambil 3 data produk terbaru dari database beserta relasi kategorinya (Product::with('category')->latest()->take(3)->get()) untuk dipamerkan kepada pengunjung.

catalog(Request $request):

Fitur: Pencarian produk dan filter berdasarkan kategori.

Logika: Menangkap parameter search (pencarian nama batik) dan category (filter jenis batik), lalu menerapkan sistem pembagian halaman (pagination) sebanyak 6 item per halaman agar performa tetap cepat.

show($id):

Fitur: Halaman detail produk lengkap.

Logika: Mencari data produk spesifik berdasarkan ID. Jika ditemukan, sistem juga mencarikan rekomendasi produk lain yang sejenis di kategori yang sama.

about():

Fitur: Halaman informasi Brand Story.

Logika: Menampilkan halaman statis narasi filosofi dan latar belakang berdirinya Juragan Batik.

B. ProductController (Manajemen CRUD Admin)
index():

Fitur: Tabel inventaris stok admin.

Logika: Mengambil seluruh daftar produk dan kategori untuk ditampilkan di panel pengelola.

store(Request $request):

Fitur: Tambah produk baru.

Logika: Melakukan validasi input form (nama, kategori, harga, stok, deskripsi, dan gambar), mengunggah file gambar ke direktori server (storage/app/public/products), lalu menyimpan datanya ke PostgreSQL.

update(Request $request, $id):

Fitur: Edit/perbarui data produk.

Logika: Memperbarui informasi produk yang sudah ada. Jika admin mengunggah file gambar baru, sistem otomatis menghapus gambar lama di server dan menggantinya dengan yang baru.

destroy($id):

Fitur: Hapus produk.

Logika: Menghapus record produk dari database sekaligus menghapus file fisik gambar terkait dari penyimpanan server agar tidak menumpuk (clean storage).

🖥️ 2. Tampilan Antarmuka & Views (Blade & Bootstrap 5)
Bagian ini mendefinisikan struktur tampilan visual yang akan dilihat oleh pengguna umum maupun admin.

A. Master Layout (resources/views/layouts/app.blade.php)
Kerangka Utama: Memuat konfigurasi standar HTML5, penyertaan framework Bootstrap 5 (CSS & JS), serta integrasi Google Fonts.

Navbar Konsisten: Menu navigasi atas yang selalu tampil di setiap halaman berisi tautan: Beranda, Katalog, Cerita Kami, dan tombol pintasan menuju Admin Panel.

Footer Informatif: Bagian bawah halaman yang memuat keterangan hak cipta dan identitas proyek sertifikasi.

B. Halaman Publik (Views)
Beranda (home.blade.php):

Fitur: Banner utama (Hero Section) dengan ajakan bertindak (CTA), penjelasan singkat keunggulan produk (pengrajin lokal & bahan alami), serta cuplikan 3 produk terbaru.

Katalog (catalog.blade.php):

Fitur: Tata letak dua kolom (Sidebar filter kategori di sebelah kiri dan grid kartu produk di sebelah kanan). Setiap kartu produk dilengkapi foto, badge kategori, harga dalam format Rupiah, dan tombol lihat detail.

Detail Produk (detail.blade.php):

Fitur: Menampilkan foto produk berukuran besar, informasi ketersediaan stok, deskripsi rinci bahan, tombol interaktif pemesanan, serta bagian produk terkait di bagian bawah.

Cerita Kami (about.blade.php):

Fitur: Halaman narasi (Brand Story) yang mengisahkan kemitraan langsung dengan pengrajin lokal di Jawa Tengah/Yogyakarta, teknik canting tradisional, serta misi pelestarian warisan budaya.

C. Halaman Admin Dashboard (resources/views/admin/products/index.blade.php)
Tabel Inventaris:

Fitur: Menampilkan daftar barang secara terstruktur dengan kolom: Thumbnail Gambar, Nama Produk, Kategori, Harga, Stok, serta Tombol Aksi (Edit & Hapus).

Modal Form Interaktif:

Fitur: Jendela pop-up dinamis untuk form tambah produk baru dan form edit data tanpa mengharuskan halaman melakukan reload penuh.

Sistem Notifikasi (Flash Message):

Fitur: Pesan pemberitahuan visual berwarna hijau/merah yang muncul di atas layar ketika aksi tambah, edit, atau hapus data berhasil dieksekusi.

🎨 3. Identitas Visual & Tema Warna
Penerapan gaya visual yang konsisten untuk menciptakan kesan klasik, berkelas, dan bernuansa budaya nusantara:

Cokelat Tua Batik (#4A2E1B):

Penggunaan: Warna utama untuk Navbar, Footer, dan judul-judul besar.

Soga Terracotta (#7A3B1E):

Penggunaan: Warna aksen untuk tombol saat disentuh (hover) dan sub-heading.

Aksen Emas (#D4A373):

Penggunaan: Warna khusus untuk tombol Call to Action (CTA) utama dan elemen sorotan yang elegan.

Latar Belakang Krem (#FAF6F0):

Penggunaan: Warna latar dasar seluruh halaman web agar memberikan kenyamanan visual yang hangat dan tidak terlalu mencolok seperti warna putih bersih.

Tipografi:

Playfair Display (Serif): Digunakan khusus untuk judul/heading agar memberikan kesan megah dan artistik.

Poppins (Sans-serif): Digunakan untuk teks isi (body text) agar bersih, rapi, dan sangat mudah dibaca.