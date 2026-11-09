@extends('layouts.app')
@section('title', 'Filosofi Motif')

@section('content')

{{-- ── Hero ── --}}
<section class="relative w-full overflow-hidden bg-gradient-to-b from-surface-container-low via-surface to-background py-24">
    <div class="absolute -top-32 right-10 w-[520px] h-[520px] bg-secondary-container/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin relative z-10 text-center">
        <div class="inline-flex items-center gap-space-xs bg-surface-container-high px-space-md py-space-xs rounded-full shadow-sm mb-space-md">
            <span class="w-2 h-2 rounded-full bg-secondary animate-pulse"></span>
            <span class="font-label-sm text-label-sm uppercase tracking-[0.25em] text-secondary">Warisan Budaya Takbenda UNESCO</span>
        </div>
        <h1 class="font-display text-display-mobile lg:text-display text-primary tracking-tight mb-space-md max-w-4xl mx-auto">
            Filosofi di Balik Setiap Goresan <span class="italic text-secondary">Canting</span>
        </h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto leading-relaxed">
            Batik bukan sekadar ornamen kain. Ia adalah meditasi kesabaran, narasi sakral yang diguratkan melalui cairan malam panas, dan kearifan alam Jawa yang mengalir turun-temurun.
        </p>
    </div>
</section>

{{-- ── Filosofi Motif Cards ── --}}
<section class="w-full py-space-xl bg-surface">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
        <div class="text-center mb-space-xl">
            <span class="font-label-md text-label-md text-secondary uppercase tracking-[0.25em] font-bold block mb-space-xs">Ensiklopedia Motif Adiluhung</span>
            <h2 class="font-headline-lg text-headline-lg text-primary">Makna Tersembunyi Motif Keraton</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-space-lg">
            @foreach ([
                ['draw',         'Parang Rusak',   'Filosofi Ksatria',     'Diciptakan Sultan Agung saat bertapa di Pantai Selatan. Guratan diagonal menyerupai ombak karang yang tak pernah pecah — melambangkan keteguhan jiwa pemimpin dan semangat pantang menyerah.',              'Tingkat Kerumitan: ★★★★★'],
                ['local_florist','Kawung',          'Simbol Kesucian',      'Pola geometri buah aren kuno melambangkan kesucian, kemurnian hati, dan keseimbangan semesta. Motif ini dulunya hanya boleh dikenakan keluarga kerajaan Mataram.',                                         'Tingkat Kerumitan: ★★★★☆'],
                ['spa',          'Sekar Jagad',     'Bunga Semesta Raya',   'Sekar berarti bunga, Jagad berarti dunia. Motif ini merangkum keberagaman bumi dan alam semesta. Diwarnai ganda menggunakan daun tom indigo alami dan soga tingi langka selama 60 hari.',              'Tingkat Kerumitan: ★★★★★'],
                ['water_drop',   'Mega Mendung',    'Awan Pembawa Sejuk',   'Motif khas Cirebon dengan gradasi tujuh lapis awan yang melambangkan kesabaran dan ketenangan batin. Setiap lapisan gradasi warna dikerjakan secara manual dengan tingkat presisi tinggi.',              'Tingkat Kerumitan: ★★★★☆'],
                ['eco',          'Truntum',         'Cinta yang Tumbuh',    'Truntum berasal dari kata "taruntum" — tumbuh kembali. Motif bintang kecil ini melambangkan cinta yang selalu tumbuh baru meski pernah layu. Sering dipakai orang tua pengantin.',                      'Tingkat Kerumitan: ★★★☆☆'],
                ['grain',        'Sidomukti',       'Simbol Kemakmuran',    'Sido berarti jadi/tercapai, Mukti berarti mulia/sejahtera. Dipakai dalam pernikahan adat Jawa sebagai doa dan harapan agar kedua mempelai hidup bahagia dan sejahtera selamanya.',                      'Tingkat Kerumitan: ★★★★☆'],
            ] as [$icon, $name, $subtitle, $desc, $note])
            <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm hover:shadow-md transition-shadow flex flex-col gap-space-md group">
                <div class="w-14 h-14 rounded-lg bg-surface-container flex items-center justify-center text-secondary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                    <span class="material-symbols-outlined text-[32px]">{{ $icon }}</span>
                </div>
                <div class="flex flex-col gap-space-xs flex-1">
                    <span class="font-label-sm text-label-sm text-secondary uppercase tracking-widest font-bold">{{ $subtitle }}</span>
                    <h3 class="font-headline-sm text-headline-sm text-primary">{{ $name }}</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed flex-1">{{ $desc }}</p>
                </div>
                <div class="pt-space-sm bg-surface-container-low px-space-md py-space-sm rounded font-label-sm text-label-sm text-secondary font-bold">
                    {{ $note }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Perjalanan ── --}}
