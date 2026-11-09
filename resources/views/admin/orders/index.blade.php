@extends('layouts.app')
@section('title', 'Manajemen Pesanan — Juragan Batik')

@section('content')
{{-- ══ Admin Header ══ --}}
<div class="w-full bg-gradient-to-r from-primary via-primary-container to-secondary py-8">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md">
        <div>
            <span class="font-label-sm text-label-sm text-secondary-fixed uppercase tracking-[0.2em] block mb-1">PORTAL ADMINISTRASI</span>
            <h1 class="font-headline-md text-headline-md text-on-primary font-bold">
                Manajemen Pesanan
            </h1>
            <p class="font-body-sm text-body-sm text-primary-fixed-dim mt-1">
                Kelola pesanan pelanggan, nomor resi pengiriman, serta konfirmasi semi-otomatis via WhatsApp.
            </p>
        </div>
        <div class="flex items-center gap-space-sm flex-shrink-0">
            <a href="{{ route('admin.orders.create') }}"
               class="inline-flex items-center justify-center gap-space-xs px-space-md bg-surface-container-lowest hover:bg-surface text-primary font-label-lg text-label-lg rounded shadow-sm transition-colors h-10">
                <span class="material-symbols-outlined text-[20px] text-secondary">add_circle</span>
                <span>Input Pesanan Baru</span>
            </a>
        </div>
    </div>
</div>

<div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

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
    <div class="flex items-center gap-2 mb-space-lg overflow-x-auto pb-1">
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
    </div>

    {{-- ══ Toolbar Filter & Search ══ --}}
    <form method="GET" action="{{ route('admin.orders.index') }}"
          class="flex flex-col lg:flex-row gap-space-md mb-space-lg bg-surface-container-lowest rounded-xl shadow-sm p-space-md border border-outline-variant">

        {{-- Pertahankan filter status jika ada --}}
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}"/>
        @endif

        {{-- Search Input --}}
        <div class="relative flex-1 min-w-0">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari No. Pesanan, Resi, Nama, atau No. Telepon..."
                   class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
        </div>

        {{-- Filter Kurir --}}
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">local_shipping</span>
            <select name="courier" onchange="this.form.submit()"
                    class="pl-9 pr-8 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer min-w-[170px]">
                <option value="">Semua Kurir</option>
                <option value="JNE" {{ request('courier') === 'JNE' ? 'selected' : '' }}>JNE</option>
                <option value="J&T Express" {{ request('courier') === 'J&T Express' ? 'selected' : '' }}>J&T Express</option>
                <option value="Ambil Mandiri" {{ request('courier') === 'Ambil Mandiri' ? 'selected' : '' }}>Ambil Mandiri</option>
            </select>
            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
        </div>

        {{-- Tombol Aksi --}}
        <div class="flex gap-space-sm flex-shrink-0">
            <button type="submit"
                    class="inline-flex items-center gap-1 px-space-lg py-2.5 bg-primary hover:bg-secondary text-on-primary text-sm font-bold tracking-wider rounded-lg transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
            @if(request('search') || request('courier') || request('status'))
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center gap-1 px-space-md py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-sm font-semibold rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
                Reset
            </a>
            @endif
        </div>
    </form>

    {{-- ══ Tabel Pesanan ══ --}}
    <div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
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
                            @elseif($order->status === 'shipped')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-300 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                    Dikirim
                                </span>
                            @elseif($order->status === 'completed')
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-300 inline-flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                    Selesai
                                </span>
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
                                        class="w-8 h-8 rounded-lg bg-surface-container hover:bg-primary hover:text-white text-primary flex items-center justify-center transition-colors"
                                        title="Ubah Status & Input Resi">
                                    <span class="material-symbols-outlined text-[16px]">edit_note</span>
                                </button>

                                {{-- 3. Hapus Pesanan & Kembalikan Stok --}}
                                <button type="button"
                                        onclick="openDeleteModal({{ $order->id }}, '{{ $order->order_number }}')"
                                        class="w-8 h-8 rounded-lg bg-surface-container hover:bg-error hover:text-white text-error flex items-center justify-center transition-colors"
                                        title="Batalkan & Hapus Pesanan">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
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
        <div class="px-space-lg py-3 border-t border-surface-container bg-surface-container-low">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ══ MODAL LIHAT ALAMAT (Sama seperti Manajemen User) ══ --}}
<div id="modalAlamat" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-md w-full rounded-xl shadow-2xl overflow-hidden border border-outline-variant">
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
<div id="modalStatus" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-md w-full rounded-xl shadow-2xl overflow-hidden">
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

{{-- ══════════ MODAL HAPUS PESANAN ══════════ --}}
<div id="modalDelete" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-sm w-full rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-error px-space-lg py-space-md text-white flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">warning</span>
                <span>Batalkan Pesanan</span>
            </h3>
            <button onclick="document.getElementById('modalDelete').classList.add('hidden')" class="hover:opacity-80">
                <span class="material-symbols-outlined text-[22px]">close</span>
            </button>
        </div>

        <form id="formDelete" method="POST" class="p-space-lg flex flex-col gap-3">
            @csrf
            @method('DELETE')

            <p class="text-sm text-on-surface leading-relaxed">
                Apakah Anda yakin ingin membatalkan & menghapus pesanan <strong id="deleteOrderNumber" class="text-error font-mono"></strong>?
            </p>

            <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg text-amber-900 text-xs flex items-start gap-2">
                <span class="material-symbols-outlined text-[16px] text-amber-700 flex-shrink-0 mt-0.5">inventory</span>
                <span>Stok semua produk dalam pesanan ini akan <strong>secara otomatis dikembalikan</strong> ke inventaris.</span>
            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-surface-container mt-2">
                <button type="button" onclick="document.getElementById('modalDelete').classList.add('hidden')"
                        class="px-4 py-2 bg-surface-container text-on-surface text-xs font-bold uppercase rounded-lg hover:bg-surface-container-high transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-error hover:bg-red-700 text-white text-xs font-bold uppercase rounded-lg transition-colors shadow-sm">
                    Ya, Batalkan Pesanan
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

    // Modal Update Status & Resi
    function openStatusModal(id, orderNumber, currentStatus, currentTracking, courier = '') {
        document.getElementById('formStatus').action = `/admin/orders/${id}/status`;
        document.getElementById('modalOrderNumber').textContent = orderNumber;
        document.getElementById('modalStatusSelect').value = currentStatus;

        const trackingWrap = document.getElementById('modalTrackingWrap');
        const trackingInput = document.getElementById('modalTrackingInput');
        const shippedOption = document.getElementById('modalShippedOption');

        if (courier === 'Ambil Mandiri') {
            trackingWrap.classList.add('hidden');
            trackingInput.value = '';
            if (shippedOption) {
                shippedOption.textContent = 'Shipped (Siap Diambil)';
            }
        } else {
            trackingWrap.classList.remove('hidden');
            trackingInput.value = currentTracking || '';
            if (shippedOption) {
                shippedOption.textContent = 'Shipped (Dalam Pengiriman)';
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
