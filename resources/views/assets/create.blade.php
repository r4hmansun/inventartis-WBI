@extends('layouts.app')

@section('title', 'Tambah Aset')
@section('page-title', 'Tambah Aset')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('assets.index') }}" class="inline-flex items-center gap-1 text-sm text-on-surface-variant hover:text-on-surface transition-colors mb-6">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Daftar Aset
    </a>

    <div class="bg-surface-white rounded-lg border border-border-light overflow-hidden shadow-xs">
        <div class="px-6 py-4 border-b border-border-light">
            <h2 class="font-display text-lg font-semibold text-on-surface">Tambah Aset Baru</h2>
            <p class="text-sm text-on-surface-variant mt-0.5">
                Aset baru akan otomatis masuk ke <span class="font-mono text-xs text-primary-light bg-primary-surface px-1.5 py-0.5 rounded">Gudang Inventaris</span>
            </p>
        </div>

        <form method="POST" action="{{ route('assets.store') }}" class="p-6 space-y-5" id="asset-form">
            @csrf

            {{-- Nama Aset --}}
            <div>
                <label for="name" class="block text-sm font-medium text-on-surface mb-1.5">Nama Aset / Barang</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       placeholder="Contoh: Laptop Dell Latitude 5550"
                       class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                              placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                              @error('name') border-error @enderror">
                @error('name')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Harga Perolehan (with Rp prefix) --}}
            <div>
                <label for="purchase_price" class="block text-sm font-medium text-on-surface mb-1.5">Harga Perolehan</label>
                <div class="relative">
                    <span class="absolute left-0 top-0 bottom-0 flex items-center px-3 bg-surface-container border border-r-0 border-outline-variant rounded-l-md text-sm font-mono text-on-surface-variant">
                        Rp
                    </span>
                    <input type="number" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required
                           min="0" step="1"
                           placeholder="500000"
                           class="w-full pl-12 pr-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm font-mono
                                  placeholder-on-surface-variant/50 focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                                  @error('purchase_price') border-error @enderror"
                           oninput="checkThreshold(this.value)">
                </div>

                {{-- Threshold Warning (BR-01) --}}
                <div id="threshold-warning" class="hidden mt-2 px-3 py-2 rounded-md bg-amber-50 border border-amber-200">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 text-warning shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <p class="text-xs font-semibold text-warning">Nilai di bawah ambang batas kapitalisasi</p>
                            <p class="text-xs text-on-surface-variant mt-0.5">Barang dengan nilai &lt; Rp 500.000 tidak dapat didaftarkan sebagai Aset Inventaris (Non-Asset / Beban Operasional).</p>
                        </div>
                    </div>
                </div>

                @error('purchase_price')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Perolehan --}}
            <div>
                <label for="purchase_date" class="block text-sm font-medium text-on-surface mb-1.5">Tanggal Perolehan</label>
                <input type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required
                       class="w-full px-4 py-2.5 rounded-md border border-outline-variant bg-surface-white text-on-surface text-sm
                              focus:outline-none focus:border-primary-light focus:ring-2 focus:ring-primary-light/20 transition-colors
                              @error('purchase_date') border-error @enderror">
                @error('purchase_date')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info Box: Auto-generate code & auto-assign department --}}
            <div class="p-4 rounded-lg bg-surface-container border border-outline-variant/50">
                <div class="space-y-2 text-xs text-on-surface-variant">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-light shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span><strong>Kode Aset</strong> akan di-generate otomatis: <span class="font-mono text-primary-light">AST/GDG-INV/{{ date('m/Y') }}/XXX</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-light shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                        <span><strong>Lokasi Awal:</strong> Gudang Inventaris (otomatis)</span>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4 border-t border-border-light">
                <button type="submit" id="submit-btn"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-md bg-primary text-on-primary text-sm font-semibold
                               hover:bg-primary-light transition-all duration-200 active:scale-[0.98]
                               disabled:bg-surface-dim disabled:text-on-surface-variant disabled:cursor-not-allowed"
                        title="Simpan Aset">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Aset
                </button>
                <a href="{{ route('assets.index') }}"
                   class="px-5 py-2.5 rounded-md border border-outline-variant text-sm font-medium text-on-surface-variant hover:bg-surface-container transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // BR-01: Real-time threshold validation
    function checkThreshold(value) {
        const warning = document.getElementById('threshold-warning');
        const submitBtn = document.getElementById('submit-btn');
        const price = parseFloat(value) || 0;

        if (price > 0 && price < 500000) {
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.title = 'Nilai barang di bawah ambang batas kapitalisasi Rp 500.000';
        } else {
            warning.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.title = 'Simpan Aset';
        }
    }

    // Run on page load for old() values
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.getElementById('purchase_price');
        if (priceInput.value) {
            checkThreshold(priceInput.value);
        }
    });
</script>
@endpush
