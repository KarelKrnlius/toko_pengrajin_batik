@extends('layouts.app')
@section('title', 'Portal Kurator — Manajemen Produk')

@push('styles')
<style>
    .thumb-img { width:56px; height:56px; object-fit:cover; border-radius:8px; }
    .thumb-placeholder { width:56px; height:56px; border-radius:8px; background:#f1ede7;
                         display:flex; align-items:center; justify-content:center; }
    /* Tailwind pagination override */
    nav[role="navigation"] svg { display:inline; }
    nav[role="navigation"] span, nav[role="navigation"] a {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2.25rem; height: 2.25rem; border-radius: 0.5rem;
        font-size: 12px; font-weight: 600; letter-spacing: 0.05em;
        transition: background 0.15s, color 0.15s;
    }
    nav[role="navigation"] a { background:#f1ede7; color:#311908; }
    nav[role="navigation"] a:hover { background:#311908; color:#fff; }
    nav[role="navigation"] span[aria-current] { background:#311908; color:#fff; }
    nav[role="navigation"] span.text-gray-500 { color:#82746d; pointer-events:none; }

    /* ── Toast Notification ── */
    #toast-container {
        position: fixed;
        top: 5.5rem; /* di bawah navbar */
        right: 1.25rem;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        pointer-events: none;
    }
    .toast {
        pointer-events: all;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 300px;
        max-width: 420px;
        padding: 0.875rem 1rem;
        border-radius: 0.75rem;
        box-shadow: 0 8px 24px rgba(49,25,8,.18);
        transform: translateX(120%);
        opacity: 0;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;
    }
    .toast.show {
        transform: translateX(0);
        opacity: 1;
    }
    .toast.hide {
        transform: translateX(120%);
        opacity: 0;
    }
    .toast-success {
        background: #ffffff;
        border-left: 4px solid #386a20;
    }
    .toast-error {
        background: #ffffff;
        border-left: 4px solid #ba1a1a;
    }
    .toast-icon {
        width: 36px; height: 36px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .toast-success .toast-icon { background: #d2f4d3; }
    .toast-error   .toast-icon { background: #ffdad6; }
    .toast-progress {
        position: absolute;
        bottom: 0; left: 0;
        height: 3px;
        border-radius: 0 0 0.75rem 0.75rem;
        animation: toastProgress 4s linear forwards;
    }
    .toast-success .toast-progress { background: #386a20; }
    .toast-error   .toast-progress { background: #ba1a1a; }
    @keyframes toastProgress {
        from { width: 100%; }
        to   { width: 0%; }
    }
</style>
@endpush

@section('content')

{{-- ══ Admin Header ══ --}}
<div class="w-full bg-gradient-to-r from-primary via-primary-container to-secondary py-8">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md">
        <div>
            <span class="font-label-sm text-label-sm text-secondary-fixed uppercase tracking-[0.2em] block mb-1">PORTAL KURATOR</span>
            <h1 class="font-headline-md text-headline-md text-on-primary font-bold">Manajemen Inventaris Produk</h1>
            <p class="font-body-sm text-body-sm text-primary-fixed-dim mt-1">
                Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>
                &bull; Total <span class="font-semibold">{{ $products->total() }}</span> produk
            </p>
        </div>
        <div class="flex items-center gap-space-sm flex-shrink-0">
            <button type="button"
                    onclick="document.getElementById('modalKategori').classList.remove('hidden'); loadCategories()"
                    class="inline-flex items-center justify-center gap-space-xs px-space-md bg-on-primary/10 hover:bg-on-primary/20 text-on-primary font-label-lg text-label-lg rounded transition-colors h-10 shadow-none">
                <span class="material-symbols-outlined text-[20px]">category</span>
                <span class="hidden sm:inline">Kategori</span>
            </button>
            <button type="button"
                    onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                    class="inline-flex items-center justify-center gap-space-xs px-space-md bg-surface-container-lowest hover:bg-surface text-primary font-label-lg text-label-lg rounded shadow-sm transition-colors h-10">
                <span class="material-symbols-outlined text-[20px] text-secondary">add_circle</span>
                <span class="hidden sm:inline">Tambah Produk</span>
            </button>
            <form action="{{ route('logout') }}" method="POST" class="inline-flex items-center m-0 p-0">
                @csrf
                <button type="submit"
                        class="inline-flex items-center justify-center gap-space-xs px-space-md bg-on-primary/10 hover:bg-on-primary/20 text-on-primary font-label-lg text-label-lg rounded transition-colors h-10 shadow-none">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                    <span class="hidden sm:inline">Keluar</span>
                </button>
            </form>
        </div>
    </div>  
</div>

<div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

    {{-- Flash Messages — ditangani oleh toast di bawah --}}

    {{-- ══ Toolbar: Search + Filter Kategori + Sort ══ --}}
    <form method="GET" action="{{ route('admin.products.index') }}"
          class="flex flex-col lg:flex-row gap-space-md mb-space-lg bg-surface-container-lowest rounded-xl shadow-sm p-space-md border border-outline-variant">

        {{-- Search --}}
        <div class="relative flex-1 min-w-0">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama produk atau kategori..."
                   class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
        </div>

        {{-- Filter Kategori (Scrollable jika > 5) --}}
        <div class="relative" id="toolbarCategoryDropdownWrap">
            <input type="hidden" name="category" id="toolbarCategoryInput" value="{{ request('category') }}">
            @php
                $selectedCategory = $categories->firstWhere('id', request('category'));
                $selectedCategoryName = $selectedCategory ? $selectedCategory->name : 'Semua Kategori';
            @endphp
            <button type="button" onclick="toggleToolbarCategoryDropdown()"
                    class="flex items-center justify-between gap-2 pl-9 pr-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer min-w-[190px] w-full text-left">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">category</span>
                <span class="truncate" id="toolbarCategoryLabel">{{ $selectedCategoryName }}</span>
                <span class="material-symbols-outlined text-outline text-[18px] pointer-events-none">expand_more</span>
            </button>
            <div id="toolbarCategoryMenu"
                 class="hidden absolute left-0 top-full mt-1 w-full min-w-[200px] bg-surface-container-lowest border border-outline-variant rounded-lg shadow-xl z-40 max-h-[220px] overflow-y-auto py-1 pr-1.5">
                <div onclick="selectToolbarCategory('', 'Semua Kategori')"
                     class="px-3 py-2 text-sm text-on-surface hover:bg-surface-container cursor-pointer transition-colors flex items-center justify-between {{ !request('category') ? 'bg-surface-container text-primary font-bold' : '' }}">
                    <span>Semua Kategori</span>
                    @if(!request('category'))
                        <span class="material-symbols-outlined text-[16px] text-secondary">check</span>
                    @endif
                </div>
                @foreach($categories as $cat)
                    <div onclick="selectToolbarCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}')"
                         class="px-3 py-2 text-sm text-on-surface hover:bg-surface-container cursor-pointer transition-colors flex items-center justify-between {{ request('category') == $cat->id ? 'bg-surface-container text-primary font-bold' : '' }}">
                        <span class="truncate">{{ $cat->name }}</span>
                        @if(request('category') == $cat->id)
                            <span class="material-symbols-outlined text-[16px] text-secondary">check</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Sort --}}
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">sort</span>
            <select name="sort"
                    class="pl-9 pr-8 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer min-w-[180px]">
                <option value="terbaru"     {{ $sort === 'terbaru'     ? 'selected' : '' }}>Terbaru</option>
                <option value="terlama"     {{ $sort === 'terlama'     ? 'selected' : '' }}>Terlama</option>
                <option value="nama_az"     {{ $sort === 'nama_az'     ? 'selected' : '' }}>Nama A–Z</option>
                <option value="nama_za"     {{ $sort === 'nama_za'     ? 'selected' : '' }}>Nama Z–A</option>
                <option value="harga_naik"  {{ $sort === 'harga_naik'  ? 'selected' : '' }}>Harga Terendah</option>
                <option value="harga_turun" {{ $sort === 'harga_turun' ? 'selected' : '' }}>Harga Tertinggi</option>
                <option value="stok_naik"   {{ $sort === 'stok_naik'   ? 'selected' : '' }}>Stok Sedikit</option>
                <option value="stok_turun"  {{ $sort === 'stok_turun'  ? 'selected' : '' }}>Stok Terbanyak</option>
            </select>
            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-space-sm flex-shrink-0">
            <button type="submit"
                    class="inline-flex items-center gap-1 px-space-lg py-2.5 bg-primary hover:bg-secondary text-on-primary text-sm font-bold tracking-wider rounded-lg transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
            @if(request()->hasAny(['search','category','sort']) && request('sort','terbaru') !== 'terbaru' || request('search') || request('category'))
            <a href="{{ route('admin.products.index') }}"
               class="inline-flex items-center gap-1 px-space-md py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-sm font-semibold rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
                Reset
            </a>
            @endif
        </div>
    </form>

    {{-- ══ Info Bar ══ --}}
    <div class="flex items-center justify-between mb-space-md px-1">
        <p class="text-sm text-on-surface-variant">
            Menampilkan
            <span class="font-bold text-on-surface">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</span>
            dari <span class="font-bold text-on-surface">{{ $products->total() }}</span> produk
            @if(request('search'))
                &bull; pencarian "<span class="italic text-secondary">{{ request('search') }}</span>"
            @endif
            @if(request('sort') && request('sort') !== 'terbaru')
                &bull; diurutkan: <span class="font-semibold text-primary">{{ $sortLabel }}</span>
            @endif
        </p>
        <span class="text-xs text-outline">Halaman {{ $products->currentPage() }} / {{ $products->lastPage() }}</span>
    </div>

    {{-- ══ Tabel ══ --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant">
        <div class="overflow-x-auto">
            <table class="w-full table-fixed">
                <thead>
                    <tr class="bg-primary text-on-primary">
                        <th class="px-4 py-3 text-center font-label-md text-label-md tracking-wider" style="width:48px">#</th>
                        <th class="px-4 py-3 text-left font-label-md text-label-md tracking-wider col-foto" style="width:80px">Foto</th>
                        <th class="px-4 py-3 text-left font-label-md text-label-md tracking-wider">Nama Produk</th>
                        <th class="px-4 py-3 text-left font-label-md text-label-md tracking-wider hidden md:table-cell" style="width:150px">Kategori</th>
                        <th class="px-4 py-3 text-right font-label-md text-label-md tracking-wider hidden lg:table-cell" style="width:160px">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $sort === 'harga_naik' ? 'harga_turun' : 'harga_naik']) }}"
                               class="inline-flex items-center justify-end gap-1 hover:text-tertiary-fixed transition-colors w-full">
                                Harga
                                <span class="material-symbols-outlined text-[14px]">
                                    {{ in_array($sort, ['harga_naik','harga_turun']) ? ($sort === 'harga_naik' ? 'arrow_upward' : 'arrow_downward') : 'unfold_more' }}
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center font-label-md text-label-md tracking-wider hidden lg:table-cell" style="width:90px">
                            <a href="{{ request()->fullUrlWithQuery(['sort' => $sort === 'stok_naik' ? 'stok_turun' : 'stok_naik']) }}"
                               class="inline-flex items-center justify-center gap-1 hover:text-tertiary-fixed transition-colors">
                                Stok
                                <span class="material-symbols-outlined text-[14px]">
                                    {{ in_array($sort, ['stok_naik','stok_turun']) ? ($sort === 'stok_naik' ? 'arrow_upward' : 'arrow_downward') : 'unfold_more' }}
                                </span>
                            </a>
                        </th>
                        <th class="px-4 py-3 text-center font-label-md text-label-md tracking-wider" style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    @forelse($products as $i => $product)
                    <tr class="hover:bg-surface-container-low transition-colors group">

                        {{-- Nomor urut global (bukan reset per halaman) --}}
                        <td class="px-4 py-3 text-center">
                            <span class="font-label-md text-label-md text-outline font-bold">
                                {{ $products->firstItem() + $loop->index }}
                            </span>
                        </td>

                        {{-- Foto --}}
                        <td class="px-4 py-3 col-foto">
                            @if($product->image)
                                <img src="{{ Storage::url($product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="thumb-img border border-outline-variant"/>
                            @else
                                <div class="thumb-placeholder">
                                    <span class="material-symbols-outlined text-[18px] text-outline">hide_image</span>
                                </div>
                            @endif
                        </td>

                        {{-- Nama & Deskripsi --}}
                        <td class="px-4 py-3 overflow-hidden">
                            <p class="font-title-lg text-title-lg text-primary truncate group-hover:text-secondary transition-colors">
                                {{ $product->name }}
                            </p>
                            <p class="text-xs text-on-surface-variant mt-0.5 truncate">
                                {{ Str::limit($product->description, 55) }}
                            </p>
                        </td>

                        {{-- Kategori --}}
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="px-2 py-0.5 rounded-full bg-secondary-fixed text-on-secondary-fixed font-label-sm text-label-sm font-bold whitespace-nowrap">
                                {{ $product->category->name ?? '-' }}
                            </span>
                        </td>

                        {{-- Harga --}}
                        <td class="px-4 py-3 text-right hidden lg:table-cell whitespace-nowrap">
                            <span class="font-label-lg text-label-lg text-primary font-bold">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Stok --}}
                        <td class="px-4 py-3 text-center hidden lg:table-cell">
                            @if($product->stock > 10)
                                <span class="px-2 py-0.5 rounded-full font-label-sm text-label-sm font-bold" style="background:#d2f4d3;color:#1a6b1e">
                                    {{ $product->stock }}
                                </span>
                            @elseif($product->stock > 0)
                                <span class="px-2 py-0.5 rounded-full bg-tertiary-fixed font-label-sm text-label-sm font-bold text-primary">
                                    {{ $product->stock }}
                                </span>
                            @else
                                <span class="px-2 py-0.5 rounded-full bg-error-container text-error font-label-sm text-label-sm font-bold">
                                    Habis
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button"
                                        onclick="openEditModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->category_id }}, {{ $product->price }}, {{ $product->stock }}, {{ json_encode($product->description) }}, {{ $product->image ? json_encode(Storage::url($product->image)) : 'null' }})"
                                        class="w-9 h-9 rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary flex items-center justify-center transition-colors flex-shrink-0"
                                        title="Edit Produk">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                      class="m-0 flex items-center"
                                      onsubmit="return confirm('Hapus produk {{ addslashes($product->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-9 h-9 rounded-lg bg-surface-container hover:bg-error hover:text-on-error text-error flex items-center justify-center transition-colors flex-shrink-0"
                                            title="Hapus Produk">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-space-lg py-16 text-center">
                            <span class="material-symbols-outlined text-[64px] text-outline block mb-4">inventory_2</span>
                            <p class="font-body-md text-body-md text-on-surface-variant mb-1">
                                @if(request('search'))
                                    Tidak ada produk yang cocok dengan "<strong>{{ request('search') }}</strong>"
                                @else
                                    Belum ada produk. Tambahkan produk pertama!
                                @endif
                            </p>
                            @if(request('search') || request('category'))
                                <a href="{{ route('admin.products.index') }}"
                                   class="inline-flex items-center gap-1 mt-2 text-secondary hover:text-primary text-sm font-semibold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Tampilkan Semua
                                </a>
                            @else
                                <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                                        class="mt-3 inline-flex items-center gap-space-xs px-space-lg py-space-sm bg-primary text-on-primary font-label-lg text-label-lg rounded hover:bg-secondary transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                                    Tambah Sekarang
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ══ Footer Tabel: Pagination ══ --}}
        @if($products->hasPages())
        <div class="flex flex-col sm:flex-row items-center justify-between gap-space-sm px-space-lg py-space-md border-t border-surface-container bg-surface-container-low">
            <p class="text-xs text-on-surface-variant">
                Halaman <span class="font-bold text-on-surface">{{ $products->currentPage() }}</span>
                dari <span class="font-bold text-on-surface">{{ $products->lastPage() }}</span>
                &bull; {{ $products->total() }} produk total
            </p>
            {{-- Custom pagination links --}}
            <div class="flex items-center gap-1">
                {{-- Prev --}}
                @if($products->onFirstPage())
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container text-outline cursor-not-allowed text-[18px]">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </span>
                @else
                    <a href="{{ $products->previousPageUrl() }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </a>
                @endif

                {{-- Page numbers --}}
                @php
                    $start = max(1, $products->currentPage() - 2);
                    $end   = min($products->lastPage(), $products->currentPage() + 2);
                @endphp

                @if($start > 1)
                    <a href="{{ $products->url(1) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary text-xs font-bold transition-colors">1</a>
                    @if($start > 2)
                        <span class="w-6 text-center text-outline text-xs">…</span>
                    @endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $products->currentPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-on-primary text-xs font-bold shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $products->url($page) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary text-xs font-bold transition-colors">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $products->lastPage())
                    @if($end < $products->lastPage() - 1)
                        <span class="w-6 text-center text-outline text-xs">…</span>
                    @endif
                    <a href="{{ $products->url($products->lastPage()) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary text-xs font-bold transition-colors">{{ $products->lastPage() }}</a>
                @endif

                {{-- Next --}}
                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </a>
                @else
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container text-outline cursor-not-allowed">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ══ Toast Container ══ --}}
<div id="toast-container"></div>

