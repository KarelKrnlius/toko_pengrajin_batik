@extends('layouts.app')
@section('title', 'Buat Pesanan Baru — Juragan Batik')

@section('content')
{{-- ══ Admin Header ══ --}}
<div class="w-full bg-gradient-to-r from-primary via-primary-container to-secondary py-8">
    <div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin flex flex-col sm:flex-row items-start sm:items-center justify-between gap-space-md">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('admin.orders.index') }}" class="text-secondary-fixed hover:text-white transition-colors flex items-center gap-1 text-xs font-bold tracking-wider uppercase">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    Kembali ke Daftar Pesanan
                </a>
            </div>
            <h1 class="font-headline-md text-headline-md text-on-primary font-bold">
                Input Pesanan Baru
            </h1>
            <p class="font-body-sm text-body-sm text-primary-fixed-dim mt-1">
                Pilih atau cari email pelanggan untuk pengisian otomatis nomor telepon, nama, dan alamat pengiriman.
            </p>
        </div>
        <div class="flex items-center gap-space-sm flex-shrink-0">
            <a href="{{ route('admin.orders.index') }}"
               class="inline-flex items-center justify-center gap-space-xs px-space-md bg-on-primary/10 hover:bg-on-primary/20 text-on-primary font-label-lg text-label-lg rounded transition-colors h-10">
                <span class="material-symbols-outlined text-[20px]">receipt_long</span>
                <span class="hidden sm:inline">Daftar Pesanan</span>
            </a>
        </div>
    </div>
</div>

{{-- Floating Toast Notification --}}
<div id="toastNotification" class="fixed top-6 right-6 z-50 transform -translate-y-6 opacity-0 pointer-events-none transition-all duration-300 max-w-md bg-error text-white px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3">
    <span class="material-symbols-outlined text-white text-[24px] flex-shrink-0" id="toastIcon">warning</span>
    <div id="toastMessage" class="text-sm font-semibold">Pesanan harus memiliki minimal satu produk.</div>
    <button type="button" onclick="hideToast()" class="ml-2 text-white/80 hover:text-white">
        <span class="material-symbols-outlined text-[18px]">close</span>
    </button>
</div>

