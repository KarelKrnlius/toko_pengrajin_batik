# -*- coding: utf-8 -*-
"""
Generate a professional, fully structured PRD (.docx) for Juragan Batik.
Follows the exact 7-section structure of the reference PRD:
1. Pendahuluan & Latar Belakang
2. Analisis Kebutuhan (User Needs & System Requirements)
3. Ruang Lingkup (In-Scope & Out-of-Scope)
4. Peran Pengguna dan Hak Akses (Roles & Access Matrix)
5. Spesifikasi Fitur Utama (Core Features)
6. Kriteria Keberhasilan (Success Metrics)
7. Rencana Pengembangan & Milestones
"""

import zipfile
import html

def escape_xml(text):
    if text is None:
        return ""
    return html.escape(str(text))

class DocxBuilder:
    def __init__(self):
        self.body_elements = []

    def add_p(self, text, style="Normal", align="left", bold=False, italic=False, font_size=11, color="1C1917", space_before=0, space_after=120, bullet=False):
        """Add a paragraph with text formatting."""
        p_pr = f'<w:pPr>'
        if align != "left":
            p_pr += f'<w:jc w:val="{align}"/>'
        if space_before > 0 or space_after > 0:
            p_pr += f'<w:spacing w:before="{space_before}" w:after="{space_after}"/>'
        if bullet:
            # Bullet indentation
            p_pr += f'<w:ind w:left="420" w:hanging="260"/>'
        p_pr += f'</w:pPr>'

        r_pr = f'<w:rPr>'
        r_pr += f'<w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>'
        if bold:
            r_pr += '<w:b/>'
        if italic:
            r_pr += '<w:i/>'
        r_pr += f'<w:sz w:val="{font_size * 2}"/>'
        r_pr += f'<w:color w:val="{color}"/>'
        r_pr += f'</w:rPr>'

        bullet_prefix = '<w:r><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:color w:val="4A2810"/></w:rPr><w:t>●  </w:t></w:r>' if bullet else ''

        run = f'<w:r>{r_pr}<w:t xml:space="preserve">{escape_xml(text)}</w:t></w:r>'
        self.body_elements.append(f'<w:p>{p_pr}{bullet_prefix}{run}</w:p>')

    def add_subbullet(self, title, text="", bold_title=True, font_size=10.5, space_after=60):
        """Add indented sub-bullet (e.g. ○ Detail)."""
        p_pr = f'<w:pPr><w:ind w:left="840" w:hanging="260"/><w:spacing w:after="{space_after}"/></w:pPr>'
        r_bullet = '<w:r><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:color w:val="904C2E"/></w:rPr><w:t>○  </w:t></w:r>'
        r_title = f'<w:r><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:b/><w:sz w:val="{int(font_size*2)}"/><w:color w:val="1C1917"/></w:rPr><w:t xml:space="preserve">{escape_xml(title)}: </w:t></w:r>' if bold_title else ''
        r_text = f'<w:r><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:sz w:val="{int(font_size*2)}"/><w:color w:val="333333"/></w:rPr><w:t xml:space="preserve">{escape_xml(text)}</w:t></w:r>'
        self.body_elements.append(f'<w:p>{p_pr}{r_bullet}{r_title}{r_text}</w:p>')

    def add_heading1(self, text):
        self.add_p(text, bold=True, font_size=14, color="4A2810", space_before=240, space_after=120)

    def add_heading2(self, text):
        self.add_p(text, bold=True, font_size=12, color="904C2E", space_before=180, space_after=80)

    def add_divider(self):
        """Add horizontal line divider."""
        self.body_elements.append(
            '<w:p><w:pPr><w:pBdr><w:bottom w:val="single" w:sz="6" w:space="1" w:color="D5C3B2"/></w:pBdr><w:spacing w:before="120" w:after="160"/></w:pPr></w:p>'
        )

    def add_table(self, headers, rows, col_widths=None):
        """Add styled Word table."""
        tbl_pr = '''<w:tblPr>
            <w:tblW w:w="9360" w:type="dxa"/>
            <w:tblBorders>
                <w:top w:val="single" w:sz="6" w:space="0" w:color="D5C3B2"/>
                <w:left w:val="single" w:sz="6" w:space="0" w:color="D5C3B2"/>
                <w:bottom w:val="single" w:sz="8" w:space="0" w:color="4A2810"/>
                <w:right w:val="single" w:sz="6" w:space="0" w:color="D5C3B2"/>
                <w:insideH w:val="single" w:sz="4" w:space="0" w:color="E7E5E4"/>
                <w:insideV w:val="single" w:sz="4" w:space="0" w:color="E7E5E4"/>
            </w:tblBorders>
            <w:tblCellMar>
                <w:top w:w="120" w:type="dxa"/>
                <w:left w:w="140" w:type="dxa"/>
                <w:bottom w:w="120" w:type="dxa"/>
                <w:right w:w="140" w:type="dxa"/>
            </w:tblCellMar>
        </w:tblPr>'''

        tbl_grid = '<w:tblGrid>'
        if col_widths:
            for w in col_widths:
                tbl_grid += f'<w:gridCol w:w="{w}"/>'
        tbl_grid += '</w:tblGrid>'

        xml_rows = []

        # Header Row
        tr = '<w:tr><w:trPr><w:tblHeader/></w:trPr>'
        for i, h in enumerate(headers):
            w_str = f'<w:tcW w:w="{col_widths[i]}" w:type="dxa"/>' if col_widths else ''
            align = "left" if i == 0 else "center"
            tr += f'''<w:tc>
                <w:tcPr>{w_str}<w:shd w:val="clear" w:color="auto" w:fill="4A2810"/></w:tcPr>
                <w:p><w:pPr><w:jc w:val="{align}"/><w:spacing w:after="40" w:before="40"/></w:pPr>
                    <w:r><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/><w:b/><w:sz w:val="18"/><w:color w:val="FFFFFF"/></w:rPr>
                        <w:t>{escape_xml(h)}</w:t>
                    </w:r>
                </w:p>
            </w:tc>'''
        tr += '</w:tr>'
        xml_rows.append(tr)

        # Data Rows
        for r_idx, row in enumerate(rows):
            bg_fill = "FDFBF7" if (r_idx % 2 == 1) else "FFFFFF"
            tr = '<w:tr>'
            for i, val in enumerate(row):
                w_str = f'<w:tcW w:w="{col_widths[i]}" w:type="dxa"/>' if col_widths else ''
                align = "left" if i == 0 else "center"
                bold_tag = '<w:b/>' if (val in ['✅', '❌'] or '✅' in val) else ''
                text_color = "15803D" if val == '✅' else ("DC2626" if val == '❌' else ("4A2810" if i==0 else "333333"))
                tr += f'''<w:tc>
                    <w:tcPr>{w_str}<w:shd w:val="clear" w:color="auto" w:fill="{bg_fill}"/></w:tcPr>
                    <w:p><w:pPr><w:jc w:val="{align}"/><w:spacing w:after="30" w:before="30"/></w:pPr>
                        <w:r><w:rPr><w:rFonts w:ascii="Calibri" w:hAnsi="Calibri"/>{bold_tag}<w:sz w:val="18"/><w:color w:val="{text_color}"/></w:rPr>
                            <w:t>{escape_xml(val)}</w:t>
                        </w:r>
                    </w:p>
                </w:tc>'''
            tr += '</w:tr>'
            xml_rows.append(tr)

        table_xml = f'<w:tbl>{tbl_pr}{tbl_grid}{"".join(xml_rows)}</w:tbl>'
        self.body_elements.append(table_xml)

    def save(self, filepath):
        doc_xml = f'''<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <w:body>
        {"".join(self.body_elements)}
        <w:sectPr>
            <w:pgSz w:w="11906" w:h="16838"/>
            <w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440" w:header="720" w:footer="720" w:gutter="0"/>
        </w:sectPr>
    </w:body>
</w:document>'''

        content_types_xml = '''<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
</Types>'''

        rels_xml = '''<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>'''

        with zipfile.ZipFile(filepath, 'w', zipfile.ZIP_DEFLATED) as z:
            z.writestr('[Content_Types].xml', content_types_xml)
            z.writestr('_rels/.rels', rels_xml)
            z.writestr('word/document.xml', doc_xml)


