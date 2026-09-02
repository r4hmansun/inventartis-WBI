{{-- Sidebar Navigation Component --}}
<aside id="sidebar"
       class="fixed top-16 left-0 z-35 w-[260px] h-[calc(100vh-4rem)] bg-primary text-on-primary flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0 border-r border-primary-light/30 shadow-xs">

    {{-- Navigation Links --}}
    <nav class="flex-1 overflow-y-auto px-3 pt-6 pb-4 space-y-1">
        {{-- Section: Dashboard / Menu Utama --}}
        <div>
            <p class="px-3 text-[10px] font-mono font-semibold uppercase tracking-widest text-white/40 mb-2">
                Dashboard
            </p>
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>
        </div>

        {{-- Section: Aset (Accessible to all authenticated users) --}}
        <div class="pt-5">
            <p class="px-3 text-[10px] font-mono font-semibold uppercase tracking-widest text-white/40 mb-2">
                {{ auth()->user()->hasRole('user') ? 'Inventaris & Barang' : 'Manajemen Aset' }}
            </p>

            @if(auth()->user()->hasRole('user') && auth()->user()->department_id)
            {{-- Shortcut khusus staf unit --}}
            <a href="{{ route('assets.index', ['scope' => 'my_dept']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors mb-1
                      {{ request()->routeIs('assets.*') && request('scope') === 'my_dept' ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0 text-secondary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Aset Unit Saya</span>
            </a>
            @endif

            <a href="{{ route('assets.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('assets.index') && request('scope') !== 'my_dept' ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>{{ auth()->user()->hasRole('user') ? 'Semua Aset Kampus' : 'Daftar Aset' }}</span>
            </a>

            <a href="{{ route('mutations.index') }}"
               class="flex items-center justify-between px-3 py-2.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('mutations.*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0 text-secondary-container" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span>Mutasi Aset</span>
                </div>

                @if(isset($sidebarPendingCount) && $sidebarPendingCount > 0 && auth()->user()->hasRole('user'))
                    <span class="px-2 py-0.5 text-[10px] font-mono font-bold rounded-full bg-amber-400 text-amber-950 animate-pulse" title="{{ $sidebarPendingCount }} mutasi menunggu approval Anda">
                        {{ $sidebarPendingCount }}
                    </span>
                @elseif(isset($sidebarReadyCount) && $sidebarReadyCount > 0 && auth()->user()->hasRole('inventory', 'admin', 'super_admin'))
                    <span class="px-2 py-0.5 text-[10px] font-mono font-bold rounded-full bg-secondary-light text-primary font-bold" title="{{ $sidebarReadyCount }} mutasi siap dieksekusi &amp; arsip">
                        {{ $sidebarReadyCount }}
                    </span>
                @endif
            </a>

            @if(auth()->user()->hasRole('finance', 'admin'))
            <a href="{{ route('assets.create') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-md text-xs font-medium transition-colors mt-1
                      {{ request()->routeIs('assets.create') ? 'bg-white/15 text-white' : 'text-white/60 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-4 h-4 shrink-0 text-primary-surface" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Registrasi Aset Baru</span>
            </a>
            @endif
        </div>

        {{-- Section: Admin --}}
        @if(auth()->user()->isAdmin())
        <div class="pt-5">
            <p class="px-3 text-[10px] font-mono font-semibold uppercase tracking-widest text-white/40 mb-2">Master Data</p>

            <a href="{{ route('departments.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('departments.*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Departemen</span>
            </a>

            @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium transition-colors
                      {{ request()->routeIs('users.*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Pengguna</span>
            </a>
            @endif
        </div>
        @endif

        {{-- Section: Bantuan / SOP --}}
        <div class="pt-5">
            <p class="px-3 text-[10px] font-mono font-semibold uppercase tracking-widest text-white/40 mb-2">Bantuan &amp; SOP</p>

            <button type="button" onclick="openSystemGuideModal()"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-sm font-medium text-white/70 hover:bg-white/10 hover:text-white transition-colors text-left cursor-pointer">
                <svg class="w-5 h-5 shrink-0 text-secondary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Panduan Alur Sistem</span>
            </button>
        </div>
    </nav>

    {{-- Sidebar Footer: User Info --}}
    <div class="p-3 border-t border-white/10 mt-auto">
        <a href="{{ route('profile.index') }}"
           class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-white/10 transition-colors group">
            <div class="w-7 h-7 rounded-full bg-white/20 text-white flex items-center justify-center text-xs font-bold shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-white truncate leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-[10px] font-mono text-white/50 leading-tight">{{ auth()->user()->role_label }}</p>
            </div>
        </a>
    </div>
</aside>
