@extends('layouts.app')

@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

@section('title', 'Profil Pengguna')
@section('page-title', 'Profil Pengguna')

@section('content')
<div class="max-w-5xl mx-auto space-y-6 w-full min-w-0">

    {{-- Breadcrumb & Top Navigation Action --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div class="flex items-center gap-2 text-xs font-mono text-on-surface-variant">
            <a href="{{ route('dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
            <span class="text-outline-variant">/</span>
            <span class="text-on-surface font-semibold">Profil Saya</span>
        </div>

        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-md bg-surface-white border border-outline-variant text-xs font-semibold text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors self-start sm:self-auto"
           style="box-shadow: var(--shadow-soft);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    {{-- 2-Column Grid matching 12-column System (DESIGN.md) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- Kolom 1: Edit Informasi Profil (Nama, Email, Role, Departemen) (6 / 12 col) --}}
        <div class="lg:col-span-6 bg-surface-white rounded-lg border border-border-light overflow-hidden status-bar-active"
             style="box-shadow: var(--shadow-soft);">
            
            {{-- Header Profil Card --}}
            <div class="p-6 border-b border-border-light">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-primary text-on-primary flex items-center justify-center font-display font-bold text-xl shadow-xs shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="font-display text-lg font-bold text-on-surface leading-tight truncate">
                            {{ $user->name }}
                        </h2>
                        <p class="font-mono text-xs text-on-surface-variant truncate mt-0.5">
                            {{ $user->email }}
                        </p>
                        
                        {{-- Role Badge / Chip (DESIGN.md rounded-xl label-sm) --}}
                        <div class="mt-2">
                            @php
                                $role = $user->role;
                                $badgeClasses = match($role) {
                                    'admin' => 'bg-emerald-50 text-success border-emerald-200',
                                    'finance' => 'bg-amber-50 text-secondary border-amber-200',
                                    'inventory' => 'bg-teal-50 text-primary-light border-teal-200',
                                    default => 'bg-surface-container text-on-surface-variant border-outline-variant',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-xl text-[11px] font-mono font-semibold border {{ $badgeClasses }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $user->role_label }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Edit Profil (Nama & Email) --}}
            <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                {{-- Input Nama Lengkap --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-on-surface mb-1.5">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           required 
                           value="{{ old('name', $user->name) }}"
                           placeholder="Nama lengkap Anda"
                           class="w-full px-4 py-2.5 rounded-md border {{ $errors->has('name') ? 'border-danger focus:ring-danger/20' : 'border-outline-variant focus:border-primary-light focus:ring-primary-light/20' }} bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 transition-colors">
                    @error('name')
                        <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Alamat Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-on-surface mb-1.5">
                        Alamat Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email" 
                           required 
                           value="{{ old('email', $user->email) }}"
                           placeholder="nama@wbi.ac.id"
                           class="w-full px-4 py-2.5 rounded-md border {{ $errors->has('email') ? 'border-danger focus:ring-danger/20' : 'border-outline-variant focus:border-primary-light focus:ring-primary-light/20' }} bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 transition-colors">
                    @error('email')
                        <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Info Departemen (Read-Only) --}}
                <div class="p-3.5 rounded-md bg-surface-container/50 border border-border-light flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-mono font-semibold uppercase text-on-surface-variant block tracking-wider">
                            Departemen / Unit Kerja
                        </span>
                        <span class="text-xs font-semibold text-on-surface mt-0.5 block">
                            @if($user->department)
                                {{ $user->department->name }} 
                                <span class="font-mono text-primary-light font-bold">({{ $user->department->code }})</span>
                            @else
                                <span class="text-on-surface-variant italic">Seluruh Kampus (Global / Administrator)</span>
                            @endif
                        </span>
                    </div>
                    <div class="p-1.5 rounded bg-surface-container text-on-surface-variant" title="Departemen diatur oleh Super Admin">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>

                {{-- Tombol Simpan Perubahan Profil --}}
                <div class="pt-3 flex items-center justify-end border-t border-border-light">
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-md bg-primary text-on-primary text-sm font-semibold hover:bg-primary-light focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all duration-200 active:scale-[0.98] cursor-pointer inline-flex items-center gap-2 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Profil</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Kolom 2: Form Ganti Password (6 / 12 col) --}}
        <div class="lg:col-span-6 bg-surface-white rounded-lg border border-border-light overflow-hidden status-bar-warning"
             style="box-shadow: var(--shadow-soft);">
            
            {{-- Header Form Password --}}
            <div class="px-6 py-4 border-b border-border-light">
                <h3 class="font-display text-base font-bold text-on-surface">
                    Ganti Password
                </h3>
                <p class="text-xs text-on-surface-variant mt-0.5">
                    Perbarui kata sandi untuk menjaga keamanan akun inventaris Anda.
                </p>
            </div>

            {{-- Body Form Password --}}
            <form method="POST" action="{{ route('profile.password.update') }}" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                {{-- Password Saat Ini --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-on-surface mb-1.5">
                        Password Saat Ini <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="current_password" 
                               id="current_password" 
                               required 
                               autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 pr-11 rounded-md border {{ $errors->has('current_password') ? 'border-danger focus:ring-danger/20' : 'border-outline-variant focus:border-primary-light focus:ring-primary-light/20' }} bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 transition-colors">
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
                        <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-on-surface mb-1.5">
                        Password Baru <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               required 
                               autocomplete="new-password"
                               placeholder="Min. 6 karakter"
                               class="w-full px-4 py-2.5 pr-11 rounded-md border {{ $errors->has('password') ? 'border-danger focus:ring-danger/20' : 'border-outline-variant focus:border-primary-light focus:ring-primary-light/20' }} bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50 focus:outline-none focus:ring-2 transition-colors">
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
                        <p class="mt-1 text-xs text-danger font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password Baru --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-on-surface mb-1.5">
                        Ulangi Password Baru <span class="text-danger">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="password_confirmation" 
                               id="password_confirmation" 
                               required 
                               autocomplete="new-password"
                               placeholder="Ketik ulang password baru"
                               class="w-full px-4 py-2.5 pr-11 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
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

                {{-- Action Submit Password --}}
                <div class="pt-3 flex items-center justify-end border-t border-border-light">
                    <button type="submit" 
                            class="px-5 py-2.5 rounded-md bg-primary text-on-primary text-sm font-semibold hover:bg-primary-light focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all duration-200 active:scale-[0.98] cursor-pointer inline-flex items-center gap-2 shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Password Baru</span>
                    </button>
                </div>
            </form>
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
