@extends('layouts.app')
@section('title', 'Manajemen ' . ($role === 'admin' ? 'Admin' : 'User') . ' — Juragan Batik')

@push('styles')
<style>
    /* Tailwind pagination override */
    nav[role="navigation"] svg { display:inline; }
    nav[role="navigation"] span, nav[role="navigation"] a {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 2.25rem; height: 2.25rem; border-radius: 0.5rem;
        font-size: 12px; font-weight: 600; letter-spacing: 0.05em;
        transition: background 0.15s, color 0.15s;
    }
    nav[role="navigation"] a { background:#f1ede7; color:#311908; }
    nav[role="navigation"] a:hover { background:#311908; color:#fff; }
    nav[role="navigation"] span[aria-current] { background:#311908; color:#fff; }
    nav[role="navigation"] span.text-gray-500 { color:#82746d; pointer-events:none; }

    /* ── Toast Notification ── */
    #toast-container {
        position: fixed;
        top: 5.5rem;
        right: 1.25rem;
        left: auto;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        pointer-events: none;
        max-width: calc(100vw - 2.5rem);
    }
    @media (max-width: 640px) {
        #toast-container {
            right: 0.75rem;
            left: 0.75rem;
            max-width: none;
        }
    }
    .toast {
        pointer-events: all;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 280px;
        max-width: 420px;
        width: 100%;
        padding: 0.875rem 1rem;
        border-radius: 0.75rem;
        box-shadow: 0 8px 24px rgba(49,25,8,.18);
        transform: translateX(120%);
        opacity: 0;
        transition: transform 0.35s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;
    }
    @media (max-width: 640px) {
        .toast {
            min-width: 0;
            max-width: 100%;
            transform: translateY(-50px);
        }
        .toast.show {
            transform: translateY(0);
        }
        .toast.hide {
            transform: translateY(-50px);
        }
    }
    .toast.show { transform: translateX(0); opacity: 1; }
    .toast.hide { transform: translateX(120%); opacity: 0; }
    .toast-success { background: #ffffff; border-left: 4px solid #386a20; }
    .toast-error   { background: #ffffff; border-left: 4px solid #ba1a1a; }
    .toast-icon {
        width: 36px; height: 36px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .toast-success .toast-icon { background: #d2f4d3; }
    .toast-error   .toast-icon { background: #ffdad6; }
    .toast-progress {
        position: absolute; bottom: 0; left: 0;
        height: 3px; border-radius: 0 0 0.75rem 0.75rem;
        animation: toastProgress 4s linear forwards;
    }
    .toast-success .toast-progress { background: #386a20; }
    .toast-error   .toast-progress { background: #ba1a1a; }
    @keyframes toastProgress {
        from { width: 100%; }
        to   { width: 0%; }
    }

    /* Toggle switch */
    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.25s;
    }
    .toggle-switch::after {
        content: '';
        position: absolute;
        top: 2px; left: 2px;
        width: 20px; height: 20px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
        transition: transform 0.25s;
    }
    .toggle-switch.active { background: #386a20; }
    .toggle-switch.inactive { background: #ba1a1a; }
    .toggle-switch.active::after { transform: translateX(20px); }
</style>
@endpush

@section('content')

{{-- ══ Admin Header ══ --}}
<div class="w-full bg-gradient-to-r from-primary via-primary-container to-secondary py-8">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md">
        <div>
            <span class="font-label-sm text-label-sm text-secondary-fixed uppercase tracking-[0.2em] block mb-1">PORTAL KURATOR</span>
            <h1 class="font-headline-md text-headline-md text-on-primary font-bold">
                Manajemen {{ $role === 'admin' ? 'Admin' : 'User' }}
            </h1>
            <p class="font-body-sm text-body-sm text-primary-fixed-dim mt-1">
                Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>
                &bull; Total <span class="font-semibold">{{ $users->total() }}</span> {{ $role === 'admin' ? 'admin' : 'user' }} terdaftar
            </p>
        </div>
        <div class="flex items-center gap-2 sm:gap-space-sm flex-wrap sm:flex-nowrap flex-shrink-0 w-full sm:w-auto">
            <a href="{{ route('admin.products.index') }}"
               class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-space-xs px-3 sm:px-space-md bg-on-primary/10 hover:bg-on-primary/20 text-on-primary font-label-lg text-xs sm:text-label-lg rounded-xl sm:rounded transition-colors h-10 shadow-none">
                <span class="material-symbols-outlined text-[18px] sm:text-[20px]">inventory_2</span>
                <span>Produk</span>
            </a>
            <button type="button"
                    onclick="document.getElementById('modalTambahUser').classList.remove('hidden')"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-space-xs px-3 sm:px-space-md bg-surface-container-lowest hover:bg-surface text-primary font-label-lg text-xs sm:text-label-lg rounded-xl sm:rounded shadow-sm transition-colors h-10">
                <span class="material-symbols-outlined text-[18px] sm:text-[20px] text-secondary">person_add</span>
                <span>Tambah {{ $role === 'admin' ? 'Admin' : 'User' }}</span>
            </button>
        </div>
    </div>
</div>

<div class="w-full max-w-[1440px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

    {{-- ══ Tab Pindah Role ══ --}}
    <div class="flex items-center gap-space-sm mb-space-lg">
        <a href="{{ route('admin.users.index', ['role' => 'user']) }}"
           class="inline-flex items-center gap-space-xs px-space-lg py-2.5 rounded-lg font-label-lg text-label-lg tracking-wider transition-colors shadow-sm {{ $role === 'user' ? 'bg-primary text-on-primary' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[18px]">group</span>
            User
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
           class="inline-flex items-center gap-space-xs px-space-lg py-2.5 rounded-lg font-label-lg text-label-lg tracking-wider transition-colors shadow-sm {{ $role === 'admin' ? 'bg-primary text-on-primary' : 'bg-surface-container hover:bg-surface-container-high text-on-surface' }}">
            <span class="material-symbols-outlined text-[18px]">shield_person</span>
            Admin
        </a>
    </div>

    {{-- ══ Toolbar: Search + Filter Status + Sort ══ --}}
    <form method="GET" action="{{ route('admin.users.index') }}"
          class="w-full max-w-full overflow-hidden flex flex-col lg:flex-row gap-space-md mb-space-lg bg-surface-container-lowest rounded-xl shadow-sm p-space-md border border-outline-variant">

        {{-- Pertahankan role aktif --}}
        <input type="hidden" name="role" value="{{ $role }}"/>

        {{-- Search --}}
        <div class="relative flex-1 min-w-0">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau no telepon..."
                   class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
        </div>

        {{-- Filter Status (hanya untuk role user) --}}
        @if($role === 'user' && $hasIsActive)
        <div class="relative w-full sm:w-auto">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">filter_list</span>
            <select name="status" onchange="this.form.submit()"
                    class="w-full sm:w-auto pl-9 pr-8 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer sm:min-w-[160px]">
                <option value="" {{ !request('status') ? 'selected' : '' }}>Semua Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
        </div>
        @endif

        {{-- Sort --}}
        <div class="relative w-full sm:w-auto">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">sort</span>
            <select name="sort" onchange="this.form.submit()"
                    class="w-full sm:w-auto pl-9 pr-8 py-2.5 bg-surface-container-low border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer sm:min-w-[160px]">
                <option value="terbaru" {{ request('sort', 'terbaru') === 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                <option value="terlama" {{ request('sort') === 'terlama' ? 'selected' : '' }}>Terlama</option>
                <option value="nama_az" {{ request('sort') === 'nama_az' ? 'selected' : '' }}>Nama A–Z</option>
                <option value="nama_za" {{ request('sort') === 'nama_za' ? 'selected' : '' }}>Nama Z–A</option>
            </select>
            <span class="material-symbols-outlined absolute right-2 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
        </div>

        {{-- Tombol --}}
        <div class="flex gap-space-sm flex-shrink-0 w-full sm:w-auto">
            <button type="submit"
                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1 px-space-lg py-2.5 bg-primary hover:bg-secondary text-on-primary text-sm font-bold tracking-wider rounded-lg transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">search</span>
                Cari
            </button>
            @if(request('search') || request('status') || (request('sort') && request('sort') !== 'terbaru'))
            <a href="{{ route('admin.users.index', ['role' => $role]) }}"
               class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1 px-space-md py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface text-sm font-semibold rounded-lg transition-colors">
                <span class="material-symbols-outlined text-[18px]">close</span>
                Reset
            </a>
            @endif
        </div>
    </form>

    {{-- ══ Info Bar + Hapus Terpilih ══ --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-sm mb-space-md px-1">
        <p class="text-sm text-on-surface-variant">
            Menampilkan
            <span class="font-bold text-on-surface">{{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }}</span>
            dari <span class="font-bold text-on-surface">{{ $users->total() }}</span> {{ $role === 'admin' ? 'admin' : 'user' }}
            @if(request('search'))
                &bull; pencarian "<span class="italic text-secondary">{{ request('search') }}</span>"
            @endif
        </p>
        <div class="flex items-center gap-space-sm">
            <span class="text-xs text-outline">Halaman {{ $users->currentPage() }} / {{ $users->lastPage() }}</span>
            <button type="button" id="btnHapusTerpilih"
                    onclick="hapusTerpilih()"
                    class="hidden inline-flex items-center gap-1 px-space-md py-1.5 bg-error hover:bg-red-700 text-on-error text-xs font-bold tracking-wider rounded-lg transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[16px]">delete_sweep</span>
                <span id="labelHapusTerpilih">Hapus Terpilih</span>
            </button>
        </div>
    </div>

    {{-- ══ Peringatan Admin Terakhir ══ --}}
    @if($role === 'admin' && $totalAdmins <= 1)
    <div class="flex items-center gap-space-sm p-space-md mb-space-md bg-tertiary-fixed rounded-xl border border-outline-variant">
        <span class="material-symbols-outlined text-primary text-[22px] flex-shrink-0">info</span>
        <p class="text-sm text-primary font-semibold">Hanya ada 1 admin tersisa. Admin terakhir tidak dapat dihapus.</p>
    </div>
    @endif

    {{-- ══ Tabel ══ --}}
    <div class="w-full max-w-full bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden border border-outline-variant">
        <div class="w-full overflow-x-auto">
            <table class="w-full min-w-[620px] table-auto">
                <thead>
                    <tr class="bg-primary text-on-primary">
                        <th class="px-3 py-3 text-center" style="width:44px">
                            <input type="checkbox" id="checkAll"
                                   class="w-4 h-4 rounded accent-secondary cursor-pointer"
                                   onchange="toggleCheckAll(this)"/>
                        </th>
                        <th class="px-3 py-3 text-center font-label-md text-label-md tracking-wider" style="width:48px">No</th>
                        <th class="px-4 py-3 text-left font-label-md text-label-md tracking-wider">Nama</th>
                        <th class="px-4 py-3 text-left font-label-md text-label-md tracking-wider hidden md:table-cell">Email</th>
                        <th class="px-4 py-3 text-left font-label-md text-label-md tracking-wider hidden lg:table-cell" style="width:140px">No Telepon</th>
                        <th class="px-4 py-3 text-left font-label-md text-label-md tracking-wider hidden xl:table-cell" style="width:140px">Alamat</th>
                        @if($role === 'user' && $hasIsActive)
                        <th class="px-4 py-3 text-center font-label-md text-label-md tracking-wider" style="width:90px">Status</th>
                        @endif
                        <th class="px-4 py-3 text-center font-label-md text-label-md tracking-wider" style="width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    @forelse($users as $i => $user)
                    @php
                        $isSelf = $user->id === Auth::id();
                    @endphp
                    <tr class="hover:bg-surface-container-low transition-colors group {{ $isSelf ? 'bg-tertiary-fixed/20' : '' }}" id="row-{{ $user->id }}">
                        {{-- Checkbox --}}
                        <td class="px-3 py-3 text-center">
                            @if($isSelf)
                                <span class="material-symbols-outlined text-outline text-[18px]" title="Anda sendiri">person</span>
                            @else
                                <input type="checkbox" class="user-check w-4 h-4 rounded accent-secondary cursor-pointer"
                                       value="{{ $user->id }}" onchange="updateBulkButton()"/>
                            @endif
                        </td>

                        {{-- No --}}
                        <td class="px-3 py-3 text-center">
                            <span class="font-label-md text-label-md text-outline font-bold">
                                {{ $users->firstItem() + $loop->index }}
                            </span>
                        </td>

                        {{-- Nama --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary to-secondary text-on-primary flex items-center justify-center font-serif font-bold text-[10px] flex-shrink-0 select-none">
                                    {{ $user->initials }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-primary truncate text-sm group-hover:text-secondary transition-colors">
                                        {{ $user->name }}
                                        @if($isSelf)
                                            <span class="text-[10px] font-bold text-secondary ml-1">(Anda)</span>
                                        @endif
                                    </p>
                                    <p class="text-[11px] text-on-surface-variant md:hidden truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Email --}}
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="text-sm text-on-surface truncate block">{{ $user->email }}</span>
                        </td>

                        {{-- No Telepon --}}
                        <td class="px-4 py-3 hidden lg:table-cell">
                            <span class="text-sm text-on-surface-variant">{{ $user->phone ?? '—' }}</span>
                        </td>

                        {{-- Alamat (Klik untuk lihat full) --}}
                        <td class="px-4 py-3 hidden xl:table-cell">
                            @if($user->address)
                                <button type="button"
                                        onclick="showAlamat({{ json_encode($user->name) }}, {{ json_encode($user->address) }})"
                                        class="text-sm text-secondary hover:text-primary truncate block max-w-[120px] text-left underline decoration-dashed underline-offset-2 cursor-pointer transition-colors"
                                        title="Klik untuk lihat alamat lengkap">
                                    {{ Str::limit($user->address, 20) }}
                                </button>
                            @else
                                <span class="text-sm text-on-surface-variant">—</span>
                            @endif
                        </td>

                        {{-- Status (hanya untuk role user) --}}
                        @if($role === 'user' && $hasIsActive)
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline-flex items-center justify-center m-0">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="toggle-switch {{ $user->is_active ? 'active' : 'inactive' }}"
                                        title="{{ $user->is_active ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}">
                                </button>
                            </form>
                            <p class="text-[10px] mt-0.5 font-bold tracking-wider uppercase {{ $user->is_active ? 'text-green-700' : 'text-error' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </p>
                        </td>
                        @endif

                        {{-- Aksi --}}
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                @if($isSelf)
                                    {{-- Admin melihat diri sendiri: hanya info --}}
                                    <span class="text-xs text-on-surface-variant italic">Akun Anda</span>
                                @else
                                    {{-- Edit --}}
                                    <button type="button"
                                            onclick="openEditModal({{ $user->id }}, {{ json_encode($user->name) }}, {{ json_encode($user->email) }}, {{ json_encode($user->phone) }})"
                                            class="w-8 h-8 rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary flex items-center justify-center transition-colors flex-shrink-0"
                                            title="Edit User">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>

                                    {{-- Ganti Password --}}
                                    <button type="button"
                                            onclick="confirmResetPassword({{ $user->id }}, {{ json_encode($user->name) }})"
                                            class="w-8 h-8 rounded-lg bg-surface-container hover:bg-secondary hover:text-on-secondary text-secondary flex items-center justify-center transition-colors flex-shrink-0"
                                            title="Reset Password">
                                        <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                                    </button>

                                    {{-- Hapus (untuk admin: disable jika admin terakhir) --}}
                                    @if($role === 'admin' && $totalAdmins <= 1)
                                        <button type="button" disabled
                                                class="w-8 h-8 rounded-lg bg-surface-container text-outline cursor-not-allowed flex items-center justify-center flex-shrink-0 opacity-50"
                                                title="Tidak bisa hapus admin terakhir">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    @else
                                        <button type="button"
                                                onclick="confirmDeleteUser({{ $user->id }}, {{ json_encode($user->name) }})"
                                                class="w-8 h-8 rounded-lg bg-surface-container hover:bg-error hover:text-on-error text-error flex items-center justify-center transition-colors flex-shrink-0"
                                                title="Hapus Akun">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $role === 'user' && $hasIsActive ? 8 : 7 }}" class="px-space-lg py-16 text-center">
                            <span class="material-symbols-outlined text-[64px] text-outline block mb-4">group_off</span>
                            <p class="font-body-md text-body-md text-on-surface-variant mb-1">
                                @if(request('search'))
                                    Tidak ada {{ $role === 'admin' ? 'admin' : 'user' }} yang cocok dengan "<strong>{{ request('search') }}</strong>"
                                @else
                                    Belum ada {{ $role === 'admin' ? 'admin' : 'user' }} terdaftar.
                                @endif
                            </p>
                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.users.index', ['role' => $role]) }}"
                                   class="inline-flex items-center gap-1 mt-2 text-secondary hover:text-primary text-sm font-semibold transition-colors">
                                    <span class="material-symbols-outlined text-[16px]">arrow_back</span> Tampilkan Semua
                                </a>
                            @else
                                <button onclick="document.getElementById('modalTambahUser').classList.remove('hidden')"
                                        class="mt-3 inline-flex items-center gap-space-xs px-space-lg py-space-sm bg-primary text-on-primary font-label-lg text-label-lg rounded hover:bg-secondary transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                                    Tambah {{ $role === 'admin' ? 'Admin' : 'User' }} Pertama
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ══ Footer Tabel: Pagination ══ --}}
        @if($users->hasPages())
        <div class="flex flex-col sm:flex-row items-center justify-between gap-space-sm px-space-lg py-space-md border-t border-surface-container bg-surface-container-low">
            <p class="text-xs text-on-surface-variant">
                Halaman <span class="font-bold text-on-surface">{{ $users->currentPage() }}</span>
                dari <span class="font-bold text-on-surface">{{ $users->lastPage() }}</span>
                &bull; {{ $users->total() }} {{ $role === 'admin' ? 'admin' : 'user' }} total
            </p>
            <div class="flex items-center gap-1">
                {{-- Prev --}}
                @if($users->onFirstPage())
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container text-outline cursor-not-allowed">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </span>
                @else
                    <a href="{{ $users->previousPageUrl() }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    </a>
                @endif

                {{-- Page numbers --}}
                @php
                    $start = max(1, $users->currentPage() - 2);
                    $end   = min($users->lastPage(), $users->currentPage() + 2);
                @endphp

                @if($start > 1)
                    <a href="{{ $users->url(1) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary text-xs font-bold transition-colors">1</a>
                    @if($start > 2)
                        <span class="w-6 text-center text-outline text-xs">…</span>
                    @endif
                @endif

                @for($page = $start; $page <= $end; $page++)
                    @if($page == $users->currentPage())
                        <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-on-primary text-xs font-bold shadow-sm">{{ $page }}</span>
                    @else
                        <a href="{{ $users->url($page) }}"
                           class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary text-xs font-bold transition-colors">{{ $page }}</a>
                    @endif
                @endfor

                @if($end < $users->lastPage())
                    @if($end < $users->lastPage() - 1)
                        <span class="w-6 text-center text-outline text-xs">…</span>
                    @endif
                    <a href="{{ $users->url($users->lastPage()) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary text-xs font-bold transition-colors">{{ $users->lastPage() }}</a>
                @endif

                {{-- Next --}}
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}"
                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container hover:bg-primary hover:text-on-primary text-primary transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </a>
                @else
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-surface-container text-outline cursor-not-allowed">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

{{-- ══ Toast Container ══ --}}
<div id="toast-container"></div>

{{-- ══════════ MODAL TAMBAH USER ══════════ --}}
<div id="modalTambahUser" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-lg w-full rounded-xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="bg-primary px-space-lg py-space-md flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm text-on-primary flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[22px]">person_add</span>
                Tambah {{ $role === 'admin' ? 'Admin' : 'User' }} Baru
            </h3>
            <button onclick="document.getElementById('modalTambahUser').classList.add('hidden')"
                    class="text-on-primary/70 hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-space-lg flex flex-col gap-space-md">
            @csrf
            {{-- Role otomatis sesuai tab aktif --}}
            <input type="hidden" name="role" value="{{ $role }}"/>

            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">Nama Lengkap <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">person</span>
                    <input type="text" name="name" required placeholder="Nama lengkap"
                           class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">Email <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">mail</span>
                    <input type="email" name="email" required placeholder="email@contoh.com"
                           class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-space-md">
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">No Telepon</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">phone</span>
                        <input type="text" name="phone" placeholder="08xxxxxxxxxx"
                               class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                    </div>
                </div>
                <div class="flex flex-col gap-1.5">
                    <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">Role</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">badge</span>
                        <div class="w-full pl-11 pr-4 py-3 bg-surface-container border border-outline-variant rounded-xl text-sm text-on-surface font-semibold cursor-not-allowed">
                            {{ $role === 'admin' ? 'Admin' : 'User' }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">Alamat</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-3.5 text-outline text-[20px]">home</span>
                    <textarea name="address" rows="2" placeholder="Alamat lengkap (opsional)"
                              class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition resize-none"></textarea>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">Kata Sandi <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                    <input type="password" name="password" id="addPassword" required placeholder="Min. 8 karakter"
                           class="w-full pl-11 pr-12 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                    <button type="button" onclick="togglePw('addPassword','addEyeIcon')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[20px]" id="addEyeIcon">visibility</span>
                    </button>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">Konfirmasi Kata Sandi <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">lock</span>
                    <input type="password" name="password_confirmation" id="addPasswordConfirm" required placeholder="Ulangi kata sandi"
                           class="w-full pl-11 pr-12 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                    <button type="button" onclick="togglePw('addPasswordConfirm','addEyeIconConfirm')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[20px]" id="addEyeIconConfirm">visibility</span>
                    </button>
                </div>
            </div>
            <div class="flex justify-end gap-space-md pt-space-xs border-t border-surface-container">
                <button type="button" onclick="document.getElementById('modalTambahUser').classList.add('hidden')"
                        class="px-space-lg py-space-sm bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-space-lg py-space-sm bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded-lg transition-colors shadow-sm flex items-center gap-space-xs">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Tambah {{ $role === 'admin' ? 'Admin' : 'User' }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ MODAL EDIT USER ══════════ --}}
<div id="modalEditUser" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-lg w-full rounded-xl shadow-2xl overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="bg-primary px-space-lg py-space-md flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm text-on-primary flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[22px]">edit</span>
                Edit Data {{ $role === 'admin' ? 'Admin' : 'User' }}
            </h3>
            <button onclick="document.getElementById('modalEditUser').classList.add('hidden')"
                    class="text-on-primary/70 hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
        <form id="formEditUser" method="POST" class="p-space-lg flex flex-col gap-space-md">
            @csrf
            @method('PUT')
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">Nama Lengkap <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">person</span>
                    <input type="text" name="name" id="editName" required
                           class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">Email <span class="text-error">*</span></label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">mail</span>
                    <input type="email" name="email" id="editEmail" required
                           class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                </div>
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider">No Telepon</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">phone</span>
                    <input type="text" name="phone" id="editPhone" placeholder="08xxxxxxxxxx"
                           class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                </div>
            </div>
            <div class="flex justify-end gap-space-md pt-space-xs border-t border-surface-container">
                <button type="button" onclick="document.getElementById('modalEditUser').classList.add('hidden')"
                        class="px-space-lg py-space-sm bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-space-lg py-space-sm bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded-lg transition-colors shadow-sm flex items-center gap-space-xs">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════ MODAL KONFIRMASI RESET PASSWORD ══════════ --}}
<div id="modalResetPw" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-sm w-full rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-secondary px-space-lg py-space-md flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm text-on-secondary flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[22px]">lock_reset</span>
                Ganti Password
            </h3>
            <button onclick="document.getElementById('modalResetPw').classList.add('hidden')"
                    class="text-on-secondary/70 hover:text-on-secondary transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
        <div class="p-space-lg">
            <div class="flex items-start gap-3 mb-space-md">
                <div class="w-12 h-12 rounded-full bg-secondary-fixed flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-secondary text-[28px]">key</span>
                </div>
                <div>
                    <p class="text-sm text-on-surface">
                        Password <strong id="resetPwName" class="text-primary"></strong> akan direset menjadi:
                    </p>
                    <div class="mt-2 px-3 py-2 bg-surface-container rounded-lg border border-outline-variant">
                        <code class="text-secondary font-bold text-sm tracking-wider">juraganbatik345</code>
                    </div>
                    <p class="text-xs text-on-surface-variant mt-2">
                        <span class="material-symbols-outlined text-[14px] align-middle">info</span>
                        User perlu mengganti password setelah login.
                    </p>
                </div>
            </div>
            <form id="formResetPw" method="POST" class="flex justify-end gap-space-sm">
                @csrf
                @method('PATCH')
                <button type="button" onclick="document.getElementById('modalResetPw').classList.add('hidden')"
                        class="px-space-lg py-space-sm bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-space-lg py-space-sm bg-secondary hover:bg-primary text-on-secondary font-label-lg text-label-lg rounded-lg transition-colors shadow-sm flex items-center gap-space-xs">
                    <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                    Ya, Reset Password
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══════════ MODAL KONFIRMASI HAPUS USER ══════════ --}}
<div id="modalDeleteUser" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-sm w-full rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-error px-space-lg py-space-md flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm text-on-error flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[22px]">warning</span>
                Hapus Akun
            </h3>
            <button onclick="document.getElementById('modalDeleteUser').classList.add('hidden')"
                    class="text-on-error/70 hover:text-on-error transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
        <div class="p-space-lg">
            <div class="flex items-start gap-3 mb-space-md">
                <div class="w-12 h-12 rounded-full bg-error-container flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-error text-[28px]">person_remove</span>
                </div>
                <div>
                    <p class="text-sm text-on-surface font-semibold mb-1">Apakah Anda yakin?</p>
                    <p class="text-sm text-on-surface-variant">
                        Akun <strong id="deleteUserName" class="text-error"></strong> akan dihapus secara permanen beserta semua data terkait (keranjang, favorit, dll).
                    </p>
                    <p class="text-xs text-error font-bold mt-2">
                        <span class="material-symbols-outlined text-[14px] align-middle">error</span>
                        Tindakan ini tidak dapat dibatalkan!
                    </p>
                </div>
            </div>
            <form id="formDeleteUser" method="POST" class="flex justify-end gap-space-sm">
                @csrf
                @method('DELETE')
                <button type="button" onclick="document.getElementById('modalDeleteUser').classList.add('hidden')"
                        class="px-space-lg py-space-sm bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-space-lg py-space-sm bg-error hover:bg-red-700 text-on-error font-label-lg text-label-lg rounded-lg transition-colors shadow-sm flex items-center gap-space-xs">
                    <span class="material-symbols-outlined text-[18px]">delete_forever</span>
                    Ya, Hapus Akun
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══════════ MODAL KONFIRMASI HAPUS TERPILIH ══════════ --}}
<div id="modalBulkDelete" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-sm w-full rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-error px-space-lg py-space-md flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm text-on-error flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[22px]">warning</span>
                Hapus Banyak Akun
            </h3>
            <button onclick="document.getElementById('modalBulkDelete').classList.add('hidden')"
                    class="text-on-error/70 hover:text-on-error transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
        <div class="p-space-lg">
            <div class="flex items-start gap-3 mb-space-md">
                <div class="w-12 h-12 rounded-full bg-error-container flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-error text-[28px]">delete_sweep</span>
                </div>
                <div>
                    <p class="text-sm text-on-surface font-semibold mb-1">Apakah Anda yakin?</p>
                    <p class="text-sm text-on-surface-variant">
                        <strong id="bulkDeleteCount" class="text-error"></strong> akun yang dipilih akan dihapus secara permanen beserta semua data terkait.
                    </p>
                    <p class="text-xs text-error font-bold mt-2">
                        <span class="material-symbols-outlined text-[14px] align-middle">error</span>
                        Tindakan ini tidak dapat dibatalkan!
                    </p>
                </div>
            </div>
            <form id="formBulkDelete" action="{{ route('admin.users.destroyMultiple') }}" method="POST" class="flex justify-end gap-space-sm">
                @csrf
                @method('DELETE')
                <div id="bulkDeleteInputs"></div>
                <button type="button" onclick="document.getElementById('modalBulkDelete').classList.add('hidden')"
                        class="px-space-lg py-space-sm bg-surface-container hover:bg-surface-container-high text-on-surface font-label-lg text-label-lg rounded-lg transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="px-space-lg py-space-sm bg-error hover:bg-red-700 text-on-error font-label-lg text-label-lg rounded-lg transition-colors shadow-sm flex items-center gap-space-xs">
                    <span class="material-symbols-outlined text-[18px]">delete_forever</span>
                    Ya, Hapus Semua
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ══════════ MODAL LIHAT ALAMAT ══════════ --}}
<div id="modalAlamat" class="hidden fixed inset-0 z-50 flex items-center justify-center p-space-md bg-primary-container/40 backdrop-blur-sm">
    <div class="bg-surface-container-lowest max-w-sm w-full rounded-xl shadow-2xl overflow-hidden">
        <div class="bg-primary px-space-lg py-space-md flex items-center justify-between">
            <h3 class="font-headline-sm text-headline-sm text-on-primary flex items-center gap-space-xs">
                <span class="material-symbols-outlined text-[22px]">location_on</span>
                <span id="alamatTitle">Alamat</span>
            </h3>
            <button onclick="document.getElementById('modalAlamat').classList.add('hidden')"
                    class="text-on-primary/70 hover:text-on-primary transition-colors">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
        </div>
        <div class="p-space-lg">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-secondary text-[22px]">home</span>
                </div>
                <p id="alamatContent" class="text-sm text-on-surface leading-relaxed"></p>
            </div>
            <div class="flex justify-end mt-space-md">
                <button type="button" onclick="document.getElementById('modalAlamat').classList.add('hidden')"
                        class="px-space-lg py-space-sm bg-primary hover:bg-secondary text-on-primary font-label-lg text-label-lg rounded-lg transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ── Toggle Password Visibility ──
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

    // ── Lihat Alamat Lengkap ──
    function showAlamat(name, address) {
        document.getElementById('alamatTitle').textContent = 'Alamat ' + name;
        document.getElementById('alamatContent').textContent = address;
        document.getElementById('modalAlamat').classList.remove('hidden');
    }

    // ── Edit Modal ──
    function openEditModal(id, name, email, phone) {
        document.getElementById('formEditUser').action = '/admin/users/' + id;
        document.getElementById('editName').value  = name || '';
        document.getElementById('editEmail').value = email || '';
        document.getElementById('editPhone').value = phone || '';
        document.getElementById('modalEditUser').classList.remove('hidden');
    }

    // ── Reset Password Modal ──
    function confirmResetPassword(id, name) {
        document.getElementById('resetPwName').textContent = name;
        document.getElementById('formResetPw').action = '/admin/users/' + id + '/reset-password';
        document.getElementById('modalResetPw').classList.remove('hidden');
    }

    // ── Delete User Modal ──
    function confirmDeleteUser(id, name) {
        document.getElementById('deleteUserName').textContent = name;
        document.getElementById('formDeleteUser').action = '/admin/users/' + id;
        document.getElementById('modalDeleteUser').classList.remove('hidden');
    }

    // ── Checkbox All ──
    function toggleCheckAll(el) {
        document.querySelectorAll('.user-check').forEach(cb => cb.checked = el.checked);
        updateBulkButton();
    }

    function updateBulkButton() {
        const checked = document.querySelectorAll('.user-check:checked');
        const btn = document.getElementById('btnHapusTerpilih');
        const label = document.getElementById('labelHapusTerpilih');

        if (checked.length > 0) {
            btn.classList.remove('hidden');
            label.textContent = 'Hapus Terpilih (' + checked.length + ')';
        } else {
            btn.classList.add('hidden');
        }

        // Update checkAll state
        const allChecks = document.querySelectorAll('.user-check');
        const checkAll = document.getElementById('checkAll');
        if (allChecks.length > 0) {
            checkAll.checked = checked.length === allChecks.length;
            checkAll.indeterminate = checked.length > 0 && checked.length < allChecks.length;
        }
    }

    // ── Hapus Terpilih (Bulk) ──
    function hapusTerpilih() {
        const checked = document.querySelectorAll('.user-check:checked');
        if (checked.length === 0) return;

        document.getElementById('bulkDeleteCount').textContent = checked.length;

        // Build hidden inputs
        const container = document.getElementById('bulkDeleteInputs');
        container.innerHTML = '';
        checked.forEach(cb => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = cb.value;
            container.appendChild(input);
        });

        document.getElementById('modalBulkDelete').classList.remove('hidden');
    }

    // ── Toast ──
    function showToast(message, type) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        toast.style.position = 'relative';
        const iconName = type === 'success' ? 'check_circle' : 'error';
        const iconColor = type === 'success' ? '#386a20' : '#ba1a1a';
        toast.innerHTML =
            '<div class="toast-icon"><span class="material-symbols-outlined" style="font-size:20px;color:' + iconColor + '">' + iconName + '</span></div>' +
            '<div style="flex:1;min-width:0"><p style="font-size:13px;font-weight:600;color:#311908;margin:0">' + (type === 'success' ? 'Berhasil' : 'Gagal') + '</p>' +
            '<p style="font-size:12px;color:#50443e;margin:2px 0 0">' + message + '</p></div>' +
            '<button onclick="this.parentElement.remove()" style="background:none;border:none;cursor:pointer;padding:4px;color:#82746d"><span class="material-symbols-outlined" style="font-size:18px">close</span></button>' +
            '<div class="toast-progress"></div>';
        container.appendChild(toast);
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => {
            toast.classList.add('hide');
            setTimeout(() => toast.remove(), 400);
        }, 4000);
    }

    // ── Flash Messages ──
    document.addEventListener('DOMContentLoaded', function() {
        @if(session('success'))
            showToast(@json(session('success')), 'success');
        @endif
        @if(session('error'))
            showToast(@json(session('error')), 'error');
        @endif
        @if($errors->any())
            @foreach($errors->all() as $error)
                showToast(@json($error), 'error');
            @endforeach
            document.getElementById('modalTambahUser')?.classList.remove('hidden');
        @endif
    });
</script>
@endpush
