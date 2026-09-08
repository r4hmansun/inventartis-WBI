@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="bg-surface-white rounded-lg border border-border-light p-8" style="box-shadow: var(--shadow-soft);">
    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="Wilmar Business Indonesia Polytechnic" class="h-16 w-auto max-w-full object-contain">
        </div>
        <h1 class="font-display text-2xl font-bold text-on-surface">WBI Inventaris</h1>
        <p class="text-sm text-on-surface-variant mt-1">Sistem Manajemen Inventaris &amp; Mutasi Aset</p>
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
                   value="{{ old('email', Cookie::get('remember_email', '')) }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="nama@wbi.co.id"
                   class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50
                          focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-on-surface mb-1.5">Password</label>
            <div class="relative">
                <input type="password"
                       id="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full px-4 py-2.5 pr-11 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm placeholder-on-surface-variant/50
                              focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                <button type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-on-surface-variant/50 hover:text-on-surface-variant transition-colors cursor-pointer"
                        title="Tampilkan / sembunyikan password">
                    <svg id="eyeIcon" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg id="eyeSlashIcon" class="w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Remember me --}}
        <div class="flex items-center gap-2">
            <input type="checkbox"
                   id="remember"
                   name="remember"
                   value="1"
                   {{ old('remember', Cookie::has('remember_email')) ? 'checked' : '' }}
                   class="w-4 h-4 rounded border-outline-variant text-primary-light focus:ring-primary-light/30">
            <label for="remember" class="text-sm text-on-surface-variant cursor-pointer select-none">Ingat saya</label>
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

@push('scripts')
<script>
    // Toggle show/hide password
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    const eyeSlashIcon = document.getElementById('eyeSlashIcon');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            if (eyeIcon && eyeSlashIcon) {
                eyeIcon.classList.toggle('hidden', isPassword);
                eyeSlashIcon.classList.toggle('hidden', !isPassword);
            }
        });
    }
</script>
@endpush
@endsection
