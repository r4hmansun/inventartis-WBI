<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Login Sistem Manajemen Inventaris & Mutasi Aset WBI">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>@hasSection('title')@yield('title') | @endif{{ config('app.name', 'Inventaris WBI') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-bg font-body text-on-surface min-h-screen flex flex-col items-center justify-center p-4">
    {{-- Preloader & Page Loading System --}}
    @include('components.preloader')

    <div class="w-full max-w-md">
        @yield('content')
    </div>

    {{-- Lightweight Global Footer --}}
    <footer class="mt-6 text-center text-xs text-on-surface-variant">
        &copy; {{ date('Y') }} Inventaris WBI
    </footer>
    @include('components.sweetalert')
    @stack('scripts')
</body>
</html>
