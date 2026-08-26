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
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all active:scale-[0.98] shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </a>
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
                                <span class="text-on-surface-variant/50 font-mono">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php
                                $roleConfig = [
                                    'admin' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-800', 'border' => 'border-purple-200', 'dot' => 'bg-purple-500'],
                                    'finance' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-800', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500'],
                                    'inventory' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
                                    'user' => ['bg' => 'bg-stone-100', 'text' => 'text-stone-700', 'border' => 'border-stone-300', 'dot' => 'bg-stone-400'],
                                ];
                                $c = $roleConfig[$u->role] ?? $roleConfig['user'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-mono font-medium {{ $c['bg'] }} {{ $c['text'] }} border {{ $c['border'] }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $c['dot'] }}"></span>
                                {{ $u->role_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('users.edit', $u) }}"
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
        <div class="px-5 py-3.5 border-t border-border-light bg-surface-white">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
