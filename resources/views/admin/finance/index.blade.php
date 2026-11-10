@extends('layouts.app')
@section('title', 'Manajemen Keuangan & Rekap Penjualan — Juragan Batik')

@section('content')
{{-- ══ Admin Header ══ --}}
<div class="w-full bg-gradient-to-r from-primary via-primary-container to-secondary py-8 overflow-hidden">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md min-w-0">
        <div>
            <span class="font-label-sm text-label-sm text-secondary-fixed uppercase tracking-[0.2em] block mb-1">PORTAL ADMINISTRASI</span>
            <h1 class="font-headline-md text-headline-md text-on-primary font-bold">
                Laporan & Manajemen Keuangan
            </h1>
            <p class="font-body-sm text-body-sm text-primary-fixed-dim mt-1">
                Pantau pendapatan masuk (selesai diterima), nominal tertunda (pending), serta rekap penjualan kain batik siap ekspor ke Excel.
            </p>
        </div>
        <div class="flex items-center gap-space-sm flex-shrink-0 w-full sm:w-auto">
            <button type="button"
               id="exportBtn"
               onclick="downloadFinanceExcel()"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-space-xs px-space-md bg-emerald-700 hover:bg-emerald-800 text-white font-label-lg text-label-lg rounded-xl sm:rounded shadow-sm transition-all h-10 cursor-pointer">
                <span class="material-symbols-outlined text-[20px]">table_view</span>
                <span>Download Rekap Excel (.xls)</span>
            </button>
        </div>
    </div>
</div>

