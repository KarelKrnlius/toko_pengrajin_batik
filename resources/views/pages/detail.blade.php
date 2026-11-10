@extends('layouts.app')
@section('title', $product->name)

@push('styles')
<style>
    /* ── Lightbox ── */
    #lightbox {
        display: none;
        position: fixed; inset: 0; z-index: 9999;
        background: rgba(49,25,8,.92);
        backdrop-filter: blur(6px);
        align-items: center; justify-content: center;
        flex-direction: column;
    }
    #lightbox.open { display: flex; }
    #lb-img-wrap {
        position: relative; overflow: hidden;
        width: min(90vw, 500px);
        height: min(90vh, 667px); /* 3:4 */
        cursor: grab;
        touch-action: none;
        user-select: none;
    }
    #lb-img-wrap.dragging { cursor: grabbing; }
    #lb-img {
        position: absolute; top: 0; left: 0;
        width: 100%; height: 100%;
        object-fit: contain;
        transform-origin: center center;
        will-change: transform;
        transition: none;
        pointer-events: none;
    }
    /* zoom controls */
    #lb-controls {
        display: flex; align-items: center; gap: 12px;
        margin-top: 14px;
    }
    .lb-btn {
        width: 40px; height: 40px; border-radius: 50%;
        background: rgba(255,255,255,.15); border: none; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        color: #fff; transition: background .15s;
    }
    .lb-btn:hover { background: rgba(255,255,255,.3); }
    #lb-zoom-label {
        color: rgba(255,255,255,.7); font-size: 12px; font-weight: 600;
        min-width: 44px; text-align: center;
    }
    /* klik gambar utama */
    .clickable-img { cursor: zoom-in; }
</style>
@endpush

@section('content')

