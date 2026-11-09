<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'Juragan Batik') — Adiluhung Surakarta</title>

    {{-- Favicon Batik --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}"/>
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}"/>

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

        /* ── Penanda Garis Scroll (Elemen Garis-Garis di Pinggir Penanda Bisa di-Scroll) ── */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f7f3ed;
            border-left: 2px dashed #bfaaa0; /* Garis-garis vertikal di pinggir */
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #904c2e; /* Warna sekunder khas wastra */
            border-radius: 4px;
            border: 1px solid #fdf9f3;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #311908; /* Warna primer lebih gelap saat diarahkan */
        }

        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: #904c2e #f7f3ed;
        }
    </style>

    {{-- Toast Notification Box --}}
    <div id="appToastContainer" class="fixed top-24 right-5 z-[99999] flex flex-col gap-2 pointer-events-none max-w-sm w-full"></div>

    {{-- Global Confirmation Modal --}}
    <div id="globalConfirmModal" class="fixed inset-0 z-[99999] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity">
        <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 shadow-2xl border border-outline-variant/60 flex flex-col gap-4 transform transition-all scale-95 duration-200" id="globalConfirmBox">
            <div class="flex items-start gap-3.5">
                <div id="globalConfirmIconWrap" class="w-11 h-11 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                    <span id="globalConfirmIcon" class="material-symbols-outlined text-[24px]">delete</span>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 id="globalConfirmTitle" class="font-headline-sm text-base font-bold text-primary">Konfirmasi Hapus</h3>
                    <p id="globalConfirmMessage" class="text-xs text-on-surface-variant mt-1 leading-relaxed">Apakah Anda yakin ingin menghapus item ini?</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-outline-variant/40">
                <button type="button" id="globalConfirmBtnCancel" class="px-4 py-2 rounded-xl border border-outline-variant hover:bg-surface-container text-on-surface-variant font-bold text-xs uppercase tracking-wider transition">
                    Batal
                </button>
                <button type="button" id="globalConfirmBtnOk" class="px-4 py-2 rounded-xl bg-error hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">check</span>
                    <span id="globalConfirmOkText">Ya, Hapus</span>
                </button>
            </div>
        </div>
    </div>

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
        <div class="flex items-center gap-3 h-9">
            {{-- Search --}}
            <form action="{{ route('catalog') }}" method="GET"
                  class="m-0 hidden md:flex items-center bg-surface-container-low px-3 rounded-lg shadow-sm w-64 h-9 border border-outline-variant/40 focus-within:border-secondary transition-colors">
                <span class="material-symbols-outlined text-outline text-[18px] mr-1.5 flex-shrink-0 select-none">search</span>
                <input name="search" value="{{ request('search') }}"
                       class="bg-transparent text-on-surface text-sm placeholder:text-outline focus:outline-none w-full py-0 border-0 leading-normal"
                       placeholder="Cari motif Parang, Kawung..."/>
            </form>

            {{-- Login / User Menu --}}
            <div class="flex items-center gap-2 sm:gap-3 h-9">
                @auth
                {{-- User/Admin sudah login --}}
                <div class="relative flex items-center h-9" id="userMenuWrap">
                    <button id="userMenuBtn" type="button"
                            class="flex items-center gap-1.5 px-2 sm:px-3 bg-surface-container hover:bg-surface-container-high text-on-surface text-sm font-semibold rounded-lg transition-colors h-9 shadow-sm">
                        <div class="w-6 h-6 rounded-full bg-primary text-on-primary flex items-center justify-center flex-shrink-0 text-[10px] font-bold font-serif select-none">
                            {{ mb_substr(Auth::user()->initials, 0, 2) }}
                        </div>
                        <span class="hidden md:inline max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                        <span class="material-symbols-outlined text-[16px] text-outline">expand_more</span>
                    </button>
                    <div id="userDropdown"
                         class="hidden absolute right-0 top-full mt-2 w-64 bg-surface-container-lowest rounded-xl shadow-xl border border-outline-variant overflow-hidden z-50">
                        <div class="px-space-md py-space-sm border-b border-outline-variant flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-secondary text-on-primary flex items-center justify-center font-serif font-bold text-xs flex-shrink-0 select-none">
                                {{ Auth::user()->initials }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-label-sm text-[10px] tracking-widest text-outline uppercase">Masuk sebagai</p>
                                <p class="font-label-lg text-label-lg text-primary font-bold truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-on-surface-variant truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        @if(Auth::user()->isAdmin())
                            {{-- Khusus Menu Admin dengan Batas Garis --}}
                            <div class="px-space-md pt-2 pb-1 text-[10px] font-bold text-secondary uppercase tracking-widest flex items-center gap-1.5 bg-surface-container-low/60 border-b border-outline-variant/40">
                                <span class="material-symbols-outlined text-[13px]">admin_panel_settings</span>
                                <span>Menu Administrator</span>
                            </div>
                            <a href="{{ route('admin.products.index') }}"
                                class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md">
                                <span class="material-symbols-outlined text-secondary text-[18px]">inventory_2</span>
                                <span>Manajemen Inventaris Produk</span>
                            </a>
                            <a href="{{ route('admin.orders.index') }}"
                                class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md">
                                <span class="material-symbols-outlined text-secondary text-[18px]">receipt_long</span>
                                <span>Manajemen Pesanan</span>
                            </a>
                            <a href="{{ route('admin.finance.index') }}"
                                class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md">
                                <span class="material-symbols-outlined text-secondary text-[18px]">payments</span>
                                <span>Manajemen Keuangan</span>
                            </a>
                            <a href="{{ route('admin.users.index', ['role' => 'user']) }}"
                                class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md">
                                <span class="material-symbols-outlined text-secondary text-[18px]">group</span>
                                <span>Manajemen User</span>
                            </a>
                            <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
                                class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md border-b-2 border-outline-variant">
                                <span class="material-symbols-outlined text-secondary text-[18px]">manage_accounts</span>
                                <span>Manajemen Admin</span>
                            </a>
                        @endif

                        {{-- Menu untuk semua user (termasuk Admin) --}}
                        <div class="px-space-md pt-2 pb-1 text-[10px] font-bold text-outline uppercase tracking-widest">
                            <span>Akun Saya</span>
                        </div>
                        <a href="{{ route('profile.show') }}"
                           class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md">
                            <span class="material-symbols-outlined text-secondary text-[18px]">account_circle</span>
                            <span>Profil Saya</span>
                        </a>
                        <a href="{{ route('user.orders.index') }}"
                           class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md">
                            <span class="material-symbols-outlined text-secondary text-[18px]">receipt_long</span>
                            <span>Pesanan Saya</span>
                            @php $activeOrdersCount = \App\Models\Order::where('user_id', Auth::id())->whereIn('status', ['pending', 'shipped'])->count(); @endphp
                            @if($activeOrdersCount > 0)
                                <span class="ml-auto bg-amber-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $activeOrdersCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('cart.index') }}"
                           class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md">
                            <span class="material-symbols-outlined text-secondary text-[18px]">shopping_bag</span>
                            <span>Keranjang</span>
                            @php $cartCount = \App\Models\Cart::where('user_id', Auth::id())->count(); @endphp
                            @if($cartCount > 0)
                                <span class="ml-auto bg-secondary text-on-secondary text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('favorites.index') }}"
                           class="flex items-center gap-space-sm px-space-md py-space-sm hover:bg-surface-container transition-colors text-on-surface font-label-md text-label-md">
                            <span class="material-symbols-outlined text-secondary text-[18px]">favorite</span>
                            <span>Favorit</span>
                            @php $favCount = \App\Models\Favorite::where('user_id', Auth::id())->count(); @endphp
                            @if($favCount > 0)
                                <span class="ml-auto bg-secondary text-on-secondary text-[9px] font-bold px-1.5 py-0.5 rounded-full">{{ $favCount }}</span>
                            @endif
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
                {{-- Belum login: tombol Login + Daftar --}}
                <a href="{{ route('login') }}"
                   class="flex items-center gap-1 sm:gap-1.5 px-2.5 sm:px-3 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs sm:text-sm font-semibold rounded-lg transition-colors h-9 shadow-sm">
                    <span class="material-symbols-outlined text-[16px] text-secondary">login</span>
                    <span>Login</span>
                </a>
                <a href="{{ route('register') }}"
                   class="hidden sm:flex items-center gap-1.5 px-3 bg-primary hover:bg-secondary text-on-primary text-sm font-semibold rounded-lg transition-colors h-9 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">person_add</span>
                    <span>Daftar</span>
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
    <div id="mobileNav" class="hidden lg:hidden bg-surface border-t border-surface-container px-margin-mobile py-space-md flex flex-col gap-space-sm max-h-[calc(100vh-5rem)] overflow-y-auto">
        {{-- Search bar di mobile --}}
        <form action="{{ route('catalog') }}" method="GET"
              class="m-0 md:hidden flex items-center bg-surface-container-low px-3 rounded-lg shadow-sm w-full h-10 border border-outline-variant/40 focus-within:border-secondary transition-colors mb-2">
            <span class="material-symbols-outlined text-outline text-[18px] mr-2 flex-shrink-0 select-none">search</span>
            <input name="search" value="{{ request('search') }}"
                   class="bg-transparent text-on-surface text-sm placeholder:text-outline focus:outline-none w-full py-0 border-0 leading-normal"
                   placeholder="Cari motif Parang, Kawung..."/>
        </form>

        <a href="{{ route('home') }}" class="font-label-lg text-label-lg {{ request()->routeIs('home') ? 'text-primary font-bold' : 'text-on-surface-variant' }} py-space-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">home</span> Beranda
        </a>
        <a href="{{ route('catalog') }}" class="font-label-lg text-label-lg {{ request()->routeIs('catalog') ? 'text-primary font-bold' : 'text-on-surface-variant' }} py-space-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">storefront</span> Katalog Batik
        </a>
        <a href="{{ route('about') }}" class="font-label-lg text-label-lg {{ request()->routeIs('about') ? 'text-primary font-bold' : 'text-on-surface-variant' }} py-space-xs flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">auto_stories</span> Filosofi Motif
        </a>
        <div class="border-t border-surface-container pt-space-sm mt-space-xs">
            @auth
                @if(Auth::user()->isAdmin())
                    <div class="px-space-xs pt-1 pb-1 text-[10px] font-bold text-secondary uppercase tracking-widest flex items-center gap-1 border-b border-outline-variant/40">
                        <span class="material-symbols-outlined text-[14px]">admin_panel_settings</span>
                        <span>Menu Administrator</span>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-secondary py-space-xs">
                        <span class="material-symbols-outlined text-[16px]">inventory_2</span> Manajemen Inventaris Produk
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-secondary py-space-xs">
                        <span class="material-symbols-outlined text-[16px]">receipt_long</span> Manajemen Pesanan
                    </a>
                    <a href="{{ route('admin.finance.index') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-secondary py-space-xs">
                        <span class="material-symbols-outlined text-[16px]">payments</span> Manajemen Keuangan
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'user']) }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-secondary py-space-xs">
                        <span class="material-symbols-outlined text-[16px]">group</span> Manajemen User
                    </a>
                    <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-secondary py-space-xs pb-2 border-b-2 border-outline-variant">
                        <span class="material-symbols-outlined text-[16px]">manage_accounts</span> Manajemen Admin
                    </a>
                @endif
                <div class="px-space-xs pt-2 text-[10px] font-bold text-outline uppercase tracking-widest">
                    <span>Akun Saya</span>
                </div>
                <a href="{{ route('profile.show') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-on-surface py-space-xs">
                    <span class="material-symbols-outlined text-[16px]">account_circle</span> Profil Saya
                </a>
                <a href="{{ route('user.orders.index') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-on-surface py-space-xs">
                    <span class="material-symbols-outlined text-[16px]">receipt_long</span> Pesanan Saya
                </a>
                <a href="{{ route('cart.index') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-on-surface py-space-xs">
                    <span class="material-symbols-outlined text-[16px]">shopping_bag</span> Keranjang
                </a>
                <a href="{{ route('favorites.index') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-on-surface py-space-xs">
                    <span class="material-symbols-outlined text-[16px]">favorite</span> Favorit
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-space-xs font-label-lg text-label-lg text-error py-space-xs w-full text-left">
                        <span class="material-symbols-outlined text-[16px]">logout</span> Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-secondary py-space-xs">
                    <span class="material-symbols-outlined text-[16px]">login</span> Login
                </a>
                <a href="{{ route('register') }}" class="flex items-center gap-space-xs font-label-lg text-label-lg text-on-surface-variant py-space-xs">
                    <span class="material-symbols-outlined text-[16px]">person_add</span> Daftar
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-xl mb-space-xl">
            <div class="flex flex-col gap-space-sm">
                <span class="font-title-lg text-title-lg tracking-[0.2em] text-primary uppercase">JURAGAN BATIK</span>
                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Preservasi mahakarya adiluhung batik tulis tangan keraton, diproduksi dengan malam lebah murni dan pewarna soga alami di Surakarta Hadiningrat.
                </p>
            </div>
            <div class="flex flex-col gap-space-sm md:items-end md:text-right">
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

    // ── Global Toast Notification ──
    window.showToast = function (message, type = 'success') {
        const container = document.getElementById('appToastContainer');
        if (!container) return;

        const isSuccess = type === 'success';
        const isError = type === 'error';

        const toast = document.createElement('div');
        toast.className = `pointer-events-auto flex items-start gap-3 p-3.5 rounded-xl shadow-lg border transition-all duration-300 transform translate-y-2 opacity-0 ${
            isSuccess ? 'bg-white border-green-200 text-green-900 shadow-green-900/10' :
            isError ? 'bg-white border-red-200 text-red-900 shadow-red-900/10' :
            'bg-white border-amber-200 text-amber-900 shadow-amber-900/10'
        }`;

        const iconName = isSuccess ? 'check_circle' : (isError ? 'error' : 'info');
        const iconColor = isSuccess ? 'text-green-600' : (isError ? 'text-red-600' : 'text-amber-600');

        toast.innerHTML = `
            <span class="material-symbols-outlined text-[20px] ${iconColor} flex-shrink-0 mt-0.5">${iconName}</span>
            <div class="flex-1 text-xs font-semibold leading-relaxed">${message}</div>
            <button type="button" class="text-outline hover:text-primary transition flex-shrink-0">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
        `;

        toast.querySelector('button').onclick = () => {
            toast.classList.add('opacity-0', '-translate-y-2');
            setTimeout(() => toast.remove(), 200);
        };

        container.appendChild(toast);
        requestAnimationFrame(() => {
            toast.classList.remove('opacity-0', 'translate-y-2');
        });

        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.add('opacity-0', '-translate-y-2');
                setTimeout(() => toast.remove(), 250);
            }
        }, 4000);
    };

    // Replace native window.alert with custom toast
    window.alert = function (message) {
        window.showToast(message, 'info');
    };

    // ── Global Custom Confirm Dialog ──
    window.showConfirmDialog = function ({
        title = 'Konfirmasi Hapus',
        message = 'Apakah Anda yakin ingin menghapus item ini?',
        okText = 'Ya, Hapus',
        cancelText = 'Batal',
        icon = 'delete',
        type = 'danger'
    } = {}) {
        return new Promise((resolve) => {
            const modal = document.getElementById('globalConfirmModal');
            const titleEl = document.getElementById('globalConfirmTitle');
            const msgEl = document.getElementById('globalConfirmMessage');
            const okBtn = document.getElementById('globalConfirmBtnOk');
            const cancelBtn = document.getElementById('globalConfirmBtnCancel');
            const okTextEl = document.getElementById('globalConfirmOkText');
            const iconEl = document.getElementById('globalConfirmIcon');
            const iconWrap = document.getElementById('globalConfirmIconWrap');

            if (!modal) {
                resolve(true);
                return;
            }

            titleEl.textContent = title;
            msgEl.textContent = message;
            okTextEl.textContent = okText;
            cancelBtn.textContent = cancelText;
            iconEl.textContent = icon;

            if (type === 'danger') {
                iconWrap.className = 'w-11 h-11 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0';
                okBtn.className = 'px-4 py-2 rounded-xl bg-error hover:bg-red-700 text-white font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center gap-1.5 cursor-pointer';
            } else {
                iconWrap.className = 'w-11 h-11 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0';
                okBtn.className = 'px-4 py-2 rounded-xl bg-primary hover:bg-secondary text-white font-bold text-xs uppercase tracking-wider transition shadow-sm flex items-center gap-1.5 cursor-pointer';
            }

            const close = (result) => {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
                okBtn.onclick = null;
                cancelBtn.onclick = null;
                resolve(result);
            };

            okBtn.onclick = () => close(true);
            cancelBtn.onclick = () => close(false);

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    };
</script>

@stack('scripts')
</body>
</html>