<div class="w-full max-w-[1440px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

    {{-- ══ KARTU RINGKASAN METRIK NOMINAL ══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-space-lg min-w-0">

        {{-- 1. Pendapatan Masuk (Completed) --}}
        <div class="p-4 bg-emerald-50/70 border border-emerald-200 rounded-2xl shadow-sm flex items-center justify-between gap-3 min-w-0">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Pendapatan Masuk</span>
                <span class="font-serif text-xl sm:text-2xl font-bold text-emerald-950 mt-1">
                    Rp {{ number_format($totalPendapatanMasuk, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-emerald-700 mt-0.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                    <span>{{ $countPendapatanMasuk }} pesanan selesai (diterima)</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">account_balance_wallet</span>
            </div>
        </div>

        {{-- 2. Pendapatan Pending (Belum Terima) --}}
        <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-2xl shadow-sm flex items-center justify-between gap-3 min-w-0">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] font-bold text-amber-800 uppercase tracking-wider">Nominal Pending</span>
                <span class="font-serif text-xl sm:text-2xl font-bold text-amber-950 mt-1">
                    Rp {{ number_format($totalPendapatanPending, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-amber-700 mt-0.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    <span>{{ $countPendapatanPending }} pesanan belum selesai</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">hourglass_top</span>
            </div>
        </div>

        {{-- 3. Total Kain Batik Terjual --}}
        <div class="p-4 bg-surface-container-low border border-outline-variant/60 rounded-2xl shadow-sm flex items-center justify-between gap-3 min-w-0">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] font-bold text-secondary uppercase tracking-wider"> Batik Terjual</span>
                <span class="font-serif text-xl sm:text-2xl font-bold text-primary mt-1">
                    {{ number_format($totalPcsTerjual, 0, ',', '.') }} <span class="text-xs font-sans font-normal text-outline">pcs</span>
                </span>
                <span class="text-[11px] text-on-surface-variant mt-0.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">checkroom</span>
                    <span>{{ $countPendapatanMasuk }} pesanan selesai (diterima)</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-secondary text-on-secondary flex items-center justify-center flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">shopping_bag</span>
            </div>
        </div>

        {{-- 4. Transaksi Dibatalkan --}}
        <div class="p-4 bg-red-50/70 border border-red-200 rounded-2xl shadow-sm flex items-center justify-between gap-3 min-w-0">
            <div class="flex flex-col min-w-0">
                <span class="text-[11px] font-bold text-red-800 uppercase tracking-wider">Dibatalkan</span>
                <span class="font-serif text-xl sm:text-2xl font-bold text-red-950 mt-1">
                    Rp {{ number_format($totalDibatalkan, 0, ',', '.') }}
                </span>
                <span class="text-[11px] text-red-700 mt-0.5 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">cancel</span>
                    <span>{{ $countDibatalkan }} pesanan batal</span>
                </span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-red-600 text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                <span class="material-symbols-outlined text-[26px]">remove_shopping_cart</span>
            </div>
        </div>

    </div>

    {{-- ══ FILTER & SEARCH ══ --}}
    <div class="w-full max-w-full bg-surface-container-lowest p-4 rounded-2xl shadow-sm border border-outline-variant/60 mb-space-md min-w-0">
        <form action="{{ route('admin.finance.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-end">
            {{-- Rentang Tanggal Mulai --}}
            <div class="lg:col-span-3">
                <label class="block text-[11px] font-bold text-outline uppercase tracking-wider mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}"
                       class="w-full text-xs py-2 px-3 rounded-xl border border-outline-variant bg-surface-container-low text-on-surface focus:outline-none focus:border-secondary transition">
            </div>

            {{-- Rentang Tanggal Akhir --}}
            <div class="lg:col-span-3">
                <label class="block text-[11px] font-bold text-outline uppercase tracking-wider mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}"
                       class="w-full text-xs py-2 px-3 rounded-xl border border-outline-variant bg-surface-container-low text-on-surface focus:outline-none focus:border-secondary transition">
            </div>

            {{-- Filter Status Keuangan --}}
            <div class="lg:col-span-3">
                <label class="block text-[11px] font-bold text-outline uppercase tracking-wider mb-1">Status Keuangan</label>
                <select name="finance_status" class="w-full text-xs py-2 px-3 rounded-xl border border-outline-variant bg-surface-container-low text-on-surface focus:outline-none focus:border-secondary transition">
                    <option value="all" {{ request('finance_status') === 'all' || !request('finance_status') ? 'selected' : '' }}>Semua Status</option>
                    <option value="masuk" {{ request('finance_status') === 'masuk' ? 'selected' : '' }}>Pendapatan Masuk (Selesai)</option>
                    <option value="pending" {{ request('finance_status') === 'pending' ? 'selected' : '' }}>Pending (Belum Diterima)</option>
                    <option value="cancelled" {{ request('finance_status') === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            {{-- Search Box & Submit --}}
            <div class="sm:col-span-2 lg:col-span-3 flex flex-col sm:flex-row gap-2">
                <div class="flex-1 w-full min-w-0">
                    <label class="block text-[11px] font-bold text-outline uppercase tracking-wider mb-1">Cari Transaksi / Motif</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="No. pesanan, nama, motif..."
                           class="w-full text-xs py-2 px-3 rounded-xl border border-outline-variant bg-surface-container-low text-on-surface focus:outline-none focus:border-secondary transition">
                </div>
                <div class="flex items-center gap-2 mt-auto">
                    <button type="submit" class="flex-1 sm:flex-initial h-9 px-4 rounded-xl bg-primary hover:bg-secondary text-on-primary text-xs font-bold transition flex items-center justify-center gap-1 shadow-sm flex-shrink-0 cursor-pointer" title="Terapkan Filter">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        <span>Terapkan</span>
                    </button>
                    @if(request()->hasAny(['start_date', 'end_date', 'finance_status', 'search']))
                    <a href="{{ route('admin.finance.index') }}" class="h-9 px-3 rounded-xl border border-outline-variant hover:bg-surface-container text-on-surface-variant text-xs font-bold transition flex items-center justify-center flex-shrink-0" title="Reset Filter">
                        <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ══ TABEL DATA KEUANGAN & RINCIAN BARANG TERJUAL (OPSI 1 ROWSPAN MERGED) ══ --}}
    <div class="w-full max-w-full bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/60 overflow-hidden min-w-0">
        <div class="w-full max-w-full overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left border-collapse text-xs table-auto">
                <thead>
                    <tr class="bg-surface-container-low text-outline uppercase tracking-wider text-[11px] border-b border-outline-variant">
                        <th class="py-3 px-3 text-center w-12">No</th>
                        <th class="py-3 px-3 w-28">Tanggal</th>
                        <th class="py-3 px-3 w-40">No. Pesanan</th>
                        <th class="py-3 px-3 min-w-[140px]">Pelanggan</th>
                        <th class="py-3 px-3 min-w-[200px]">Nama Barang / Motif Batik</th>
                        <th class="py-3 px-3 w-28 text-center">Kategori</th>
                        <th class="py-3 px-3 text-right w-28">Harga Satuan</th>
                        <th class="py-3 px-3 text-center w-20">Qty</th>
                        <th class="py-3 px-3 text-right w-32">Subtotal Barang</th>
                        <th class="py-3 px-3 text-right min-w-[130px] bg-surface-container/60">Total Transaksi</th>
                        <th class="py-3 px-3 text-center min-w-[140px] bg-surface-container/60">Status Keuangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/40">
                    @forelse($orders as $orderIndex => $order)
                        @php
                            $items = $order->items;
                            $rowCount = max(1, $items->count());
                            $customerName  = $order->customer_name ?: ($order->user?->name ?? 'Pelanggan');
                            $customerPhone = $order->customer_phone ?: ($order->user?->phone ?? '-');

                            // Status keuangan
                            $isMasuk     = $order->status === 'completed';
                            $isCancelled = $order->status === 'cancelled';
                            $isPending   = !$isMasuk && !$isCancelled;
                        @endphp

                        @if($items->isEmpty())
                            {{-- Pesanan tanpa rincian item --}}
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="py-3 px-3 text-center font-bold text-outline">{{ $orders->firstItem() + $orderIndex }}</td>
                                <td class="py-3 px-3 text-on-surface-variant whitespace-nowrap">{{ $order->created_at->format('d/m/Y') }}<br><span class="text-[10px] text-outline">{{ $order->created_at->format('H:i') }}</span></td>
                                <td class="py-3 px-3 font-mono font-bold text-primary">{{ $order->order_number }}</td>
                                <td class="py-3 px-3">
                                    <span class="font-bold text-primary block truncate">{{ $customerName }}</span>
                                    <span class="text-[10px] text-outline">{{ $customerPhone }}</span>
                                </td>
                                <td colspan="5" class="py-3 px-3 text-center text-outline italic">Tidak ada item rincian</td>
                                <td class="py-3 px-3 text-right font-bold text-primary bg-surface-container-lowest">
                                    Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-3 text-center bg-surface-container-lowest">
                                    @if($isMasuk)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Pendapatan Masuk
                                        </span>
                                    @elseif($isCancelled)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span> Dibatalkan
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @else
                            {{-- Pesanan dengan 1 atau lebih barang (Model Rowspan Merged Sel Kebawah) --}}
                            @foreach($items as $itemIndex => $item)
                                <tr class="hover:bg-surface-container-lowest transition-colors {{ $itemIndex === 0 && $orderIndex > 0 ? 'border-t-2 border-outline-variant/70' : '' }}">
                                    @if($itemIndex === 0)
                                        {{-- Kolom yang digabung vertikal (rowspan) sesuai jumlah produk --}}
                                        <td rowspan="{{ $rowCount }}" class="py-3 px-3 text-center font-bold text-outline align-top bg-surface-container-lowest border-r border-outline-variant/30">
                                            {{ $orders->firstItem() + $orderIndex }}
                                        </td>
                                        <td rowspan="{{ $rowCount }}" class="py-3 px-3 text-on-surface-variant whitespace-nowrap align-top bg-surface-container-lowest border-r border-outline-variant/30">
                                            <span class="font-semibold text-primary">{{ $order->created_at->format('d/m/Y') }}</span>
                                            <span class="block text-[10px] text-outline">{{ $order->created_at->format('H:i') }} WIB</span>
                                        </td>
                                        <td rowspan="{{ $rowCount }}" class="py-3 px-3 font-mono font-bold text-primary align-top bg-surface-container-lowest border-r border-outline-variant/30">
                                            <a href="{{ route('admin.orders.index', ['search' => $order->order_number]) }}" class="hover:text-secondary underline" title="Buka di Manajemen Pesanan">
                                                {{ $order->order_number }}
                                            </a>
                                            <span class="block text-[10px] font-sans font-normal text-outline mt-0.5">
                                                {{ $order->shipping_courier }}
                                            </span>
                                        </td>
                                        <td rowspan="{{ $rowCount }}" class="py-3 px-3 align-top bg-surface-container-lowest border-r border-outline-variant/30">
                                            <span class="font-bold text-primary block truncate max-w-[150px]">{{ $customerName }}</span>
                                            <span class="text-[10px] text-outline block">{{ $customerPhone }}</span>
                                        </td>
                                    @endif

                                    {{-- Kolom Spesifik Per Barang --}}
                                    <td class="py-2.5 px-3">
                                        <div class="flex items-center gap-2">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ Storage::url($item->product->image) }}" alt="{{ $item->product->name }}" class="w-8 h-8 rounded-md object-cover flex-shrink-0">
                                            @endif
                                            <div class="min-w-0">
                                                <span class="font-bold text-primary block truncate max-w-[220px]">
                                                    {{ $item->product?->name ?? 'Produk Dihapus' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="px-2 py-0.5 rounded-full bg-surface-container text-on-surface-variant text-[10px] font-bold">
                                            {{ $item->product?->category?->name ?? 'Batik' }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right text-on-surface font-medium whitespace-nowrap">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center font-bold text-primary">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-bold text-secondary whitespace-nowrap border-r border-outline-variant/30">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>

                                    {{-- Kolom Total Transaksi & Status Keuangan (Digabung Sel Kebawah Sesuai Permintaan) --}}
                                    @if($itemIndex === 0)
                                        <td rowspan="{{ $rowCount }}" class="py-3 px-3 text-right align-top bg-surface-container-lowest/80 border-r border-outline-variant/30">
                                            <span class="font-serif text-sm font-bold text-primary block">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-[10px] text-outline block mt-0.5">
                                                (Total: {{ $order->items->sum('quantity') }} pcs)
                                            </span>
                                            @if($order->shipping_cost > 0)
                                                <span class="text-[10px] text-outline block">
                                                    + Ongkir Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td rowspan="{{ $rowCount }}" class="py-3 px-3 text-center align-top bg-surface-container-lowest/80">
                                            @if($isMasuk)
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                                                    <span>Pendapatan Masuk</span>
                                                </span>
                                                <span class="block text-[10px] text-emerald-700 mt-1 font-medium">
                                                    Diterima Pembeli
                                                </span>
                                            @elseif($isCancelled)
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] font-bold bg-red-100 text-red-800 border border-red-200">
                                                    <span class="w-2 h-2 rounded-full bg-red-600"></span>
                                                    <span>Dibatalkan</span>
                                                </span>
                                                @if($order->cancellation_reason)
                                                    <span class="block text-[9px] text-red-600 mt-1 max-w-[120px] mx-auto truncate" title="{{ $order->cancellation_reason }}">
                                                        "{{ $order->cancellation_reason }}"
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                    <span class="w-2 h-2 rounded-full bg-amber-600"></span>
                                                    <span>Pending</span>
                                                </span>
                                                <span class="block text-[10px] text-amber-700 mt-1 font-medium">
                                                    Belum Diterima
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    @empty
                        <tr>
                            <td colspan="11" class="py-16 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[64px] text-outline block mb-3">account_balance</span>
                                <p class="text-sm font-semibold">Belum ada data transaksi keuangan yang sesuai dengan filter.</p>
                                <p class="text-xs text-outline mt-1">Coba ubah tanggal atau status filter Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="p-4 border-t border-outline-variant/60 flex flex-col sm:flex-row items-center justify-between gap-3 overflow-x-auto">
            <span class="text-xs text-on-surface-variant">
                Menampilkan <strong>{{ $orders->firstItem() }}</strong> – <strong>{{ $orders->lastItem() }}</strong> dari <strong>{{ $orders->total() }}</strong> transaksi
            </span>
            <div>
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </div>

</div>

<script>
function downloadFinanceExcel() {
    const now = new Date();
    const pad = (n) => String(n).padStart(2, '0');
    
    // Format YYYYMMDD_HHMMSS untuk nama file
    const fileTime = `${now.getFullYear()}${pad(now.getMonth() + 1)}${pad(now.getDate())}_${pad(now.getHours())}${pad(now.getMinutes())}${pad(now.getSeconds())}`;
    
    // Format tanggal baca (DD/MM/YYYY HH:MM:SS)
    const displayTime = `${pad(now.getDate())}/${pad(now.getMonth() + 1)}/${now.getFullYear()} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    
    // Ambil parameter URL pencarian & filter saat ini
    const url = new URL('{{ route("admin.finance.export") }}', window.location.origin);
    const currentParams = new URLSearchParams(window.location.search);
    
    currentParams.forEach((value, key) => {
        url.searchParams.set(key, value);
    });
    
    url.searchParams.set('client_time', fileTime);
    url.searchParams.set('download_time', displayTime);
    
    window.location.href = url.toString();
}
</script>
@endsection
