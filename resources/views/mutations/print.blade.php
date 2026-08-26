<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAST Mutasi Aset — {{ $mutation->form_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 18mm 15mm 18mm;
        }

        html, body {
            background-color: #ffffff !important;
            color: #000000 !important;
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', Times, 'Liberation Serif', serif;
        }

        .paper-sheet {
            background: #ffffff;
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            padding: 16mm 20mm;
            box-sizing: border-box;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            .paper-sheet {
                max-width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .page-break {
                page-break-before: always;
            }
            .avoid-break {
                page-break-inside: avoid;
            }
        }

        .table-bast {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .table-bast th, .table-bast td {
            border: 1px solid #000000;
            padding: 5px 7px;
        }

        .table-bast th {
            background-color: #f2f2f2 !important;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            font-size: 9pt;
            letter-spacing: 0.3px;
        }

        @media print {
            .table-bast th {
                background-color: #f2f2f2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body class="antialiased text-black bg-white">

    {{-- Clean Minimalist White Toolbar --}}
    <header class="no-print sticky top-0 z-50 bg-white border-b border-stone-200 shadow-2xs font-sans px-4 sm:px-8 py-2.5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('mutations.show', $mutation) }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-stone-700 bg-stone-100 hover:bg-stone-200 transition-colors"
               title="Kembali ke formulir mutasi">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali</span>
            </a>

            <div class="h-4 w-px bg-stone-300 hidden sm:block"></div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-stone-800 hidden md:inline">Berita Acara Mutasi Aset</span>
                <span class="text-stone-400 hidden md:inline">&bull;</span>
                <span class="font-mono text-xs font-bold text-stone-900 bg-stone-100 border border-stone-300 px-2 py-0.5 rounded">
                    {{ $mutation->form_number }}
                </span>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <span class="text-[11px] text-stone-500 font-mono hidden lg:inline-block">
                Shortcut: <kbd class="px-1.5 py-0.5 rounded bg-stone-100 border border-stone-300 text-stone-700 text-[10px]">Ctrl + P</kbd>
            </span>

            <button type="button" onclick="window.print()"
                    class="px-4 py-1.5 rounded-lg bg-[#002a22] hover:bg-[#134137] text-white text-xs font-semibold transition-all inline-flex items-center gap-2 shadow-xs cursor-pointer active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak Dokumen</span>
            </button>

            <button type="button" onclick="window.close()"
                    class="px-3 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 border border-stone-200 text-xs font-medium text-stone-700 transition-colors cursor-pointer">
                Tutup
            </button>
        </div>
    </header>

    {{-- Official A4 Sheet Container (Pure White Background) --}}
    <div class="paper-sheet text-black text-[10.5pt] leading-normal bg-white">

        {{-- 1. Kop Surat Resmi Institusi --}}
        <div class="flex items-center justify-between gap-5 pb-1">
            <div class="shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Politeknik Wilmar Bisnis Indonesia" class="h-20 w-auto object-contain">
            </div>
            <div class="flex-1 text-center font-doc">
                <p class="text-[11pt] font-semibold tracking-wider text-black uppercase leading-tight">
                    Yayasan Wilmar Bisnis Indonesia
                </p>
                <h1 class="text-[14.5pt] font-bold tracking-tight text-black uppercase leading-tight mt-0.5">
                    Politeknik Wilmar Bisnis Indonesia
                </h1>
                <p class="text-[11pt] font-bold text-black uppercase leading-tight mt-0.5">
                    Biro Pengelolaan Aset, Keuangan &amp; Logistik
                </p>
                <p class="text-[8.5pt] text-black leading-tight mt-1">
                    Jl. Kapten Batu Sihombing, Medan Estate, Kec. Percut Sei Tuan, Kab. Deli Serdang, Sumatera Utara 20371
                </p>
                <p class="text-[8.5pt] text-black leading-tight">
                    Telepon: (061) 8001 0010 &bull; Pos-el: info@wbi.ac.id &bull; Laman: www.wbi.ac.id
                </p>
            </div>
        </div>

        {{-- Garis Ganda Standar Kop Surat Resmi Indonesia --}}
        <div style="border-top: 2.5px solid #000000; margin-top: 6px;"></div>
        <div style="border-top: 0.75px solid #000000; margin-top: 2px; margin-bottom: 14px;"></div>

        {{-- 2. Judul & Nomor Surat --}}
        <div class="text-center my-3">
            <h2 class="text-[12pt] font-bold text-black uppercase underline tracking-wide">
                Berita Acara Serah Terima (BAST) Mutasi Aset
            </h2>
            <p class="text-[10pt] font-mono mt-0.5 font-bold">
                Nomor: {{ $mutation->form_number }}
            </p>
        </div>

        {{-- 3. Pembukaan / Klausul Waktu & Tempat (Waktu Indonesia Barat / WIB) --}}
        <p class="text-justify my-2.5">
            Pada hari ini, <strong>{{ $mutation->created_at->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('dddd') }}</strong>, tanggal <strong>{{ $mutation->created_at->setTimezone('Asia/Jakarta')->locale('id')->isoFormat('D MMMM Y') }}</strong> ({{ $mutation->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y') }}), bertempat di Kampus Politeknik Wilmar Bisnis Indonesia, kami yang bertanda tangan di bawah ini:
        </p>

        {{-- 4. Identitas Para Pihak (Format Dua Pihak Standard Dokumen Formal) --}}
        <table class="w-full text-[10pt] my-2" style="border: none; line-height: 1.5;">
            <tr>
                <td style="width: 25px; vertical-align: top; font-weight: bold;">1.</td>
                <td style="width: 175px; vertical-align: top; font-weight: bold;">Nama Pegawai</td>
                <td style="width: 12px; vertical-align: top;">:</td>
                <td style="vertical-align: top; font-weight: bold;">{{ $mutation->sender ? $mutation->sender->name : 'Sistem' }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="vertical-align: top;">Unit Kerja / Departemen</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $mutation->fromDepartment->name }} ({{ $mutation->fromDepartment->code }})</td>
            </tr>
            <tr>
                <td></td>
                <td style="vertical-align: top;">Kedudukan dalam Surat</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">Selanjutnya disebut sebagai <strong>PIHAK PERTAMA (Yang Menyerahkan)</strong></td>
            </tr>
            <tr><td colspan="4" style="height: 5px;"></td></tr>
            <tr>
                <td style="vertical-align: top; font-weight: bold;">2.</td>
                <td style="vertical-align: top; font-weight: bold;">Nama Pegawai</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top; font-weight: bold;">{{ $mutation->receiver ? $mutation->receiver->name : '(Menunggu Persetujuan Unit Penerima)' }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="vertical-align: top;">Unit Kerja / Departemen</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">{{ $mutation->toDepartment->name }} ({{ $mutation->toDepartment->code }})</td>
            </tr>
            <tr>
                <td></td>
                <td style="vertical-align: top;">Kedudukan dalam Surat</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top;">Selanjutnya disebut sebagai <strong>PIHAK KEDUA (Yang Menerima)</strong></td>
            </tr>
        </table>

        {{-- 5. Pernyataan Penyerahan Barang --}}
        <p class="text-justify my-2.5">
            Dengan ini menyatakan bahwa <strong>PIHAK PERTAMA</strong> telah menyerahkan barang inventaris kepada <strong>PIHAK KEDUA</strong>, dan <strong>PIHAK KEDUA</strong> menyatakan telah memeriksa dan menerima barang inventaris tersebut dengan rincian sebagai berikut:
        </p>

        {{-- 6. Tabel Rincian Barang (Format Border Presisi Resmi) --}}
        <div class="my-3">
            <table class="table-bast">
                <thead>
                    <tr>
                        <th style="width: 32px;">No.</th>
                        <th style="width: 140px;">Kode Inventaris</th>
                        <th>Nama Barang &amp; Spesifikasi</th>
                        <th style="width: 95px;">Kondisi Fisik</th>
                        <th style="width: 125px;">Nilai Perolehan</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalNilai = 0; @endphp
                    @forelse($mutation->items as $index => $item)
                    @php $totalNilai += $item->asset->purchase_price; @endphp
                    <tr>
                        <td style="text-align: center; font-family: monospace;">{{ $index + 1 }}</td>
                        <td style="font-family: monospace; font-weight: bold; text-align: center;">{{ $item->asset->asset_code }}</td>
                        <td>{{ $item->asset->name }}</td>
                        <td style="text-align: center;">
                            @if($item->item_condition === 'good')
                                Baik
                            @elseif($item->item_condition === 'damaged_light')
                                Rusak Ringan
                            @elseif($item->item_condition === 'damaged_heavy')
                                Rusak Berat
                            @else
                                Normal
                            @endif
                        </td>
                        <td style="text-align: right; font-family: monospace;">
                            Rp {{ number_format($item->asset->purchase_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; font-style: italic; color: #666;">
                            Tidak ada rincian barang tercatat pada formulir ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="font-weight: bold; background-color: #fafafa;">
                        <td colspan="3" style="text-align: right; text-transform: uppercase;">
                            Total: {{ $mutation->items->count() }} Unit Barang
                        </td>
                        <td style="text-align: center;">Akumulasi:</td>
                        <td style="text-align: right; font-family: monospace;">
                            Rp {{ number_format($totalNilai, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- 7. Alasan Mutasi & Klausul Pengalihan Tanggung Jawab --}}
        <div class="my-2.5 text-[10pt] leading-relaxed">
            <p>
                <strong>Keperluan / Alasan Mutasi:</strong> {{ $mutation->reason }}
            </p>
            <p class="text-justify mt-1">
                <strong>Ketentuan Pengalihan:</strong> Terhitung sejak penandatanganan Berita Acara ini, maka hak operasional, pemeliharaan fisik, dan tanggung jawab pengawasan atas barang-barang inventaris tersebut di atas secara sah beralih kepada <strong>PIHAK KEDUA</strong> dan telah dibukukan dalam Sistem Informasi Inventaris Politeknik Wilmar Bisnis Indonesia.
            </p>
            <p class="text-justify mt-1">
                Demikian Berita Acara Serah Terima ini dibuat dengan sebenarnya dan disetujui secara sadar oleh para pihak untuk dapat dipergunakan sebagaimana mestinya.
            </p>
        </div>

        {{-- 8. Catatan Kaki Dokumen Resmi --}}
        <div class="mt-8 pt-3 border-t border-stone-400 flex items-center justify-between text-[8pt] font-mono text-stone-600">
            <span>No. Dokumen: {{ $mutation->form_number }}</span>
            <span>Dokumen Resmi &bull; Sistem Inventaris Politeknik Wilmar Bisnis Indonesia</span>
            <span>Status: {{ strtoupper($mutation->status_label) }}</span>
        </div>

    </div>

</body>
</html>
