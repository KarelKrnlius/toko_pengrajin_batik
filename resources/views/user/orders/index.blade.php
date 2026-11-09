@extends('layouts.app')
@section('title', 'Pesanan Saya — Juragan Batik Surakarta')

@push('styles')
<style>
    /* Styling card & transisi halus dengan ukuran tetap presisi */
    .orders-container {
        width: 100% !important;
        box-sizing: border-box !important;
    }
    .order-card {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        transition: box-shadow 0.2s ease, border-color 0.2s ease;
    }
    .order-card:hover {
        border-color: rgba(var(--color-primary-rgb, 107, 44, 44), 0.35);
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.06);
    }
</style>
@endpush

@section('content')
{{-- ══ Header Halaman Pesanan Saya ══ --}}
<div class="w-full bg-gradient-to-r from-primary via-primary-container to-secondary py-10">
    <div class="w-full max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
        <nav class="flex items-center gap-2 text-xs text-primary-fixed-dim uppercase tracking-wider mb-2">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span>&bull;</span>
            <span class="text-secondary-fixed font-bold">Pesanan Saya</span>
        </nav>
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md">
            <div>
                <h1 class="font-headline-md text-headline-md text-on-primary font-bold">
                    Pesanan & Riwayat Belanja
                </h1>
                <p class="font-body-sm text-body-sm text-primary-fixed-dim mt-1">
                    Pantau status pengiriman, nomor resi kurir, dan konfirmasi penerimaan karya wastra batik Anda.
                </p>
            </div>
            <a href="{{ route('catalog') }}"
               class="inline-flex items-center gap-2 px-space-md py-2.5 bg-surface-container-lowest hover:bg-surface text-primary font-label-lg text-label-lg rounded-xl shadow-sm transition-all">
                <span class="material-symbols-outlined text-[20px] text-secondary">storefront</span>
                <span>Belanja Lagi di Katalog</span>
            </a>
        </div>
    </div>
</div>

