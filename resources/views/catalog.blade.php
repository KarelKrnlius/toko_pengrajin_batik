@extends('layouts.app')
@section('title', 'Katalog Batik Nusantara')

@push('styles')
<style>
    /* Range slider */
    input[type=range] {
        -webkit-appearance: none; appearance: none;
        width: 100%; height: 4px; border-radius: 2px;
        background: transparent; outline: none; pointer-events: none;
    }
    .range-slider {
        position: absolute; pointer-events: none;
        height: 4px; background: transparent; top: -5px;
    }
    .range-slider::-webkit-slider-thumb {
        -webkit-appearance: none; appearance: none;
        pointer-events: all; width: 13px; height: 13px;
        border-radius: 50%; background: #904c2e; cursor: pointer;
        border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    .range-slider::-moz-range-thumb {
        pointer-events: all; width: 13px; height: 13px;
        border-radius: 50%; background: #904c2e; cursor: pointer;
        border: 2px solid #fff; box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }

    /* Gambar kartu — rasio 3:4 terjaga, tidak dipotong */
    .card-img-wrap {
        position: relative;
        width: 100%;
        aspect-ratio: 3 / 4;    /* tinggi mengikuti lebar secara otomatis */
        overflow: hidden;
        background: #f1ede7;
        flex-shrink: 0;
    }
    .card-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;       /* cover penuh — karena gambar sudah 4:5 dari cropper */
        object-position: center;
        display: block;
        transition: transform 0.5s ease;
    }
    .group:hover .card-img-wrap img {
        transform: scale(1.03);
    }
    .card-img-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #f1ede7;
    }
</style>
@endpush

@section('content')

