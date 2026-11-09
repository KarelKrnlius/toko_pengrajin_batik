@extends('layouts.app')
@section('title', 'Produk Favorit')

@section('content')
<div class="max-w-[1200px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

    {{-- Header --}}
    <div class="mb-space-lg">
        <nav class="flex items-center gap-2 text-sm text-on-surface-variant mb-2">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
            <span class="text-primary font-semibold">Favorit</span>
        </nav>
        <h1 class="font-serif text-3xl text-primary font-bold">Produk Favorit</h1>
        <p class="text-sm text-on-surface-variant mt-1">{{ $favorites->count() }} produk tersimpan</p>
    </div>

    @if(session('success'))
    <div class="mb-4 flex items-center gap-2 p-3 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm">
        <span class="material-symbols-outlined text-[20px]" style="color:#386a20">check_circle</span>
        <p class="text-sm text-on-surface flex-1">{{ session('success') }}</p>
    </div>
    @endif

    @if($favorites->isEmpty())
    <div class="text-center py-16 bg-surface-container-lowest rounded-xl shadow-sm">
        <span class="material-symbols-outlined text-[64px] text-outline block mb-4" style="font-variation-settings:'FILL' 1;">favorite</span>
        <p class="text-lg text-on-surface-variant mb-4">Belum ada produk favorit</p>
        <a href="{{ route('catalog') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-secondary text-on-primary text-sm font-bold rounded-lg transition-colors">
            <span class="material-symbols-outlined text-[18px]">storefront</span>
            Jelajahi Katalog
        </a>
    </div>
    @else

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
        @foreach($favorites as $fav)
        @php $product = $fav->product; @endphp
        <article class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all group flex flex-col">
            <div class="relative w-full overflow-hidden bg-surface-container" style="aspect-ratio:3/4;">
                @if($product->image)
                    <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-surface-container gap-1">
                        <span class="material-symbols-outlined text-[32px] text-outline">draw</span>
                        <span class="text-[9px] text-outline font-bold uppercase">Tidak Ada Foto</span>
                    </div>
                @endif
                <span class="absolute top-1.5 left-1.5 bg-primary text-on-primary text-[8px] px-1.5 py-0.5 rounded-full font-bold shadow-sm uppercase tracking-wider">
                    {{ Str::limit($product->category->name ?? 'Batik', 8) }}
                </span>
                @if($product->stock <= 0)
                <div class="absolute inset-0 bg-black/50 backdrop-blur-[1px] flex items-center justify-center p-1 text-center">
                    <span class="text-[10px] font-bold text-white bg-error px-2 py-0.5 rounded uppercase tracking-wider">Habis</span>
                </div>
                @endif
                {{-- Tombol hapus favorit --}}
                <form action="{{ route('favorites.toggle', $product->id) }}" method="POST" class="absolute top-1.5 right-1.5">
                    @csrf
                    <button type="submit"
                            class="w-7 h-7 rounded-full bg-surface-container-lowest/80 backdrop-blur-md text-error hover:bg-error hover:text-on-error flex items-center justify-center transition-colors shadow-sm"
                            title="Hapus dari Favorit">
                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1;">favorite</span>
                    </button>
                </form>
            </div>
            <div class="p-2 flex flex-col flex-1 justify-between gap-1">
                <div>
                    <h3 class="text-[11px] font-bold text-primary group-hover:text-secondary transition-colors line-clamp-1 leading-tight">{{ $product->name }}</h3>
                    <p class="text-[10px] text-on-surface-variant line-clamp-1 mt-0.5">{{ Str::limit($product->description, 40) }}</p>
                </div>
                <div class="pt-1.5 border-t border-surface-container mt-1">
                    <span class="block text-[11px] font-bold text-primary whitespace-nowrap mb-1">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <div class="grid grid-cols-2 gap-1.5 mt-2">
                        <a href="{{ route('product.show', $product->id) }}"
                           class="w-full h-7 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary flex items-center justify-center text-[10px] sm:text-[11px] font-bold transition-colors">
                            Detail
                        </a>
                        @auth
                            @if($product->stock > 0)
                                @if(Auth::user()->isProfileComplete())
                                <button type="button"
                                        onclick="openFavBuyModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ json_encode($product->category->name ?? 'Batik') }}, {{ $product->price }}, {{ $product->stock }}, {{ json_encode($product->image ? Storage::url($product->image) : null) }})"
                                        class="w-full h-7 rounded-lg bg-primary hover:bg-secondary text-on-primary flex items-center justify-center gap-1 text-[10px] sm:text-[11px] font-bold transition-colors shadow-sm">
                                    <span class="material-symbols-outlined text-[13px] leading-none">shopping_bag</span>
                                    <span class="truncate">Beli</span>
                                </button>
                                @else
                                <a href="{{ route('profile.show') }}"
                                   class="w-full h-7 rounded-lg bg-amber-600 hover:bg-amber-700 text-white flex items-center justify-center gap-1 text-[10px] sm:text-[11px] font-bold transition-colors shadow-sm"
                                   title="Lengkapi profil untuk membeli">
                                    <span class="material-symbols-outlined text-[13px] leading-none">badge</span>
                                    <span class="truncate">Lengkapi</span>
                                </a>
                                @endif
                            @else
                            <button disabled
                                    class="w-full h-7 rounded-lg bg-surface-container-high text-on-surface-variant opacity-60 flex items-center justify-center text-[10px] sm:text-[11px] font-bold cursor-not-allowed">
                                <span class="truncate">Habis</span>
                            </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    @endif