{{-- ══════════ MODAL KATEGORI ══════════ --}}
<div id="modalKategori" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest w-full max-w-lg rounded-xl shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        {{-- Header --}}
        <div class="bg-primary px-space-lg py-space-md flex items-center justify-between flex-shrink-0">
            <h3 class="font-headline-sm text-headline-sm text-on-primary flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[22px]">category</span>
                Manajemen Kategori
            </h3>
            <button onclick="document.getElementById('modalKategori').classList.add('hidden')"
                    class="text-on-primary/70 hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>

        {{-- Body --}}
        <div class="flex flex-col flex-1 overflow-hidden">

            {{-- Tambah Kategori --}}
            <div class="px-space-lg pt-space-md pb-space-sm border-b border-outline-variant">
                <form id="formTambahKategori" onsubmit="submitCategory(event)" class="flex gap-space-sm">
                    <input type="text" id="inputNamaKategori" placeholder="Nama kategori baru..."
                           class="flex-1 px-space-md py-2.5 bg-surface-container-low rounded-lg text-sm text-on-surface border border-outline-variant focus:outline-none focus:ring-2 focus:ring-secondary" required />
                    <button type="submit" id="btnTambahKategori"
                            class="inline-flex items-center gap-1 px-space-md py-2.5 bg-primary hover:bg-secondary text-on-primary text-sm font-bold rounded-lg transition-colors shadow-sm flex-shrink-0">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        <span class="hidden sm:inline">Tambah</span>
                    </button>
                </form>
                <p id="kategoriError" class="hidden text-error text-xs mt-1.5 font-medium"></p>
            </div>

            {{-- Sort --}}
            <div class="px-space-lg py-space-sm border-b border-outline-variant flex items-center justify-between gap-space-sm flex-shrink-0">
                <span class="text-xs text-on-surface-variant font-semibold whitespace-nowrap" id="kategoriInfo">0 kategori</span>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-outline text-[16px] pointer-events-none">sort</span>
                    <select id="kategoriSort" onchange="loadCategories()"
                            class="pl-8 pr-7 py-1.5 bg-surface-container-low border border-outline-variant rounded-lg text-xs text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer">
                        <option value="terbaru">Terbaru</option>
                        <option value="terlama">Terlama</option>
                        <option value="a-z">A – Z</option>
                        <option value="z-a">Z – A</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-1.5 top-1/2 -translate-y-1/2 text-outline text-[14px] pointer-events-none">expand_more</span>
                </div>
            </div>

            {{-- List --}}
            <div id="kategoriList" class="flex-1 overflow-y-auto px-space-lg py-space-sm">
                <div class="flex items-center justify-center py-8 text-outline">
                    <span class="material-symbols-outlined text-[28px] animate-spin">progress_activity</span>
                </div>
            </div>

            {{-- Pagination --}}
            <div id="kategoriPagination" class="hidden px-space-lg py-space-sm border-t border-outline-variant flex-shrink-0">
            </div>
        </div>
    </div>