<section class="w-full py-space-xl bg-surface-container-low">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
        <div class="text-center mb-space-xl">
            <span class="font-label-md text-label-md text-secondary uppercase tracking-[0.25em] font-bold block mb-space-xs">Dibalik Sentuhan Canting</span>
            <h2 class="font-headline-lg text-headline-lg text-primary">Proses Sakral Pembuatan Batik</h2>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-space-lg">
            @foreach ([
                ['draw',         'Tahap I • Nglowongi',     'Goresan Malam Lebah',      'Tetes demi tetes lilin malam panas mengalir dari cucuk canting tembaga. Setiap garis dikerjakan dengan ketelitian milimeter yang hanya dikuasai tangan berpengalaman puluhan tahun.',         'Waktu: 30–60 Hari / Kain'],
                ['nest_eco_leaf','Tahap II • Medel & Nyoga','Pewarna Hayati Ramah Bumi', 'Pewarna dari rebusan kulit kayu tingi, tegeran, dan jambal menghasilkan cokelat khas soga. Fermentasi Indigofera menghasilkan biru abadi yang tidak bisa ditiru mesin.',                   'Celupan: Hingga 18 Kali'],
                ['diversity_3',  'Tahap III • Preservasi',  'Tangan Maestro Laweyan',   'Setiap helai kain merupakan wujud pemberdayaan 45+ maestro pembatik senior di sentra Laweyan & Kauman Surakarta — merawat martabat warisan budaya sekaligus memberi nafkah berkelanjutan.', 'Sertifikat: Tanda Tangan Pengrajin'],
            ] as [$icon, $label, $title, $desc, $note])
            <div class="bg-surface-container-lowest p-space-xl rounded-xl shadow-sm flex flex-col justify-between gap-space-lg group hover:shadow-md transition-shadow">
                <div class="flex flex-col gap-space-md">
                    <div class="w-14 h-14 rounded-lg bg-surface-container flex items-center justify-center text-secondary group-hover:bg-primary group-hover:text-on-primary transition-colors">
                        <span class="material-symbols-outlined text-[32px]">{{ $icon }}</span>
                    </div>
                    <div class="flex flex-col gap-space-xs">
                        <span class="font-label-sm text-label-sm text-secondary uppercase font-bold tracking-widest">{{ $label }}</span>
                        <h3 class="font-headline-sm text-headline-sm text-primary">{{ $title }}</h3>
                        <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">{{ $desc }}</p>
                    </div>
                </div>
                <div class="bg-surface-container-low px-space-md py-space-sm rounded font-label-sm text-label-sm text-secondary font-bold">{{ $note }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Misi ── --}}
<section class="w-full py-space-xl bg-surface">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-space-xl items-center">
            <div class="flex flex-col gap-space-md">
                <span class="font-label-md text-label-md text-secondary uppercase tracking-[0.25em] font-bold">Misi Kami</span>
                <h2 class="font-headline-lg text-headline-lg text-primary">Melestarikan Keagungan<br>Wastra Nusantara</h2>
                <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                    Juragan Batik didirikan dengan satu keyakinan: bahwa setiap helai kain batik yang terjual adalah peluru pelestarian budaya. Kami bermitra langsung dengan pengrajin di <strong class="text-primary">Yogyakarta</strong> dan <strong class="text-primary">Surakarta</strong> untuk memastikan keaslian dan keberlangsungan tradisi ini.
                </p>
                <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                    Sejak batik diakui sebagai <strong class="text-primary">Warisan Budaya Takbenda UNESCO pada 2009</strong>, kami terus berkomitmen memperkenalkan kekayaan ini kepada generasi baru dengan cara yang relevan dan bermartabat.
                </p>
                <a href="{{ route('catalog') }}"
                   class="inline-flex items-center gap-space-xs px-space-xl py-space-md bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded shadow-md transition-all transform hover:-translate-y-0.5 w-fit mt-space-sm">
                    <span>Jelajahi Koleksi</span>
                    <span class="material-symbols-outlined text-[18px]">arrow_right_alt</span>
                </a>
            </div>
            <div class="grid grid-cols-2 gap-space-md">
                @foreach ([
                    ['verified',           '100%',    'Batik Asli Bersertifikat'],
                    ['diversity_3',        '45+',     'Maestro Pembatik Mitra'],
                    ['history_edu',        '95+',     'Tahun Tradisi Berlanjut'],
                    ['workspace_premium',  '3',       'Penghargaan Pelestarian Budaya'],
                ] as [$icon, $num, $label])
                <div class="bg-surface-container-lowest rounded-xl p-space-lg shadow-sm flex flex-col items-center text-center gap-space-sm hover:shadow-md transition-shadow">
                    <span class="material-symbols-outlined text-secondary text-[36px]">{{ $icon }}</span>
                    <span class="font-headline-sm text-headline-sm text-primary font-bold">{{ $num }}</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── CTA ── --}}
<section class="w-full py-16 bg-gradient-to-r from-primary via-primary-container to-secondary">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin text-center text-on-primary">
        <h2 class="font-headline-lg text-headline-lg mb-space-md">Jadilah Bagian dari Gerakan Pelestarian Ini</h2>
        <p class="font-body-md text-body-md opacity-80 mb-space-xl max-w-2xl mx-auto">
            Setiap kain yang Anda pilih adalah dukungan nyata untuk penghidupan pengrajin lokal dan pelestarian seni batik Indonesia.
        </p>
        <a href="{{ route('catalog') }}"
           class="inline-flex items-center gap-space-xs px-space-xl py-space-md bg-surface-container-lowest hover:bg-surface text-primary font-label-lg text-label-lg rounded shadow-lg transition-colors">
            <span class="material-symbols-outlined text-[18px] text-secondary">grid_view</span>
            <span>Lihat Seluruh Koleksi</span>
        </a>
    </div>
</section>

@endsection
