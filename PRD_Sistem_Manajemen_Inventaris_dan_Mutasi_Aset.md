# 📄 Product Requirement Document (PRD)
## Sistem Manajemen Inventaris & Mutasi Aset Terpadu

---

### 1. Informasi Dokumen & Ringkasan Eksekutif
* **Nama Dokumen:** Product Requirement Document (PRD) – Sistem Manajemen Inventaris & Mutasi Aset
* **Versi Dokumen:** v1.0.0 (Final Draft)
* **Status:** Siap untuk Tahap Pengembangan (*Ready for Development*)
* **Target Rilis:** Sprint 1 - 3
* **Kategori Sistem:** Enterprise Asset Management
* **Tujuan Utama:** Mendigitalisasi siklus hidup pencatatan aset inventaris, menerapkan validasi batas kapitalisasi aset, memfasilitasi persetujuan mutasi aset antar-departemen berbasis tanda tangan digital, serta menyediakan pengarsipan otomatis untuk kebutuhan audit.

---

### 2. Identifikasi Peran & Matriks Akses (User Roles & Permissions)

| Peran (Role) | Deskripsi Tanggung Jawab | Hak Akses Utama di Sistem |
| :--- | :--- | :--- |
| **User / Departemen Terkait** *(HE, WBIC, PKA, dll.)* | Divisi/bagian operasional yang menggunakan dan mengelola aset secara langsung. | • Mengajukan permohonan kode inventaris baru ke Bagian Keuangan.<br>• Mengonfirmasi & menyetujui penerimaan fisik aset.<br>• Menerbitkan form pengajuan mutasi aset ke departemen lain.<br>• Membubuhkan TTD digital sebagai Penyerah atau Penerima. |
| **Bagian Keuangan** | Verifikator anggaran dan pencatat nilai kapitalisasi barang. | • Menerima tiket permohonan kode inventaris.<br>• Memvalidasi kelayakan harga barang (aturan ambang batas Rp 500.000).<br>• Menginput kode aset ke sistem.<br>• Menempatkan status aset awal ke *Gudang Inventaris*. |
| **Bagian Inventaris** | Pengelola logistik aset, eksekutor perpindahan aset, dan pengelola arsip formulir. | • Memutasi aset dari Gudang Inventaris ke departemen yang bertanggung jawab.<br>• Memvalidasi kelengkapan TTD pada formulir mutasi antar-bagian.<br>• Mengeksekusi mutasi resmi (mengubah penanggung jawab di sistem).<br>• Mengelola dan mengunduh berkas arsip digital formulir mutasi. |
| **Super Admin / Auditor** | Administrator teknis dan pengawas kepatuhan aset perusahaan. | • Mengelola master data (departemen, kategori aset, user).<br>• Melihat riwayat audit (*audit log timeline*) seluruh mutasi aset.<br>• Mengunduh rekap laporan inventaris keseluruhan. |

---

### 3. Aturan Bisnis Inti (Core Business Rules)

* **BR-01: Ambang Batas Nilai Kapitalisasi Aset (Rp 500.000 Threshold)**
  * Barang yang diajukan hanya dapat didaftarkan sebagai *Aset Inventaris* jika nilai perolehannya >= **Rp 500.000**.
  * Jika nilai barang **< Rp 500.000**, sistem otomatis menolak pendaftaran aset inventaris dan mengarahkannya sebagai beban operasional/barang habis pakai (*Non-Asset*).
* **BR-02: Lokasi Masuk Awal (Default Initial Storage)**
  * Semua aset yang baru didaftarkan oleh Bagian Keuangan wajib masuk terlebih dahulu ke entitas **"Gudang Inventaris"** sebelum didistribusikan ke bagian peminta.
* **BR-03: Alur Penyaluran Internal Bagian Inventaris**
  * Apabila aset tersebut dikhususkan untuk operasional Bagian Inventaris sendiri, maka Bagian Inventaris melakukan mutasi dari Gudang Inventaris ke Bagian Inventaris.