<div class="w-full max-w-[1440px] mx-auto px-margin-mobile lg:px-margin py-space-xl box-border orders-container">

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-900 rounded-xl mb-space-lg shadow-sm">
        <span class="material-symbols-outlined text-green-700 text-[24px] flex-shrink-0">check_circle</span>
        <div class="text-sm font-semibold">{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-center gap-3 p-4 bg-error-container border border-error/30 text-on-surface rounded-xl mb-space-lg shadow-sm">
        <span class="material-symbols-outlined text-error text-[24px] flex-shrink-0">error</span>
        <div class="text-sm font-semibold">{{ session('error') }}</div>
    </div>
    @endif

    {{-- ══ Filter Tabs Status Pesanan ══ --}}
    <div class="flex items-center gap-2 mb-4 overflow-x-auto pb-1 scrollbar-none">
        <a href="{{ route('user.orders.index', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all shadow-sm flex-shrink-0 {{ !request('status') ? 'bg-primary text-on-primary ring-2 ring-primary/20' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span>Semua</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('status') ? 'bg-white/20 text-white' : 'bg-outline-variant/40 text-on-surface' }}">
                {{ $counts['all'] }}
            </span>
        </a>
        <a href="{{ route('user.orders.index', array_filter(['status' => 'pending', 'search' => request('search'), 'sort' => request('sort')])) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all shadow-sm flex-shrink-0 {{ request('status') === 'pending' ? 'bg-amber-600 text-white ring-2 ring-amber-600/20' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">hourglass_empty</span>
            <span>Menunggu Konfirmasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-900 font-bold' }}">
                {{ $counts['pending'] }}
            </span>
        </a>
        <a href="{{ route('user.orders.index', array_filter(['status' => 'shipped', 'search' => request('search'), 'sort' => request('sort')])) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all shadow-sm flex-shrink-0 {{ request('status') === 'shipped' ? 'bg-blue-600 text-white ring-2 ring-blue-600/20' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">local_shipping</span>
            <span>Dikirim</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'shipped' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-900 font-bold' }}">
                {{ $counts['shipped'] }}
            </span>
        </a>
        <a href="{{ route('user.orders.index', array_filter(['status' => 'ready_pickup', 'search' => request('search'), 'sort' => request('sort')])) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all shadow-sm flex-shrink-0 {{ request('status') === 'ready_pickup' ? 'bg-purple-700 text-white ring-2 ring-purple-700/20' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">storefront</span>
            <span>Siap Diambil</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'ready_pickup' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-900 font-bold' }}">
                {{ $counts['ready_pickup'] }}
            </span>
        </a>
        <a href="{{ route('user.orders.index', array_filter(['status' => 'completed', 'search' => request('search'), 'sort' => request('sort')])) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all shadow-sm flex-shrink-0 {{ request('status') === 'completed' ? 'bg-green-700 text-white ring-2 ring-green-700/20' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">task_alt</span>
            <span>Selesai</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'completed' ? 'bg-white/20 text-white' : 'bg-green-100 text-green-900 font-bold' }}">
                {{ $counts['completed'] }}
            </span>
        </a>
    </div>

    {{-- ══ Toolbar Search Kode Nomor Pesanan & Sort By Terlama / Terbaru ══ --}}
    <form method="GET" action="{{ route('user.orders.index') }}" class="mb-space-lg bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant p-3.5 sm:p-4">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            {{-- Input Search Kode Nomor Pesanan --}}
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari kode nomor pesanan (contoh: JB-20261109-XXXX)..."
                       class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-xl text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-mono" />
            </div>

            {{-- Select Sort By (Terbaru / Terlama) --}}
            <div class="relative min-w-[190px]">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">sort</span>
                <select name="sort"
                        onchange="this.form.submit()"
                        class="w-full pl-9 pr-8 py-2.5 bg-surface-container-low border border-outline-variant rounded-xl text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary font-medium appearance-none cursor-pointer transition-all">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Urutkan: Terbaru</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Urutkan: Terlama</option>
                </select>
                <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
            </div>

            {{-- Tombol Cari & Reset --}}
            <div class="flex items-center gap-2">
                <button type="submit"
                        class="flex-1 sm:flex-none px-5 py-2.5 bg-primary hover:bg-secondary text-on-primary font-bold text-xs uppercase tracking-wider rounded-xl shadow-sm transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">search</span>
                    <span>Cari</span>
                </button>
                @if(request('search') || request('sort'))
                <a href="{{ route('user.orders.index', request('status') ? ['status' => request('status')] : []) }}"
                   class="px-3.5 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold rounded-xl border border-outline-variant transition-colors flex items-center gap-1"
                   title="Reset Pencarian & Urutan">
                    <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                    <span class="hidden sm:inline">Reset</span>
                </a>
                @endif
            </div>
        </div>
    </form>

    {{-- ══ Daftar Kartu Pesanan ══ --}}
    <div class="w-full flex flex-col gap-space-lg">
        @forelse($orders as $order)
        <div class="order-card w-full max-w-full bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden box-border">

            {{-- 1. Card Header: Klik untuk Membuka / Menutup Dropdown Rincian Pesanan --}}
            <div role="button"
                 tabindex="0"
                 onkeydown="if(event.key === 'Enter' || event.key === ' ') { toggleOrderDetail({{ $order->id }}); event.preventDefault(); }"
                 onclick="toggleOrderDetail({{ $order->id }})"
                 class="w-full max-w-full box-border text-left px-space-lg py-4 bg-surface-container-low hover:bg-surface-container transition-colors flex flex-col md:flex-row md:items-center justify-between gap-3 cursor-pointer select-none group">
                
                {{-- Kiri: Ikon, Nomor Pesanan, Tanggal & Jam, serta Kode Resi & Kurir --}}
                <div class="flex items-start md:items-center gap-3">
                    <span class="w-10 h-10 rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors flex items-center justify-center font-bold text-xs flex-shrink-0 mt-0.5 md:mt-0 shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                    </span>
                    <div>
                        {{-- Baris 1: No. Pesanan & Tanggal & Jam (Device Time) --}}
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-mono font-bold text-sm text-primary">{{ $order->order_number }}</span>
                            <span class="text-xs text-outline">&bull;</span>
                            <span class="text-xs text-on-surface font-semibold device-time" data-utc="{{ $order->created_at->toISOString() }}">
                                {{ $order->created_at->translatedFormat('d F Y, H:i') }}
                            </span>
                        </div>

                        {{-- Baris 2: Informasi Ekspedisi Kurir & Kode Resi --}}
                        <div class="flex flex-wrap items-center gap-2 mt-1 text-xs">
                            <span class="font-medium text-on-surface-variant flex items-center gap-1">
                                <span class="material-symbols-outlined text-[15px] text-secondary">
                                    {{ $order->shipping_courier === 'Ambil Mandiri' ? 'storefront' : 'local_shipping' }}
                                </span>
                                <span>{{ $order->shipping_courier }}</span>
                            </span>

                            @if($order->shipping_courier === 'Ambil Mandiri')
                                <span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-[11px] font-semibold">
                                    Ambil di Toko
                                </span>
                            @elseif($order->tracking_number)
                                <span class="text-outline">&bull;</span>
                                <span class="font-mono font-bold text-primary bg-surface-container px-2 py-0.5 rounded border border-outline-variant flex items-center gap-1" title="Nomor Resi">
                                    <span class="material-symbols-outlined text-[13px] text-outline">barcode</span>
                                    <span>Resi: {{ $order->tracking_number }}</span>
                                </span>
                            @else
                                <span class="text-outline">&bull;</span>
                                <span class="text-[11px] text-outline italic">Resi belum terbit</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kanan: Total Belanja, Status Badge, dan Ikon Panah Dropdown --}}
                <div class="flex items-center justify-between md:justify-end gap-3 flex-shrink-0 pt-2 md:pt-0 border-t md:border-t-0 border-outline-variant/40">
                    <div class="text-left md:text-right">
                        <span class="text-[10px] uppercase font-bold text-outline block">Total Belanja</span>
                        <span class="font-title-lg text-title-lg text-secondary font-bold">
                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                        </span>
                    </div>

                    {{-- Status Badge --}}
                    <div>
                        @if($order->status === 'pending')
                            <div class="flex flex-col items-end gap-1">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 inline-flex items-center gap-1.5 shadow-sm">
                                    <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                                    Menunggu Konfirmasi
                                </span>
                                <span class="text-[10px] font-bold text-green-700 flex items-center gap-1 bg-green-50 px-2 py-0.5 rounded border border-green-200">
                                    <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                    Pembayaran Berhasil
                                </span>
                            </div>
                        @elseif($order->status === 'shipped' && $order->shipping_courier === 'Ambil Mandiri')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-900 border border-purple-300 inline-flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[15px] text-purple-700">storefront</span>
                                Siap Diambil
                            </span>
                        @elseif($order->status === 'shipped')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-900 border border-blue-300 inline-flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[15px] text-blue-700">local_shipping</span>
                                Dikirim
                            </span>
                        @elseif($order->status === 'completed')
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-900 border border-green-300 inline-flex items-center gap-1.5 shadow-sm">
                                <span class="material-symbols-outlined text-[15px] text-green-700">task_alt</span>
                                Selesai
                            </span>
                        @endif
                    </div>

                    {{-- Ikon Panah Dropdown (Berputar saat terbuka) --}}
                    <span class="w-8 h-8 rounded-full bg-surface-container flex items-center justify-center text-outline transition-all duration-200" id="arrow-{{ $order->id }}">
                        <span class="material-symbols-outlined text-[20px]">expand_more</span>
                    </span>
                </div>
            </div>

            {{-- 2. Detail Rincian Pesanan (Dropdown / Pertama Ketutup Dulu) --}}
            <div id="order-detail-{{ $order->id }}" class="hidden border-t border-outline-variant w-full max-w-full box-border">
                {{-- Card Body: Daftar Produk & Rincian Pengiriman --}}
                <div class="p-space-lg grid grid-cols-1 lg:grid-cols-3 gap-space-lg w-full max-w-full box-border">

                {{-- Kolom Kiri & Tengah: Produk yang Dibeli --}}
                <div class="lg:col-span-2 flex flex-col gap-3 min-w-0 w-full">
                    <h3 class="text-xs font-bold text-outline uppercase tracking-wider mb-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-secondary">inventory_2</span>
                        <span>Daftar Wastra Batik ({{ $order->items->sum('quantity') }} Pcs)</span>
                    </h3>

                    <div class="divide-y divide-surface-container border border-outline-variant rounded-xl overflow-hidden bg-surface-container-low/40">
                        @foreach($order->items as $item)
                        <div class="p-3.5 flex items-center gap-3.5 hover:bg-surface-container-low transition-colors">
                            {{-- Foto Produk --}}
                            <div class="w-14 h-14 rounded-lg bg-surface-container overflow-hidden border border-outline-variant flex-shrink-0 flex items-center justify-center">
                                @if($item->product && $item->product->image)
                                    <img src="{{ Storage::url($item->product->image) }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover"/>
                                @else
                                    <span class="material-symbols-outlined text-outline text-[22px]">image</span>
                                @endif
                            </div>

                            {{-- Info Produk --}}
                            <div class="flex-1 min-w-0">
                                <h4 class="font-title-lg text-title-lg text-primary font-bold truncate">
                                    {{ $item->product?->name ?? 'Produk Batik' }}
                                </h4>
                                <div class="flex items-center gap-2 text-xs text-on-surface-variant mt-0.5">
                                    <span class="px-2 py-0.5 rounded bg-surface-container text-on-surface text-[10px] font-semibold">
                                        {{ $item->product?->category?->name ?? 'Batik Adiluhung' }}
                                    </span>
                                    <span>&bull;</span>
                                    <span>Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    <span>&times;</span>
                                    <span class="font-bold text-primary">{{ $item->quantity }}</span>
                                </div>
                            </div>

                            {{-- Subtotal per Produk --}}
                            <div class="text-right flex-shrink-0">
                                <span class="font-bold text-sm text-primary block">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Kolom Kanan: Info Logistik, Resi, & Total Tagihan --}}
                <div class="lg:col-span-1 flex flex-col justify-between bg-surface-container-low/60 p-4 rounded-xl border border-outline-variant min-w-0 w-full box-border">
                    <div class="flex flex-col gap-3">
                        {{-- Logistik & Resi / Lokasi Pengambilan --}}
                        <div>
                            <span class="text-[11px] font-bold text-outline uppercase tracking-wider block mb-1">
                                Informasi Pengiriman
                            </span>
                            <div class="flex items-center gap-2 text-sm font-semibold text-primary">
                                <span class="material-symbols-outlined text-[18px] text-secondary">
                                    {{ $order->shipping_courier === 'Ambil Mandiri' ? 'storefront' : 'local_shipping' }}
                                </span>
                                <span>{{ $order->shipping_courier }}</span>
                            </div>

                            @if($order->shipping_courier === 'Ambil Mandiri')
                            <div class="mt-2 p-2.5 bg-purple-50 text-purple-900 rounded-lg border border-purple-200 text-xs">
                                <span class="font-bold flex items-center gap-1 text-purple-900">
                                    <span class="material-symbols-outlined text-[15px]">storefront</span>
                                    Pengambilan Mandiri di Toko
                                </span>
                                <span class="text-[11px] text-purple-700 block mt-0.5">Silakan ambil pesanan Anda langsung di Galeri Juragan Batik Surakarta.</span>
                            </div>
                            @elseif($order->tracking_number)
                            <div class="mt-2 p-2.5 bg-surface-container-lowest rounded-lg border border-outline-variant flex items-center justify-between gap-2">
                                <div>
                                    <span class="text-[10px] font-bold text-secondary uppercase block">No. Resi Pengiriman</span>
                                    <span class="font-mono text-xs font-bold text-primary select-all">{{ $order->tracking_number }}</span>
                                </div>
                                <button type="button" onclick="copyTracking('{{ $order->tracking_number }}')"
                                        class="px-2 py-1 bg-surface-container hover:bg-primary hover:text-white text-on-surface text-[10px] font-bold rounded transition-colors"
                                        title="Salin Nomor Resi">
                                    Salin
                                </button>
                            </div>
                            @else
                            <p class="text-[11px] text-on-surface-variant italic mt-1 bg-surface-container-lowest/60 p-2 rounded border border-dashed border-outline-variant">
                                Resi akan ditampilkan setelah paket diserahkan ke kurir.
                            </p>
                            @endif
                        </div>

                        {{-- Alamat Penerima / Lokasi Pengambilan Toko --}}
                        <div class="pt-2 border-t border-surface-container">
                            @if($order->shipping_courier === 'Ambil Mandiri')
                                <span class="text-[11px] font-bold text-outline uppercase tracking-wider block mb-1 flex items-center gap-1 text-purple-900">
                                    <span class="material-symbols-outlined text-[14px]">store</span>
                                    Lokasi Pengambilan Toko
                                </span>
                                <p class="text-xs text-on-surface leading-relaxed">
                                    <strong>Galeri Utama Juragan Batik Surakarta</strong><br>
                                    Jl. Slamet Riyadi No. 128, Surakarta, Jawa Tengah (57151)<br>
                                    <span class="text-[11px] text-on-surface-variant">Buka Setiap Hari: 09.00 - 20.00 WIB</span>
                                </p>
                                <p class="text-xs text-secondary font-medium mt-1.5">
                                    Nama Pengambil: {{ $order->customer_name ?: ($order->user?->name ?? '—') }} ({{ $order->customer_phone ?: ($order->user?->phone ?? '—') }})
                                </p>
                            @else
                                <span class="text-[11px] font-bold text-outline uppercase tracking-wider block mb-1">
                                    Alamat Tujuan
                                </span>
                                <p class="text-xs text-on-surface leading-relaxed line-clamp-2" title="{{ $order->customer_address }}">
                                    {{ $order->customer_address ?: ($order->user?->address ?? 'Alamat belum diatur') }}
                                </p>
                                <p class="text-xs text-secondary font-medium mt-1">
                                    Penerima: {{ $order->customer_name ?: ($order->user?->name ?? '—') }} ({{ $order->customer_phone ?: ($order->user?->phone ?? '—') }})
                                </p>
                            @endif
                        </div>

                        {{-- Keterangan Pembayaran Berhasil saat Menunggu Konfirmasi --}}
                        @if($order->status === 'pending')
                        <div class="p-2.5 bg-green-50 border border-green-200 rounded-lg text-xs text-green-900 flex items-start gap-2">
                            <span class="material-symbols-outlined text-green-700 text-[18px] flex-shrink-0 mt-0.5">check_circle</span>
                            <div>
                                <span class="font-bold text-green-800 block">Pembayaran Berhasil</span>
                                <span class="text-[11px] text-green-900/80 leading-tight block">Pesanan Anda telah dibayar & tercatat. Menunggu verifikasi admin untuk penyiapan kain batik.</span>
                            </div>
                        </div>
                        @endif

                        {{-- Rincian Biaya --}}
                        <div class="pt-2 border-t border-surface-container text-xs space-y-1">
                            <div class="flex justify-between text-on-surface-variant">
                                <span>Subtotal Produk:</span>
                                <span>Rp {{ number_format($order->subtotal_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-on-surface-variant">
                                <span>Biaya Ongkir ({{ $order->shipping_courier }}):</span>
                                <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm font-bold text-primary pt-1.5 border-t border-dashed border-outline-variant">
                                <span>Total Bayar:</span>
                                <span class="font-title-lg text-title-lg text-secondary">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="text-[10px] text-outline text-right uppercase tracking-wider">
                                Metode: {{ str_replace('_', ' ', $order->payment_method) }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 3. Card Footer: Tombol Aksi (Pesanan Diterima & Bantuan) --}}
            <div class="px-space-lg py-3 bg-surface-container-low border-t border-outline-variant flex flex-wrap items-center justify-between gap-3 w-full box-border">
                <div class="text-xs text-on-surface-variant flex items-center gap-1.5">
                    @if($order->status === 'pending')
                        <span class="material-symbols-outlined text-[16px] text-green-600">check_circle</span>
                        <span><strong class="text-green-700">Pembayaran Berhasil.</strong> Menunggu konfirmasi & verifikasi admin Juragan Batik.</span>
                    @elseif($order->status === 'shipped' && $order->shipping_courier === 'Ambil Mandiri')
                        <span class="material-symbols-outlined text-[16px] text-purple-700">storefront</span>
                        <span><strong class="text-purple-800">Siap Diambil.</strong> Pesanan siap diambil di Galeri Juragan Batik.</span>
                    @elseif($order->status === 'shipped')
                        <span class="material-symbols-outlined text-[16px] text-blue-600">navigation</span>
                        <span>Paket sedang dalam perjalanan menuju alamat Anda.</span>
                    @elseif($order->status === 'completed')
                        <span class="material-symbols-outlined text-[16px] text-green-700">verified</span>
                        <span>Pesanan telah selesai diterima. Terima kasih!</span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    {{-- ══ TOMBOL UTAMA: PESANAN DITERIMA / SAMPAI (HANYA MUNCUL JIKA SHIPPED) ══ --}}
                    @if($order->status === 'shipped')
                    <button type="button"
                            onclick="confirmOrderReceived({{ $order->id }}, '{{ $order->order_number }}')"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-green-700 hover:bg-green-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all transform hover:-translate-y-0.5">
                        <span class="material-symbols-outlined text-[18px]">task_alt</span>
                        <span>{{ $order->shipping_courier === 'Ambil Mandiri' ? 'Pesanan Sudah Diambil' : 'Pesanan Diterima / Sampai' }}</span>
                    </button>
                    @endif

                    {{-- Tombol Bantuan / Hubungi Admin --}}
                    <a href="https://api.whatsapp.com/send?phone=6281234567890&text={{ urlencode('Halo Admin Juragan Batik, saya ingin menanyakan pesanan ' . $order->order_number) }}"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold rounded-xl transition-colors border border-outline-variant">
                        <span class="material-symbols-outlined text-[16px] text-green-700">chat</span>
                        <span>Bantuan</span>
                    </a>
                </div>
            </div>

            {{-- 3. Tutup Dropdown Detail Pesanan --}}
            </div>

        </div>
        @empty
        {{-- Empty State --}}
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant p-16 text-center">
            <div class="w-20 h-20 rounded-full bg-surface-container mx-auto flex items-center justify-center text-outline mb-4">
                <span class="material-symbols-outlined text-[48px]">receipt_long</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary font-bold mb-2">
                Belum Ada Riwayat Pesanan
            </h3>
            <p class="font-body-sm text-body-sm text-on-surface-variant max-w-md mx-auto mb-6">
                @if(request('status'))
                    Tidak ada pesanan dengan status "{{ request('status') }}". Silakan periksa tab lainnya.
                @else
                    Anda belum memiliki transaksi pesanan batik. Temukan koleksi mahakarya batik tulis & cap asli Surakarta di katalog kami.
                @endif
            </p>
            <div class="flex items-center justify-center gap-3">
                @if(request('status'))
                <a href="{{ route('user.orders.index') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs tracking-wider uppercase rounded-xl transition-colors">
                    <span class="material-symbols-outlined text-[16px]">close</span>
                    Lihat Semua Pesanan
                </a>
                @endif
                <a href="{{ route('catalog') }}"
                   class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-primary hover:bg-secondary text-on-primary font-bold text-xs tracking-wider uppercase rounded-xl shadow-md transition-all">
                    <span class="material-symbols-outlined text-[18px]">shopping_bag</span>
                    Jelajahi Katalog Batik
                </a>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
    <div class="mt-space-lg">
        {{ $orders->links() }}
    </div>
    @endif

</div>

{{-- ══════════ MODAL KONFIRMASI PESANAN SAMPAI ══════════ --}}
<div id="modalConfirmReceived" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-md w-full rounded-2xl shadow-2xl overflow-hidden border border-outline-variant">
        <div class="bg-gradient-to-r from-green-700 to-green-800 px-space-lg py-space-md text-white flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[24px]">task_alt</span>
                <span>Konfirmasi Barang Diterima</span>
            </h3>
            <button onclick="document.getElementById('modalConfirmReceived').classList.add('hidden')" class="hover:opacity-80">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>

        <form id="formConfirmReceived" method="POST" class="p-space-lg flex flex-col gap-4">
            @csrf
            @method('PATCH')

            <div class="flex items-start gap-3">
                <div class="w-12 h-12 rounded-xl bg-green-50 text-green-700 flex items-center justify-center flex-shrink-0 border border-green-200">
                    <span class="material-symbols-outlined text-[28px]">mark_email_read</span>
                </div>
                <div>
                    <p class="text-sm text-on-surface font-semibold mb-1">
                        Apakah paket pesanan <span id="confirmOrderNumber" class="text-primary font-mono font-bold"></span> telah sampai dan Anda terima dengan baik?
                    </p>
                    <p class="text-xs text-on-surface-variant leading-relaxed">
                        Dengan menekan tombol konfirmasi di bawah, status pesanan akan diselesaikan (Completed) dan transaksi dinyatakan selesai.
                    </p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-surface-container">
                <button type="button" onclick="document.getElementById('modalConfirmReceived').classList.add('hidden')"
                        class="px-4 py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs uppercase tracking-wider rounded-xl transition-colors">
                    Belum Sampai
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-green-700 hover:bg-green-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition-all flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    <span>Ya, Pesanan Sudah Diterima</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Buka / Tutup Dropdown Detail Pesanan
    function toggleOrderDetail(orderId) {
        const detail = document.getElementById('order-detail-' + orderId);
        const arrow = document.getElementById('arrow-' + orderId);
        if (!detail) return;

        const isHidden = detail.classList.contains('hidden');
        if (isHidden) {
            detail.classList.remove('hidden');
            if (arrow) {
                arrow.classList.add('rotate-180', 'bg-primary', 'text-white');
                arrow.classList.remove('bg-surface-container', 'text-outline');
            }
        } else {
            detail.classList.add('hidden');
            if (arrow) {
                arrow.classList.remove('rotate-180', 'bg-primary', 'text-white');
                arrow.classList.add('bg-surface-container', 'text-outline');
            }
        }
    }

    // Format tanggal & jam sesuai device masing-masing
    document.querySelectorAll('.device-time').forEach(function(el) {
        const utc = el.dataset.utc;
        if (utc) {
            const date = new Date(utc);
            if (!isNaN(date.getTime())) {
                const formattedDate = date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                const formattedTime = date.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: false
                });
                el.textContent = `${formattedDate}, ${formattedTime}`;
            }
        }
    });

    function confirmOrderReceived(orderId, orderNumber) {
        document.getElementById('formConfirmReceived').action = `/pesanan/${orderId}/terima`;
        document.getElementById('confirmOrderNumber').textContent = orderNumber;
        document.getElementById('modalConfirmReceived').classList.remove('hidden');
    }

    function copyTracking(trackingNumber) {
        if (!trackingNumber) return;
        navigator.clipboard.writeText(trackingNumber).then(() => {
            alert('Nomor resi ' + trackingNumber + ' berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Gagal menyalin:', err);
        });
    }
</script>
@endpush
