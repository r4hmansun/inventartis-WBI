@extends('layouts.app')

@section('title', 'Profil & Pengaturan Akun')
@section('page-title', 'Pengaturan Akun')

@section('content')
<div class="max-w-6xl mx-auto space-y-6 w-full min-w-0">

    {{-- 1. Identity Overview Banner --}}
    <div class="bg-surface-white rounded-xl border border-border-light p-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-primary text-white flex items-center justify-center font-display font-bold text-2xl shadow-sm shrink-0">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div class="space-y-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface leading-tight truncate">
                            {{ $user->name }}
                        </h2>
                        @php
                            $role = $user->role;
                            $roleBadge = match($role) {
                                'admin' => ['label' => 'Administrator', 'class' => 'bg-purple-50 text-purple-800 border-purple-200'],
                                'finance' => ['label' => 'Bagian Keuangan', 'class' => 'bg-blue-50 text-blue-800 border-blue-200'],
                                'inventory' => ['label' => 'Bagian Inventaris', 'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                                default => ['label' => $user->role_label, 'class' => 'bg-stone-50 text-stone-700 border-stone-200'],
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $roleBadge['class'] }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ $roleBadge['label'] }}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-on-surface-variant font-mono flex items-center gap-2">
                        <span>{{ $user->email }}</span>
                        <span>&bull;</span>
                        <span class="font-sans font-medium text-on-surface">
                            {{ $user->department ? $user->department->name . ' (' . $user->department->code . ')' : 'Politeknik Wilmar Bisnis Indonesia' }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2 self-start sm:self-auto border-t sm:border-t-0 pt-3 sm:pt-0 border-border-light">
                <span class="text-xs text-on-surface-variant font-mono">
                    Status Akun: <strong class="text-emerald-700 font-semibold">Aktif &bull; Terverifikasi</strong>
                </span>
            </div>
        </div>
    </div>

    {{-- 2. Settings Grid (2 Balanced Columns) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- Kolom 1: Informasi Profil & Kontak (6 Cols) --}}
        <div class="lg:col-span-6 bg-surface-white rounded-xl border border-border-light shadow-xs overflow-hidden flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-border-light bg-surface-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display text-sm sm:text-base font-bold text-on-surface">Informasi Profil</h3>
                            <p class="text-xs text-on-surface-variant">Perbarui nama lengkap dan kontak email akun.</p>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <form id="form-profile" method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Nama Lengkap --}}
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-on-surface mb-1.5">
                            Nama Lengkap <span class="text-rose-600">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               required 
                               value="{{ old('name', $user->name) }}"
                               placeholder="Nama lengkap Anda"
                               class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('name') ? 'border-rose-500 focus:ring-rose-500/20' : 'border-outline-variant focus:border-primary focus:ring-primary/20' }} bg-surface-white text-on-surface text-xs sm:text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 transition-colors">
                        @error('name')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Alamat Email --}}
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-on-surface mb-1.5">
                            Alamat Email <span class="text-rose-600">*</span>
                        </label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               required 
                               value="{{ old('email', $user->email) }}"
                               placeholder="nama@wbi.ac.id"
                               class="w-full px-3.5 py-2.5 rounded-lg border {{ $errors->has('email') ? 'border-rose-500 focus:ring-rose-500/20' : 'border-outline-variant focus:border-primary focus:ring-primary/20' }} bg-surface-white text-on-surface text-xs sm:text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 transition-colors">
                        @error('email')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Departemen / Unit Kerja (Read-Only) --}}
                    <div class="p-3.5 rounded-lg bg-surface-container/30 border border-border-light flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-mono font-semibold uppercase text-on-surface-variant block tracking-wider">
                                Unit Kerja / Departemen
                            </span>
                            <span class="text-xs sm:text-sm font-semibold text-on-surface mt-0.5 block">
                                @if($user->department)
                                    {{ $user->department->name }} 
                                    <span class="font-mono text-primary-light font-bold text-xs">({{ $user->department->code }})</span>
                                @else
                                    <span class="text-on-surface-variant italic">Seluruh Kampus (Global / Administrator)</span>
                                @endif
                            </span>
                        </div>
                        <div class="p-1.5 rounded bg-surface-container text-on-surface-variant" title="Departemen dikelola oleh Administrator">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-3 border-t border-border-light flex items-center justify-end">
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-lg bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all shadow-xs inline-flex items-center gap-2 cursor-pointer active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Kolom 2: Form Ganti Password (6 Cols) --}}
        <div class="lg:col-span-6 bg-surface-white rounded-xl border border-border-light shadow-xs overflow-hidden flex flex-col justify-between">
            <div>
                {{-- Card Header --}}
                <div class="px-6 py-4 border-b border-border-light bg-surface-white flex items-center justify-between">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-800 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-display text-sm sm:text-base font-bold text-on-surface">Keamanan &amp; Kata Sandi</h3>
                            <p class="text-xs text-on-surface-variant">Perbarui kata sandi untuk menjaga keamanan akun.</p>
                        </div>
                    </div>
                </div>

                {{-- Form Body --}}
                <form id="form-password" method="POST" action="{{ route('profile.password.update') }}" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Password Saat Ini --}}
                    <div>
                        <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-on-surface mb-1.5">
                            Password Saat Ini <span class="text-rose-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="current_password" 
                                   id="current_password" 
                                   required 
                                   autocomplete="current-password"
                                   placeholder="Ketik kata sandi saat ini"
                                   class="w-full px-3.5 py-2.5 pr-11 rounded-lg border {{ $errors->has('current_password') ? 'border-rose-500 focus:ring-rose-500/20' : 'border-outline-variant focus:border-primary focus:ring-primary/20' }} bg-surface-white text-on-surface text-xs sm:text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 transition-colors">
                            <button type="button" 
                                    onclick="togglePasswordField('current_password', this)" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer"
                                    tabindex="-1"
                                    title="Tampilkan / sembunyikan password">
                                <svg class="w-4 h-4 eye-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="w-4 h-4 eye-icon-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-on-surface mb-1.5">
                            Password Baru <span class="text-rose-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Minimal 6 karakter"
                                   class="w-full px-3.5 py-2.5 pr-11 rounded-lg border {{ $errors->has('password') ? 'border-rose-500 focus:ring-rose-500/20' : 'border-outline-variant focus:border-primary focus:ring-primary/20' }} bg-surface-white text-on-surface text-xs sm:text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 transition-colors">
                            <button type="button" 
                                    onclick="togglePasswordField('password', this)" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer"
                                    tabindex="-1"
                                    title="Tampilkan / sembunyikan password">
                                <svg class="w-4 h-4 eye-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="w-4 h-4 eye-icon-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-rose-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password Baru --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-on-surface mb-1.5">
                            Ulangi Password Baru <span class="text-rose-600">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   required 
                                   autocomplete="new-password"
                                   placeholder="Ketik ulang password baru"
                                   class="w-full px-3.5 py-2.5 pr-11 rounded-lg border border-outline-variant bg-surface-white text-on-surface text-xs sm:text-sm placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                            <button type="button" 
                                    onclick="togglePasswordField('password_confirmation', this)" 
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-on-surface-variant hover:text-on-surface transition-colors cursor-pointer"
                                    tabindex="-1"
                                    title="Tampilkan / sembunyikan password">
                                <svg class="w-4 h-4 eye-icon-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg class="w-4 h-4 eye-icon-closed hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-3 border-t border-border-light flex items-center justify-end">
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-lg bg-[#002a22] hover:bg-[#134137] text-white text-xs sm:text-sm font-semibold transition-all shadow-xs inline-flex items-center gap-2 cursor-pointer active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Perbarui Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function togglePasswordField(fieldId, btn) {
        const field = document.getElementById(fieldId);
        if (!field || !btn) return;

        const openIcon = btn.querySelector('.eye-icon-open');
        const closedIcon = btn.querySelector('.eye-icon-closed');

        if (field.type === 'password') {
            field.type = 'text';
            if (openIcon) openIcon.classList.add('hidden');
            if (closedIcon) closedIcon.classList.remove('hidden');
        } else {
            field.type = 'password';
            if (openIcon) openIcon.classList.remove('hidden');
            if (closedIcon) closedIcon.classList.add('hidden');
        }
    }
</script>
@endsection