</div>

{{-- ══════════ MODAL TAMBAH ══════════ --}}
<div id="modalTambah" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-2xl w-full rounded-xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="bg-primary px-space-lg py-space-md flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm text-on-primary flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[22px]">add_circle</span>
                Tambah Produk Baru
            </h3>
            <button onclick="document.getElementById('modalTambah').classList.add('hidden')"
                    class="text-on-primary/70 hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-space-lg flex flex-col gap-space-md">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                <div class="md:col-span-2">
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Nama Produk <span class="text-error">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Kemeja Sutra Parang Rusak Slimfit"
                           class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant"/>
                </div>
                <div class="relative" id="comboboxWrap_tambah">
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Kategori <span class="text-error">*</span></label>
                    <input type="hidden" name="category_id" id="tambah_category_id" required value=""/>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">category</span>
                        <input type="text" id="tambah_category_search" autocomplete="off"
                               placeholder="Ketik atau cari kategori..."
                               class="w-full pl-9 pr-8 py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant cursor-pointer"
                               onclick="openCategoryCombobox('tambah')" oninput="filterCategoryCombobox('tambah')" onfocus="openCategoryCombobox('tambah')"/>
                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
                    </div>
                    <div id="tambah_category_list" class="hidden absolute left-0 right-0 top-full mt-1 bg-surface-container-lowest border border-outline-variant rounded-lg shadow-xl z-50 max-h-48 overflow-y-auto py-1 pr-1.5">
                        @foreach($categories as $cat)
                            <div class="px-3 py-2 text-sm text-on-surface hover:bg-surface-container cursor-pointer transition-colors flex items-center justify-between category-item"
                                 data-id="{{ $cat->id }}" data-name="{{ strtolower($cat->name) }}"
                                 onclick="selectCategoryCombobox('tambah', {{ $cat->id }}, '{{ addslashes($cat->name) }}')">
                                <span>{{ $cat->name }}</span>
                            </div>
                        @endforeach
                        <div id="tambah_category_empty" class="hidden px-3 py-2 text-xs text-outline italic text-center">
                            Kategori tidak ditemukan
                        </div>
                    </div>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Harga (Rp) <span class="text-error">*</span></label>
                    <input type="number" name="price" required min="0" placeholder="1850000"
                           class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Stok <span class="text-error">*</span></label>
                    <input type="number" name="stock" required min="0" placeholder="50"
                           class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Gambar Produk</label>

                    {{-- Bingkai Cropper Tambah — rasio 3:4 persis katalog produk --}}
                    <div class="mx-auto mb-2 rounded-xl border-2 border-dashed border-outline-variant bg-surface-container-low overflow-hidden relative shadow-inner"
                         style="width:210px; height:280px; aspect-ratio:3/4;">

                        {{-- Placeholder --}}
                        <div id="cropTambahPlaceholder"
                             class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-outline cursor-pointer select-none"
                             onclick="document.getElementById('imageTambah').click()">
                            <span class="material-symbols-outlined text-[44px]">add_photo_alternate</span>
                            <span class="text-xs font-bold tracking-wider uppercase">Klik untuk pilih foto</span>
                            <span class="text-[10px] text-outline">Rasio 3:4 • jpg, png, webp • maks 2MB</span>
                        </div>

                        {{-- Canvas area crop --}}
                        <div id="cropTambahArea" class="hidden absolute inset-0 overflow-hidden bg-surface-container-low touch-none select-none cursor-grab active:cursor-grabbing">
                            <img id="cropTambahImage"
                                 src="" alt=""
                                 style="position:absolute; transform-origin:top left; will-change:transform;"
                                 draggable="false"/>
                            {{-- Overlay grid --}}
                            <div class="pointer-events-none absolute inset-0" style="
                                background-image:
                                    linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                                    linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px);
                                background-size: 33.33% 33.33%;
                                border: 2px solid rgba(255,255,255,.4);
                            "></div>
                        </div>

                        {{-- Toolbar crop --}}
                        <div id="cropTambahToolbar"
                             class="hidden absolute bottom-0 left-0 right-0 flex items-center justify-between px-3 py-1.5"
                             style="background:rgba(49,25,8,.7); backdrop-filter:blur(4px);">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="cropZoom('tambah',-0.1)"
                                        class="w-7 h-7 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition-colors" title="Zoom Out">
                                    <span class="material-symbols-outlined text-[16px]">zoom_out</span>
                                </button>
                                <button type="button" onclick="cropZoom('tambah',0.1)"
                                        class="w-7 h-7 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition-colors" title="Zoom In">
                                    <span class="material-symbols-outlined text-[16px]">zoom_in</span>
                                </button>
                                <button type="button" onclick="cropReset('tambah')"
                                        class="w-7 h-7 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition-colors" title="Reset">
                                    <span class="material-symbols-outlined text-[16px]">center_focus_strong</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-white/70 text-[10px]">Geser & zoom untuk posisikan foto</span>
                                <button type="button" onclick="clearCrop('tambah')"
                                        class="w-7 h-7 rounded-full bg-red-500/70 hover:bg-red-500 text-white flex items-center justify-center transition-colors" title="Ganti Foto">
                                    <span class="material-symbols-outlined text-[15px]">close</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Input file tersembunyi --}}
                    <input type="file" id="imageTambah" accept="image/*" class="hidden"
                           onchange="initCrop(this, 'tambah')"/>
                    {{-- Hidden input yang menyimpan hasil crop sebagai base64 --}}
                    <input type="hidden" id="cropDataTambah" name="crop_image"/>
                    {{-- Input file asli tetap dikirim jika tidak di-crop --}}
                    <input type="hidden" id="cropActiveTambah" name="crop_active" value="0"/>

                    <p class="text-[10px] text-on-surface-variant mt-1">Pilih foto lalu geser & zoom untuk menyesuaikan area tampil.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Deskripsi <span class="text-error">*</span></label>
                    <textarea name="description" required rows="4" placeholder="Jelaskan bahan, motif, proses pengerjaan..."
                              class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant resize-none"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-space-md pt-space-xs border-t border-surface-container">
                <button type="button" onclick="document.getElementById('modalTambah').classList.add('hidden')"
                        class="px-space-lg py-space-sm bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg rounded transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-space-xs px-space-lg py-space-sm bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Simpan Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ MODAL EDIT ══════════ --}}
