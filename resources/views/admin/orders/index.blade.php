@extends('layouts.app')
@section('title', 'Manajemen Pesanan — Juragan Batik')

@section('content')
{{-- ══ Admin Header ══ --}}
<div class="w-full bg-gradient-to-r from-primary via-primary-container to-secondary py-8 overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md min-w-0">
        <div>
            <span class="font-label-sm text-label-sm text-secondary-fixed uppercase tracking-[0.2em] block mb-1">PORTAL ADMINISTRASI</span>
            <h1 class="font-headline-md text-headline-md text-on-primary font-bold">
                Manajemen Pesanan
            </h1>
            <p class="font-body-sm text-body-sm text-primary-fixed-dim mt-1">
                Kelola pesanan pelanggan, nomor resi pengiriman, serta konfirmasi semi-otomatis via WhatsApp.
            </p>
        </div>
        <div class="flex items-center gap-space-sm flex-shrink-0 w-full sm:w-auto">
            <a href="{{ route('admin.orders.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs px-space-md bg-surface-container-lowest hover:bg-surface text-primary font-label-lg text-label-lg rounded-xl sm:rounded shadow-sm transition-colors h-10">
                <span class="material-symbols-outlined text-[20px] text-secondary">add_circle</span>
                <span>Input Pesanan Baru</span>
            </a>
        </div>
    </div>
</div>

