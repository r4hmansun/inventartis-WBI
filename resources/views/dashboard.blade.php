@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    {{-- Greeting --}}
    <div>
        <h2 class="font-display text-2xl font-bold text-on-surface">Selamat datang, {{ $user->name }}</h2>
        <p class="text-sm text-on-surface-variant mt-1">Ringkasan sistem manajemen inventaris & mutasi aset.</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @include('components.stat-card', [
            'title' => 'Total Aset',
            'value' => number_format($stats['total_assets']),
            'color' => 'primary',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>'
        ])

        @include('components.stat-card', [
            'title' => 'Aset Aktif',
            'value' => number_format($stats['active_assets']),
            'color' => 'success',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
        ])

        @include('components.stat-card', [
            'title' => 'Di Gudang',
            'value' => number_format($stats['in_storage']),
            'color' => 'slate',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>'
        ])

        @include('components.stat-card', [
            'title' => 'Mutasi Pending',
            'value' => number_format($stats['pending_mutations']),
            'color' => 'warning',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>'
        ])
    </div>

    {{-- Recent Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Assets --}}
        <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
            <div class="px-5 py-4 border-b border-border-light flex items-center justify-between">
                <h3 class="font-display text-base font-semibold text-on-surface">Aset Terbaru</h3>
                @if(auth()->user()->hasRole('finance', 'inventory', 'admin'))
                <a href="{{ route('assets.index') }}" class="text-xs font-medium text-primary-light hover:text-primary transition-colors">Lihat Semua →</a>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-surface-container text-left">
                            <th class="px-5 py-2.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kode</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nama</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light">
                        @forelse($recentAssets as $asset)
                        <tr class="table-row-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-primary-light font-medium">{{ $asset->asset_code }}</td>
                            <td class="px-5 py-3 text-on-surface">{{ $asset->name }}</td>
                            <td class="px-5 py-3">@include('components.status-badge', ['status' => $asset->status])</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-on-surface-variant text-sm">Belum ada aset terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Mutations --}}
        <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
            <div class="px-5 py-4 border-b border-border-light flex items-center justify-between">
                <h3 class="font-display text-base font-semibold text-on-surface">Mutasi Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-surface-container text-left">
                            <th class="px-5 py-2.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">No. Form</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Dari → Ke</th>
                            <th class="px-5 py-2.5 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light">
                        @forelse($recentMutations as $mutation)
                        <tr class="table-row-hover transition-colors">
                            <td class="px-5 py-3 font-mono text-xs text-secondary-hover font-medium">{{ $mutation->form_number }}</td>
                            <td class="px-5 py-3 text-on-surface text-xs">
                                {{ $mutation->fromDepartment->name }} → {{ $mutation->toDepartment->name }}
                            </td>
                            <td class="px-5 py-3">@include('components.status-badge', ['status' => $mutation->status])</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-5 py-8 text-center text-on-surface-variant text-sm">Belum ada mutasi tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
