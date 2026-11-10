<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Masuk — Juragan Batik</title>

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
        theme: {
            extend: {
                colors: {
                    "primary":                "#311908",
                    "on-primary":             "#ffffff",
                    "secondary":              "#904c2e",
                    "on-secondary":           "#ffffff",
                    "primary-container":      "#4a2e1b",
                    "secondary-container":    "#fea681",
                    "surface":                "#fdf9f3",
                    "surface-container":      "#f1ede7",
                    "surface-container-low":  "#f7f3ed",
                    "surface-container-lowest":"#ffffff",
                    "surface-container-high": "#ebe8e2",
                    "on-surface":             "#1c1c18",
                    "on-surface-variant":     "#50443e",
                    "outline":                "#82746d",
                    "outline-variant":        "#d4c3bb",
                    "error":                  "#ba1a1a",
                    "error-container":        "#ffdad6",
                    "background":             "#fdf9f3",
                    "tertiary-fixed":         "#ffdcbd",
                },
                fontFamily: {
                    sans: ["Plus Jakarta Sans", "sans-serif"],
                    serif: ["Playfair Display", "serif"],
                },
                spacing: {
                    "space-xs":  "0.25rem",
                    "space-sm":  "0.5rem",
                    "space-md":  "1rem",
                    "space-lg":  "1.5rem",
                    "space-xl":  "2.5rem",
                },
            }
        }
    };
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="min-h-screen bg-background flex items-center justify-center p-space-md">

    {{-- Background ornament --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-secondary-container/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-tertiary-fixed/30 rounded-full blur-2xl"></div>
    </div>

    <div class="relative w-full max-w-md">

        {{-- Brand Header --}}
        <div class="text-center mb-space-xl">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-2">
                <img src="{{ asset('images/logo.png') }}"
                     alt="Juragan Batik"
                     class="h-16 w-auto object-contain"/>
                <div class="flex flex-col items-center">
                    <span class="font-serif text-2xl tracking-[0.25em] text-primary uppercase font-bold">JURAGAN BATIK</span>
                    <span class="text-[10px] tracking-[0.3em] text-secondary uppercase font-semibold">Surakarta • Est. 1928</span>
                </div>
            </a>
        </div>

        {{-- Login Card --}}
        <div class="bg-surface-container-lowest rounded-2xl shadow-xl overflow-hidden">

            {{-- Card Header --}}
            <div class="bg-gradient-to-r from-primary to-primary-container px-space-xl py-space-lg text-on-primary">
                <div class="flex items-center gap-space-md">
                    <div class="w-12 h-12 rounded-xl bg-on-primary/10 flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined text-[28px]">shield_person</span>
                    </div>
                    <div>
                        <h1 class="font-serif text-xl font-bold leading-tight">Login</h1>
                        <p class="text-on-primary/70 text-sm mt-0.5">Masuk untuk berbelanja & menyimpan favorit</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <div class="px-space-xl py-space-xl">

                {{-- Error global --}}
                @if($errors->any())
                <div class="flex items-center gap-space-sm p-space-md bg-error-container rounded-xl mb-space-lg">
                    <span class="material-symbols-outlined text-error text-[20px] flex-shrink-0">error</span>
                    <p class="text-sm text-on-surface">{{ $errors->first() }}</p>
                </div>
                @endif

                {{-- Unauthorized redirect message --}}
                @if(session('error'))
                <div class="flex items-center gap-space-sm p-space-md bg-error-container rounded-xl mb-space-lg">
                    <span class="material-symbols-outlined text-error text-[20px] flex-shrink-0">lock</span>
                    <p class="text-sm text-on-surface">{{ session('error') }}</p>
                </div>
                @endif

                {{-- Success message (setelah logout / register) --}}
                @if(session('success'))
                <div class="flex items-center gap-space-sm p-space-md bg-surface-container rounded-xl mb-space-lg border border-outline-variant">
                    <span class="material-symbols-outlined text-[20px] flex-shrink-0" style="color:#386a20">check_circle</span>
                    <p class="text-sm text-on-surface">{{ session('success') }}</p>
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="flex flex-col gap-space-lg">
                    @csrf

                    {{-- Email --}}
                    <div class="flex flex-col gap-space-xs">
                        <label class="text-[12px] font-bold tracking-[0.1em] uppercase text-primary">
                            Email
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-space-md top-1/2 -translate-y-1/2 text-outline text-[20px]">mail</span>
                            <input type="email" name="email" id="emailInput" value="{{ old('email') }}" required autofocus
                                   placeholder="email@contoh.com"
                                   class="w-full pl-11 pr-space-md py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('email') border-error ring-1 ring-error @enderror"/>
                        </div>
                        @error('email')
                        <p class="text-xs text-error flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">error</span>{{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="flex flex-col gap-space-xs">
                        <label class="text-[12px] font-bold tracking-[0.1em] uppercase text-primary">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-space-md top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                            <input type="password" name="password" id="passwordInput" required
                                   placeholder="••••••••"
                                   class="w-full pl-11 pr-12 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary focus:border-transparent transition @error('password') border-error ring-1 ring-error @enderror"/>
                            <button type="button" id="togglePassword"
                                    class="absolute right-space-md top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]" id="eyeIcon">visibility</span>
                            </button>
                        </div>
                    </div>

                    {{-- Remember me & Lupa Kata Sandi --}}
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-space-sm cursor-pointer select-none">
                            <div class="relative">
                                <input type="checkbox" name="remember" id="rememberMe" class="sr-only peer"/>
                                <div class="w-10 h-5 bg-surface-container-high rounded-full peer-checked:bg-secondary transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                            </div>
                            <span class="text-sm text-on-surface-variant">Ingat sesi ini</span>
                        </label>
                        <button type="button" onclick="lupaPasswordWA()"
                           class="inline-flex items-center gap-1 text-sm text-secondary hover:text-primary font-semibold transition-colors">
                            <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                            Lupa Kata Sandi?
                        </button>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full py-3 bg-primary hover:bg-secondary text-on-primary font-bold text-sm tracking-[0.1em] uppercase rounded-xl shadow-md transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-space-sm">
                        <span class="material-symbols-outlined text-[20px]">login</span>
                        <span>Masuk</span>
                    </button>
                </form>
            </div>

            {{-- Card Footer --}}
            <div class="px-space-xl pb-space-lg pt-0 text-center">
                <div class="border-t border-outline-variant pt-space-lg flex flex-col items-center gap-3">
                    <div>
                        <p class="text-xs text-on-surface-variant mb-1">Belum punya akun?</p>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-1 text-secondary hover:text-primary text-sm font-semibold transition-colors">
                            <span class="material-symbols-outlined text-[16px]">person_add</span>
                            Daftar Sekarang
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

        <p class="text-center text-xs text-on-surface-variant mt-space-lg">
            © {{ date('Y') }} Juragan Batik Surakarta
        </p>
    </div>

    <script>
        // Lupa kata sandi — kirim WhatsApp ke admin dengan email yang sudah diketik
        function lupaPasswordWA() {
            const emailInput = document.getElementById('emailInput');
            const email = emailInput && emailInput.value.trim()
                ? emailInput.value.trim()
                : '(belum diisi)';
            const pesan = 'Halo Admin Juragan Batik, saya lupa kata sandi akun saya.\n\nEmail saya: ' + email + '\n\nMohon bantuan untuk reset password. Terima kasih.';
            window.open('https://wa.me/6281382958012?text=' + encodeURIComponent(pesan), '_blank');
        }

        // Toggle show/hide password
        document.getElementById('togglePassword').addEventListener('click', function() {
            const input = document.getElementById('passwordInput');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        });
    </script>
</body>
</html>