<div class="w-full max-w-[1440px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

    {{-- Flash Notifications --}}
    @if(session('success'))
    <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-900 rounded-xl mb-space-lg shadow-sm">
        <span class="material-symbols-outlined text-green-700 text-[24px]">check_circle</span>
        <div class="flex-1 text-sm font-semibold">{{ session('success') }}</div>
    </div>
    @endif

    @if(session('error'))
    <div class="flex items-center gap-3 p-4 bg-error-container border border-error/30 text-on-surface rounded-xl mb-space-lg shadow-sm">
        <span class="material-symbols-outlined text-error text-[24px]">error</span>
        <div class="flex-1 text-sm font-semibold">{{ session('error') }}</div>
    </div>
    @endif

    {{-- ══ Tab Status Filter Pills ══ --}}
    <div class="w-full max-w-full flex items-center gap-2 mb-space-lg overflow-x-auto pb-2 scrollbar-thin">
        <a href="{{ route('admin.orders.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition-colors shadow-sm {{ !request('status') ? 'bg-primary text-on-primary' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span>Semua Pesanan</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ !request('status') ? 'bg-white/20 text-white' : 'bg-outline-variant/40 text-on-surface' }}">
                {{ $counts['all'] }}
            </span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition-colors shadow-sm {{ request('status') === 'pending' ? 'bg-amber-600 text-white' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">hourglass_empty</span>
            <span>Menunggu</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'pending' ? 'bg-white/20 text-white' : 'bg-amber-100 text-amber-900 font-bold' }}">
                {{ $counts['pending'] }}
            </span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition-colors shadow-sm {{ request('status') === 'shipped' ? 'bg-blue-600 text-white' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">local_shipping</span>
            <span>Dikirim</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'shipped' ? 'bg-white/20 text-white' : 'bg-blue-100 text-blue-900 font-bold' }}">
                {{ $counts['shipped'] }}
            </span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'ready_pickup']) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition-colors shadow-sm {{ request('status') === 'ready_pickup' ? 'bg-purple-700 text-white' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">storefront</span>
            <span>Siap Diambil</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'ready_pickup' ? 'bg-white/20 text-white' : 'bg-purple-100 text-purple-900 font-bold' }}">
                {{ $counts['ready_pickup'] }}
            </span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition-colors shadow-sm {{ request('status') === 'completed' ? 'bg-green-700 text-white' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">task_alt</span>
            <span>Selesai</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'completed' ? 'bg-white/20 text-white' : 'bg-green-100 text-green-900 font-bold' }}">
                {{ $counts['completed'] }}
            </span>
        </a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold tracking-wider uppercase transition-colors shadow-sm {{ request('status') === 'cancelled' ? 'bg-red-700 text-white' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[14px]">cancel</span>
            <span>Pembatalan Pesanan</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] {{ request('status') === 'cancelled' ? 'bg-white/20 text-white' : 'bg-red-100 text-red-900 font-bold' }}">
                {{ $counts['cancelled'] }}
            </span>
        </a>
    </div>

    {{-- ══ Toolbar Filter & Search ══ --}}
    <form method="GET" action="{{ route('admin.orders.index') }}"
          class="w-full max-w-full flex flex-col lg:flex-row gap-space-md mb-space-lg bg-surface-container-lowest rounded-xl shadow-sm p-space-md border border-outline-variant min-w-0">

        {{-- Pertahankan filter status jika ada --}}
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}"/>
        @endif

        {{-- Search Input --}}
        <div class="relative flex-1 min-w-0 w-full">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari No. Pesanan, Resi, Nama, atau No. Telepon..."
                   class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
        </div>

        {{-- Filter Kurir --}}
        <div class="relative w-full sm:w-auto">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">local_shipping</span>
            <select name="courier" onchange="this.form.submit()"
                    class="w-full sm:w-auto sm:min-w-[170px] pl-9 pr-8 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer">
                <option value="">Semua Kurir</option>
                <option value="JNE" {{ request('courier') === 'JNE' ? 'selected' : '' }}>JNE</option>
                <option value="J&T Express" {{ request('courier') === 'J&T Express' ? 'selected' : '' }}>J&T Express</option>
                <option value="Ambil Mandiri" {{ request('courier') === 'Ambil Mandiri' ? 'selected' : '' }}>Ambil Mandiri</option>
            </select>
            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-space-sm flex-shrink-0 w-full sm:w-auto">
            <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1 px-space-lg py-2.5 bg-primary hover:bg-secondary text-on-primary text-sm font-bold tracking-wider rounded-lg transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
            @if(request('search') || request('courier') || request('status'))
            <a href="{{ route('admin.orders.index') }}"
               class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1 px-space-md py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-sm font-semibold rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
                Reset
            </a>
            @endif
        </div>
    </form>

    {{-- ══ Tabel Pesanan ══ --}}
    <div class="w-full max-w-full bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant min-w-0">
        <div class="w-full max-w-full overflow-x-auto">
            <table class="w-full min-w-[980px] text-left border-collapse table-auto">
                <thead>
                    <tr class="bg-primary text-on-primary text-xs tracking-wider uppercase">
                        <th class="px-4 py-3.5 text-center" style="width:50px">No</th>
                        <th class="px-4 py-3.5" style="width:170px">No. Pesanan & Tgl</th>
                        <th class="px-4 py-3.5" style="width:200px">Pelanggan</th>
                        <th class="px-4 py-3.5">Produk Batik</th>
                        <th class="px-4 py-3.5 text-right" style="width:160px">Total Bayar</th>
                        <th class="px-4 py-3.5" style="width:160px">Kurir & Resi</th>
                        <th class="px-4 py-3.5 text-center" style="width:120px">Status</th>
                        <th class="px-4 py-3.5 text-center" style="width:150px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container text-sm">
                    @forelse($orders as $i => $order)
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        {{-- No --}}
                        <td class="px-4 py-3.5 text-center font-bold text-outline">
                            {{ $orders->firstItem() + $loop->index }}
                        </td>

                        {{-- No. Pesanan & Tanggal --}}
                        <td class="px-4 py-3.5">
                            <span class="font-mono font-bold text-primary block leading-tight">
                                {{ $order->order_number }}
                            </span>
                            <span class="text-xs text-on-surface-variant block mt-1 device-time" data-utc="{{ $order->created_at->toISOString() }}">
                                {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                            </span>
                        </td>

                        {{-- Pelanggan & Alamat --}}
                        <td class="px-4 py-3.5">
                            <p class="font-semibold text-primary leading-tight">{{ $order->customer_name ?: ($order->user?->name ?? 'Pelanggan Tamu') }}</p>
                            <p class="text-xs text-on-surface-variant mt-0.5 truncate">{{ $order->user?->email }}</p>
                            <p class="text-xs text-secondary mt-0.5 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[13px]">phone</span>
                                {{ $order->customer_phone ?: ($order->user?->phone ?? '—') }}
                            </p>
                            {{-- Alamat (Klik untuk lihat full, sama seperti manajemen user) --}}
                            @if($order->customer_address)
                                <button type="button"
                                        onclick="showAlamat({{ json_encode($order->customer_name ?: ($order->user?->name ?? 'Pelanggan')) }}, {{ json_encode($order->customer_address) }})"
                                        class="text-left text-xs text-secondary hover:text-primary transition-colors flex items-center gap-1 group mt-1.5"
                                        title="Klik untuk lihat alamat lengkap">
                                    <span class="material-symbols-outlined text-[14px] text-outline group-hover:text-secondary flex-shrink-0">home_pin</span>
                                    <span class="underline decoration-dotted underline-offset-2 truncate max-w-[170px]">{{ Str::limit($order->customer_address, 25) }}</span>
                                </button>
                            @else
                                <span class="text-[11px] text-outline italic block mt-1">Alamat belum diatur</span>
                            @endif
                        </td>

                        {{-- Item Produk --}}
                        <td class="px-4 py-3.5">
                            <div class="flex flex-col gap-1 max-w-[280px]">
                                @foreach($order->items as $item)
                                    <div class="flex items-center justify-between text-xs py-0.5 border-b border-surface-container last:border-0">
                                        <span class="font-medium text-on-surface truncate pr-2">
                                            {{ $item->product?->name ?? 'Produk Dihapus' }}
                                        </span>
                                        <span class="text-on-surface-variant font-mono flex-shrink-0">
                                            x{{ $item->quantity }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- Total Bayar & Metode --}}
                        <td class="px-4 py-3.5 text-right">
                            <span class="font-bold text-primary font-title-lg block">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                            <span class="text-[11px] font-semibold text-outline uppercase tracking-wider block mt-0.5">
                                {{ str_replace('_', ' ', $order->payment_method) }}
                            </span>
                            <span class="text-[10px] text-on-surface-variant block">
                                (Ongkir: Rp {{ number_format($order->shipping_cost, 0, ',', '.') }})
                            </span>
                        </td>

                        {{-- Kurir & Resi (Khusus Ambil Mandiri tidak ada resi) --}}
                        <td class="px-4 py-3.5">
                            @if($order->shipping_courier === 'Ambil Mandiri')
                                <div class="flex items-center gap-1 font-semibold text-purple-800 text-xs">
                                    <span class="material-symbols-outlined text-[16px] text-purple-700">storefront</span>
                                    <span>Ambil Mandiri</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1 font-semibold text-on-surface text-xs">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">local_shipping</span>
                                    <span>{{ $order->shipping_courier }}</span>
                                </div>
                                @if($order->tracking_number)
                                    <div class="mt-1 flex items-center gap-1 font-mono text-[11px] bg-surface-container px-2 py-0.5 rounded border border-outline-variant w-fit" title="Nomor Resi">
                                        <span class="material-symbols-outlined text-[12px] text-outline">barcode</span>
                                        <span class="text-primary font-bold">{{ $order->tracking_number }}</span>
                                    </div>
                                @else
                                    <span class="text-[11px] text-outline italic block mt-1">Belum ada resi</span>
                                @endif
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3.5 text-center">
                            @if($order->status === 'pending')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-300 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span>
                                    Menunggu
                                </span>
                            @elseif($order->status === 'shipped' && $order->shipping_courier === 'Ambil Mandiri')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-800 border border-purple-300 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                                    Siap Diambil
                                </span>
                                <div class="mt-1 text-[10px] text-purple-900 leading-tight">
                                    <span>Sejak: <strong class="device-time" data-utc="{{ $order->shipped_at?->toISOString() }}">{{ $order->shipped_at ? $order->shipped_at->translatedFormat('d/m/y') : '-' }}</strong></span>
                                    <span class="block text-amber-800 font-bold bg-amber-50 px-1 py-0.5 rounded border border-amber-200 mt-0.5">
                                        Sisa {{ $order->remaining_days }} hari
                                    </span>
                                </div>
                            @elseif($order->status === 'shipped')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    Dikirim
                                </span>
                                <div class="mt-1 text-[10px] text-blue-900 leading-tight">
                                    <span>Kirim: <strong class="device-time" data-utc="{{ $order->shipped_at?->toISOString() }}">{{ $order->shipped_at ? $order->shipped_at->translatedFormat('d/m/y') : '-' }}</strong></span>
                                    <span class="block text-amber-800 font-bold bg-amber-50 px-1 py-0.5 rounded border border-amber-200 mt-0.5">
                                        Sisa {{ $order->remaining_days }} hari
                                    </span>
                                </div>
                            @elseif($order->status === 'completed')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                    Selesai
                                </span>
                            @elseif($order->status === 'cancelled')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-300 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                    Dibatalkan
                                </span>
                                @if($order->cancellation_reason)
                                    <button type="button"
                                            onclick="showAlasanBatal({{ json_encode($order->order_number) }}, {{ json_encode($order->cancellation_reason) }}, {{ json_encode($order->cancelled_at ? $order->cancelled_at->translatedFormat('d F Y, H:i') : '') }})"
                                            class="block text-[10px] text-red-700 hover:text-red-900 underline mt-1 mx-auto font-medium cursor-pointer"
                                            title="Lihat Alasan Pembatalan">
                                        Alasan Batal
                                    </button>
                                @endif
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                {{-- 1. WhatsApp Hybrid Confirmation Button --}}
                                @if($order->whatsapp_url)
                                    <a href="{{ $order->whatsapp_url }}" target="_blank"
                                       class="w-8 h-8 rounded-lg bg-green-600 hover:bg-green-700 text-white flex items-center justify-center transition-colors shadow-sm"
                                       title="Konfirmasi Pesanan ke WhatsApp Pelanggan">
                                        <span class="material-symbols-outlined text-[16px]">chat</span>
                                    </a>
                                @endif

                                {{-- 2. Edit Status & Resi Modal Trigger --}}
                                <button type="button"
                                        onclick="openStatusModal({{ $order->id }}, '{{ $order->order_number }}', '{{ $order->status }}', '{{ addslashes($order->tracking_number ?? '') }}', '{{ addslashes($order->shipping_courier) }}')"
                                        class="w-8 h-8 rounded-lg bg-surface-container hover:bg-primary hover:text-white text-primary flex items-center justify-center transition-colors cursor-pointer"
                                        title="Ubah Status & Input Resi">
                                    <span class="material-symbols-outlined text-[16px]">edit_note</span>
                                </button>

                                {{-- 3. Batalkan Pesanan (Stok Kembali & Masuk Tab Pembatalan) --}}
                                @if($order->status !== 'cancelled')
                                <button type="button"
                                        onclick="openCancelModal({{ $order->id }}, '{{ $order->order_number }}')"
                                        class="w-8 h-8 rounded-lg bg-surface-container hover:bg-red-700 hover:text-white text-red-600 flex items-center justify-center transition-colors cursor-pointer"
                                        title="Batalkan Pesanan (Stok Kembali & Masuk Tab Pembatalan)">
                                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                                </button>
                                @else
                                <button type="button"
                                        onclick="showAlasanBatal({{ json_encode($order->order_number) }}, {{ json_encode($order->cancellation_reason) }}, {{ json_encode($order->cancelled_at ? $order->cancelled_at->translatedFormat('d F Y, H:i') : '') }})"
                                        class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-700 flex items-center justify-center transition-colors cursor-pointer"
                                        title="Lihat Alasan Pembatalan">
                                    <span class="material-symbols-outlined text-[16px]">info</span>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-space-lg py-16 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-[64px] text-outline block mb-3">receipt_long</span>
                            <p class="font-body-md text-body-md font-medium">
                                @if(request('search') || request('status') || request('courier'))
                                    Tidak ada pesanan yang sesuai dengan filter pencarian.
                                @else
                                    Belum ada data pesanan tersimpan.
                                @endif
                            </p>
                            <div class="mt-4">
                                <a href="{{ route('admin.orders.create') }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-secondary text-on-primary font-bold text-xs uppercase tracking-wider rounded-lg transition-colors shadow-sm">
                                    <span class="material-symbols-outlined text-[16px]">add</span>
                                    Input Pesanan Sekarang
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="px-space-lg py-3 border-t border-surface-container bg-surface-container-low overflow-x-auto">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ══ MODAL LIHAT ALAMAT (Sama seperti Manajemen User) ══ --}}
<div id="modalAlamat" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-md w-full max-w-[calc(100vw-2rem)] rounded-xl shadow-2xl overflow-hidden border border-outline-variant">
        <div class="bg-primary px-space-lg py-space-md text-on-primary flex items-center justify-between">
            <div class="flex items-center gap-2 font-bold text-sm">
                <span class="material-symbols-outlined text-[20px]">home_pin</span>
                <span id="alamatTitle">Alamat Pengiriman</span>
            </div>
            <button onclick="document.getElementById('modalAlamat').classList.add('hidden')" class="text-on-primary/70 hover:text-on-primary">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <div class="p-space-lg flex flex-col gap-space-md">
            <p id="alamatContent" class="text-sm text-on-surface leading-relaxed whitespace-pre-line"></p>
            <div class="flex justify-end pt-3 border-t border-surface-container">
                <button type="button" onclick="document.getElementById('modalAlamat').classList.add('hidden')"
                        class="px-4 py-2 bg-primary hover:bg-secondary text-on-primary text-xs font-bold uppercase rounded-lg transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════ MODAL UBAH STATUS & INPUT RESI ══════════ --}}
