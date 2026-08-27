@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 w-full min-w-0">

    @if(auth()->user()->hasRole('user'))
    {{-- ========================================================================= --}}
    {{-- PORTAL INVENTARIS UNIT KERJA (CLEAN, LEGA & READABLE) --}}
    {{-- ========================================================================= --}}

    {{-- ALERT PENTING: HANYA MUNCUL JIKA ADA MUTASI MASUK YANG BUTUH PERSETUJUAN --}}
    @if(isset($waitingApprovalMutationsCount) && $waitingApprovalMutationsCount > 0)
    <div class="p-5 rounded-2xl bg-amber-50 border border-amber-300 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shrink-0 shadow-xs">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-amber-950">
                    Ada {{ $waitingApprovalMutationsCount }} Formulir Mutasi Menunggu Persetujuan Anda
                </h3>
                <p class="text-sm text-amber-900 mt-0.5">
                    Ada barang baru/pindahan yang dialokasikan ke unit Anda. Silakan verifikasi fisik barang dan setujui formulirnya.
                </p>
            </div>
        </div>
        <a href="{{ route('mutations.index', ['scope' => 'waiting_my_approval']) }}"
           class="px-5 py-2.5 rounded-xl bg-amber-700 hover:bg-amber-800 text-white text-sm font-bold shadow-xs whitespace-nowrap text-center">
            Tinjau &amp; Setujui Sekarang &rarr;
        </a>
    </div>
    @endif

    {{-- Clean Header --}}
    <div class="bg-surface-white rounded-2xl border border-border-light p-6 sm:p-8 shadow-xs flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">
        <div class="space-y-1.5">
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-xs font-bold font-mono">
                    {{ $userDepartment ? $userDepartment->code : 'UNIT' }}
                </span>
                <span class="text-sm font-semibold text-slate-500">
                    {{ $userDepartment ? $userDepartment->name : 'Operasional' }}
                </span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900">
                Halo, {{ $user->name }}
            </h2>
            <p class="text-sm text-slate-600 max-w-xl">
                Kelola daftar inventaris resmi di unit Anda atau ajukan pemindahan aset ke unit lain.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('mutations.create') }}"
               class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-light text-white text-sm font-bold transition-all shadow-xs inline-flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajukan Mutasi Aset
            </a>
            <button type="button" onclick="openSystemGuideModal()"
                    class="px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm font-semibold transition-all shadow-2xs inline-flex items-center gap-2 cursor-pointer">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Panduan Alur
            </button>
        </div>
    </div>

    {{-- 2 Ringkasan Utama (Lega & Jelas Dibaca) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-surface-white rounded-2xl border border-border-light p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-sm font-semibold text-slate-500">Jumlah Barang di Unit Anda</p>
                <p class="text-3xl sm:text-4xl font-bold font-mono text-slate-900">
                    {{ $deptTotalAssets }} <span class="text-base font-sans font-normal text-slate-500">Unit</span>
                </p>
                <p class="text-sm text-slate-600 pt-1">
                    <span class="text-emerald-700 font-semibold">{{ $deptActiveAssets }} Kondisi Baik</span> &bull; 
                    <span class="text-amber-700 font-semibold">{{ $deptRepairAssets }} Dalam Perbaikan</span>
                </p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>

        <div class="bg-surface-white rounded-2xl border border-border-light p-6 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-sm font-semibold text-slate-500">Total Nilai Inventaris Unit</p>
                <p class="text-2xl sm:text-3xl font-bold font-mono text-slate-900">
                    Rp {{ number_format($deptValuation, 0, ',', '.') }}
                </p>
                <p class="text-sm text-slate-500 pt-1">
                    Akumulasi harga perolehan resmi tercatat
                </p>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-primary-surface text-primary flex items-center justify-center shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Clean Table of Assets --}}
    <div class="bg-surface-white rounded-2xl border border-border-light overflow-hidden shadow-xs">
        <div class="px-6 py-5 border-b border-border-light flex items-center justify-between">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Daftar Barang di Unit {{ $userDepartment ? $userDepartment->name : 'Anda' }}</h3>
                <p class="text-sm text-slate-500">Daftar inventaris yang saat ini aktif di bawah unit kerja Anda.</p>
            </div>
            <a href="{{ route('assets.index', ['scope' => 'my_dept']) }}" class="text-sm font-bold text-primary hover:underline">
                Buka Seluruh Aset &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[650px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="px-6 py-4">Kode Aset</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Nilai Perolehan</th>
                        <th class="px-6 py-4">Tanggal Terima</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($deptAssets as $asset)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-primary whitespace-nowrap">
                            {{ $asset->asset_code }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900 text-sm">{{ $asset->name }}</p>
                        </td>
                        <td class="px-6 py-4 font-mono text-slate-800 font-semibold whitespace-nowrap">
                            Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 whitespace-nowrap text-sm">
                            {{ $asset->purchase_date ? $asset->purchase_date->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @include('components.status-badge', ['status' => $asset->status])
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('assets.show', $asset) }}"
                               class="px-3.5 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-xs font-bold text-slate-700 transition-colors shadow-2xs">
                                Detail Aset
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <p class="font-bold text-slate-800 text-base mb-1">Belum Ada Inventaris Terdaftar</p>
                            <p class="text-sm">Jika unit Anda baru membeli barang (&ge; Rp 500rb), hubungi Bagian Keuangan agar didaftarkan ke sistem.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- 2. PORTAL BAGIAN KEUANGAN (SESUAI FLOW KODE ASET - TANPA GRAFIK) --}}
    {{-- ========================================================================= --}}
    @elseif(auth()->user()->hasRole('finance'))

    {{-- Header Banner Keuangan --}}
    <div class="bg-gradient-to-r from-emerald-900 to-teal-800 text-white rounded-2xl p-6 sm:p-7 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold uppercase tracking-wider bg-white/20 text-white">
                    Peran: Bagian Keuangan
                </span>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-white">
                    Pencatatan &amp; Penerbitan Kode Aset
                </h2>
                <p class="text-sm text-emerald-100 max-w-2xl leading-relaxed">
                    Menerima permohonan kode dari unit kerja, menganalisis nilai perolehan (&ge; Rp 500.000), dan mendaftarkan aset baru ke Gudang Inventaris.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('assets.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-emerald-950 text-sm font-bold hover:bg-emerald-50 transition-all shadow-sm">
                    <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    + Registrasi Aset Baru
                </a>
                <button type="button" onclick="openSystemGuideModal('kode-aset')"
                        class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-xs sm:text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Panduan Alur Kode Aset
                </button>
            </div>
        </div>
    </div>

    {{-- Ringkasan Metrik Angka Sederhana (3 Kartu Jelas) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Aset Terdaftar</p>
                <h3 class="text-3xl font-bold text-slate-900 font-mono">{{ number_format($stats['total_assets']) }} <span class="text-sm font-normal text-slate-500">Unit</span></h3>
                <p class="text-xs text-slate-600 font-mono">Valuasi: <strong class="text-slate-900">Rp {{ number_format($stats['total_valuation'], 0, ',', '.') }}</strong></p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Aset Baru di Gudang</p>
                <h3 class="text-3xl font-bold text-slate-900 font-mono">{{ number_format($stats['in_storage']) }} <span class="text-sm font-normal text-slate-500">Unit</span></h3>
                <p class="text-xs text-slate-600">Menunggu penyaluran oleh Inventaris</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Standar Nilai Kapitalisasi</p>
                <h3 class="text-2xl font-bold text-emerald-800 font-mono">&ge; Rp 500.000</h3>
                <p class="text-xs text-slate-600">&lt; Rp 500rb masuk beban operasional</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Panduan Alur Kerja Sesuai SOP (3 Langkah Jelas) --}}
    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <h3 class="font-display text-base font-bold text-slate-900">Alur Standar Pencatatan Kode Aset Politeknik WBI</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="p-4 rounded-xl bg-white border border-slate-200 space-y-1">
                <div class="flex items-center gap-2 font-bold text-slate-900">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">1</span>
                    <span>Permohonan User</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    User/Unit kerja membeli barang baru dan mengajukan permintaan kode inventaris kepada Bagian Keuangan lengkap dengan nota belanja.
                </p>
            </div>

            <div class="p-4 rounded-xl bg-white border border-slate-200 space-y-1">
                <div class="flex items-center gap-2 font-bold text-slate-900">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">2</span>
                    <span>Analisis Nilai Aset</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Keuangan memeriksa harga beli:
                    <br>&bull; <strong>&lt; Rp 500.000:</strong> Tidak diinput (Beban operasional / Stop).
                    <br>&bull; <strong>&ge; Rp 500.000:</strong> Klik tombol <strong>Registrasi Aset Baru</strong>.
                </p>
            </div>

            <div class="p-4 rounded-xl bg-white border border-slate-200 space-y-1">
                <div class="flex items-center gap-2 font-bold text-slate-900">
                    <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-800 text-xs flex items-center justify-center font-bold">3</span>
                    <span>Masuk Gudang &amp; Selesai</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Aset resmi diterbitkan dan otomatis masuk ke <strong>Gudang Inventaris [GDG-INV]</strong>. Tugas Keuangan selesai, selanjutnya Bagian Inventaris yang menyalurkan aset ke unit kerja.
                </p>
            </div>
        </div>
    </div>

    {{-- Tabel Aset Terbaru yang Dicatat --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">Daftar Aset Terbaru Didaftarkan</h3>
                <p class="text-xs text-slate-500">Aset-aset resmi yang baru saja dicatat oleh Keuangan</p>
            </div>
            <a href="{{ route('assets.index') }}" class="text-xs font-bold text-primary hover:text-primary-light transition-colors">
                Lihat Seluruh Aset &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[650px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="px-6 py-4">Kode Aset</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Harga Perolehan</th>
                        <th class="px-6 py-4">Tanggal Input</th>
                        <th class="px-6 py-4">Status &amp; Lokasi</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentAssets as $asset)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-primary whitespace-nowrap">
                            {{ $asset->asset_code }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-900 text-sm">{{ $asset->name }}</p>
                            <p class="text-xs text-slate-500">Kategori: {{ $asset->category->name ?? 'Umum' }}</p>
                        </td>
                        <td class="px-6 py-4 font-mono font-bold text-slate-900 whitespace-nowrap">
                            Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-xs whitespace-nowrap">
                            {{ $asset->purchase_date ? $asset->purchase_date->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="space-y-1">
                                @include('components.status-badge', ['status' => $asset->status])
                                <p class="text-[11px] text-slate-500">{{ $asset->currentDepartment ? $asset->currentDepartment->name : '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('assets.show', $asset) }}"
                               class="px-3.5 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-xs font-bold text-slate-700 transition-colors shadow-2xs">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <p class="font-bold text-slate-800 text-base mb-1">Belum Ada Aset Terdaftar</p>
                            <p class="text-sm">Klik tombol "Registrasi Aset Baru" di atas untuk menambahkan aset resmi baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- 3. PORTAL BAGIAN INVENTARIS (PENYALURAN GUDANG & MUTASI - TANPA GRAFIK) --}}
    {{-- ========================================================================= --}}
    @elseif(auth()->user()->hasRole('inventory'))
    
    {{-- Header Banner Inventaris --}}
    <div class="bg-gradient-to-r from-teal-900 to-slate-900 text-white rounded-2xl p-6 sm:p-7 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
            <div class="space-y-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono font-bold uppercase tracking-wider bg-white/20 text-white">
                    Peran: Bagian Inventaris
                </span>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-white">
                    Pusat Distribusi Gudang &amp; Eksekusi Mutasi
                </h2>
                <p class="text-sm text-teal-100 max-w-2xl leading-relaxed">
                    Menyalurkan aset dari Gudang ke departemen penanggung jawab, mengeksekusi mutasi yang telah disetujui ganda, dan mengarsipkan Berita Acara (BAST).
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <a href="{{ route('mutations.create', ['from_department_id' => \App\Models\Department::where('code', 'GDG-INV')->value('id')]) }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-teal-950 text-sm font-bold hover:bg-teal-50 transition-all shadow-sm">
                    <svg class="w-5 h-5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Salurkan Aset dari Gudang
                </a>
                <button type="button" onclick="openSystemGuideModal('mutasi-aset')"
                        class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-xs sm:text-sm font-semibold text-white transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Panduan Alur Mutasi
                </button>
            </div>
        </div>
    </div>

    {{-- Ringkasan Metrik Angka Sederhana (3 Kartu Jelas) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Aset di Gudang Inventaris</p>
                <h3 class="text-3xl font-bold text-slate-900 font-mono">{{ number_format($stats['in_storage']) }} <span class="text-sm font-normal text-slate-500">Unit</span></h3>
                <p class="text-xs text-slate-600">Siap disalurkan ke unit penanggung jawab</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mutasi Siap Eksekusi</p>
                <h3 class="text-3xl font-bold text-amber-700 font-mono">{{ number_format($stats['ready_execution']) }} <span class="text-sm font-normal text-slate-500">Form</span></h3>
                <p class="text-xs text-slate-600">Dual-approval selesai &bull; Menunggu eksekusi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs flex items-center justify-between">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Arsip Berita Acara</p>
                <h3 class="text-3xl font-bold text-emerald-800 font-mono">{{ number_format($stats['archived_mutations']) }} <span class="text-sm font-normal text-slate-500">BAST</span></h3>
                <p class="text-xs text-slate-600">Mutasi resmi selesai dan tersimpan</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
    </div>

    {{-- Panduan Alur Kerja Inventaris (3 Langkah Jelas) --}}
    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
            </svg>
            <h3 class="font-display text-base font-bold text-slate-900">Alur Standar Pengelolaan &amp; Mutasi Aset WBI</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div class="p-4 rounded-xl bg-white border border-slate-200 space-y-1">
                <div class="flex items-center gap-2 font-bold text-slate-900">
                    <span class="w-6 h-6 rounded-full bg-teal-100 text-teal-800 text-xs flex items-center justify-center font-bold">1</span>
                    <span>Salurkan dari Gudang</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Aset baru di Gudang Inventaris dimutasi ke departemen penanggung jawab (atau ke Inventaris sendiri jika dikelola langsung).
                </p>
            </div>

            <div class="p-4 rounded-xl bg-white border border-slate-200 space-y-1">
                <div class="flex items-center gap-2 font-bold text-slate-900">
                    <span class="w-6 h-6 rounded-full bg-teal-100 text-teal-800 text-xs flex items-center justify-center font-bold">2</span>
                    <span>Approval Penerima</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Departemen penerima memeriksa kondisi fisik barang dan melakukan persetujuan (*Approval*) di sistem.
                </p>
            </div>

            <div class="p-4 rounded-xl bg-white border border-slate-200 space-y-1">
                <div class="flex items-center gap-2 font-bold text-slate-900">
                    <span class="w-6 h-6 rounded-full bg-teal-100 text-teal-800 text-xs flex items-center justify-center font-bold">3</span>
                    <span>Eksekusi &amp; Arsip BAST</span>
                </div>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Inventaris melakukan serah terima fisik, klik *Eksekusi Mutasi*, dan mengunduh/mencetak Berita Acara sah (BAST).
                </p>
            </div>
        </div>
    </div>

    {{-- Tabel Mutasi Berjalan / Siap Eksekusi --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="font-display text-base font-bold text-slate-900">Formulir Mutasi Siap Tindak Lanjut</h3>
                <p class="text-xs text-slate-500">Daftar perpindahan aset yang memerlukan tindakan atau eksekusi</p>
            </div>
            <a href="{{ route('mutations.index') }}" class="text-xs font-bold text-primary hover:text-primary-light transition-colors">
                Lihat Seluruh Mutasi &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm min-w-[650px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-600 uppercase tracking-wider">
                        <th class="px-6 py-4">Nomor Formulir</th>
                        <th class="px-6 py-4">Alur Perpindahan</th>
                        <th class="px-6 py-4">Pengirim</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentMutations as $mutation)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono font-bold text-primary whitespace-nowrap">
                            {{ $mutation->form_number }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                <span>{{ $mutation->fromDepartment ? $mutation->fromDepartment->name : '-' }}</span>
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                                <span class="text-primary">{{ $mutation->toDepartment ? $mutation->toDepartment->name : '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-xs">
                            {{ $mutation->sender ? $mutation->sender->name : 'Sistem' }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-xs whitespace-nowrap">
                            {{ $mutation->created_at ? $mutation->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @include('components.status-badge', ['status' => $mutation->status])
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('mutations.show', $mutation) }}"
                               class="px-3.5 py-1.5 rounded-lg border border-slate-300 bg-white hover:bg-slate-100 text-xs font-bold text-slate-700 transition-colors shadow-2xs">
                                Buka Form
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <p class="font-bold text-slate-800 text-base mb-1">Belum Ada Mutasi Berjalan</p>
                            <p class="text-sm">Klik "Salurkan Aset dari Gudang" untuk memulai mutasi baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    {{-- ========================================================================= --}}
    {{-- 4. DASHBOARD KHUSUS ADMINISTRATOR (MONITORING & GRAFIK MAKRO) --}}
    {{-- ========================================================================= --}}
    @else
    {{-- HUB ADMIN / SUPER ADMIN --}}
    <div class="bg-surface-white rounded-2xl border border-border-light p-5 sm:p-6 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2 mb-1">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase tracking-wider bg-primary text-white">
                        WBI Inventaris &bull; Administrator
                    </span>
                </div>
                <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface">
                    Ringkasan Eksekutif &amp; Monitoring Sistem
                </h2>
                <p class="text-xs text-on-surface-variant">
                    Monitoring menyeluruh pergerakan aset, audit trail transaksi, serta pengelolaan master data kampus.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" onclick="openSystemGuideModal()"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-border-light bg-surface-white text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Panduan Alur Sistem
                </button>

                <a href="{{ route('assets.create') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-primary text-white text-xs font-semibold hover:bg-primary-light transition-all shadow-xs">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Registrasi Aset
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
            'badge' => 'Terdaftar',
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

    {{-- 3. Visual Analytics Section --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between pb-0.5">
            <div class="flex items-center gap-2">
                <div class="w-2.5 h-2.5 rounded-full bg-primary"></div>
                <h3 class="font-display text-base font-bold text-on-surface">Analisis &amp; Visualisasi Inventaris</h3>
            </div>
            <span class="text-xs font-mono text-on-surface-variant">Update Realtime</span>
        </div>

        {{-- Baris 1: 2 Grafik Utama (Hub Distribusi & Kelayakan + Valuasi Departemen) --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            {{-- Card 1: Distribusi & Kondisi Aset --}}

            <div class="lg:col-span-6 bg-surface-white rounded-xl border border-border-light p-5 shadow-xs flex flex-col justify-between">
                <div>
                    {{-- Header with Segmented Pill Tabs & Chart Type Toggle --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                        <div>
                            <h4 id="assetDistTitle" class="font-display text-sm font-bold text-on-surface">Distribusi Status Aset</h4>
                            <p id="assetDistDesc" class="text-xs text-on-surface-variant mt-0.5">Sebaran status operasional, gudang inventaris, dan perbaikan.</p>
                        </div>

                        {{-- Controls: Segmented Tabs & Type Switcher --}}
                        <div class="flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
                            <div class="inline-flex items-center p-0.5 rounded-lg bg-surface-container text-xs">
                                <button type="button" id="tabAssetStatus" onclick="switchAssetDistView('status')"
                                        class="px-2.5 py-1 rounded-md text-xs font-semibold bg-surface-white text-primary shadow-2xs transition-all">
                                    Status Aset
                                </button>
                                <button type="button" id="tabAssetCondition" onclick="switchAssetDistView('condition')"
                                        class="px-2.5 py-1 rounded-md text-xs font-medium text-on-surface-variant hover:text-on-surface transition-all">
                                    Kondisi Fisik
                                </button>
                            </div>

                            <div class="inline-flex items-center p-0.5 rounded-lg bg-surface-container">
                                <button type="button" id="btnAssetDistDonut" onclick="toggleAssetDistType('doughnut')"
                                        class="p-1 rounded-md bg-surface-white text-primary shadow-2xs transition-all"
                                        title="Tampilan Donut">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                                    </svg>
                                </button>
                                <button type="button" id="btnAssetDistBar" onclick="toggleAssetDistType('bar')"
                                        class="p-1 rounded-md text-on-surface-variant hover:text-on-surface transition-all"
                                        title="Tampilan Batang">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Chart Canvas Container --}}
                    <div class="relative h-56 flex items-center justify-center my-2">
                        <canvas id="chartAssetDist"></canvas>
                    </div>
                </div>

                {{-- Clean Dynamic Status / Condition Legend Chips (No Duplication) --}}
                <div>
                    {{-- Status Legend --}}
                    <div id="assetDistStatusFooter" class="pt-3 mt-2 border-t border-border-light grid grid-cols-2 sm:grid-cols-4 gap-2 text-xs font-mono">
                        <div class="flex items-center justify-between p-2 rounded-lg bg-surface-container/40">
                            <span class="flex items-center gap-1.5 text-on-surface-variant text-[11px]"><span class="w-2 h-2 rounded-full bg-[#2D6A4F] shrink-0"></span> Aktif</span>
                            <span class="font-bold text-on-surface">{{ $stats['active_assets'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-surface-container/40">
                            <span class="flex items-center gap-1.5 text-on-surface-variant text-[11px]"><span class="w-2 h-2 rounded-full bg-[#537E83] shrink-0"></span> Gudang</span>
                            <span class="font-bold text-on-surface">{{ $stats['in_storage'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-surface-container/40">
                            <span class="flex items-center gap-1.5 text-on-surface-variant text-[11px]"><span class="w-2 h-2 rounded-full bg-[#D97706] shrink-0"></span> Perbaikan</span>
                            <span class="font-bold text-on-surface">{{ $stats['under_repair'] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-surface-container/40">
                            <span class="flex items-center gap-1.5 text-on-surface-variant text-[11px]"><span class="w-2 h-2 rounded-full bg-[#991B1B] shrink-0"></span> Dihapus</span>
                            <span class="font-bold text-on-surface">{{ $stats['disposed'] }}</span>
                        </div>
                    </div>

                    {{-- Condition Legend --}}
                    <div id="assetDistConditionFooter" class="pt-3 mt-2 border-t border-border-light grid grid-cols-3 gap-2 text-xs font-mono hidden">
                        <div class="flex items-center justify-between p-2 rounded-lg bg-surface-container/40">
                            <span class="flex items-center gap-1.5 text-on-surface-variant text-[11px]"><span class="w-2 h-2 rounded-full bg-[#2D6A4F] shrink-0"></span> Kondisi Baik</span>
                            <span class="font-bold text-emerald-700">{{ $chartConditions['data'][0] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-surface-container/40">
                            <span class="flex items-center gap-1.5 text-on-surface-variant text-[11px]"><span class="w-2 h-2 rounded-full bg-[#D97706] shrink-0"></span> Rusak Ringan</span>
                            <span class="font-bold text-amber-700">{{ $chartConditions['data'][1] }}</span>
                        </div>
                        <div class="flex items-center justify-between p-2 rounded-lg bg-surface-container/40">
                            <span class="flex items-center gap-1.5 text-on-surface-variant text-[11px]"><span class="w-2 h-2 rounded-full bg-[#991B1B] shrink-0"></span> Rusak Berat</span>
                            <span class="font-bold text-rose-700">{{ $chartConditions['data'][2] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card 2: Valuasi Nilai Aset per Unit Kerja --}}
            <div class="lg:col-span-6 bg-surface-white rounded-xl border border-border-light p-5 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <div>
                            <h4 class="font-display text-sm font-bold text-on-surface">Valuasi Aset per Unit Kerja</h4>
                            <p class="text-xs text-on-surface-variant mt-0.5">Akumulasi harga perolehan barang per unit.</p>
                        </div>
                        <span class="text-[11px] font-mono px-2.5 py-1 rounded-md bg-surface-container text-primary font-bold whitespace-nowrap">
                            Rupiah (IDR)
                        </span>
                    </div>

                    <div class="relative h-56 my-2">
                        <canvas id="chartDeptValuations"></canvas>
                    </div>
                </div>
                <div class="pt-3 mt-2 border-t border-border-light flex items-center justify-between text-xs text-on-surface-variant font-mono">
                    <span>Total Valuasi Terdata:</span>
                    <strong class="text-on-surface font-bold text-sm">Rp {{ number_format($stats['total_valuation'], 0, ',', '.') }}</strong>
                </div>
            </div>
        </div>

        {{-- Baris 2: Aktivitas & Siklus Mutasi Barang --}}
        <div class="bg-surface-white rounded-xl border border-border-light p-5 shadow-xs flex flex-col justify-between">
            <div>
                {{-- Header with Segmented Pill Tabs & Chart Type Toggle --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                    <div>
                        <h4 id="mutationHubTitle" class="font-display text-sm font-bold text-on-surface">Tren Mutasi Bulanan</h4>
                        <p id="mutationHubDesc" class="text-xs text-on-surface-variant mt-0.5">Volume permohonan perpindahan antar-unit dalam 6 bulan terakhir.</p>
                    </div>

                    {{-- Controls: Segmented Tabs & Type Switcher --}}
                    <div class="flex items-center gap-1.5 shrink-0 self-start sm:self-auto">
                        <div class="inline-flex items-center p-0.5 rounded-lg bg-surface-container text-xs">
                            <button type="button" id="tabMutationTrend" onclick="switchMutationView('trend')"
                                    class="px-2.5 py-1 rounded-md text-xs font-semibold bg-surface-white text-secondary shadow-2xs transition-all">
                                Tren Bulanan
                            </button>
                            <button type="button" id="tabMutationReceivers" onclick="switchMutationView('receivers')"
                                    class="px-2.5 py-1 rounded-md text-xs font-medium text-on-surface-variant hover:text-on-surface transition-all">
                                Unit Penerima
                            </button>
                        </div>

                        <div class="inline-flex items-center p-0.5 rounded-lg bg-surface-container">
                            <button type="button" id="btnMutationLine" onclick="toggleMutationType('line')"
                                    class="p-1 rounded-md bg-surface-white text-secondary shadow-2xs transition-all"
                                    title="Tampilan Garis">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/>
                                </svg>
                            </button>
                            <button type="button" id="btnMutationBar" onclick="toggleMutationType('bar')"
                                    class="p-1 rounded-md text-on-surface-variant hover:text-on-surface transition-all"
                                    title="Tampilan Batang">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Chart Canvas Container --}}
                <div class="relative h-52 my-2">
                    <canvas id="chartMutationHub"></canvas>
                </div>
            </div>

            {{-- Dynamic Footer Legend / Breakdown --}}
            <div>
                {{-- Trend Footer --}}
                <div id="mutationTrendFooter" class="pt-2.5 mt-2 border-t border-border-light text-xs text-on-surface-variant flex items-center justify-between font-mono">
                    <span>Total Formulir Seluruh Siklus:</span>
                    <span class="font-bold text-primary">{{ $stats['pending_mutations'] + $stats['archived_mutations'] }} Form</span>
                </div>

                {{-- Receivers Footer --}}
                <div id="mutationReceiverFooter" class="pt-2.5 mt-2 border-t border-border-light text-xs text-on-surface-variant flex items-center justify-between font-mono hidden">
                    <span>Total Arsip Berita Acara Sah:</span>
                    <span class="font-bold text-emerald-700">{{ $stats['archived_mutations'] }} BAST</span>
                </div>
            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    function openGuideModal() {

        if (typeof openSystemGuideModal === 'function') {
            openSystemGuideModal('kode-aset');
        }
    }

    function openSopModal() {
        if (typeof openSystemGuideModal === 'function') {
            openSystemGuideModal('mutasi-aset');
        }
    }

    function copyTemplateText() {
        if (typeof copySystemTemplateText === 'function') {
            copySystemTemplateText();
        }
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
                    <svg class="w-4 h-4 text-emerald-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <div>
                        <p class="font-bold">Termasuk Aset Resmi WBI</p>
                        <p class="text-[11px] text-emerald-700 mt-0.5">Nilai &ge; Rp 500.000. Wajib dilaporkan ke Keuangan untuk penomoran kode aset.</p>
                    </div>
                </div>
            `;
        } else {
            resultEl.classList.add('bg-amber-50', 'text-amber-800', 'border-amber-200');
            resultEl.innerHTML = `
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold">Barang Habis Pakai (Non-Asset)</p>
                        <p class="text-[11px] text-amber-700 mt-0.5">Nilai &lt; Rp 500.000. Langsung dicatat sebagai beban operasional tanpa kode aset.</p>
                    </div>
                </div>
            `;
        }
    }

    // Global Data Stores from Backend
    const assetDistData = {
        status: {
            title: 'Distribusi Status Aset',
            desc: 'Sebaran status operasional, gudang inventaris, dan perbaikan.',
            labels: @json($chartStatus['labels']),
            data: @json($chartStatus['data']),
            colors: @json($chartStatus['colors'])
        },
        condition: {
            title: 'Kondisi Fisik Barang',
            desc: 'Kualitas fisik barang yang dilaporkan unit kerja.',
            labels: @json($chartConditions['labels']),
            data: @json($chartConditions['data']),
            colors: @json($chartConditions['colors'])
        }
    };

    const mutationHubData = {
        trend: {
            title: 'Tren Mutasi Bulanan',
            desc: 'Volume permohonan perpindahan antar-unit dalam 6 bulan terakhir.',
            labels: @json($chartMonthlyTrends['labels']),
            data: @json($chartMonthlyTrends['data'])
        },
        receivers: {
            title: 'Distribusi Unit Penerima',
            desc: 'Departemen dengan frekuensi penerimaan mutasi barang tertinggi.',
            labels: @json($chartTopReceivers['labels']),
            data: @json($chartTopReceivers['data'])
        }
    };

    let chartAssetDistInstance = null;
    let chartDeptValuationsInstance = null;
    let chartMutationHubInstance = null;

    let currentAssetDistView = 'status'; // 'status' | 'condition'
    let currentAssetDistType = 'doughnut'; // 'doughnut' | 'bar'

    let currentMutationView = 'trend'; // 'trend' | 'receivers'
    let currentMutationType = 'line'; // 'line' | 'bar'

    // 1. Render Asset Distribution Chart
    function renderAssetDistChart() {
        const canvas = document.getElementById('chartAssetDist');
        if (!canvas || typeof window.Chart === 'undefined') return;

        if (chartAssetDistInstance) {
            chartAssetDistInstance.destroy();
        }

        const dataObj = assetDistData[currentAssetDistView];
        const ctx = canvas.getContext('2d');

        if (currentAssetDistType === 'doughnut') {
            chartAssetDistInstance = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: dataObj.labels,
                    datasets: [{
                        data: dataObj.data,
                        backgroundColor: dataObj.colors,
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 350 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const val = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total > 0 ? Math.round((val / total) * 100) : 0;
                                    return ` ${context.label}: ${val} Unit (${pct}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        } else {
            // Bar Chart version
            chartAssetDistInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dataObj.labels,
                    datasets: [{
                        label: 'Jumlah Unit',
                        data: dataObj.data,
                        backgroundColor: dataObj.colors,
                        borderRadius: 4,
                        barPercentage: 0.55
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 350 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.raw} Unit`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F1F3F5' },
                            ticks: {
                                precision: 0,
                                stepSize: 1,
                                font: { size: 10, family: 'JetBrains Mono, monospace' }
                            }
                        }
                    }
                }
            });
        }
    }

    // Switch View for Asset Dist Chart (Tab Pills)
    window.switchAssetDistView = function(view) {
        currentAssetDistView = view;
        const info = assetDistData[view];

        // Update Title & Description
        const titleEl = document.getElementById('assetDistTitle');
        const descEl = document.getElementById('assetDistDesc');
        if (titleEl) titleEl.innerText = info.title;
        if (descEl) descEl.innerText = info.desc;

        // Toggle Tab Pill active state
        const tabStatus = document.getElementById('tabAssetStatus');
        const tabCondition = document.getElementById('tabAssetCondition');
        if (view === 'status') {
            if (tabStatus) tabStatus.className = 'px-2.5 py-1 rounded-md text-xs font-semibold bg-surface-white text-primary shadow-2xs transition-all';
            if (tabCondition) tabCondition.className = 'px-2.5 py-1 rounded-md text-xs font-medium text-on-surface-variant hover:text-on-surface transition-all';
        } else {
            if (tabCondition) tabCondition.className = 'px-2.5 py-1 rounded-md text-xs font-semibold bg-surface-white text-primary shadow-2xs transition-all';
            if (tabStatus) tabStatus.className = 'px-2.5 py-1 rounded-md text-xs font-medium text-on-surface-variant hover:text-on-surface transition-all';
        }

        // Toggle Footers
        const statusFooter = document.getElementById('assetDistStatusFooter');
        const conditionFooter = document.getElementById('assetDistConditionFooter');
        if (view === 'status') {
            if (statusFooter) statusFooter.classList.remove('hidden');
            if (conditionFooter) conditionFooter.classList.add('hidden');
        } else {
            if (statusFooter) statusFooter.classList.add('hidden');
            if (conditionFooter) conditionFooter.classList.remove('hidden');
        }

        renderAssetDistChart();
    };

    // Toggle Chart Type (Donut vs Bar)
    window.toggleAssetDistType = function(type) {
        currentAssetDistType = type;

        const btnDonut = document.getElementById('btnAssetDistDonut');
        const btnBar = document.getElementById('btnAssetDistBar');

        if (type === 'doughnut') {
            if (btnDonut) btnDonut.className = 'p-1 rounded-md bg-surface-white text-primary shadow-2xs transition-all';
            if (btnBar) btnBar.className = 'p-1 rounded-md text-on-surface-variant hover:text-on-surface transition-all';
        } else {
            if (btnBar) btnBar.className = 'p-1 rounded-md bg-surface-white text-primary shadow-2xs transition-all';
            if (btnDonut) btnDonut.className = 'p-1 rounded-md text-on-surface-variant hover:text-on-surface transition-all';
        }

        renderAssetDistChart();
    };

    // 2. Render Valuations Horizontal Bar Chart
    function renderDeptValuationsChart() {
        const ctxValuations = document.getElementById('chartDeptValuations');
        if (!ctxValuations || typeof window.Chart === 'undefined') return;

        if (chartDeptValuationsInstance) {
            chartDeptValuationsInstance.destroy();
        }

        chartDeptValuationsInstance = new Chart(ctxValuations, {
            type: 'bar',
            data: {
                labels: @json($chartValuations['labels']),
                datasets: [{
                    label: 'Valuasi Aset',
                    data: @json($chartValuations['data']),
                    backgroundColor: '#134137',
                    borderRadius: 4,
                    barPercentage: 0.6
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
                            title: function(items) {
                                return items[0]?.label || '';
                            },
                            label: function(context) {
                                return ' Valuasi: Rp ' + Number(context.raw || 0).toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: '#F1F3F5' },
                        ticks: {
                            font: { size: 10, family: 'JetBrains Mono, monospace' },
                            maxTicksLimit: 5,
                            callback: function(value) {
                                if (value === 0) return '0';
                                if (value >= 1000000000) return (value / 1000000000).toLocaleString('id-ID') + ' M';
                                if (value >= 1000000) return (value / 1000000).toLocaleString('id-ID') + ' Jt';
                                if (value >= 1000) return (value / 1000).toLocaleString('id-ID') + ' Rb';
                                return value.toLocaleString('id-ID');
                            }
                        }
                    },
                    y: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11, weight: '500' },
                            callback: function(value) {
                                const label = this.getLabelForValue(value) || '';
                                return label.length > 18 ? label.substring(0, 16) + '...' : label;
                            }
                        }
                    }
                }
            }
        });
    }

    // 3. Render Mutation Hub Chart
    function renderMutationHubChart() {
        const canvas = document.getElementById('chartMutationHub');
        if (!canvas || typeof window.Chart === 'undefined') return;

        if (chartMutationHubInstance) {
            chartMutationHubInstance.destroy();
        }

        const dataObj = mutationHubData[currentMutationView];
        const ctx = canvas.getContext('2d');
        const isTrend = (currentMutationView === 'trend');

        if (currentMutationType === 'line') {
            const strokeColor = isTrend ? '#805600' : '#134137';
            const fillColor = isTrend ? 'rgba(255, 197, 105, 0.2)' : 'rgba(19, 65, 55, 0.12)';

            chartMutationHubInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dataObj.labels,
                    datasets: [{
                        label: isTrend ? 'Jumlah Mutasi' : 'Mutasi Diterima',
                        data: dataObj.data,
                        borderColor: strokeColor,
                        backgroundColor: fillColor,
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        pointBackgroundColor: strokeColor,
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 350 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.raw} ${isTrend ? 'Form Mutasi' : 'Mutasi Diterima'}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F1F3F5' },
                            ticks: {
                                precision: 0,
                                stepSize: 1,
                                font: { size: 10, family: 'JetBrains Mono, monospace' }
                            }
                        }
                    }
                }
            });
        } else {
            // Bar Chart version
            const barColor = isTrend ? '#805600' : '#537E83';

            chartMutationHubInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dataObj.labels,
                    datasets: [{
                        label: isTrend ? 'Jumlah Mutasi' : 'Mutasi Diterima',
                        data: dataObj.data,
                        backgroundColor: barColor,
                        borderRadius: 4,
                        barPercentage: 0.55
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: { duration: 350 },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.raw} ${isTrend ? 'Form Mutasi' : 'Mutasi Diterima'}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#F1F3F5' },
                            ticks: {
                                precision: 0,
                                stepSize: 1,
                                font: { size: 10, family: 'JetBrains Mono, monospace' }
                            }
                        }
                    }
                }
            });
        }
    }

    function updateMutationTypeButtonUI(type) {
        const btnLine = document.getElementById('btnMutationLine');
        const btnBar = document.getElementById('btnMutationBar');

        if (type === 'line') {
            if (btnLine) btnLine.className = 'p-1 rounded-md bg-surface-white text-secondary shadow-2xs transition-all';
            if (btnBar) btnBar.className = 'p-1 rounded-md text-on-surface-variant hover:text-on-surface transition-all';
        } else {
            if (btnBar) btnBar.className = 'p-1 rounded-md bg-surface-white text-primary shadow-2xs transition-all';
            if (btnLine) btnLine.className = 'p-1 rounded-md text-on-surface-variant hover:text-on-surface transition-all';
        }
    }

    // Switch View for Mutation Hub Chart (Tab Pills)
    window.switchMutationView = function(view) {
        currentMutationView = view;
        currentMutationType = (view === 'trend') ? 'line' : 'bar';

        const info = mutationHubData[view];

        // Update Title & Description
        const titleEl = document.getElementById('mutationHubTitle');
        const descEl = document.getElementById('mutationHubDesc');
        if (titleEl) titleEl.innerText = info.title;
        if (descEl) descEl.innerText = info.desc;

        // Toggle Tab Pill active state
        const tabTrend = document.getElementById('tabMutationTrend');
        const tabReceivers = document.getElementById('tabMutationReceivers');
        if (view === 'trend') {
            if (tabTrend) tabTrend.className = 'px-2.5 py-1 rounded-md text-xs font-semibold bg-surface-white text-secondary shadow-2xs transition-all';
            if (tabReceivers) tabReceivers.className = 'px-2.5 py-1 rounded-md text-xs font-medium text-on-surface-variant hover:text-on-surface transition-all';
        } else {
            if (tabReceivers) tabReceivers.className = 'px-2.5 py-1 rounded-md text-xs font-semibold bg-surface-white text-secondary shadow-2xs transition-all';
            if (tabTrend) tabTrend.className = 'px-2.5 py-1 rounded-md text-xs font-medium text-on-surface-variant hover:text-on-surface transition-all';
        }

        // Toggle Footers
        const trendFooter = document.getElementById('mutationTrendFooter');
        const receiverFooter = document.getElementById('mutationReceiverFooter');
        if (view === 'trend') {
            if (trendFooter) trendFooter.classList.remove('hidden');
            if (receiverFooter) receiverFooter.classList.add('hidden');
        } else {
            if (trendFooter) trendFooter.classList.add('hidden');
            if (receiverFooter) receiverFooter.classList.remove('hidden');
        }

        updateMutationTypeButtonUI(currentMutationType);
        renderMutationHubChart();
    };

    // Toggle Chart Type (Line vs Bar)
    window.toggleMutationType = function(type) {
        currentMutationType = type;
        updateMutationTypeButtonUI(type);
        renderMutationHubChart();
    };

    // Initialize all Charts on Page Load
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof window.Chart === 'undefined') return;

        Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui, sans-serif';
        Chart.defaults.color = '#404846';

        renderAssetDistChart();
        renderDeptValuationsChart();
        renderMutationHubChart();
    });
</script>
@endpush
@endsection