<div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-2xl w-full rounded-xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="px-space-lg py-space-md flex items-center justify-between" style="background:#904c2e;">
            <h3 class="font-headline-sm text-headline-sm text-on-primary flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[22px]">edit</span>
                Edit Produk
            </h3>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="text-on-primary/70 hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
        <form id="formEdit" method="POST" enctype="multipart/form-data" class="p-space-lg flex flex-col gap-space-md">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                <div class="md:col-span-2">
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Nama Produk <span class="text-error">*</span></label>
                    <input type="text" name="name" id="edit_name" required
                           class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant"/>
                </div>
                <div class="relative" id="comboboxWrap_edit">
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Kategori <span class="text-error">*</span></label>
                    <input type="hidden" name="category_id" id="edit_category_id" required value=""/>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">category</span>
                        <input type="text" id="edit_category_search" autocomplete="off"
                               placeholder="Ketik atau cari kategori..."
                               class="w-full pl-9 pr-8 py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant cursor-pointer"
                               onclick="openCategoryCombobox('edit')" oninput="filterCategoryCombobox('edit')" onfocus="openCategoryCombobox('edit')"/>
                        <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
                    </div>
                    <div id="edit_category_list" class="hidden absolute left-0 right-0 top-full mt-1 bg-surface-container-lowest border border-outline-variant rounded-lg shadow-xl z-50 max-h-48 overflow-y-auto py-1 pr-1.5">
                        @foreach($categories as $cat)
                            <div class="px-3 py-2 text-sm text-on-surface hover:bg-surface-container cursor-pointer transition-colors flex items-center justify-between category-item"
                                 data-id="{{ $cat->id }}" data-name="{{ strtolower($cat->name) }}"
                                 onclick="selectCategoryCombobox('edit', {{ $cat->id }}, '{{ addslashes($cat->name) }}')">
                                <span>{{ $cat->name }}</span>
                            </div>
                        @endforeach
                        <div id="edit_category_empty" class="hidden px-3 py-2 text-xs text-outline italic text-center">
                            Kategori tidak ditemukan
                        </div>
                    </div>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Harga (Rp) <span class="text-error">*</span></label>
                    <input type="number" name="price" id="edit_price" required min="0"
                           class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Stok <span class="text-error">*</span></label>
                    <input type="number" name="stock" id="edit_stock" required min="0"
                           class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant"/>
                </div>
                <div>
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Gambar Baru (opsional)</label>

                    {{-- Bingkai Cropper Edit — rasio 3:4 persis katalog produk --}}
                    <div class="mx-auto mb-2 rounded-xl border-2 border-dashed border-outline-variant bg-surface-container-low overflow-hidden relative shadow-inner"
                         style="width:210px; height:280px; aspect-ratio:3/4;">

                        <div id="cropEditPlaceholder"
                             class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-outline cursor-pointer select-none"
                             onclick="document.getElementById('imageEdit').click()">
                            <span class="material-symbols-outlined text-[44px]">photo_camera</span>
                            <span class="text-xs font-bold tracking-wider uppercase">Klik untuk ganti foto</span>
                            <span class="text-[10px] text-outline">Kosongkan jika tidak ingin mengubah</span>
                        </div>

                        <div id="cropEditArea" class="hidden absolute inset-0 overflow-hidden bg-surface-container-low touch-none select-none cursor-grab active:cursor-grabbing">
                            <img id="cropEditImage" src="" alt=""
                                 style="position:absolute; transform-origin:top left; will-change:transform;"
                                 draggable="false"/>
                            <div class="pointer-events-none absolute inset-0" style="
                                background-image:
                                    linear-gradient(rgba(255,255,255,.15) 1px, transparent 1px),
                                    linear-gradient(90deg, rgba(255,255,255,.15) 1px, transparent 1px);
                                background-size: 33.33% 33.33%;
                                border: 2px solid rgba(255,255,255,.4);
                            "></div>
                        </div>

                        <div id="cropEditToolbar"
                             class="hidden absolute bottom-0 left-0 right-0 flex items-center justify-between px-3 py-1.5"
                             style="background:rgba(49,25,8,.7); backdrop-filter:blur(4px);">
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="cropZoom('edit',-0.1)"
                                        class="w-7 h-7 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">zoom_out</span>
                                </button>
                                <button type="button" onclick="cropZoom('edit',0.1)"
                                        class="w-7 h-7 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">zoom_in</span>
                                </button>
                                <button type="button" onclick="cropReset('edit')"
                                        class="w-7 h-7 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">center_focus_strong</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-white/70 text-[10px]">Geser & zoom untuk posisikan foto</span>
                                <button type="button" onclick="clearCrop('edit')"
                                        class="w-7 h-7 rounded-full bg-red-500/70 hover:bg-red-500 text-white flex items-center justify-center transition-colors">
                                    <span class="material-symbols-outlined text-[15px]">close</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <input type="file" id="imageEdit" accept="image/*" class="hidden"
                           onchange="initCrop(this, 'edit')"/>
                    <input type="hidden" id="cropDataEdit" name="crop_image"/>
                    <input type="hidden" id="cropActiveEdit" name="crop_active" value="0"/>

                    <p class="text-[10px] text-on-surface-variant mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
                </div>
                <div class="md:col-span-2">
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Deskripsi <span class="text-error">*</span></label>
                    <textarea name="description" id="edit_description" required rows="4"
                              class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant resize-none"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-space-md pt-space-xs border-t border-surface-container">
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                        class="px-space-lg py-space-sm bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg rounded transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-space-xs px-space-lg py-space-sm text-on-primary font-label-lg text-label-lg rounded shadow-sm transition-colors"
                        style="background:#904c2e;">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Perbarui Produk</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ════════════════════════════════════════════════════════════
