"""
Script to generate Flowchart_Juragan_Batik.pdf using Matplotlib with exact aesthetic layouts.
Pages:
1. Cover & Arsitektur Utama Sistem (Global System Architecture)
2. Flowchart Pengunjung Publik & Pelanggan (Public & Customer Journey)
3. Flowchart Transaksi & Pemesanan WhatsApp (Order & WhatsApp Direct Checkout)
4. Flowchart Panel Administrasi & Kurator (Admin & Kurator Workflow)
"""

import matplotlib.pyplot as plt
from matplotlib.backends.backend_pdf import PdfPages
import matplotlib.patches as patches

# Color palette inspired by Juragan Batik (Batik Warm Theme)
PRIMARY = '#4A2810'       # Deep Batik Brown
SECONDARY = '#904C2E'     # Warm Terracotta
ACCENT = '#D4AF37'        # Royal Gold
BG_COLOR = '#FDFBF7'      # Warm Cream/Parchment
CARD_BG = '#FFFFFF'       # Clean White
TEXT_DARK = '#1C1917'     # Deep Slate
TEXT_MUTED = '#57534E'    # Muted Warm Gray
BORDER_COLOR = '#E7E5E4'  # Soft Border Gray

# Status Colors
GREEN_COLOR = '#15803D'   # Success Green
BLUE_COLOR = '#1D4ED8'    # Action Blue
AMBER_COLOR = '#B45309'   # Decision Amber
PURPLE_COLOR = '#6D28D9'  # Processing Purple

def setup_page(fig, title, subtitle, page_num, total_pages=4):
    ax = fig.add_axes([0, 0, 1, 1])
    ax.set_facecolor(BG_COLOR)
    ax.set_xlim(0, 100)
    ax.set_ylim(0, 100)
    ax.axis('off')

    # Top Header Banner
    header_rect = patches.FancyBboxPatch(
        (3, 91), 94, 6.5,
        boxstyle="round,pad=0.2,rounding_size=0.8",
        facecolor=PRIMARY, edgecolor='none'
    )
    ax.add_patch(header_rect)

    # Gold Accent Line inside Header
    ax.plot([4, 96], [91.3, 91.3], color=ACCENT, linewidth=1.5)

    # Header Texts
    ax.text(5, 95.2, "JURAGAN BATIK — SURAKARTA EST. 1928", fontsize=11, fontweight='bold', color=ACCENT, family='sans-serif')
    ax.text(5, 92.5, title.upper(), fontsize=13, fontweight='bold', color='#FFFFFF', family='sans-serif')
    ax.text(95, 92.5, f"Hal. {page_num} dari {total_pages}", fontsize=10, fontweight='bold', color='#F5EBE1', ha='right', family='sans-serif')

    # Subtitle / description bar
    ax.text(5, 88.5, subtitle, fontsize=9.5, fontstyle='italic', color=TEXT_MUTED, family='sans-serif')
    ax.plot([3, 97], [87.2, 87.2], color=BORDER_COLOR, linewidth=1)

    # Footer
    ax.plot([3, 97], [4.5, 4.5], color=BORDER_COLOR, linewidth=1)
    ax.text(5, 2.5, "Dokumen Spesifikasi Alur Sistem & Flowchart Aplikasi Web Juragan Batik", fontsize=8, color=TEXT_MUTED, family='sans-serif')
    ax.text(95, 2.5, "Teknologi: Laravel 13 • PostgreSQL • Tailwind CSS", fontsize=8, color=PRIMARY, fontweight='bold', ha='right', family='sans-serif')

    return ax

