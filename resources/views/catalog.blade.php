@extends('layouts.app')
@section('title', 'Katalog Batik Nusantara')

@section('content')

<div class="relative w-full overflow-hidden">
<div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin pt-space-lg pb-space-xl">

    {{-- Breadcrumb & Meta --}}
    <div class="flex flex-wrap items-center justify-between gap-space-sm mb-space-md">
        <nav class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
            <span class="text-primary font-semibold">Katalog Wastra Nusantara</span>
        </nav>
        <div class="flex items-center gap-space-xs bg-surface-container px-space-md py-space-xs rounded-full">
            <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
            <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest">Kurasi Resmi Dalem Keraton Surakarta</span>
        </div>
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
                <div class="w-12 h-12 rounded-lg bg-primary flex items-center justify-center text-on-primary">
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

        {{-- Decorative Divider --}}
        <div class="relative flex items-center justify-center my-space-lg">
            <div class="w-full h-px bg-surface-container-highest"></div>
            <div class="absolute px-space-md bg-background text-secondary flex items-center gap-space-xs">
                <span class="text-[10px] tracking-widest text-outline uppercase font-label-sm">SANCTUARY WASTRA</span>
                <span class="material-symbols-outlined text-[16px] text-secondary">diamond</span>
                <span class="text-[10px] tracking-widest text-outline uppercase font-label-sm">EST. 1928</span>
            </div>
        </div>

        {{-- Search & Sort Toolbar --}}
        <form method="GET" action="{{ route('catalog') }}"
              class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-space-md bg-surface-container-low p-space-md rounded-xl shadow-sm">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-space-md top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                <input name="search" value="{{ request('search') }}"
                       class="w-full pl-11 pr-space-md py-2.5 bg-surface-container-lowest text-on-surface font-body-sm text-body-sm rounded-lg focus:outline-none focus:ring-1 focus:ring-secondary text-primary placeholder:text-outline shadow-sm"
                       placeholder="Cari motif (Parang, Kawung, Sekar Jagad, Truntum, Megamendung)..."/>
            </div>
            <div class="flex items-center gap-space-md">
                <button type="submit"
                        class="px-space-lg py-2.5 bg-primary text-on-primary font-label-lg text-label-lg rounded-lg hover:bg-secondary transition-colors flex items-center gap-space-xs">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                    <span>Cari</span>
                </button>
                @if(request()->hasAny(['search','category']))
                    <a href="{{ route('catalog') }}"
                       class="px-space-md py-2.5 bg-surface-container text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container-high transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Main 2-Column Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-start">

        {{-- ── SIDEBAR FILTER ── --}}
        <aside class="lg:col-span-3 flex flex-col gap-space-lg bg-surface-container-low p-space-lg rounded-xl shadow-sm">
            <div class="flex items-center justify-between pb-space-sm border-b border-surface-container-highest">
                <div class="flex items-center gap-space-xs text-primary font-title-lg text-title-lg">
                    <span class="material-symbols-outlined text-[20px] text-secondary">filter_list</span>
                    <span>Filter Kurasi</span>
                </div>
                <a href="{{ route('catalog') }}" class="text-secondary font-label-sm text-label-sm hover:underline tracking-wider uppercase font-bold">Reset</a>
            </div>

            <form method="GET" action="{{ route('catalog') }}" id="filterForm">
                @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                @endif

                {{-- Kategori --}}
                <div class="flex flex-col gap-space-sm mb-space-md">
                    <div class="flex items-center justify-between">
                        <span class="font-label-md text-label-md text-primary uppercase tracking-wider font-bold">Kategori Wastra</span>
                        <span class="font-label-sm text-label-sm text-outline">{{ $categories->count() }} Jenis</span>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label class="flex items-center justify-between p-2 rounded-lg {{ !request('category') ? 'bg-surface-container-lowest text-primary' : 'text-on-surface-variant' }} font-label-md text-label-md cursor-pointer hover:bg-surface-container-lowest transition-colors">
                            <span class="flex items-center gap-space-xs">
                                <input {{ !request('category') ? 'checked' : '' }} class="accent-secondary w-3.5 h-3.5" name="category" type="radio" value="" onchange="this.form.submit()"/>
                                <span class="{{ !request('category') ? 'font-bold' : '' }}">Semua Koleksi</span>
                            </span>
                        </label>
                        @foreach($categories as $cat)
                        <label class="flex items-center justify-between p-2 rounded-lg {{ request('category') == $cat->id ? 'bg-surface-container-lowest text-primary' : 'text-on-surface-variant' }} font-label-md text-label-md cursor-pointer hover:bg-surface-container-lowest transition-colors">
                            <span class="flex items-center gap-space-xs">
                                <input {{ request('category') == $cat->id ? 'checked' : '' }} class="accent-secondary w-3.5 h-3.5" name="category" type="radio" value="{{ $cat->id }}" onchange="this.form.submit()"/>
                                <span>{{ $cat->name }}</span>
                            </span>
                            <span class="text-outline font-label-sm text-label-sm">{{ $cat->products_count ?? '' }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Sertifikat --}}
                <div class="p-space-md bg-surface-container rounded-lg flex items-start gap-space-sm mt-space-xs">
                    <span class="material-symbols-outlined text-secondary text-[24px]">verified</span>
                    <div class="flex flex-col gap-0.5">
                        <span class="font-label-md text-label-md text-primary font-bold">Sertifikat Otentisitas</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant leading-snug">Setiap helai kain dilengkapi sertifikat fisik bernomor registrasi pembatik.</p>
                    </div>
                </div>
            </form>
        </aside>

        {{-- ── PRODUCT GRID ── --}}
        <div class="lg:col-span-9 flex flex-col gap-space-xl">

            @if($products->isEmpty())
                <div class="text-center py-16 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[64px] block mb-4">inventory_2</span>
                    <p class="font-body-md text-body-md">Tidak ada produk yang sesuai filter.</p>
                    <a href="{{ route('catalog') }}" class="mt-4 inline-flex items-center gap-1 text-secondary hover:text-primary font-label-lg text-label-lg transition-colors">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Lihat Semua
                    </a>
                </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-space-lg">
                @foreach($products as $product)
                <article class="group flex flex-col bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="relative aspect-[4/5] overflow-hidden bg-surface-container">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-surface-container">
                                <span class="material-symbols-outlined text-[80px] text-outline">draw</span>
                            </div>
                        @endif
                        <div class="absolute top-space-sm left-space-sm flex flex-col gap-1 items-start">
                            <span class="px-space-sm py-0.5 rounded-full bg-primary text-on-primary font-label-sm text-label-sm font-bold tracking-wider uppercase shadow-sm">{{ $product->category->name ?? 'Batik' }}</span>
                        </div>
                        <button class="absolute top-space-sm right-space-sm w-8 h-8 rounded-full bg-surface-container-lowest/80 backdrop-blur-md text-primary hover:text-secondary flex items-center justify-center transition-colors"
                                title="Simpan ke Favorit">
                            <span class="material-symbols-outlined text-[18px]">favorite</span>
                        </button>
                        <div class="absolute bottom-space-sm left-space-sm right-space-sm bg-surface/90 backdrop-blur-md p-space-xs rounded-lg text-center transform translate-y-8 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                            <span class="font-label-sm text-label-sm text-primary font-bold">
                                Stok: {{ $product->stock }} pcs
                            </span>
                        </div>
                    </div>
                    <div class="p-space-md flex flex-col flex-1 justify-between gap-space-sm">
                        <div>
                            <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest font-semibold block mb-0.5">{{ $product->category->name ?? '-' }}</span>
                            <h3 class="font-title-lg text-title-lg text-primary group-hover:text-secondary transition-colors line-clamp-1">{{ $product->name }}</h3>
                            <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2 mt-1">{{ Str::limit($product->description, 90) }}</p>
                        </div>
                        <div class="pt-space-sm border-t border-surface-container">
                            <div class="flex items-baseline justify-between mb-space-sm">
                                <span class="font-label-sm text-label-sm text-outline uppercase">Mahar Wastra</span>
                                <span class="font-headline-sm text-headline-sm text-primary font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-space-xs">
                                <a href="{{ route('product.show', $product->id) }}"
                                   class="py-2 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary font-label-md text-label-md transition-colors text-center">
                                    Detail
                                </a>
                                <a href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya ingin memesan: ' . $product->name) }}" target="_blank" rel="noopener"
                                   class="py-2 rounded-lg bg-primary hover:bg-secondary text-on-primary font-label-md text-label-md shadow-sm transition-colors flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">shopping_bag</span>
                                    <span>Beli</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-space-md pt-space-lg border-t border-surface-container-high">
                <span class="font-body-sm text-body-sm text-on-surface-variant">
                    Menampilkan <span class="text-primary font-bold">{{ $products->firstItem() }}–{{ $products->lastItem() }}</span>
                    dari <span class="text-primary font-bold">{{ $products->total() }}</span> koleksi adiluhung
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
                <a href="mailto:concierge@juraganbatik.id"
                   class="whitespace-nowrap px-space-lg py-space-sm bg-secondary hover:bg-secondary-container hover:text-on-secondary-container text-on-secondary font-label-lg text-label-lg rounded-lg shadow-sm transition-all flex items-center gap-space-xs flex-shrink-0">
                    <span class="material-symbols-outlined text-[18px]">support_agent</span>
                    <span>Hubungi Kurator</span>
                </a>
            </div>
            @endif

        </div>{{-- end product grid --}}
    </div>{{-- end 2col --}}

</div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('button[title="Simpan ke Favorit"]').forEach(btn => {
    btn.addEventListener('click', function() {
        const icon = this.querySelector('.material-symbols-outlined');
        const filled = icon.style.fontVariationSettings === "'FILL' 1";
        icon.style.fontVariationSettings = filled ? "'FILL' 0" : "'FILL' 1";
        icon.style.color = filled ? '' : '#ba1a1a';
    });
});
</script>
@endpush
