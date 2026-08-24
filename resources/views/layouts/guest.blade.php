<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Login — Sistem Manajemen Inventaris & Mutasi Aset WBI">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>@yield('title', 'Login') — WBI Inventaris</title>

    {{-- Google Fonts sesuai DESIGN.md --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,400..800;1,400..800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-bg font-body text-on-surface min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md px-4">
        @yield('content')
    </div>
    @include('components.sweetalert')
    @stack('scripts')
</body>
</html>