def draw_node(ax, x, y, w, h, text, shape='rect', bg_color='#FFFFFF', border_color=PRIMARY, text_color=TEXT_DARK, font_size=8.5, font_weight='normal', line_width=1.5):
    """Draw a flowchart node with precise text wrapping and aesthetics."""
    if shape == 'pill': # Start / End
        box = patches.FancyBboxPatch(
            (x - w/2, y - h/2), w, h,
            boxstyle=f"round,pad=0.1,rounding_size={h/2}",
            facecolor=bg_color, edgecolor=border_color, linewidth=line_width
        )
    elif shape == 'round': # Process
        box = patches.FancyBboxPatch(
            (x - w/2, y - h/2), w, h,
            boxstyle="round,pad=0.1,rounding_size=0.8",
            facecolor=bg_color, edgecolor=border_color, linewidth=line_width
        )
    elif shape == 'decision': # Diamond
        box = patches.Polygon(
            [[x, y + h/2], [x + w/2, y], [x, y - h/2], [x - w/2, y]],
            closed=True, facecolor=bg_color, edgecolor=border_color, linewidth=line_width
        )
    else: # Default Rect
        box = patches.Rectangle(
            (x - w/2, y - h/2), w, h,
            facecolor=bg_color, edgecolor=border_color, linewidth=line_width
        )
    ax.add_patch(box)
    
    # Text centered
    ax.text(x, y, text, ha='center', va='center', fontsize=font_size, fontweight=font_weight,
            color=text_color, family='sans-serif', multialignment='center')

def draw_arrow(ax, x1, y1, x2, y2, label=None, label_pos='right', color=PRIMARY, line_width=1.3):
    """Draw a clean arrow between two points."""
    ax.annotate(
        '', xy=(x2, y2), xytext=(x1, y1),
        arrowprops=dict(arrowstyle="->", color=color, lw=line_width, shrinkA=0, shrinkB=0,
                        mutation_scale=12)
    )
    if label:
        mx = (x1 + x2) / 2
        my = (y1 + y2) / 2
        offset_x = 1.8 if label_pos == 'right' else (-1.8 if label_pos == 'left' else 0)
        offset_y = 1.0 if label_pos == 'top' else (-1.0 if label_pos == 'bottom' else 0)
        ax.text(mx + offset_x, my + offset_y, label, ha='center', va='center',
                fontsize=7.5, fontweight='bold', color=color, family='sans-serif',
                bbox=dict(boxstyle='square,pad=0.1', facecolor=BG_COLOR, edgecolor='none', alpha=0.85))

def draw_section_box(ax, x, y, w, h, title, fill_color='#F5EBE1', border_color='#D5C3B2'):
    """Draw a grouping container for modular flowchart lanes."""
    box = patches.FancyBboxPatch(
        (x, y), w, h,
        boxstyle="round,pad=0.2,rounding_size=1.2",
        facecolor=fill_color, edgecolor=border_color, linewidth=1, linestyle='--'
    )
    ax.add_patch(box)
    ax.text(x + 1.5, y + h - 2.5, title.upper(), fontsize=9, fontweight='bold',
            color=PRIMARY, family='sans-serif')


