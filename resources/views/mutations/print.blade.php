<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Mutasi Aset — {{ $mutation->form_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>
<body class="bg-stone-100 text-stone-900 font-sans p-4 sm:p-8 antialiased">
    <div class="max-w-4xl mx-auto space-y-4">
        {{-- Floating Control Bar (Hidden during print) --}}
        <div class="no-print bg-white p-4 rounded-xl border border-stone-200 shadow-sm flex items-center justify-between">
            <div class="text-xs text-stone-600">
                Dokumen Resmi: <strong class="font-mono text-stone-900">{{ $mutation->form_number }}</strong>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="window.print()" class="px-4 py-2 rounded-lg bg-[#002a22] text-white text-xs font-semibold hover:bg-[#134137] transition-colors inline-flex items-center gap-1.5 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak / Simpan PDF
                </button>
                <button type="button" onclick="window.close()" class="px-3 py-2 rounded-lg bg-stone-100 text-xs font-semibold text-stone-700 hover:bg-stone-200 transition-colors">
                    Tutup
                </button>
            </div>
        </div>

        {{-- Official Sheet (Paper Style) --}}
        <div class="bg-white p-8 sm:p-12 rounded-xl border border-stone-200 shadow-sm space-y-6 text-xs sm:text-sm leading-relaxed">
            {{-- Kop Surat Resmi --}}
            <div class="flex items-center justify-between pb-6 border-b-2 border-stone-800 gap-4">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Wilmar Business Indonesia Polytechnic" class="h-14 sm:h-16 w-auto object-contain shrink-0">
                    <div class="border-l border-stone-300 pl-4">
                        <h1 class="font-bold text-base sm:text-lg text-stone-900 tracking-tight leading-tight">POLITEKNIK WILMAR BISNIS INDONESIA</h1>
                        <p class="text-xs text-stone-700 font-medium">Biro Pengelolaan Aset, Keuangan &amp; Logistik Terpadu</p>
                        <p class="text-[10px] text-stone-500 mt-0.5">Jl. Kapten Batu Sihombing, Medan Estate, Kec. Percut Sei Tuan, Deli Serdang, Sumatera Utara 20371</p>
                    </div>
                </div>
                <div class="text-right font-mono text-[11px] text-stone-600 shrink-0">
                    <p class="font-bold text-stone-900 text-xs">BERITA ACARA MUTASI</p>
                    <p class="font-bold text-primary">{{ $mutation->form_number }}</p>
                    <p class="text-[10px] text-emerald-800 font-bold uppercase mt-1">{{ $mutation->status === 'archived' ? 'TERCATAT & SAH' : 'PROSES PERSETUJUAN' }}</p>
                </div>
            </div>

            {{-- Document Title --}}
            <div class="text-center space-y-1 py-2">
                <h2 class="text-base sm:text-lg font-bold text-stone-900 tracking-wide uppercase underline underline-offset-4">
                    BERITA ACARA SERAH TERIMA ASET INVENTARIS
                </h2>
                <p class="text-xs text-stone-600 font-mono">
                    Nomor: {{ $mutation->form_number }}
                </p>
            </div>

            {{-- Intro Paragraph --}}
            <p class="text-stone-800">
                Pada hari ini, tanggal <strong>{{ $mutation->created_at->translatedFormat('d F Y') }}</strong>, bertempat di lingkungan Politeknik Wilmar Bisnis Indonesia, telah dilaksanakan serah terima kepemilikan dan tanggung jawab operasional atas aset inventaris antara pihak-pihak di bawah ini:
            </p>

            {{-- Parties Description --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-lg bg-stone-50 border border-stone-200">
                <div class="space-y-1">
                    <p class="text-[11px] font-mono uppercase font-bold text-stone-500">PIHAK PERTAMA (PENYERAH)</p>
                    <p class="font-bold text-stone-900">{{ $mutation->sender ? $mutation->sender->name : 'Sistem' }}</p>
                    <p class="text-xs text-stone-600">Unit / Departemen: <strong>{{ $mutation->fromDepartment->name }} ({{ $mutation->fromDepartment->code }})</strong></p>
                </div>
                <div class="space-y-1">
                    <p class="text-[11px] font-mono uppercase font-bold text-stone-500">PIHAK KEDUA (PENERIMA)</p>
                    <p class="font-bold text-stone-900">{{ $mutation->receiver ? $mutation->receiver->name : 'Menunggu Approval' }}</p>
                    <p class="text-xs text-stone-600">Unit / Departemen: <strong>{{ $mutation->toDepartment->name }} ({{ $mutation->toDepartment->code }})</strong></p>
                </div>
            </div>

            {{-- Reason / Keperluan --}}
            <div>
                <p class="font-semibold text-stone-900 mb-1">Keperluan / Alasan Mutasi:</p>
                <p class="p-3 rounded-lg bg-stone-50 border border-stone-200 text-stone-800 italic">
                    "{{ $mutation->reason }}"
                </p>
            </div>

            {{-- Items Table --}}
            <div>
                <p class="font-semibold text-stone-900 mb-2">Rincian Barang yang Diserahterimakan:</p>
                <table class="w-full text-left border border-stone-300 text-xs">
                    <thead>
                        <tr class="bg-stone-100 border-b border-stone-300 font-mono text-[11px] uppercase">
                            <th class="p-2.5 border-r border-stone-300 text-center w-10">No</th>
                            <th class="p-2.5 border-r border-stone-300">Kode Aset</th>
                            <th class="p-2.5 border-r border-stone-300">Nama Barang / Deskripsi</th>
                            <th class="p-2.5 border-r border-stone-300 text-center">Kondisi</th>
                            <th class="p-2.5 text-right">Nilai Perolehan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-300">
                        @foreach($mutation->items as $index => $item)
                        <tr>
                            <td class="p-2.5 border-r border-stone-300 text-center font-mono">{{ $index + 1 }}</td>
                            <td class="p-2.5 border-r border-stone-300 font-mono font-bold text-stone-900">{{ $item->asset->asset_code }}</td>
                            <td class="p-2.5 border-r border-stone-300 font-medium">{{ $item->asset->name }}</td>
                            <td class="p-2.5 border-r border-stone-300 text-center capitalize">
                                {{ $item->item_condition === 'good' ? 'Baik' : ($item->item_condition === 'damaged_light' ? 'Rusak Ringan' : 'Rusak Berat') }}
                            </td>
                            <td class="p-2.5 text-right font-mono">Rp {{ number_format($item->asset->purchase_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Digital Approval Sign-Off Block --}}
            <div class="pt-6 border-t border-stone-200">
                <p class="text-center font-semibold text-stone-700 text-xs mb-4">
                    Verifikasi Persetujuan Digital Sistem (Dual-Approval WBI Asset Management)
                </p>
                <div class="grid grid-cols-3 gap-4 text-center">
                    {{-- Pihak Penyerah --}}
                    <div class="p-3 rounded-lg border border-stone-200 bg-stone-50 space-y-2">
                        <p class="text-[10px] font-mono font-bold uppercase text-stone-500">Pihak Penyerah</p>
                        <div class="h-16 flex flex-col items-center justify-center text-[10px] font-mono text-emerald-800">
                            <span class="font-bold border border-emerald-400 bg-emerald-100/60 px-2 py-0.5 rounded">DIGITALLY APPROVED</span>
                            <span class="text-[9px] mt-0.5">{{ $mutation->sender_signed_at ? $mutation->sender_signed_at->format('d/m/Y H:i') : '-' }} WIB</span>
                        </div>
                        <p class="font-bold text-stone-900 border-t border-stone-200 pt-1 text-xs">{{ $mutation->sender ? $mutation->sender->name : 'Sistem' }}</p>
                        <p class="text-[10px] text-stone-500">{{ $mutation->fromDepartment->name }}</p>
                    </div>

                    {{-- Pihak Penerima --}}
                    <div class="p-3 rounded-lg border border-stone-200 bg-stone-50 space-y-2">
                        <p class="text-[10px] font-mono font-bold uppercase text-stone-500">Pihak Penerima</p>
                        <div class="h-16 flex flex-col items-center justify-center text-[10px] font-mono {{ $mutation->receiver_signed_at ? 'text-emerald-800' : 'text-amber-800' }}">
                            @if($mutation->receiver_signed_at)
                                <span class="font-bold border border-emerald-400 bg-emerald-100/60 px-2 py-0.5 rounded">DIGITALLY APPROVED</span>
                                <span class="text-[9px] mt-0.5">{{ $mutation->receiver_signed_at->format('d/m/Y H:i') }} WIB</span>
                            @else
                                <span class="border border-amber-300 bg-amber-50 px-2 py-0.5 rounded italic">Menunggu Persetujuan</span>
                            @endif
                        </div>
                        <p class="font-bold text-stone-900 border-t border-stone-200 pt-1 text-xs">{{ $mutation->receiver ? $mutation->receiver->name : 'Penerima Barang' }}</p>
                        <p class="text-[10px] text-stone-500">{{ $mutation->toDepartment->name }}</p>
                    </div>

                    {{-- Pengelola / Eksekutor Inventaris --}}
                    <div class="p-3 rounded-lg border border-stone-200 bg-stone-50 space-y-2">
                        <p class="text-[10px] font-mono font-bold uppercase text-stone-500">Bagian Inventaris</p>
                        <div class="h-16 flex flex-col items-center justify-center text-[10px] font-mono {{ $mutation->executed_by_user_id ? 'text-emerald-800' : 'text-stone-500' }}">
                            @if($mutation->executed_by_user_id)
                                <span class="font-bold border border-emerald-400 bg-emerald-100/60 px-2 py-0.5 rounded">EXECUTED &amp; ARCHIVED</span>
                                <span class="text-[9px] mt-0.5">{{ $mutation->updated_at->format('d/m/Y H:i') }} WIB</span>
                            @else
                                <span class="border border-stone-300 bg-stone-100 px-2 py-0.5 rounded italic">Menunggu Eksekusi</span>
                            @endif
                        </div>
                        <p class="font-bold text-stone-900 border-t border-stone-200 pt-1 text-xs">{{ $mutation->executor ? $mutation->executor->name : 'Bagian Logistik WBI' }}</p>
                        <p class="text-[10px] text-stone-500">Biro Pengelolaan Inventaris</p>
                    </div>
                </div>
            </div>

            {{-- Footer Note --}}
            <div class="pt-4 text-center text-[10px] font-mono text-stone-500 border-t border-stone-200">
                Dokumen ini diterbitkan secara elektronik oleh Sistem Inventaris WBI dan memiliki kekuatan hukum serah terima aset yang sah.
            </div>
        </div>
    </div>
</body>
</html>