{{-- Main 2-Column --}}
<section class="w-full py-6 sm:py-8">
    <div class="max-w-[1200px] mx-auto px-margin-mobile lg:px-margin">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">

            {{-- ── Kolom Kiri: Gambar (Ukuran lebih ringkas & proporsional) ── --}}
            <div class="lg:col-span-5 flex flex-col gap-4 w-full max-w-sm mx-auto lg:max-w-none">
                <div class="relative bg-surface-container-lowest rounded-xl p-3 shadow-sm border border-outline-variant/50 overflow-hidden max-w-[360px] mx-auto w-full">

                    {{-- Foto utama — klik untuk zoom --}}
                    <div class="relative rounded-lg overflow-hidden bg-surface-container w-full"
                         style="aspect-ratio:3/4; max-width:340px; margin:0 auto;">
                        @if($product->image)
                            <img alt="{{ $product->name }}"
                                 class="w-full h-full object-cover clickable-img transition-transform duration-300 hover:scale-105"
                                 id="mainProductImage"
                                 src="{{ Storage::url($product->image) }}"
                                 onclick="openLightbox('{{ Storage::url($product->image) }}')"/>
                            {{-- Hint zoom --}}
                            <div class="absolute bottom-2 right-2 bg-primary/80 backdrop-blur-sm rounded-full px-2 py-0.5 flex items-center gap-1 pointer-events-none">
                                <span class="material-symbols-outlined text-on-primary text-[13px]">zoom_in</span>
                                <span class="text-on-primary text-[9px] font-bold">Klik zoom</span>
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-surface-container">
                                <span class="material-symbols-outlined text-[70px] text-outline">draw</span>
                            </div>
                        @endif
                        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 items-start pointer-events-none">
                            <span class="px-2 py-0.5 bg-surface/95 backdrop-blur-md rounded-full font-label-sm text-[9px] text-primary tracking-wider shadow-sm flex items-center gap-1 font-bold">
                                <span class="material-symbols-outlined text-[11px] text-secondary">verified</span>
                                {{ strtoupper($product->category->name ?? 'BATIK') }} ASLI
                            </span>
                        </div>
                    </div>

                    {{-- Info Stok & Sertifikasi --}}
                    <div class="mt-2.5 flex items-center justify-around gap-2 bg-surface-container-low px-3 py-1.5 rounded-lg">
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-secondary text-[16px]">history_edu</span>
                            <div>
                                <span class="block text-[8px] text-outline uppercase font-semibold">Stok</span>
                                <span class="text-[11px] text-primary font-bold">{{ $product->stock }} pcs</span>
                            </div>
                        </div>
                        <div class="h-5 w-px bg-outline-variant/60"></div>
                        <div class="flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-secondary text-[16px]">award_star</span>
                            <div>
                                <span class="block text-[8px] text-outline uppercase font-semibold">Sertifikasi</span>
                                <span class="text-[11px] text-primary font-bold">Batik Mark</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Kolom Kanan: Info & Pembelian (Dikecilkan dan lebih padat) ── --}}
            <div class="lg:col-span-7 flex flex-col gap-4">
                <div class="bg-surface-container-lowest p-4 sm:p-5 rounded-xl shadow-sm border border-outline-variant/50 flex flex-col gap-3.5">

                    <div class="flex flex-col gap-1">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] tracking-[0.2em] text-secondary font-bold uppercase">{{ $product->category->name ?? 'BATIK' }}</span>
                        </div>
                        <h1 class="font-headline-sm text-lg sm:text-xl font-bold text-primary leading-snug">{{ $product->name }}</h1>
                    </div>

                    <div class="flex flex-col bg-surface-container-low p-3 rounded-lg gap-1">
                        <div class="flex items-baseline justify-between">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-outline tracking-wider uppercase font-semibold">Nilai Investasi</span>
                                <span class="font-serif text-lg sm:text-xl text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <span class="px-2 py-0.5 bg-surface-container-highest text-on-surface-variant text-[10px] rounded font-medium">Harga Kolektor</span>
                        </div>
                        @if($product->stock > 0 && $product->stock <= 10)
                        <div class="flex items-center gap-1 pt-0.5 text-secondary text-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                            <span class="font-bold">Hanya {{ $product->stock }} tersisa</span>
                        </div>
                        @elseif($product->stock == 0)
                        <div class="flex items-center gap-1 pt-0.5 text-error text-xs">
                            <span class="material-symbols-outlined text-[14px]">cancel</span>
                            <span class="font-bold">Stok Habis</span>
                        </div>
                        @endif
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] text-primary font-bold tracking-wider uppercase">Deskripsi Produk</span>
                        <p class="text-xs text-on-surface-variant leading-relaxed">{{ $product->description }}</p>
                    </div>

                    {{-- Notifikasi Flash Pesan --}}
                    @if(session('success'))
                    <div class="p-3.5 bg-green-50 border border-green-200 rounded-xl text-green-900 text-xs flex items-center gap-2.5 shadow-sm">
                        <span class="material-symbols-outlined text-[20px] text-green-600 flex-shrink-0">check_circle</span>
                        <div class="flex-1 font-medium">{{ session('success') }}</div>
                        <a href="{{ route('cart.index') }}" class="font-bold underline text-green-700 hover:text-green-900 flex-shrink-0">Lihat Keranjang</a>
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="p-3.5 bg-red-50 border border-red-200 rounded-xl text-red-900 text-xs flex items-center gap-2.5 shadow-sm">
                        <span class="material-symbols-outlined text-[20px] text-red-600 flex-shrink-0">error</span>
                        <div class="flex-1 font-medium">{{ session('error') }}</div>
                    </div>
                    @endif

                    <div class="flex flex-col gap-space-sm pt-space-xs">
                        @if($product->stock > 0)
                            @auth
                            @if(!Auth::user()->isProfileComplete())
                            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-amber-900 text-xs flex items-center justify-between gap-2">
                                <div class="flex items-center gap-1.5 min-w-0">
                                    <span class="material-symbols-outlined text-[18px] text-amber-600 flex-shrink-0">info</span>
                                    <span class="truncate">Profil belum lengkap (nomor HP & alamat).</span>
                                </div>
                                <a href="{{ route('profile.show') }}" class="font-bold underline text-secondary hover:text-primary whitespace-nowrap flex-shrink-0">Lengkapi</a>
                            </div>
                            <a href="{{ route('profile.show') }}"
                               class="w-full py-space-md px-space-lg bg-amber-600 hover:bg-amber-700 text-white font-label-lg text-label-lg rounded tracking-widest uppercase transition-all shadow-md flex items-center justify-center gap-space-sm group">
                                <span class="material-symbols-outlined text-[20px]">badge</span>
                                <span>Lengkapi Profil untuk Membeli</span>
                            </a>
                            @else
                            {{-- Tombol Beli: Buka Modal Input Jumlah untuk WhatsApp --}}
                            <button type="button" onclick="openBuyModal()"
                               class="w-full py-space-md px-space-lg bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded tracking-widest uppercase transition-all shadow-md flex items-center justify-center gap-space-sm group">
                                <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">shopping_bag</span>
                                <span>Beli Sekarang</span>
                            </button>
                            @endif
                            @else
                            <a href="{{ route('login') }}"
                               class="w-full py-space-md px-space-lg bg-primary text-on-primary font-label-lg text-label-lg rounded tracking-widest uppercase transition-all shadow-md flex items-center justify-center gap-space-sm">
                                <span class="material-symbols-outlined text-[20px]">login</span>
                                <span>Login untuk Membeli</span>
                            </a>
                            @endauth
                        @else
                        <button disabled
                                class="w-full py-space-md px-space-lg bg-surface-container-high text-on-surface-variant font-label-lg text-label-lg rounded tracking-widest uppercase cursor-not-allowed opacity-60 flex items-center justify-center gap-space-sm">
                            <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                            <span>Stok Habis</span>
                        </button>
                        @endif
                        <a href="{{ route('catalog') }}"
                           class="w-full py-space-md px-space-lg bg-surface-container-high hover:bg-surface-container-highest text-primary font-label-lg text-label-lg rounded tracking-widest uppercase transition-all flex items-center justify-center gap-space-sm">
                            <span class="material-symbols-outlined text-[20px] text-secondary">arrow_back</span>
                            <span>Kembali ke Katalog</span>
                        </a>

                        {{-- Tombol Favorit & Keranjang (hanya user) --}}
                        @auth
                        <div class="flex gap-2">
                            <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="flex-1">
                                @csrf
                                @php $isFav = \App\Models\Favorite::where('user_id', Auth::id())->where('product_id', $product->id)->exists(); @endphp
                                <button type="submit"
                                        class="w-full py-2.5 px-4 {{ $isFav ? 'bg-error text-on-error' : 'bg-surface-container hover:bg-surface-container-high text-error' }} font-bold text-xs tracking-wider uppercase rounded-lg transition-colors flex items-center justify-center gap-1.5 border border-outline-variant">
                                    <span class="material-symbols-outlined text-[18px]" style="{{ $isFav ? 'font-variation-settings: \'FILL\' 1;' : '' }}">favorite</span>
                                    <span>{{ $isFav ? 'Hapus Favorit' : 'Tambah Favorit' }}</span>
                                </button>
                            </form>
                            @if($product->stock > 0)
                            {{-- Tombol Buka Modal Keranjang --}}
                            <button type="button" onclick="openCartModal()"
                                    class="flex-1 py-2.5 px-4 bg-secondary-container text-on-secondary-container hover:brightness-95 font-bold text-xs tracking-wider uppercase rounded-lg transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
                                <span>Keranjang</span>
                            </button>
                            @endif
                        </div>
                        @endauth
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-sm pt-space-sm bg-surface-container-low p-space-sm rounded-lg text-on-surface-variant font-label-sm text-label-sm">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
                            <span>Garansi Kemurnian Kain 100%</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[18px]">local_shipping</span>
                            <span>Pengiriman Eksklusif &amp; Aman</span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-space-xs pt-space-sm">
                        @foreach ([
                            ['acc-rawat',    'dry_cleaning',  'Panduan Perawatan Kain',      '<ul class="list-disc pl-4 space-y-1"><li>Cuci menggunakan sari buah lerak alami atau sabun khusus batik.</li><li>Jangan diperas kasar; angin-anginkan di tempat teduh.</li><li>Setrika dengan alas kain pelindung suhu sedang rendah.</li><li>Simpan bersama akar wangi alami.</li></ul>'],
                            ['acc-sertif',   'verified_user', 'Garansi Keaslian Batik Mark', 'Karya ini menyandang sertifikat resmi <strong>Batik Mark Indonesia</strong> bertaraf Kementerian Perindustrian RI, dilengkapi sertifikat fisik bertanda tangan Maestro Pembatik.'],
                        ] as [$id, $icon, $title, $body])
                        <div class="bg-surface-container-low rounded-lg overflow-hidden">
                            <button class="w-full px-space-md py-space-sm flex items-center justify-between text-left font-label-lg text-label-lg text-primary hover:bg-surface-container transition-colors"
                                    onclick="toggleAccordion('{{ $id }}')" type="button">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[20px]">{{ $icon }}</span>
                                    {{ $title }}
                                </span>
                                <span class="material-symbols-outlined text-[18px] text-outline transition-transform duration-300" id="icon-{{ $id }}">expand_more</span>
                            </button>
                            <div class="px-space-md pb-space-md text-on-surface-variant font-body-sm text-body-sm leading-relaxed hidden" id="{{ $id }}">
                                {!! $body !!}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Koleksi Sejenis — ukuran sama dengan katalog, 5 kolom agar lebih kecil --}}