<div id="modalStatus" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-md w-full max-w-[calc(100vw-2rem)] rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-primary px-space-lg py-space-md flex items-center justify-between text-on-primary">
            <h3 class="font-headline-sm text-headline-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">edit_note</span>
                <span>Update Status Pesanan</span>
            </h3>
            <button onclick="document.getElementById('modalStatus').classList.add('hidden')" class="hover:text-tertiary-fixed">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <form id="formStatus" method="POST" class="p-space-lg flex flex-col gap-space-md">
            @csrf
            @method('PATCH')

            <div>
                <span class="text-xs text-outline uppercase font-bold block mb-0.5">Nomor Pesanan</span>
                <span id="modalOrderNumber" class="font-mono text-base font-bold text-primary"></span>
            </div>

            <div>
                <label for="modalStatusSelect" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                    Status Pesanan <span class="text-error">*</span>
                </label>
                <select name="status" id="modalStatusSelect" required
                        class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="pending">Pending (Menunggu)</option>
                    <option value="shipped" id="modalShippedOption">Shipped (Dalam Pengiriman)</option>
                    <option value="completed">Completed (Selesai)</option>
                    <option value="cancelled">Cancelled (Dibatalkan)</option>
                </select>
            </div>

            {{-- Resi Input (Khusus kurir ekspedisi, Ambil Mandiri otomatis disembunyikan) --}}
            <div id="modalTrackingWrap">
                <label for="modalTrackingInput" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                    Nomor Resi Pengiriman (AWB)
                </label>
                <input type="text" name="tracking_number" id="modalTrackingInput"
                       placeholder="Contoh: JNE892301928"
                       class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary"/>
                <p class="text-[11px] text-on-surface-variant mt-1">Wajib diisi saat pesanan berstatus dikirim agar pelanggan dapat melacak paket.</p>
            </div>

            {{-- Peringatan Otomatis Selesai (21 Hari / 12 Hari) --}}
            <div id="modalDeadlineNotice" class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-xs text-amber-950 flex items-start gap-2">
                <span class="material-symbols-outlined text-[18px] text-amber-700 flex-shrink-0 mt-0.5">info</span>
                <div id="modalDeadlineNoticeText" class="space-y-0.5 leading-relaxed"></div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-surface-container">
                <button type="button" onclick="document.getElementById('modalStatus').classList.add('hidden')"
                        class="px-4 py-2 bg-surface-container text-on-surface text-xs font-bold uppercase rounded-lg hover:bg-surface-container-high transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-primary hover:bg-secondary text-on-primary text-xs font-bold uppercase rounded-lg transition-colors shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ MODAL BATALKAN PESANAN (TIDAK HAPUS, STOK KEMBALI, MASUK PEMBATALAN) ══════════ --}}
