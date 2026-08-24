@extends('layouts.app')

@section('title', 'Daftar Aset & Inventaris')
@section('page-title', 'Daftar Aset & Inventaris')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface">Daftar Aset &amp; Inventaris</h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Pencatatan resmi barang inventaris terkapitalisasi (&ge; Rp 500.000) di seluruh lingkungan WBI.
            </p>
        </div>
        @if(auth()->user()->hasRole('finance', 'admin'))
        <a href="{{ route('assets.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-on-primary text-xs sm:text-sm font-semibold
                  hover:bg-primary-light transition-all duration-200 active:scale-[0.98] shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Registrasi Aset Baru
        </a>
        @endif
    </div>

    {{-- Tabs Lingkup (Khusus Pengguna dengan Departemen) --}}
    @if($userDepartment)
    <div class="flex items-center gap-2 border-b border-border-light pb-2">
        <a href="{{ route('assets.index', ['scope' => 'my_dept']) }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all
                  {{ request('scope') === 'my_dept' ? 'bg-primary text-white shadow-xs' : 'bg-surface-white text-on-surface-variant hover:bg-surface-container border border-border-light' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span>Aset Unit Saya ({{ $userDepartment->code }})</span>
            <span class="px-1.5 py-0.2 text-[11px] font-mono rounded {{ request('scope') === 'my_dept' ? 'bg-white/20 text-white' : 'bg-surface-container text-on-surface' }}">
                {{ $myDeptAssetCount }}
            </span>
        </a>

        <a href="{{ route('assets.index') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all
                  {{ request('scope') !== 'my_dept' && !request('department_id') ? 'bg-primary text-white shadow-xs' : 'bg-surface-white text-on-surface-variant hover:bg-surface-container border border-border-light' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
            </svg>
            <span>Semua Aset Kampus</span>
        </a>
    </div>
    @endif

    {{-- Filters Box --}}
    <div class="bg-surface-white rounded-xl border border-border-light p-4 shadow-xs">
        <form method="GET" action="{{ route('assets.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            @if(request('scope'))
            <input type="hidden" name="scope" value="{{ request('scope') }}">
            @endif

            {{-- Search --}}
            <div class="sm:col-span-5">
                <label for="search" class="block text-xs font-semibold text-on-surface-variant mb-1">Cari Barang / Kode</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Ketik nama alat atau kode AST/..."
                           class="w-full pl-9 pr-4 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-xs sm:text-sm
                                  placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                    <svg class="w-4 h-4 text-on-surface-variant/60 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="sm:col-span-3">
                <label for="status" class="block text-xs font-semibold text-on-surface-variant mb-1">Status Barang</label>
                <select id="status" name="status"
                        class="w-full px-3 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-xs sm:text-sm
                               focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif (Sedang Dipakai)</option>
                    <option value="in_storage" {{ request('status') === 'in_storage' ? 'selected' : '' }}>Di Gudang Inventaris</option>
                    <option value="under_repair" {{ request('status') === 'under_repair' ? 'selected' : '' }}>Dalam Perbaikan</option>
                    <option value="disposed" {{ request('status') === 'disposed' ? 'selected' : '' }}>Tidak Digunakan / Dihapus</option>
                </select>
            </div>

            {{-- Department Filter --}}
            <div class="sm:col-span-3">
                <label for="department_id" class="block text-xs font-semibold text-on-surface-variant mb-1">Lokasi Unit / Bagian</label>
                <select id="department_id" name="department_id"
                        class="w-full px-3 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-xs sm:text-sm
                               focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                    <option value="">Semua Unit</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }} ({{ $dept->code }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol Aksi Filter --}}
            <div class="sm:col-span-1 flex items-end gap-1.5">
                <button type="submit"
                        class="w-full py-2 rounded-md bg-primary text-on-primary text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all text-center">
                    Cari
                </button>
            </div>
        </form>

        @if(request()->hasAny(['search', 'status', 'department_id', 'scope']))
        <div class="mt-3 pt-2.5 border-t border-border-light flex items-center justify-between text-xs text-on-surface-variant">
            <span>Filter aktif diterapkan</span>
            <a href="{{ route('assets.index') }}"
               class="text-primary-light font-semibold hover:underline inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset Semua Filter
            </a>
        </div>
        @endif
    </div>

    {{-- Assets Table --}}
    <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-surface-container/80 border-b border-border-light text-[11px] font-mono font-semibold text-on-surface-variant uppercase tracking-wider">
                        <th class="px-5 py-3">Kode Aset</th>
                        <th class="px-5 py-3">Nama Barang</th>
                        <th class="px-5 py-3">Harga Perolehan</th>
                        <th class="px-5 py-3">Tanggal Beli</th>
                        <th class="px-5 py-3">Unit Bertanggung Jawab</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light text-xs sm:text-sm">
                    @forelse($assets as $asset)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-semibold text-primary-light whitespace-nowrap">
                            {{ $asset->asset_code }}
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-on-surface text-sm">{{ $asset->name }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Didaftarkan: {{ $asset->creator ? $asset->creator->name : 'Sistem' }}</p>
                        </td>
                        <td class="px-5 py-3.5 text-on-surface font-mono text-xs font-medium whitespace-nowrap">
                            Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-on-surface-variant text-xs whitespace-nowrap">
                            {{ $asset->purchase_date->format('d/m/Y') }}
                        </td>
                        <td class="px-5 py-3.5 text-xs">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-surface-container text-on-surface font-medium">
                                <strong class="font-mono text-[11px] text-primary-light">{{ $asset->currentDepartment->code }}</strong>
                                <span class="text-on-surface-variant">&bull;</span>
                                <span>{{ $asset->currentDepartment->name }}</span>
                            </span>
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @include('components.status-badge', ['status' => $asset->status])
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('assets.show', $asset) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md border border-outline-variant bg-surface-white text-xs font-semibold text-on-surface hover:bg-surface-container hover:border-primary-light transition-colors shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-on-surface-variant">
                            <div class="max-w-md mx-auto space-y-2 text-center">
                                <svg class="w-12 h-12 mx-auto text-outline-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                <p class="text-sm font-semibold text-on-surface">Tidak ada data aset yang sesuai kriteria.</p>
                                <p class="text-xs text-on-surface-variant">
                                    Coba ubah kata kunci pencarian atau reset filter untuk melihat seluruh inventaris.
                                </p>
                                @if(auth()->user()->hasRole('finance', 'admin'))
                                <div class="pt-2">
                                    <a href="{{ route('assets.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-light transition-colors shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        Registrasi Aset Baru
                                    </a>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assets->hasPages())
        <div class="px-5 py-3 border-t border-border-light bg-surface-white">
            {{ $assets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

