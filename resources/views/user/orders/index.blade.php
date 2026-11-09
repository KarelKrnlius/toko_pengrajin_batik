@extends('layouts.app')
@section('title', 'Pesanan Saya — Juragan Batik Surakarta')

@push('styles')
<style>
    /* Styling scrollbar halus */
    .order-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .order-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
{{-- ══ Header Halaman Pesanan Saya ══ --}}
<div class="w-full bg-gradient-to-r from-primary via-primary-container to-secondary py-10">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
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

<div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

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
    <div class="flex items-center gap-2 mb-space-lg overflow-x-auto pb-1 scrollbar-none">
        <a href="{{ route('user.orders.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all shadow-sm flex-shrink-0 {{ !request('status') ? 'bg-primary text-on-primary ring-2 ring-primary/20' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span>Semua</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('status') ? 'bg-white/20 text-white' : 'bg-outline-variant/40 text-on-surface' }}">
                {{ $counts['all'] }}
            </span>
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'pending']) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all shadow-sm flex-shrink-0 {{ request('status') === 'pending' ? 'bg-amber-600 text-white ring-2 ring-amber-600/20' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">hourglass_empty</span>
            <span>Menunggu Konfirmasi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-900 font-bold' }}">
                {{ $counts['pending'] }}
            </span>
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'shipped']) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all shadow-sm flex-shrink-0 {{ request('status') === 'shipped' ? 'bg-blue-600 text-white ring-2 ring-blue-600/20' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">local_shipping</span>
            <span>Sedang Dikirim</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'shipped' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-900 font-bold' }}">
                {{ $counts['shipped'] }}
            </span>
        </a>
        <a href="{{ route('user.orders.index', ['status' => 'completed']) }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold tracking-wider uppercase transition-all shadow-sm flex-shrink-0 {{ request('status') === 'completed' ? 'bg-green-700 text-white ring-2 ring-green-700/20' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">task_alt</span>
            <span>Selesai</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'completed' ? 'bg-white/20 text-white' : 'bg-green-100 text-green-900 font-bold' }}">
                {{ $counts['completed'] }}
            </span>
        </a>
    </div>

    {{-- ══ Daftar Kartu Pesanan ══ --}}
    <div class="flex flex-col gap-space-lg">
        @forelse($orders as $order)
        <div class="order-card bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden">

            {{-- 1. Card Header: No Pesanan, Tanggal, & Badge Status --}}
            <div class="px-space-lg py-3.5 bg-surface-container-low border-b border-outline-variant flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center font-serif font-bold text-xs flex-shrink-0">
                        <span class="material-symbols-outlined text-[18px]">receipt</span>
                    </span>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-mono font-bold text-sm text-primary">#{{ $order->order_number }}</span>
                            <span class="text-xs text-outline">&bull;</span>
                            <span class="text-xs text-on-surface-variant font-medium">
                                {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Status Badge --}}
                <div>
                    @if($order->status === 'pending')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300 inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                            Menunggu Konfirmasi
                        </span>
                    @elseif($order->status === 'shipped')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-900 border border-blue-300 inline-flex items-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[15px] text-blue-700">local_shipping</span>
                            Sedang Dikirim
                        </span>
                    @elseif($order->status === 'completed')
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-900 border border-green-300 inline-flex items-center gap-1.5 shadow-sm">
                            <span class="material-symbols-outlined text-[15px] text-green-700">task_alt</span>
                            Pesanan Selesai
                        </span>
                    @endif
                </div>
            </div>

            {{-- 2. Card Body: Daftar Produk & Rincian Pengiriman --}}
            <div class="p-space-lg grid grid-cols-1 lg:grid-cols-3 gap-space-lg">

                {{-- Kolom Kiri & Tengah: Produk yang Dibeli --}}
                <div class="lg:col-span-2 flex flex-col gap-3">
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
                <div class="lg:col-span-1 flex flex-col justify-between bg-surface-container-low/60 p-4 rounded-xl border border-outline-variant">
                    <div class="flex flex-col gap-3">
                        {{-- Logistik & Resi --}}
                        <div>
                            <span class="text-[11px] font-bold text-outline uppercase tracking-wider block mb-1">
                                Informasi Pengiriman
                            </span>
                            <div class="flex items-center gap-2 text-sm font-semibold text-primary">
                                <span class="material-symbols-outlined text-[18px] text-secondary">local_shipping</span>
                                <span>{{ $order->shipping_courier }}</span>
                            </div>

                            @if($order->tracking_number)
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

                        {{-- Alamat Penerima --}}
                        <div class="pt-2 border-t border-surface-container">
                            <span class="text-[11px] font-bold text-outline uppercase tracking-wider block mb-1">
                                Alamat Tujuan
                            </span>
                            <p class="text-xs text-on-surface leading-relaxed line-clamp-2" title="{{ $order->customer_address }}">
                                {{ $order->customer_address ?: ($order->user?->address ?? 'Alamat belum diatur') }}
                            </p>
                            <p class="text-xs text-secondary font-medium mt-1">
                                Penerima: {{ $order->customer_name ?: ($order->user?->name ?? '—') }} ({{ $order->customer_phone ?: ($order->user?->phone ?? '—') }})
                            </p>
                        </div>

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
            <div class="px-space-lg py-3 bg-surface-container-low border-t border-outline-variant flex flex-wrap items-center justify-between gap-3">
                <div class="text-xs text-on-surface-variant flex items-center gap-1.5">
                    @if($order->status === 'pending')
                        <span class="material-symbols-outlined text-[16px] text-amber-600">info</span>
                        <span>Pesanan sedang diverifikasi oleh admin Juragan Batik.</span>
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
                        <span>Pesanan Diterima / Sampai</span>
                    </button>
                    @endif

                    {{-- Tombol Bantuan / Hubungi Admin --}}
                    <a href="https://api.whatsapp.com/send?phone=6281234567890&text={{ urlencode('Halo Admin Juragan Batik, saya ingin menanyakan pesanan #' . $order->order_number) }}"
                       target="_blank"
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-semibold rounded-xl transition-colors border border-outline-variant">
                        <span class="material-symbols-outlined text-[16px] text-green-700">chat</span>
                        <span>Bantuan</span>
                    </a>
                </div>
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
    function confirmOrderReceived(orderId, orderNumber) {
        document.getElementById('formConfirmReceived').action = `/pesanan/${orderId}/terima`;
        document.getElementById('confirmOrderNumber').textContent = '#' + orderNumber;
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
