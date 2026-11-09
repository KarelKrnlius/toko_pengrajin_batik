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
        height: 4px; background: transparent; top: 1px;
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
                    <span class="font-body-sm text-body-sm text-outline">Siap Kirim</span>
                </div>
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

                    {{-- 1. Kategori Wastra (Dropdown Buka/Tutup, default tertutup) --}}
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
                                {{-- Arrow: rotate-180 = terbuka, default = tertutup --}}
                                <span class="material-symbols-outlined text-outline text-[20px] transition-transform duration-200 {{ request('category') ? 'rotate-180' : '' }}" id="categoryDropdownArrow">
                                    expand_more
                                </span>
                            </div>
                        </button>

                        {{-- Dropdown list: terbuka otomatis jika ada filter aktif --}}
                        <div id="categoryDropdownList" class="{{ request('category') ? 'flex' : 'hidden' }} flex-col border-t border-outline-variant/30">
                            {{-- Search box kategori --}}
                            <div class="px-2 pt-2 pb-1">
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-[16px] text-outline pointer-events-none">search</span>
                                    <input type="text" id="categorySearchInput"
                                           placeholder="Cari kategori…"
                                           autocomplete="off"
                                           oninput="filterCategories(this.value)"
                                           class="w-full pl-7 pr-2 py-1.5 text-[12px] rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface placeholder-outline focus:outline-none focus:border-secondary transition-colors"/>
                                </div>
                            </div>
                            {{-- List kategori --}}
                            <div class="flex flex-col gap-0.5 px-2 pb-2 pr-1.5 max-h-56 overflow-y-auto" id="categoryList">
                                <label class="cat-item flex items-center justify-between p-2 rounded-lg cursor-pointer hover:bg-surface-container transition-colors {{ !request('category') ? 'bg-surface-container text-primary font-bold' : '' }}"
                                       data-name="semua koleksi">
                                    <span class="flex items-center gap-space-xs font-label-md text-label-md {{ !request('category') ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
                                        <input type="radio" name="category" value="" class="accent-secondary w-3.5 h-3.5"
                                               {{ !request('category') ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()"/>
                                        Semua Koleksi
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm font-bold">{{ $products->total() }}</span>
                                </label>
                                @foreach($categories as $cat)
                                <label class="cat-item flex items-center justify-between p-2 rounded-lg cursor-pointer hover:bg-surface-container transition-colors {{ request('category') == $cat->id ? 'bg-surface-container text-primary font-bold' : '' }}"
                                       data-name="{{ strtolower($cat->name) }}">
                                    <span class="flex items-center gap-space-xs font-label-md text-label-md {{ request('category') == $cat->id ? 'text-primary font-bold' : 'text-on-surface-variant' }}">
                                        <input type="radio" name="category" value="{{ $cat->id }}" class="accent-secondary w-3.5 h-3.5"
                                               {{ request('category') == $cat->id ? 'checked' : '' }} onchange="document.getElementById('filterForm').submit()"/>
                                        {{ $cat->name }}
                                    </span>
                                    <span class="text-outline font-label-sm text-label-sm">{{ $cat->products_count }}</span>
                                </label>
                                @endforeach
                                {{-- Pesan kosong saat search tidak ditemukan --}}
                                <div id="catNoResult" class="hidden text-center text-[12px] text-outline py-3">Tidak ditemukan</div>
                            </div>
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
                                   style="top:1px;"
                                   oninput="syncRange()"/>
                            <input type="range" name="price_max" id="rangeMax"
                                   min="0" max="10000000" step="100000"
                                   value="{{ request('price_max', 10000000) }}"
                                   class="range-slider absolute w-full"
                                   style="top:1px;"
                                   oninput="syncRange()"/>
                        </div>

                        <button type="submit"
                                class="w-full py-2 mt-2 bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded-lg transition-colors flex items-center justify-center gap-1 text-xs shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">done_all</span>
                            Terapkan Harga
                        </button>
                    </div>

                </form>

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
                        {{-- Tombol Love / Favorit langsung di kartu katalog --}}
                        <div class="absolute top-2.5 right-2.5 z-10">
                            @auth
                                @php $isCardFav = in_array($product->id, $userFavoriteIds ?? []); @endphp
                                <button type="button"
                                        onclick="toggleCatalogFav({{ $product->id }}, this)"
                                        class="w-8 h-8 rounded-full bg-surface/90 hover:bg-surface backdrop-blur-sm shadow-md flex items-center justify-center transition-transform active:scale-90 group/fav"
                                        title="{{ $isCardFav ? 'Hapus dari Favorit' : 'Tambah ke Favorit' }}">
                                    <span class="material-symbols-outlined text-[18px] text-error transition-all"
                                          style="{{ $isCardFav ? 'font-variation-settings: \'FILL\' 1;' : 'font-variation-settings: \'FILL\' 0;' }}">
                                        favorite
                                    </span>
                                </button>
                            @else
                                <a href="{{ route('login') }}"
                                   class="w-8 h-8 rounded-full bg-surface/90 hover:bg-surface backdrop-blur-sm shadow-md flex items-center justify-center transition-transform active:scale-90 text-error"
                                   title="Login untuk menyimpan favorit">
                                    <span class="material-symbols-outlined text-[18px]">favorite</span>
                                </a>
                            @endauth
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
                                        @if($product->stock > 0)
                                        <button type="button"
                                                onclick="openCatalogBuyModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ json_encode($product->category->name ?? 'Batik') }}, {{ $product->price }}, {{ $product->stock }}, {{ json_encode($product->image ? Storage::url($product->image) : null) }})"
                                                class="py-1.5 rounded-lg bg-primary hover:bg-secondary text-on-primary shadow-sm transition-colors flex items-center justify-center gap-1 text-[11px] font-semibold">
                                            <span class="material-symbols-outlined text-[13px]">shopping_bag</span>
                                            <span>Beli</span>
                                        </button>
                                        @else
                                        <button disabled
                                                class="py-1.5 rounded-lg bg-surface-container-high text-on-surface-variant opacity-60 text-center text-[11px] font-semibold cursor-not-allowed">
                                            Habis
                                        </button>
                                        @endif
                                    @else
                                        <a href="{{ route('profile.show') }}"
                                           class="py-1.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-white shadow-sm transition-colors flex items-center justify-center gap-1 text-[11px] font-semibold"
                                           title="Lengkapi profil untuk membeli">
                                            <span class="material-symbols-outlined text-[13px]">badge</span>
                                            <span>Lengkapi</span>
                                        </a>
                                    @endif
                                @else
                                    @if($product->stock > 0)
                                    <a href="{{ route('login') }}"
                                       class="py-1.5 rounded-lg bg-primary hover:bg-secondary text-on-primary shadow-sm transition-colors flex items-center justify-center gap-1 text-[11px] font-semibold">
                                        <span class="material-symbols-outlined text-[13px]">login</span>
                                        <span>Beli</span>
                                    </a>
                                    @else
                                    <button disabled
                                            class="py-1.5 rounded-lg bg-surface-container-high text-on-surface-variant opacity-60 text-center text-[11px] font-semibold cursor-not-allowed">
                                        Habis
                                    </button>
                                    @endif
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
            @endif

        </div>
    </div>

</div>
</div>

{{-- ── MODAL BELI SEKARANG (WHATSAPP) KATALOG ── --}}
@auth
<div id="catalogBuyModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 shadow-2xl border border-outline-variant/60 flex flex-col gap-4 transform transition-all scale-95 duration-200">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/40">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[24px]">shopping_bag</span>
                <h3 class="font-headline-sm text-base font-bold text-primary">Konfirmasi Pembelian via WhatsApp</h3>
            </div>
            <button type="button" onclick="closeCatalogBuyModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-outline hover:text-primary hover:bg-surface-container transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
            <img id="catBuyImg" src="" alt="Produk" class="w-14 h-14 object-cover rounded-lg flex-shrink-0 hidden"/>
            <div id="catBuyImgPlaceholder" class="w-14 h-14 bg-surface-container rounded-lg flex items-center justify-center flex-shrink-0 text-outline">
                <span class="material-symbols-outlined text-[24px]">draw</span>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-[10px] text-secondary font-bold uppercase tracking-wider block" id="catBuyCategory">BATIK</span>
                <h4 class="text-sm font-bold text-primary truncate" id="catBuyName">Nama Produk</h4>
                <span class="text-xs font-bold text-secondary" id="catBuyPrice">Rp 0 <span class="text-[10px] font-normal text-outline">/ pcs</span></span>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-primary uppercase tracking-wider">Ingin Beli Berapa Barang?</label>
                    <span class="text-xs text-outline">Stok: <strong class="text-primary font-bold" id="catBuyStock">0</strong> pcs</span>
                </div>
                <div class="flex items-center justify-between gap-3 bg-surface-container-low p-2 rounded-xl border border-outline-variant/50">
                    <div class="flex items-center bg-surface-container-highest rounded-lg p-1 border border-outline-variant/60">
                        <button type="button" onclick="adjustCatBuyQty(-1)" class="w-9 h-9 rounded-md bg-surface flex items-center justify-center text-primary hover:bg-surface-container transition shadow-sm font-bold">
                            <span class="material-symbols-outlined text-[18px]">remove</span>
                        </button>
                        <input type="number" id="catBuyQtyInput" value="1" min="1"
                               class="w-14 text-center font-bold text-sm bg-transparent border-none text-primary focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                               oninput="validateCatBuyQty(this)">
                        <button type="button" onclick="adjustCatBuyQty(1)" class="w-9 h-9 rounded-md bg-surface flex items-center justify-center text-primary hover:bg-surface-container transition shadow-sm font-bold">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                        </button>
                    </div>
                    <div class="text-right pr-2">
                        <span class="block text-[10px] text-outline uppercase font-semibold">Total Pembayaran</span>
                        <span class="text-base font-bold text-secondary" id="catBuySubtotal">
                            Rp 0
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-3 bg-surface-container rounded-xl text-xs text-on-surface-variant flex flex-col gap-1 border border-outline-variant/30">
                <span class="font-bold text-primary flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px] text-secondary">contact_phone</span>
                    Tujuan Pemesanan:
                </span>
                <p>Pesanan akan langsung dialihkan ke WhatsApp resmi Juragan Batik: <strong class="text-primary">081382958012</strong> beserta rincian jumlah dan data pengiriman Anda.</p>
            </div>

            <div class="flex gap-2 pt-1">
                <button type="button" onclick="closeCatalogBuyModal()" class="flex-1 py-2.5 px-4 rounded-xl border border-outline-variant hover:bg-surface-container text-on-surface-variant font-bold text-xs uppercase tracking-wider transition">
                    Batal
                </button>
                <button type="button" onclick="submitCatBuyToWhatsApp()" class="flex-1 py-2.5 px-4 rounded-xl bg-primary hover:bg-secondary text-on-primary font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-1.5 shadow-md">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    <span>Bayar ke WA</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endauth

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
    const list  = document.getElementById('categoryDropdownList');
    const arrow = document.getElementById('categoryDropdownArrow');
    const input = document.getElementById('categorySearchInput');
    if (!list || !arrow) return;

    const isNowHidden = list.classList.toggle('hidden');
    list.classList.toggle('flex', !isNowHidden);

    if (isNowHidden) {
        arrow.classList.remove('rotate-180');
    } else {
        arrow.classList.add('rotate-180');
        // Auto-fokus ke search input saat dropdown dibuka
        if (input) setTimeout(() => input.focus(), 80);
    }
}

function filterCategories(query) {
    const q       = query.trim().toLowerCase();
    const items   = document.querySelectorAll('#categoryList .cat-item');
    const noResult = document.getElementById('catNoResult');
    let visible = 0;

    items.forEach(item => {
        const name = item.dataset.name || '';
        const match = !q || name.includes(q);
        item.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    if (noResult) noResult.classList.toggle('hidden', visible > 0);
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

async function toggleCatalogFav(productId, btn) {
    const icon = btn.querySelector('.material-symbols-outlined');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    try {
        const response = await fetch(`/favorit/${productId}/toggle`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken || ''
            }
        });

        const data = await response.json();
        if (data && data.success) {
            if (data.is_favorited) {
                icon.style.fontVariationSettings = "'FILL' 1";
                btn.setAttribute('title', 'Hapus dari Favorit');
            } else {
                icon.style.fontVariationSettings = "'FILL' 0";
                btn.setAttribute('title', 'Tambah ke Favorit');
            }
        }
    } catch (e) {
        console.error('Gagal toggle favorit:', e);
    }
}

document.addEventListener('DOMContentLoaded', function () {
    syncRange();
});

// Modal Beli Sekarang Katalog (WhatsApp)
let currentCatProduct = null;

function formatRupiahCat(num) {
    return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function openCatalogBuyModal(id, name, category, price, stock, imageUrl) {
    currentCatProduct = { id, name, category, price, stock };

    const modal = document.getElementById('catalogBuyModal');
    if (!modal) return;

    document.getElementById('catBuyName').innerText = name;
    document.getElementById('catBuyCategory').innerText = category.toUpperCase();
    document.getElementById('catBuyPrice').innerHTML = formatRupiahCat(price) + ' <span class="text-[10px] font-normal text-outline">/ pcs</span>';
    document.getElementById('catBuyStock').innerText = stock;

    const imgEl = document.getElementById('catBuyImg');
    const imgPlaceholder = document.getElementById('catBuyImgPlaceholder');
    if (imageUrl) {
        imgEl.src = imageUrl;
        imgEl.classList.remove('hidden');
        if (imgPlaceholder) imgPlaceholder.classList.add('hidden');
    } else {
        imgEl.classList.add('hidden');
        if (imgPlaceholder) imgPlaceholder.classList.remove('hidden');
    }

    const input = document.getElementById('catBuyQtyInput');
    if (input) {
        input.value = 1;
        input.max = stock;
    }
    updateCatBuySubtotal(1);

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCatalogBuyModal() {
    const modal = document.getElementById('catalogBuyModal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function updateCatBuySubtotal(qty) {
    if (!currentCatProduct) return;
    const subtotal = qty * currentCatProduct.price;
    const el = document.getElementById('catBuySubtotal');
    if (el) el.innerText = formatRupiahCat(subtotal);
}

function adjustCatBuyQty(delta) {
    if (!currentCatProduct) return;
    const input = document.getElementById('catBuyQtyInput');
    if (!input) return;
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (val > currentCatProduct.stock) val = currentCatProduct.stock;
    input.value = val;
    updateCatBuySubtotal(val);
}

function validateCatBuyQty(input) {
    if (!currentCatProduct) return;
    let val = parseInt(input.value);
    if (isNaN(val) || val < 1) {
        val = 1;
    } else if (val > currentCatProduct.stock) {
        val = currentCatProduct.stock;
        input.value = currentCatProduct.stock;
    }
    updateCatBuySubtotal(val);
}

function submitCatBuyToWhatsApp() {
    if (!currentCatProduct) return;
    const input = document.getElementById('catBuyQtyInput');
    let qty = input ? (parseInt(input.value) || 1) : 1;
    if (qty < 1) qty = 1;
    if (qty > currentCatProduct.stock) qty = currentCatProduct.stock;

    const subtotal = qty * currentCatProduct.price;
    const subtotalFormatted = formatRupiahCat(subtotal);
    const unitPriceFormatted = formatRupiahCat(currentCatProduct.price);

    @auth
    const userName = @json(Auth::user()->name);
    const userEmail = @json(Auth::user()->email);
    const userPhone = @json(Auth::user()->phone ?? '-');
    const userAddress = @json(Auth::user()->address ?? '-');
    @else
    const userName = '-';
    const userEmail = '-';
    const userPhone = '-';
    const userAddress = '-';
    @endauth

    let msg = `Halo Juragan Batik, saya ingin memesan produk berikut:\n\n` +
        `• Produk: ${currentCatProduct.name}\n` +
        `• Kategori: ${currentCatProduct.category}\n` +
        `• Harga Satuan: ${unitPriceFormatted}\n` +
        `• Jumlah Beli: ${qty} pcs\n` +
        `• Total Pembayaran: ${subtotalFormatted}\n\n` +
        `Data Pemesan:\n` +
        `• Nama: ${userName}\n` +
        `• Email: ${userEmail}\n` +
        `• No. Telepon: ${userPhone}\n` +
        `• Alamat Pengiriman: ${userAddress}`;

    const url = 'https://wa.me/6281382958012?text=' + encodeURIComponent(msg);
    closeCatalogBuyModal();
    window.open(url, '_blank');
}

// Escape key to close modal
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeCatalogBuyModal();
    }
});
</script>
@endpush