def create_page1_architecture(pdf):
    fig = plt.figure(figsize=(11.69, 8.27), dpi=300) # A4 Landscape
    ax = setup_page(fig, "Arsitektur Keseluruhan & Peta Alur Sistem",
                    "Diagram tingkat tinggi interaksi antara Pengunjung Tamu, Pelanggan Terdaftar, Sistem Web, dan Panel Kurator Admin", 1)

    # 3 Big Columns: Public/Guest, Registered User, Admin/Kurator
    # Section 1: Pengunjung / Publik
    draw_section_box(ax, 3, 8, 29, 76, "1. Portal Publik (Tamu)", fill_color='#FFF8F0', border_color='#E5D0BA')
    
    # Section 2: Pelanggan (Member Terdaftar)
    draw_section_box(ax, 35.5, 8, 29, 76, "2. Portal Pelanggan (Member)", fill_color='#F3F8F4', border_color='#C2DEC8')

    # Section 3: Admin & Kurator Panel
    draw_section_box(ax, 68, 8, 29, 76, "3. Portal Kurator (Admin)", fill_color='#F4F5FB', border_color='#C9CEEA')

    # Page 1: Nodes in Column 1 (Publik)
    draw_node(ax, 17.5, 78, 22, 5, "Mulai: Buka Website\n(127.0.0.1:8000)", 'pill', '#4A2810', '#D4AF37', '#FFFFFF', font_weight='bold')
    draw_node(ax, 17.5, 68, 24, 6, "Halaman Beranda (/)\n• Hero Slider Produk\n• Kurasi Wastra Unggulan", 'round', '#FFFFFF', '#904C2E', font_size=8)
    draw_node(ax, 17.5, 56, 24, 7, "Katalog Batik (/katalog)\n• Pencarian Motif\n• Filter Kategori & Harga\n• Prioritas Stok (>0)", 'round', '#FFFFFF', '#904C2E', font_size=8)
    draw_node(ax, 17.5, 43, 24, 6, "Detail Produk (/katalog/{id})\n• Foto, Deskripsi, Stok\n• Tombol Beli / Keranjang", 'round', '#FFFFFF', '#904C2E', font_size=8)
    draw_node(ax, 17.5, 30, 22, 7, "Pilih Aksi Belanja\n(Langsung Beli via WA\natau Login Akun?)", 'decision', '#FEF3C7', '#B45309', '#78350F', font_size=7.5, font_weight='bold')
    draw_node(ax, 17.5, 16, 22, 5.5, "Konsultasi / Direct WA\n(Format Pesan Otomatis)", 'round', '#DCFCE7', '#15803D', '#14532D', font_size=8, font_weight='bold')

    # Arrows Column 1
    draw_arrow(ax, 17.5, 75.5, 17.5, 71)
    draw_arrow(ax, 17.5, 65, 17.5, 59.5)
    draw_arrow(ax, 17.5, 52.5, 17.5, 46)
    draw_arrow(ax, 17.5, 40, 17.5, 33.5)
    draw_arrow(ax, 17.5, 26.5, 17.5, 18.8, label="Direct WA", label_pos='right', color='#15803D')

    # Connecting Arrow to Member
    draw_arrow(ax, 28.5, 30, 36.5, 72, label="Login / Register", label_pos='top', color='#1D4ED8')

    # Page 1: Nodes in Column 2 (Member)
    draw_node(ax, 50, 72, 24, 6, "Autentikasi Pengguna\n• Login (/login)\n• Register Akun Baru", 'round', '#DBEAFE', '#1D4ED8', '#1E3A8A', font_size=8, font_weight='bold')
    draw_node(ax, 50, 60, 24, 6, "Keranjang Belanja (/keranjang)\n• Ubah Jumlah (Qty)\n• Hapus Item & Subtotal", 'round', '#FFFFFF', '#1D4ED8', font_size=8)
    draw_node(ax, 50, 48, 24, 6, "Koleksi Favorit (/favorit)\n• Wishlist Produk Batik\n• Akses Cepat Koleksi", 'round', '#FFFFFF', '#1D4ED8', font_size=8)
    draw_node(ax, 50, 36, 24, 6, "Checkout Keranjang\n(Format WA Multi-Produk\n+ Alamat & No. HP)", 'round', '#DCFCE7', '#15803D', '#14532D', font_size=8, font_weight='bold')
    draw_node(ax, 50, 24, 24, 6.5, "Riwayat Pesanan (/pesanan)\n• Cek No. Resi & Kurir\n• Konfirmasi Barang Diterima", 'round', '#FFFFFF', '#1D4ED8', font_size=8)
    draw_node(ax, 50, 13, 22, 5, "Selesai: Pesanan Selesai", 'pill', '#15803D', '#15803D', '#FFFFFF', font_weight='bold')

    # Arrows Column 2
    draw_arrow(ax, 50, 69, 50, 63)
    draw_arrow(ax, 50, 57, 50, 51)
    draw_arrow(ax, 50, 45, 50, 39)
    draw_arrow(ax, 50, 33, 50, 27.3)
    draw_arrow(ax, 50, 20.7, 50, 15.5)

    # Connecting Column 2 to Column 3 (Order synchronization)
    draw_arrow(ax, 62, 36, 70, 49, label="Pesanan Masuk", label_pos='top', color='#6D28D9')

    # Page 1: Nodes in Column 3 (Admin)
    draw_node(ax, 82.5, 76, 24, 6, "Login Portal Admin\n(Role: is_admin = true)", 'round', '#EDE9FE', '#6D28D9', '#4C1D95', font_size=8, font_weight='bold')
    draw_node(ax, 82.5, 63, 24, 6, "Manajemen Produk\n• Tambah/Edit Produk & Stok\n• Upload Foto & Kategori", 'round', '#FFFFFF', '#6D28D9', font_size=8)
    draw_node(ax, 82.5, 49, 24, 7, "Manajemen Pesanan\n• Buat Pesanan Manual\n• Input Resi / Kirim Paket\n• Status / Pembatalan", 'round', '#FFFFFF', '#6D28D9', font_size=8)
    draw_node(ax, 82.5, 34, 24, 6.5, "Laporan Keuangan (/finance)\n• Metrik Omzet & Ongkir\n• Ekspor CSV / Excel", 'round', '#FFFFFF', '#6D28D9', font_size=8)
    draw_node(ax, 82.5, 20, 24, 6, "Manajemen Pengguna\n• Tambah Admin / Reset Sandi\n• Toggle Status Aktif / Nonaktif", 'round', '#FFFFFF', '#6D28D9', font_size=8)

    # Arrows Column 3
    draw_arrow(ax, 82.5, 73, 82.5, 66)
    draw_arrow(ax, 82.5, 60, 82.5, 52.5)
    draw_arrow(ax, 82.5, 45.5, 82.5, 37.3)
    draw_arrow(ax, 82.5, 30.7, 82.5, 23)

    pdf.savefig(fig)
    plt.close(fig)


