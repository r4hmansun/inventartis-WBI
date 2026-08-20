@extends('layouts.app')

@section('title', 'Departemen')
@section('page-title', 'Master Departemen')

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-display text-xl font-bold text-on-surface">Daftar Departemen</h2>
            <p class="text-sm text-on-surface-variant mt-0.5">Kelola departemen yang terdaftar di sistem.</p>
        </div>
        <a href="{{ route('departments.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-primary text-on-primary text-sm font-semibold
                  hover:bg-primary-light transition-all duration-200 active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Departemen
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Kode</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-center">Users</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-center">Aset</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-center">Status</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light">
                    @forelse($departments as $dept)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-5 py-3 font-mono text-xs font-medium text-primary-light">{{ $dept->code }}</td>
                        <td class="px-5 py-3 font-medium text-on-surface">{{ $dept->name }}</td>
                        <td class="px-5 py-3 text-center text-on-surface-variant">{{ $dept->users_count }}</td>
                        <td class="px-5 py-3 text-center text-on-surface-variant">{{ $dept->assets_count }}</td>
                        <td class="px-5 py-3 text-center">
                            @if($dept->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-xl text-[11px] font-mono font-medium bg-emerald-50 text-success">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-xl text-[11px] font-mono font-medium bg-red-50 text-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('departments.edit', $dept) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-md border border-outline-variant text-xs font-medium text-on-surface-variant
                                      hover:bg-surface-container hover:text-on-surface transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-on-surface-variant">
                            <svg class="w-12 h-12 mx-auto mb-3 text-outline-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-sm">Belum ada departemen terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($departments->hasPages())
        <div class="px-5 py-3 border-t border-border-light">
            {{ $departments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