<div class="max-w-[1440px] mx-auto px-margin-mobile lg:px-margin py-space-xl">

    {{-- Error Alert --}}
    @if ($errors->any())
    <div class="p-space-md mb-space-lg bg-error-container rounded-xl border border-error/30 text-on-surface">
        <div class="flex items-center gap-2 font-bold text-error mb-1">
            <span class="material-symbols-outlined text-[20px]">error</span>
            <span>Terdapat beberapa kesalahan input:</span>
        </div>
        <ul class="list-disc list-inside text-xs text-on-surface-variant space-y-0.5 ml-6">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.orders.store') }}" method="POST" id="orderForm">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-space-lg">

            {{-- ══ SISI KIRI (2 Kolom): Form Input ══ --}}
            <div class="lg:col-span-2 flex flex-col gap-space-lg">

                {{-- 1. DATA PELANGGAN --}}
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-space-lg">
                    <div class="flex items-center gap-2 pb-3 mb-space-md border-b border-outline-variant">
                        <span class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center font-bold">1</span>
                        <div>
                            <h2 class="font-title-lg text-title-lg text-primary font-bold">Data Pelanggan</h2>
                            <p class="text-xs text-on-surface-variant">Cari email pelanggan untuk mengisi data secara otomatis</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                        {{-- Searchable Email Pelanggan (Search & Instant Dropdown, Hanya Email) --}}
                        <div class="md:col-span-2">
                            <label class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Pilih Email Pelanggan <span class="text-error">*</span>
                            </label>

                            <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id') }}" required/>

                            <div class="relative" id="userComboboxWrap">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px] pointer-events-none">search</span>
                                <input type="text" id="user_email_search" autocomplete="off"
                                       placeholder="Ketik email"
                                       value="{{ old('user_id') ? ($users->firstWhere('id', old('user_id'))?->email ?? '') : '' }}"
                                       class="w-full pl-11 pr-10 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary transition"
                                       onclick="openUserDropdown()" oninput="openUserDropdown()" onfocus="openUserDropdown()" onkeydown="onUserKeydown(event)"/>
                                <button type="button" onclick="toggleUserDropdown()" class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary">
                                    <span class="material-symbols-outlined text-[20px] pointer-events-none" id="userDropdownArrow">expand_more</span>
                                </button>

                                {{-- Daftar Dropdown Email --}}
                                <div id="userDropdownList" class="hidden absolute left-0 right-0 top-full mt-1 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-2xl z-50 max-h-56 overflow-y-auto py-1">
                                    @foreach($users as $user)
                                        <button type="button"
                                                class="user-option w-full text-left px-4 py-2.5 hover:bg-surface-container cursor-pointer transition-colors text-sm text-on-surface border-b border-surface-container/50 last:border-0"
                                                data-id="{{ $user->id }}"
                                                data-email="{{ $user->email }}"
                                                data-name="{{ $user->name }}"
                                                data-phone="{{ $user->phone ?? '' }}"
                                                data-address="{{ $user->address ?? '' }}">
                                            <span class="font-medium text-primary">{{ $user->email }}</span>
                                        </button>
                                    @endforeach
                                    <div id="userEmptyMsg" class="hidden px-4 py-3 text-xs text-outline italic text-center">
                                        Email pelanggan tidak ditemukan
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nama Pelanggan (Bisa Diubah) --}}
                        <div>
                            <label for="customer_name" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Nama Pelanggan <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">person</span>
                                <input type="text" name="customer_name" id="customer_name" required
                                       value="{{ old('customer_name') }}"
                                       placeholder="Nama pelanggan"
                                       class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                            </div>
                        </div>

                        {{-- Nomor Telepon Pelanggan --}}
                        <div>
                            <label for="customer_phone" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Nomor Telepon / WhatsApp <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">phone</span>
                                <input type="text" name="customer_phone" id="customer_phone"
                                       value="{{ old('customer_phone') }}" required
                                       placeholder="Contoh: 081234567890"
                                       class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                            </div>
                        </div>

                        {{-- Alamat Pengiriman Lengkap --}}
                        <div class="md:col-span-2">
                            <label for="customer_address" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Alamat Pengiriman Lengkap <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-3.5 text-outline text-[20px]">home_pin</span>
                                <textarea name="customer_address" id="customer_address" rows="3" required
                                          placeholder="Nama jalan, nomor rumah, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos"
                                          class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition resize-none">{{ old('customer_address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. PILIH PRODUK BATIK (Dynamic Item Rows) --}}
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-space-lg">
                    <div class="flex items-center justify-between pb-3 mb-space-md border-b border-outline-variant">
                        <div class="flex items-center gap-2">
                            <span class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center font-bold">2</span>
                            <div>
                                <h2 class="font-title-lg text-title-lg text-primary font-bold">Produk Batik</h2>
                                <p class="text-xs text-on-surface-variant">Pilih motif kain/busana batik dan kuantitas</p>
                            </div>
                        </div>
                        <button type="button" onclick="addProductRow()"
                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary hover:bg-secondary text-on-primary text-xs font-bold rounded-lg transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[16px]">add</span>
                            Tambah Baris Produk
                        </button>
                    </div>

                    <div id="product-rows-container" class="flex flex-col gap-3">
                        {{-- Row template disisipkan oleh JS --}}
                    </div>

                    <div class="mt-4 pt-3 border-t border-surface-container flex items-center justify-between text-sm">
                        <span class="text-on-surface-variant font-medium">Subtotal Produk:</span>
                        <span id="subtotal-display" class="font-title-lg text-title-lg text-primary font-bold">Rp 0</span>
                    </div>
                </div>

                {{-- 3. KURIR, ONGKIR & PEMBAYARAN --}}
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-space-lg">
                    <div class="flex items-center gap-2 pb-3 mb-space-md border-b border-outline-variant">
                        <span class="w-8 h-8 rounded-lg bg-secondary/10 text-secondary flex items-center justify-center font-bold">3</span>
                        <div>
                            <h2 class="font-title-lg text-title-lg text-primary font-bold">Logistik & Pembayaran</h2>
                            <p class="text-xs text-on-surface-variant">Atur ekspedisi kurir, ongkir, nomor resi, dan metode pembayaran</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                        {{-- Ekspedisi Kurir (Hanya JNE Express, J&T, Ambil Mandiri) --}}
                        <div>
                            <label for="shipping_courier" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Ekspedisi Kurir <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px] pointer-events-none">local_shipping</span>
                                <select name="shipping_courier" id="shipping_courier" required onchange="onCourierChange()"
                                        class="w-full pl-11 pr-8 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer">
                                    <option value="JNE" {{ old('shipping_courier', 'JNE') == 'JNE' ? 'selected' : '' }}>JNE</option>
                                    <option value="J&T Express" {{ old('shipping_courier') == 'J&T Express' ? 'selected' : '' }}>J&T Express</option>
                                    <option value="Ambil Mandiri" {{ old('shipping_courier') == 'Ambil Mandiri' ? 'selected' : '' }}>Ambil Mandiri</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        {{-- Biaya Ongkir (Dikosongkan agar bisa langsung diketik) --}}
                        <div id="shipping_cost_wrap">
                            <label for="shipping_cost" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Biaya Ongkir (Rp) <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">payments</span>
                                <input type="number" name="shipping_cost" id="shipping_cost"
                                       value="{{ old('shipping_cost') }}" min="0" required
                                       oninput="recalculateTotal()"
                                       placeholder="0"
                                       class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                            </div>
                        </div>

                        {{-- Nomor Resi (Wajib untuk JNE & J&T Express, Tidak Muncul untuk Ambil Mandiri) --}}
                        <div id="tracking_number_wrap">
                            <label for="tracking_number" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Nomor Resi / Tracking AWB <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">barcode</span>
                                <input type="text" name="tracking_number" id="tracking_number" required
                                       value="{{ old('tracking_number') }}"
                                       placeholder="Contoh: JNE8239019283"
                                       class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                            </div>
                        </div>

                        {{-- Metode Pembayaran (Hanya Transfer Bank dan QRIS) --}}
                        <div>
                            <label for="payment_method" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Metode Pembayaran <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px] pointer-events-none">account_balance</span>
                                <select name="payment_method" id="payment_method" required
                                        class="w-full pl-11 pr-8 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer">
                                    <option value="transfer_bank" {{ old('payment_method') == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="qris" {{ old('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        {{-- Status Awal Pesanan --}}
                        <div>
                            <label for="status" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Status Awal Pesanan <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px] pointer-events-none">flag</span>
                                <select name="status" id="status" required
                                        class="w-full pl-11 pr-8 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary appearance-none cursor-pointer">
                                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending (Menunggu Konfirmasi)</option>
                                    <option value="shipped" id="status_shipped_option" {{ old('status') == 'shipped' ? 'selected' : '' }}>Shipped (Dalam Pengiriman)</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed (Selesai)</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px] pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        {{-- Catatan Pesanan --}}
                        <div>
                            <label for="notes" class="font-label-md text-label-md text-primary font-bold uppercase tracking-wider block mb-1">
                                Catatan Khusus (Opsional)
                            </label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">edit_note</span>
                                <input type="text" name="notes" id="notes"
                                       value="{{ old('notes') }}"
                                       placeholder="Contoh: Motif parang dikemas dengan pita batik"
                                       class="w-full pl-11 pr-4 py-3 bg-surface-container-low border border-outline-variant rounded-xl text-sm text-on-surface placeholder:text-outline focus:outline-none focus:ring-2 focus:ring-secondary transition"/>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ SISI KANAN (1 Kolom): Ringkasan & Submit ══ --}}
            <div class="lg:col-span-1">
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant p-space-lg sticky top-28">
                    <h3 class="font-headline-sm text-headline-sm text-primary font-bold pb-2 border-b border-outline-variant mb-4">
                        Ringkasan Pesanan
                    </h3>

                    <div class="flex flex-col gap-3 text-sm">
                        <div class="flex justify-between items-center text-on-surface-variant">
                            <span>Total Item Produk:</span>
                            <span id="total-qty-badge" class="font-bold text-primary">0 item</span>
                        </div>
                        <div class="flex justify-between items-center text-on-surface-variant">
                            <span>Subtotal Produk:</span>
                            <span id="summary-subtotal" class="font-semibold text-on-surface">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-on-surface-variant">
                            <span>Biaya Kirim (Ongkir):</span>
                            <span id="summary-shipping" class="font-semibold text-on-surface">Rp 0</span>
                        </div>
                        <div class="pt-3 border-t-2 border-dashed border-outline-variant flex justify-between items-center">
                            <span class="font-bold text-primary">Total Tagihan:</span>
                            <span id="summary-grand-total" class="font-title-lg text-title-lg text-secondary font-bold">Rp 0</span>
                        </div>
                    </div>

                    {{-- Hybrid WhatsApp Info --}}
                    <div class="mt-space-md p-space-sm bg-surface-container rounded-lg border border-outline-variant/60 flex items-start gap-2">
                        <span class="material-symbols-outlined text-[18px] text-green-700 flex-shrink-0 mt-0.5">chat</span>
                        <p class="text-[11px] text-on-surface-variant leading-relaxed">
                            Setelah disimpan, Anda dapat mengklik tombol <strong>Konfirmasi WhatsApp</strong> di tabel pesanan untuk mengirim rincian lengkap ke pelanggan.
                        </p>
                    </div>

                    {{-- Submit & Batal --}}
                    <div class="flex flex-col gap-2 mt-space-lg">
                        <button type="submit"
                                class="w-full py-3.5 bg-primary hover:bg-secondary text-on-primary font-bold text-sm tracking-wider uppercase rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[20px]">save</span>
                            <span>Simpan Pesanan</span>
                        </button>
                        <a href="{{ route('admin.orders.index') }}"
                           class="w-full py-2.5 bg-surface-container hover:bg-surface-container-high text-on-surface font-semibold text-xs tracking-wider uppercase rounded-xl transition-colors text-center">
                            Batalkan
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

{{-- Data Produk untuk JavaScript --}}
<script>
    const availableProducts = @json($products);
</script>

@endsection

@push('scripts')
<script>
    // ══════════════════════════════════════════════════════════════════
    // 1. SEARCHABLE EMAIL COMBOBOX & AUTO-FILL
    // ══════════════════════════════════════════════════════════════════
    const emailSearchInput = document.getElementById('user_email_search');
    const userDropdownList = document.getElementById('userDropdownList');
    const userEmptyMsg = document.getElementById('userEmptyMsg');
    const userIdInput = document.getElementById('user_id');
    const nameInput = document.getElementById('customer_name');
    const phoneInput = document.getElementById('customer_phone');
    const addressInput = document.getElementById('customer_address');
    let userActiveIndex = 0;

    function visibleUserOptions() {
        return [...userDropdownList.querySelectorAll('.user-option')].filter((opt) => opt.style.display !== 'none');
    }

    function highlightUserOption(index) {
        const options = visibleUserOptions();
        if (options.length === 0) {
            userActiveIndex = 0;
            return;
        }

        userActiveIndex = (index + options.length) % options.length;
        options.forEach((opt, i) => {
            opt.classList.toggle('bg-secondary/10', i === userActiveIndex);
        });
        options[userActiveIndex].scrollIntoView({ block: 'nearest' });
    }

    function openUserDropdown() {
        userDropdownList.classList.remove('hidden');
        filterUserDropdown();
    }

    function toggleUserDropdown() {
        if (userDropdownList.classList.contains('hidden')) {
            openUserDropdown();
            emailSearchInput.focus();
        } else {
            userDropdownList.classList.add('hidden');
        }
    }

    function filterUserDropdown() {
        const query = emailSearchInput.value.toLowerCase().trim();
        const options = userDropdownList.querySelectorAll('.user-option');
        let visibleCount = 0;

        options.forEach((opt) => {
            const email = (opt.dataset.email || '').toLowerCase();
            const matched = email.includes(query);
            opt.style.display = matched ? 'block' : 'none';
            if (matched) {
                visibleCount++;
            }
        });

        userEmptyMsg.classList.toggle('hidden', visibleCount > 0);
        highlightUserOption(0);
    }

    function selectUserFromOption(option) {
        if (!option) {
            return;
        }

        userIdInput.value = option.dataset.id || '';
        emailSearchInput.value = option.dataset.email || '';
        userDropdownList.classList.add('hidden');

        nameInput.value = option.dataset.name || '';
        phoneInput.value = option.dataset.phone || '';
        addressInput.value = option.dataset.address || '';

        [nameInput, phoneInput, addressInput].forEach((el) => {
            el.classList.add('ring-2', 'ring-secondary');
            setTimeout(() => el.classList.remove('ring-2', 'ring-secondary'), 800);
        });
    }

    function onUserKeydown(event) {
        const options = visibleUserOptions();

        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            const wasHidden = userDropdownList.classList.contains('hidden');
            if (wasHidden) {
                openUserDropdown();
                return;
            }
            highlightUserOption(userActiveIndex + (event.key === 'ArrowDown' ? 1 : -1));
            return;
        }

        if (event.key === 'Escape') {
            userDropdownList.classList.add('hidden');
            return;
        }

        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();
        const query = emailSearchInput.value.toLowerCase().trim();
        const exact = options.find((opt) => (opt.dataset.email || '').toLowerCase() === query);
        selectUserFromOption(exact || options[userActiveIndex] || options[0]);
    }

    userDropdownList.addEventListener('click', function (event) {
        const option = event.target.closest('.user-option');
        if (option) {
            selectUserFromOption(option);
        }
    });

    userDropdownList.addEventListener('mousemove', function (event) {
        const option = event.target.closest('.user-option');
        if (!option || option.style.display === 'none') {
            return;
        }
        const index = visibleUserOptions().indexOf(option);
        if (index >= 0) {
            highlightUserOption(index);
        }
    });

    document.addEventListener('click', function (event) {
        const wrap = document.getElementById('userComboboxWrap');
        if (wrap && !wrap.contains(event.target)) {
            userDropdownList.classList.add('hidden');
        }

        if (!event.target.closest('.product-combobox')) {
            document.querySelectorAll('.product-dropdown').forEach((list) => list.classList.add('hidden'));
        }
    });

    // ══════════════════════════════════════════════════════════════════
    // NOTIFIKASI TOAST & ALERT
    // ══════════════════════════════════════════════════════════════════
    function showToast(message, isError = true) {
        const toast = document.getElementById('toastNotification');
        const toastMsg = document.getElementById('toastMessage');
        const toastIcon = document.getElementById('toastIcon');
        if (!toast || !toastMsg) {
            return;
        }
        toastMsg.textContent = message;
        if (toastIcon) {
            toastIcon.textContent = isError ? 'warning' : 'check_circle';
        }
        toast.className = `fixed top-6 right-6 z-50 transform translate-y-0 opacity-100 transition-all duration-300 max-w-md px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-3 ${
            isError ? 'bg-error text-white' : 'bg-green-700 text-white'
        }`;
        clearTimeout(window.toastTimer);
        window.toastTimer = setTimeout(() => {
            hideToast();
        }, 4000);
    }

    function hideToast() {
        const toast = document.getElementById('toastNotification');
        if (toast) {
            toast.classList.add('-translate-y-6', 'opacity-0', 'pointer-events-none');
            toast.classList.remove('translate-y-0', 'opacity-100');
        }
    }

    // ══════════════════════════════════════════════════════════════════
    // 2. KONTROL KURIR & NOMOR RESI DINAMIS
    // ══════════════════════════════════════════════════════════════════
    function onCourierChange() {
        const courier = document.getElementById('shipping_courier').value;
        const trackingWrap = document.getElementById('tracking_number_wrap');
        const trackingInput = document.getElementById('tracking_number');
        const shippingCostInput = document.getElementById('shipping_cost');
        const statusShippedOpt = document.getElementById('status_shipped_option');

        if (courier === 'Ambil Mandiri') {
            // Ambil mandiri: Sembunyikan resi & hapus required
            trackingWrap.classList.add('hidden');
            trackingInput.removeAttribute('required');
            trackingInput.value = '';
            shippingCostInput.value = 0; // Tidak ada ongkir
            shippingCostInput.setAttribute('readonly', 'readonly');
            if (statusShippedOpt) {
                statusShippedOpt.textContent = 'Shipped (Siap Diambil)';
            }
        } else {
            // JNE / J&T Express: Munculkan resi & wajib diisi
            trackingWrap.classList.remove('hidden');
            trackingInput.setAttribute('required', 'required');
            shippingCostInput.removeAttribute('readonly');
            if (shippingCostInput.value === '0') {
                shippingCostInput.value = ''; // Kosongkan agar admin bisa langsung mengetik
            }
            if (statusShippedOpt) {
                statusShippedOpt.textContent = 'Shipped (Dalam Pengiriman)';
            }
        }

        recalculateTotal();
    }

    // ══════════════════════════════════════════════════════════════════
    // 3. DYNAMIC PRODUCT ROWS & REAL-TIME CALCULATION
    // ══════════════════════════════════════════════════════════════════
    let rowIndex = 0;
    const container = document.getElementById('product-rows-container');
    const selectedProducts = {};

    function formatRupiah(number) {
        return 'Rp ' + Number(number || 0).toLocaleString('id-ID');
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;');
    }

    function findProduct(id) {
        return availableProducts.find((product) => String(product.id) === String(id));
    }

    function matchingProducts(query) {
        const keyword = query.toLowerCase().trim();
        return availableProducts.filter((product) => {
            const name = String(product.name || '').toLowerCase();
            const category = String(product.category || '').toLowerCase();
            return keyword === '' || name.includes(keyword) || category.includes(keyword);
        });
    }

    function addProductRow(productId = '', quantity = 1) {
        const index = rowIndex;
        const rowId = `product-row-${index}`;
        const selected = productId ? findProduct(productId) : null;

        const rowHtml = `
            <div id="${rowId}" class="product-row p-3 bg-surface-container-low rounded-xl border border-outline-variant flex flex-col md:flex-row items-start gap-3" data-index="${index}">
                <div class="flex-1 w-full product-combobox relative">
                    <label class="text-[11px] font-bold text-outline uppercase block mb-1">Motif / Kain Batik</label>
                    <input type="hidden" name="items[${index}][product_id]" id="prod-id-${index}" value="${selected ? selected.id : ''}">
                    <input type="text" id="prod-search-${index}" autocomplete="off"
                           placeholder="Ketik nama batik"
                           value="${selected ? escapeHtml(selected.name) : ''}"
                           oninput="filterProductDropdown(${index})"
                           onfocus="openProductDropdown(${index})"
                           onkeydown="onProductKeydown(event, ${index})"
                           class="product-search w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary">
                    <p id="prod-error-${index}" class="hidden text-[11px] text-error mt-1">Pilih produk dari hasil pencarian.</p>
                    <div id="prod-dropdown-${index}" class="product-dropdown hidden absolute left-0 right-0 top-full mt-1 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-2xl z-50 max-h-56 overflow-y-auto py-1"></div>
                </div>

                <div class="w-full md:w-32">
                    <div class="flex items-center justify-between mb-1">
                        <label class="text-[11px] font-bold text-outline uppercase block">Jumlah</label>
                        <span id="prod-stock-badge-${index}" class="text-[10px] text-outline font-semibold"></span>
                    </div>
                    <input type="number" name="items[${index}][quantity]" value="${quantity}" min="1" required
                           id="prod-qty-${index}" oninput="onQuantityChange(${index})"
                           class="w-full px-3 py-2 bg-surface-container-lowest border border-outline-variant rounded-lg text-sm text-center text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                    <p id="prod-qty-warning-${index}" class="hidden text-[11px] text-error font-semibold mt-1 leading-tight"></p>
                </div>

                <div class="w-full md:w-32 text-left md:text-right">
                    <label class="text-[11px] font-bold text-outline uppercase block mb-1">Harga Satuan</label>
                    <span id="prod-price-${index}" class="text-sm font-semibold text-primary block py-2">Rp 0</span>
                </div>

                <div class="w-full md:w-36 text-left md:text-right">
                    <label class="text-[11px] font-bold text-outline uppercase block mb-1">Subtotal</label>
                    <span id="prod-subtotal-${index}" class="text-sm font-bold text-secondary block py-2">Rp 0</span>
                </div>

                <div class="pt-5">
                    <button type="button" onclick="removeProductRow('${rowId}')"
                            class="w-8 h-8 rounded-lg bg-surface-container hover:bg-error hover:text-white text-error flex items-center justify-center transition-colors"
                            title="Hapus item ini">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </div>
        `;

        container.insertAdjacentHTML('beforeend', rowHtml);
        const dropdown = document.getElementById(`prod-dropdown-${index}`);
        dropdown.addEventListener('click', function (event) {
            const option = event.target.closest('.product-option');
            if (option) {
                selectProduct(index, option.dataset.id);
            }
        });

        rowIndex++;
        if (selected) {
            applySelectedProduct(index, selected);
        } else {
            recalculateTotal();
        }
    }

    function renderProductOptions(index) {
        const search = document.getElementById(`prod-search-${index}`);
        const dropdown = document.getElementById(`prod-dropdown-${index}`);
        const matches = matchingProducts(search.value);

        if (matches.length === 0) {
            dropdown.innerHTML = '<div class="px-4 py-3 text-xs text-outline italic text-center">Produk batik tidak ditemukan</div>';
            dropdown.dataset.active = '0';
            return;
        }

        dropdown.innerHTML = matches.map((product, optionIndex) => `
            <button type="button" class="product-option w-full text-left px-4 py-2.5 hover:bg-surface-container transition-colors border-b border-surface-container/50 last:border-0"
                    data-id="${product.id}" data-option-index="${optionIndex}">
                <span class="font-medium text-primary block">${escapeHtml(product.name)}</span>
                <span class="block text-[11px] text-on-surface-variant">${escapeHtml(product.category || 'Batik')} · Stok ${product.stock} · ${formatRupiah(product.price)}</span>
            </button>
        `).join('');
        highlightProductOption(index, 0);
    }

    function highlightProductOption(index, optionIndex) {
        const dropdown = document.getElementById(`prod-dropdown-${index}`);
        const options = [...dropdown.querySelectorAll('.product-option')];
        if (options.length === 0) {
            dropdown.dataset.active = '0';
            return;
        }

        const nextIndex = (optionIndex + options.length) % options.length;
        dropdown.dataset.active = String(nextIndex);
        options.forEach((option, i) => {
            option.classList.toggle('bg-secondary/10', i === nextIndex);
        });
        options[nextIndex].scrollIntoView({ block: 'nearest' });
    }

    function openProductDropdown(index) {
        document.querySelectorAll('.product-dropdown').forEach((list) => {
            if (list.id !== `prod-dropdown-${index}`) {
                list.classList.add('hidden');
            }
        });
        const dropdown = document.getElementById(`prod-dropdown-${index}`);
        dropdown.classList.remove('hidden');
        renderProductOptions(index);
    }

    function filterProductDropdown(index) {
        const hidden = document.getElementById(`prod-id-${index}`);
        const search = document.getElementById(`prod-search-${index}`);
        const selected = selectedProducts[index];
        if (!selected || selected.name !== search.value) {
            hidden.value = '';
            delete selectedProducts[index];
            document.getElementById(`prod-price-${index}`).textContent = formatRupiah(0);
            const stockBadge = document.getElementById(`prod-stock-badge-${index}`);
            if (stockBadge) stockBadge.textContent = '';
            updateRowSubtotal(index);
        }
        openProductDropdown(index);
    }

    function applySelectedProduct(index, product) {
        selectedProducts[index] = product;
        document.getElementById(`prod-id-${index}`).value = product.id;
        document.getElementById(`prod-search-${index}`).value = product.name;
        document.getElementById(`prod-dropdown-${index}`).classList.add('hidden');
        document.getElementById(`prod-error-${index}`).classList.add('hidden');

        const qtyInput = document.getElementById(`prod-qty-${index}`);
        const warning = document.getElementById(`prod-qty-warning-${index}`);
        const stockBadge = document.getElementById(`prod-stock-badge-${index}`);

        if (stockBadge) {
            stockBadge.textContent = `(Maks: ${product.stock})`;
        }

        qtyInput.max = product.stock;
        if (product.stock <= 0) {
            qtyInput.value = 0;
            qtyInput.disabled = true;
            if (warning) {
                warning.textContent = 'Stok habis, tidak dapat dipesan';
                warning.classList.remove('hidden');
            }
        } else {
            qtyInput.disabled = false;
            if (Number(qtyInput.value) > product.stock) {
                qtyInput.value = product.stock;
                if (warning) {
                    warning.textContent = `Jumlah melebihi stok! Maks: ${product.stock}`;
                    warning.classList.remove('hidden');
                }
            } else {
                if (warning) warning.classList.add('hidden');
            }
            if (Number(qtyInput.value) < 1) {
                qtyInput.value = 1;
            }
        }

        document.getElementById(`prod-price-${index}`).textContent = formatRupiah(product.price);
        updateRowSubtotal(index);
    }

    function selectProduct(index, productId) {
        const product = findProduct(productId);
        if (!product) {
            return;
        }
        applySelectedProduct(index, product);
    }

    function onProductKeydown(event, index) {
        const dropdown = document.getElementById(`prod-dropdown-${index}`);
        const options = [...dropdown.querySelectorAll('.product-option')];

        if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
            event.preventDefault();
            const wasHidden = dropdown.classList.contains('hidden');
            if (wasHidden) {
                openProductDropdown(index);
                return;
            }
            const current = Number(dropdown.dataset.active || 0);
            highlightProductOption(index, current + (event.key === 'ArrowDown' ? 1 : -1));
            return;
        }

        if (event.key === 'Escape') {
            dropdown.classList.add('hidden');
            return;
        }

        if (event.key !== 'Enter') {
            return;
        }

        event.preventDefault();
        if (dropdown.classList.contains('hidden')) {
            openProductDropdown(index);
        }

        const freshOptions = [...document.getElementById(`prod-dropdown-${index}`).querySelectorAll('.product-option')];
        const query = document.getElementById(`prod-search-${index}`).value.toLowerCase().trim();
        const exact = freshOptions.find((option) => {
            const product = findProduct(option.dataset.id);
            return product && String(product.name).toLowerCase() === query;
        });
        const active = Number(document.getElementById(`prod-dropdown-${index}`).dataset.active || 0);
        const chosen = exact || freshOptions[active] || freshOptions[0];
        if (chosen) {
            selectProduct(index, chosen.dataset.id);
        }
    }

    function removeProductRow(rowId) {
        const row = document.getElementById(rowId);
        if (!row) {
            return;
        }
        const totalRows = container.querySelectorAll('.product-row').length;
        if (totalRows <= 1) {
            showToast('Pesanan harus memiliki minimal satu produk.', true);
            return;
        }
        delete selectedProducts[row.dataset.index];
        row.remove();
        recalculateTotal();
    }

    function onQuantityChange(index) {
        const product = selectedProducts[index];
        const qtyInput = document.getElementById(`prod-qty-${index}`);
        const warning = document.getElementById(`prod-qty-warning-${index}`);
        let qty = Number(qtyInput.value);

        if (product) {
            if (qty > product.stock) {
                // Jangan izinkan ditambahkan melebihi stok, otomatis batasi ke stok dan beri keterangan
                qtyInput.value = product.stock;
                if (warning) {
                    warning.textContent = `Jumlah melebihi stok! Maks: ${product.stock}`;
                    warning.classList.remove('hidden');
                }
            } else if (qty < 1 && qtyInput.value !== '') {
                qtyInput.value = 1;
                if (warning) warning.classList.add('hidden');
            } else {
                if (warning) warning.classList.add('hidden');
            }
        }
        updateRowSubtotal(index);
    }

    function updateRowSubtotal(index) {
        const product = selectedProducts[index];
        const price = product ? Number(product.price || 0) : 0;
        const qty = Number(document.getElementById(`prod-qty-${index}`)?.value || 0);
        const subtotalEl = document.getElementById(`prod-subtotal-${index}`);
        if (subtotalEl) {
            subtotalEl.textContent = formatRupiah(price * qty);
        }
        recalculateTotal();
    }

    function recalculateTotal() {
        let totalItems = 0;
        let subtotalProducts = 0;

        container.querySelectorAll('.product-row').forEach((row) => {
            const index = row.dataset.index;
            const product = selectedProducts[index];
            const qty = Number(document.getElementById(`prod-qty-${index}`)?.value || 0);
            if (product) {
                totalItems += qty;
                subtotalProducts += Number(product.price || 0) * qty;
            }
        });

        const shippingCost = Number(document.getElementById('shipping_cost')?.value || 0);
        const grandTotal = subtotalProducts + shippingCost;

        document.getElementById('subtotal-display').textContent = formatRupiah(subtotalProducts);
        document.getElementById('total-qty-badge').textContent = totalItems + ' item';
        document.getElementById('summary-subtotal').textContent = formatRupiah(subtotalProducts);
        document.getElementById('summary-shipping').textContent = formatRupiah(shippingCost);
        document.getElementById('summary-grand-total').textContent = formatRupiah(grandTotal);
    }

    document.getElementById('orderForm').addEventListener('submit', function (event) {
        let valid = true;
        let errorMessage = '';

        if (!userIdInput.value) {
            valid = false;
            errorMessage = 'Silakan pilih email pelanggan terlebih dahulu.';
            emailSearchInput.focus();
            openUserDropdown();
        }

        const rows = container.querySelectorAll('.product-row');
        if (rows.length === 0) {
            valid = false;
            errorMessage = 'Pesanan harus memiliki minimal satu produk.';
        }

        rows.forEach((row) => {
            const index = row.dataset.index;
            const chosen = document.getElementById(`prod-id-${index}`).value;
            const error = document.getElementById(`prod-error-${index}`);
            const qtyInput = document.getElementById(`prod-qty-${index}`);
            const product = selectedProducts[index];

            if (!chosen) {
                valid = false;
                error.classList.remove('hidden');
                if (!errorMessage) errorMessage = 'Pilih motif batik untuk semua baris produk.';
            } else {
                error.classList.add('hidden');
                if (product && Number(qtyInput.value) > product.stock) {
                    valid = false;
                    qtyInput.value = product.stock;
                    if (!errorMessage) errorMessage = `Jumlah produk '${product.name}' melebihi stok yang tersedia (${product.stock}).`;
                }
            }
        });

        if (!valid) {
            event.preventDefault();
            if (errorMessage) {
                showToast(errorMessage, true);
            }
        }
    });

    // Inisialisasi awal
    document.addEventListener('DOMContentLoaded', function() {
        addProductRow();
        onCourierChange();
    });
</script>
@endpush