def generate_prd():
    doc = DocxBuilder()

    # Title & Metadata
    doc.add_p("Product Requirement Document (PRD)", bold=True, font_size=20, color="4A2810", space_after=60)
    doc.add_p("Platform E-Commerce & Kurasi Batik Warisan (Juragan Batik)", bold=True, font_size=15, color="904C2E", space_after=180)

    doc.add_p("Instansi / Brand: Juragan Batik Surakarta (Est. 1928)", bold=True, font_size=10.5, color="333333", space_after=20)
    doc.add_p("Versi: 1.0 (Production Ready)", font_size=10.5, color="333333", space_after=20)
    doc.add_p("Tanggal: 10 November 2026", font_size=10.5, color="333333", space_after=20)
    doc.add_p("Status: Production / Deployed", bold=True, font_size=10.5, color="15803D", space_after=140)

    doc.add_divider()

    # 1. Pendahuluan & Latar Belakang
    doc.add_heading1("1. Pendahuluan & Latar Belakang")
    doc.add_p(
        "Sebagai sentra pelestarian batik tulis dan cap klasik yang telah berdiri sejak 1928 di Surakarta, "
        "Juragan Batik melayani pemesanan kain adat, jarik, kemeja, dan sarung eksklusif. "
        "Sebelumnya, proses kurasi, publikasi motif, konsultasi ukuran/stok, hingga pencatatan transaksi "
        "masih dilakukan secara terpisah melalui catatan manual atau percakapan chat privat yang tidak terorganisir. "
        "Hal ini menimbulkan berbagai tantangan operasional:",
        space_after=80
    )
    doc.add_p("Penyebaran informasi stok dan katalog motif yang tersebar secara sporadis tanpa katalog digital terpadu.", bullet=True, space_after=40)
    doc.add_p("Resiko 'overselling' atau pesanan ganda akibat ketiadaan validasi ketersediaan stok riil yang sinkron.", bullet=True, space_after=40)
    doc.add_p("Kurator dan manajemen kesulitan memantau metrik performa omzet penjualan, rekap ongkir, serta pelacakan resi ekspedisi.", bullet=True, space_after=40)
    doc.add_p("Pelanggan mengalami kesulitan dalam menyusun daftar pesanan kain multi-motif secara akurat beserta data pengiriman.", bullet=True, space_after=100)

    doc.add_p(
        "Platform Web E-Commerce Juragan Batik dibangun untuk mendigitalisasi, mengintegrasikan, "
        "dan mempermudah seluruh alur pameran karya seni wastra, konsultasi pemesanan personal via WhatsApp API, "
        "manajemen logistik pengiriman, serta rekapitulasi keuangan usaha secara profesional dan terpusat.",
        bold=True, color="4A2810", space_after=160
    )

    doc.add_divider()

    # 2. Analisis Kebutuhan (Needs Analysis)
    doc.add_heading1("2. Analisis Kebutuhan (Needs Analysis)")

    doc.add_heading2("2.1. Kebutuhan Pengguna (User Needs)")
    doc.add_p("Pengunjung / Pembeli (Tamu & Pelanggan):", bold=True, color="4A2810", space_after=40)
    doc.add_subbullet("Katalog Terkurasi", "Membutuhkan kemudahan eksplorasi motif batik lengkap dengan filter kategori, pencarian kata kunci, dan penyaringan rentang harga.")
    doc.add_subbullet("Transparansi Stok", "Membutuhkan kejelasan status barang (prioritas stok tersedia di atas dan penandaan jelas untuk produk yang habis).")
    doc.add_subbullet("Pemesanan Efisien", "Membutuhkan jalur pembelian langsung maupun keranjang multi-kain yang otomatis tersusun rapi ke pesan WhatsApp resmi.")
    doc.add_subbullet("Pelacakan Pesanan", "Membutuhkan transparansi status pengiriman paket, nama kurir ekspedisi, nomor resi riil, serta tombol konfirmasi barang diterima.")

    doc.add_p("Kurator Wastra / Pengelola Toko:", bold=True, color="4A2810", space_before=100, space_after=40)
    doc.add_subbullet("Kelola Katalog & Stok", "Membutuhkan portal untuk menambah, memperbarui harga/stok, dan mengunggah foto kain batik dengan proporsi 3:4 yang konsisten.")
    doc.add_subbullet("Kelola Kategori Cepat", "Membutuhkan modal interaktif AJAX untuk menambah atau menghapus klasifikasi kategori wastra tanpa memuat ulang halaman.")
    doc.add_subbullet("Manajemen Logistik Pesanan", "Membutuhkan fitur input nomor resi pengiriman, pemilihan jasa kurir, dan pencatatan alasan jika terjadi pembatalan pesanan.")

    doc.add_p("Pemilik Usaha / Admin Utama:", bold=True, color="4A2810", space_before=100, space_after=40)
    doc.add_subbullet("Kontrol Penuh Hak Akses", "Membutuhkan manajemen akun pengguna (tambah kurator, toggle aktif/nonaktif, reset kata sandi, dan hapus akun).")
    doc.add_subbullet("Analitik & Rekap Keuangan", "Membutuhkan dashboard omzet kotor, total ongkos kirim, rasio pesanan berhasil, breakdown kurir & metode pembayaran, serta fitur ekspor rekap penjualan ke format Excel/CSV.")

    doc.add_heading2("2.2. Kebutuhan Sistem (System Requirements)")
    doc.add_p("Autentikasi & Otorisasi Ketat: Pemisahan hak akses antara Pelanggan (Member) dan Administrator Kurator menggunakan middleware Laravel 'auth' dan 'is_admin'.", bullet=True, space_after=40)
    doc.add_p("Validasi Aturan Bisnis Stok: Otomatis memprioritaskan produk aktif (stok > 0) di beranda dan urutan teratas katalog, serta mencegah checkout jika stok habis.", bullet=True, space_after=40)
    doc.add_p("Responsif & Accessible: Tampilan adaptif di semua jenis perangkat (smartphone, tablet, laptop, dan desktop) berkat arsitektur antarmuka Tailwind CSS.", bullet=True, space_after=140)

    doc.add_divider()

    # 3. Ruang Lingkup (Scope)
    doc.add_heading1("3. Ruang Lingkup (Scope)")

    doc.add_heading2("3.1. Dalam Jangkauan (In-Scope)")
    doc.add_p("1. Manajemen Pengguna: Registrasi & Login sesi, profil pengguna, kelola alamat kirim, ganti password, dan panel kontrol admin pengguna.", space_after=40)
    doc.add_p("2. Katalog & Eksplorasi Produk: Hero slider beranda, filter kategori, slider harga, pengurutan cerdas, dan detail produk ringkas.", space_after=40)
    doc.add_p("3. Keranjang & Favorit: Penambahan ke wishlist, kelola kuantiti keranjang belanja, dan kalkulasi subtotal instan.", space_after=40)
    doc.add_p("4. Alur Transaksi WhatsApp: Penyusunan teks pesanan otomatis terenkripsi URL menuju WhatsApp Customer Service resmi (+62 813-8295-8012).", space_after=40)
    doc.add_p("5. Manajemen Pesanan & Logistik: Pembuatan order manual admin, pelacakan nomor resi, histori pesanan pengguna, dan konfirmasi barang sampai.", space_after=40)
    doc.add_p("6. Dashboard Finansial & Ekspor Data: Grafik/metrik penjualan, filter rentang tanggal transaksi, serta ekspor file laporan Excel/CSV.", space_after=100)

    doc.add_heading2("3.2. Diluar Jangkauan (Out-of-Scope)")
    doc.add_p("1. Pembayaran otomatis via Virtual Account / Credit Card Gateway pihak ketiga (Midtrans/Xendit) — transaksi saat ini difokuskan pada transfer bank dan verifikasi langsung via WhatsApp.", space_after=40)
    doc.add_p("2. Sistem API ongkos kirim real-time otomatis (RajaOngkir) — input ongkir saat ini diinput langsung oleh kurator saat merancang pesanan.", space_after=40)
    doc.add_p("3. Sistem pengembalian dana (refund) mandiri terotomatisasi di web — diakomodasi melalui koordinasi manual kurator via WhatsApp.", space_after=140)

    doc.add_divider()

    # 4. Peran Pengguna dan Hak Akses (Roles & Access Matrix)
    doc.add_heading1("4. Peran Pengguna dan Hak Akses (Roles & Access Matrix)")

    headers = ["Fitur / Hak Akses", "Pengunjung (Tamu)", "Pelanggan (Member)", "Kurator / Admin"]
    rows = [
        ["Lihat Beranda & Katalog Batik", "✅", "✅", "✅"],
        ["Simpan Produk ke Favorit", "❌", "✅", "✅"],
        ["Kelola Keranjang Belanja", "❌", "✅", "✅"],
        ["Checkout via WhatsApp", "✅ (Direct)", "✅ (Multi-Item)", "✅"],
        ["Lihat Riwayat Pesanan Saya", "❌", "✅", "✅"],
        ["Konfirmasi Penerimaan Barang", "❌", "✅", "✅"],
        ["Kelola Produk & Stok Batik", "❌", "❌", "✅"],
        ["Kelola Kategori Wastra (AJAX)", "❌", "❌", "✅"],
        ["Buat & Input Resi Pesanan", "❌", "❌", "✅"],
        ["Laporan Keuangan & Ekspor CSV", "❌", "❌", "✅"],
        ["Kelola Akun & Reset Password User", "❌", "❌", "✅"]
    ]
    col_widths = [3600, 1800, 1960, 2000]
    doc.add_table(headers, rows, col_widths)

    doc.add_divider()

    # 5. Spesifikasi Fitur Utama (Core Features)
    doc.add_heading1("5. Spesifikasi Fitur Utama (Core Features)")

    doc.add_heading2("5.1. Modul Autentikasi & Profil Pengguna")
    doc.add_p("Sistem Login & Registrasi Sesi: Validasi email unik, hashing kata sandi Bcrypt, dan proteksi middleware untuk rute privat.", bullet=True, space_after=40)
    doc.add_p("Profil Pelanggan: Menampilkan dan memperbarui nama lengkap, nomor WhatsApp aktif, alamat pengiriman default, serta fitur ubah kata sandi mandiri.", bullet=True, space_after=100)

    doc.add_heading2("5.2. Modul Katalog Wastra (Product Directory)")
    doc.add_p("Landing Page Beranda: Slider produk otomatis sinkron dengan durasi 5 detik, fitur hover pause, serta galeri produk unggulan yang hanya menampilkan barang berstok aktif.", bullet=True, space_after=40)
    doc.add_p("Katalog Interaktif: Filter kategori wastra, dual-thumb range slider harga yang presisi sejajar, live text search motif, serta pengurutan cerdas yang memposisikan barang habis di bagian terbawah.", bullet=True, space_after=40)
    doc.add_p("Detail Produk Elegan: Tampilan foto rasio proporsional, uraian filosofi motif, ketersediaan stok, dan tombol cepat beli via WhatsApp.", bullet=True, space_after=100)

    doc.add_heading2("5.3. Modul Pemesanan (Order & WhatsApp Flow)")
    doc.add_p("Direct Buy (Beli Langsung): Modal pop-up cepat untuk memesan 1 jenis kain secara instan dengan input kuantiti dan auto-fill profil.", bullet=True, space_after=40)
    doc.add_p("Keranjang Belanja (Multi-Item): Pengelolaan jumlah kain belanja, kalkulasi otomatis subtotal belanja, dan pembuatan link WhatsApp Checkout komprehensif.", bullet=True, space_after=40)
    doc.add_p("Format Pesan Otomatis: Sistem merangkum nama pemesan, rincian kain, alamat tujuan, dan total biaya ke dalam template pesan WhatsApp yang siap dikirim.", bullet=True, space_after=100)

    doc.add_heading2("5.4. Modul Manajemen Pesanan & Logistik (Order Tracking)")
    doc.add_p("Input Pesanan Manual Admin: Formulir pembuatan order oleh kurator dengan fitur pencarian user untuk auto-fill nama, nomor HP, dan alamat.", bullet=True, space_after=40)
    doc.add_p("Manajemen Status & Ekspedisi: Pembaruan status pesanan (pending -> shipped -> completed / cancelled), penambahan nomor resi, dan pilihan kurir (JNE, J&T, SiCepat, POS, dll).", bullet=True, space_after=40)
    doc.add_p("Halaman Pesanan Saya (/pesanan): Pelanggan dapat memantau perjalanan pesanan mereka secara real-time dan menekan tombol konfirmasi barang telah sampai.", bullet=True, space_after=100)

    doc.add_heading2("5.5. Modul Pengaturan Admin & Laporan (Admin & Reporting)")
    doc.add_p("Manajemen Pengguna (/admin/users): Monitoring akun pelanggan dan admin, toggle status aktif/nonaktif, reset password instan, dan hapus pengguna (satuan / bulk delete).", bullet=True, space_after=40)
    doc.add_p("Dashboard Finansial (/admin/finance): Ringkasan metrik omzet kotor, pendapatan bersih, biaya ongkos kirim, dan distribusi metode pembayaran.", bullet=True, space_after=40)
    doc.add_p("Ekspor Rekapitulasi: Fasilitas unduh data rekap transaksi dan penjualan ke format .csv / .xlsx untuk keperluan pembukuan akuntansi.", bullet=True, space_after=140)

    doc.add_divider()

    # 6. Kriteria Keberhasilan (Success Metrics)
    doc.add_heading1("6. Kriteria Keberhasilan (Success Metrics)")
    doc.add_p("1. Efisiensi Transaksi: Waktu perakitan pesanan pelanggan hingga terhubung ke chat kurator berkurang 60% dibanding metode chat manual tanpa form.", space_after=40)
    doc.add_p("2. Pencegahan Overselling: 0% insiden penjualan kain yang sudah habis berkat filter stok otomatis pada homepage dan urutan katalog.", space_after=40)
    doc.add_p("3. Transparansi Logistik: 100% pesanan yang dikirim terdokumentasi dengan nomor resi dan kurir yang dapat dilacak langsung oleh pelanggan di halaman /pesanan.", space_after=40)
    doc.add_p("4. Performa & Aksesibilitas: Skor performa tampilan mobile di atas 90% dengan kelancaran browsing pada seluruh tipe resolusi layar.", space_after=140)

    doc.add_divider()

    # 7. Rencana Pengembangan & Milestones
    doc.add_heading1("7. Rencana Pengembangan & Milestones")
    doc.add_p("Fase 1 (Minggu 1-2): Finalisasi PRD, Desain UI/UX Klasik Modern, Setup Database Relasional (PostgreSQL), & Skema Migrasi 7 Tabel Inti.", bullet=True, space_after=40)
    doc.add_p("Fase 2 (Minggu 3-4): Pengembangan Fitur Autentikasi Pengguna, Halaman Beranda, Katalog Batik, Filter Dinamis, & Keranjang Belanja.", bullet=True, space_after=40)
    doc.add_p("Fase 3 (Minggu 5-6): Integrasi Template Pesanan WhatsApp API, Modul Manajemen Pesanan Kurator, Input Resi, & Halaman Riwayat Pesanan Member.", bullet=True, space_after=40)
    doc.add_p("Fase 4 (Minggu 7): Pembangunan Dashboard Analitik Keuangan, Fitur Ekspor CSV, Manajemen Pengguna, dan Audit Responsivitas Mobile.", bullet=True, space_after=40)
    doc.add_p("Fase 5 (Minggu 8): Pengujian Menyeluruh (UAT & View Cache), Deployment Server Lokal/Produksi, serta Pelatihan Operasional Kurator Juragan Batik.", bullet=True, space_after=120)

    output_path = "PRD_Juragan_Batik.docx"
    doc.save(output_path)
    print(f"Berhasil membuat dokumen PRD: {output_path}")

if __name__ == "__main__":
    generate_prd()