<div id="modalCancel" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-md w-full max-w-[calc(100vw-2rem)] rounded-2xl shadow-2xl overflow-hidden border border-outline-variant">
        <div class="bg-gradient-to-r from-red-700 to-red-800 px-space-lg py-space-md text-white flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[22px]">cancel</span>
                <span>Batalkan Pesanan</span>
            </h3>
            <button type="button" onclick="document.getElementById('modalCancel').classList.add('hidden')" class="hover:opacity-80 cursor-pointer">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <form id="formCancel" method="POST" class="p-space-lg flex flex-col gap-4">
            @csrf
            @method('PATCH')

            <div>
                <span class="text-xs text-outline uppercase font-bold block mb-0.5">Nomor Pesanan</span>
                <span id="cancelOrderNumber" class="font-mono text-base font-bold text-primary"></span>
            </div>

            <div>
                <label for="cancelReasonInput" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                    Keterangan Alasan Pembatalan <span class="text-error">*</span>
                </label>
                <textarea name="reason" id="cancelReasonInput" rows="3" required
                          placeholder="Tuliskan keterangan mengapa pesanan dibatalkan (misal: Permintaan pembatalan dari pelanggan via WhatsApp / Stok kain tidak tersedia / Kendala pembayaran)..."
                          class="w-full px-3 py-2.5 bg-surface-container-low border border-outline-variant rounded-xl text-xs sm:text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-red-600 placeholder:text-outline"></textarea>
            </div>

            <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-950 text-xs flex items-start gap-2.5">
                <span class="material-symbols-outlined text-[20px] text-red-700 flex-shrink-0 mt-0.5">info</span>
                <div class="leading-relaxed">
                    <span>Pesanan ini <strong>tidak akan terhapus</strong>, melainkan disimpan dengan status <strong>Dibatalkan</strong> dan akan muncul di tab <strong>Pembatalan Pesanan</strong>.</span>
                    <span class="block mt-1 font-semibold text-green-800">✅ Stok semua produk akan secara otomatis dikembalikan ke inventaris.</span>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-surface-container">
                <button type="button" onclick="document.getElementById('modalCancel').classList.add('hidden')"
                        class="px-4 py-2 bg-surface-container hover:bg-surface-container-high text-on-surface text-xs font-bold uppercase rounded-xl transition-colors cursor-pointer">
                    Kembali
                </button>
                <button type="submit"
                        class="px-5 py-2.5 bg-red-700 hover:bg-red-800 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-colors shadow-sm flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                    <span>Konfirmasi Batalkan Pesanan</span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ MODAL LIHAT ALASAN PEMBATALAN ══════════ --}}
