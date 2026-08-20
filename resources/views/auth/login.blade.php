@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="bg-surface-white rounded-lg border border-border-light p-8" style="box-shadow: var(--shadow-soft);">
    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-primary flex items-center justify-center mb-4">
            <svg class="w-9 h-9 text-on-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
        </div>
        <h1 class="font-display text-2xl font-bold text-on-surface">WBI Inventaris</h1>
        <p class="text-sm text-on-surface-variant mt-1">Sistem Manajemen Inventaris & Mutasi Aset</p>
    </div>

    {{-- Error messages --}}
    @if ($errors->any())
    <div class="mb-6 px-4 py-3 rounded-md bg-red-50 border border-red-200">
        @foreach ($errors->all() as $error)
            <p class="text-sm text-danger">{{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Login Form --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-on-surface mb-1.5">Email</label>
            <input type="email"
                   id="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   placeholder="nama@wbi.co.id"
                   class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50
                          focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-on-surface mb-1.5">Password</label>
            <input type="password"
                   id="password"
                   name="password"
                   required
                   placeholder="••••••••"
                   class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50
                          focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2">
            <input type="checkbox"
                   id="remember"
                   name="remember"
                   class="w-4 h-4 rounded border-outline-variant text-primary-light focus:ring-primary-light/30">
            <label for="remember" class="text-sm text-on-surface-variant">Ingat saya</label>
        </div>

        {{-- Submit --}}
        <button type="submit"
                class="w-full px-4 py-2.5 rounded-md bg-primary text-on-primary text-sm font-semibold
                       hover:bg-primary-light focus:outline-none focus:ring-2 focus:ring-primary/30
                       transition-all duration-200 active:scale-[0.98]">
            Masuk
        </button>
    </form>
</div>

{{-- Demo credentials --}}
<div class="mt-6 p-4 rounded-md bg-surface-container border border-outline-variant/50">
    <p class="text-xs font-semibold text-on-surface-variant mb-2">Akun Demo:</p>
    <div class="space-y-1 font-mono text-[11px] text-on-surface-variant">
        <p><span class="text-primary-light font-medium">Admin:</span> admin@wbi.co.id</p>
        <p><span class="text-primary-light font-medium">Keuangan:</span> keuangan@wbi.co.id</p>
        <p><span class="text-primary-light font-medium">Inventaris:</span> inventaris@wbi.co.id</p>
        <p><span class="text-primary-light font-medium">Password:</span> password</p>
    </div>
</div>
@endsection
