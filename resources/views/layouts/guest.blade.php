<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Login — Sistem Manajemen Inventaris & Mutasi Aset WBI">
    <title>@yield('title', 'Login') — WBI Inventaris</title>
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
