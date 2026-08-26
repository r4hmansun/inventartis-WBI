<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Manajemen Inventaris & Mutasi Aset Terpadu — WBI">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <title>@yield('title', 'Dashboard') — WBI Inventaris</title>

    {{-- Google Fonts sesuai DESIGN.md --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:ital,wght@0,400..800;1,400..800&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-bg font-body text-on-surface min-h-screen overflow-x-hidden">
    {{-- Preloader & Page Loading System --}}
    @include('components.preloader')

    <div class="flex min-h-screen w-full max-w-full overflow-x-hidden">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 min-w-0 w-full max-w-full flex flex-col min-h-screen lg:ml-[260px] overflow-x-hidden">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-surface-white/95 backdrop-blur-xs border-b border-border-light px-4 sm:px-6 py-3 flex items-center justify-between" style="box-shadow: var(--shadow-soft);">
                <div class="flex items-center gap-3">
                    {{-- Mobile menu toggle --}}
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-surface-container transition-colors text-on-surface focus:outline-none" aria-label="Toggle navigation">
                        <svg class="w-5 h-5 text-on-surface" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    {{-- Page title & Breadcrumb --}}
                    <div>
                        <h1 class="font-display text-lg sm:text-xl font-bold text-on-surface">
                            @yield('page-title', 'Dashboard')
                        </h1>
                    </div>
                </div>

                {{-- User Profile & Actions --}}
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 pl-1.5 pr-2 py-1 rounded-full bg-surface-container/60 border border-border-light hover:bg-surface-container/90 transition-colors {{ request()->routeIs('profile.*') ? 'ring-2 ring-primary/20 bg-surface-container' : '' }}">
                        <a href="{{ route('profile.index') }}" 
                           class="flex items-center gap-2.5 text-left focus:outline-none cursor-pointer group"
                           title="Lihat Profil &amp; Ganti Password">
                            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-semibold shrink-0 shadow-2xs group-hover:ring-2 group-hover:ring-primary/20 transition-all">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="text-left hidden sm:block pr-1">
                                <p class="text-xs font-semibold text-on-surface leading-tight group-hover:text-primary transition-colors flex items-center gap-1">
                                    <span>{{ auth()->user()->name }}</span>
                                    <svg class="w-3 h-3 text-on-surface-variant group-hover:text-primary opacity-60 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </p>
                                <p class="text-[10px] font-mono text-on-surface-variant leading-tight">{{ auth()->user()->role_label }}</p>
                            </div>
                        </a>

                        <div class="h-4 w-px bg-border-light"></div>

                        <form method="POST" action="{{ route('logout') }}" class="inline-flex">
                            @csrf
                            <button type="submit"
                                    class="p-1.5 rounded-full text-on-surface-variant hover:text-danger hover:bg-rose-50 transition-colors cursor-pointer"
                                    title="Keluar / Logout"
                                    aria-label="Logout">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 rounded-md bg-primary-surface text-primary-light text-sm font-medium flex items-center gap-2" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-4 sm:mx-6 mt-4 px-4 py-3 rounded-md bg-red-50 text-danger text-sm font-medium flex items-center gap-2" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 p-4 sm:p-6 min-w-0 w-full max-w-full">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="border-t border-border-light px-4 sm:px-6 py-4 text-center text-xs text-on-surface-variant">
                &copy; {{ date('Y') }} WBI — Sistem Manajemen Inventaris & Mutasi Aset
            </footer>
        </div>
    </div>

    {{-- Mobile sidebar overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @include('components.sweetalert')
    @stack('scripts')
</body>
</html>
