<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Juragan Batik') — Adiluhung Surakarta</title>

    {{-- Material Symbols --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet"/>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "on-secondary-fixed": "#360f00",
                    "inverse-surface": "#31302d",
                    "primary": "#311908",
                    "on-primary": "#ffffff",
                    "surface-container": "#f1ede7",
                    "tertiary-fixed-dim": "#f0bd8b",
                    "on-primary-fixed-variant": "#5f402c",
                    "surface-container-lowest": "#ffffff",
                    "on-tertiary-fixed-variant": "#623f18",
                    "surface": "#fdf9f3",
                    "inverse-primary": "#eabda3",
                    "on-tertiary-fixed": "#2c1600",
                    "secondary-fixed-dim": "#ffb597",
                    "surface-variant": "#e6e2dc",
                    "on-error": "#ffffff",
                    "on-tertiary-container": "#c39465",
                    "secondary": "#904c2e",
                    "on-error-container": "#93000a",
                    "outline-variant": "#d4c3bb",
                    "surface-dim": "#dddad4",
                    "surface-container-highest": "#e6e2dc",
                    "background": "#fdf9f3",
                    "tertiary-fixed": "#ffdcbd",
                    "outline": "#82746d",
                    "surface-tint": "#795741",
                    "on-tertiary": "#ffffff",
                    "primary-container": "#4a2e1b",
                    "on-secondary-fixed-variant": "#723519",
                    "on-primary-container": "#bd957c",
                    "primary-fixed": "#ffdbc7",
                    "secondary-container": "#fea681",
                    "surface-bright": "#fdf9f3",
                    "on-secondary-container": "#78391c",
                    "primary-fixed-dim": "#eabda3",
                    "surface-container-low": "#f7f3ed",
                    "on-surface-variant": "#50443e",
                    "secondary-fixed": "#ffdbcd",
                    "error": "#ba1a1a",
                    "inverse-on-surface": "#f4f0ea",
                    "surface-container-high": "#ebe8e2",
                    "tertiary-container": "#4d2d07",
                    "tertiary": "#311900",
                    "on-surface": "#1c1c18",
                    "on-secondary": "#ffffff",
                    "error-container": "#ffdad6",
                    "on-primary-fixed": "#2d1605",
                    "on-background": "#1c1c18"
                },
                borderRadius: {
                    "DEFAULT": "0.125rem",
                    "lg": "0.25rem",
                    "xl": "0.5rem",
                    "full": "0.75rem"
                },
                spacing: {
                    "margin": "4rem",
                    "gutter-mobile": "1rem",
                    "space-xl": "2.5rem",
                    "space-sm": "0.5rem",
                    "gutter": "1.5rem",
                    "space-lg": "1.5rem",
                    "margin-mobile": "1.25rem",
                    "space-md": "1rem",
                    "space-xs": "0.25rem"
                },
                fontFamily: {
                    "body-lg": ["Plus Jakarta Sans"],
                    "headline-md": ["Playfair Display"],
                    "display-mobile": ["Playfair Display"],
                    "body-md": ["Plus Jakarta Sans"],
                    "label-sm": ["Plus Jakarta Sans"],
                    "title-lg": ["Playfair Display"],
                    "headline-sm": ["Playfair Display"],
                    "display": ["Playfair Display"],
                    "label-md": ["Plus Jakarta Sans"],
                    "label-lg": ["Plus Jakarta Sans"],
                    "headline-lg-mobile": ["Playfair Display"],
                    "headline-lg": ["Playfair Display"],
                    "body-sm": ["Plus Jakarta Sans"]
                },
                fontSize: {
                    "body-lg": ["18px", {"lineHeight": "28px", "letterSpacing": "0em", "fontWeight": "400"}],
                    "headline-md": ["28px", {"lineHeight": "36px", "letterSpacing": "0em", "fontWeight": "500"}],
                    "display-mobile": ["38px", {"lineHeight": "46px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                    "body-md": ["15px", {"lineHeight": "24px", "letterSpacing": "0.01em", "fontWeight": "400"}],
                    "label-sm": ["10px", {"lineHeight": "14px", "letterSpacing": "0.12em", "fontWeight": "700"}],
                    "title-lg": ["18px", {"lineHeight": "26px", "letterSpacing": "0.02em", "fontWeight": "600"}],
                    "headline-sm": ["22px", {"lineHeight": "30px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                    "display": ["56px", {"lineHeight": "64px", "letterSpacing": "-0.02em", "fontWeight": "600"}],
                    "label-md": ["12px", {"lineHeight": "16px", "letterSpacing": "0.1em", "fontWeight": "600"}],
                    "label-lg": ["14px", {"lineHeight": "20px", "letterSpacing": "0.08em", "fontWeight": "600"}],
                    "headline-lg-mobile": ["28px", {"lineHeight": "36px", "letterSpacing": "0em", "fontWeight": "500"}],
                    "headline-lg": ["40px", {"lineHeight": "48px", "letterSpacing": "-0.01em", "fontWeight": "500"}],
                    "body-sm": ["13px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "400"}]
                }
            }
        }
    };
    </script>

    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; }
            main > :first-child { margin-top: 0 !important; }
            main > :last-child  { margin-bottom: 0 !important; }
        }
        ::-webkit-scrollbar { display: none; }
    </style>

    @stack('styles')
</head>
<body class="bg-background font-body-md text-body-md text-on-surface">

{{-- ══════════════════ HEADER / NAVBAR ══════════════════ --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-surface/90 backdrop-blur-xl shadow-[0_1px_8px_rgba(74,46,27,0.06)]">
    <div class="h-20 max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex items-center justify-between gap-space-lg">

        {{-- Logo + Nav --}}
        <div class="flex items-center gap-space-xl">
            <a class="flex items-center gap-3" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Juragan Batik"
                     class="h-10 w-auto object-contain flex-shrink-0"
                     style="mix-blend-mode: multiply;"/>
                <div class="flex flex-col">
                    <span class="font-title-lg text-title-lg tracking-[0.25em] text-primary uppercase leading-tight">JURAGAN BATIK</span>
                    <span class="font-label-sm text-label-sm tracking-[0.3em] text-secondary uppercase">Surakarta • Est. 1928</span>
                </div>
            </a>
            <nav class="hidden lg:flex items-center gap-space-lg">
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'text-primary font-bold' : 'font-label-lg text-label-lg text-on-surface-variant hover:text-primary' }} transition-colors py-space-xs">
                   Beranda
                </a>
                <a href="{{ route('catalog') }}"
                   class="{{ request()->routeIs('catalog') ? 'text-primary font-bold' : 'font-label-lg text-label-lg text-on-surface-variant hover:text-primary' }} transition-colors py-space-xs">
                   Katalog Batik
                </a>
                <a href="{{ route('about') }}"
                   class="{{ request()->routeIs('about') ? 'text-primary font-bold' : 'font-label-lg text-label-lg text-on-surface-variant hover:text-primary' }} transition-colors py-space-xs">
                   Filosofi Motif
                </a>
            </nav>
        </div>

        {{-- Right Side --}}
        <div class="flex items-center gap-space-md">
            {{-- Search --}}
            <form action="{{ route('catalog') }}" method="GET"
                  class="hidden md:flex items-center bg-surface-container-low px-space-md py-space-xs rounded-xl shadow-[0_1px_4px_rgba(74,46,27,0.04)] w-64">
                <span class="material-symbols-outlined text-outline text-[20px] mr-space-xs">search</span>
                <input name="search" value="{{ request('search') }}"
                       class="bg-transparent text-on-surface text-body-sm font-body-sm placeholder:text-outline focus:outline-none w-full"
                       placeholder="Cari motif Parang, Kawung..."/>
            </form>

            <div class="flex items-center gap-space-sm">
                @auth
                {{-- Admin sudah login: tampilkan nama + dropdown --}}
                <div class="relative hidden sm:block" id="userMenuWrap">
                    <button id="userMenuBtn" type="button"
                            class="flex items-center gap-space-xs px-space-md py-space-xs bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md rounded-lg transition-colors">
                        <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-on-primary text-[14px]">person</span>
                        </div>
                        <span class="max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                        <span class="material-symbols-outlined text-[16px] text-outline">expand_more</span>
                    </button>
                    {{-- Dropdown --}}
                    <div id="userDropdown"
                         class="hidden absolute right-0 top-full mt-2 w-52 bg-surface-container-lowest rounded-xl shadow-xl border border-outline-variant overflow-hidden z-50">
                        <div class="px-space-md py-space-sm border-b border-outline-variant">
                            <p class="font-label-sm text-[10px] tracking-widest text-outline uppercase">Masuk sebagai</p>
                            <p class="font-label-lg text-label-lg text-primary font-bold truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-on-surface-variant truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.products.index') }}"
                           class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md">
                            <span class="material-symbols-outlined text-secondary text-[18px]">grid_view</span>
                            <span>Panel Produk</span>
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-space-sm px-space-md py-space-sm hover:bg-error-container transition-colors text-error font-label-md text-label-md">
                                <span class="material-symbols-outlined text-[18px]">logout</span>
                                <span>Keluar</span>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                {{-- Belum login: tombol Portal Kurator → ke halaman login --}}
                <a href="{{ route('login') }}"
                   class="hidden sm:flex items-center gap-space-xs px-space-md py-space-xs bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-[16px] text-secondary">shield_person</span>
                    <span>Portal Kurator</span>
                </a>
                @endauth

                {{-- Mobile menu button --}}
                <button id="mobileMenuBtn" class="lg:hidden p-space-xs text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[24px]">menu</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Nav --}}
    <div id="mobileNav" class="hidden lg:hidden bg-surface border-t border-surface-container px-margin-mobile py-space-md flex flex-col gap-space-sm">
        {{-- Logo mobile --}}
        <div class="flex items-center gap-2 pb-space-sm border-b border-surface-container mb-space-xs">
            <img src="{{ asset('images/logo.png') }}" alt="Juragan Batik" class="h-8 w-auto object-contain"/>
            <span class="font-label-lg text-label-lg text-primary font-bold tracking-widest uppercase">Juragan Batik</span>
        </div>
        <a href="{{ route('home') }}" class="font-label-lg text-label-lg {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-on-surface-variant' }} py-space-xs">Beranda</a>
        <a href="{{ route('catalog') }}" class="font-label-lg text-label-lg {{ request()->routeIs('catalog') ? 'text-primary font-bold' : 'text-on-surface-variant' }} py-space-xs">Katalog Batik</a>
        <a href="{{ route('about') }}" class="font-label-lg text-label-lg {{ request()->routeIs('about') ? 'text-primary font-bold' : 'text-on-surface-variant' }} py-space-xs">Filosofi Motif</a>
        <div class="border-t border-surface-container pt-space-sm mt-space-xs">
            @auth
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-secondary py-space-xs">
                    <span class="material-symbols-outlined text-[16px]">grid_view</span> Panel Produk
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-space-xs font-label-lg text-label-lg text-error py-space-xs w-full text-left">
                        <span class="material-symbols-outlined text-[16px]">logout</span> Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-secondary py-space-xs">
                    <span class="material-symbols-outlined text-[16px]">shield_person</span> Portal Kurator
                </a>
            @endauth
        </div>
    </div>
