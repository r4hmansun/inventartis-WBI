@extends('layouts.app')

@section('title', 'Departemen')
@section('page-title', 'Departemen')

@section('content')
<div class="max-w-6xl mx-auto space-y-5 w-full min-w-0">
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface">Departemen</h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Kelola unit kerja, fakultas, program studi, dan divisi operasional di WBI.
            </p>
        </div>
        <a href="{{ route('departments.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all active:scale-[0.98] shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Departemen
        </a>
    </div>

    {{-- Table Card --}}
    <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[550px]">
                <thead>
                    <tr class="bg-surface-container/60 border-b border-border-light text-[11px] font-mono font-semibold text-on-surface-variant uppercase tracking-wider">
                        <th class="px-5 py-3.5">Kode</th>
                        <th class="px-5 py-3.5">Nama Departemen</th>
                        <th class="px-5 py-3.5 text-center">Pengguna</th>
                        <th class="px-5 py-3.5 text-center">Total Aset</th>
                        <th class="px-5 py-3.5 text-center">Status</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light text-xs">
                    @forelse($departments as $dept)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-5 py-3.5 font-mono text-xs font-semibold text-primary-light whitespace-nowrap">
                            {{ $dept->code }}
                        </td>
                        <td class="px-5 py-3.5 font-medium text-on-surface text-sm">
                            {{ $dept->name }}
                        </td>
                        <td class="px-5 py-3.5 text-center font-mono text-xs text-on-surface-variant">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-surface-container text-on-surface font-semibold">
                                {{ $dept->users_count }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center font-mono text-xs text-on-surface-variant">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-primary/5 text-primary-light font-semibold border border-primary/10">
                                {{ $dept->assets_count }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center whitespace-nowrap">
                            @if($dept->is_active)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-mono font-medium bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-mono font-medium bg-rose-50 text-rose-800 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('departments.edit', $dept) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-border-light bg-surface-white text-xs font-semibold text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
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
                            <div class="max-w-xs mx-auto text-center space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-on-surface">Belum ada departemen terdaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($departments->hasPages())
        <div class="px-5 py-3.5 border-t border-border-light bg-surface-white">
            {{ $departments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