// CROPPER ENGINE — drag + zoom, output canvas crop ke hidden input
// ════════════════════════════════════════════════════════════
const cropState = {};

function initCrop(input, mode) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const imgEl    = document.getElementById('cropImage_' + mode) || document.getElementById('crop' + cap(mode) + 'Image');
        const areaEl   = document.getElementById('crop' + cap(mode) + 'Area');
        const holdEl   = document.getElementById('crop' + cap(mode) + 'Placeholder');
        const toolEl   = document.getElementById('crop' + cap(mode) + 'Toolbar');

        imgEl.onload = function() {
            const wrapEl = areaEl.parentElement;
            const boxW   = wrapEl.offsetWidth || 210;
            const boxH   = wrapEl.offsetHeight || 280;
            const nat    = { w: imgEl.naturalWidth, h: imgEl.naturalHeight };

            // Hitung scale agar gambar cover penuh bingkai
            const scale  = Math.max(boxW / nat.w, boxH / nat.h);
            const iW     = nat.w * scale;
            const iH     = nat.h * scale;
            const x      = (boxW - iW) / 2;
            const y      = (boxH - iH) / 2;

            cropState[mode] = { scale, x, y, iW, iH, boxW, boxH, nat, scale0: scale };
            clampPos(cropState[mode]);
            applyTransform(mode);
        };
        imgEl.src = e.target.result;

        areaEl.classList.remove('hidden');
        holdEl.classList.add('hidden');
        toolEl.classList.remove('hidden');
        toolEl.classList.add('flex');

        // Aktifkan hidden fields
        document.getElementById('cropActive' + cap(mode)).value = '1';

        // Drag
        setupDrag(areaEl, mode);
    };
    reader.readAsDataURL(file);
}

function cap(s) { return s.charAt(0).toUpperCase() + s.slice(1); }

function applyTransform(mode) {
    const s   = cropState[mode];
    const img = document.getElementById('crop' + cap(mode) + 'Image');
    if (!s || !img) return;
    img.style.width     = s.iW + 'px';
    img.style.height    = s.iH + 'px';
    img.style.left      = s.x + 'px';
    img.style.top       = s.y + 'px';
    img.style.transform = '';
}

function cropZoom(mode, delta) {
    const s = cropState[mode];
    if (!s) return;
    const minScale = s.scale0 * 0.8;
    const maxScale = s.scale0 * 4;
    const newScale = Math.min(maxScale, Math.max(minScale, s.scale + delta * s.scale));
    const ratio    = newScale / s.scale;
    s.iW   *= ratio;
    s.iH   *= ratio;
    // Zoom dari tengah bingkai
    const cx = s.boxW / 2;
    const cy = s.boxH / 2;
    s.x = cx - (cx - s.x) * ratio;
    s.y = cy - (cy - s.y) * ratio;
    s.scale = newScale;
    clampPos(s);
    applyTransform(mode);
}

