@extends('layouts.app')

@section('title', 'Edit Departemen')
@section('page-title', 'Edit Departemen')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Back link --}}
    <a href="{{ route('departments.index') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-on-surface transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Departemen
    </a>

    <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
        <div class="px-6 py-4 border-b border-border-light">
            <h2 class="font-display text-lg font-semibold text-on-surface">Edit Departemen</h2>
            <p class="text-sm text-on-surface-variant mt-0.5">Perbarui informasi departemen <span class="font-mono text-primary-light">{{ $department->code }}</span>.</p>
        </div>

        <form method="POST" action="{{ route('departments.update', $department) }}" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Kode --}}
            <div>
                <label for="code" class="block text-sm font-medium text-on-surface mb-1.5">Kode Departemen</label>
                <input type="text" id="code" name="code" value="{{ old('code', $department->code) }}" required
                       class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm font-mono uppercase
                              focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                              @error('code') border-error @enderror">
                @error('code')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-medium text-on-surface mb-1.5">Nama Departemen</label>
                <input type="text" id="name" name="name" value="{{ old('name', $department->name) }}" required
                       class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                              focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                              @error('name') border-error @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Status Aktif --}}
            <div class="flex items-center gap-3">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $department->is_active) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-outline-variant text-primary-light focus:ring-primary-light/30">
                <label for="is_active" class="text-sm text-on-surface">Departemen aktif</label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-border-light">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md bg-primary text-on-primary text-sm font-semibold
                               hover:bg-primary-light transition-all duration-200 active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Perbarui
                </button>
                <a href="{{ route('departments.index') }}"
                   class="px-5 py-2.5 rounded-md border border-outline-variant text-sm font-medium text-on-surface-variant hover:bg-surface-container transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
