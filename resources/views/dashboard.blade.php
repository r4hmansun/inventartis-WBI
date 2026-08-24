@extends('layouts.app')

@section('title', auth()->user()->hasRole('user') ? 'Portal Inventaris Unit Anda' : 'Dashboard Eksekutif')
@section('page-title', auth()->user()->hasRole('user') ? 'Portal Inventaris Unit Anda' : 'Ringkasan Eksekutif & Operasional')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    @if(auth()->user()->hasRole('user'))
    {{-- ========================================================================= --}}
    {{-- PORTAL INVENTARIS UNIT KERJA (STAF / KEPALA UNIT) --}}
    {{-- ========================================================================= --}}

    {{-- 1. Unit Context Header --}}
    <div class="bg-surface-white rounded-xl border border-border-light p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-2 mb-1.5">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Unit: {{ $userDepartment ? $userDepartment->name : 'Staf / Operasional' }}
                    </span>
                    <span class="text-xs text-on-surface-variant bg-surface-container px-2.5 py-0.5 rounded-full font-mono font-medium">
                        {{ $userDepartment ? $userDepartment->code : 'USER' }}
                    </span>
                </div>
                <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface">
                    Halo, {{ $user->name }}
                </h2>
                <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed max-w-2xl">
                    Kelola peralatan kerja unit Anda, periksa barang terdaftar, dan ajukan permohonan mutasi inventaris secara resmi.
                </p>
            </div>

            {{-- Quick Action Buttons --}}
            <div class="flex flex-wrap items-center gap-2.5 pt-3 lg:pt-0 border-t lg:border-t-0 border-border-light">
                <a href="{{ route('assets.index', ['scope' => 'my_dept']) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Semua Aset Unit
                </a>
                <button type="button" onclick="openGuideModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border-light bg-surface-white text-xs sm:text-sm font-medium text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                    <svg class="w-4 h-4 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Panduan Minta Kode Aset
                </button>
            </div>
        </div>
    </div>

    {{-- 2. Ringkasan Metrik Unit --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @include('components.stat-card', [
            'title' => 'Barang di Unit Anda',
            'value' => $deptTotalAssets,
            'unit' => 'Unit',
            'badge' => $userDepartment ? $userDepartment->code : 'Unit',
            'badgeType' => 'neutral',
            'subvalue' => 'Peralatan resmi tercatat',
            'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'
        ])

        @include('components.stat-card', [
            'title' => 'Siap Digunakan',
            'value' => $deptActiveAssets,
            'unit' => 'Aktif',
            'badge' => 'Kondisi Baik',
            'badgeType' => 'success',
            'subvalue' => 'Operasional sehari-hari',
            'icon' => '<svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ])

        @include('components.stat-card', [
            'title' => 'Dalam Perbaikan',
            'value' => $deptRepairAssets,
            'unit' => 'Unit',
            'badge' => $deptRepairAssets > 0 ? 'Perlu Tindakan' : 'Nihil Rusak',
            'badgeType' => $deptRepairAssets > 0 ? 'warning' : 'neutral',
            'subvalue' => $deptRepairAssets > 0 ? 'Sedang ditangani teknisi' : 'Semua barang prima',
            'icon' => '<svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>'
        ])

        @include('components.stat-card', [
            'title' => 'Nilai Inventaris Unit',
            'value' => 'Rp ' . number_format($deptValuation, 0, ',', '.'),
            'unit' => '',
            'badge' => 'Total Nilai',
            'badgeType' => 'teal',
            'subvalue' => 'Akumulasi harga perolehan',
            'icon' => '<svg class="w-4 h-4 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ])
    </div>

    {{-- 3. Panduan Alur Singkat (Clean Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        {{-- Card 1 --}}
        <div class="bg-surface-white rounded-xl border border-border-light p-4 sm:p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition-all">
            <div>
                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <h3 class="font-display text-sm font-bold text-on-surface mb-1">
                    Pengadaan Barang Baru
                </h3>
                <p class="text-xs text-on-surface-variant leading-relaxed mb-3">
                    Pembelian barang seharga <strong>&ge; Rp 500.000</strong> wajib dilaporkan ke Keuangan untuk penomoran kode aset resmi.
                </p>
            </div>
            <button type="button" onclick="openGuideModal()"
                    class="w-full text-center px-3 py-2 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold hover:bg-emerald-100 transition-colors">
                Format Pengajuan &rarr;
            </button>
        </div>

        {{-- Card 2 --}}
        <div class="bg-surface-white rounded-xl border border-border-light p-4 sm:p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition-all">
            <div>
                <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <h3 class="font-display text-sm font-bold text-on-surface mb-1">
                    Mutasi &amp; Pindah Barang
                </h3>
                <p class="text-xs text-on-surface-variant leading-relaxed mb-3">
                    Barang dipindahkan antar-ruangan atau departemen? Proses melalui formulir mutasi resmi dengan tombol persetujuan digital.
                </p>
            </div>
            <a href="{{ route('mutations.create') }}"
               class="w-full text-center px-3 py-2 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 text-xs font-semibold hover:bg-amber-100 transition-colors">
                Ajukan Mutasi Barang &rarr;
            </a>
        </div>

        {{-- Card 3 --}}
        <div class="bg-surface-white rounded-xl border border-border-light p-4 sm:p-5 shadow-xs flex flex-col justify-between hover:border-slate-300 transition-all">
            <div>
                <div class="w-9 h-9 rounded-lg bg-teal-50 text-primary-light flex items-center justify-center mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="font-display text-sm font-bold text-on-surface mb-1">
                    Cek Aturan Harga Barang
                </h3>
                <p class="text-xs text-on-surface-variant leading-relaxed mb-2">
                    Ketik nominal harga perolehan untuk cek kategori:
                </p>
                <div class="space-y-2">
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-xs font-mono text-on-surface-variant">Rp</span>
                        <input type="number" id="quick-price-calc" placeholder="Contoh: 750000"
                               class="w-full pl-9 pr-3 py-1.5 text-xs font-mono rounded-md border border-outline-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20"
                               oninput="calculateAssetCategory(this.value)">
                    </div>
                    <div id="quick-calc-result" class="p-2 rounded-md text-xs hidden"></div>
                </div>
            </div>
            <p class="text-[11px] text-on-surface-variant/70 mt-2">
                Aturan WBI: Batas kapitalisasi aset &ge; Rp 500.000.
            </p>
        </div>
    </div>

    {{-- 4. Daftar Inventaris di Unit --}}
    <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
        <div class="px-5 py-4 border-b border-border-light flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div class="flex items-center gap-2.5">
                <div class="w-2.5 h-2.5 rounded-full bg-primary"></div>
                <div>
                    <h3 class="font-display text-sm sm:text-base font-bold text-on-surface">
                        Daftar Inventaris Unit {{ $userDepartment ? $userDepartment->name : 'Anda' }}
                    </h3>
                    <p class="text-xs text-on-surface-variant">
                        Barang-barang yang tercatat di unit Anda saat ini.
                    </p>
                </div>
            </div>
            <a href="{{ route('assets.index', ['scope' => 'my_dept']) }}"
               class="text-xs font-semibold text-primary-light hover:text-primary transition-colors inline-flex items-center gap-1">
                Buka Seluruh Aset Unit &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-surface-container/60 border-b border-border-light text-[11px] font-mono font-semibold text-on-surface-variant uppercase tracking-wider">
                        <th class="px-5 py-3">Kode Aset</th>
                        <th class="px-5 py-3">Nama Barang</th>
                        <th class="px-5 py-3">Nilai Perolehan</th>
                        <th class="px-5 py-3">Tanggal Diterima</th>
                        <th class="px-5 py-3">Status</th>
                        <th class="px-5 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light text-xs">
                    @forelse($deptAssets as $asset)
                    <tr class="table-row-hover transition-colors">
                        <td class="px-5 py-3.5 font-mono font-semibold text-primary-light whitespace-nowrap">
                            {{ $asset->asset_code }}
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-semibold text-on-surface text-sm">{{ $asset->name }}</p>
                            <p class="text-[11px] text-on-surface-variant mt-0.5">Didaftarkan: {{ $asset->creator ? $asset->creator->name : 'Sistem' }}</p>
                        </td>
                        <td class="px-5 py-3.5 font-mono text-xs text-on-surface font-medium whitespace-nowrap">
                            Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}
                        </td>
                        <td class="px-5 py-3.5 text-on-surface-variant whitespace-nowrap font-mono text-[11px]">
                            {{ $asset->purchase_date ? $asset->purchase_date->format('d M Y') : '-' }}
                        </td>
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @include('components.status-badge', ['status' => $asset->status])
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('assets.show', $asset) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-border-light bg-surface-white text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                                <svg class="w-3.5 h-3.5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center">
                            <div class="max-w-md mx-auto text-center space-y-3">
                                <div class="w-12 h-12 mx-auto rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-on-surface">Belum Ada Inventaris Terdaftar</h4>
                                <p class="text-xs text-on-surface-variant leading-relaxed">
                                    Jika unit Anda baru saja membeli barang seharga &ge; Rp 500.000, silakan hubungi Bagian Keuangan untuk pencatatan kode inventaris resmi.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @else
    {{-- ========================================================================= --}}
    {{-- DASHBOARD EKSEKUTIF UNTUK ADMIN, KEUANGAN & INVENTARIS --}}
    {{-- ========================================================================= --}}

    {{-- 1. Executive Context & Action Bar --}}
    <div class="bg-surface-white rounded-xl border border-border-light p-4 sm:p-5 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono font-semibold uppercase tracking-wider bg-primary text-white">
                        WBI Inventaris
                    </span>
                    <span class="text-xs font-mono text-on-surface-variant">
                        Tahun Anggaran {{ date('Y') }} &bull; Sem. {{ date('n') <= 6 ? 'I' : 'II' }}
                    </span>
                </div>
                <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface">
                    Ringkasan Eksekutif &amp; Operasional
                </h2>
                <p class="text-xs text-on-surface-variant mt-0.5">
                    Monitoring kapitalisasi aset (&ge; Rp 500.000), stok gudang, dan pergerakan mutasi antar-unit.
                </p>
            </div>

            {{-- Action Cluster --}}
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" onclick="openSopModal()"
                        class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-border-light bg-surface-white text-xs font-medium text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Panduan Alur SOP
                </button>

                @if(auth()->user()->hasRole('finance', 'admin'))
                <a href="{{ route('assets.create') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-light transition-all shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Registrasi Aset Baru
                </a>
                @endif

                <a href="{{ route('assets.index') }}"
                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-border-light bg-surface-white text-xs font-medium text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Daftar Aset
                </a>
            </div>
        </div>
    </div>

    {{-- 2. Executive Metric Grid (4 Clean KPI Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Aset --}}
        @include('components.stat-card', [
            'title' => 'Total Aset Terdata',
            'value' => number_format($stats['total_assets']),
            'unit' => 'Unit',
            'badge' => 'Terkapitalisasi',
            'badgeType' => 'teal',
            'subvalue' => 'Nilai: <strong class="text-on-surface font-mono">Rp ' . number_format($stats['total_valuation'], 0, ',', '.') . '</strong>',
            'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>'
        ])

        {{-- Card 2: Aset Aktif --}}
        @include('components.stat-card', [
            'title' => 'Aset Operasional',
            'value' => number_format($stats['active_assets']),
            'unit' => 'Unit',
            'badge' => ($stats['total_assets'] > 0 ? round(($stats['active_assets'] / $stats['total_assets']) * 100) : 0) . '% Aktif',
            'badgeType' => 'success',
            'subvalue' => 'Aktif di departemen pengguna',
            'icon' => '<svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
        ])

        {{-- Card 3: Di Gudang --}}
        @include('components.stat-card', [
            'title' => 'Gudang Inventaris',
            'value' => number_format($stats['in_storage']),
            'unit' => 'Unit',
            'badge' => 'Pool Cadangan',
            'badgeType' => 'slate',
            'subvalue' => 'Siap disalurkan ke unit',
            'icon' => '<svg class="w-4 h-4 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>'
        ])

        {{-- Card 4: Mutasi Pending --}}
        @include('components.stat-card', [
            'title' => 'Siklus Mutasi Aset',
            'value' => number_format($stats['pending_mutations']),
            'unit' => 'Form',
            'badge' => 'Dual-Approval',
            'badgeType' => ($stats['pending_mutations'] > 0 ? 'warning' : 'neutral'),
            'subvalue' => '<span>' . $stats['waiting_receiver'] . ' Tunggu Approval</span> &bull; <span>' . $stats['ready_execution'] . ' Eksekusi</span>',
            'icon' => '<svg class="w-4 h-4 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>'
        ])
    </div>

    {{-- 3. Visual Analytics Section (5 Charts Sesuai DESIGN.md) --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-primary"></div>
                <h3 class="font-display text-base font-bold text-on-surface">Analitik &amp; Visualisasi Inventaris</h3>
                <span class="text-[11px] font-mono text-on-surface-variant">(5 Grafik Interaktif)</span>
            </div>
            <span class="text-xs font-mono text-on-surface-variant">Update Realtime</span>
        </div>

        {{-- Baris 1: 2 Grafik Utama (Doughnut Status & Bar Valuasi Departemen) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            {{-- Grafik 1: Distribusi Status Aset --}}
            <div class="lg:col-span-5 bg-surface-white rounded-xl border border-border-light p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-display text-sm font-bold text-on-surface">1. Distribusi Status Aset</h4>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-surface-container text-on-surface-variant font-medium">Status Proporsi</span>
                    </div>
                    <p class="text-xs text-on-surface-variant mb-4">Sebaran status operasional, gudang penampungan, dan perbaikan.</p>
                    <div class="relative h-56 flex items-center justify-center">
                        <canvas id="chartAssetStatus"></canvas>
                    </div>
                </div>
                <div class="pt-3 mt-2 border-t border-border-light grid grid-cols-2 gap-2 text-[11px] font-mono text-on-surface-variant">
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-700"></span> Aktif: {{ $stats['active_assets'] }} Unit</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-slate-600"></span> Gudang: {{ $stats['in_storage'] }} Unit</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-600"></span> Perbaikan: {{ $stats['under_repair'] }} Unit</div>
                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-rose-700"></span> Dihapus: {{ $stats['disposed'] }} Unit</div>
                </div>
            </div>

            {{-- Grafik 2: Valuasi Nilai Aset per Departemen --}}
            <div class="lg:col-span-7 bg-surface-white rounded-xl border border-border-light p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-display text-sm font-bold text-on-surface">2. Valuasi Nilai Kapitalisasi Aset per Unit</h4>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-surface-container text-primary font-bold">Rupiah (IDR)</span>
                    </div>
                    <p class="text-xs text-on-surface-variant mb-4">Akumulasi nilai buku barang (&ge; Rp 500.000) di unit kerja pemegang aset.</p>
                    <div class="relative h-56">
                        <canvas id="chartDeptValuations"></canvas>
                    </div>
                </div>
                <div class="pt-3 mt-2 border-t border-border-light flex items-center justify-between text-[11px] text-on-surface-variant font-mono">
                    <span>Total Valuasi Terdata:</span>
                    <strong class="text-on-surface font-bold">Rp {{ number_format($stats['total_valuation'], 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        {{-- Baris 2: 3 Grafik Pendukung (Tren Mutasi, Kondisi Fisik, Top Penerima) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Grafik 3: Tren Mutasi Aset Bulanan --}}
            <div class="bg-surface-white rounded-xl border border-border-light p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-display text-sm font-bold text-on-surface">3. Tren Mutasi Bulanan</h4>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-surface-container text-secondary font-bold">6 Bulan</span>
                    </div>
                    <p class="text-xs text-on-surface-variant mb-3">Volume permohonan perpindahan antar-unit.</p>
                    <div class="relative h-44">
                        <canvas id="chartMonthlyTrends"></canvas>
                    </div>
                </div>
                <div class="pt-2.5 mt-2 border-t border-border-light text-[11px] text-on-surface-variant flex items-center justify-between font-mono">
                    <span>Total Formulir:</span>
                    <span class="font-bold text-primary">{{ $stats['pending_mutations'] + $stats['archived_mutations'] }} Form</span>
                </div>
            </div>

            {{-- Grafik 4: Komposisi Kondisi Fisik --}}
            <div class="bg-surface-white rounded-xl border border-border-light p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-display text-sm font-bold text-on-surface">4. Kondisi Fisik Inventaris</h4>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-surface-container text-emerald-800 font-bold">Kelayakan</span>
                    </div>
                    <p class="text-xs text-on-surface-variant mb-3">Kualitas fisik barang yang dilaporkan.</p>
                    <div class="relative h-44 flex items-center justify-center">
                        <canvas id="chartConditions"></canvas>
                    </div>
                </div>
                <div class="pt-2.5 mt-2 border-t border-border-light text-[11px] text-on-surface-variant flex items-center justify-around font-mono">
                    <span class="text-emerald-700 font-semibold">Baik: {{ $chartConditions['data'][0] }}</span>
                    <span class="text-amber-700 font-semibold">Rusak Ringan: {{ $chartConditions['data'][1] }}</span>
                    <span class="text-rose-700 font-semibold">Rusak Berat: {{ $chartConditions['data'][2] }}</span>
                </div>
            </div>

            {{-- Grafik 5: Top 5 Unit Distribusi Penerima --}}
            <div class="bg-surface-white rounded-xl border border-border-light p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="font-display text-sm font-bold text-on-surface">5. Unit Distribusi Penerima</h4>
                        <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-surface-container text-primary font-bold">Top 5</span>
                    </div>
                    <p class="text-xs text-on-surface-variant mb-3">Departemen dengan frekuensi mutasi masuk tertinggi.</p>
                    <div class="relative h-44">
                        <canvas id="chartTopReceivers"></canvas>
                    </div>
                </div>
                <div class="pt-2.5 mt-2 border-t border-border-light text-[11px] text-on-surface-variant flex items-center justify-between font-mono">
                    <span>Arsip Sah Terkunci:</span>
                    <span class="font-bold text-emerald-700">{{ $stats['archived_mutations'] }} BAST</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. Main Operational Ledger: 2-Column Balanced Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left: Daftar Aset Inventaris Terbaru (7 Cols) --}}
        <div class="lg:col-span-7 bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs flex flex-col justify-between">
            <div>
                <div class="px-5 py-3.5 border-b border-border-light flex items-center justify-between bg-surface-white">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-primary"></div>
                        <h3 class="font-display text-sm font-bold text-on-surface">Aset Inventaris Terbaru</h3>
                        <span class="text-[11px] font-mono text-on-surface-variant">({{ $recentAssets->count() }} Terkini)</span>
                    </div>
                    <a href="{{ route('assets.index') }}" class="text-xs font-semibold text-primary-light hover:text-primary transition-colors inline-flex items-center gap-1">
                        Lihat Seluruh Aset &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="bg-surface-container/60 border-b border-border-light text-[11px] font-mono font-semibold text-on-surface-variant uppercase tracking-wider">
                                <th class="px-4 py-2.5">Kode Aset</th>
                                <th class="px-4 py-2.5">Nama &amp; Nilai</th>
                                <th class="px-4 py-2.5">Departemen</th>
                                <th class="px-4 py-2.5">Status</th>
                                <th class="px-4 py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light text-xs">
                            @forelse($recentAssets as $asset)
                            <tr class="table-row-hover transition-colors">
                                <td class="px-4 py-3 font-mono font-medium text-primary-light whitespace-nowrap">
                                    {{ $asset->asset_code }}
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-on-surface">{{ $asset->name }}</p>
                                    <p class="text-[11px] font-mono text-on-surface-variant">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-surface-container text-on-surface">
                                        {{ $asset->currentDepartment ? $asset->currentDepartment->name : 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @include('components.status-badge', ['status' => $asset->status])
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ route('assets.show', $asset) }}"
                                       class="inline-flex items-center px-2.5 py-1 rounded-md border border-border-light bg-surface-white text-[11px] font-semibold text-on-surface-variant hover:text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-4 py-10 text-center">
                                    <div class="max-w-xs mx-auto text-center space-y-2">
                                        <div class="w-10 h-10 mx-auto rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                        <p class="text-xs font-semibold text-on-surface">Belum Ada Aset Terdaftar</p>
                                        <p class="text-[11px] text-on-surface-variant">
                                            Aset yang dicatat oleh Bagian Keuangan (nilai &ge; Rp 500.000) akan tampil di sini.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="px-4 py-2.5 bg-surface-container/30 border-t border-border-light text-[11px] font-mono text-on-surface-variant flex items-center justify-between">
                <span>Ambang Batas Kapitalisasi: &ge; Rp 500.000</span>
                <span>Standar: AST/[DEPT]/[MM]/[YYYY]/[NO]</span>
            </div>
        </div>

        {{-- Right: Mutasi & Audit Trail Stream (5 Cols) --}}
        <div class="lg:col-span-5 space-y-5">

            {{-- Mutasi Form List --}}
            <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
                <div class="px-4 py-3.5 border-b border-border-light flex items-center justify-between bg-surface-white">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-secondary-hover"></div>
                        <h3 class="font-display text-sm font-bold text-on-surface">Alur Mutasi Terkini</h3>
                    </div>
                    <a href="{{ route('mutations.index') }}" class="text-[11px] font-semibold text-primary-light hover:text-primary transition-colors">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="divide-y divide-border-light text-xs">
                    @forelse($recentMutations as $mutation)
                    <a href="{{ route('mutations.show', $mutation) }}" class="block p-3.5 hover:bg-surface-container/30 transition-colors">
                        <div class="flex items-center justify-between gap-2 mb-1">
                            <span class="font-mono text-xs font-semibold text-secondary-hover">
                                {{ $mutation->form_number }}
                            </span>
                            @include('components.status-badge', ['status' => $mutation->status])
                        </div>
                        <div class="flex items-center gap-1.5 text-xs text-on-surface mt-1">
                            <span class="font-medium">{{ $mutation->fromDepartment ? $mutation->fromDepartment->name : 'N/A' }}</span>
                            <span class="text-on-surface-variant font-mono">&rarr;</span>
                            <span class="font-medium">{{ $mutation->toDepartment ? $mutation->toDepartment->name : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-on-surface-variant font-mono mt-1.5">
                            <span>Oleh: {{ $mutation->sender ? $mutation->sender->name : 'Sistem' }}</span>
                            <span>{{ $mutation->created_at ? $mutation->created_at->format('d/m/Y') : '-' }}</span>
                        </div>
                    </a>
                    @empty
                    <div class="p-6 text-center">
                        <div class="w-8 h-8 mx-auto rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                        </div>
                        <p class="text-xs font-semibold text-on-surface">Belum Ada Mutasi Berjalan</p>
                        <p class="text-[11px] text-on-surface-variant mt-0.5">
                            Pengajuan mutasi aset akan ditampilkan di sini.
                        </p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Audit Trail (Log Riwayat) --}}
            <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
                <div class="px-4 py-3.5 border-b border-border-light flex items-center justify-between bg-surface-white">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-slate-gray"></div>
                        <h3 class="font-display text-sm font-bold text-on-surface">Audit Trail Terkini</h3>
                    </div>
                    <span class="text-[10px] font-mono px-1.5 py-0.5 rounded bg-stone-100 text-stone-600 border border-stone-200">
                        LOG PERMANEN
                    </span>
                </div>

                <div class="p-3.5">
                    @if($recentAuditLogs->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentAuditLogs as $log)
                        <div class="flex items-start gap-2.5 text-xs">
                            <div class="w-1.5 h-1.5 rounded-full bg-primary-light mt-1.5 shrink-0"></div>
                            <div class="flex-1">
                                <p class="text-xs text-on-surface">
                                    <strong class="font-semibold">{{ $log->action_label }}</strong>:
                                    <span class="font-mono text-primary-light font-medium">{{ $log->asset ? $log->asset->asset_code : 'Aset' }}</span>
                                </p>
                                <p class="text-[11px] text-on-surface-variant font-mono">
                                    {{ $log->actor ? $log->actor->name : 'Sistem' }} &bull; {{ $log->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="py-3 text-center">
                        <p class="text-xs text-on-surface-variant">Log audit pergerakan aset terekam secara otomatis.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- 4. Department Asset Distribution Grid --}}
    <div class="bg-surface-white rounded-xl border border-border-light p-4 sm:p-5 shadow-xs">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-border-light">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <h3 class="font-display text-xs font-bold uppercase tracking-wider text-on-surface">
                    Distribusi Aset Per Departemen
                </h3>
            </div>
            <span class="text-[11px] font-mono text-on-surface-variant">
                Total {{ $departments->count() }} Departemen Aktif
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            @foreach($departments as $dept)
            <div class="p-3 rounded-lg border border-border-light bg-surface-container/20 hover:bg-surface-container/50 transition-colors flex flex-col justify-between">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[10px] font-mono font-bold text-primary-light uppercase tracking-wider">{{ $dept->code }}</span>
                    <span class="font-mono text-xs font-bold text-on-surface">{{ $dept->assets_count }} <span class="text-[10px] font-normal text-on-surface-variant">unit</span></span>
                </div>
                <p class="text-xs text-on-surface-variant truncate" title="{{ $dept->name }}">{{ $dept->name }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

{{-- MODAL PANDUAN KODE ASET (UNTUK USER) --}}
<div id="guide-modal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden backdrop-blur-xs">
    <div class="bg-surface-white rounded-xl border border-border-light max-w-lg w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-border-light">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h4 class="font-display text-base font-bold text-on-surface">Panduan Permohonan Kode Aset</h4>
                    <p class="text-xs text-on-surface-variant">Pencatatan inventaris baru (&ge; Rp 500.000)</p>
                </div>
            </div>
            <button type="button" onclick="closeGuideModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-md hover:bg-surface-container">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="space-y-3 text-xs text-on-surface-variant leading-relaxed">
            <div class="p-3 rounded-lg bg-stone-50 border border-stone-200">
                <p class="font-semibold text-on-surface mb-1">Syarat Pencatatan Aset Resmi:</p>
                <ul class="list-disc list-inside space-y-0.5 text-stone-700">
                    <li>Harga beli minimal <strong>Rp 500.000</strong> per unit</li>
                    <li>Memiliki bukti kuitansi/faktur pembelian yang sah</li>
                    <li>Masa manfaat &gt; 1 tahun (bukan barang habis pakai)</li>
                </ul>
            </div>

            <div>
                <p class="font-semibold text-on-surface mb-1">Format Permohonan ke Bagian Keuangan:</p>
                <div class="p-3 rounded-lg bg-surface-container/60 border border-outline-variant font-mono text-[11px] text-on-surface space-y-1">
                    <p id="template-text">Yth. Bagian Keuangan WBI,&#13;&#10;Kami dari Unit {{ $userDepartment ? $userDepartment->name : 'Operasional' }} mengajukan pencatatan aset baru:&#13;&#10;- Nama Barang: [Nama/Merk Barang]&#13;&#10;- Harga Beli: Rp [Nominal &ge; 500.000]&#13;&#10;- Tanggal Pembelian: [Tanggal]&#13;&#10;- Bukti Nota: (Terlampir)&#13;&#10;Mohon dibuatkan kode aset resmi. Terima kasih.</p>
                    <button type="button" onclick="copyTemplateText()" class="mt-2 inline-flex items-center gap-1.5 px-3 py-1 rounded bg-primary text-white text-[11px] font-sans font-semibold hover:bg-primary-light transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        Salin Format Pesan
                    </button>
                </div>
            </div>
        </div>

        <div class="pt-3 border-t border-border-light flex justify-end">
            <button type="button" onclick="closeGuideModal()" class="px-4 py-2 rounded-lg bg-surface-container text-xs font-semibold text-on-surface hover:bg-surface-container-high transition-colors">
                Tutup Panduan
            </button>
        </div>
    </div>
</div>

{{-- MODAL PANDUAN ALUR SOP (UNTUK ADMIN / EKSEKUTIF) --}}
<div id="sop-modal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4 hidden backdrop-blur-xs">
    <div class="bg-surface-white rounded-xl border border-border-light max-w-2xl w-full p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-border-light">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-teal-50 text-primary-light flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <h4 class="font-display text-base font-bold text-on-surface">Arsitektur Siklus Hidup Aset WBI</h4>
                    <p class="text-xs text-on-surface-variant">Standard Operating Procedure &amp; Business Rules</p>
                </div>
            </div>
            <button type="button" onclick="closeSopModal()" class="text-on-surface-variant hover:text-on-surface p-1 rounded-md hover:bg-surface-container">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
            <div class="p-3.5 rounded-lg border border-border-light bg-surface-container/30">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-mono font-bold text-primary-light text-[10px]">TAHAP 1</span>
                    <span class="font-mono text-[10px] text-on-surface-variant">Keuangan</span>
                </div>
                <h5 class="font-semibold text-on-surface mb-0.5">Registrasi &amp; Validasi Nilai</h5>
                <p class="text-on-surface-variant text-[11px] leading-relaxed">
                    Validasi harga perolehan &ge; Rp 500.000 dan penerbitan nomor kode unik sistem.
                </p>
            </div>

            <div class="p-3.5 rounded-lg border border-border-light bg-surface-container/30">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-mono font-bold text-slate-700 text-[10px]">TAHAP 2</span>
                    <span class="font-mono text-[10px] text-on-surface-variant">Gudang INV</span>
                </div>
                <h5 class="font-semibold text-on-surface mb-0.5">Penampungan Gudang</h5>
                <p class="text-on-surface-variant text-[11px] leading-relaxed">
                    Aset baru masuk pool Gudang Inventaris untuk pelabelan fisik barcode/QR sebelum didistribusikan.
                </p>
            </div>

            <div class="p-3.5 rounded-lg border border-border-light bg-surface-container/30">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-mono font-bold text-amber-700 text-[10px]">TAHAP 3</span>
                    <span class="font-mono text-[10px] text-on-surface-variant">Departemen</span>
                </div>
                <h5 class="font-semibold text-on-surface mb-0.5">Pengajuan &amp; Approval Digital</h5>
                <p class="text-on-surface-variant text-[11px] leading-relaxed">
                    Penerbitan formulir mutasi resmi dengan tombol persetujuan digital (Approval Button) oleh pihak Penyerah &amp; Penerima.
                </p>
            </div>

            <div class="p-3.5 rounded-lg border border-border-light bg-surface-container/30">
                <div class="flex items-center justify-between mb-1">
                    <span class="font-mono font-bold text-emerald-700 text-[10px]">TAHAP 4</span>
                    <span class="font-mono text-[10px] text-on-surface-variant">Inventaris</span>
                </div>
                <h5 class="font-semibold text-on-surface mb-0.5">Eksekusi &amp; Arsip Berita Acara</h5>
                <p class="text-on-surface-variant text-[11px] leading-relaxed">
                    Pemindahan kepemilikan aset secara definitif dan arsip PDF Berita Acara yang terkunci permanen.
                </p>
            </div>
        </div>

        <div class="pt-3 border-t border-border-light flex justify-end">
            <button type="button" onclick="closeSopModal()" class="px-4 py-2 rounded-lg bg-surface-container text-xs font-semibold text-on-surface hover:bg-surface-container-high transition-colors">
                Tutup SOP
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openGuideModal() {
        const modal = document.getElementById('guide-modal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeGuideModal() {
        const modal = document.getElementById('guide-modal');
        if (modal) modal.classList.add('hidden');
    }

    function openSopModal() {
        const modal = document.getElementById('sop-modal');
        if (modal) modal.classList.remove('hidden');
    }

    function closeSopModal() {
        const modal = document.getElementById('sop-modal');
        if (modal) modal.classList.add('hidden');
    }

    function copyTemplateText() {
        const textElement = document.getElementById('template-text');
        if (!textElement) return;

        const text = textElement.innerText || textElement.textContent;
        navigator.clipboard.writeText(text).then(() => {
            if (typeof window.showToast === 'function') {
                window.showToast('Format pesan berhasil disalin ke clipboard!', 'success');
            } else {
                alert('Format pesan berhasil disalin!');
            }
        }).catch(() => {
            alert('Silakan salin teks secara manual.');
        });
    }

    function calculateAssetCategory(val) {
        const resultEl = document.getElementById('quick-calc-result');
        if (!resultEl) return;

        const price = parseFloat(val);
        if (isNaN(price) || price <= 0) {
            resultEl.classList.add('hidden');
            return;
        }

        resultEl.classList.remove('hidden', 'bg-emerald-50', 'text-emerald-800', 'border-emerald-200', 'bg-amber-50', 'text-amber-800', 'border-amber-200');
        resultEl.classList.add('border');

        if (price >= 500000) {
            resultEl.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
            resultEl.innerHTML = `
                <div class="flex items-start gap-2">
                    <span class="font-bold text-sm text-emerald-700">[V]</span>
                    <div>
                        <p class="font-bold">Termasuk ASET RESMI WBI</p>
                        <p class="text-[11px] text-emerald-700 mt-0.5">Nilai &ge; Rp 500.000. Wajib dilaporkan ke Keuangan untuk penomoran kode aset.</p>
                    </div>
                </div>
            `;
        } else {
            resultEl.classList.add('bg-amber-50', 'text-amber-800', 'border-amber-200');
            resultEl.innerHTML = `
                <div class="flex items-start gap-2">
                    <span class="font-bold text-sm text-amber-700">[i]</span>
                    <div>
                        <p class="font-bold">BARANG HABIS PAKAI (Non-Asset)</p>
                        <p class="text-[11px] text-amber-700 mt-0.5">Nilai &lt; Rp 500.000. Langsung dicatat sebagai beban operasional tanpa kode aset.</p>
                    </div>
                </div>
            `;
        }
    }

    // Initialize 5 Charts
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof window.Chart === 'undefined') return;

        Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui, sans-serif';
        Chart.defaults.color = '#404846';

        // 1. Chart: Distribusi Status Aset (Doughnut)
        const ctxStatus = document.getElementById('chartAssetStatus');
        if (ctxStatus) {
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: @json($chartStatus['labels']),
                    datasets: [{
                        data: @json($chartStatus['data']),
                        backgroundColor: @json($chartStatus['colors']),
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: { size: 11, weight: '500' },
                                padding: 10
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }

        // 2. Chart: Valuasi Nilai Aset per Unit Kerja (Horizontal Bar)
        const ctxValuations = document.getElementById('chartDeptValuations');
        if (ctxValuations) {
            new Chart(ctxValuations, {
                type: 'bar',
                data: {
                    labels: @json($chartValuations['labels']),
                    datasets: [{
                        label: 'Valuasi Aset',
                        data: @json($chartValuations['data']),
                        backgroundColor: '#134137',
                        borderRadius: 4,
                        barPercentage: 0.65
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ' Rp ' + Number(context.raw).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { color: '#E5E7EB' },
                            ticks: {
                                font: { size: 10, family: 'JetBrains Mono, monospace' },
                                callback: function(value) {
                                    if (value >= 1000000000) return (value / 1000000000).toFixed(1) + ' M';
                                    if (value >= 1000000) return (value / 1000000).toFixed(0) + ' Jt';
                                    return value;
                                }
                            }
                        },
                        y: {
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: '500' } }
                        }
                    }
                }
            });
        }

        // 3. Chart: Tren Mutasi Aset Bulanan (Line / Area)
        const ctxTrends = document.getElementById('chartMonthlyTrends');
        if (ctxTrends) {
            new Chart(ctxTrends, {
                type: 'line',
                data: {
                    labels: @json($chartMonthlyTrends['labels']),
                    datasets: [{
                        label: 'Jumlah Mutasi',
                        data: @json($chartMonthlyTrends['data']),
                        borderColor: '#805600',
                        backgroundColor: 'rgba(255, 197, 105, 0.25)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                        pointBackgroundColor: '#805600',
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#E5E7EB' },
                            ticks: { stepSize: 1, font: { size: 10, family: 'JetBrains Mono, monospace' } }
                        }
                    }
                }
            });
        }

        // 4. Chart: Komposisi Kondisi Fisik Inventaris (Doughnut)
        const ctxConditions = document.getElementById('chartConditions');
        if (ctxConditions) {
            new Chart(ctxConditions, {
                type: 'doughnut',
                data: {
                    labels: @json($chartConditions['labels']),
                    datasets: [{
                        data: @json($chartConditions['data']),
                        backgroundColor: @json($chartConditions['colors']),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 10, font: { size: 10 }, padding: 8 }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        // 5. Chart: Top 5 Unit Distribusi Penerima (Bar)
        const ctxReceivers = document.getElementById('chartTopReceivers');
        if (ctxReceivers) {
            new Chart(ctxReceivers, {
                type: 'bar',
                data: {
                    labels: @json($chartTopReceivers['labels']),
                    datasets: [{
                        label: 'Mutasi Diterima',
                        data: @json($chartTopReceivers['data']),
                        backgroundColor: '#537E83',
                        borderRadius: 4,
                        barPercentage: 0.55
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#E5E7EB' },
                            ticks: { stepSize: 1, font: { size: 10, family: 'JetBrains Mono, monospace' } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