function cropReset(mode) {
    const s = cropState[mode];
    if (!s) return;
    const scale = s.scale0;
    s.iW    = s.nat.w * scale;
    s.iH    = s.nat.h * scale;
    s.x     = (s.boxW - s.iW) / 2;
    s.y     = (s.boxH - s.iH) / 2;
    s.scale = scale;
    clampPos(s);
    applyTransform(mode);
}

function clampPos(s) {
    // Geser bebas dengan batas bingkai (bisa digeser-geser horizontal dan vertikal)
    if (s.iW <= s.boxW) {
        s.x = (s.boxW - s.iW) / 2;
    } else {
        if (s.x > 0) s.x = 0;
        if (s.x + s.iW < s.boxW) s.x = s.boxW - s.iW;
    }
    if (s.iH <= s.boxH) {
        s.y = (s.boxH - s.iH) / 2;
    } else {
        if (s.y > 0) s.y = 0;
        if (s.y + s.iH < s.boxH) s.y = s.boxH - s.iH;
    }
}

function clearCrop(mode) {
    document.getElementById('image' + cap(mode)).value = '';
    document.getElementById('crop' + cap(mode) + 'Area').classList.add('hidden');
    document.getElementById('crop' + cap(mode) + 'Toolbar').classList.remove('flex');
    document.getElementById('crop' + cap(mode) + 'Toolbar').classList.add('hidden');
    document.getElementById('crop' + cap(mode) + 'Placeholder').classList.remove('hidden');
    document.getElementById('cropData' + cap(mode)).value = '';
    document.getElementById('cropActive' + cap(mode)).value = '0';
    delete cropState[mode];
}

function setupDrag(areaEl, mode) {
    if (areaEl._dragSetup) return;
    areaEl._dragSetup = true;

    let dragging = false, startX, startY, startOX, startOY;

    // Touch
    areaEl.addEventListener('touchstart', e => {
        if (!cropState[mode]) return;
        dragging = true;
        startX = e.touches[0].clientX; startY = e.touches[0].clientY;
        startOX = cropState[mode].x; startOY = cropState[mode].y;
        e.preventDefault();
    }, { passive: false });
    areaEl.addEventListener('touchmove', e => {
        if (!dragging || !cropState[mode]) return;
        const dx = e.touches[0].clientX - startX;
        const dy = e.touches[0].clientY - startY;
        cropState[mode].x = startOX + dx;
        cropState[mode].y = startOY + dy;
        clampPos(cropState[mode]);
        applyTransform(mode);
        e.preventDefault();
    }, { passive: false });
    areaEl.addEventListener('touchend', () => { dragging = false; });

    // Mouse
    areaEl.addEventListener('mousedown', e => {
        if (!cropState[mode]) return;
        dragging = true;
        startX = e.clientX; startY = e.clientY;
        startOX = cropState[mode].x; startOY = cropState[mode].y;
    });
    document.addEventListener('mousemove', e => {
        if (!dragging || !cropState[mode]) return;
        cropState[mode].x = startOX + (e.clientX - startX);
        cropState[mode].y = startOY + (e.clientY - startY);
        clampPos(cropState[mode]);
        applyTransform(mode);
    });
    document.addEventListener('mouseup', () => { dragging = false; });

    // Pinch zoom
    let lastDist = 0;
    areaEl.addEventListener('touchstart', e => {
        if (e.touches.length === 2) {
            lastDist = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
        }
    }, { passive: false });
    areaEl.addEventListener('touchmove', e => {
        if (e.touches.length === 2) {
            const dist = Math.hypot(
                e.touches[0].clientX - e.touches[1].clientX,
                e.touches[0].clientY - e.touches[1].clientY
            );
            const delta = (dist - lastDist) / 200;
            cropZoom(mode, delta);
            lastDist = dist;
        }
    }, { passive: false });
}

// Render crop ke canvas sebelum submit
function renderCropToCanvas(mode) {
    const s   = cropState[mode];
    const img = document.getElementById('crop' + cap(mode) + 'Image');
    if (!s || !img || !img.src) return;

    const canvas  = document.createElement('canvas');
    const outW    = 800;   // lebar output crop (px)
    const outH    = Math.round(outW * (4/3)); // rasio 3:4
    canvas.width  = outW;
    canvas.height = outH;
    const ctx     = canvas.getContext('2d');

    // Hitung src rect (area yang terlihat di bingkai)
    const ratio   = s.nat.w / s.iW;
    const srcX    = (-s.x) * ratio;
    const srcY    = (-s.y) * ratio;
    const srcW    = s.boxW * ratio;
    const srcH    = s.boxH * ratio;

    ctx.drawImage(img, srcX, srcY, srcW, srcH, 0, 0, outW, outH);

    document.getElementById('cropData' + cap(mode)).value = canvas.toDataURL('image/jpeg', 0.92);
}

// ── Toast ────────────────────────────────────────────────────
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const icons = { success:{ sym:'check_circle', color:'#386a20' }, error:{ sym:'error', color:'#ba1a1a' } };
    const labels = { success:'Berhasil', error:'Gagal' };
    const toast = document.createElement('div');
    toast.className = `toast toast-${type} relative overflow-hidden`;
    toast.innerHTML = `
        <div class="toast-icon">
            <span class="material-symbols-outlined text-[20px]" style="color:${icons[type].color};font-variation-settings:'FILL' 1;">${icons[type].sym}</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-label-sm text-[10px] font-bold uppercase" style="color:${icons[type].color};">${labels[type]}</p>
            <p class="font-body-sm text-body-sm font-medium leading-tight mt-0.5" style="color:#1c1c18;">${message}</p>
        </div>
        <button onclick="dismissToast(this.closest('.toast'))"
                class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center hover:bg-surface-container transition-colors text-on-surface-variant" style="background:none;border:none;cursor:pointer;">
            <span class="material-symbols-outlined text-[16px]">close</span>
        </button>
        <div class="toast-progress"></div>`;
    container.appendChild(toast);
    requestAnimationFrame(() => requestAnimationFrame(() => toast.classList.add('show')));
    setTimeout(() => dismissToast(toast), 4000);
}
function dismissToast(toast) {
    if (!toast || toast.classList.contains('hide')) return;
    toast.classList.add('hide'); toast.classList.remove('show');
    setTimeout(() => toast?.remove(), 350);
}

@if(session('success'))
document.addEventListener('DOMContentLoaded', () => showToast(@json(session('success')), 'success'));
@endif
@if(session('error'))
document.addEventListener('DOMContentLoaded', () => showToast(@json(session('error')), 'error'));
@endif