def create_page2_public_flow(pdf):
    fig = plt.figure(figsize=(11.69, 8.27), dpi=300)
    ax = setup_page(fig, "Flowchart Alur Pengunjung Publik & Pelanggan",
                    "Detail navigasi pengunjung dari halaman Beranda, pencarian di Katalog Batik, hingga interaksi Keranjang & Favorit", 2)

    # Start
    draw_node(ax, 10, 78, 14, 5, "Mulai", 'pill', '#4A2810', '#D4AF37', '#FFFFFF', font_weight='bold')
    draw_node(ax, 10, 66, 16, 6, "Buka Beranda\nURL: /", 'round', '#FFFFFF', PRIMARY)
    draw_node(ax, 10, 50, 16, 7, "Cek Komponen:\n• Slider Mahakarya\n• Koleksi Aktif\n• Segel Keaslian", 'round', '#FFFFFF', PRIMARY, font_size=8)

    draw_arrow(ax, 10, 75.5, 10, 69)
    draw_arrow(ax, 10, 63, 10, 53.5)

    # Menu pilihan dari Beranda
    draw_node(ax, 32, 50, 16, 7, "Aksi Pengunjung\ndi Navigasi Bar?", 'decision', '#FEF3C7', '#B45309', '#78350F', font_size=8, font_weight='bold')
    draw_arrow(ax, 18, 50, 24, 50)

    # Cabang 1: Cerita Kami
    draw_node(ax, 32, 70, 16, 5.5, "Cerita Kami\nURL: /cerita-kami", 'round', '#FFFFFF', PRIMARY, font_size=8)
    draw_arrow(ax, 32, 53.5, 32, 67.2, label="Filosofi", label_pos='right')

    # Cabang 2: Ke Katalog
    draw_node(ax, 32, 28, 18, 6.5, "Katalog Batik\nURL: /katalog", 'round', '#F5EBE1', PRIMARY, font_weight='bold')
    draw_arrow(ax, 32, 46.5, 32, 31.3, label="Katalog", label_pos='right')

    # Alur di dalam Katalog
    draw_node(ax, 56, 38, 18, 7, "Gunakan Filter?\n• Kategori Wastra\n• Rentang Harga\n• Kata Kunci Search", 'decision', '#FEF3C7', '#B45309', '#78350F', font_size=7.5, font_weight='bold')
    draw_arrow(ax, 41, 28, 48, 38, label="Eksplorasi", label_pos='top')

    draw_node(ax, 56, 56, 18, 6.5, "Query Eloquent:\n• Filter Kategori & Harga\n• Urutkan Stok > 0 Dulu\n• Barang Habis di Bawah", 'round', '#EFF6FF', '#1D4ED8', '#1E3A8A', font_size=7.5)
    draw_arrow(ax, 56, 41.5, 56, 52.7, label="Ya", label_pos='right')

    draw_node(ax, 56, 18, 18, 6, "Tampilkan Daftar:\n• Kartu Produk & Harga\n• Badge Kategori & Stok", 'round', '#FFFFFF', PRIMARY, font_size=8)
    draw_arrow(ax, 56, 34.5, 56, 21, label="Tidak", label_pos='right')
    draw_arrow(ax, 65, 56, 65, 18)
    draw_arrow(ax, 65, 18, 65, 18)

    # Dari List ke Detail Produk
    draw_node(ax, 80, 18, 18, 6.5, "Klik Produk:\nHalaman Detail\nURL: /katalog/{id}", 'round', '#FFFFFF', PRIMARY, font_size=8, font_weight='bold')
    draw_arrow(ax, 65, 18, 71, 18, label="Pilih Kain", label_pos='top')

    # Cek Stok di Detail Produk
    draw_node(ax, 80, 36, 16, 7, "Apakah Stok\nTersedia (>0)?", 'decision', '#FEF3C7', '#B45309', '#78350F', font_size=8, font_weight='bold')
    draw_arrow(ax, 80, 21.3, 80, 32.5)

    # Jika Habis
    draw_node(ax, 94, 36, 10, 5, "Badge:\n'Habis'", 'round', '#FEE2E2', '#DC2626', '#991B1B', font_size=8, font_weight='bold')
    draw_arrow(ax, 88, 36, 89, 36, label="Tidak", label_pos='top', color='#DC2626')

    # Jika Ada Stok
    draw_node(ax, 80, 56, 18, 7.5, "Pilihan Pembelian:\n1. Beli Langsung (WA)\n2. Tambah Keranjang\n3. Simpan ke Favorit", 'round', '#DCFCE7', '#15803D', '#14532D', font_size=8, font_weight='bold')
    draw_arrow(ax, 80, 39.5, 80, 52.2, label="Ya", label_pos='right', color='#15803D')

    # Akhir sesi
    draw_node(ax, 80, 74, 16, 5, "Menuju Checkout\natau Lanjut Belanja", 'pill', PRIMARY, ACCENT, '#FFFFFF', font_weight='bold')
    draw_arrow(ax, 80, 59.8, 80, 71.5)

    pdf.savefig(fig)
    plt.close(fig)