@if($related->isNotEmpty())
<section class="w-full py-space-xl">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex flex-col gap-space-md">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-sm">
            <div class="flex flex-col gap-space-xs">
                <span class="font-label-sm text-label-sm text-secondary uppercase tracking-[0.2em] font-bold">KOLEKSI SEJENIS</span>
                <h2 class="font-headline-md text-headline-md text-primary">Koleksi Pelengkap Senada</h2>
            </div>
            <a href="{{ route('catalog', ['category' => $product->category_id]) }}"
               class="inline-flex items-center gap-1 font-label-lg text-label-lg text-secondary hover:text-primary transition-colors text-sm">
                <span>Lihat Seluruh Koleksi {{ $product->category->name ?? '' }}</span>
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>

        {{-- 5 kolom agar tiap kartu lebih kecil --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
            @foreach($related as $rel)
            <article class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all group flex flex-col">
                <div class="relative w-full overflow-hidden bg-surface-container" style="aspect-ratio:3/4;">
                    @if($rel->image)
                        <img src="{{ Storage::url($rel->image) }}" alt="{{ $rel->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center bg-surface-container gap-1">
                            <span class="material-symbols-outlined text-[32px] text-outline">draw</span>
                            <span class="text-[9px] text-outline font-bold uppercase">Tidak Ada Foto</span>
                        </div>
                    @endif
                    <span class="absolute top-1.5 left-1.5 bg-primary text-on-primary text-[8px] px-1.5 py-0.5 rounded-full font-bold shadow-sm uppercase tracking-wider">
                        {{ Str::limit($rel->category->name ?? 'Batik', 8) }}
                    </span>
                </div>
                <div class="p-2 flex flex-col flex-1 justify-between gap-1">
                    <div>
                        <h3 class="text-[11px] font-bold text-primary group-hover:text-secondary transition-colors line-clamp-1 leading-tight">{{ $rel->name }}</h3>
                        <p class="text-[10px] text-on-surface-variant line-clamp-1 mt-0.5">{{ Str::limit($rel->description, 40) }}</p>
                    </div>
                    <div class="pt-1.5 border-t border-surface-container mt-1">
                        <span class="block text-[11px] font-bold text-primary whitespace-nowrap mb-1">
                            Rp {{ number_format($rel->price, 0, ',', '.') }}
                        </span>
                        <a href="{{ route('product.show', $rel->id) }}"
                           class="block w-full py-1 rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary text-center text-[10px] font-bold transition-colors">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── MODAL MASUKKAN KE KERANJANG ── --}}
