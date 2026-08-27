{{-- Modal Panduan Alur Sistem Terpadu (WBI Inventaris - DESIGN.md Compliant) --}}
<div id="system-guide-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4 sm:p-6 md:p-8 lg:p-12 hidden transition-opacity overflow-y-auto" onclick="if(event.target === this) closeSystemGuideModal()">
    <div class="bg-surface rounded-2xl border border-border-light max-w-4xl w-full max-h-[85vh] my-auto flex flex-col shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        {{-- Modal Header --}}
        <div class="px-6 sm:px-8 py-4.5 bg-surface-container-lowest border-b border-border-light flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center font-bold shadow-soft shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-display text-lg sm:text-xl font-bold text-on-surface">Panduan Lengkap Alur &amp; SOP Inventaris</h3>
                    <p class="font-body text-xs sm:text-sm text-on-surface-variant mt-0.5">Panduan resmi alur kode aset, mutasi barang, dan peran pengguna.</p>
                </div>
            </div>
            <button type="button" onclick="closeSystemGuideModal()"
                    class="p-2 text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high rounded-xl transition-colors cursor-pointer"
                    aria-label="Tutup panduan">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Segmented Tabs Bar (Material Design 3 Style) --}}
        <div class="px-6 sm:px-8 py-3 bg-surface-container-low border-b border-border-light shrink-0">
            <div class="p-1 bg-surface-container rounded-xl flex items-center gap-1 overflow-x-auto scrollbar-none text-xs sm:text-sm">
                <button type="button" onclick="switchGuideTab('kode-aset')" id="tab-btn-kode-aset"
                        class="guide-tab-btn px-4 py-2 rounded-lg font-bold transition-all bg-primary text-on-primary shadow-soft shrink-0 cursor-pointer">
                    1. Flow Kode Aset
                </button>
                <button type="button" onclick="switchGuideTab('mutasi-aset')" id="tab-btn-mutasi-aset"
                        class="guide-tab-btn px-4 py-2 rounded-lg font-medium text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-all shrink-0 cursor-pointer">
                    2. Flow Mutasi Aset
                </button>
                <button type="button" onclick="switchGuideTab('peran-user')" id="tab-btn-peran-user"
                        class="guide-tab-btn px-4 py-2 rounded-lg font-medium text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-all shrink-0 cursor-pointer">
                    3. Peran &amp; Tugas
                </button>
                <button type="button" onclick="switchGuideTab('template-pesan')" id="tab-btn-template-pesan"
                        class="guide-tab-btn px-4 py-2 rounded-lg font-medium text-on-surface-variant hover:text-on-surface hover:bg-surface-container-high transition-all shrink-0 cursor-pointer">
                    4. Format Pengajuan ke Keuangan
                </button>
            </div>
        </div>

        {{-- Modal Body (Scrollable) --}}
        <div class="p-6 sm:p-8 overflow-y-auto space-y-6 text-sm text-on-surface font-body">

            {{-- TAB 1: FLOW KODE ASET --}}
            <div id="guide-tab-kode-aset" class="guide-tab-content space-y-5">
                <div class="p-5 rounded-2xl bg-primary-surface/30 border border-primary-muted/40">
                    <p class="font-display font-bold text-primary text-base mb-1.5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Prinsip Registrasi Kode Aset Baru
                    </p>
                    <p class="text-on-surface-variant leading-relaxed text-sm">
                        Hanya barang dengan nilai perolehan <strong>&ge; Rp 500.000</strong> yang didaftarkan sebagai Aset Inventaris. Barang yang baru diinput Bagian Keuangan otomatis masuk ke <strong class="text-on-surface">Gudang Inventaris [GDG-INV]</strong> sebelum disalurkan ke unit kerja penanggung jawab.
                    </p>
                </div>

                {{-- Visual Step Sequence --}}
                <div class="space-y-3.5">
                    <div class="flex items-start gap-4 p-4.5 sm:p-5 rounded-2xl border border-border-light bg-surface-container-lowest shadow-soft">
                        <span class="w-8 h-8 rounded-xl bg-surface-container text-on-surface font-bold font-mono flex items-center justify-center shrink-0 text-sm mt-0.5">1</span>
                        <div>
                            <p class="font-display font-bold text-on-surface text-base">Permintaan Kode dari User</p>
                            <p class="mt-1 text-sm text-on-surface-variant leading-relaxed">
                                User atau unit kerja yang baru membeli barang mengajukan permohonan kode inventaris kepada <strong class="text-on-surface">Bagian Keuangan</strong> beserta bukti nota/kuitansi pembelian.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4.5 sm:p-5 rounded-2xl border border-border-light bg-surface-container-lowest shadow-soft">
                        <span class="w-8 h-8 rounded-xl bg-surface-container text-on-surface font-bold font-mono flex items-center justify-center shrink-0 text-sm mt-0.5">2</span>
                        <div class="w-full">
                            <p class="font-display font-bold text-on-surface text-base">Analisis &amp; Pengecekan Nilai oleh Keuangan</p>
                            <p class="mt-1 text-sm text-on-surface-variant leading-relaxed">
                                Bagian Keuangan memeriksa nilai aset:
                            </p>
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs sm:text-sm">
                                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-danger">
                                    <p class="font-bold mb-1">Nilai &lt; Rp 500.000:</p>
                                    <p class="leading-relaxed">Dikategorikan <em>Non-Asset / Biaya Operasional</em> biasa. Tidak diinput ke inventaris dan proses selesai.</p>
                                </div>
                                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-success">
                                    <p class="font-bold mb-1">Nilai &ge; Rp 500.000:</p>
                                    <p class="leading-relaxed">Memenuhi syarat. Keuangan menginput data aset ke sistem.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4.5 sm:p-5 rounded-2xl border border-border-light bg-surface-container-lowest shadow-soft">
                        <span class="w-8 h-8 rounded-xl bg-surface-container text-on-surface font-bold font-mono flex items-center justify-center shrink-0 text-sm mt-0.5">3</span>
                        <div>
                            <p class="font-display font-bold text-on-surface text-base">Penerbitan Kode &amp; Masuk Gudang Inventaris</p>
                            <p class="mt-1 text-sm text-on-surface-variant leading-relaxed">
                                Sistem menerbitkan kode aset resmi (contoh: <code class="bg-surface-container px-2 py-0.5 rounded font-mono font-bold text-primary">AST/GDG-INV/08/2026/001</code>) dan aset otomatis berstatus <code>in_storage</code> di <strong class="text-on-surface">Gudang Inventaris</strong>.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4.5 sm:p-5 rounded-2xl border border-border-light bg-surface-container-lowest shadow-soft">
                        <span class="w-8 h-8 rounded-xl bg-surface-container text-on-surface font-bold font-mono flex items-center justify-center shrink-0 text-sm mt-0.5">4</span>
                        <div>
                            <p class="font-display font-bold text-on-surface text-base">Penyaluran dari Gudang ke Unit Kerja</p>
                            <p class="mt-1 text-sm text-on-surface-variant leading-relaxed">
                                Bagian Inventaris membuat formulir mutasi untuk menyalurkan aset dari Gudang Inventaris ke unit kerja peminta/penanggung jawab.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4.5 sm:p-5 rounded-2xl border border-primary-muted/40 bg-primary-surface/20 shadow-soft">
                        <span class="w-8 h-8 rounded-xl bg-primary text-on-primary font-bold font-mono flex items-center justify-center shrink-0 text-sm mt-0.5 shadow-soft">5</span>
                        <div>
                            <p class="font-display font-bold text-primary text-base">Approval Penerima &amp; Eksekusi Selesai</p>
                            <p class="mt-1 text-sm text-on-surface-variant leading-relaxed">
                                Unit penanggung jawab menyetujui mutasi secara digital. Bagian Inventaris mengeksekusi serah terima fisik &amp; sistem. Aset resmi aktif (<code>active</code>) di unit tersebut.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 2: FLOW MUTASI ASET --}}
            <div id="guide-tab-mutasi-aset" class="guide-tab-content space-y-5 hidden">
                <div class="p-5 rounded-2xl bg-amber-50/70 border border-secondary-light/60">
                    <p class="font-display font-bold text-secondary text-base mb-1.5 flex items-center gap-2">
                        <svg class="w-5 h-5 text-secondary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Prinsip Dual-Approval &amp; Pengarsipan Berita Acara
                    </p>
                    <p class="text-on-surface-variant leading-relaxed text-sm">
                        Pemindahan aset antar-departemen memerlukan persetujuan ganda (<em>Dual Approval</em>) dari pihak pengirim dan penerima sebelum Bagian Inventaris mengeksekusi pemindahan dan mengarsipkan Berita Acara.
                    </p>
                </div>

                {{-- Visual Step Sequence --}}
                <div class="space-y-3.5">
                    <div class="flex items-start gap-4 p-4.5 sm:p-5 rounded-2xl border border-border-light bg-surface-container-lowest shadow-soft">
                        <span class="w-8 h-8 rounded-xl bg-surface-container text-on-surface font-bold font-mono flex items-center justify-center shrink-0 text-sm mt-0.5">1</span>
                        <div>
                            <p class="font-display font-bold text-on-surface text-base">Inisiasi Form &amp; Persetujuan Pengirim</p>
                            <p class="mt-1 text-sm text-on-surface-variant leading-relaxed">
                                Unit asal membuka menu <strong class="text-on-surface">Mutasi Aset &rarr; Ajukan Mutasi Baru</strong>, memilih departemen penerima, mencentang barang yang dipindahkan, serta menandatangani persetujuan pengirim secara digital.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4.5 sm:p-5 rounded-2xl border border-border-light bg-surface-container-lowest shadow-soft">
                        <span class="w-8 h-8 rounded-xl bg-surface-container text-on-surface font-bold font-mono flex items-center justify-center shrink-0 text-sm mt-0.5">2</span>
                        <div>
                            <p class="font-display font-bold text-on-surface text-base">Verifikasi &amp; Approval Unit Penerima</p>
                            <p class="mt-1 text-sm text-on-surface-variant leading-relaxed">
                                Unit penerima menerima notifikasi mutasi masuk, memeriksa kondisi fisik barang, lalu mengklik tombol <strong class="text-on-surface">"Setujui Mutasi (Approval Penerima)"</strong>. Status berubah menjadi <em>Siap Eksekusi</em>.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4.5 sm:p-5 rounded-2xl border border-border-light bg-surface-container-lowest shadow-soft">
                        <span class="w-8 h-8 rounded-xl bg-surface-container text-on-surface font-bold font-mono flex items-center justify-center shrink-0 text-sm mt-0.5">3</span>
                        <div>
                            <p class="font-display font-bold text-on-surface text-base">Eksekusi Pemindahan oleh Bagian Inventaris</p>
                            <p class="mt-1 text-sm text-on-surface-variant leading-relaxed">
                                Bagian Inventaris memverifikasi bahwa kedua pihak telah setuju, lalu mengklik tombol <strong class="text-on-surface">"Pindahkan Aset &amp; Arsip Form"</strong>. Kepemilikan aset resmi beralih di sistem.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4.5 sm:p-5 rounded-2xl border border-primary-muted/40 bg-primary-surface/20 shadow-soft">
                        <span class="w-8 h-8 rounded-xl bg-primary text-on-primary font-bold font-mono flex items-center justify-center shrink-0 text-sm mt-0.5 shadow-soft">4</span>
                        <div>
                            <p class="font-display font-bold text-primary text-base">Arsip Berita Acara &amp; Audit Trail Lengkap</p>
                            <p class="mt-1 text-sm text-on-surface-variant leading-relaxed">
                                Sistem otomatis mengarsipkan Berita Acara Serah Terima Aset (bisa dicetak/disimpan sebagai PDF) dan mencatat riwayat pemindahan secara permanen di audit log.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TAB 3: PERAN & TUGAS --}}
            <div id="guide-tab-peran-user" class="guide-tab-content space-y-4 hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Role 1: User Unit --}}
                    <div class="p-5 rounded-2xl border border-border-light bg-surface-container-lowest space-y-2.5 shadow-soft">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-lg bg-surface-container font-mono text-xs font-bold text-on-surface">ROLE: USER</span>
                            <h4 class="font-display font-bold text-on-surface text-base">User / Staf Departemen</h4>
                        </div>
                        <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                            <li>Memeriksa daftar inventaris yang dipegang unit kerja Anda.</li>
                            <li>Menyetujui permohonan mutasi barang yang masuk ke unit Anda (<em>Approval Button</em>).</li>
                            <li>Mengajukan pemindahan barang ke departemen lain bila diperlukan.</li>
                            <li>Mengajukan permohonan kode aset ke Keuangan bila ada pengadaan barang baru.</li>
                        </ul>
                    </div>

                    {{-- Role 2: Keuangan --}}
                    <div class="p-5 rounded-2xl border border-border-light bg-surface-container-lowest space-y-2.5 shadow-soft">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-lg bg-primary-surface/50 font-mono text-xs font-bold text-primary">ROLE: FINANCE</span>
                            <h4 class="font-display font-bold text-on-surface text-base">Bagian Keuangan</h4>
                        </div>
                        <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                            <li>Menerima permohonan kode aset dari unit kerja.</li>
                            <li>Mengecek batas nilai perolehan (&ge; Rp 500.000).</li>
                            <li>Menginput registrasi aset baru ke sistem (otomatis masuk Gudang Inventaris).</li>
                            <li>Memantau total valuasi dan nilai aset kampus.</li>
                        </ul>
                    </div>

                    {{-- Role 3: Inventaris --}}
                    <div class="p-5 rounded-2xl border border-border-light bg-surface-container-lowest space-y-2.5 shadow-soft">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-lg bg-secondary-container/30 font-mono text-xs font-bold text-secondary">ROLE: INVENTORY</span>
                            <h4 class="font-display font-bold text-on-surface text-base">Bagian Inventaris</h4>
                        </div>
                        <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                            <li>Mengelola stok barang baru di Gudang Inventaris.</li>
                            <li>Menyalurkan aset dari Gudang ke unit penanggung jawab.</li>
                            <li>Mengeksekusi formulir mutasi yang telah disetujui Pengirim &amp; Penerima.</li>
                            <li>Mengarsipkan dan mencetak dokumen resmi Berita Acara Serah Terima.</li>
                        </ul>
                    </div>

                    {{-- Role 4: Admin --}}
                    <div class="p-5 rounded-2xl border border-border-light bg-surface-container-lowest space-y-2.5 shadow-soft">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 rounded-lg bg-surface-container-highest font-mono text-xs font-bold text-on-surface">ROLE: ADMIN</span>
                            <h4 class="font-display font-bold text-on-surface text-base">Administrator Sistem</h4>
                        </div>
                        <ul class="list-disc list-inside space-y-1.5 text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                            <li>Monitoring audit trail dan log pergerakan aset.</li>
                            <li>Kelola hak akses pengguna dan departemen kampus.</li>
                            <li>Akses penuh ke semua modul dan pelaporan aset.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- TAB 4: TEMPLATE PESAN PENGADAAN --}}
            <div id="guide-tab-template-pesan" class="guide-tab-content space-y-4 hidden">
                <div class="p-6 rounded-2xl bg-surface-container-lowest border border-border-light space-y-3.5 shadow-soft">
                    <p class="font-display font-bold text-on-surface text-base">Template Permohonan Kode Aset ke Bagian Keuangan</p>
                    <p class="text-xs sm:text-sm text-on-surface-variant leading-relaxed">
                        Salin format pesan di bawah ini untuk dikirimkan via WhatsApp / Memo / Email ke Bagian Keuangan saat unit Anda membeli barang baru (&ge; Rp 500.000):
                    </p>

                    <div class="p-4 rounded-xl bg-surface-container-low border border-border-light font-mono text-xs sm:text-sm text-on-surface space-y-1 leading-relaxed">
                        <p id="system-template-text">Yth. Bagian Keuangan Politeknik WBI,&#13;&#10;Kami dari Unit {{ auth()->user()->department ? auth()->user()->department->name : '[Nama Unit]' }} mengajukan pencatatan aset inventaris baru:&#13;&#10;&#13;&#10;1. Nama / Merk Barang: [Contoh: Laptop Asus ExpertBook B1400]&#13;&#10;2. Harga Beli / Perolehan: Rp [Nominal &ge; 500.000]&#13;&#10;3. Tanggal Pembelian: [Tanggal / Bulan / Tahun]&#13;&#10;4. Bukti Nota Pembelian: (Terlampir Foto / Invoice)&#13;&#10;&#13;&#10;Mohon bantuannya untuk diproses nomor kode aset inventaris pada sistem. Terima kasih.&#13;&#10;Salam,&#13;&#10;{{ auth()->user()->name }}</p>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <button type="button" onclick="copySystemTemplateText()"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary text-on-primary text-xs sm:text-sm font-bold hover:bg-primary-light transition-all shadow-soft cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                            Salin Format Pesan ke Clipboard
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- Modal Footer --}}
        <div class="px-6 sm:px-8 py-4 bg-surface-container-low border-t border-border-light flex items-center justify-between shrink-0">
            <span class="text-xs text-on-surface-variant font-mono">WBI Asset Management System</span>
            <button type="button" onclick="closeSystemGuideModal()"
                    class="px-4 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-xs sm:text-sm font-bold text-on-surface transition-colors cursor-pointer">
                Tutup Panduan
            </button>
        </div>


    </div>