<div id="modalLihatAlasan" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-md w-full max-w-[calc(100vw-2rem)] rounded-2xl shadow-2xl overflow-hidden border border-outline-variant">
        <div class="bg-gradient-to-r from-red-700 to-red-800 px-space-lg py-space-md text-white flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[22px]">info</span>
                <span>Alasan Pembatalan Pesanan</span>
            </h3>
            <button type="button" onclick="document.getElementById('modalLihatAlasan').classList.add('hidden')" class="hover:opacity-80 cursor-pointer">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <div class="p-space-lg flex flex-col gap-3">
            <div>
                <span class="text-xs text-outline uppercase font-bold block mb-0.5">Nomor Pesanan</span>
                <span id="lihatAlasanOrderNumber" class="font-mono text-base font-bold text-primary"></span>
                <span id="lihatAlasanDate" class="text-xs text-on-surface-variant block mt-0.5"></span>
            </div>

            <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-950 text-xs">
                <span class="font-bold text-red-800 uppercase tracking-wider block mb-1">Keterangan / Alasan:</span>
                <p id="lihatAlasanContent" class="text-xs text-on-surface whitespace-pre-line leading-relaxed font-medium"></p>
            </div>

            <div class="flex justify-end pt-3 border-t border-surface-container">
                <button type="button" onclick="document.getElementById('modalLihatAlasan').classList.add('hidden')"
                        class="px-4 py-2 bg-primary hover:bg-secondary text-on-primary text-xs font-bold uppercase rounded-xl transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════ MODAL HAPUS PESANAN ══════════ --}}