@auth
@if($product->stock > 0)
<div id="cartModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 shadow-2xl border border-outline-variant/60 flex flex-col gap-4 transform transition-all scale-95 duration-200" id="cartModalBox">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/40">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[24px]">add_shopping_cart</span>
                <h3 class="font-headline-sm text-base font-bold text-primary">Tambah ke Keranjang</h3>
            </div>
            <button type="button" onclick="closeCartModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-outline hover:text-primary hover:bg-surface-container transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-14 h-14 object-cover rounded-lg flex-shrink-0"/>
            @endif
            <div class="min-w-0 flex-1">
                <span class="text-[10px] text-secondary font-bold uppercase tracking-wider block">{{ $product->category->name ?? 'BATIK' }}</span>
                <h4 class="text-sm font-bold text-primary truncate">{{ $product->name }}</h4>
                <span class="text-xs font-bold text-secondary">Rp {{ number_format($product->price, 0, ',', '.') }} <span class="text-[10px] font-normal text-outline">/ pcs</span></span>
            </div>
        </div>

        <form action="{{ route('cart.store', $product->id) }}" method="POST" id="modalCartForm" class="flex flex-col gap-4">
            @csrf
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-primary uppercase tracking-wider">Masukkan Jumlah Barang:</label>
                    <span class="text-xs text-outline">Stok: <strong class="text-primary font-bold">{{ $product->stock }}</strong> pcs</span>
                </div>
                <div class="flex items-center justify-between gap-3 bg-surface-container-low p-2 rounded-xl border border-outline-variant/50">
                    <div class="flex items-center bg-surface-container-highest rounded-lg p-1 border border-outline-variant/60">
                        <button type="button" onclick="adjustModalQty('cart', -1)" class="w-9 h-9 rounded-md bg-surface flex items-center justify-center text-primary hover:bg-surface-container transition shadow-sm font-bold">
                            <span class="material-symbols-outlined text-[18px]">remove</span>
                        </button>
                        <input type="number" name="quantity" id="modalCartQtyInput" value="1" min="1" max="{{ $product->stock }}"
                               class="w-14 text-center font-bold text-sm bg-transparent border-none text-primary focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                               oninput="validateModalQty('cart', this)">
                        <button type="button" onclick="adjustModalQty('cart', 1)" class="w-9 h-9 rounded-md bg-surface flex items-center justify-center text-primary hover:bg-surface-container transition shadow-sm font-bold">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                        </button>
                    </div>
                    <div class="text-right pr-2">
                        <span class="block text-[10px] text-outline uppercase font-semibold">Subtotal</span>
                        <span class="text-sm font-bold text-secondary" id="modalCartSubtotal">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex gap-2 pt-2">
                <button type="button" onclick="closeCartModal()" class="flex-1 py-2.5 px-4 rounded-xl border border-outline-variant hover:bg-surface-container text-on-surface-variant font-bold text-xs uppercase tracking-wider transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl bg-secondary-container hover:brightness-95 text-on-secondary-container font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">done</span>
                    <span>Kirim ke Keranjang</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endauth

