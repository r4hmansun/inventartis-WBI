<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistem Manajemen Inventaris & Mutasi Aset Terpadu — WBI">
    <title>@yield('title', 'Dashboard') — WBI Inventaris</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-bg font-body text-on-surface min-h-screen">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col min-h-screen lg:ml-[260px]">
            {{-- Top Bar --}}
            <header class="sticky top-0 z-30 bg-surface-white border-b border-border-light px-6 py-3 flex items-center justify-between" style="box-shadow: var(--shadow-soft);">
                {{-- Mobile menu toggle --}}
                <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-md hover:bg-surface-container transition-colors" aria-label="Toggle navigation">
                    <svg class="w-6 h-6 text-on-surface" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Page title --}}
                <h1 class="font-display text-xl font-semibold text-on-surface hidden sm:block">
                    @yield('page-title', 'Dashboard')
                </h1>

                {{-- User dropdown --}}
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium text-on-surface">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-on-surface-variant">{{ auth()->user()->role_label }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center text-on-primary text-sm font-semibold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-md hover:bg-surface-container transition-colors text-on-surface-variant" title="Logout">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </header>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mx-6 mt-4 px-4 py-3 rounded-md bg-primary-surface text-primary-light text-sm font-medium flex items-center gap-2" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4 px-4 py-3 rounded-md bg-red-50 text-danger text-sm font-medium flex items-center gap-2" role="alert">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- Page Content --}}
            <main class="flex-1 p-6">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="border-t border-border-light px-6 py-4 text-center text-xs text-on-surface-variant">
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
