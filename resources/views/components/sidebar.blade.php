{{-- Sidebar Navigation Component --}}
<aside id="sidebar"
       class="fixed top-0 left-0 z-50 w-[260px] h-full bg-primary text-on-primary flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">

    {{-- Logo / Brand --}}
    <div class="px-6 py-5 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-secondary-light flex items-center justify-center">
                <svg class="w-6 h-6 text-secondary-hover" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div>
                <h2 class="font-display text-base font-bold tracking-tight">WBI Inventaris</h2>
                <p class="text-xs text-on-primary-container opacity-70">Asset Management</p>
            </div>
        </div>
    </div>

    {{-- Navigation Links --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
                  {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- Section: Aset --}}
        @if(auth()->user()->hasRole('finance', 'inventory', 'admin'))
        <div class="pt-4">
            <p class="px-3 text-[10px] font-mono font-semibold uppercase tracking-widest text-white/40 mb-2">Manajemen Aset</p>

            <a href="{{ route('assets.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('assets.*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Daftar Aset
            </a>
        </div>
        @endif

        {{-- Section: Admin --}}
        @if(auth()->user()->isAdmin())
        <div class="pt-4">
            <p class="px-3 text-[10px] font-mono font-semibold uppercase tracking-widest text-white/40 mb-2">Master Data</p>

            <a href="{{ route('departments.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('departments.*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Departemen
            </a>

            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('users.*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Pengguna
            </a>
        </div>
        @endif
    </nav>

    {{-- Sidebar footer --}}
    <div class="px-4 py-3 border-t border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-primary-hover flex items-center justify-center text-xs font-semibold text-white">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="overflow-hidden">
                <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                <p class="text-[11px] text-white/50 font-mono">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>
</aside>