{{-- ── MODAL BELI SEKARANG (WHATSAPP) ── --}}
@auth
@if($product->stock > 0)
<div id="buyModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 shadow-2xl border border-outline-variant/60 flex flex-col gap-4 transform transition-all scale-95 duration-200" id="buyModalBox">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/40">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[24px]">shopping_bag</span>
                <h3 class="font-headline-sm text-base font-bold text-primary">Konfirmasi Pembelian via WhatsApp</h3>
            </div>
            <button type="button" onclick="closeBuyModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-outline hover:text-primary hover:bg-surface-container transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
            @if($product->image)
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-14 h-14 object-cover rounded-lg flex-shrink-0"/>
            @endif
            <div class="min-w-0 flex-1">
                <span class="text-[10px] text-secondary font-bold uppercase tracking-wider block">{{ $product->category->name ?? 'BATIK' }}</span>
                <h4 class="text-sm font-bold text-primary truncate">{{ $product->name }}</h4>
                <span class="text-xs font-bold text-secondary">Rp {{ number_format($product->price, 0, ',', '.') }} <span class="text-[10px] font-normal text-outline">/ pcs</span></span>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-primary uppercase tracking-wider">Ingin Beli Berapa Barang?</label>
                    <span class="text-xs text-outline">Stok: <strong class="text-primary font-bold">{{ $product->stock }}</strong> pcs</span>
                </div>
                <div class="flex items-center justify-between gap-3 bg-surface-container-low p-2 rounded-xl border border-outline-variant/50">
                    <div class="flex items-center bg-surface-container-highest rounded-lg p-1 border border-outline-variant/60">
                        <button type="button" onclick="adjustModalQty('buy', -1)" class="w-9 h-9 rounded-md bg-surface flex items-center justify-center text-primary hover:bg-surface-container transition shadow-sm font-bold">
                            <span class="material-symbols-outlined text-[18px]">remove</span>
                        </button>
                        <input type="number" id="modalBuyQtyInput" value="1" min="1" max="{{ $product->stock }}"
                               class="w-14 text-center font-bold text-sm bg-transparent border-none text-primary focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                               oninput="validateModalQty('buy', this)">
                        <button type="button" onclick="adjustModalQty('buy', 1)" class="w-9 h-9 rounded-md bg-surface flex items-center justify-center text-primary hover:bg-surface-container transition shadow-sm font-bold">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                        </button>
                    </div>
                    <div class="text-right pr-2">
                        <span class="block text-[10px] text-outline uppercase font-semibold">Total Pembayaran</span>
                        <span class="text-base font-bold text-secondary" id="modalBuySubtotal">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
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
                <button type="button" onclick="closeBuyModal()" class="flex-1 py-2.5 px-4 rounded-xl border border-outline-variant hover:bg-surface-container text-on-surface-variant font-bold text-xs uppercase tracking-wider transition">
                    Batal
                </button>
                <button type="button" onclick="submitBuyToWhatsApp()" class="flex-1 py-2.5 px-4 rounded-xl bg-primary hover:bg-secondary text-on-primary font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-1.5 shadow-md">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    <span>Bayar ke WA</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endauth