</div>

{{-- ── MODAL BELI SEKARANG (WHATSAPP) FAVORIT ── --}}
@auth
<div id="favBuyModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest rounded-2xl max-w-md w-full p-6 shadow-2xl border border-outline-variant/60 flex flex-col gap-4 transform transition-all scale-95 duration-200">
        <div class="flex items-center justify-between pb-3 border-b border-outline-variant/40">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary text-[24px]">shopping_bag</span>
                <h3 class="font-headline-sm text-base font-bold text-primary">Konfirmasi Pembelian via WhatsApp</h3>
            </div>
            <button type="button" onclick="closeFavBuyModal()" class="w-8 h-8 rounded-full flex items-center justify-center text-outline hover:text-primary hover:bg-surface-container transition">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl">
            <img id="favBuyImg" src="" alt="Produk" class="w-14 h-14 object-cover rounded-lg flex-shrink-0 hidden"/>
            <div id="favBuyImgPlaceholder" class="w-14 h-14 bg-surface-container rounded-lg flex items-center justify-center flex-shrink-0 text-outline">
                <span class="material-symbols-outlined text-[24px]">draw</span>
            </div>
            <div class="min-w-0 flex-1">
                <span class="text-[10px] text-secondary font-bold uppercase tracking-wider block" id="favBuyCategory">BATIK</span>
                <h4 class="text-sm font-bold text-primary truncate" id="favBuyName">Nama Produk</h4>
                <span class="text-xs font-bold text-secondary" id="favBuyPrice">Rp 0 <span class="text-[10px] font-normal text-outline">/ pcs</span></span>
            </div>
        </div>

        <div class="flex flex-col gap-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-bold text-primary uppercase tracking-wider">Ingin Beli Berapa Barang?</label>
                    <span class="text-xs text-outline">Stok: <strong class="text-primary font-bold" id="favBuyStock">0</strong> pcs</span>
                </div>
                <div class="flex items-center justify-between gap-3 bg-surface-container-low p-2 rounded-xl border border-outline-variant/50">
                    <div class="flex items-center bg-surface-container-highest rounded-lg p-1 border border-outline-variant/60">
                        <button type="button" onclick="adjustFavBuyQty(-1)" class="w-9 h-9 rounded-md bg-surface flex items-center justify-center text-primary hover:bg-surface-container transition shadow-sm font-bold">
                            <span class="material-symbols-outlined text-[18px]">remove</span>
                        </button>
                        <input type="number" id="favBuyQtyInput" value="1" min="1"
                               class="w-14 text-center font-bold text-sm bg-transparent border-none text-primary focus:outline-none focus:ring-0 [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                               oninput="validateFavBuyQty(this)">
                        <button type="button" onclick="adjustFavBuyQty(1)" class="w-9 h-9 rounded-md bg-surface flex items-center justify-center text-primary hover:bg-surface-container transition shadow-sm font-bold">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                        </button>
                    </div>
                    <div class="text-right pr-2">
                        <span class="block text-[10px] text-outline uppercase font-semibold">Total Pembayaran</span>
                        <span class="text-base font-bold text-secondary" id="favBuySubtotal">
                            Rp 0
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
                <button type="button" onclick="closeFavBuyModal()" class="flex-1 py-2.5 px-4 rounded-xl border border-outline-variant hover:bg-surface-container text-on-surface-variant font-bold text-xs uppercase tracking-wider transition">
                    Batal
                </button>
                <button type="button" onclick="submitFavBuyToWhatsApp()" class="flex-1 py-2.5 px-4 rounded-xl bg-primary hover:bg-secondary text-on-primary font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-1.5 shadow-md">
                    <span class="material-symbols-outlined text-[18px]">chat</span>
                    <span>Bayar ke WA</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endauth