def create_page3_checkout_flow(pdf):
    fig = plt.figure(figsize=(11.69, 8.27), dpi=300)
    ax = setup_page(fig, "Flowchart Transaksi & Alur Pemesanan WhatsApp",
                    "Mekanisme transaksi otomatis dari perakitan keranjang, kompilasi data pembeli, hingga direct API WhatsApp Juragan Batik", 3)

    # 2 Jalur: Jalur A (Direct Buy Satu Produk) vs Jalur B (Keranjang Belanja Multi Produk)
    draw_section_box(ax, 4, 10, 43, 74, "Jalur A: Beli Langsung (Single Product)", fill_color='#FFFBF5', border_color='#E5D5C5')
    draw_section_box(ax, 53, 10, 43, 74, "Jalur B: Keranjang Belanja (Multi Product)", fill_color='#F4FBF6', border_color='#BFDCC6')

    # Jalur A (Kiri)
    draw_node(ax, 25.5, 75, 26, 5, "Mulai: Klik 'Pesan Sekarang'", 'pill', PRIMARY, ACCENT, '#FFFFFF', font_weight='bold')
    draw_node(ax, 25.5, 63, 28, 6, "Modal Pop-up Checkout Langsung\n• Atur Jumlah Kuantiti (Qty)\n• Ambil Nama & No. HP Akun", 'round', '#FFFFFF', PRIMARY, font_size=8)
    draw_node(ax, 25.5, 50, 26, 7, "Cek Status Login\nPengguna?", 'decision', '#FEF3C7', '#B45309', '#78350F', font_size=8, font_weight='bold')

    draw_arrow(ax, 25.5, 72.5, 25.5, 66)
    draw_arrow(ax, 25.5, 60, 25.5, 53.5)

    # Jika Belum Login
    draw_node(ax, 11, 37, 12, 6, "Input Manual:\nNama & HP", 'round', '#FFFFFF', PRIMARY, font_size=7.5)
    draw_arrow(ax, 18.5, 50, 11, 40, label="Tamu", label_pos='left')

    # Jika Sudah Login
    draw_node(ax, 38, 37, 14, 6, "Auto-fill Profil:\nNama, HP, Alamat", 'round', '#DBEAFE', '#1D4ED8', '#1E3A8A', font_size=7.5)
    draw_arrow(ax, 32.5, 50, 38, 40, label="Member", label_pos='right')

    # Gabung ke generate WhatsApp
    draw_node(ax, 25.5, 23, 30, 7.5, "Generate URL wa.me/6281382958012\nFormat Teks Terenkripsi URL:\n'Halo Juragan Batik, saya ingin memesan...'", 'round', '#DCFCE7', '#15803D', '#14532D', font_size=7.8, font_weight='bold')
    draw_arrow(ax, 11, 34, 21, 26.8)
    draw_arrow(ax, 38, 34, 30, 26.8)

    draw_node(ax, 25.5, 14, 24, 4.5, "Terhubung ke WhatsApp Kurator", 'pill', '#15803D', '#15803D', '#FFFFFF', font_weight='bold')
    draw_arrow(ax, 25.5, 19.2, 25.5, 16.3)

    # Jalur B (Kanan)
    draw_node(ax, 74.5, 75, 26, 5, "Mulai: Masuk ke /keranjang", 'pill', '#1D4ED8', '#60A5FA', '#FFFFFF', font_weight='bold')
    draw_node(ax, 74.5, 63, 28, 6, "Tampilkan Daftar Item:\n• Gambar, Judul Kain, Harga\n• Input Qty (+ / -) & Hapus Item", 'round', '#FFFFFF', '#1D4ED8', font_size=8)
    draw_node(ax, 74.5, 51, 26, 6, "Kalkulasi Subtotal:\nSubtotal = ∑ (Harga × Qty)\nCek Validasi Stok Riil", 'round', '#EFF6FF', '#1D4ED8', font_size=8)
    draw_node(ax, 74.5, 38, 26, 6.5, "Klik 'Checkout WhatsApp'\n(POST /keranjang/checkout)", 'round', '#FEF3C7', '#B45309', '#78350F', font_size=8, font_weight='bold')

    draw_arrow(ax, 74.5, 72.5, 74.5, 66)
    draw_arrow(ax, 74.5, 60, 74.5, 54)
    draw_arrow(ax, 74.5, 48, 74.5, 41.3)

    draw_node(ax, 74.5, 25, 30, 7.5, "Susun Pesan WhatsApp Multi-Produk:\n1. Daftar Seluruh Kain & Qty\n2. Grand Total Pembayaran\n3. Alamat Kirim & Penerima", 'round', '#DCFCE7', '#15803D', '#14532D', font_size=7.8, font_weight='bold')
    draw_arrow(ax, 74.5, 34.7, 74.5, 28.8)

    draw_node(ax, 74.5, 14, 24, 4.5, "Kurator Memproses Pesanan", 'pill', '#15803D', '#15803D', '#FFFFFF', font_weight='bold')
    draw_arrow(ax, 74.5, 21.2, 74.5, 16.3)

    pdf.savefig(fig)
    plt.close(fig)