</header>

{{-- ══════════════════ MAIN CONTENT ══════════════════ --}}
<main class="w-full pt-20 bg-background">
    <div class="flex flex-col w-full">
        @yield('content')
    </div>
</main>

{{-- ══════════════════ FOOTER ══════════════════ --}}
<footer class="w-full bg-surface-container-low py-space-xl mt-space-xl shadow-[0_-1px_8px_rgba(74,46,27,0.03)]">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-space-xl mb-space-xl">
            <div class="flex flex-col gap-space-sm">
                <span class="font-title-lg text-title-lg tracking-[0.2em] text-primary uppercase">JURAGAN BATIK</span>
                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Preservasi mahakarya adiluhung batik tulis tangan keraton, diproduksi dengan malam lebah murni dan pewarna soga alami di Surakarta Hadiningrat.
                </p>
            </div>
            <div class="flex flex-col gap-space-sm">
                <span class="font-label-lg text-label-lg text-primary uppercase tracking-widest font-bold">Koleksi Adiluhung</span>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ route('catalog') }}">Batik Tulis Tembokan</a>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ route('catalog') }}">Sutera Baron Exclusive</a>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ route('catalog') }}">Kain Adat Pengantin</a>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ route('catalog') }}">Pusaka Sandang Terbatas</a>
            </div>
            <div class="flex flex-col gap-space-sm">
                <span class="font-label-lg text-label-lg text-primary uppercase tracking-widest font-bold">Atelier & Layanan</span>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Konsultasi Busana Bespoke</a>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Sertifikasi Keaslian Wastra</a>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Pemeliharaan Kain Antik</a>
                <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors" href="#">Kunjungan Galeri Privat</a>
            </div>
            <div class="flex flex-col gap-space-sm">
                <span class="font-label-lg text-label-lg text-primary uppercase tracking-widest font-bold">Sanctuary Resmi</span>
                <p class="font-body-sm text-body-sm text-on-surface-variant">Dalem Juragan Heritage, Jl. Slamet Riyadi No. 128, Surakarta, Jawa Tengah 57141</p>
                <span class="font-label-md text-label-md text-secondary">concierge@juraganbatik.id</span>
            </div>
        </div>
        <div class="flex flex-col md:flex-row items-center justify-between pt-space-lg bg-surface-container rounded-lg px-space-lg text-on-surface-variant gap-space-sm">
            <span class="font-label-sm text-label-sm tracking-wider">© {{ date('Y') }} JURAGAN BATIK SURAKARTA. Hak Cipta Dilindungi Wastra Adat.</span>
            <div class="flex gap-space-md">
                <span class="font-label-sm text-label-sm text-outline">Kurasi Warisan Budaya Takbenda Dunia</span>
            </div>
        </div>
    </div>
</footer>

<script>
    // Mobile menu toggle
    document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
        const nav = document.getElementById('mobileNav');
        nav.classList.toggle('hidden');
    });

    // User dropdown toggle
    const userMenuBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userDropdown');
    if (userMenuBtn && userDropdown) {
        userMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function() {
            userDropdown.classList.add('hidden');
        });
    }
</script>

@stack('scripts')
</body>
</html>
