@extends('layouts.app')
@section('title', $product->name)

@section('content')

{{-- Breadcrumb --}}
<section class="w-full bg-surface-container-low/70 py-space-md">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex flex-wrap items-center justify-between gap-space-sm">
        <nav aria-label="Breadcrumb" class="flex items-center gap-space-xs text-on-surface-variant font-label-md text-label-md">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
            <a href="{{ route('catalog') }}" class="hover:text-primary transition-colors">Katalog Batik</a>
            <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
            <span class="text-on-surface-variant">{{ $product->category->name ?? 'Produk' }}</span>
            <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
            <span class="text-secondary font-bold line-clamp-1 max-w-[200px]">{{ $product->name }}</span>
        </nav>
        <div class="hidden sm:flex items-center gap-space-xs bg-surface-container px-space-sm py-1 rounded-full text-on-surface-variant font-label-sm text-label-sm tracking-wider">
            <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
            <span>KURASI ARCHIVE NO. JB-{{ strtoupper(substr($product->category->name ?? 'BTK', 0, 3)) }}-{{ date('Y') }}-{{ str_pad($product->id, 2, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>
</section>

{{-- Main 2-Column --}}
<section class="w-full py-space-xl lg:py-space-xl">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-xl items-start">

            {{-- ── Kolom Kiri: Galeri ── --}}
            <div class="lg:col-span-7 flex flex-col gap-space-lg">
                <div class="relative bg-surface-container-lowest rounded-xl p-space-md shadow-md overflow-hidden group">
                    <div class="relative aspect-[4/5] w-full rounded-lg overflow-hidden bg-surface-container">
                        @if($product->image)
                            <img alt="{{ $product->name }}"
                                 class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                                 id="mainProductImage"
                                 src="{{ Storage::url($product->image) }}"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-surface-container" id="mainProductImage">
                                <span class="material-symbols-outlined text-[120px] text-outline">draw</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-primary/30 via-transparent to-transparent opacity-40 pointer-events-none"></div>
                        <div class="absolute top-space-md left-space-md flex flex-col gap-space-xs items-start">
                            <span class="px-space-md py-1 bg-surface/95 backdrop-blur-md rounded-full font-label-sm text-label-sm text-primary tracking-widest shadow-sm flex items-center gap-1 font-bold">
                                <span class="material-symbols-outlined text-[14px] text-secondary">verified</span>
                                {{ strtoupper($product->category->name ?? 'BATIK') }} ASLI
                            </span>
                            <span class="px-space-md py-1 bg-primary/90 text-on-primary backdrop-blur-md rounded-full font-label-sm text-label-sm tracking-wider shadow-sm">
                                PEWARNA ALAMI SOGA
                            </span>
                        </div>
                    </div>

                    {{-- Provenance Ledger --}}
                    <div class="mt-space-md pt-space-md flex flex-wrap items-center justify-between gap-space-sm bg-surface-container-low px-space-md py-space-sm rounded-lg">
                        <div class="flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-secondary text-[20px]">fingerprint</span>
                            <div class="flex flex-col">
                                <span class="font-label-sm text-label-sm text-outline">NOMOR REGISTER</span>
                                <span class="font-label-md text-label-md text-primary font-bold">JB-{{ strtoupper(substr($product->category->name ?? 'BTK', 0, 3)) }}-{{ date('Y') }}-{{ str_pad($product->id, 2, '0', STR_PAD_LEFT) }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-secondary text-[20px]">history_edu</span>
                            <div class="flex flex-col">
                                <span class="font-label-sm text-label-sm text-outline">STOK TERSEDIA</span>
                                <span class="font-label-md text-label-md text-primary font-bold">{{ $product->stock }} pcs</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-secondary text-[20px]">award_star</span>
                            <div class="flex flex-col">
                                <span class="font-label-sm text-label-sm text-outline">SERTIFIKASI</span>
                                <span class="font-label-md text-label-md text-primary font-bold">Batik Mark Indonesia</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Kolom Kanan: Info & Pembelian ── --}}
            <div class="lg:col-span-5 flex flex-col gap-space-lg">
                <div class="bg-surface-container-lowest p-space-lg lg:p-space-xl rounded-xl shadow-md flex flex-col gap-space-md">

                    {{-- Title --}}
                    <div class="flex flex-col gap-space-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-label-md text-label-md tracking-[0.2em] text-secondary font-bold uppercase">{{ $product->category->name ?? 'BATIK' }}</span>
                            <div class="flex items-center gap-1 bg-surface-container px-space-sm py-0.5 rounded-full">
                                <span class="material-symbols-outlined text-[16px] text-secondary" style="font-variation-settings:'FILL' 1;">star</span>
                                <span class="font-label-md text-label-md font-bold">5.0</span>
                            </div>
                        </div>
                        <h1 class="font-headline-md text-headline-md text-primary leading-tight">{{ $product->name }}</h1>
                    </div>

                    {{-- Price --}}
                    <div class="flex flex-col bg-surface-container-low p-space-md rounded-lg gap-space-xs">
                        <div class="flex items-baseline justify-between">
                            <div class="flex flex-col">
                                <span class="font-label-sm text-label-sm text-outline tracking-wider uppercase">Nilai Investasi Mahakarya</span>
                                <span class="font-headline-sm text-headline-sm text-primary font-bold tracking-tight">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <span class="px-space-sm py-1 bg-surface-container-highest text-on-surface-variant font-label-sm text-label-sm rounded">Harga Kolektor</span>
                        </div>
                        @if($product->stock > 0 && $product->stock <= 10)
                        <div class="flex items-center gap-space-xs pt-space-xs text-secondary font-label-md text-label-md">
                            <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
                            <span class="font-bold">Hanya {{ $product->stock }} tersisa</span>
                            <span class="text-on-surface-variant">— Stok Terbatas</span>
                        </div>
                        @elseif($product->stock == 0)
                        <div class="flex items-center gap-space-xs pt-space-xs text-error font-label-md text-label-md">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                            <span class="font-bold">Stok Habis</span>
                        </div>
                        @endif
                    </div>

                    {{-- Deskripsi --}}
                    <div class="flex flex-col gap-space-xs">
                        <span class="font-label-md text-label-md text-primary font-bold tracking-wider uppercase">Deskripsi Produk</span>
                        <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ $product->description }}</p>
                    </div>

                    {{-- CTA Buttons --}}
                    <div class="flex flex-col gap-space-sm pt-space-xs">
                        @if($product->stock > 0)
                        <a href="https://wa.me/6281234567890?text={{ urlencode('Halo, saya ingin memesan: ' . $product->name . ' (Rp ' . number_format($product->price, 0, ',', '.') . ')') }}"
                           target="_blank" rel="noopener"
                           class="w-full py-space-md px-space-lg bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded tracking-widest uppercase transition-all shadow-md flex items-center justify-center gap-space-sm group">
                            <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">shopping_bag</span>
                            <span>Pesan via WhatsApp</span>
                        </a>
                        @else
                        <button class="w-full py-space-md px-space-lg bg-surface-container-high text-on-surface-variant font-label-lg text-label-lg rounded tracking-widest uppercase cursor-not-allowed flex items-center justify-center gap-space-sm opacity-60" disabled>
                            <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                            <span>Stok Habis</span>
                        </button>
                        @endif
                        <a href="{{ route('catalog') }}"
                           class="w-full py-space-md px-space-lg bg-surface-container-high hover:bg-surface-container-highest text-primary font-label-lg text-label-lg rounded tracking-widest uppercase transition-all flex items-center justify-center gap-space-sm">
                            <span class="material-symbols-outlined text-[20px] text-secondary">arrow_back</span>
                            <span>Kembali ke Katalog</span>
                        </a>
                    </div>

                    {{-- Trust Bar --}}
                    <div class="grid grid-cols-2 gap-space-sm pt-space-sm bg-surface-container-low p-space-sm rounded-lg text-on-surface-variant font-label-sm text-label-sm">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[18px]">verified</span>
                            <span>Garansi Kemurnian Kain 100%</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[18px]">local_shipping</span>
                            <span>Pengiriman Eksklusif &amp; Aman</span>
                        </div>
                    </div>

                    {{-- Accordions --}}
                    <div class="flex flex-col gap-space-xs pt-space-sm">
                        @foreach ([
                            ['acc-filosofi', 'spa',          'Filosofi Motif',               'Setiap motif batik menyimpan makna filosofis mendalam yang diwariskan dari tradisi keraton Jawa. Motif ini melambangkan keselarasan batin, keteguhan jiwa, dan kearifan leluhur yang terus dijaga oleh para pengrajin generasi ke generasi.'],
                            ['acc-rawat',    'dry_cleaning',  'Panduan Perawatan Kain',        '<ul class="list-disc pl-4 space-y-1"><li>Cuci menggunakan sari buah lerak alami atau sabun khusus batik.</li><li>Jangan diperas kasar; angin-anginkan di tempat teduh.</li><li>Setrika dengan alas kain pelindung suhu sedang rendah.</li><li>Simpan bersama akar wangi alami.</li></ul>'],
                            ['acc-sertif',   'verified_user', 'Garansi Keaslian Batik Mark',   'Karya ini menyandang sertifikat resmi <strong>Batik Mark Indonesia</strong> bertaraf Kementerian Perindustrian RI, dilengkapi sertifikat fisik bertanda tangan Maestro Pembatik.'],
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

{{-- Artisan Narrative --}}
<section class="w-full bg-surface-container py-space-xl">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
        <div class="bg-surface-container-lowest rounded-xl p-space-lg lg:p-space-xl shadow-md grid grid-cols-1 md:grid-cols-3 gap-space-xl items-center">
            <div class="flex flex-col gap-space-xs">
                <span class="font-label-sm text-label-sm text-secondary uppercase tracking-[0.2em] font-bold">PROSES ATELIER</span>
                <h2 class="font-headline-sm text-headline-sm text-primary">Keahlian Adiluhung Tangan Maestro</h2>
                <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                    Dibuat di bengkel canting Laweyan, Surakarta. Menggunakan canting tembaga untuk detail isen-isen cecek yang super halus di atas lembaran kain pilihan.
                </p>
            </div>
            <div class="flex flex-col gap-space-sm bg-surface-container-low p-space-md rounded-lg">
                <div class="flex items-center justify-between">
                    <span class="font-label-sm text-label-sm text-outline uppercase">Indeks Kerumitan Pola</span>
                    <span class="font-label-md text-label-md text-secondary font-bold">Tingkat 4/5</span>
                </div>
                <div class="w-full bg-surface-container h-2 rounded-full overflow-hidden">
                    <div class="bg-secondary h-full rounded-full" style="width: 85%;"></div>
                </div>
                <div class="flex justify-between font-label-sm text-label-sm text-on-surface-variant pt-1">
                    <span>2.840 Titik Cecek / dm²</span>
                    <span>3 Tahap Celup Soga</span>
                </div>
            </div>
            <div class="flex items-center gap-space-md">
                <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-secondary text-[32px]">local_florist</span>
                </div>
                <div class="flex flex-col">
                    <span class="font-title-lg text-title-lg text-primary">Pewarnaan Soga Jawa Alami</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Ekstrak kulit kayu Mahoni, Tingi, dan Tegeran menghasilkan warna cokelat tanah yang kian matang seiring usia.</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Produk Terkait --}}