* **BR-04: Syarat Sah Persetujuan Ganda (Dual-Approval Mutasi)**
  * Formulir mutasi baru dinyatakan valid dan siap dieksekusi apabila telah di approve secara digital oleh pihak **Penyerah** dan pihak **Penerima**.
* **BR-05: Otomatisasi Pengarsipan Formulir (Digital Archiving)**
  * Setiap mutasi yang telah dieksekusi oleh Bagian Inventaris harus langsung terkunci secara permanen dan otomatis diarsipkan ke modul *Arsip Form* sistem.

---

### 4. Alur Proses & Logika Fitur (Process Workflows)

#### Alur A: Pendaftaran & Penyaluran Kode Aset Baru
```text
[User / Bagian Terkait]
        │
        ▼ (Meminta Kode Inventaris)
[Bagian KEUANGAN]
        │
        ├─► [Cek Nilai Asset < Rp 500.000?] ──► [YA] ──► Ditolak / Tidak Input Asset
        │
        └─► [TIDAK / >= Rp 500.000]
                │
                ▼
        [Bagian KEUANGAN Menginput Kode & Masuk ke "Gudang Inventaris"]
                │
                ▼
        [Bagian INVENTARIS Memutasi dari Gudang Inventaris ke Bagian yg Bertanggung Jawab]
        *(Jika dikelola Bag. Inventaris: Mutasi dari Gudang Inv. ke Bag. Inventaris)*
                │
                ▼
        [Approval & Konfirmasi Penerimaan oleh Bagian yg Bertanggung Jawab]
                │
                ▼
        [SELESAI - Status Aset Aktif Digunakan]
```

#### Alur B: Mutasi Aset Antar-Departemen
```text
[User Bagian Terkait (HE / WBIC / PKA / dll.)]
        │
        ▼ (Menerbitkan FORM Pengajuan Mutasi Asset)
[Mengisi Form: Pilih Asset, Tujuan Mutasi, & Alasan]
        │
        ▼
[Proses Approval & E-Signature: TTD Penyerah & TTD Penerima / AL Penyerahan]
        │
        ▼
[Setelah Disetujui: Bagian INVENTARIS Memindahkan / Mutasi Asset ke Penerima]
        │
        ▼
[Bagian INVENTARIS Mengarsipkan FORM Mutasi (Arsip Form)]
        │
        ▼
[SELESAI - Kepemilikan Resmi Berpindah di Database]
```

---

### 5. Kebutuhan Fungsional Sistem (Functional Requirements)

| ID Modul | Nama Fitur | Kriteria Keberterimaan (Acceptance Criteria) |
| :--- | :--- | :--- |
| **FR-REG-01** | Validasi Ambang Batas Kapitalisasi | Sistem otomatis menghitung input harga. Jika < Rp 500.000, tombol "Simpan Aset" disable / muncul peringatan. |
| **FR-REG-02** | Auto-Generate Kode Aset | Sistem membuat kode unik format: `AST/[KODE-DEPT]/[BULAN]/[TAHUN]/[NO-URUT]`. |
| **FR-MUT-01** | Penerbitan Digital Form Mutasi | Dropdown aset hanya menampilkan barang yang dimiliki departemen penyerah. Tersedia kolom alasan mutasi & kondisi. |
| **FR-MUT-02** | E-Signature & Multi-Approval | Canvas TTD digital untuk Penyerah & Penerima. Tombol eksekusi Bag. Inventaris aktif jika kedua TTD terisi. |
| **FR-MUT-03** | Auto-Archiving & PDF Generation | Setelah eksekusi, sistem membuat file PDF Berita Acara Serah Terima otomatis yang dapat diunduh. |
| **FR-LOG-01** | Asset Audit Trail & Timeline | Perubahan status, pemegang aset, dan mutasi tercatat dalam log audit yang tidak dapat diubah (immutable). |

---

### 6. Rancangan Struktur Data & Database Schema