@endsection

@push('scripts')
<script>
let currentFavProduct = null;

function formatRupiahFav(num) {
    return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function openFavBuyModal(id, name, category, price, stock, imageUrl) {
    currentFavProduct = { id, name, category, price, stock };

    const modal = document.getElementById('favBuyModal');
    if (!modal) return;

    document.getElementById('favBuyName').innerText = name;
    document.getElementById('favBuyCategory').innerText = category.toUpperCase();
    document.getElementById('favBuyPrice').innerHTML = formatRupiahFav(price) + ' <span class="text-[10px] font-normal text-outline">/ pcs</span>';
    document.getElementById('favBuyStock').innerText = stock;

    const imgEl = document.getElementById('favBuyImg');
    const imgPlaceholder = document.getElementById('favBuyImgPlaceholder');
    if (imageUrl) {
        imgEl.src = imageUrl;
        imgEl.classList.remove('hidden');
        if (imgPlaceholder) imgPlaceholder.classList.add('hidden');
    } else {
        imgEl.classList.add('hidden');
        if (imgPlaceholder) imgPlaceholder.classList.remove('hidden');
    }

    const input = document.getElementById('favBuyQtyInput');
    if (input) {
        input.value = 1;
        input.max = stock;
    }
    updateFavBuySubtotal(1);

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeFavBuyModal() {
    const modal = document.getElementById('favBuyModal');
    if (!modal) return;
    modal.classList.add('hidden');
    document.body.style.overflow = '';
}

function updateFavBuySubtotal(qty) {
    if (!currentFavProduct) return;
    const subtotal = qty * currentFavProduct.price;
    const el = document.getElementById('favBuySubtotal');
    if (el) el.innerText = formatRupiahFav(subtotal);
}

function adjustFavBuyQty(delta) {
    if (!currentFavProduct) return;
    const input = document.getElementById('favBuyQtyInput');
    if (!input) return;
    let val = parseInt(input.value) || 1;
    val += delta;
    if (val < 1) val = 1;
    if (val > currentFavProduct.stock) val = currentFavProduct.stock;
    input.value = val;
    updateFavBuySubtotal(val);
}

function validateFavBuyQty(input) {
    if (!currentFavProduct) return;
    let val = parseInt(input.value);
    if (isNaN(val) || val < 1) {
        val = 1;
    } else if (val > currentFavProduct.stock) {
        val = currentFavProduct.stock;
        input.value = currentFavProduct.stock;
    }
    updateFavBuySubtotal(val);
}

function submitFavBuyToWhatsApp() {
    if (!currentFavProduct) return;
    const input = document.getElementById('favBuyQtyInput');
    let qty = input ? (parseInt(input.value) || 1) : 1;
    if (qty < 1) qty = 1;
    if (qty > currentFavProduct.stock) qty = currentFavProduct.stock;

    const subtotal = qty * currentFavProduct.price;
    const subtotalFormatted = formatRupiahFav(subtotal);
    const unitPriceFormatted = formatRupiahFav(currentFavProduct.price);

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

    let msg = `Halo Juragan Batik, saya ingin memesan produk berikut:\n\n` +
        `• Produk: ${currentFavProduct.name}\n` +
        `• Kategori: ${currentFavProduct.category}\n` +
        `• Harga Satuan: ${unitPriceFormatted}\n` +
        `• Jumlah Beli: ${qty} pcs\n` +
        `• Total Pembayaran: ${subtotalFormatted}\n\n` +
        `Data Pemesan:\n` +
        `• Nama: ${userName}\n` +
        `• Email: ${userEmail}\n` +
        `• No. Telepon: ${userPhone}\n` +
        `• Alamat Pengiriman: ${userAddress}`;

    const url = 'https://wa.me/6281382958012?text=' + encodeURIComponent(msg);
    closeFavBuyModal();
    window.open(url, '_blank');
}

// Escape key to close modal
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        closeFavBuyModal();
    }
});
</script>
@endpush
