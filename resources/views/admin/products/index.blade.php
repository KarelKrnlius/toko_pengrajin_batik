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
        <div class="flex items-center gap-space-md flex-shrink-0">
            <button onclick="document.getElementById('modalTambah').classList.remove('hidden')"
                    class="inline-flex items-center gap-space-xs px-space-lg py-space-sm bg-surface-container-lowest hover:bg-surface text-primary font-label-lg text-label-lg rounded shadow-sm transition-colors">
                <span class="material-symbols-outlined text-[20px] text-secondary">add_circle</span>
                <span>Tambah Produk</span>
            </button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-space-xs px-space-lg py-space-sm bg-on-primary/10 hover:bg-on-primary/20 text-on-primary font-label-lg text-label-lg rounded transition-colors">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                    <span>Keluar</span>
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

        {{-- Filter Kategori --}}
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">category</span>
            <select name="category"
                    class="pl-9 pr-8 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer min-w-[180px]">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
        </div>

        {{-- Sort --}}
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">sort</span>
            <select name="sort"
                    class="pl-9 pr-8 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer min-w-[180px]">
                <option value="terbaru"     {{ $sort === 'terbaru'     ? 'selected' : '' }}>Terbaru</option>
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
                            <div class="flex items-center justify-center gap-space-xs">
                                <button type="button"
                                        onclick="openEditModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ $product->category_id }}, {{ $product->price }}, {{ $product->stock }}, {{ json_encode($product->description) }})"
                                        class="w-9 h-9 rounded-full bg-surface-container hover:bg-primary hover:text-on-primary text-primary flex items-center justify-center transition-colors"
                                        title="Edit Produk">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus produk {{ addslashes($product->name) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-9 h-9 rounded-full bg-surface-container hover:bg-error hover:text-on-error text-error flex items-center justify-center transition-colors"
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
                <div>
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Kategori <span class="text-error">*</span></label>
                    <select name="category_id" required
                            class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
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
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none border border-outline-variant"/>
                    <p class="text-xs text-on-surface-variant mt-1">jpg, png, webp — maks 2MB</p>
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
                <div>
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">Kategori <span class="text-error">*</span></label>
                    <select name="category_id" id="edit_category_id" required
                            class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary border border-outline-variant">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
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
                    <input type="file" name="image" accept="image/*"
                           class="w-full px-space-md py-space-sm bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none border border-outline-variant"/>
                    <p class="text-xs text-on-surface-variant mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
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
// ── Toast Notification System ────────────────────────────────
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');

    const icons = {
        success: { symbol: 'check_circle', color: '#386a20' },
        error:   { symbol: 'error',        color: '#ba1a1a' },
    };
    const labels = { success: 'Berhasil', error: 'Gagal' };

    const toast = document.createElement('div');
    toast.className = `toast toast-${type} relative overflow-hidden`;
    toast.innerHTML = `
        <div class="toast-icon">
            <span class="material-symbols-outlined text-[20px]" style="color:${icons[type].color};font-variation-settings:'FILL' 1;">${icons[type].symbol}</span>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-[11px] font-bold uppercase tracking-widest" style="color:${icons[type].color};">${labels[type]}</p>
            <p class="text-sm text-on-surface font-medium leading-tight mt-0.5">${message}</p>
        </div>
        <button onclick="dismissToast(this.closest('.toast'))"
                class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center hover:bg-surface-container transition-colors text-on-surface-variant">
            <span class="material-symbols-outlined text-[16px]">close</span>
        </button>
        <div class="toast-progress"></div>
    `;

    container.appendChild(toast);

    // Trigger animasi masuk
    requestAnimationFrame(() => {
        requestAnimationFrame(() => toast.classList.add('show'));
    });

    // Auto dismiss setelah 4 detik
    setTimeout(() => dismissToast(toast), 4000);
}

function dismissToast(toast) {
    if (!toast || toast.classList.contains('hide')) return;
    toast.classList.add('hide');
    toast.classList.remove('show');
    setTimeout(() => toast?.remove(), 350);
}

// ── Trigger dari session Laravel ────────────────────────────────
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showToast(@json(session('success')), 'success');
    });
@endif

@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showToast(@json(session('error')), 'error');
    });
@endif

// ── Edit Modal ──────────────────────────────────────────────────
function openEditModal(id, name, categoryId, price, stock, description) {
    document.getElementById('formEdit').action = '/admin/products/' + id;
    document.getElementById('edit_name').value        = name;
    document.getElementById('edit_category_id').value = categoryId;
    document.getElementById('edit_price').value       = price;
    document.getElementById('edit_stock').value       = stock;
    document.getElementById('edit_description').value = description;
    document.getElementById('modalEdit').classList.remove('hidden');
}

// Close modal on backdrop click
['modalTambah', 'modalEdit'].forEach(function(id) {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});

// Auto-submit sort & kategori
document.querySelectorAll('select[name="sort"], select[name="category"]').forEach(function(el) {
    el.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>
@endpush
