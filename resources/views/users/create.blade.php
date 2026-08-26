@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('page-title', 'Tambah Pengguna')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('users.index') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-on-surface transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Pengguna
    </a>

    <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
        <div class="px-6 py-4 border-b border-border-light">
            <h2 class="font-display text-lg font-semibold text-on-surface">Pengguna Baru</h2>
            <p class="text-sm text-on-surface-variant mt-0.5">Buat akun pengguna baru untuk sistem.</p>
        </div>

        <form method="POST" action="{{ route('users.store') }}" class="p-6 space-y-5">
            @csrf

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-medium text-on-surface mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       placeholder="Nama lengkap pengguna"
                       class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                              placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                              @error('name') border-error @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-on-surface mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                       placeholder="email@wbi.co.id"
                       class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                              placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                              @error('email') border-error @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-on-surface mb-1.5">Password</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Min. 8 karakter"
                           class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                                  placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                                  @error('password') border-error @enderror">
                    @error('password')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-on-surface mb-1.5">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           placeholder="Ulangi password"
                           class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                                  placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                </div>
            </div>

            {{-- Departemen & Role --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="department_id" class="block text-sm font-medium text-on-surface mb-1.5">Departemen</label>
                    <select id="department_id" name="department_id"
                            class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                                   focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                                   @error('department_id') border-error @enderror">
                        <option value="">— Pilih Departemen —</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-on-surface mb-1.5">Role</label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                                   focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                                   @error('role') border-error @enderror">
                        @php
                            $roleLabels = [
                                'super_admin' => 'Super Admin (Kelola Role & Semua Data Pengguna)',
                                'admin' => 'Admin (Admin Biasa - Kelola Master & Inventaris)',
                                'finance' => 'Bagian Keuangan',
                                'inventory' => 'Bagian Inventaris',
                                'user' => 'User / Staf Departemen',
                            ];
                        @endphp
                        @foreach($roles as $roleKey => $roleLabel)
                            @php
                                $value = is_string($roleKey) ? $roleKey : $roleLabel;
                                $label = is_string($roleKey) ? $roleLabel : ($roleLabels[$value] ?? $value);
                            @endphp
                            <option value="{{ $value }}" {{ old('role') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Info Perbedaan Role --}}
            <div class="p-3.5 rounded-md bg-surface-container/50 border border-border-light text-xs text-on-surface-variant space-y-1.5 font-mono">
                <div class="flex items-center gap-1.5 text-on-surface font-semibold">
                    <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Hak Akses Administrator:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 pl-1 text-[11px]">
                    <li><strong class="text-on-surface">Super Admin:</strong> Memiliki wewenang penuh mengubah informasi profil, ganti role pengguna lain, dan kelola master data.</li>
                    <li><strong class="text-on-surface">Admin Biasa:</strong> Memiliki akses master data & inventaris, namun <em>tidak dapat mengubah</em> data profil / role pengguna lain.</li>
                </ul>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-border-light">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md bg-primary text-on-primary text-sm font-semibold
                               hover:bg-primary-light transition-all duration-200 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
                <a href="{{ route('users.index') }}"
                   class="px-5 py-2.5 rounded-md border border-outline-variant text-sm font-medium text-on-surface-variant hover:bg-surface-container transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