</div>

<script>
function openSystemGuideModal(tab = 'kode-aset') {
    const modal = document.getElementById('system-guide-modal');
    if (modal) {
        modal.classList.remove('hidden');
        switchGuideTab(tab);
    }
}

function closeSystemGuideModal() {
    const modal = document.getElementById('system-guide-modal');
    if (modal) modal.classList.add('hidden');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSystemGuideModal();
    }
});

function switchGuideTab(tabKey) {
    document.querySelectorAll('.guide-tab-content').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.guide-tab-btn').forEach(btn => {
        btn.classList.remove('bg-primary', 'text-on-primary', 'shadow-soft', 'font-bold');
        btn.classList.add('text-on-surface-variant', 'font-medium');
    });

    const activeContent = document.getElementById('guide-tab-' + tabKey);
    const activeBtn = document.getElementById('tab-btn-' + tabKey);

    if (activeContent) activeContent.classList.remove('hidden');
    if (activeBtn) {
        activeBtn.classList.remove('text-on-surface-variant', 'font-medium');
        activeBtn.classList.add('bg-primary', 'text-on-primary', 'shadow-soft', 'font-bold');
    }
}

function copySystemTemplateText() {
    const textEl = document.getElementById('system-template-text');
    if (!textEl) return;

    const text = textEl.innerText || textEl.textContent;
    navigator.clipboard.writeText(text).then(() => {
        if (typeof window.showToast === 'function') {
            window.showToast('Format permohonan berhasil disalin ke clipboard!', 'success');
        } else {
            alert('Format pesan permohonan berhasil disalin!');
        }
    }).catch(() => {
        alert('Silakan salin teks secara manual.');
    });
}
</script>