<div class="relative w-full overflow-hidden">
<div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin pt-space-lg pb-space-xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center justify-between gap-space-sm mb-space-md">
        <nav class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
            <span class="text-primary font-semibold">Katalog Wastra Nusantara</span>
        </nav>
    </div>

    {{-- Page Header --}}
    <div class="relative mb-space-xl">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-lg">
            <div class="max-w-3xl">
                <span class="font-label-md text-label-md text-secondary uppercase tracking-[0.25em] font-bold block mb-space-xs">Adiluhung • Warisan Budaya Takbenda</span>
                <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight mb-space-sm">Katalog Produk Batik Nusantara</h1>
                <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                    Eksplorasi ragam wastra pusaka, mahakarya canting tulis tangan murni malam lebah serta ragam cap tembaga adibusana.
                </p>
            </div>
            <div class="flex items-center gap-space-md p-space-md bg-surface-container-low rounded-xl shadow-sm self-start lg:self-auto">
                <div class="w-12 h-12 rounded-lg bg-primary flex items-center justify-center text-on-primary flex-shrink-0">
                    <span class="material-symbols-outlined text-[26px]">inventory_2</span>
                </div>
                <div>
                    <div class="flex items-baseline gap-space-xs">
                        <span class="font-headline-sm text-headline-sm text-primary font-bold">{{ $products->total() }}</span>
                        <span class="font-label-sm text-label-sm text-on-surface-variant uppercase">Koleksi Terkurasi</span>
                    </div>
                    <span class="font-body-sm text-body-sm text-outline">Siap Kirim &amp; Arsip Bespoke</span>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="relative flex items-center justify-center my-space-lg">
            <div class="w-full h-px bg-surface-container-highest"></div>
            <div class="absolute px-space-md bg-background flex items-center gap-space-xs">
                <span class="text-[10px] tracking-widest text-outline uppercase font-label-sm">SANCTUARY WASTRA</span>
                <span class="material-symbols-outlined text-[16px] text-secondary">diamond</span>
                <span class="text-[10px] tracking-widest text-outline uppercase font-label-sm">EST. 1928</span>
            </div>
        </div>

        {{-- Search Toolbar --}}
        <form method="GET" action="{{ route('catalog') }}" id="searchForm"
              class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-space-md bg-surface-container-low p-space-md rounded-xl shadow-sm">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-space-md top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                <input name="search" value="{{ request('search') }}"
                       class="w-full pl-11 pr-space-md py-2.5 bg-surface-container-lowest text-on-surface font-body-sm text-body-sm rounded-lg focus:outline-none focus:ring-1 focus:ring-secondary text-primary placeholder:text-outline shadow-sm"
                       placeholder="Cari motif (Parang, Kawung, Sekar Jagad, Truntum, Megamendung)..."/>
                @if(request('category'))  <input type="hidden" name="category"  value="{{ request('category') }}">  @endif
                @if(request('sort'))      <input type="hidden" name="sort"      value="{{ request('sort') }}">      @endif
                @if(request('price_min'))<input type="hidden" name="price_min" value="{{ request('price_min') }}"> @endif
                @if(request('price_max'))<input type="hidden" name="price_max" value="{{ request('price_max') }}"> @endif
            </div>
            <div class="flex items-center gap-space-md flex-shrink-0">
                <div class="relative hidden md:block">
                    <select name="sort" onchange="this.form.submit()"
                            class="appearance-none bg-surface-container-lowest text-primary font-label-md text-label-md pl-space-md pr-8 py-2.5 rounded-lg shadow-sm focus:outline-none focus:ring-1 focus:ring-secondary cursor-pointer text-sm">
                        <option value="terbaru"     {{ request('sort','terbaru') === 'terbaru'     ? 'selected' : '' }}>Rilis Terbaru</option>
                        <option value="harga_naik"  {{ request('sort') === 'harga_naik'  ? 'selected' : '' }}>Harga: Terendah</option>
                        <option value="harga_turun" {{ request('sort') === 'harga_turun' ? 'selected' : '' }}>Harga: Tertinggi</option>
                        <option value="nama_az"     {{ request('sort') === 'nama_az'     ? 'selected' : '' }}>Nama A–Z</option>
                        <option value="nama_za"     {{ request('sort') === 'nama_za'     ? 'selected' : '' }}>Nama Z–A</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-space-xs top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[18px]">expand_more</span>
                </div>
                <button type="submit"
                        class="px-space-lg py-2.5 bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded-lg transition-colors shadow-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    <span>Cari</span>
                </button>
                @if(request()->hasAny(['search','category','sort','price_min','price_max']))
                <a href="{{ route('catalog') }}"
                   class="px-space-md py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-label-md text-label-md rounded-lg transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    <span class="hidden sm:inline">Reset</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Main 2-Column --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-start">

        @php
            $activeFilterCount = (request('category') ? 1 : 0) + (request('price_min') || (request('price_max') && request('price_max') != 10000000) ? 1 : 0);
            $hasActiveFilters = $activeFilterCount > 0;
        @endphp

        {{-- ══ SIDEBAR FILTER (Responsive Dropdown) ══ --}}
        <aside class="lg:col-span-3 flex flex-col bg-surface-container-low rounded-xl shadow-sm lg:sticky lg:top-24 overflow-hidden border border-outline-variant/30">

            {{-- Filter Header (Bisa diklik buka/tutup pada tampilan mobile/responsive) --}}
            <div class="flex items-center justify-between p-space-md lg:p-space-lg pb-space-sm lg:pb-space-sm border-b border-surface-container-highest cursor-pointer lg:cursor-default select-none transition-colors hover:bg-surface-container/50 lg:hover:bg-transparent"
                 onclick="toggleEntireFilter()">
                <div class="flex items-center gap-space-xs text-primary font-title-lg text-title-lg">
                    <span class="material-symbols-outlined text-[20px] text-secondary">tune</span>
                    <span>Filter Kurasi</span>
                    @if($activeFilterCount > 0)
                        <span class="ml-1 bg-secondary text-on-secondary text-[10px] font-bold px-2 py-0.5 rounded-full lg:hidden">
                            {{ $activeFilterCount }}
                        </span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    @if(request()->hasAny(['category','price_min','price_max']))
                        <a href="{{ route('catalog') }}" onclick="event.stopPropagation()"
                           class="text-secondary font-label-sm text-label-sm hover:underline tracking-wider uppercase font-bold">Reset</a>
                    @endif
                    {{-- Toggle arrow untuk responsive / mobile --}}
                    <div class="lg:hidden w-8 h-8 rounded-lg bg-surface-container flex items-center justify-center text-primary transition-transform duration-200 {{ $hasActiveFilters ? 'rotate-180' : '' }}" id="mobileFilterArrow">
                        <span class="material-symbols-outlined text-[20px]">expand_more</span>
                    </div>
                </div>
            </div>

            {{-- Filter Content (Tutup/Buka di mobile, selalu tampil di desktop) --}}
            <div id="filterContent" class="{{ $hasActiveFilters ? 'flex' : 'hidden' }} lg:flex flex-col gap-space-lg p-space-md lg:p-space-lg pt-space-md lg:pt-0">

                <form method="GET" action="{{ route('catalog') }}" id="filterForm">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('sort'))   <input type="hidden" name="sort"   value="{{ request('sort') }}"> @endif

                    {{-- 1. Kategori Wastra (Dropdown Buka/Tutup) --}}
                    <div class="flex flex-col mb-space-md bg-surface-container-lowest/80 rounded-xl border border-outline-variant/60 overflow-hidden shadow-xs">
                        <button type="button" onclick="toggleCategoryDropdown()"
                                class="w-full flex items-center justify-between p-3 hover:bg-surface-container transition-colors text-left select-none">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="material-symbols-outlined text-[18px] text-secondary">category</span>
                                <div class="flex flex-col">
                                    <span class="font-label-md text-label-md text-primary uppercase tracking-wider font-bold">Kategori Wastra</span>
                                    <span class="text-[11px] text-outline truncate" id="categoryActiveLabel">
                                        @if(request('category'))
                                            {{ $categories->firstWhere('id', request('category'))?->name ?? 'Kategori Terpilih' }}
                                        @else
                                            Semua Koleksi ({{ $categories->count() }} Jenis)
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                @if(request('category'))
                                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                                @endif
                                <span class="material-symbols-outlined text-outline text-[20px] transition-transform duration-200 rotate-180" id="categoryDropdownArrow">
                                    expand_more
                                </span>
                            </div>
                        </button>

                        <div id="categoryDropdownList" class="flex flex-col gap-1 p-2 pt-1 pr-1.5 border-t border-outline-variant/30 max-h-60 overflow-y-auto">
                            <label class="flex items-center justify-between p-2 rounded-lg cursor-pointer hover:bg-surface-container transition-colors {{ !request('category') ? 'bg-surface-container text-primary font-bold' : '' }}">
                                <span class="flex items-center gap-space-xs font-label-md text-label-md {{ !request('category') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
                                    <input type="radio" name="category" value="" class="accent-secondary w-3.5 h-3.5"
                                           {{ !request('category') ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()"/>
                                    Semua Koleksi
                                </span>
                                <span class="px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm font-bold">{{ $products->total() }}</span>
                            </label>
                            @foreach($categories as $cat)
                            <label class="flex items-center justify-between p-2 rounded-lg cursor-pointer hover:bg-surface-container transition-colors {{ request('category') == $cat->id ? 'bg-surface-container text-primary font-bold' : '' }}">
                                <span class="flex items-center gap-space-xs font-label-md text-label-md {{ request('category') == $cat->id ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
                                    <input type="radio" name="category" value="{{ $cat->id }}" class="accent-secondary w-3.5 h-3.5"
                                           {{ request('category') == $cat->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()"/>
                                    {{ $cat->name }}
                                </span>
                                <span class="text-outline font-label-sm text-label-sm">{{ $cat->products_count }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- 2. Rentang Harga --}}
                    <div class="flex flex-col gap-space-sm border-t border-outline-variant/60 pt-space-md mb-space-md">
                        <span class="font-label-md text-label-md text-primary uppercase tracking-wider font-bold">Rentang Harga</span>

                        {{-- Kotak Min & Max --}}
                        <div class="grid grid-cols-2 gap-2 mt-1">
                            <div class="p-2 bg-surface-container-lowest rounded-lg border border-outline-variant overflow-hidden">
                                <span class="block font-label-sm text-[9px] text-outline uppercase tracking-wider mb-0.5">Min</span>
                                <span id="displayMin" class="block text-primary font-bold leading-tight"
                                      style="font-size:clamp(9px,1.8vw,12px); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    Rp {{ number_format(request('price_min', 0), 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="p-2 bg-surface-container-lowest rounded-lg border border-outline-variant overflow-hidden">
                                <span class="block font-label-sm text-[9px] text-outline uppercase tracking-wider mb-0.5">Maks</span>
                                <span id="displayMax" class="block text-primary font-bold leading-tight"
                                      style="font-size:clamp(9px,1.8vw,12px); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    Rp {{ number_format(request('price_max', 10000000), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        {{-- Double Slider --}}
                        <div class="relative mt-3 mb-1 px-1">
                            <div class="relative h-1.5 rounded-full bg-outline-variant">
                                <div id="rangeHighlight" class="absolute h-full rounded-full" style="background:#904c2e;"></div>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span style="font-size:9px;" class="text-outline font-label-sm">Rp 0</span>
                                <span style="font-size:9px;" class="text-outline font-label-sm">Rp 10.000.000</span>
                            </div>
                            <input type="range" name="price_min" id="rangeMin"
                                   min="0" max="10000000" step="100000"
                                   value="{{ request('price_min', 0) }}"
                                   class="range-slider absolute w-full"
                                   style="top:-14px;"
                                   oninput="syncRange()"/>
                            <input type="range" name="price_max" id="rangeMax"
                                   min="0" max="10000000" step="100000"
                                   value="{{ request('price_max', 10000000) }}"
                                   class="range-slider absolute w-full"
                                   style="top:-14px;"
                                   oninput="syncRange()"/>
                        </div>

                        <button type="submit"
                                class="w-full py-2 mt-2 bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded-lg transition-colors flex items-center justify-center gap-1 text-xs shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">done_all</span>
                            Terapkan Harga
                        </button>
                    </div>

                </form>

                {{-- 3. Sertifikat Otentisitas --}}
                <div class="p-space-md bg-surface-container rounded-lg flex items-start gap-space-sm border-t border-outline-variant pt-space-md">
                    <span class="material-symbols-outlined text-secondary text-[24px] flex-shrink-0">verified</span>
                    <div class="flex flex-col gap-0.5">
                        <span class="font-label-md text-label-md text-primary font-bold">Sertifikat Otentisitas</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant leading-snug">Setiap helai kain dilengkapi sertifikat fisik bernomor registrasi pembatik.</p>
                    </div>
                </div>

            </div>
        </aside>

        {{-- ══ PRODUCT GRID ══ --}}
        <div class="lg:col-span-9 flex flex-col gap-space-xl">

            <div class="flex items-center justify-between">
                <p class="font-body-sm text-body-sm text-on-surface-variant">
                    Menampilkan <span class="font-bold text-on-surface">{{ $products->total() }}</span> produk
                    @if(request('search'))
                        &bull; "<span class="italic text-secondary">{{ request('search') }}</span>"
                    @endif
                </p>
            </div>

            @if($products->isEmpty())
            <div class="text-center py-16 text-on-surface-variant">
                <span class="material-symbols-outlined text-[64px] block mb-4">inventory_2</span>
                <p class="font-body-md text-body-md mb-2">Tidak ada produk yang sesuai.</p>
                <a href="{{ route('catalog') }}"
                   class="inline-flex items-center gap-1 text-secondary hover:text-primary font-label-lg text-label-lg transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Lihat Semua
                </a>
            </div>
            @else

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-space-md">
                @foreach($products as $product)
                <article class="group flex flex-col bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">

                    {{-- Gambar --}}
                    <div class="card-img-wrap">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"/>
                        @else
                            <div class="card-img-placeholder">
                                <span class="material-symbols-outlined text-[56px] text-outline">draw</span>
                                <span class="font-label-sm text-[10px] text-outline uppercase tracking-wider">Tidak Ada Gambar</span>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 flex flex-col gap-1 items-start">
                            <span class="px-2 py-0.5 rounded-full bg-primary text-on-primary font-label-sm text-[10px] font-bold tracking-wider uppercase shadow-sm">
                                {{ $product->category->name ?? 'Batik' }}
                            </span>
                        </div>
                        <div class="absolute bottom-3 left-3 right-3 bg-surface/90 backdrop-blur-md px-3 py-1.5 rounded-lg text-center transform translate-y-8 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="font-label-sm text-[10px] text-primary font-bold">
                                Stok tersedia: {{ $product->stock }} pcs
                            </span>
                        </div>
                    </div>

                    {{-- Konten --}}
                    <div class="p-3 flex flex-col flex-1 justify-between gap-1">
                        <div>
                            <span class="text-[9px] text-secondary uppercase tracking-widest font-bold block mb-0.5">
                                {{ $product->category->name ?? '-' }}
                            </span>
                            <h3 class="text-sm font-bold text-primary group-hover:text-secondary transition-colors line-clamp-1 leading-tight">
                                {{ $product->name }}
                            </h3>
                            <p class="text-[11px] text-on-surface-variant line-clamp-2 mt-1 leading-snug">
                                {{ Str::limit($product->description, 70) }}
                            </p>
                        </div>
                        <div class="pt-2 border-t border-surface-container mt-1">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[9px] text-outline uppercase tracking-wider">Harga</span>
                                <span class="text-sm font-bold text-primary whitespace-nowrap">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-1">
                                <a href="{{ route('product.show', $product->id) }}"
                                   class="py-1.5 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary text-center text-[11px] font-semibold transition-colors">
                                    Detail
                                </a>
                                @auth
                                    @if(Auth::user()->isProfileComplete())
                                        @php
                                            $catWaMsg = "Halo Juragan Batik, saya ingin memesan produk berikut:\n\n"
                                                . "• Produk: " . $product->name . "\n"
                                                . "• Harga: Rp " . number_format($product->price, 0, ',', '.') . "\n\n"
                                                . "Data Pemesan:\n"
                                                . "• Nama: " . Auth::user()->name . "\n"
                                                . "• Email: " . Auth::user()->email . "\n"
                                                . "• No. Telepon: " . Auth::user()->phone . "\n"
                                                . "• Alamat Pengiriman: " . Auth::user()->address;
                                            $catWaUrl = "https://wa.me/6281382958012?text=" . urlencode($catWaMsg);
                                        @endphp
                                        <a href="{{ $catWaUrl }}" target="_blank" rel="noopener"
                                           class="py-1.5 rounded-lg bg-primary hover:bg-secondary text-on-primary shadow-sm transition-colors flex items-center justify-center gap-1 text-[11px] font-semibold">
                                            <span class="material-symbols-outlined text-[13px]">shopping_bag</span>
                                            <span>Beli</span>
                                        </a>
                                    @else
                                        <a href="{{ route('profile.show') }}"
                                           class="py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white shadow-sm transition-colors flex items-center justify-center gap-1 text-[11px] font-semibold"
                                           title="Lengkapi profil untuk membeli">
                                            <span class="material-symbols-outlined text-[13px]">badge</span>
                                            <span>Lengkapi</span>
                                        </a>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}"
                                       class="py-1.5 rounded-lg bg-primary text-on-primary shadow-sm transition-colors flex items-center justify-center gap-1 text-[11px] font-semibold">
                                        <span class="material-symbols-outlined text-[13px]">login</span>
                                        <span>Beli</span>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-space-md pt-space-lg border-t border-surface-container-high">
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Menampilkan <span class="font-bold text-on-surface">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span>
                    dari <span class="font-bold text-on-surface">{{ $products->total() }}</span> koleksi
                </span>
                {{ $products->links() }}
            </div>

            {{-- Curatorial Banner --}}
            <div class="relative overflow-hidden bg-primary text-on-primary rounded-xl p-space-lg flex flex-col md:flex-row items-center justify-between gap-space-lg shadow-md">
                <div class="flex items-center gap-space-md max-w-xl">
                    <div class="w-14 h-14 rounded-full bg-secondary-fixed flex-shrink-0 flex items-center justify-center text-on-secondary-fixed">
                        <span class="material-symbols-outlined text-[30px]">workspace_premium</span>
                    </div>
                    <div>
                        <span class="font-label-sm text-label-sm text-secondary-container uppercase tracking-widest font-bold block mb-1">Layanan Kurator Privat</span>
                        <h4 class="font-title-lg text-title-lg font-bold">Mencari Motif Pusaka Khusus atau Pesanan Bespoke?</h4>
                        <p class="font-body-sm text-body-sm text-primary-fixed-dim mt-1">Konsultasikan kebutuhan kain adat dengan kurator wastra senior kami.</p>
                    </div>
                </div>
                <a href="https://wa.me/6281382958012?text={{ urlencode('Halo Juragan Batik, saya ingin konsultasi mengenai motif batik.') }}"
                   target="_blank" rel="noopener"
                   class="whitespace-nowrap px-space-lg py-space-sm text-on-secondary font-label-lg text-label-lg rounded-lg shadow-sm transition-all flex items-center gap-space-xs flex-shrink-0 hover:opacity-90"
                   style="background:#25d366;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    <span>Chat via WhatsApp</span>
                </a>
            </div>
            @endif

        </div>
    </div>

</div>
</div>

@endsection

@push('scripts')
<script>
const MAX_PRICE = 10000000;

function formatRupiah(val) {
    return 'Rp ' + parseInt(val).toLocaleString('id-ID');
}

function syncRange() {
    const minSlider  = document.getElementById('rangeMin');
    const maxSlider  = document.getElementById('rangeMax');
    const highlight  = document.getElementById('rangeHighlight');
    const dispMin    = document.getElementById('displayMin');
    const dispMax    = document.getElementById('displayMax');

    let minVal = parseInt(minSlider.value);
    let maxVal = parseInt(maxSlider.value);

    const gap = 100000;
    if (minVal >= maxVal - gap) {
        if (minSlider === document.activeElement) {
            minVal = maxVal - gap;
            minSlider.value = minVal;
        } else {
            maxVal = minVal + gap;
            maxSlider.value = maxVal;
        }
    }

    dispMin.textContent = formatRupiah(minVal);
    dispMax.textContent = formatRupiah(maxVal);

    const pctMin = (minVal / MAX_PRICE) * 100;
    const pctMax = (maxVal / MAX_PRICE) * 100;
    highlight.style.left  = pctMin + '%';
    highlight.style.width = (pctMax - pctMin) + '%';
}

function toggleCategoryDropdown() {
    const list = document.getElementById('categoryDropdownList');
    const arrow = document.getElementById('categoryDropdownArrow');
    if (!list || !arrow) return;
    const isHidden = list.classList.toggle('hidden');
    if (isHidden) {
        arrow.classList.remove('rotate-180');
    } else {
        arrow.classList.add('rotate-180');
    }
}

function toggleEntireFilter() {
    if (window.innerWidth >= 1024) return; // Pada desktop sticky sidebar selalu terbuka
    const content = document.getElementById('filterContent');
    const arrow = document.getElementById('mobileFilterArrow');
    if (!content || !arrow) return;
    const isHidden = content.classList.toggle('hidden');
    if (isHidden) {
        content.classList.remove('flex');
        arrow.classList.remove('rotate-180');
    } else {
        content.classList.add('flex');
        arrow.classList.add('rotate-180');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    syncRange();
});
</script>
@endpush
