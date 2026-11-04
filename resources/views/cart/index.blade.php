@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@section('content')
<div class="max-w-[1200px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

    {{-- Header --}}
    <div class="mb-space-lg">
        <nav class="flex items-center gap-2 text-sm text-on-surface-variant mb-2">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
            <span class="text-primary font-semibold">Keranjang</span>
        </nav>
        <h1 class="font-serif text-3xl text-primary font-bold">Keranjang Belanja</h1>
        <p class="text-sm text-on-surface-variant mt-1">{{ $carts->count() }} produk dalam keranjang</p>
    </div>

    @if(session('success'))
    <div class="mb-4 flex items-center gap-2 p-3 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm">
        <span class="material-symbols-outlined text-[20px]" style="color:#386a20">check_circle</span>
        <p class="text-sm text-on-surface flex-1">{{ session('success') }}</p>
    </div>
    @endif

    @if($carts->isEmpty())
    <div class="text-center py-16 bg-surface-container-lowest rounded-xl shadow-sm">
        <span class="material-symbols-outlined text-[64px] text-outline block mb-4">shopping_bag</span>
        <p class="text-lg text-on-surface-variant mb-4">Keranjang masih kosong</p>
        <a href="{{ route('catalog') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary hover:bg-secondary text-on-primary text-sm font-bold rounded-lg transition-colors">
            <span class="material-symbols-outlined text-[18px]">storefront</span>
            Mulai Belanja
        </a>
    </div>
    @else

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-space-lg">

        {{-- Daftar Produk --}}
        <div class="lg:col-span-2 flex flex-col gap-3">
            @foreach($carts as $cart)
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden flex">

                {{-- Gambar --}}
                <div class="w-24 flex-shrink-0 bg-surface-container">
                    @if($cart->product->image)
                        <img src="{{ Storage::url($cart->product->image) }}" alt="{{ $cart->product->name }}"
                             class="w-full h-full object-cover" style="aspect-ratio:3/4;"/>
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="aspect-ratio:3/4;">
                            <span class="material-symbols-outlined text-[28px] text-outline">draw</span>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 p-4 flex flex-col justify-between gap-2 min-w-0">
                    <div>
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <a href="{{ route('product.show', $cart->product->id) }}"
                                   class="text-sm font-bold text-primary hover:text-secondary transition-colors line-clamp-1">
                                    {{ $cart->product->name }}
                                </a>
                                <span class="text-[11px] text-on-surface-variant">{{ $cart->product->category->name ?? '-' }}</span>
                            </div>
                            <form action="{{ route('cart.destroy', $cart->id) }}" method="POST" class="flex-shrink-0"
                                  onsubmit="return confirm('Hapus dari keranjang?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 rounded-full bg-surface-container hover:bg-error hover:text-on-error text-error flex items-center justify-center transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                        <p class="text-sm font-bold text-primary mt-1">
                            Rp {{ number_format($cart->product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    {{-- Qty --}}
                    <div class="flex items-center justify-between">
                        <form action="{{ route('cart.update', $cart->id) }}" method="POST" class="flex items-center gap-1">
                            @csrf @method('PUT')
                            <button type="button" onclick="this.nextElementSibling.value = Math.max(1, parseInt(this.nextElementSibling.value)-1); this.form.submit()"
                                    class="w-7 h-7 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-[14px]">remove</span>
                            </button>
                            <input type="text" name="quantity" value="{{ $cart->quantity }}" readonly
                                   class="w-10 text-center bg-surface-container-low border border-outline-variant rounded-lg text-sm font-bold text-primary py-1"/>
                            <button type="button" onclick="this.previousElementSibling.value = Math.min({{ $cart->product->stock }}, parseInt(this.previousElementSibling.value)+1); this.form.submit()"
                                    class="w-7 h-7 rounded-lg bg-surface-container hover:bg-surface-container-high text-primary flex items-center justify-center transition-colors">
                                <span class="material-symbols-outlined text-[14px]">add</span>
                            </button>
                        </form>
                        <span class="text-xs text-on-surface-variant">Subtotal: <strong class="text-primary">Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Ringkasan --}}
        <div class="lg:col-span-1">
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-5 sticky top-24 flex flex-col gap-4">
                <h2 class="font-serif text-lg text-primary font-bold">Ringkasan</h2>

                <div class="flex flex-col gap-2 text-sm">
                    @foreach($carts as $cart)
                    <div class="flex justify-between text-on-surface-variant">
                        <span class="line-clamp-1">{{ $cart->product->name }} ({{ $cart->quantity }}x)</span>
                        <span class="font-semibold text-primary whitespace-nowrap">Rp {{ number_format($cart->product->price * $cart->quantity, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-surface-container pt-3">
                    <div class="flex items-baseline justify-between">
                        <span class="text-sm font-bold text-on-surface-variant">Total</span>
                        <span class="font-serif text-2xl font-bold text-primary">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="w-full py-3 text-on-secondary font-bold text-sm tracking-wider uppercase rounded-lg shadow-md transition-all hover:opacity-90 flex items-center justify-center gap-2"
                            style="background:#25d366;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Checkout via WhatsApp
                    </button>
                </form>

                <a href="{{ route('catalog') }}"
                   class="text-center text-xs text-secondary hover:text-primary font-semibold transition-colors">
                    ← Lanjut Belanja
                </a>
            </div>
        </div>
    </div>

    @endif
</div>
@endsection
