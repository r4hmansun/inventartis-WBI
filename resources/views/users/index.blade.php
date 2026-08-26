@extends('layouts.app')

@section('title', 'Pengguna')
@section('page-title', 'Master Pengguna')

@section('content')
<div class="max-w-6xl mx-auto space-y-5 w-full min-w-0">
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface">Daftar Pengguna</h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Kelola akun pengguna, penugasan departemen, dan hak akses sistem.
            </p>
        </div>
        @if(auth()->user()->isSuperAdmin())
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all active:scale-[0.98] shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </a>
        @else
        <div class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-surface-container border border-border-light text-xs font-mono text-on-surface-variant self-start sm:self-auto">
            <svg class="w-4 h-4 text-on-surface-variant/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Mode Lihat (Admin) — Perubahan data dikelola Super Admin</span>
        </div>
        @endif
    </div>

    {{-- Table Card --}}
    <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[600px]">
                <thead>
                    <tr class="bg-surface-container/60 border-b border-border-light text-[11px] font-mono font-semibold text-on-surface-variant uppercase tracking-wider">
                        <th class="px-5 py-3.5">Nama Pengguna</th>
                        <th class="px-5 py-3.5">Email</th>
                        <th class="px-5 py-3.5">Departemen</th>
                        <th class="px-5 py-3.5">Role</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light text-xs">
                    @forelse($users as $u)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center text-xs font-semibold shrink-0 shadow-2xs">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-on-surface text-sm">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-on-surface-variant font-mono text-xs">{{ $u->email }}</td>
                        <td class="px-5 py-3.5 text-on-surface-variant">
                            @if($u->department)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md bg-surface-container text-on-surface text-xs font-medium">
                                    {{ $u->department->name }}
                                </span>
                            @else
                                <span class="text-on-surface-variant/50 font-mono italic">Global / Kampus</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php
                                $roleConfig = [
                                    'super_admin' => ['bg' => 'bg-emerald-50', 'text' => 'text-success', 'border' => 'border-emerald-200', 'dot' => 'bg-success'],
                                    'admin' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-800', 'border' => 'border-purple-200', 'dot' => 'bg-purple-600'],
                                    'finance' => ['bg' => 'bg-amber-50', 'text' => 'text-secondary', 'border' => 'border-amber-200', 'dot' => 'bg-secondary'],
                                    'inventory' => ['bg' => 'bg-teal-50', 'text' => 'text-primary-light', 'border' => 'border-teal-200', 'dot' => 'bg-primary-light'],
                                    'user' => ['bg' => 'bg-surface-container', 'text' => 'text-on-surface-variant', 'border' => 'border-outline-variant', 'dot' => 'bg-outline'],
                                ];
                                $c = $roleConfig[$u->role] ?? $roleConfig['user'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-mono font-medium {{ $c['bg'] }} {{ $c['text'] }} border {{ $c['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                                {{ $u->role_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('users.edit', $u) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-border-light bg-surface-white text-xs font-semibold text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded text-[11px] font-mono text-on-surface-variant/70 bg-surface-container/50 border border-border-light/60" title="Hanya Super Admin yang dapat mengedit data pengguna">
                                <svg class="w-3.5 h-3.5 text-on-surface-variant/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Terkunci</span>
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-on-surface-variant">
                            <div class="max-w-xs mx-auto text-center space-y-2">
                                <div class="w-10 h-10 mx-auto rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-semibold text-on-surface">Belum ada pengguna terdaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-5 py-3 border-t border-border-light bg-surface-container/30">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