```sql
-- 1. Master Departemen
CREATE TABLE departments (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(20) UNIQUE NOT NULL, 
    name VARCHAR(100) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Master Pengguna (Users & RBAC)
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    department_id BIGINT,
    role ENUM('user', 'finance', 'inventory', 'admin') NOT NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id)
);

-- 3. Data Utama Aset (Assets)
CREATE TABLE assets (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    asset_code VARCHAR(50) UNIQUE NOT NULL,
    name VARCHAR(150) NOT NULL,
    purchase_price DECIMAL(15, 2) NOT NULL,
    purchase_date DATE NOT NULL,
    current_department_id BIGINT NOT NULL, 
    status ENUM('in_storage', 'active', 'under_repair', 'disposed') DEFAULT 'in_storage',
    created_by_user_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (current_department_id) REFERENCES departments(id)
);

-- 4. Formulir Mutasi (Mutation Forms)
CREATE TABLE mutation_forms (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    form_number VARCHAR(50) UNIQUE NOT NULL,
    from_department_id BIGINT NOT NULL,
    to_department_id BIGINT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('draft', 'waiting_receiver', 'ready_for_execution', 'archived', 'rejected') DEFAULT 'draft',
    sender_user_id BIGINT NOT NULL,
    receiver_user_id BIGINT NULL,
    executed_by_user_id BIGINT NULL,
    archived_pdf_path VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (from_department_id) REFERENCES departments(id),
    FOREIGN KEY (to_department_id) REFERENCES departments(id)
);

-- 5. Detail Barang yang Dimutasi (Mutation Items)
CREATE TABLE mutation_items (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    mutation_form_id BIGINT NOT NULL,
    asset_id BIGINT NOT NULL,
    item_condition ENUM('good', 'damaged_light', 'damaged_heavy') DEFAULT 'good',
    FOREIGN KEY (mutation_form_id) REFERENCES mutation_forms(id) ON DELETE CASCADE,
    FOREIGN KEY (asset_id) REFERENCES assets(id)
);

-- 6. Log Riwayat Pergerakan Aset (Asset Mutation Audit Trail)
CREATE TABLE asset_histories (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    asset_id BIGINT NOT NULL,
    action_type ENUM('registration', 'initial_dispatch', 'department_mutation', 'repair', 'disposal') NOT NULL,
    from_department_id BIGINT NULL,
    to_department_id BIGINT NOT NULL,
    actor_user_id BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (asset_id) REFERENCES assets(id),
    FOREIGN KEY (to_department_id) REFERENCES departments(id)
);
```

---

### 7. Kebutuhan Non-Fungsional (Non-Functional Requirements)

1. **Integritas Data & Audit Trail:** Seluruh perpindahan barang dari Gudang Inventaris maupun antar-departemen harus memiliki catatan riwayat yang *immutable* (tidak dapat diedit/dihapus secara manual dari antarmuka).
2. **Validasi File Tanda Tangan:** Tanda tangan digital disimpan dalam format *vector base64/PNG* terenkripsi dengan penyematan waktu otomatis (*timestamp*).
3. **Performa Laporan:** Pembuatan berkas PDF arsip formulir mutasi harus selesai dalam waktu kurang dari 2 detik per dokumen.
4. **Responsivitas Antarmuka:** Tampilan formulir persetujuan dan tanda tangan harus kompatibel dengan perangkat mobile/tablet agar memudahkan verifikasi fisik barang langsung di lapangan.

---

### 8. Rencana Implementasi (Sprint Roadmap)

* **Sprint 1 (Fondasi & Registrasi Aset):** Setup arsitektur, Role & Permission, Master Departemen & User, Modul Request Kode Aset ke Keuangan, serta validasi logika threshold Rp 500.000.
* **Sprint 2 (Penyaluran Gudang & Form Mutasi):** Alur penyaluran dari Gudang Inventaris, perancangan form mutasi dinamis, dan integrasi modul TTD digital (Penyerah & Penerima).
* **Sprint 3 (Eksekusi, Arsip & Audit Log):** Dashboard eksekusi Bagian Inventaris, auto-generate Berita Acara PDF, modul Arsip Form, dan pengujian alur kerja (*User Acceptance Testing*).