def create_page4_admin_flow(pdf):
    fig = plt.figure(figsize=(11.69, 8.27), dpi=300)
    ax = setup_page(fig, "Flowchart Panel Administrasi & Kurator Wastra",
                    "Alur kerja pengelolaan produk, input pesanan logistik, monitoring pengguna, dan rekap keuangan", 4)

    # Start Admin
    draw_node(ax, 10, 76, 14, 5, "Mulai", 'pill', '#4A2810', '#D4AF37', '#FFFFFF', font_weight='bold')
    draw_node(ax, 10, 62, 16, 6, "Akses /admin/*\nCek Middleware:\nauth + is_admin", 'round', '#EDE9FE', '#6D28D9', '#4C1D95', font_size=8)
    draw_node(ax, 10, 45, 16, 7, "Apakah Role\nAdmin Valid?", 'decision', '#FEF3C7', '#B45309', '#78350F', font_size=8, font_weight='bold')

    draw_arrow(ax, 10, 73.5, 10, 65)
    draw_arrow(ax, 10, 59, 10, 48.5)

    # Ditolak
    draw_node(ax, 10, 26, 16, 5, "Redirect / Home\n(Akses Ditolak)", 'pill', '#DC2626', '#DC2626', '#FFFFFF', font_size=8, font_weight='bold')
    draw_arrow(ax, 10, 41.5, 10, 28.5, label="Tidak", label_pos='left', color='#DC2626')

    # Diterima -> Dashboard Menu
    draw_node(ax, 32, 45, 18, 8, "Navigasi Menu Admin:\n1. Produk (/admin/products)\n2. Pesanan (/admin/orders)\n3. Keuangan (/admin/finance)\n4. Pengguna (/admin/users)", 'round', '#FFFFFF', PRIMARY, font_size=8, font_weight='bold')
    draw_arrow(ax, 18, 45, 23, 45, label="Ya", label_pos='top', color='#15803D')

    # Modul 1: Produk & Kategori
    draw_node(ax, 58, 73, 22, 7, "Modul Produk & Kategori\n• Form Tambah / Edit Produk\n• Upload & Crop Foto (3:4)\n• Modal AJAX Tambah Kategori", 'round', '#FFFFFF', PRIMARY, font_size=7.5)
    draw_arrow(ax, 32, 49, 47, 73, label="Produk", label_pos='top')

    # Modul 2: Pesanan (Orders)
    draw_node(ax, 58, 55, 22, 8, "Modul Pesanan (Orders)\n• Form Buat Pesanan Baru\n• Auto-fill Info User Terdaftar\n• Update Resi & Kurir Ekspedisi\n• Pembatalan dengan Alasan", 'round', '#EFF6FF', '#1D4ED8', '#1E3A8A', font_size=7.5, font_weight='bold')
    draw_arrow(ax, 41, 45, 47, 55, label="Pesanan", label_pos='top')

    # Modul 3: Keuangan (Finance)
    draw_node(ax, 58, 35, 22, 7.5, "Modul Laporan Keuangan\n• Metrik Omzet Kotor & Bersih\n• Breakdown Kurir & Metode Bayar\n• Filter Tanggal Transaksi\n• Ekspor Laporan CSV/Excel", 'round', '#DCFCE7', '#15803D', '#14532D', font_size=7.5)
    draw_arrow(ax, 41, 45, 47, 35, label="Finance", label_pos='bottom')

    # Modul 4: Pengguna (Users)
    draw_node(ax, 58, 17, 22, 7, "Modul Manajemen User\n• Tambah Akun Admin Baru\n• Toggle Status Aktif / Nonaktif\n• Reset Kata Sandi Instan\n• Hapus Single & Bulk User", 'round', '#FFFBEB', '#B45309', '#78350F', font_size=7.5)
    draw_arrow(ax, 32, 41, 47, 17, label="Users", label_pos='bottom')

    # Action Target / Result
    draw_node(ax, 86, 55, 18, 8, "Database PostgreSQL:\n• Update Tabel 'orders'\n• Update Status: 'shipped'\n• Generate Notifikasi ke User", 'round', '#F3F4F6', '#374151', '#111827', font_size=7.5)
    draw_arrow(ax, 69, 55, 77, 55)

    draw_node(ax, 86, 35, 18, 6, "Berkas Laporan:\n'laporan-penjualan.csv'\nSiap Diunduh", 'round', '#DCFCE7', '#15803D', '#14532D', font_size=7.5, font_weight='bold')
    draw_arrow(ax, 69, 35, 77, 35)

    draw_node(ax, 86, 73, 18, 5, "Selesai: Data Disimpan", 'pill', PRIMARY, ACCENT, '#FFFFFF', font_weight='bold')
    draw_arrow(ax, 69, 73, 77, 73)

    pdf.savefig(fig)
    plt.close(fig)


def main():
    output_filename = "Flowchart_Lengkap_Juragan_Batik.pdf"
    print(f"Membuat {output_filename}...")
    with PdfPages(output_filename) as pdf:
        create_page1_architecture(pdf)
        print("Halaman 1 (Arsitektur Sistem) selesai.")
        create_page2_public_flow(pdf)
        print("Halaman 2 (Alur Publik & Pelanggan) selesai.")
        create_page3_checkout_flow(pdf)
        print("Halaman 3 (Alur Transaksi & WhatsApp) selesai.")
        create_page4_admin_flow(pdf)
        print("Halaman 4 (Alur Panel Admin) selesai.")

    print(f"Berhasil membuat dokumen PDF: {output_filename}")

if __name__ == "__main__":
    main()
