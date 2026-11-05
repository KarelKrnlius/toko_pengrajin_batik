@extends('layouts.app')
@section('title', 'Profil Pengguna — Juragan Batik')

@section('content')
<div class="max-w-[1200px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

    {{-- Breadcrumb & Title --}}
    <div class="mb-space-lg">
        <nav class="flex items-center gap-2 text-sm text-on-surface-variant mb-2">
            <a href="{{ route('home') }}" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-[14px] text-outline">chevron_right</span>
            <span class="text-primary font-semibold">Profil Saya</span>
        </nav>
        <h1 class="font-serif text-3xl text-primary font-bold">Profil Akun</h1>
        <p class="text-sm text-on-surface-variant mt-1">Kelola data pribadi, alamat rumah, kontak, dan keamanan akun Anda</p>
    </div>

    {{-- Alerts --}}
    @if(session('warning'))
    <div class="mb-6 flex items-start sm:items-center gap-3 p-4 bg-amber-50 border border-amber-200 rounded-2xl shadow-sm text-amber-900">
        <span class="material-symbols-outlined text-[24px] text-amber-600 flex-shrink-0 mt-0.5 sm:mt-0">warning</span>
        <div class="flex-1">
            <p class="text-sm font-bold text-amber-900">Lengkapi Profil Anda</p>
            <p class="text-xs text-amber-700 mt-0.5 leading-relaxed">{{ session('warning') }}</p>
        </div>
    </div>
    @endif

    @if(session('success_profile') || session('success'))
    <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm text-emerald-900">
        <span class="material-symbols-outlined text-[24px] text-emerald-600 flex-shrink-0">check_circle</span>
        <div class="flex-1">
            <p class="text-sm font-bold text-emerald-900">Berhasil Disimpan</p>
            <p class="text-xs text-emerald-700 mt-0.5">{{ session('success_profile') ?? session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('success_password'))
    <div class="mb-6 flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl shadow-sm text-emerald-900">
        <span class="material-symbols-outlined text-[24px] text-emerald-600 flex-shrink-0">lock_reset</span>
        <div class="flex-1">
            <p class="text-sm font-bold text-emerald-900">Kata Sandi Berhasil Diperbarui</p>
            <p class="text-xs text-emerald-700 mt-0.5">{{ session('success_password') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-space-lg items-start">

        {{-- Left Column: User Summary Card --}}
        <div class="lg:col-span-1 flex flex-col gap-4 sticky top-24">
            {{-- Profile Card --}}
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant p-6 flex flex-col items-center text-center">
                {{-- Avatar --}}
                @php
                    $initials = $user->initials;
                    $initialsLen = mb_strlen($initials);
                    $textSize = $initialsLen === 2 ? 'text-2xl tracking-widest' : 'text-3xl';
                @endphp
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-primary to-secondary text-on-primary flex items-center justify-center font-serif font-bold shadow-md mb-4 {{ $textSize }} select-none">
                    {{ $initials }}
                </div>

                <h2 class="font-serif text-xl font-bold text-primary">{{ $user->name }}</h2>
                <p class="text-xs text-on-surface-variant mt-0.5 truncate max-w-full">{{ $user->email }}</p>

                <div class="mt-3 inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold {{ $user->isAdmin() ? 'bg-primary text-on-primary' : 'bg-surface-container text-secondary' }}">
                    <span class="material-symbols-outlined text-[14px]">{{ $user->isAdmin() ? 'shield_person' : 'person' }}</span>
                    <span>{{ $user->isAdmin() ? 'Administrator' : 'Pelanggan Terdaftar' }}</span>
                </div>

                <div class="w-full border-t border-outline-variant my-5"></div>

                {{-- Quick Stats --}}
                <div class="w-full grid grid-cols-2 gap-3 text-left">
                    <a href="{{ route('cart.index') }}"
                       class="p-3 bg-surface-container-low hover:bg-surface-container rounded-xl transition-colors border border-outline-variant flex flex-col justify-between">
                        <div class="flex items-center justify-between text-secondary mb-1">
                            <span class="material-symbols-outlined text-[20px]">shopping_bag</span>
                            <span class="font-bold text-base text-primary">{{ $user->carts()->sum('quantity') }}</span>
                        </div>
                        <span class="text-xs text-on-surface-variant font-medium">Keranjang</span>
                    </a>

                    <a href="{{ route('favorites.index') }}"
                       class="p-3 bg-surface-container-low hover:bg-surface-container rounded-xl transition-colors border border-outline-variant flex flex-col justify-between">
                        <div class="flex items-center justify-between text-error mb-1">
                            <span class="material-symbols-outlined text-[20px]">favorite</span>
                            <span class="font-bold text-base text-primary">{{ $user->favorites()->count() }}</span>
                        </div>
                        <span class="text-xs text-on-surface-variant font-medium">Favorit</span>
                    </a>
                </div>

                @if($user->phone)
                <div class="w-full mt-4 p-3 bg-surface-container-low rounded-xl text-left border border-outline-variant">
                    <span class="text-[10px] font-bold text-outline tracking-wider uppercase block mb-1">No. Telepon</span>
                    <p class="text-xs font-semibold text-primary flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary">phone</span>
                        <span>{{ $user->phone }}</span>
                    </p>
                </div>
                @endif

                @if($user->address)
                <div class="w-full mt-3 p-3 bg-surface-container-low rounded-xl text-left border border-outline-variant">
                    <span class="text-[10px] font-bold text-outline tracking-wider uppercase block mb-1">Alamat Rumah</span>
                    <p class="text-xs text-on-surface leading-relaxed flex items-start gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-secondary flex-shrink-0 mt-0.5">home_pin</span>
                        <span>{{ $user->address }}</span>
                    </p>
                </div>
                @endif
            </div>
        </div>

        {{-- Right Column: Edit Profile & Password Forms --}}
        <div class="lg:col-span-2 flex flex-col gap-space-lg">

            {{-- Form 1: Biodata & Kontak --}}
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">badge</span>
                    </div>
                    <div>
                        <h2 class="font-serif text-lg font-bold text-primary">Informasi Pribadi & Alamat</h2>
                        <p class="text-xs text-on-surface-variant">Perbarui nama, email, nomor telepon, dan alamat rumah Anda</p>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="p-6 flex flex-col gap-5">
                    @csrf
                    @method('PUT')

                    {{-- Nama Lengkap --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="nameInput" class="text-xs font-bold tracking-wider uppercase text-primary">
                            Nama Lengkap <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">person</span>
                            <input type="text" id="nameInput" name="name" value="{{ old('name', $user->name) }}" required
                                   placeholder="Nama lengkap Anda"
                                   class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('name') border-error ring-1 ring-error @enderror"/>
                        </div>
                        @error('name')
                        <p class="text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="emailInput" class="text-xs font-bold tracking-wider uppercase text-primary">
                            Alamat Email <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">mail</span>
                            <input type="email" id="emailInput" name="email" value="{{ old('email', $user->email) }}" required
                                   placeholder="nama@email.com"
                                   class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('email') border-error ring-1 ring-error @enderror"/>
                        </div>
                        @error('email')
                        <p class="text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Nomor Telepon --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="phoneInput" class="text-xs font-bold tracking-wider uppercase text-primary flex items-center justify-between">
                            <span>Nomor Telepon / WhatsApp <span class="text-error">*</span></span>
                            @if(!$user->phone)
                            <span class="text-[10px] text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full font-semibold lowercase">wajib untuk beli</span>
                            @endif
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">phone</span>
                            <input type="tel" id="phoneInput" name="phone" value="{{ old('phone', $user->phone) }}"
                                   placeholder="Contoh: 081234567890"
                                   class="w-full pl-11 pr-4 py-3 bg-surface-container-low border {{ !$user->phone ? 'border-amber-400 ring-1 ring-amber-300' : 'border-outline-variant' }} rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('phone') border-error ring-1 ring-error @enderror"/>
                        </div>
                        <p class="text-[11px] text-on-surface-variant">Wajib diisi agar data Anda otomatis terkirim saat memesan via WhatsApp.</p>
                        @error('phone')
                        <p class="text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Alamat Rumah --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="addressInput" class="text-xs font-bold tracking-wider uppercase text-primary flex items-center justify-between">
                            <span>Alamat Rumah Lengkap <span class="text-error">*</span></span>
                            @if(!$user->address)
                            <span class="text-[10px] text-amber-700 bg-amber-100 px-2 py-0.5 rounded-full font-semibold lowercase">wajib untuk beli</span>
                            @endif
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-3 text-outline text-[20px]">home_pin</span>
                            <textarea id="addressInput" name="address" rows="3"
                                      placeholder="Masukkan alamat rumah lengkap (jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, kode pos)"
                                      class="w-full pl-11 pr-4 py-3 bg-surface-container-low border {{ !$user->address ? 'border-amber-400 ring-1 ring-amber-300' : 'border-outline-variant' }} rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition leading-relaxed @error('address') border-error ring-1 ring-error @enderror">{{ old('address', $user->address) }}</textarea>
                        </div>
                        <p class="text-[11px] text-on-surface-variant">Wajib diisi sebagai alamat pengiriman pesanan Anda via WhatsApp.</p>
                        @error('address')
                        <p class="text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Submit Profil --}}
                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-primary hover:bg-secondary text-on-primary font-bold text-xs tracking-wider uppercase rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Form 2: Ganti Password --}}
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant overflow-hidden">
                <div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-secondary/15 text-secondary flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                    </div>
                    <div>
                        <h2 class="font-serif text-lg font-bold text-primary">Keamanan & Kata Sandi</h2>
                        <p class="text-xs text-on-surface-variant">Ganti kata sandi akun Anda secara berkala untuk menjaga keamanan</p>
                    </div>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="p-6 flex flex-col gap-5">
                    @csrf
                    @method('PUT')

                    {{-- Kata Sandi Saat Ini --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="curPassword" class="text-xs font-bold tracking-wider uppercase text-primary">
                            Kata Sandi Saat Ini <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                            <input type="password" id="curPassword" name="current_password" required
                                   placeholder="••••••••"
                                   class="w-full pl-11 pr-12 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('current_password') border-error ring-1 ring-error @enderror"/>
                            <button type="button" onclick="togglePass('curPassword', 'curEyeIcon')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]" id="curEyeIcon">visibility</span>
                            </button>
                        </div>
                        @error('current_password')
                        <p class="text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Kata Sandi Baru --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="newPassword" class="text-xs font-bold tracking-wider uppercase text-primary">
                            Kata Sandi Baru <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock_reset</span>
                            <input type="password" id="newPassword" name="password" required
                                   placeholder="Minimal 8 karakter"
                                   class="w-full pl-11 pr-12 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('password') border-error ring-1 ring-error @enderror"/>
                            <button type="button" onclick="togglePass('newPassword', 'newEyeIcon')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]" id="newEyeIcon">visibility</span>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Kata Sandi Baru --}}
                    <div class="flex flex-col gap-1.5">
                        <label for="confirmPassword" class="text-xs font-bold tracking-wider uppercase text-primary">
                            Konfirmasi Kata Sandi Baru <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">check_circle</span>
                            <input type="password" id="confirmPassword" name="password_confirmation" required
                                   placeholder="Ulangi kata sandi baru"
                                   class="w-full pl-11 pr-12 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition"/>
                            <button type="button" onclick="togglePass('confirmPassword', 'confirmEyeIcon')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]" id="confirmEyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Password --}}
                    <div class="pt-2 flex justify-end">
                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-secondary hover:bg-primary text-on-secondary font-bold text-xs tracking-wider uppercase rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">key</span>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </div>

</div>

<script>
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerText = 'visibility_off';
        } else {
            input.type = 'password';
            icon.innerText = 'visibility';
        }
    }
</script>
@endsection