// ── Edit Modal ──────────────────────────────────────────────────
function openEditModal(id, name, categoryId, price, stock, description, existingImage) {
    document.getElementById('formEdit').action = '/admin/products/' + id;
    document.getElementById('edit_name').value        = name;
    document.getElementById('edit_price').value       = price;
    document.getElementById('edit_stock').value       = stock;
    document.getElementById('edit_description').value = description;

    // Set category combobox in edit
    const itemEl = document.querySelector(`#edit_category_list .category-item[data-id="${categoryId}"]`);
    const catName = itemEl ? itemEl.querySelector('span').textContent.trim() : '';
    selectCategoryCombobox('edit', categoryId, catName);

    // Reset crop edit
    clearCrop('edit');

    // Tampilkan gambar existing di bingkai jika ada
    if (existingImage) {
        const areaEl = document.getElementById('cropEditArea');
        const holdEl = document.getElementById('cropEditPlaceholder');
        const toolEl = document.getElementById('cropEditToolbar');
        const imgEl  = document.getElementById('cropEditImage');

        imgEl.onload = function() {
            const wrapEl = areaEl.parentElement;
            const boxW   = wrapEl.offsetWidth  || 210;
            const boxH   = wrapEl.offsetHeight || 280; // rasio 3:4 persis katalog
            const nat    = { w: imgEl.naturalWidth, h: imgEl.naturalHeight };
            const scale  = Math.max(boxW / nat.w, boxH / nat.h);
            const iW     = nat.w * scale;
            const iH     = nat.h * scale;
            cropState['edit'] = {
                scale, x: (boxW-iW)/2, y: (boxH-iH)/2,
                iW, iH, boxW, boxH, nat, scale0: scale
            };
            clampPos(cropState['edit']);
            applyTransform('edit');
        };
        imgEl.src = existingImage;
        areaEl.classList.remove('hidden');
        holdEl.classList.add('hidden');
        toolEl.classList.remove('hidden');
        toolEl.classList.add('flex');
        setupDrag(areaEl, 'edit');
    }

    document.getElementById('modalEdit').classList.remove('hidden');
}

// ── Toolbar Filter Kategori Dropdown (Scrollable jika > 5) ────
function toggleToolbarCategoryDropdown() {
    const menu = document.getElementById('toolbarCategoryMenu');
    if (!menu) return;
    menu.classList.toggle('hidden');
}

function selectToolbarCategory(id, name) {
    document.getElementById('toolbarCategoryInput').value = id;
    document.getElementById('toolbarCategoryLabel').textContent = name;
    document.getElementById('toolbarCategoryMenu').classList.add('hidden');
    document.getElementById('toolbarCategoryInput').closest('form').submit();
}

// ── Combobox Kategori (Bisa diketik & scroll jika > 5) ────────
function openCategoryCombobox(mode) {
    const list = document.getElementById(mode + '_category_list');
    if (!list) return;
    list.classList.remove('hidden');
    const items = list.querySelectorAll('.category-item');
    items.forEach(el => el.classList.remove('hidden'));
    document.getElementById(mode + '_category_empty').classList.add('hidden');
}

function toggleCategoryCombobox(mode) {
    const list = document.getElementById(mode + '_category_list');
    if (!list) return;
    if (list.classList.contains('hidden')) {
        openCategoryCombobox(mode);
    } else {
        list.classList.add('hidden');
    }
}

function filterCategoryCombobox(mode) {
    const input = document.getElementById(mode + '_category_search');
    const query = input.value.trim().toLowerCase();
    const list  = document.getElementById(mode + '_category_list');
    if (!list) return;
    list.classList.remove('hidden');

    const items = list.querySelectorAll('.category-item');
    let visibleCount = 0;
    items.forEach(item => {
        const name = (item.getAttribute('data-name') || '').toLowerCase();
        if (!query || name.includes(query)) {
            item.classList.remove('hidden');
            visibleCount++;
        } else {
            item.classList.add('hidden');
        }
    });

    const emptyEl = document.getElementById(mode + '_category_empty');
    if (visibleCount === 0) {
        emptyEl.classList.remove('hidden');
    } else {
        emptyEl.classList.add('hidden');
    }
}

function selectCategoryCombobox(mode, id, name) {
    document.getElementById(mode + '_category_id').value = id;
    document.getElementById(mode + '_category_search').value = name;
    const list = document.getElementById(mode + '_category_list');
    if (list) list.classList.add('hidden');
}

// Tutup dropdown jika klik di luar
document.addEventListener('click', function(e) {
    // Toolbar category
    const tbWrap = document.getElementById('toolbarCategoryDropdownWrap');
    const tbMenu = document.getElementById('toolbarCategoryMenu');
    if (tbWrap && tbMenu && !tbWrap.contains(e.target)) {
        tbMenu.classList.add('hidden');
    }
    // Tambah combobox
    const cbTambah = document.getElementById('comboboxWrap_tambah');
    if (cbTambah && !cbTambah.contains(e.target)) {
        document.getElementById('tambah_category_list')?.classList.add('hidden');
    }
    // Edit combobox
    const cbEdit = document.getElementById('comboboxWrap_edit');
    if (cbEdit && !cbEdit.contains(e.target)) {
        document.getElementById('edit_category_list')?.classList.add('hidden');
    }
});

// ── Submit form — render crop ke canvas dulu ──────────────────
document.querySelector('#modalTambah form')?.addEventListener('submit', function() {
    if (cropState['tambah']) renderCropToCanvas('tambah');
});
document.getElementById('formEdit')?.addEventListener('submit', function() {
    if (cropState['edit']) renderCropToCanvas('edit');
});

// ── Close modal on backdrop click ────────────────────────────
['modalTambah', 'modalEdit', 'modalKategori'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});

// ── Auto-submit sort & kategori ─────────────────────────────
document.querySelectorAll('select[name="sort"], select[name="category"]').forEach(function(el) {
    el.addEventListener('change', function() {
        this.closest('form').submit();
    });
});

// ══ Kategori Modal (AJAX) ═══════════════════════════════════
let kategoriCurrentPage = 1;

