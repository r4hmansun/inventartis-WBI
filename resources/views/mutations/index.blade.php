@extends('layouts.app')

@section('title', 'Mutasi Aset')
@section('page-title', 'Mutasi Aset')

@section('content')
<div class="max-w-7xl mx-auto space-y-5 w-full min-w-0">
    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface">Mutasi Aset</h2>
            <p class="text-xs sm:text-sm text-on-surface-variant mt-0.5">
                Siklus persetujuan digital resmi pemindahan aset antar-departemen di lingkungan WBI.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="openSystemGuideModal('mutasi-aset')"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-border-light bg-surface-white text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                <svg class="w-4 h-4 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Panduan Alur Mutasi
            </button>
            <a href="{{ route('mutations.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-white text-xs sm:text-sm font-bold
                      hover:bg-primary-light transition-all duration-200 active:scale-[0.98] shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajukan Mutasi Baru
            </a>
        </div>
    </div>

    {{-- Workflow Mini Guide Ribbon --}}
    <div class="p-3.5 rounded-xl bg-surface-white border border-border-light shadow-2xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 text-xs">
            <div class="flex items-center gap-2.5 p-2 rounded-lg bg-surface-container/40">
                <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-800 font-mono font-bold flex items-center justify-center shrink-0 text-xs">1</span>
                <div>
                    <p class="font-bold text-on-surface text-[11px]">Penerbitan &amp; TTD Pengirim</p>
                    <p class="text-[10px] text-on-surface-variant">Unit pengirim ajukan barang</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 p-2 rounded-lg bg-amber-50/70 border border-amber-200/60">
                <span class="w-6 h-6 rounded-full bg-amber-200 text-amber-900 font-mono font-bold flex items-center justify-center shrink-0 text-xs">2</span>
                <div>
                    <p class="font-bold text-amber-950 text-[11px]">Approval Penerima</p>
                    <p class="text-[10px] text-amber-800">Unit penerima verifikasi &amp; setujui</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5 p-2 rounded-lg bg-teal-50/70 border border-teal-200/60">
                <span class="w-6 h-6 rounded-full bg-teal-200 text-teal-900 font-mono font-bold flex items-center justify-center shrink-0 text-xs">3</span>
                <div>
                    <p class="font-bold text-teal-950 text-[11px]">Eksekusi &amp; Arsip Inventaris</p>
                    <p class="text-[10px] text-teal-800">Inventaris pindahkan data &amp; arsip</p>
                </div>
            </div>
        </div>
    </div>


    {{-- Tabs Lingkup / Filter Cepat Status --}}
    <div class="flex items-center gap-2 border-b border-border-light pb-2 overflow-x-auto whitespace-nowrap scrollbar-none">
        <a href="{{ route('mutations.index') }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all
                  {{ !request('scope') && !request('status') ? 'bg-primary text-white shadow-xs' : 'bg-surface-white text-on-surface-variant hover:bg-surface-container border border-border-light' }}">
            <span>Semua Mutasi</span>
        </a>

        @if($user->department_id)
        <a href="{{ route('mutations.index', ['scope' => 'waiting_my_approval']) }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all
                  {{ request('scope') === 'waiting_my_approval' ? 'bg-amber-700 text-white shadow-xs' : 'bg-surface-white text-on-surface-variant hover:bg-surface-container border border-border-light' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Menunggu Approval Unit Saya</span>
            @if($waitingMyApprovalCount > 0)
            <span class="px-1.5 py-0.2 text-[10px] font-mono font-bold rounded-full {{ request('scope') === 'waiting_my_approval' ? 'bg-white text-amber-900' : 'bg-amber-100 text-amber-900' }}">
                {{ $waitingMyApprovalCount }}
            </span>
            @endif
        </a>
        @endif

        <a href="{{ route('mutations.index', ['scope' => 'ready_execution']) }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all
                  {{ request('scope') === 'ready_execution' ? 'bg-primary text-white shadow-xs' : 'bg-surface-white text-on-surface-variant hover:bg-surface-container border border-border-light' }}">
            <span>Siap Eksekusi</span>
            @if($readyExecutionCount > 0)
            <span class="px-1.5 py-0.2 text-[10px] font-mono font-bold rounded-full {{ request('scope') === 'ready_execution' ? 'bg-white/20 text-white' : 'bg-surface-container text-on-surface' }}">
                {{ $readyExecutionCount }}
            </span>
            @endif
        </a>

        <a href="{{ route('mutations.index', ['scope' => 'archived']) }}"
           class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs sm:text-sm font-semibold transition-all
                  {{ request('scope') === 'archived' ? 'bg-primary text-white shadow-xs' : 'bg-surface-white text-on-surface-variant hover:bg-surface-container border border-border-light' }}">
            <span>Arsip Selesai</span>
        </a>
    </div>

    {{-- Filters Box --}}
    <div class="bg-surface-white rounded-xl border border-border-light p-4 shadow-xs">
        <form method="GET" action="{{ route('mutations.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            @if(request('scope'))
            <input type="hidden" name="scope" value="{{ request('scope') }}">
            @endif

            {{-- Search --}}
            <div class="sm:col-span-5">
                <label for="search" class="block text-xs font-semibold text-on-surface-variant mb-1">Cari No. Formulir / Alasan</label>
                <div class="relative">
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Ketik MUT/... atau alasan mutasi"
                           class="w-full pl-9 pr-4 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-xs sm:text-sm
                                  placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                    <svg class="w-4 h-4 text-on-surface-variant/60 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="sm:col-span-3">
                <label for="status" class="block text-xs font-semibold text-on-surface-variant mb-1">Status Persetujuan</label>
                <select id="status" name="status"
                        class="w-full px-3 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-xs sm:text-sm
                               focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                    <option value="">Semua Status</option>
                    <option value="waiting_receiver" {{ request('status') === 'waiting_receiver' ? 'selected' : '' }}>Menunggu Approval Penerima</option>
                    <option value="ready_for_execution" {{ request('status') === 'ready_for_execution' ? 'selected' : '' }}>Siap Eksekusi (Disetujui Ganda)</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Selesai / Diarsipkan</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Department Filter --}}
            <div class="sm:col-span-3">
                <label for="department_id" class="block text-xs font-semibold text-on-surface-variant mb-1">Unit Terkait</label>
                <select id="department_id" name="department_id"
                        class="w-full px-3 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-xs sm:text-sm
                               focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                    <option value="">Semua Departemen</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }} ({{ $dept->code }})</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Actions --}}
            <div class="sm:col-span-1 flex items-end">
                <button type="submit"
                        class="w-full py-2 rounded-md bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all text-center shadow-2xs">
                    Filter
                </button>
            </div>
        </form>

        @if(request()->hasAny(['search', 'status', 'department_id', 'scope']))
        <div class="mt-3 pt-2.5 border-t border-border-light flex items-center justify-between text-xs text-on-surface-variant">
            <span>Filter aktif diterapkan</span>
            <a href="{{ route('mutations.index') }}"
               class="text-primary-light font-semibold hover:underline inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset Filter
            </a>
        </div>
        @endif
    </div>

    {{-- Mutations Table --}}
    <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[700px]">
                <thead>
                    <tr class="bg-surface-container/60 border-b border-border-light text-[11px] font-mono font-semibold text-on-surface-variant uppercase tracking-wider">
                        <th class="px-5 py-3.5">No. Formulir</th>
                        <th class="px-5 py-3.5">Alur Perpindahan Unit</th>
                        <th class="px-5 py-3.5">Barang / Aset</th>
                        <th class="px-5 py-3.5">Pemohon &amp; Tanggal</th>
                        <th class="px-5 py-3.5">Status Alur</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light text-xs sm:text-sm">
                    @forelse($mutations as $mutation)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-5 py-4 font-mono font-bold text-primary-light whitespace-nowrap">
                            {{ $mutation->form_number }}
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-on-surface">
                                <span class="px-2 py-0.5 rounded bg-surface-container text-on-surface font-mono text-[11px]">
                                    {{ $mutation->fromDepartment ? $mutation->fromDepartment->code : '-' }}
                                </span>
                                <span class="text-on-surface-variant font-mono">&rarr;</span>
                                <span class="px-2 py-0.5 rounded bg-primary/10 text-primary-light font-mono font-semibold text-[11px]">
                                    {{ $mutation->toDepartment ? $mutation->toDepartment->code : '-' }}
                                </span>
                            </div>
                            <p class="text-[11px] text-on-surface-variant mt-1 truncate max-w-xs">
                                {{ $mutation->fromDepartment?->name }} ke {{ $mutation->toDepartment?->name }}
                            </p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="font-semibold text-on-surface text-xs">
                                {{ $mutation->items->count() }} Unit Barang
                            </p>
                            <p class="text-[11px] text-on-surface-variant truncate max-w-xs mt-0.5">
                                {{ $mutation->items->pluck('asset.name')->implode(', ') }}
                            </p>
                        </td>
                        <td class="px-5 py-4 text-xs text-on-surface-variant whitespace-nowrap">
                            <p class="font-medium text-on-surface">{{ $mutation->sender ? $mutation->sender->name : 'Sistem' }}</p>
                            <p class="text-[11px] font-mono text-on-surface-variant/70">{{ $mutation->created_at->format('d M Y, H:i') }}</p>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            @include('components.status-badge', ['status' => $mutation->status])
                        </td>
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('mutations.show', $mutation) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border-light bg-surface-white text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail &amp; Approval
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-on-surface-variant">
                            <div class="max-w-md mx-auto space-y-2 text-center">
                                <div class="w-12 h-12 mx-auto rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-on-surface">Belum Ada Formulir Mutasi</h4>
                                <p class="text-xs text-on-surface-variant">
                                    Pengajuan perpindahan aset antar-departemen akan ditampilkan di sini.
                                </p>
                                <div class="pt-2">
                                    <a href="{{ route('mutations.create') }}"
                                       class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-light transition-colors shadow-2xs">
                                        + Terbitkan Form Mutasi
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mutations->hasPages())
        <div class="px-5 py-3.5 border-t border-border-light bg-surface-white">
            {{ $mutations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
