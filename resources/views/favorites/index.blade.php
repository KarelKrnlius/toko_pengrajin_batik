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
                        <form action="{{ route('cart.store', $product->id) }}" method="POST" class="w-full m-0 p-0 flex">
                            @csrf
                            <button type="submit"
                                    class="w-full h-7 rounded-lg bg-primary hover:bg-secondary text-on-primary flex items-center justify-center gap-1 text-[10px] sm:text-[11px] font-bold transition-colors shadow-sm">
                                <span class="material-symbols-outlined text-[13px] leading-none">add_shopping_cart</span>
                                <span class="truncate">Keranjang</span>
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    @endif
</div>
@endsection