@if($related->isNotEmpty())
<section class="w-full py-space-xl">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex flex-col gap-space-lg">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-sm">
            <div class="flex flex-col gap-space-xs">
                <span class="font-label-sm text-label-sm text-secondary uppercase tracking-[0.2em] font-bold">KOLEKSI SEJENIS</span>
                <h2 class="font-headline-md text-headline-md text-primary">Koleksi Pelengkap Senada</h2>
            </div>
            <a href="{{ route('catalog', ['category' => $product->category_id]) }}"
               class="inline-flex items-center gap-1 font-label-lg text-label-lg text-secondary hover:text-primary transition-colors">
                <span>Lihat Seluruh Koleksi {{ $product->category->name ?? '' }}</span>
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-{{ min($related->count(), 3) }} gap-space-lg">
            @foreach($related as $rel)
            <article class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all group flex flex-col">
                <div class="relative aspect-[4/5] bg-surface-container overflow-hidden">
                    @if($rel->image)
                        <img src="{{ Storage::url($rel->image) }}"
                             alt="{{ $rel->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-surface-container">
                            <span class="material-symbols-outlined text-[60px] text-outline">draw</span>
                        </div>
                    @endif
                    <span class="absolute top-space-sm left-space-sm bg-surface/90 text-primary font-label-sm text-label-sm px-space-sm py-0.5 rounded-full font-bold">{{ $rel->category->name ?? 'Batik' }}</span>
                </div>
                <div class="p-space-md flex flex-col flex-1 justify-between gap-space-sm">
                    <div class="flex flex-col gap-1">
                        <h3 class="font-title-lg text-title-lg text-primary group-hover:text-secondary transition-colors">{{ $rel->name }}</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant line-clamp-2">{{ Str::limit($rel->description, 80) }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-space-xs">
                        <span class="font-label-lg text-label-lg text-primary font-bold">Rp {{ number_format($rel->price, 0, ',', '.') }}</span>
                        <a href="{{ route('product.show', $rel->id) }}"
                           class="w-9 h-9 rounded-full bg-surface-container hover:bg-primary hover:text-on-primary text-primary flex items-center justify-center transition-colors">
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
function toggleAccordion(id) {
    const body = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);
    const isHidden = body.classList.contains('hidden');
    body.classList.toggle('hidden', !isHidden);
    icon.style.transform = isHidden ? 'rotate(180deg)' : '';
}
</script>
@endpush