<div id="modalDelete" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-sm w-full rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-error px-space-lg py-space-md text-white flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">warning</span>
                <span>Hapus Pesanan</span>
            </h3>
            <button onclick="document.getElementById('modalDelete').classList.add('hidden')" class="hover:opacity-80">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <form id="formDelete" method="POST" class="p-space-lg flex flex-col gap-3">
            @csrf
            @method('DELETE')

            <p class="text-sm text-on-surface leading-relaxed">
                Apakah Anda yakin ingin menghapus data pesanan <strong id="deleteOrderNumber" class="text-error font-mono"></strong>?
            </p>

            <div class="flex justify-end gap-2 pt-3 border-t border-surface-container mt-2">
                <button type="button" onclick="document.getElementById('modalDelete').classList.add('hidden')"
                        class="px-4 py-2 bg-surface-container text-on-surface text-xs font-bold uppercase rounded-lg hover:bg-surface-container-high transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-error hover:bg-red-700 text-white text-xs font-bold uppercase rounded-lg transition-colors shadow-sm">
                    Ya, Hapus Data
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Format tanggal & jam sesuai device masing-masing
    document.querySelectorAll('.device-time').forEach(function(el) {
        const utc = el.dataset.utc;
        if (utc) {
            const date = new Date(utc);
            if (!isNaN(date.getTime())) {
                const formattedDate = date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
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

    // Modal Lihat Alamat Lengkap
    function showAlamat(name, address) {
        document.getElementById('alamatTitle').textContent = 'Alamat Pengiriman ' + name;
        document.getElementById('alamatContent').textContent = address;
        document.getElementById('modalAlamat').classList.remove('hidden');
    }

    // Modal Batalkan Pesanan (Stok Kembali & Masuk Tab Pembatalan)
    function openCancelModal(id, orderNumber) {
        document.getElementById('formCancel').action = `/admin/orders/${id}/cancel`;
        document.getElementById('cancelOrderNumber').textContent = orderNumber;
        document.getElementById('cancelReasonInput').value = '';
        document.getElementById('modalCancel').classList.remove('hidden');
    }

    // Modal Lihat Alasan Pembatalan
    function showAlasanBatal(orderNumber, reason, cancelledAt) {
        document.getElementById('lihatAlasanOrderNumber').textContent = orderNumber;
        document.getElementById('lihatAlasanContent').textContent = reason || 'Tidak ada keterangan alasan.';
        document.getElementById('lihatAlasanDate').textContent = cancelledAt ? 'Dibatalkan pada: ' + cancelledAt : '';
        document.getElementById('modalLihatAlasan').classList.remove('hidden');
    }

    // Modal Update Status & Resi
    function openStatusModal(id, orderNumber, currentStatus, currentTracking, courier = '') {
        document.getElementById('formStatus').action = `/admin/orders/${id}/status`;
        document.getElementById('modalOrderNumber').textContent = orderNumber;
        document.getElementById('modalStatusSelect').value = currentStatus;

        const trackingWrap = document.getElementById('modalTrackingWrap');
        const trackingInput = document.getElementById('modalTrackingInput');
        const shippedOption = document.getElementById('modalShippedOption');
        const deadlineNoticeText = document.getElementById('modalDeadlineNoticeText');

        if (courier === 'Ambil Mandiri') {
            trackingWrap.classList.add('hidden');
            trackingInput.value = '';
            if (shippedOption) {
                shippedOption.textContent = 'Shipped (Siap Diambil)';
            }
            if (deadlineNoticeText) {
                deadlineNoticeText.innerHTML = '<strong>Aturan Ambil Mandiri:</strong> Ketika status diubah ke <em>Siap Diambil</em>, tanggal otomatis dicatat. Pelanggan diberi rentang <strong>12 hari</strong>. Jika melewati 12 hari belum diambil, pesanan akan secara <strong>otomatis diselesaikan sistem (Completed)</strong>.';
            }
        } else {
            trackingWrap.classList.remove('hidden');
            trackingInput.value = currentTracking || '';
            if (shippedOption) {
                shippedOption.textContent = 'Shipped (Dalam Pengiriman)';
            }
            if (deadlineNoticeText) {
                deadlineNoticeText.innerHTML = '<strong>Aturan Pengiriman Kurir:</strong> Ketika status diubah ke <em>Dikirim</em>, tanggal otomatis dicatat. Pelanggan diberi rentang <strong>21 hari</strong>. Jika melewati 21 hari tanpa konfirmasi diterima, pesanan akan secara <strong>otomatis diselesaikan sistem (Completed)</strong>.';
            }
        }

        document.getElementById('modalStatus').classList.remove('hidden');
    }

    // Modal Hapus Pesanan
    function openDeleteModal(id, orderNumber) {
        document.getElementById('formDelete').action = `/admin/orders/${id}`;
        document.getElementById('deleteOrderNumber').textContent = orderNumber;
        document.getElementById('modalDelete').classList.remove('hidden');
    }
</script>
@endpush
