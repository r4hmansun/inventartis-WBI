<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Manajemen Inventaris & Mutasi Aset WBI">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>@hasSection('title')@yield('title') | @endif{{ config('app.name', 'Inventaris WBI') }}</title>

    {{-- Google Fonts sesuai DESIGN.md --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,400..800;1,400..800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-bg font-body text-on-surface min-h-screen overflow-x-hidden">
    {{-- Preloader & Page Loading System --}}
    @include('components.preloader')

    {{-- Full-Width Top Navigation Bar --}}
    <header class="fixed top-0 left-0 right-0 z-40 h-16 bg-surface-white/95 backdrop-blur-xs border-b border-border-light px-4 sm:px-6 flex items-center justify-between" style="box-shadow: var(--shadow-soft);">
        <div class="flex items-center gap-3 sm:gap-4">
            {{-- Mobile menu toggle --}}
            <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-1 rounded-lg hover:bg-surface-container transition-colors text-on-surface focus:outline-none cursor-pointer" aria-label="Toggle navigation">
                <svg class="w-5 h-5 text-on-surface" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Brand Logo in Topbar --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 focus:outline-none group" title="Politeknik Wilmar Bisnis Indonesia">
                <img src="{{ asset('images/logo.png') }}" alt="Wilmar Business Indonesia Polytechnic" class="h-8 sm:h-9 w-auto object-contain transition-transform group-hover:scale-[1.02]">
            </a>

            {{-- Divider between logo & page title on desktop --}}
            <div class="hidden lg:block h-6 w-px bg-border-light ml-2"></div>

            {{-- Page title & Breadcrumb --}}
            <div class="hidden sm:block">
                <h1 class="font-display text-base sm:text-lg font-bold text-on-surface">
                    @yield('page-title', 'Dashboard')
                </h1>
            </div>
        </div>

        {{-- Mobile Page Title (Center/Truncated) --}}
        <div class="sm:hidden text-center">
            <h1 class="font-display text-sm font-bold text-on-surface truncate max-w-[130px]">
                @yield('page-title', 'Dashboard')
            </h1>
        </div>

        {{-- User Profile & Actions --}}
        <div class="flex items-center gap-2.5">
            <a href="{{ route('profile.index') }}"
               class="flex items-center gap-2 pl-1.5 pr-3 py-1 rounded-full bg-surface-container/60 border border-border-light hover:bg-surface-container/90 transition-colors {{ request()->routeIs('profile.*') ? 'ring-2 ring-primary/20 bg-surface-container' : '' }}"
               title="Lihat Profil & Ganti Password">
                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-semibold shrink-0 shadow-2xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="text-left hidden md:block">
                    <p class="text-xs font-semibold text-on-surface leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] font-mono text-on-surface-variant leading-tight">{{ auth()->user()->role_label }}</p>
                </div>
            </a>

            <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                @csrf
                <button type="submit"
                        class="p-2 rounded-full text-on-surface-variant hover:text-rose-600 hover:bg-rose-50 transition-colors cursor-pointer"
                        title="Keluar"
                        aria-label="Logout">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </header>

    {{-- Main Shell with top padding for fixed topbar --}}
    <div class="pt-16 flex min-h-screen w-full max-w-full overflow-x-hidden">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 min-w-0 w-full max-w-full flex flex-col min-h-[calc(100vh-4rem)] lg:ml-[260px] overflow-x-hidden">
            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6 min-w-0 w-full max-w-full">
                @yield('content')
            </main>

            {{-- Lightweight Global Footer --}}
            <footer class="py-4 text-center text-xs text-on-surface-variant border-t border-border-light">
                &copy; {{ date('Y') }} Inventaris WBI
            </footer>
        </div>
    </div>

    {{-- Mobile sidebar overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @include('components.sweetalert')
    @include('components.system-guide-modal')
    @stack('scripts')
</body>
</html>
