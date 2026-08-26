@extends('layouts.app')

@section('title', 'Detail Aset')
@section('page-title', 'Detail Aset')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Tombol Kembali --}}
    <div>
        <a href="{{ route('assets.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-primary-light hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Aset
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Asset Info Card (2 Cols) --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden shadow-sm">
                @php
                    $statusBarClass = match($asset->status) {
                        'active' => 'status-bar-active',
                        'under_repair' => 'status-bar-warning',
                        'disposed' => 'status-bar-danger',
                        default => 'status-bar-slate',
                    };
                @endphp
                <div class="px-6 py-5 border-b border-border-light {{ $statusBarClass }} bg-surface-white">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div>
                            <span class="text-[11px] font-mono uppercase font-semibold text-on-surface-variant/80 tracking-wider">
                                Nomor Registrasi Aset
                            </span>
                            <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface mt-0.5">{{ $asset->name }}</h2>
                            <p class="font-mono text-sm sm:text-base font-bold text-primary-light mt-1">{{ $asset->asset_code }}</p>
                        </div>
                        <div class="self-start">
                            @include('components.status-badge', ['status' => $asset->status])
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-4 pb-2 border-b border-border-light">
                        Spesifikasi &amp; Informasi Kepemilikan
                    </h3>

                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="p-3 rounded-lg bg-surface-container/30 border border-border-light/60">
                            <dt class="text-xs font-semibold text-on-surface-variant mb-1">Harga Perolehan (Nilai Buku)</dt>
                            <dd class="font-mono text-base font-bold text-on-surface">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</dd>
                            <p class="text-[10px] text-emerald-700 mt-0.5">&ge; Rp 500.000 (Terkapitalisasi)</p>
                        </div>

                        <div class="p-3 rounded-lg bg-surface-container/30 border border-border-light/60">
                            <dt class="text-xs font-semibold text-on-surface-variant mb-1">Tanggal Perolehan / Pembelian</dt>
                            <dd class="text-sm font-semibold text-on-surface">{{ $asset->purchase_date->format('d F Y') }}</dd>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">Format: Hari / Bulan / Tahun</p>
                        </div>

                        <div class="p-3 rounded-lg bg-surface-container/30 border border-border-light/60">
                            <dt class="text-xs font-semibold text-on-surface-variant mb-1">Unit / Departemen Penanggung Jawab</dt>
                            <dd class="text-sm font-semibold text-on-surface">
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="font-mono text-xs text-primary-light bg-primary-surface px-2 py-0.5 rounded font-bold">{{ $asset->currentDepartment->code }}</span>
                                    {{ $asset->currentDepartment->name }}
                                </span>
                            </dd>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">Lokasi fisik aset saat ini</p>
                        </div>

                        <div class="p-3 rounded-lg bg-surface-container/30 border border-border-light/60">
                            <dt class="text-xs font-semibold text-on-surface-variant mb-1">Didaftarkan Oleh</dt>
                            <dd class="text-sm font-semibold text-on-surface">{{ $asset->creator->name }}</dd>
                            <p class="text-[10px] text-on-surface-variant mt-0.5">{{ $asset->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Info Banner Bantuan --}}
            <div class="p-4 rounded-lg bg-stone-50 border border-stone-200 text-xs text-stone-700 flex items-start gap-3">
                <svg class="w-5 h-5 text-primary-light shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="font-semibold text-stone-900">Perhatian untuk Pemegang Barang:</p>
                    <p class="mt-0.5 leading-relaxed">
                        Jika barang ini hendak dipindahkan ke ruangan/departemen lain atau membutuhkan perbaikan teknis, laporkan kode <strong class="font-mono">{{ $asset->asset_code }}</strong> ke Bagian Inventaris untuk pembuatan Berita Acara resmi.
                    </p>
                </div>
            </div>
        </div>

        {{-- Audit Trail Timeline (1 Col) --}}
        <div class="lg:col-span-1">
            <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden shadow-sm">
                <div class="px-5 py-4 border-b border-border-light bg-surface-white flex items-center justify-between">
                    <div>
                        <h3 class="font-display text-base font-bold text-on-surface">Riwayat Pergerakan</h3>
                        <p class="text-xs text-on-surface-variant">Catatan mutasi fisik barang</p>
                    </div>
                    <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-stone-100 text-stone-600 border border-stone-200">
                        Resmi
                    </span>
                </div>

                <div class="p-5">
                    @if($asset->histories->isEmpty())
                        <div class="text-center py-6 text-on-surface-variant text-xs space-y-1">
                            <svg class="w-8 h-8 mx-auto text-outline-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="font-semibold text-on-surface">Belum Ada Riwayat Mutasi</p>
                            <p class="text-[11px]">Aset masih berada di lokasi awal pendaftaran.</p>
                        </div>
                    @else
                    <div class="relative">
                        {{-- Timeline line --}}
                        <div class="absolute left-3.5 top-2 bottom-2 w-0.5 bg-slate-gray/20"></div>

                        <div class="space-y-6">
                            @foreach($asset->histories as $history)
                            <div class="relative flex gap-3.5">
                                {{-- Dot indicator --}}
                                <div class="relative z-10 w-7 h-7 rounded-full bg-surface-white border-2 border-primary-light flex items-center justify-center shrink-0 shadow-2xs">
                                    <div class="w-2.5 h-2.5 rounded-full bg-primary-light"></div>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-on-surface">{{ $history->action_label }}</p>
                                    @if($history->fromDepartment)
                                    <p class="text-xs text-on-surface-variant mt-0.5">
                                        <span class="font-medium text-stone-700">{{ $history->fromDepartment->name }}</span>
                                        <span class="font-mono text-primary-light">&rarr;</span>
                                        <span class="font-medium text-stone-900">{{ $history->toDepartment->name }}</span>
                                    </p>
                                    @else
                                    <p class="text-xs text-on-surface-variant mt-0.5">
                                        Masuk ke: <span class="font-medium text-stone-900">{{ $history->toDepartment->name }}</span>
                                    </p>
                                    @endif
                                    <div class="flex items-center gap-1.5 mt-1 text-[10px] font-mono text-on-surface-variant">
                                        <span>{{ $history->created_at->format('d/m/Y H:i') }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $history->actor ? $history->actor->name : 'Sistem' }}</span>
                                    </div>
                                    @if($history->notes)
                                    <p class="text-[11px] text-on-surface-variant mt-1.5 bg-surface-container/50 p-2 rounded border border-border-light/60">
                                        {{ $history->notes }}
                                    </p>
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

