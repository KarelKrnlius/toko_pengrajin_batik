<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Daftar Akun — Juragan Batik</title>

    {{-- Favicon Batik --}}
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}"/>
    <link rel="apple-touch-icon" href="{{ asset('images/logo.png') }}"/>

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet"/>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: { extend: {
            colors: {
                "primary":"#311908","on-primary":"#ffffff","secondary":"#904c2e",
                "on-secondary":"#ffffff","primary-container":"#4a2e1b",
                "surface":"#fdf9f3","surface-container":"#f1ede7","surface-container-low":"#f7f3ed",
                "surface-container-lowest":"#ffffff","surface-container-high":"#ebe8e2",
                "surface-container-highest":"#e6e2dc","on-surface":"#1c1c18",
                "on-surface-variant":"#50443e","outline":"#82746d","outline-variant":"#d4c3bb",
                "error":"#ba1a1a","on-error":"#ffffff","error-container":"#ffdad6",
                "background":"#fdf9f3","tertiary-fixed":"#ffdcbd","secondary-fixed":"#ffdbcd",
                "on-secondary-fixed":"#360f00","primary-fixed":"#ffdbc7",
                "secondary-container":"#fea681","on-secondary-container":"#78391c",
            },
            fontFamily: { sans:["Plus Jakarta Sans","sans-serif"], serif:["Playfair Display","serif"] },
        }}
    };
    </script>
    <style>
        body { font-family:'Plus Jakarta Sans',sans-serif; }
        ::-webkit-scrollbar { display:none; }
    </style>
</head>
<body class="min-h-screen bg-background flex items-center justify-center p-4">

    {{-- Background ornament --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-secondary-container/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-tertiary-fixed/30 rounded-full blur-2xl"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Brand Header --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="Juragan Batik" class="h-16 w-auto object-contain" style="mix-blend-mode:multiply;"/>
                <div class="flex flex-col items-center">
                    <span class="font-serif text-2xl tracking-[0.25em] text-primary uppercase font-bold">JURAGAN BATIK</span>
                    <span class="text-[10px] tracking-[0.3em] text-secondary uppercase font-semibold">Surakarta • Est. 1928</span>
                </div>
            </a>
        </div>

        {{-- Register Card --}}
        <div class="bg-surface-container-lowest rounded-2xl shadow-xl overflow-hidden">

            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-primary to-primary-container px-6 py-5 text-on-primary">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-on-primary/10 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[24px]">person_add</span>
                    </div>
                    <div>
                        <h1 class="font-serif text-xl font-bold leading-tight">Daftar Akun Baru</h1>
                        <p class="text-on-primary/70 text-sm mt-0.5">Buat akun untuk berbelanja & simpan favorit</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-6 py-6">

                {{-- Error global --}}
                {{-- Error per field --}}
                @if($errors->any())
                <div class="flex flex-col gap-2 p-3 bg-error-container rounded-xl mb-4">
                    @foreach($errors->all() as $error)
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-error text-[18px] flex-shrink-0">error</span>
                        <p class="text-sm text-on-surface">{{ $error }}</p>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Success message --}}
                @if(session('success'))
                <div class="flex items-center gap-2 p-3 bg-surface-container rounded-xl mb-4 border border-outline-variant">
                    <span class="material-symbols-outlined text-[20px] flex-shrink-0" style="color:#386a20">check_circle</span>
                    <p class="text-sm text-on-surface">{{ session('success') }}</p>
                </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST" class="flex flex-col gap-5">
                    @csrf

                    {{-- Nama --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[12px] font-bold tracking-[0.1em] uppercase text-primary">Nama Lengkap</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">person</span>
                            <input type="text" name="name" value="{{ old('name') }}" required autofocus
                                   placeholder="Nama Anda"
                                   class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('name') border-error ring-1 ring-error @enderror"/>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[12px] font-bold tracking-[0.1em] uppercase text-primary">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">mail</span>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   placeholder="email@contoh.com"
                                   class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('email') border-error ring-1 ring-error @enderror"/>
                        </div>
                        @error('email')
                        <p class="text-xs text-error font-semibold flex items-center gap-1 mt-0.5">
                            <span class="material-symbols-outlined text-[15px]">error</span>
                            <span>{{ $message }}</span>
                        </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[12px] font-bold tracking-[0.1em] uppercase text-primary">Kata Sandi</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                            <input type="password" name="password" id="regPassword" required
                                   placeholder="Min. 8 karakter"
                                   class="w-full pl-11 pr-12 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('password') border-error ring-1 ring-error @enderror"/>
                            <button type="button" id="toggleRegPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]" id="regEyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[12px] font-bold tracking-[0.1em] uppercase text-primary">Konfirmasi Kata Sandi</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                            <input type="password" name="password_confirmation" id="regPasswordConfirm" required
                                   placeholder="Ulangi kata sandi"
                                   class="w-full pl-11 pr-12 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('password') border-error ring-1 ring-error @enderror"/>
                            <button type="button" id="toggleRegPasswordConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]" id="regEyeIconConfirm">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full py-3 bg-primary hover:bg-secondary text-on-primary font-bold text-sm tracking-[0.1em] uppercase rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">person_add</span>
                        <span>Daftar Sekarang</span>
                    </button>
                </form>
            </div>

            {{-- Footer --}}
            <div class="px-6 pb-5 pt-0 text-center">
                <div class="border-t border-outline-variant pt-4 flex flex-col items-center gap-3">
                    <div>
                        <p class="text-xs text-on-surface-variant mb-1">Sudah punya akun?</p>
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center gap-1 text-secondary hover:text-primary text-sm font-semibold transition-colors">
                            <span class="material-symbols-outlined text-[16px]">login</span>
                            Login di sini
                        </a>
                    </div>
                    <div class="border-t border-outline-variant w-full pt-2">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-1 text-on-surface-variant hover:text-primary text-sm font-semibold transition-colors">
                            <span class="material-symbols-outlined text-[16px]">home</span>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-xs text-on-surface-variant mt-4">
            © {{ date('Y') }} Juragan Batik Surakarta
        </p>
    </div>

    <script>
        function togglePw(inputId, iconId) {
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
        document.getElementById('toggleRegPassword')?.addEventListener('click', () => togglePw('regPassword','regEyeIcon'));
        document.getElementById('toggleRegPasswordConfirm')?.addEventListener('click', () => togglePw('regPasswordConfirm','regEyeIconConfirm'));
    </script>
</body>
</html>