function loadCategories(page) {
    if (page !== undefined) {
        kategoriCurrentPage = page;
    }
    const sort     = document.getElementById('kategoriSort').value;
    const listEl   = document.getElementById('kategoriList');
    const pagEl    = document.getElementById('kategoriPagination');
    const infoEl   = document.getElementById('kategoriInfo');

    listEl.innerHTML = '<div class="flex items-center justify-center py-8 text-outline"><span class="material-symbols-outlined text-[28px] animate-spin">progress_activity</span></div>';

    fetch(`{{ route('admin.categories.index') }}?sort=${sort}&page=${kategoriCurrentPage}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        infoEl.textContent = data.total + ' kategori';

        if (data.data.length === 0) {
            listEl.innerHTML = `
                <div class="flex flex-col items-center justify-center py-8 text-outline gap-2">
                    <span class="material-symbols-outlined text-[48px]">folder_open</span>
                    <span class="text-sm font-medium">Belum ada kategori</span>
                </div>`;
            pagEl.classList.add('hidden');
            return;
        }

        let html = '<div class="flex flex-col gap-1.5">';
        data.data.forEach(function(cat, index) {
            const num = (data.current_page - 1) * data.per_page + index + 1;
            const createdDate = new Date(cat.created_at).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' });
            html += `
                <div class="flex items-center gap-space-sm px-space-sm py-2.5 rounded-lg hover:bg-surface-container-low transition-colors group" id="kat-row-${cat.id}">
                    <span class="w-7 h-7 rounded-full bg-secondary-fixed text-on-secondary-fixed flex items-center justify-center text-xs font-bold flex-shrink-0">${num}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-on-surface truncate">${escapeHtml(cat.name)}</p>
                        <p class="text-[10px] text-outline">${createdDate}</p>
                    </div>
                    <button type="button" onclick="deleteCategory(${cat.id}, '${escapeHtml(cat.name)}')"
                            class="w-8 h-8 rounded-lg bg-surface-container hover:bg-error hover:text-on-error text-error flex items-center justify-center transition-colors flex-shrink-0 opacity-0 group-hover:opacity-100 sm:opacity-100"
                            title="Hapus Kategori">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </div>`;
        });
        html += '</div>';
        listEl.innerHTML = html;

        // Pagination
        if (data.last_page > 1) {
            pagEl.classList.remove('hidden');
            let pagHtml = '<div class="flex items-center justify-center gap-1 flex-wrap">';

            // Prev
            if (data.current_page > 1) {
                pagHtml += `<button onclick="loadCategories(${data.current_page - 1})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary transition-colors text-xs"><span class="material-symbols-outlined text-[16px]">chevron_left</span></button>`;
            }

            // Pages
            for (let p = 1; p <= data.last_page; p++) {
                if (p === data.current_page) {
                    pagHtml += `<span class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary text-on-primary text-xs font-bold shadow-sm">${p}</span>`;
                } else {
                    pagHtml += `<button onclick="loadCategories(${p})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary text-xs font-bold transition-colors">${p}</button>`;
                }
            }

            // Next
            if (data.current_page < data.last_page) {
                pagHtml += `<button onclick="loadCategories(${data.current_page + 1})" class="w-8 h-8 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary transition-colors text-xs"><span class="material-symbols-outlined text-[16px]">chevron_right</span></button>`;
            }

            pagHtml += '</div>';
            pagEl.innerHTML = pagHtml;
        } else {
            pagEl.classList.add('hidden');
        }
    })
    .catch(err => {
        listEl.innerHTML = '<div class="flex flex-col items-center justify-center py-8 text-error gap-2"><span class="material-symbols-outlined text-[36px]">error</span><span class="text-sm">Gagal memuat data</span></div>';
        console.error(err);
    });
}

function submitCategory(e) {
    e.preventDefault();
    const input   = document.getElementById('inputNamaKategori');
    const errEl   = document.getElementById('kategoriError');
    const btn     = document.getElementById('btnTambahKategori');
    const name    = input.value.trim();

    if (!name) { return; }

    errEl.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">progress_activity</span>';

    fetch(`{{ route('admin.categories.store') }}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ name: name })
    })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">add</span><span class="hidden sm:inline">Tambah</span>';

        if (!ok) {
            const msg = data.errors?.name?.[0] || data.message || 'Gagal menambahkan kategori.';
            errEl.textContent = msg;
            errEl.classList.remove('hidden');
            return;
        }

        input.value = '';
        showToast(data.message, 'success');
        kategoriCurrentPage = 1;
        loadCategories();

        // Juga refresh dropdown kategori di form produk
        refreshCategoryDropdowns(data.category);
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">add</span><span class="hidden sm:inline">Tambah</span>';
        errEl.textContent = 'Terjadi kesalahan jaringan.';
        errEl.classList.remove('hidden');
    });
}

function deleteCategory(id, name) {
    if (!confirm('Hapus kategori "' + name + '"?')) { return; }

    const row = document.getElementById('kat-row-' + id);
    if (row) {
        row.style.opacity = '0.5';
        row.style.pointerEvents = 'none';
    }

    fetch(`{{ url('admin/categories') }}/${id}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
    .then(({ ok, data }) => {
        if (!ok) {
            if (row) { row.style.opacity = '1'; row.style.pointerEvents = ''; }
            showToast(data.message || 'Gagal menghapus kategori.', 'error');
            return;
        }
        showToast(data.message, 'success');
        loadCategories();

        // Hapus dari dropdown kategori di form produk
        removeCategoryFromDropdowns(id);
    })
    .catch(() => {
        if (row) { row.style.opacity = '1'; row.style.pointerEvents = ''; }
        showToast('Terjadi kesalahan jaringan.', 'error');
    });
}

function refreshCategoryDropdowns(cat) {
    // 1. Toolbar menu
    const tbMenu = document.getElementById('toolbarCategoryMenu');
    if (tbMenu && !tbMenu.querySelector(`[onclick*="selectToolbarCategory(${cat.id},"]`)) {
        const item = document.createElement('div');
        item.className = 'px-3 py-2 text-sm text-on-surface hover:bg-surface-container cursor-pointer transition-colors flex items-center justify-between';
        item.setAttribute('onclick', `selectToolbarCategory(${cat.id}, '${escapeHtml(cat.name)}')`);
        item.innerHTML = `<span class="truncate">${escapeHtml(cat.name)}</span>`;
        tbMenu.appendChild(item);
    }

    // 2. Comboboxes (tambah & edit)
    ['tambah', 'edit'].forEach(mode => {
        const list = document.getElementById(mode + '_category_list');
        if (list && !list.querySelector(`.category-item[data-id="${cat.id}"]`)) {
            const emptyEl = document.getElementById(mode + '_category_empty');
            const item = document.createElement('div');
            item.className = 'px-3 py-2 text-sm text-on-surface hover:bg-surface-container cursor-pointer transition-colors flex items-center justify-between category-item';
            item.setAttribute('data-id', cat.id);
            item.setAttribute('data-name', cat.name.toLowerCase());
            item.setAttribute('onclick', `selectCategoryCombobox('${mode}', ${cat.id}, '${escapeHtml(cat.name)}')`);
            item.innerHTML = `<span>${escapeHtml(cat.name)}</span>`;
            list.insertBefore(item, emptyEl);
        }
    });
}

function removeCategoryFromDropdowns(id) {
    // 1. Toolbar menu
    const tbItem = document.querySelector(`#toolbarCategoryMenu [onclick*="selectToolbarCategory(${id},"]`);
    if (tbItem) tbItem.remove();

    // 2. Comboboxes
    ['tambah', 'edit'].forEach(mode => {
        const item = document.querySelector(`#${mode}_category_list .category-item[data-id="${id}"]`);
        if (item) item.remove();
        // Jika sedang terpilih, kosongkan
        const hiddenId = document.getElementById(`${mode}_category_id`);
        if (hiddenId && hiddenId.value == id) {
            hiddenId.value = '';
            document.getElementById(`${mode}_category_search`).value = '';
        }
    });
}

function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}
</script>
@endpush

