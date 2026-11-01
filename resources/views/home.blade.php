@extends('layouts.app')
@section('title', 'Beranda')

@section('content')

{{-- ══════════════ HERO SECTION ══════════════ --}}
<section class="relative w-full overflow-hidden bg-gradient-to-b from-surface-container-low via-surface to-background pt-space-xl pb-24">
    {{-- Ambient blobs --}}
    <div class="absolute -top-32 right-10 w-[520px] h-[520px] bg-secondary-container/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 -left-20 w-[420px] h-[420px] bg-tertiary-fixed/30 rounded-full blur-2xl pointer-events-none"></div>

    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-center">

            {{-- Hero Text --}}
            <div class="lg:col-span-7 flex flex-col gap-space-md">
                <div class="inline-flex items-center gap-space-xs bg-surface-container-high px-space-md py-space-xs rounded-full shadow-sm w-fit">
                    <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                    <span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary">Adiluhung Surakarta Hadiningrat</span>
                </div>
                <h1 class="font-display text-display lg:text-[68px] lg:leading-[74px] text-primary tracking-tight">
                    Keanggunan Warisan <span class="italic font-display text-secondary">Nusantara</span>
                </h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl leading-relaxed">
                    Menghidupkan pusaka luhur lewat goresan canting tembaga dan malam lebah murni. Busana haute-couture batik tulis &amp; cap autentik Solo dan Yogyakarta, diselaraskan dengan keanggunan modern bagi para penikmat wastra sejati.
                </p>
                <div class="flex flex-wrap items-center gap-space-md pt-space-sm">
                    <a href="{{ route('catalog') }}"
                       class="inline-flex items-center justify-center gap-space-xs px-space-xl py-space-md bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded shadow-md transition-all transform hover:-translate-y-0.5">
                        <span>Jelajahi Katalog</span>
                        <span class="material-symbols-outlined text-[18px]">arrow_right_alt</span>
                    </a>
                    <a href="{{ route('about') }}"
                       class="inline-flex items-center justify-center gap-space-xs px-space-xl py-space-md bg-surface-container-lowest hover:bg-surface-container text-primary font-label-lg text-label-lg rounded shadow-sm transition-colors">
                        <span class="material-symbols-outlined text-[18px] text-secondary">auto_stories</span>
                        <span>Pelajari Filosofi</span>
                    </a>
                </div>

                {{-- Authenticity Seals --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-space-sm pt-space-lg">
                    @foreach ([
                        ['verified',           '100% SUTRA & MORI', 'Serat Halus Primissima'],
                        ['workspace_premium',  'BATIK MARK',        'Sertifikasi Resmi RI'],
                        ['eco',                'SOGA ALAMI',        'Ekstrak Kulit Kayu'],
                        ['fingerprint',        'SERI CANTING',      'Kurasi Terbatas Unik'],
                    ] as [$icon, $title, $sub])
                    <div class="bg-surface-container-lowest p-space-sm rounded shadow-sm flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-secondary text-[22px]">{{ $icon }}</span>
                        <div class="flex flex-col">
                            <span class="font-label-sm text-label-sm text-primary font-bold">{{ $title }}</span>
                            <span class="font-body-sm text-body-sm text-on-surface-variant text-[11px] leading-tight">{{ $sub }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Hero Visual --}}
            <div class="lg:col-span-5 relative">
                <div class="relative bg-surface-container-lowest p-space-sm rounded-xl shadow-xl">
                    <div class="relative w-full aspect-[4/5] rounded overflow-hidden">
                        <img alt="Kemeja Sutra Parang Rusak Slimfit Eksklusif"
                             class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-700"
                             src="https://lh3.googleusercontent.com/aida/AEtjO1WPwAu_Hm1Zf7gFa0A6MOxZXP72k_NVYhedaUhvJDeHUMDO9iQz7WGyVu3zhbE-57YYHBSy9jcFnURhOboiurcqDQePTOQXEGPNcCOvi43B6EZO0UxAl3bECWUxY2OHXZDBHf5G2znPiVdcxMWpRo1J5fXnMV0a8NS2BgOLAuiSoVJ2NsBBx_AfzqI2zk-SY_PUXrauRJviOI5Xd9S30xi25YuvzTZhAlwQszlZpiU"/>
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-6 left-6 right-6 text-on-primary">
                            <span class="font-label-sm text-label-sm uppercase tracking-[0.2em] bg-secondary/80 backdrop-blur-sm px-space-sm py-0.5 rounded text-on-secondary">Koleksi Kasunanan</span>
                            <p class="font-title-lg text-title-lg font-bold mt-1 text-on-primary">Mahakarya Parang Rusak Barong</p>
                            <p class="font-body-sm text-body-sm text-surface-container-highest">Motif agung kewibawaan dan keteguhan jiwa para ksatria</p>
                        </div>
                    </div>
                </div>
                {{-- Provenance Card --}}
                <div class="hidden sm:flex absolute -bottom-8 -left-10 bg-surface-container-lowest/95 backdrop-blur-md p-space-md rounded-lg shadow-lg flex-col gap-1 max-w-[240px]">
                    <div class="flex items-center justify-between">
                        <span class="font-label-sm text-label-sm text-secondary font-bold uppercase tracking-wider">Provenance</span>
                        <span class="material-symbols-outlined text-[16px] text-secondary">history_edu</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface font-semibold">Canting Solo No. 042</p>
                    <p class="font-body-sm text-body-sm text-on-surface-variant text-[12px] leading-tight">Ditenun &amp; dicanting tangan selama 48 hari oleh maestro generasi ke-3.</p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ══════════════ PRODUK SLIDESHOW ══════════════ --}}