{{-- ── LIGHTBOX ZOOM ── --}}
<div id="lightbox" onclick="closeLightboxOutside(event)">
    <div id="lb-img-wrap">
        <img id="lb-img" src="" alt="Zoom produk" draggable="false"/>
    </div>
    <div id="lb-controls">
        <button class="lb-btn" onclick="lbZoom(-0.25)" title="Zoom Out">
            <span class="material-symbols-outlined text-[20px]">zoom_out</span>
        </button>
        <span id="lb-zoom-label">100%</span>
        <button class="lb-btn" onclick="lbZoom(0.25)" title="Zoom In">
            <span class="material-symbols-outlined text-[20px]">zoom_in</span>
        </button>
        <button class="lb-btn" onclick="lbReset()" title="Reset">
            <span class="material-symbols-outlined text-[20px]">center_focus_strong</span>
        </button>
        <button class="lb-btn" onclick="closeLightbox()" title="Tutup" style="background:rgba(186,26,26,.5);">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>
    <p style="color:rgba(255,255,255,.5); font-size:11px; margin-top:8px;">
        Geser untuk memindahkan • Scroll/pinch untuk zoom
    </p>
</div>

@endsection

@push('scripts')
<script>
// ── Accordion ─────────────────────────────────────────────────
function toggleAccordion(id) {
    const body = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    const isHidden = body.classList.contains('hidden');
    body.classList.toggle('hidden', !isHidden);
    icon.style.transform = isHidden ? 'rotate(180deg)' : '';
}

// ── Lightbox ──────────────────────────────────────────────────
let lbScale = 1, lbX = 0, lbY = 0;
let lbDragging = false, lbStartX, lbStartY, lbOX, lbOY;

function openLightbox(src) {
    const lb  = document.getElementById('lightbox');
    const img = document.getElementById('lb-img');
    img.src   = src;
    lbScale = 1; lbX = 0; lbY = 0;
    applyLbTransform();
    lb.classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}
function closeLightboxOutside(e) {
    if (e.target === document.getElementById('lightbox')) closeLightbox();
}
function lbZoom(delta) {
    lbScale = Math.min(5, Math.max(1, lbScale + delta));
    if (lbScale === 1) { lbX = 0; lbY = 0; }
    applyLbTransform();
}
function lbReset() {
    lbScale = 1; lbX = 0; lbY = 0;
    applyLbTransform();
}
function applyLbTransform() {
    document.getElementById('lb-img').style.transform =
        `translate(${lbX}px, ${lbY}px) scale(${lbScale})`;
    document.getElementById('lb-zoom-label').textContent =
        Math.round(lbScale * 100) + '%';
}

// Mouse drag
const wrap = document.getElementById('lb-img-wrap');
wrap.addEventListener('mousedown', e => {
    if (lbScale <= 1) return;
    lbDragging = true; wrap.classList.add('dragging');
    lbStartX = e.clientX; lbStartY = e.clientY;
    lbOX = lbX; lbOY = lbY;
});
document.addEventListener('mousemove', e => {
    if (!lbDragging) return;
    lbX = lbOX + (e.clientX - lbStartX);
    lbY = lbOY + (e.clientY - lbStartY);
    applyLbTransform();
});
document.addEventListener('mouseup', () => { lbDragging = false; wrap.classList.remove('dragging'); });

// Mouse wheel zoom
wrap.addEventListener('wheel', e => {
    e.preventDefault();
    lbZoom(e.deltaY < 0 ? 0.2 : -0.2);
}, { passive: false });

