@extends('layouts.app')

@section('title', 'Daftar Aset')
@section('page-title', 'Daftar Aset')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="font-display text-xl font-bold text-on-surface">Daftar Aset Inventaris</h2>
            <p class="text-sm text-on-surface-variant mt-0.5">Seluruh aset yang terdaftar dalam sistem.</p>
        </div>
        @if(auth()->user()->hasRole('finance', 'admin'))
        <a href="{{ route('assets.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-primary text-on-primary text-sm font-semibold
                  hover:bg-primary-light transition-all duration-200 active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Registrasi Aset Baru
        </a>
        @endif
    </div>

    {{-- Filters --}}
    <div class="bg-surface-white rounded-lg border border-border-light p-4 mb-5">
        <form method="GET" action="{{ route('assets.index') }}" class="flex flex-col sm:flex-row gap-3">
            {{-- Search --}}
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau kode aset..."
                       class="w-full px-4 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                              placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
            </div>

            {{-- Status Filter --}}
            <select name="status"
                    class="px-4 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                           focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                <option value="">Semua Status</option>
                <option value="in_storage" {{ request('status') === 'in_storage' ? 'selected' : '' }}>In Storage</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="under_repair" {{ request('status') === 'under_repair' ? 'selected' : '' }}>Under Repair</option>
                <option value="disposed" {{ request('status') === 'disposed' ? 'selected' : '' }}>Disposed</option>
            </select>

            {{-- Department Filter --}}
            <select name="department_id"
                    class="px-4 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                           focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors">
                <option value="">Semua Departemen</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>

            <button type="submit"
                    class="px-4 py-2 rounded-md bg-primary text-on-primary text-sm font-semibold hover:bg-primary-light transition-all">
                Filter
            </button>

            @if(request()->hasAny(['search', 'status', 'department_id']))
            <a href="{{ route('assets.index') }}"
               class="px-4 py-2 rounded-md border border-outline-variant text-sm font-medium text-on-surface-variant hover:bg-surface-container transition-colors text-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Assets Table --}}
    <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="sticky top-0 z-10">
                    <tr class="bg-surface-container text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kode Aset</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Harga Perolehan</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Departemen</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light">
                    @forelse($assets as $asset)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-5 py-3 font-mono text-xs font-medium text-primary-light whitespace-nowrap">{{ $asset->asset_code }}</td>
                        <td class="px-5 py-3 font-medium text-on-surface">{{ $asset->name }}</td>
                        <td class="px-5 py-3 text-on-surface-variant font-mono text-xs whitespace-nowrap">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
                        <td class="px-5 py-3 text-on-surface-variant text-xs whitespace-nowrap">{{ $asset->purchase_date->format('d/m/Y') }}</td>
                        <td class="px-5 py-3 text-on-surface-variant text-xs">{{ $asset->currentDepartment->name }}</td>
                        <td class="px-5 py-3">@include('components.status-badge', ['status' => $asset->status])</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('assets.show', $asset) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md border border-outline-variant text-xs font-medium text-on-surface-variant
                                      hover:bg-surface-container hover:text-on-surface transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            <svg class="w-12 h-12 mx-auto mb-3 text-outline-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p class="text-sm">Belum ada aset terdaftar.</p>
                            @if(auth()->user()->hasRole('finance', 'admin'))
                            <a href="{{ route('assets.create') }}" class="text-sm text-primary-light hover:underline mt-1 inline-block">Registrasi aset pertama →</a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($assets->hasPages())
        <div class="px-5 py-3 border-t border-border-light">
            {{ $assets->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