<section class="w-full py-space-xl bg-surface">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">

        <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-md mb-space-xl">
            <div class="flex flex-col gap-space-xs">
                <span class="font-label-md text-label-md text-secondary tracking-[0.2em] uppercase font-bold">Kurasi Unggulan Musim Ini</span>
                <h2 class="font-headline-lg text-headline-lg text-primary">Tiga Mahakarya Wastra Pilihan</h2>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-xl">
                    Setiap helai kain membawa filosofi sakral yang diwariskan dari bilik-bilik keraton.
                </p>
            </div>
            <a href="{{ route('catalog') }}"
               class="inline-flex items-center gap-space-xs text-secondary hover:text-primary font-label-lg text-label-lg transition-colors">
                <span>Lihat Seluruh Katalog</span>
                <span class="material-symbols-outlined text-[20px]">east</span>
            </a>
        </div>

        @if($allProducts->isEmpty())
        <div class="col-span-3 text-center py-16 text-on-surface-variant">
            <span class="material-symbols-outlined text-[64px] block mb-4">inventory_2</span>
            <p class="font-body-md text-body-md">Belum ada produk tersedia.</p>
        </div>
        @else

        {{-- Grid 3 kartu --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-space-lg" id="productSlider">
            {{-- 3 slot kartu -- diisi JS --}}
            @for($i = 0; $i < 3; $i++)
            <div class="product-card bg-surface-container-lowest rounded-lg shadow-sm hover:shadow-xl transition-all duration-500 flex flex-col group overflow-hidden opacity-0"
                 style="transition: opacity 0.4s ease, transform 0.4s ease;">
            </div>
            @endfor
        </div>

        {{-- Dot indicators --}}
        <div class="flex justify-center gap-2 mt-space-lg" id="dotIndicators"></div>

        {{-- Auto-play progress bar --}}
        <div class="mt-space-sm max-w-xs mx-auto">
            <div class="w-full h-0.5 bg-surface-container-high rounded-full overflow-hidden">
                <div id="slideProgress"
                     class="h-full rounded-full"
                     style="background:#904c2e; width:0%; transition:width linear;"></div>
            </div>
        </div>

        @endif
    </div>
</section>

{{-- ══════════════ KEUNGGULAN ══════════════ --}}
<section class="w-full py-24 bg-surface-container-low relative overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
        <div class="text-center max-w-3xl mx-auto mb-space-xl flex flex-col items-center gap-space-xs">
            <span class="font-label-md text-label-md text-secondary tracking-[0.25em] uppercase font-bold">Dibalik Sentuhan Canting</span>
            <h2 class="font-headline-lg text-headline-lg text-primary">Kearifan Leluhur yang Tetap Bernapas</h2>
            <p class="font-body-md text-body-md text-on-surface-variant">
                Batik bukan sekadar ornamen kain; ia adalah meditasi kesabaran, doa yang diguratkan melalui cairan malam panas.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-space-lg mb-space-xl">
            @foreach ([
                ['draw',        'Tahap I • Nglowongi',    'Goresan Malam Lebah Alami',       'Tetes demi tetes lilin malam panas mengalir dari cucuk canting tembaga. Setiap garis menahan warna dengan ketelitian milimeter yang hanya dikuasai oleh tangan berpengalaman puluhan tahun.',           'Waktu Pengerjaan',      '30 – 60 Hari / Kain'],
                ['nest_eco_leaf','Tahap II • Medel & Nyoga','Pewarna Hayati Ramah Bumi',      'Kami menolak pewarna kimia sintetis agresif. Warna cokelat khas soga dihasilkan dari rebusan kulit kayu tingi dan jambal, dipadukan fermentasi daun Indigofera untuk biru yang abadi.', 'Proses Pencelupan',     'Hingga 18 Kali Celup'],
                ['diversity_3', 'Tahap III • Preservasi',  'Dedikasi Ibu Pembatik Laweyan',   'Setiap helai kain adalah wujud pemberdayaan nyata bagi 45+ maestro pembatik senior di sentra Laweyan & Kauman Surakarta.',                                                              'Sertifikat Otentikasi', 'Tanda Tangan Pengrajin'],
            ] as [$icon, $label, $title, $desc, $statLabel, $statVal])
            <div class="bg-surface-container-lowest p-space-xl rounded-xl shadow-sm flex flex-col justify-between gap-space-lg group hover:shadow-md transition-shadow">
                <div class="flex flex-col gap-space-md">
                    <div class="w-14 h-14 rounded-lg bg-surface-container flex items-center justify-center text-secondary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                        <span class="material-symbols-outlined text-[32px]">{{ $icon }}</span>
                    </div>
                    <div class="flex flex-col gap-space-xs">
                        <span class="font-label-sm text-label-sm text-secondary uppercase font-bold tracking-widest">{{ $label }}</span>
                        <h3 class="font-headline-sm text-headline-sm text-primary">{{ $title }}</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ $desc }}</p>
                    </div>
                </div>
                <div class="pt-space-md bg-surface-container-low px-space-md pb-space-sm rounded">
                    <div class="flex justify-between items-center text-on-surface">
                        <span class="font-label-sm text-label-sm font-bold uppercase">{{ $statLabel }}</span>
                        <span class="font-label-sm text-label-sm text-secondary font-bold">{{ $statVal }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- CTA Banner --}}
        <div class="bg-gradient-to-r from-primary via-primary-container to-secondary rounded-xl p-space-xl text-on-primary shadow-xl flex flex-col lg:flex-row items-center justify-between gap-space-lg">
            <div class="flex flex-col gap-space-xs max-w-2xl text-center lg:text-left">
                <span class="font-label-sm text-label-sm text-secondary-fixed uppercase tracking-[0.2em]">Konsultasi Privat Wastra</span>
                <h3 class="font-headline-md text-headline-md text-on-primary font-bold">Mencari Motif Tertentu untuk Upacara atau Koleksi?</h3>
                <p class="font-body-md text-body-md text-surface-container-highest">Kurator kami siap membantu mencocokkan filosofi motif yang tepat sesuai selera busana bespoke Anda.</p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-space-md">
                <a href="{{ route('catalog') }}"
                   class="px-space-lg py-space-md bg-surface-container-lowest hover:bg-surface text-primary font-label-lg text-label-lg rounded shadow transition-colors">
                    Buka Katalog Lengkap
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex items-center gap-space-xs px-space-lg py-space-md bg-primary/40 hover:bg-primary/70 text-on-primary font-label-lg text-label-lg rounded transition-colors">
                    <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
                    <span>Akses Portal Kurator</span>
                </a>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    // ── Data produk dari Laravel ──────────────────────────────────
    const products = @json($allProducts);

    if (!products.length) return;

    const SLOTS      = 3;          // jumlah kartu tampil
    const INTERVAL   = 4000;       // ms antar rotate
    const ANIM       = 400;        // ms durasi fade

    let currentStart = 0;          // index produk di kartu pertama
    let timer        = null;
    let progressTimer= null;

    const slider     = document.getElementById('productSlider');
    const dots       = document.getElementById('dotIndicators');
    const progressEl = document.getElementById('slideProgress');
    const cards      = slider ? [...slider.querySelectorAll('.product-card')] : [];

    if (!slider || !cards.length) return;

    // ── Buat dots ─────────────────────────────────────────────────
    function buildDots() {
        if (!dots) return;
        dots.innerHTML = '';
        const total = Math.ceil(products.length / SLOTS);
        for (let i = 0; i < total; i++) {
            const d = document.createElement('button');
            d.className = 'w-2 h-2 rounded-full transition-all duration-300';
            d.style.background = '#d4c3bb';
            d.addEventListener('click', () => goTo(i * SLOTS));
            dots.appendChild(d);
        }
    }

    function updateDots() {
        if (!dots) return;
        const page  = Math.floor(currentStart / SLOTS);
        [...dots.children].forEach((d, i) => {
            d.style.background = i === page ? '#904c2e' : '#d4c3bb';
            d.style.width      = i === page ? '1.5rem' : '0.5rem';
        });
    }

    // ── Render kartu ─────────────────────────────────────────────
    function renderCard(card, product) {
        const imgHtml = product.image
            ? `<img src="${product.image}" alt="${product.name}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>`
            : `<div class="w-full h-full flex flex-col items-center justify-center bg-surface-container gap-2">
                   <span class="material-symbols-outlined text-[64px] text-outline">draw</span>
                   <span class="text-xs text-outline font-label-sm">Tidak Ada Gambar</span>
               </div>`;

        card.innerHTML = `
            <div class="relative aspect-[4/5] overflow-hidden bg-surface-container">
                ${imgHtml}
                <div class="absolute top-4 left-4">
                    <span class="bg-primary text-on-primary font-label-sm text-[10px] px-2 py-0.5 rounded uppercase tracking-wider shadow">
                        ${product.category}
                    </span>
                </div>
            </div>
            <div class="p-space-lg flex flex-col flex-1 justify-between gap-space-md">
                <div class="flex flex-col gap-space-xs">
                    <div class="flex items-center justify-between">
                        <span class="font-label-sm text-[10px] text-secondary tracking-widest uppercase">${product.category}</span>
                        <div class="flex items-center gap-1 text-secondary">
                            <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1;">star</span>
                            <span class="font-label-sm text-[10px] font-bold text-on-surface">5.0</span>
                        </div>
                    </div>
                    <h3 class="font-title-lg text-title-lg text-primary font-bold line-clamp-1">${product.name}</h3>
                    <p class="text-xs text-on-surface-variant line-clamp-2 leading-relaxed">${product.desc}</p>
                </div>
                <div class="flex flex-col gap-space-sm pt-space-xs">
                    <div class="flex items-baseline justify-between">
                        <span class="text-[10px] text-outline uppercase tracking-wider">Investasi Mahakarya</span>
                        <span class="font-headline-sm text-headline-sm text-primary font-bold">Rp ${product.price}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-space-xs">
                        <a href="${product.url}"
                           class="inline-flex items-center justify-center py-space-sm px-space-sm bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md rounded transition-colors text-center text-xs">
                            Detail Produk
                        </a>
                        <a href="${product.wa}" target="_blank" rel="noopener"
                           class="inline-flex items-center justify-center gap-1 py-space-sm px-space-sm bg-primary hover:bg-secondary text-on-primary font-label-md text-label-md rounded transition-colors shadow text-xs">
                            <span class="material-symbols-outlined text-[14px]">shopping_bag</span>
                            <span>Beli</span>
                        </a>
                    </div>
                </div>
            </div>`;
    }

    // ── Tampilkan set produk mulai index tertentu (dengan fade) ───
    function showSlide(startIndex, direction = 1) {
        // fade out
        cards.forEach(c => {
            c.style.opacity    = '0';
            c.style.transform  = `translateX(${direction * 20}px)`;
        });

        setTimeout(() => {
            for (let i = 0; i < SLOTS; i++) {
                const idx  = (startIndex + i) % products.length;
                renderCard(cards[i], products[idx]);
                cards[i].style.transform = `translateX(${-direction * 20}px)`;
            }
            // trigger reflow lalu fade in
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    cards.forEach((c, i) => {
                        setTimeout(() => {
                            c.style.opacity   = '1';
                            c.style.transform = 'translateX(0)';
                        }, i * 60);
                    });
                });
            });
        }, ANIM);

        currentStart = startIndex;
        updateDots();
        startProgress();
    }

    function goTo(index) {
        const dir = index > currentStart ? 1 : -1;
        resetTimer();
        showSlide(index, dir);
    }

    // ── Progress bar ─────────────────────────────────────────────
    function startProgress() {
        if (!progressEl) return;
        clearInterval(progressTimer);
        progressEl.style.transition = 'none';
        progressEl.style.width      = '0%';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                progressEl.style.transition = `width ${INTERVAL}ms linear`;
                progressEl.style.width      = '100%';
            });
        });
    }

    // ── Auto-play ────────────────────────────────────────────────
    function startTimer() {
        timer = setInterval(() => {
            const next = (currentStart + SLOTS) % products.length;
            showSlide(next, 1);
        }, INTERVAL);
    }

    function resetTimer() {
        clearInterval(timer);
        startTimer();
    }

    // ── Kontrol tombol prev/next ──────────────────────────────────
    // (tombol dihapus — slideshow berjalan otomatis)

    // ── Pause saat hover ─────────────────────────────────────────
    slider.addEventListener('mouseenter', () => clearInterval(timer));
    slider.addEventListener('mouseleave', () => startTimer());

    // ── Init ─────────────────────────────────────────────────────
    buildDots();
    showSlide(0, 1);
    startTimer();
})();
</script>
@endpush
