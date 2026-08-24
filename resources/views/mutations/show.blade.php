@extends('layouts.app')

@section('title', 'Formulir Mutasi ' . $mutation->form_number)
@section('page-title', 'Detail Mutasi Aset')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    {{-- Header Navigation & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('mutations.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-primary-light hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Mutasi
        </a>

        <div class="flex items-center gap-2">
            <a href="{{ route('mutations.print', $mutation) }}" target="_blank"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg border border-border-light bg-surface-white text-xs font-semibold text-on-surface hover:bg-surface-container transition-colors shadow-2xs">
                <svg class="w-4 h-4 text-on-surface-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Cetak Berita Acara
            </a>
        </div>
    </div>

    {{-- Status Bar & Step Progress Tracker --}}
    <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
        <div class="px-6 py-4 border-b border-border-light flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-surface-white">
            <div>
                <span class="text-[11px] font-mono uppercase tracking-wider text-on-surface-variant">Nomor Berkas Mutasi</span>
                <h2 class="font-display text-xl sm:text-2xl font-bold text-on-surface mt-0.5">{{ $mutation->form_number }}</h2>
            </div>
            <div>
                @include('components.status-badge', ['status' => $mutation->status])
            </div>
        </div>

        {{-- Progress Stages --}}
        @php
            $isDraft = $mutation->status === 'draft';
            $isWaitingReceiver = $mutation->status === 'waiting_receiver';
            $isReady = $mutation->status === 'ready_for_execution';
            $isArchived = $mutation->status === 'archived';
            $isRejected = $mutation->status === 'rejected';
        @endphp
        <div class="px-6 py-5 bg-surface-container/20 border-b border-border-light">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-xs">
                {{-- Step 1 --}}
                <div class="p-3 rounded-lg border {{ $mutation->sender_signed_at ? 'border-emerald-300 bg-emerald-50/60' : 'border-border-light bg-surface-white' }}">
                    <div class="flex items-center gap-1.5 text-emerald-800 font-bold text-[11px] mb-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        1. Pengajuan
                    </div>
                    <p class="font-semibold text-on-surface">Pihak Penyerah</p>
                    <p class="text-[10px] text-on-surface-variant font-mono mt-0.5">Disetujui Digital</p>
                </div>

                {{-- Step 2 --}}
                <div class="p-3 rounded-lg border {{ $mutation->receiver_signed_at ? 'border-emerald-300 bg-emerald-50/60' : ($isWaitingReceiver ? 'border-amber-400 bg-amber-50/70 ring-2 ring-amber-200' : ($isRejected ? 'border-rose-300 bg-rose-50' : 'border-border-light bg-surface-white')) }}">
                    <div class="flex items-center gap-1.5 {{ $mutation->receiver_signed_at ? 'text-emerald-800' : ($isWaitingReceiver ? 'text-amber-800 font-bold' : ($isRejected ? 'text-rose-800' : 'text-on-surface-variant')) }} text-[11px] mb-0.5">
                        @if($mutation->receiver_signed_at)
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @elseif($isWaitingReceiver)
                            <span class="w-2 h-2 rounded-full bg-amber-600 animate-pulse"></span>
                        @endif
                        2. Approval Unit
                    </div>
                    <p class="font-semibold text-on-surface">Pihak Penerima</p>
                    <p class="text-[10px] text-on-surface-variant font-mono mt-0.5">
                        @if($mutation->receiver_signed_at)
                            Disetujui Digital
                        @elseif($isRejected)
                            Ditolak
                        @else
                            Menunggu Approval
                        @endif
                    </p>
                </div>

                {{-- Step 3 --}}
                <div class="p-3 rounded-lg border {{ $isArchived ? 'border-emerald-300 bg-emerald-50/60' : ($isReady ? 'border-teal-400 bg-teal-50/70 ring-2 ring-teal-200' : 'border-border-light bg-surface-white') }}">
                    <div class="flex items-center gap-1.5 {{ $isArchived ? 'text-emerald-800' : ($isReady ? 'text-teal-900 font-bold' : 'text-on-surface-variant') }} text-[11px] mb-0.5">
                        @if($isArchived)
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @elseif($isReady)
                            <span class="w-2 h-2 rounded-full bg-teal-600 animate-pulse"></span>
                        @endif
                        3. Eksekusi Mutasi
                    </div>
                    <p class="font-semibold text-on-surface">Bagian Inventaris</p>
                    <p class="text-[10px] text-on-surface-variant font-mono mt-0.5">
                        {{ $isArchived ? 'Selesai Dipindahkan' : ($isReady ? 'Siap Eksekusi' : 'Menunggu Approval') }}
                    </p>
                </div>

                {{-- Step 4 --}}
                <div class="p-3 rounded-lg border {{ $isArchived ? 'border-emerald-300 bg-emerald-50/60' : 'border-border-light bg-surface-white' }}">
                    <div class="flex items-center gap-1.5 {{ $isArchived ? 'text-emerald-800 font-bold' : 'text-on-surface-variant' }} text-[11px] mb-0.5">
                        @if($isArchived)
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        @endif
                        4. Arsip Dokumen
                    </div>
                    <p class="font-semibold text-on-surface">Berita Acara</p>
                    <p class="text-[10px] text-on-surface-variant font-mono mt-0.5">
                        {{ $isArchived ? 'Terkunci Permanen' : 'Draft Sistem' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- 2-Column Content Layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left: Mutation Details & Items (7 Cols) --}}
        <div class="lg:col-span-7 space-y-5">
            {{-- General Info Card --}}
            <div class="bg-surface-white rounded-xl border border-border-light p-6 shadow-xs space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-on-surface-variant pb-2 border-b border-border-light">
                    Informasi Perpindahan Aset
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-3 rounded-lg bg-surface-container/30 border border-border-light/60">
                        <span class="text-[11px] text-on-surface-variant font-medium">Unit Asal (Penyerah)</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="font-mono text-xs px-2 py-0.5 rounded bg-surface-container text-on-surface font-bold">
                                {{ $mutation->fromDepartment->code }}
                            </span>
                            <span class="text-sm font-semibold text-on-surface">{{ $mutation->fromDepartment->name }}</span>
                        </div>
                    </div>

                    <div class="p-3 rounded-lg bg-primary/5 border border-primary/20">
                        <span class="text-[11px] text-primary-light font-medium">Unit Tujuan (Penerima)</span>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="font-mono text-xs px-2 py-0.5 rounded bg-primary text-white font-bold">
                                {{ $mutation->toDepartment->code }}
                            </span>
                            <span class="text-sm font-semibold text-on-surface">{{ $mutation->toDepartment->name }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-3 rounded-lg bg-surface-container/20 border border-border-light space-y-1">
                    <span class="text-[11px] font-semibold text-on-surface-variant uppercase tracking-wider">Alasan / Keperluan Mutasi</span>
                    <p class="text-xs sm:text-sm text-on-surface leading-relaxed">
                        {{ $mutation->reason }}
                    </p>
                </div>

                @if($mutation->rejection_reason)
                <div class="p-3.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-rose-800">Alasan Penolakan:</span>
                    <p class="text-xs leading-relaxed">{{ $mutation->rejection_reason }}</p>
                </div>
                @endif
            </div>

            {{-- Asset Items Table Card --}}
            <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
                <div class="px-5 py-4 border-b border-border-light bg-surface-white flex items-center justify-between">
                    <div>
                        <h3 class="font-display text-sm font-bold text-on-surface">Daftar Barang yang Dimutasi</h3>
                        <p class="text-xs text-on-surface-variant">Total {{ $mutation->items->count() }} unit aset terlampir</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-surface-container/50 border-b border-border-light text-[11px] font-mono font-semibold text-on-surface-variant uppercase">
                                <th class="px-5 py-3">Aset &amp; Kode</th>
                                <th class="px-5 py-3">Nilai Perolehan</th>
                                <th class="px-5 py-3 text-right">Kondisi Fisik</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light">
                            @foreach($mutation->items as $item)
                            <tr class="hover:bg-surface-container/20 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="font-semibold text-on-surface">{{ $item->asset->name }}</div>
                                    <div class="font-mono text-xs text-primary-light font-bold mt-0.5">
                                        {{ $item->asset->asset_code }}
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 font-mono text-xs text-on-surface">
                                    Rp {{ number_format($item->asset->purchase_price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    @php
                                        $condBadge = match($item->item_condition) {
                                            'good' => ['label' => 'Baik', 'class' => 'bg-emerald-50 text-emerald-800 border-emerald-200'],
                                            'damaged_light' => ['label' => 'Rusak Ringan', 'class' => 'bg-amber-50 text-amber-800 border-amber-200'],
                                            'damaged_heavy' => ['label' => 'Rusak Berat', 'class' => 'bg-rose-50 text-rose-800 border-rose-200'],
                                            default => ['label' => 'Normal', 'class' => 'bg-stone-50 text-stone-700 border-stone-200'],
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold border {{ $condBadge['class'] }}">
                                        {{ $condBadge['label'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: Dual-Approval Panels & Inventory Actions (5 Cols) --}}
        <div class="lg:col-span-5 space-y-5">

            {{-- 1. Sender Approval Card --}}
            <div class="bg-surface-white rounded-xl border border-border-light p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-mono font-bold uppercase tracking-wider text-emerald-800">
                        Approval Pihak Penyerah
                    </span>
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Disetujui
                    </span>
                </div>

                <div class="p-3.5 rounded-lg bg-surface-container/30 border border-border-light space-y-1 text-xs">
                    <p class="text-on-surface font-semibold">{{ $mutation->sender ? $mutation->sender->name : 'Sistem' }}</p>
                    <p class="text-[11px] text-on-surface-variant font-mono">
                        {{ $mutation->fromDepartment->name }}
                    </p>
                    <p class="text-[10px] text-on-surface-variant font-mono pt-1 border-t border-border-light">
                        Stempel Waktu: {{ $mutation->sender_signed_at ? $mutation->sender_signed_at->format('d F Y, H:i') : '-' }} WIB
                    </p>
                </div>
            </div>

            {{-- 2. Receiver Approval Card --}}
            <div class="bg-surface-white rounded-xl border border-border-light p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-mono font-bold uppercase tracking-wider {{ $mutation->receiver_signed_at ? 'text-emerald-800' : 'text-amber-800' }}">
                        Approval Pihak Penerima
                    </span>
                    @if($mutation->receiver_signed_at)
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Disetujui
                    </span>
                    @elseif($isRejected)
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-rose-800 bg-rose-50 px-2 py-0.5 rounded-full border border-rose-200">
                        Ditolak
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200 animate-pulse">
                        Menunggu Persetujuan
                    </span>
                    @endif
                </div>

                @if($mutation->receiver_signed_at)
                <div class="p-3.5 rounded-lg bg-surface-container/30 border border-border-light space-y-1 text-xs">
                    <p class="text-on-surface font-semibold">{{ $mutation->receiver ? $mutation->receiver->name : 'Penerima' }}</p>
                    <p class="text-[11px] text-on-surface-variant font-mono">
                        {{ $mutation->toDepartment->name }}
                    </p>
                    <p class="text-[10px] text-on-surface-variant font-mono pt-1 border-t border-border-light">
                        Stempel Waktu: {{ $mutation->receiver_signed_at->format('d F Y, H:i') }} WIB
                    </p>
                </div>
                @elseif($canApproveAsReceiver)
                {{-- Receiver Action Box: 1-Click Approval Button --}}
                <div class="p-4 rounded-lg bg-amber-50/70 border border-amber-300 space-y-3">
                    <div>
                        <h4 class="text-xs font-bold text-amber-950">Konfirmasi Penerimaan Aset</h4>
                        <p class="text-[11px] text-amber-800 mt-0.5 leading-relaxed">
                            Unit Anda ({{ $mutation->toDepartment->name }}) tercatat sebagai penerima aset ini. Klik tombol di bawah untuk menyetujui mutasi secara digital.
                        </p>
                    </div>

                    <div class="flex flex-col gap-2 pt-1">
                        <form method="POST" action="{{ route('mutations.approve-receiver', $mutation) }}">
                            @csrf
                            <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-lg bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 transition-all shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Setujui Mutasi (Approval Penerima)
                            </button>
                        </form>

                        <button type="button" onclick="document.getElementById('reject-box').classList.toggle('hidden')"
                                class="w-full py-1.5 text-center text-xs font-semibold text-rose-700 hover:text-rose-900 transition-colors">
                            Tolak Pengajuan Mutasi
                        </button>

                        <div id="reject-box" class="hidden p-3 rounded-md bg-white border border-rose-200 space-y-2 mt-1">
                            <form method="POST" action="{{ route('mutations.reject', $mutation) }}" class="space-y-2">
                                @csrf
                                <label for="rejection_reason" class="block text-[11px] font-semibold text-rose-900">Alasan Penolakan:</label>
                                <textarea name="rejection_reason" id="rejection_reason" rows="2" required
                                          placeholder="Tuliskan alasan penolakan..."
                                          class="w-full p-2 text-xs border border-rose-300 rounded focus:outline-none focus:ring-1 focus:ring-rose-500"></textarea>
                                <button type="submit" class="w-full py-1.5 rounded bg-rose-700 text-white text-xs font-semibold hover:bg-rose-800 transition-colors">
                                    Konfirmasi Tolak Mutasi
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @else
                <div class="p-3.5 rounded-lg bg-stone-50 border border-stone-200 text-xs text-on-surface-variant space-y-1">
                    <p class="font-semibold text-on-surface">Menunggu Tanggapan Unit Penerima</p>
                    <p class="text-[11px]">
                        Pengguna dari departemen <strong class="text-on-surface">{{ $mutation->toDepartment->name }}</strong> berhak menyetujui atau menolak formulir ini.
                    </p>
                </div>
                @endif
            </div>

            {{-- 3. Inventory Execution Card --}}
            <div class="bg-surface-white rounded-xl border border-border-light p-5 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-mono font-bold uppercase tracking-wider {{ $isArchived ? 'text-emerald-800' : ($isReady ? 'text-primary-light' : 'text-on-surface-variant') }}">
                        Eksekusi Bagian Inventaris
                    </span>
                    @if($isArchived)
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Diarsipkan
                    </span>
                    @elseif($isReady)
                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary-light bg-primary-surface px-2 py-0.5 rounded-full border border-teal-200">
                        Siap Eksekusi
                    </span>
                    @endif
                </div>

                @if($isArchived)
                <div class="p-3.5 rounded-lg bg-surface-container/30 border border-border-light space-y-1 text-xs">
                    <p class="text-on-surface font-semibold">Dieksekusi oleh: {{ $mutation->executor ? $mutation->executor->name : 'Bagian Inventaris' }}</p>
                    <p class="text-[11px] text-emerald-700 font-medium">
                        Kepemilikan aset resmi telah berpindah ke {{ $mutation->toDepartment->name }}.
                    </p>
                    <p class="text-[10px] text-on-surface-variant font-mono pt-1 border-t border-border-light">
                        Waktu Eksekusi: {{ $mutation->updated_at->format('d F Y, H:i') }} WIB
                    </p>
                </div>
                @elseif($canExecute)
                {{-- Inventory Execution Action Box --}}
                <div class="p-4 rounded-lg bg-teal-50/70 border border-teal-300 space-y-3">
                    <div>
                        <h4 class="text-xs font-bold text-teal-950">Eksekusi Perpindahan &amp; Arsip Berita Acara</h4>
                        <p class="text-[11px] text-teal-800 mt-0.5 leading-relaxed">
                            Kedua pihak (Penyerah &amp; Penerima) telah menyetujui mutasi ini. Klik tombol di bawah untuk memindahkan kepemilikan aset secara definitif di database dan mengunci arsip formulir.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('mutations.execute', $mutation) }}">
                        @csrf
                        <button type="submit"
                                onclick="return confirm('Apakah Anda yakin ingin mengeksekusi mutasi aset ini secara definitif?')"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-primary text-white text-xs font-bold hover:bg-primary-light transition-all shadow-xs">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Eksekusi Pemindahan &amp; Arsipkan Mutasi
                        </button>
                    </form>
                </div>
                @else
                <div class="p-3.5 rounded-lg bg-stone-50 border border-stone-200 text-xs text-on-surface-variant space-y-1">
                    <p class="font-semibold text-on-surface">Tahap Eksekusi Logistik</p>
                    <p class="text-[11px]">
                        Tombol eksekusi mutasi akan aktif bagi Bagian Inventaris setelah pihak penerima menyetujui formulir ini.
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
