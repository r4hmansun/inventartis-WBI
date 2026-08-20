@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('page-title', 'Edit Pengguna')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('users.index') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-on-surface transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Pengguna
    </a>

    <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
        <div class="px-6 py-4 border-b border-border-light">
            <h2 class="font-display text-lg font-semibold text-on-surface">Edit Pengguna</h2>
            <p class="text-sm text-on-surface-variant mt-0.5">Perbarui data <span class="font-medium">{{ $user->name }}</span>.</p>
        </div>

        <form method="POST" action="{{ route('users.update', $user) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-medium text-on-surface mb-1.5">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                              focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                              @error('name') border-error @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-on-surface mb-1.5">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                              focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                              @error('email') border-error @enderror">
                @error('email')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password (optional on edit) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-on-surface mb-1.5">
                        Password <span class="text-on-surface-variant font-normal">(kosongkan jika tidak diubah)</span>
                    </label>
                    <input type="password" id="password" name="password"
                           placeholder="Password baru"
                           class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                                  placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                                  @error('password') border-error @enderror">
                    @error('password')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-on-surface mb-1.5">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           placeholder="Ulangi password baru"
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
                                   focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                        <option value="">— Pilih Departemen —</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id', $user->department_id) == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }} ({{ $dept->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-on-surface mb-1.5">Role</label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                                   focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                        @php
                            $roleLabels = ['user' => 'User / Departemen', 'finance' => 'Bagian Keuangan', 'inventory' => 'Bagian Inventaris', 'admin' => 'Super Admin'];
                        @endphp
                        @foreach($roles as $role)
                            <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>
                                {{ $roleLabels[$role] ?? $role }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-border-light">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md bg-primary text-on-primary text-sm font-semibold
                               hover:bg-primary-light transition-all duration-200 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Perbarui
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
