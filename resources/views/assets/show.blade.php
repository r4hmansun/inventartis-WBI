@extends('layouts.app')

@section('title', $asset->name)
@section('page-title', 'Detail Aset')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('assets.index') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-on-surface transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Aset
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Asset Info Card --}}
        <div class="lg:col-span-2">
            <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
                @php
                    $statusBarClass = match($asset->status) {
                        'active' => 'status-bar-active',
                        'under_repair' => 'status-bar-warning',
                        'disposed' => 'status-bar-danger',
                        default => 'status-bar-slate',
                    };
                @endphp
                <div class="px-6 py-4 border-b border-border-light {{ $statusBarClass }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="font-display text-lg font-semibold text-on-surface">{{ $asset->name }}</h2>
                            <p class="font-mono text-sm text-primary-light mt-1">{{ $asset->asset_code }}</p>
                        </div>
                        @include('components.status-badge', ['status' => $asset->status])
                    </div>
                </div>

                <div class="p-6">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div>
                            <dt class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">Harga Perolehan</dt>
                            <dd class="font-mono text-sm font-medium text-on-surface">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">Tanggal Perolehan</dt>
                            <dd class="text-sm text-on-surface">{{ $asset->purchase_date->format('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">Departemen Saat Ini</dt>
                            <dd class="text-sm text-on-surface">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="font-mono text-xs text-primary-light bg-primary-surface px-1.5 py-0.5 rounded">{{ $asset->currentDepartment->code }}</span>
                                    {{ $asset->currentDepartment->name }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">Didaftarkan Oleh</dt>
                            <dd class="text-sm text-on-surface">{{ $asset->creator->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-1">Tanggal Registrasi</dt>
                            <dd class="text-sm text-on-surface">{{ $asset->created_at->format('d F Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        {{-- Audit Trail Timeline --}}
        <div class="lg:col-span-1">
            <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden">
                <div class="px-5 py-4 border-b border-border-light">
                    <h3 class="font-display text-base font-semibold text-on-surface">Riwayat Aset</h3>
                    <p class="text-xs text-on-surface-variant mt-0.5">Audit trail pergerakan aset</p>
                </div>

                <div class="p-5">
                    @if($asset->histories->isEmpty())
                        <p class="text-sm text-on-surface-variant text-center py-4">Belum ada riwayat.</p>
                    @else
                    <div class="relative">
                        {{-- Timeline line --}}
                        <div class="absolute left-3 top-2 bottom-2 w-0.5 bg-slate-gray/20"></div>

                        <div class="space-y-5">
                            @foreach($asset->histories as $history)
                            <div class="relative flex gap-4">
                                {{-- Dot indicator --}}
                                <div class="relative z-10 w-6 h-6 rounded-full bg-surface-white border-2 border-slate-gray flex items-center justify-center shrink-0">
                                    <div class="w-2 h-2 rounded-full bg-slate-gray"></div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-on-surface">{{ $history->action_label }}</p>
                                    @if($history->fromDepartment)
                                    <p class="text-xs text-on-surface-variant mt-0.5">
                                        {{ $history->fromDepartment->name }} → {{ $history->toDepartment->name }}
                                    </p>
                                    @else
                                    <p class="text-xs text-on-surface-variant mt-0.5">
                                        → {{ $history->toDepartment->name }}
                                    </p>
                                    @endif
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-mono text-on-surface-variant/60">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                        <span class="text-[10px] text-on-surface-variant/40">•</span>
                                        <span class="text-[10px] text-on-surface-variant/60">{{ $history->actor->name }}</span>
                                    </div>
                                    @if($history->notes)
                                    <p class="text-xs text-on-surface-variant mt-1 italic">{{ $history->notes }}</p>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