// Touch pinch zoom + drag
let lastTouchDist = 0;
wrap.addEventListener('touchstart', e => {
    if (e.touches.length === 2) {
        lastTouchDist = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );
    } else if (e.touches.length === 1 && lbScale > 1) {
        lbDragging = true;
        lbStartX = e.touches[0].clientX; lbStartY = e.touches[0].clientY;
        lbOX = lbX; lbOY = lbY;
    }
}, { passive: false });
wrap.addEventListener('touchmove', e => {
    e.preventDefault();
    if (e.touches.length === 2) {
        const dist = Math.hypot(
            e.touches[0].clientX - e.touches[1].clientX,
            e.touches[0].clientY - e.touches[1].clientY
        );
        lbZoom((dist - lastTouchDist) / 200);
        lastTouchDist = dist;
    } else if (lbDragging && e.touches.length === 1) {
        lbX = lbOX + (e.touches[0].clientX - lbStartX);
        lbY = lbOY + (e.touches[0].clientY - lbStartY);
        applyLbTransform();
    }
}, { passive: false });
wrap.addEventListener('touchend', () => { lbDragging = false; });

// Escape key
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeLightbox();
        closeCartModal();
        closeBuyModal();
    }
});

// Modal Logic
const maxStock = {{ (int) $product->stock }};
const unitPrice = {{ (int) $product->price }};

function formatRupiah(num) {
    return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function openCartModal() {
    const modal = document.getElementById('cartModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    const input = document.getElementById('modalCartQtyInput');
    if (input) {
        input.value = 1;
        updateModalSubtotal('cart', 1);
    }
}

function closeCartModal() {
    const modal = document.getElementById('cartModal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function openBuyModal() {
    const modal = document.getElementById('buyModal');
    if (!modal) return;
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    const input = document.getElementById('modalBuyQtyInput');
    if (input) {
        input.value = 1;
        updateModalSubtotal('buy', 1);
    }
}

function closeBuyModal() {
    const modal = document.getElementById('buyModal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function updateModalSubtotal(type, qty) {
    const subtotal = qty * unitPrice;
    if (type === 'cart') {
        const el = document.getElementById('modalCartSubtotal');
        if (el) el.innerText = formatRupiah(subtotal);
    } else {
        const el = document.getElementById('modalBuySubtotal');
        if (el) el.innerText = formatRupiah(subtotal);
    }
}

function adjustModalQty(type, delta) {
    const inputId = type === 'cart' ? 'modalCartQtyInput' : 'modalBuyQtyInput';
    const input = document.getElementById(inputId);
    if (!input) return;
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (val > maxStock) val = maxStock;
    input.value = val;
    updateModalSubtotal(type, val);
}

function validateModalQty(type, input) {
    let val = parseInt(input.value);
    if (isNaN(val) || val < 1) {
        val = 1;
    } else if (val > maxStock) {
        val = maxStock;
        input.value = maxStock;
    }
    updateModalSubtotal(type, val);
}

function submitBuyToWhatsApp() {
    const input = document.getElementById('modalBuyQtyInput');
    let qty = input ? (parseInt(input.value) || 1) : 1;
    if (qty < 1) qty = 1;
    if (qty > maxStock) qty = maxStock;

    const subtotal = qty * unitPrice;
    const subtotalFormatted = formatRupiah(subtotal);
    const unitPriceFormatted = formatRupiah(unitPrice);

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

    const productName = @json($product->name);
    const categoryName = @json($product->category->name ?? 'Batik');

    let msg = `Halo Juragan Batik, saya ingin memesan produk berikut:\n\n` +
        `• Produk: ${productName}\n` +
        `• Kategori: ${categoryName}\n` +
        `• Harga Satuan: ${unitPriceFormatted}\n` +
        `• Jumlah Beli: ${qty} pcs\n` +
        `• Total Pembayaran: ${subtotalFormatted}\n\n` +
        `Data Pemesan:\n` +
        `• Nama: ${userName}\n` +
        `• Email: ${userEmail}\n` +
        `• No. Telepon: ${userPhone}\n` +
        `• Alamat Pengiriman: ${userAddress}`;

    const url = 'https://wa.me/6281382958012?text=' + encodeURIComponent(msg);
    closeBuyModal();
    window.open(url, '_blank');
}
</script>
@endpush
