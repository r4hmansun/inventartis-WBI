@extends('layouts.app')

@section('title', 'Terbitkan Formulir Mutasi Aset')
@section('page-title', 'Formulir Mutasi Aset')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Back Link --}}
    <div>
        <a href="{{ route('mutations.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-primary-light hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Mutasi
        </a>
    </div>

    {{-- Form Container --}}
    <div class="bg-surface-white rounded-xl border border-border-light overflow-hidden shadow-xs">
        <div class="px-6 py-5 border-b border-border-light bg-surface-white">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-primary-surface text-primary-light flex items-center justify-center font-bold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-display text-lg sm:text-xl font-bold text-on-surface">Penerbitan Formulir Mutasi Aset</h2>
                    <p class="text-xs text-on-surface-variant">
                        Mulai alur perpindahan aset resmi dengan persetujuan digital (Dual-Approval System).
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('mutations.store') }}" class="p-6 space-y-6">
            @csrf

            {{-- Error Summary --}}
            @if ($errors->any())
            <div class="p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 text-xs space-y-1">
                <p class="font-semibold flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Mohon periksa kembali isian formulir:
                </p>
                <ul class="list-disc list-inside space-y-0.5 text-[11px] pl-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Section 1: Department Mapping --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                {{-- Origin Department --}}
                <div class="p-4 rounded-lg bg-surface-container/30 border border-border-light">
                    <label class="block text-xs font-semibold text-on-surface-variant mb-1 uppercase tracking-wider">
                        Departemen Asal (Penyerah)
                    </label>
                    <input type="hidden" name="from_department_id" value="{{ $fromDepartment->id }}">
                    <div class="flex items-center gap-2 mt-1">
                        <span class="font-mono text-xs px-2 py-0.5 rounded bg-surface-container text-primary font-bold">
                            {{ $fromDepartment->code }}
                        </span>
                        <span class="text-sm font-bold text-on-surface">{{ $fromDepartment->name }}</span>
                    </div>
                    <p class="text-[11px] text-on-surface-variant mt-1">
                        Diajukan oleh: <strong class="text-on-surface">{{ $user->name }}</strong>
                    </p>
                </div>

                {{-- Destination Department --}}
                <div class="p-4 rounded-lg bg-surface-white border border-outline-variant">
                    <label for="to_department_id" class="block text-xs font-semibold text-on-surface mb-1 uppercase tracking-wider">
                        Departemen Tujuan (Penerima) <span class="text-rose-600">*</span>
                    </label>
                    <select id="to_department_id" name="to_department_id" required
                            class="w-full px-3 py-2 rounded-md border border-outline-variant bg-surface-white text-on-surface text-xs sm:text-sm
                                   focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">
                        <option value="">-- Pilih Departemen Penerima --</option>
                        @foreach($targetDepartments as $target)
                            <option value="{{ $target->id }}" {{ old('to_department_id') == $target->id ? 'selected' : '' }}>
                                {{ $target->name }} ({{ $target->code }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-on-surface-variant mt-1">
                        Pihak penerima akan menerima notifikasi untuk menyetujui mutasi ini.
                    </p>
                </div>
            </div>

            {{-- Section 2: Asset Selection --}}
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface">
                            Pilih Aset yang Akan Dimutasi <span class="text-rose-600">*</span>
                        </label>
                        <p class="text-[11px] text-on-surface-variant">Centang aset milik unit Anda yang akan dipindahkan.</p>
                    </div>
                    <span class="text-xs font-mono text-on-surface-variant">
                        Tersedia: {{ $availableAssets->count() }} unit
                    </span>
                </div>

                @if($availableAssets->isEmpty())
                <div class="p-6 rounded-lg bg-stone-50 border border-stone-200 text-center text-xs text-on-surface-variant space-y-1">
                    <p class="font-semibold text-on-surface">Tidak ada aset aktif di unit {{ $fromDepartment->name }}.</p>
                    <p class="text-[11px]">Pastikan barang telah terdaftar dan berada di bawah tanggung jawab unit Anda.</p>
                </div>
                @else
                <div class="rounded-lg border border-border-light overflow-hidden divide-y divide-border-light max-h-80 overflow-y-auto">
                    @foreach($availableAssets as $asset)
                    <div class="p-3.5 hover:bg-surface-container/30 transition-colors flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="asset_{{ $asset->id }}" name="asset_ids[]" value="{{ $asset->id }}"
                                   {{ is_array(old('asset_ids')) && in_array($asset->id, old('asset_ids')) ? 'checked' : '' }}
                                   class="mt-1 w-4 h-4 text-primary rounded border-outline-variant focus:ring-primary">
                            <label for="asset_{{ $asset->id }}" class="cursor-pointer">
                                <span class="font-semibold text-xs sm:text-sm text-on-surface">{{ $asset->name }}</span>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="font-mono text-[11px] text-primary-light font-bold">{{ $asset->asset_code }}</span>
                                    <span class="text-on-surface-variant text-[11px]">&bull;</span>
                                    <span class="font-mono text-[11px] text-on-surface-variant">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</span>
                                </div>
                            </label>
                        </div>

                        <div class="sm:self-center flex items-center gap-2">
                            <label class="text-[11px] text-on-surface-variant whitespace-nowrap">Kondisi:</label>
                            <select name="item_conditions[{{ $asset->id }}]"
                                    class="px-2.5 py-1 rounded border border-outline-variant bg-surface-white text-on-surface text-xs focus:outline-none focus:border-primary">
                                <option value="good" {{ old("item_conditions.{$asset->id}") === 'good' ? 'selected' : '' }}>Baik (Normal)</option>
                                <option value="damaged_light" {{ old("item_conditions.{$asset->id}") === 'damaged_light' ? 'selected' : '' }}>Rusak Ringan</option>
                                <option value="damaged_heavy" {{ old("item_conditions.{$asset->id}") === 'damaged_heavy' ? 'selected' : '' }}>Rusak Berat</option>
                            </select>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Section 3: Reason --}}
            <div class="space-y-1.5">
                <label for="reason" class="block text-xs font-bold uppercase tracking-wider text-on-surface">
                    Alasan / Keperluan Mutasi <span class="text-rose-600">*</span>
                </label>
                <textarea id="reason" name="reason" rows="3" required
                          placeholder="Contoh: Perpindahan workstation untuk staf baru di Unit PKA atau penyesuaian fasilitas laboratorium."
                          class="w-full px-3.5 py-2.5 rounded-lg border border-outline-variant bg-surface-white text-on-surface text-xs sm:text-sm
                                 placeholder-on-surface-variant/50 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-colors">{{ old('reason') }}</textarea>
            </div>

            {{-- Section 4: Digital Approval Confirmation --}}
            <div class="p-4 rounded-lg bg-teal-50/60 border border-teal-200 space-y-2">
                <div class="flex items-start gap-2.5">
                    <div class="w-5 h-5 rounded-full bg-teal-600 text-white flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-bold text-teal-950">Persetujuan Digital Pihak Penyerah (1-Click Approval)</h4>
                        <p class="text-[11px] text-teal-800 leading-relaxed mt-0.5">
                            Sesuai SOP digital WBI, formulir yang diterbitkan otomatis mencatat identitas Anda (<strong class="font-semibold">{{ $user->name }}</strong>) dan stempel waktu persetujuan sebagai pihak penyerah tanpa perlu tanda tangan fisik.
                        </p>
                    </div>
                </div>

                <div class="pt-2 border-t border-teal-200/60 flex items-start gap-2">
                    <input type="checkbox" id="sender_approval_confirm" name="sender_approval_confirm" value="1" required
                           {{ old('sender_approval_confirm') ? 'checked' : '' }}
                           class="mt-0.5 w-4 h-4 text-primary rounded border-teal-400 focus:ring-primary">
                    <label for="sender_approval_confirm" class="text-xs text-teal-950 font-medium cursor-pointer">
                        Saya menyetujui penyerahan aset di atas dan menyatakan data formulir mutasi ini sah dan benar. <span class="text-rose-600">*</span>
                    </label>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-4 border-t border-border-light flex items-center justify-end gap-3">
                <a href="{{ route('mutations.index') }}"
                   class="px-4 py-2.5 rounded-lg bg-surface-container text-xs sm:text-sm font-semibold text-on-surface hover:bg-surface-container-high transition-colors">
                    Batal
                </a>
                <button type="submit"
                        @if($availableAssets->isEmpty()) disabled @endif
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-white text-xs sm:text-sm font-semibold hover:bg-primary-light transition-all shadow-xs disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Terbitkan &amp; Setujui Mutasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
