@extends('layouts.app')

@section('title', 'Pengguna')
@section('page-title', 'Master Pengguna')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="font-display text-xl font-bold text-on-surface">Daftar Pengguna</h2>
            <p class="text-sm text-on-surface-variant mt-0.5">Kelola akun pengguna dan hak akses sistem.</p>
        </div>
        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-primary text-on-primary text-sm font-semibold
                  hover:bg-primary-light transition-all duration-200 active:scale-[0.98]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container text-left">
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Nama</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Email</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Departemen</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Role</th>
                        <th class="px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light">
                    @forelse($users as $u)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary-surface flex items-center justify-center text-primary-light text-xs font-semibold shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-on-surface">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-on-surface-variant">{{ $u->email }}</td>
                        <td class="px-5 py-3 text-on-surface-variant">{{ $u->department?->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            @php
                                $roleColors = [
                                    'admin' => 'bg-purple-50 text-purple-700',
                                    'finance' => 'bg-blue-50 text-blue-700',
                                    'inventory' => 'bg-emerald-50 text-success',
                                    'user' => 'bg-slate-100 text-slate-gray',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl text-[11px] font-mono font-medium {{ $roleColors[$u->role] ?? 'bg-slate-100 text-slate-600' }}">
                                {{ $u->role_label }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('users.edit', $u) }}"
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
                        <td colspan="5" class="px-5 py-12 text-center text-on-surface-variant">
                            <svg class="w-12 h-12 mx-auto mb-3 text-outline-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm">Belum ada pengguna terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-5 py-3 border-t border-border-light">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
